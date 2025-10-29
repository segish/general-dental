<?php

namespace App\Http\Controllers\admin;

use App\Models\Test;
use App\Models\TestCategory;
use App\Models\SpecimenType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;

class TestController extends Controller
{
    function __construct(
        private Test $test,
    ) {
        $this->middleware('checkAdminPermission:test.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:test.add-new,index')->only(['index']);
    }

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $testCategories = TestCategory::all();
        $specimenTypes = SpecimenType::all();
        return view('admin-views.test-types.index', compact('roles', 'testCategories', 'specimenTypes'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $category = $request['category'];
        $status = $request['status'];

        $query = $this->test->join('test_categories', 'test_categories.id', '=', 'tests.test_category_id')
            ->select('tests.*', 'test_categories.name as category_name');

        // Apply search filter
        if ($request->has('search') && !empty($search)) {
            $key = explode(' ', $search);
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('tests.id', 'like', "%{$value}%")
                        ->orWhere('tests.title', 'like', "%{$value}%")
                        ->orWhere('tests.test_name', 'like', "%{$value}%")
                        ->orWhere('tests.description', 'like', "%{$value}%")
                        ->orWhere('test_categories.name', 'like', "%{$value}%");
                }
            });
        }

        // Apply category filter
        if ($request->has('category') && !empty($category)) {
            $query->where('tests.test_category_id', $category);
        }

        // Apply status filter
        if ($request->has('status') && !empty($status)) {
            $query->where('tests.is_active', $status);
        }

        $query->orderBy('tests.created_at', 'desc');

        $query_param = [
            'search' => $search,
            'category' => $category,
            'status' => $status
        ];

        $tests = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        $testCategories = TestCategory::all();

        return view('admin-views.test-types.list', compact('tests', 'search', 'category', 'status', 'testCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.test-types.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'test_name' => 'required|string|max:255|unique:tests,test_name',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0|max:9999999.99',
            'time_taken_hour' => 'required|integer|min:0|max:23',
            'time_taken_min' => 'required|integer|min:0|max:59',
            'test_category_id' => 'required|exists:test_categories,id',
            'specimen_type_id' => 'nullable|exists:specimen_types,id',
            'result_source' => 'required|in:machine,manual',
            'result_type' => 'required|in:multi-type,numeric,text,other',
            'paper_size' => 'required|in:A4,A5,Letter,Legal',
            'paper_orientation' => 'required|in:portrait,landscape',
            'page_display' => 'required|in:single,group',
            'additional_notes' => 'nullable|string',
        ]);

        try {
            // Create and save the new test record
            $test = new Test([
                'test_name' => $request->get('test_name'),
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'cost' => $request->get('cost'),
                'time_taken_hour' => $request->get('time_taken_hour'),
                'time_taken_min' => $request->get('time_taken_min'),
                'test_category_id' => $request->get('test_category_id'),
                'specimen_type_id' => $request->get('specimen_type_id'),
                'result_source' => $request->get('result_source'),
                'result_type' => $request->get('result_type'),
                'paper_size' => $request->get('paper_size'),
                'paper_orientation' => $request->get('paper_orientation'),
                'page_display' => $request->get('page_display'),
                'additional_notes' => $request->get('additional_notes'),
                'is_active' => 1,
            ]);

            $test->save();

            Toastr::success(translate('Test Saved successfully!'));
            return redirect()->route('admin.test.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('admin-views.test-types.show', compact('role', 'rolePermissions'));
    }



    public function edit($id)
    {
        $testType = Test::find($id);
        $testCategories = TestCategory::all();
        $specimenTypes = SpecimenType::all();
        return view('admin-views.test-types.edit', compact('testType', 'testCategories', 'specimenTypes'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'test_name' => 'required|string|max:255|unique:tests,test_name,' . $id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0|max:9999999.99',
            'time_taken_hour' => 'required|integer|min:0|max:23',
            'time_taken_min' => 'required|integer|min:0|max:59',
            'result_type' => 'required|in:multi-type,numeric,text,other',
            'test_category_id' => 'required|exists:test_categories,id',
            'specimen_type_id' => 'nullable|exists:specimen_types,id',
            'result_source' => 'required|in:machine,manual',
            'paper_size' => 'required|in:A4,A5',
            'paper_orientation' => 'required|in:portrait,landscape',
            'page_display' => 'required|in:single,group',
            'additional_notes' => 'nullable|string',
        ]);

        try {
            $testType = Test::findOrFail($id);

            // Update test fields
            $testType->test_name = $request->test_name;
            $testType->title = $request->title;
            $testType->description = $request->description;
            $testType->cost = $request->cost;
            $testType->time_taken_hour = $request->time_taken_hour;
            $testType->time_taken_min = $request->time_taken_min;
            $testType->result_type = $request->result_type;
            $testType->test_category_id = $request->test_category_id;
            $testType->specimen_type_id = $request->specimen_type_id;
            $testType->result_source = $request->result_source;
            $testType->paper_size = $request->paper_size;
            $testType->paper_orientation = $request->paper_orientation;
            $testType->page_display = $request->page_display;
            $testType->additional_notes = $request->additional_notes;
            $testType->is_active = $request->is_active;


            // Save the updated test
            $testType->save();

            Toastr::success(translate('Test Type Updated successfully!'));
            return redirect()->route('admin.test.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $testType = Test::findOrFail($id);

            $testType->delete();
            Toastr::success(translate('Test Type Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::success($e->getMessage());
        }
    }
}

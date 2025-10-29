<?php

namespace App\Http\Controllers\admin;

use App\Models\Radiology;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;

class RadiologyController extends Controller
{
    function __construct(
        private Radiology $radiology,
    ) {
        $this->middleware('checkAdminPermission:radiology.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:radiology.add-new,index')->only(['index']);
    }

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $testCategories = TestCategory::all();
        return view('admin-views.radiology-types.index', compact('roles', 'testCategories'));
    }

    public function list(Request $request): Factory|View|Application
    {

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->radiology->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('title', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->radiology->latest();
        }
        $radiologys = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.radiology-types.list', compact('radiologys', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.radiology-types.create', compact('permission'));
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
            'radiology_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'cost' => 'required|numeric|min:0|max:9999999.99',
            'time_taken_hour' => 'required|integer|min:0|max:23',
            'time_taken_min' => 'required|integer|min:0|max:59',
            'paper_size' => 'required|in:A4,A5',
            'is_inhouse' => 'required|boolean',
            'paper_orientation' => 'required|in:portrait,landscape',
            'is_active' => 'required|boolean',
        ]);

        try {
            // Create and save the new radiology record
            $radiology = new Radiology([
                'radiology_name' => $request->get('radiology_name'),
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'additional_notes' => $request->get('additional_notes'),
                'cost' => $request->get('cost'),
                'time_taken_hour' => $request->get('time_taken_hour'),
                'time_taken_min' => $request->get('time_taken_min'),
                'paper_size' => $request->get('paper_size'),
                'is_inhouse' => $request->get('is_inhouse'),
                'paper_orientation' => $request->get('paper_orientation'),
                'is_active' => $request->get('is_active'),
            ]);

            $radiology->save();

            Toastr::success(translate('Radiology Saved successfully!'));
            return redirect()->route('admin.radiology.list');
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

        return view('admin-views.radiology-types.show', compact('role', 'rolePermissions'));
    }



    public function edit($id)
    {
        $radiology = Radiology::find($id);
        $testCategories = TestCategory::all();
        return view('admin-views.radiology-types.edit', compact('radiology', 'testCategories'));
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
            'radiology_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'cost' => 'required|numeric|min:0|max:9999999.99',
            'time_taken_hour' => 'required|integer|min:0|max:23',
            'time_taken_min' => 'required|integer|min:0|max:59',
            'paper_size' => 'required|in:A4,A5',
            'is_inhouse' => 'required|boolean',
            'paper_orientation' => 'required|in:portrait,landscape',
            'is_active' => 'required|boolean',
        ]);

        try {
            $radiology = Radiology::findOrFail($id);

            // Update radiology fields
            $radiology->radiology_name = $request->radiology_name;
            $radiology->title = $request->title;
            $radiology->description = $request->description;
            $radiology->additional_notes = $request->additional_notes;
            $radiology->cost = $request->cost;
            $radiology->time_taken_hour = $request->time_taken_hour;
            $radiology->time_taken_min = $request->time_taken_min;
            $radiology->paper_size = $request->paper_size;
            $radiology->is_inhouse = $request->is_inhouse;
            $radiology->paper_orientation = $request->paper_orientation;
            $radiology->is_active = $request->is_active;

            // Save the updated radiology
            $radiology->save();

            Toastr::success(translate('Radiology Updated successfully!'));
            return redirect()->route('admin.radiology.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
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
            $testType = Radiology::findOrFail($id);

            $testType->delete();
            Toastr::success(translate('Radiology Type Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::success($e->getMessage());
        }
    }
}

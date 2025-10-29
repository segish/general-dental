<?php
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\TestCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;

class TestCategoryController extends Controller
{
    function __construct(
        private TestCategory $testCategory,
    ) {
        $this->middleware('checkAdminPermission:test_category.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:test_category.add-new,index')->only(['index']);
    }

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin-views.test-category.index', compact('roles'));
    }

    public function list(Request $request): Factory|View|Application
    {

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->testCategory->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->testCategory->latest();
        }
        $testCategories = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.test-category.list', compact('testCategories', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.test-category.create', compact('permission'));
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
            'name' => 'required|string|max:255|unique:test_categories,name',
            'description' => 'nullable|string',
        ]);

        try {

            $testCategory = new TestCategory([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ]);
            $testCategory->save();
            Toastr::success(translate('Test Category Saved successfully!'));
            return redirect()->route('admin.test_category.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));

        }
        // Redirect back with a success message
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

        return view('admin-views.test-category.show', compact('role', 'rolePermissions'));
    }



    public function edit($id)
    {
        $testCategory = TestCategory::find($id);


        return view('admin-views.test-category.edit', compact('testCategory'));
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
            'name' => 'required|string|max:255|unique:test_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        try {
            $testCategory = TestCategory::findOrFail($id);

            $testCategory->name = $request->get('name');
            $testCategory->description = $request->get('description');
            $testCategory->save();

            Toastr::success(translate('Test Category Updated successfully!'));
            return redirect()->route('admin.test_category.list');
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
            $testCategory = TestCategory::findOrFail($id);

            $testCategory->delete();
            Toastr::success(translate('Test Category Deleted Successfully!'));
            return back();

        } catch (\Exception $e) {
            Toastr::success($e->getMessage());

        }
    }
}

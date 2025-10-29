<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use App\Models\Department;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;

class DepartmentController extends Controller
{
    function __construct(
        private Department $department,
    ) {
        $this->middleware('checkAdminPermission:department.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:department.add-new,index')->only(['index']);

    }

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();

        return view('admin-views.departments.index', compact('roles'));

    }

    public function list(Request $request): Factory|View|Application
    {

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->department->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->department->latest();
        }
        $departments = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.departments.list', compact('departments', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.departments.create', compact('permission'));
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
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Make image nullable
        ]);

        try {
            // Create a new department instance
            $department = new Department([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ]);

            // Handle the image upload if an image is provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = now()->format('YmdHis') . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('department_images', $imageName, 'public');
                $department->image = $imagePath; // Assign the image path to the department
            }

            // Save the department
            $department->save();

            Toastr::success(translate('Department saved successfully!'));
            return redirect()->route('admin.department.list');

        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput(); // Redirect back with the old input
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

        return view('admin-views.departments.show', compact('role', 'rolePermissions'));
    }



    public function edit($id)
    {
        $department = Department::find($id);


        return view('admin-views.departments.edit', compact('department'));
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
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $department = Department::findOrFail($id);

            $department->name = $request->get('name');
            $department->description = $request->get('description');

            if ($request->hasFile('image')) {
                // Delete existing image if any
                Storage::disk('public')->delete($department->image);

                // Store the new image
                $imagePath = $request->file('image')->store('department_images', 'public');
                $department->image = $imagePath;
            }

            $department->save();

            Toastr::success(translate('Department Updated successfully!'));
            return redirect()->route('admin.department.list');
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
            $department = Department::findOrFail($id);
            $department->delete();
            Toastr::success(translate('Department Deleted Successfully!'));
            return back();

        } catch (\Exception $e) {
            Toastr::success($e->getMessage());

        }
    }
}

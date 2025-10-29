<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(
        private Role $role
    )
    {
        
        $this->middleware('checkAdminPermission:roles.add-new,index')->only(['index','branch_index']);
        $this->middleware('checkAdminPermission:roles.list,list')->only(['list']);

    }

  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     
    public function branch_index(Request $request)
    {
        // $permission = Permission::get();
        $guard = 'branch';
        $permissions = DB::table('permissions')->where('guard_name', 'branch')
            ->get();

        // Group permissions by their group name
        $permission = $permissions->groupBy('group');
            return view('admin-views.roles.index',compact('permission','guard'));

    }

    public function index(Request $request)
    {
        // $permission = Permission::get();
        $guard = 'admin';

        $permissions = DB::table('permissions')->where('guard_name', 'admin')
            ->get();

        // Group permissions by their group name
        $permission = $permissions->groupBy('group');
            return view('admin-views.roles.index',compact('permission','guard'));

    }

    public function list(Request $request): Factory|View|Application
    {
       
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->role->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->role->latest();
        }
        $roles = $query->paginate(Helpers::pagination_limit())->appends($query_param);
         return view('admin-views.roles.list', compact('roles', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.roles.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
           // Add validation for guard_name
            'permission' => 'required',
        ]);
    
        try {
            $role = $this->role->create([
                'guard_name' => $request->guard_name, // Use the selected guard
                'name' => $request->name,
            ]);
    
            $permissionsAsNumbers = array_map('intval', $request->input('permission'));
    
            $role->syncPermissions($permissionsAsNumbers);
    
            return response()->json([], 200);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 400);
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
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        return view('admin-views.roles.show',compact('role','rolePermissions'));
    }

 

    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::where('guard_name','admin')->get();

        // Fetch the user's role permissions
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id')
            ->all();

        // Fetch the user's permission groups
        $userGroups = array_unique(array_map(function ($permission) {
            [$group] = array_pad(explode('.', $permission, 1), 1, null);
            return $group;
        }, $rolePermissions));

        // Fetch the user's individual permissions
        $userPermissions = $rolePermissions;

        // Group permissions by their groups
        $groupedPermissions = $permissions->groupBy('group');

        return view('admin-views.roles.edit', compact('role', 'groupedPermissions', 'userGroups', 'userPermissions'));
    }

    public function branch_edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::where('guard_name','branch')->get();

        // Fetch the user's role permissions
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id')
            ->all();

        // Fetch the user's permission groups
        $userGroups = array_unique(array_map(function ($permission) {
            [$group] = array_pad(explode('.', $permission, 1), 1, null);
            return $group;
        }, $rolePermissions));

        // Fetch the user's individual permissions
        $userPermissions = $rolePermissions;

        // Group permissions by their groups
        $groupedPermissions = $permissions->groupBy('group');

        return view('admin-views.roles.edit', compact('role', 'groupedPermissions', 'userGroups', 'userPermissions'));
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
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        try {
            $role = Role::find($id);
            $role->name = $request->input('name');

            $role->save();

            $permissionsAsNumbers = array_map('intval', $request->input('permission'));

            $role->syncPermissions($permissionsAsNumbers);

            return response()->json([], 200);
        }catch (\Exception $e) {
            return response()->json(['error' => $e],400);
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
        $role = Role::findOrFail($id);

        // Delete the role
        $role->delete();
        Toastr::success(translate('Role Deleted!'));
        return back();
    }
}
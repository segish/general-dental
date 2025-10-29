<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;

class PermissionsController extends Controller
{

    function __construct(
        private Permission $permission
    )
     {

            
        $this->middleware('checkAdminPermission:permissions.add-new,index')->only(['index']);
        $this->middleware('checkAdminPermission:permissions.list,list')->only(['list']);
    }

    public function index(Request $request)
    {
        $permissions = Permission::orderBy('id','DESC')->paginate(5);
        return view('admin-views.permissions.index',compact('permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->permission->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->permission->latest();
        }
        $permissions = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.permissions.list', compact('permissions', 'search'));
    }

    public function listPermission()
    {
        $permissions = Permission::all();
        
        return response()->json($permissions);
    }
    

    public function create()
    {
        return view('admin-views.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required']);


        try {
            $permission = $this->permission->create([
                'name' => $request->name,
            ]);
            return response()->json($permission);
        } catch (\Exception $e) {
            return response()->json(['error' => $e],400);
        }

    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin-views.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(['name' => 'required']);


        try {
            $permission = Permission::findOrFail($id);

            $permission->name = $request->input('name');

            $permission->save();


            return response()->json($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e],400);
        }
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        // Delete the per$permission
        $permission->delete();
        Toastr::success(translate('Permission Deleted!'));
        return back();
    }

    public function assignRole(Request $request, Permission $permission)
    {
        if ($permission->hasRole($request->role)) {
            return back()->with('message', 'Role exists.');
        }

        $permission->assignRole($request->role);
        return back()->with('message', 'Role assigned.');
    }

    public function removeRole(Permission $permission, Role $role)
    {
        if ($permission->hasRole($role)) {
            $permission->removeRole($role);
            return back()->with('message', 'Role removed.');
        }

        return back()->with('message', 'Role not exists.');
    }

    public function deleteAll(Request $request)
    {

        $ids = $request->ids;
        $permissions= DB::table("permissions")->whereIn('id',explode(",",$ids))->get();
        if(count($permissions)>0){
              DB::table("permissions")->whereIn('id',explode(",",$ids))->delete();
              return response()->json(['success'=>"permissions Deleted successfully."]);

        }
        else{
            return response()->json(['error'=>"No permissions to Delete."]);

        }

    }
}
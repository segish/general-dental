<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Ward;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;


class WardController extends Controller
{
    function __construct(
        private Ward $ward,
    ) {}

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin-views.wards.index', compact('roles'));
    }

    public function list(Request $request): Factory|View|Application
    {

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->ward->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('ward_name', 'like', "%{$value}%")
                        ->orWhere('max_beds_capacity', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->ward->latest();
        }
        $wards = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.wards.list', compact('wards', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.wards.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ward_name' => 'required|string',
            'description' => 'nullable|string',
            'max_beds_capacity' => 'nullable|integer',
        ]);

        try {

            Ward::create($data);
            Toastr::success('Ward created successfully!');
            return redirect()->route('admin.ward.list');
        } catch (\Exception $e) {
            Toastr::error();
            return redirect()->back()->withInput();
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

        return view('admin-views.wards.show', compact('role', 'rolePermissions'));
    }



    public function edit($id)
    {
        $ward = ward::find($id);
        return view('admin-views.wards.edit', compact('ward'));
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
        $data = $request->validate([
            'ward_name' => 'required|string',
            'description' => 'nullable|string',
            'max_beds_capacity' => 'nullable|integer',
        ]);

        try {
            $ward = Ward::withCount('beds')->findOrFail($id); // Load bed count

            // If new capacity is less than existing beds count, prevent update
            if ($request->max_beds_capacity !== null && $request->max_beds_capacity < $ward->beds_count) {
                Toastr::error(translate("Cannot reduce max capacity to {$request->max_beds_capacity}. This ward currently has {$ward->beds_count} beds. Please delete some beds first."));
                return redirect()->back()->withInput();
            }

            // Proceed with update
            $ward->ward_name = $request->ward_name;
            $ward->description = $request->description;
            $ward->max_beds_capacity = $request->max_beds_capacity;
            $ward->save();

            Toastr::success(translate('Ward updated successfully!'));
            return redirect()->route('admin.ward.list');
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
            $ward = ward::findOrFail($id);
            $ward->delete();
            Toastr::success(translate('ward Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::success($e->getMessage());
        }
    }
}

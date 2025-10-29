<?php
namespace App\Http\Controllers\admin;

use App\Models\SampleType;
use App\Models\SpecimenType;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\TestType;
use App\Models\QuickService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;

class SpecimenTypeController extends Controller
{
    function __construct(
        private SpecimenType $specimenTypes,
    ) {
        $this->middleware('checkAdminPermission:specimen_type.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:specimen_type.add-new,index')->only(['index']);
    }

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();

        return view('admin-views.specimen-type.index', compact('roles'));

    }

    public function list(Request $request): Factory|View|Application
    {

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->specimenTypes->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('title', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->specimenTypes->latest();
        }
        $sampleTypes = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.specimen-type.list', compact('sampleTypes', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.specimen-type.create', compact('permission'));
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
            'name' => 'required|string|max:255|unique:specimen_types,name',
            'description' => 'nullable|string',
        ]);

        try {

            $testType = new SpecimenType([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ]);
            $testType->save();
            Toastr::success(translate('Test Type Saved successfully!'));
            return redirect()->route('admin.specimen_type.list');
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

        return view('admin-views.specimen-type.show', compact('role', 'rolePermissions'));
    }



    public function edit($id)
    {
        $specimenType = SpecimenType::find($id);


        return view('admin-views.specimen-type.edit', compact('specimenType'));
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
            'name' => 'required|string|max:255|unique:specimen_types,name,' . $id,
            'description' => 'nullable|string',
        ]);

        try {
            $testType = SpecimenType::findOrFail($id);

            $testType->name = $request->get('name');
            $testType->description = $request->get('description');
            $testType->save();

            Toastr::success(translate('testType Updated successfully!'));
            return redirect()->route('admin.specimen_type.list');
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
            $testType = SpecimenType::findOrFail($id);

            $testType->delete();
            Toastr::success(translate('Specimen Type Deleted Successfully!'));
            return back();

        } catch (\Exception $e) {
            Toastr::success($e->getMessage());

        }
    }
}

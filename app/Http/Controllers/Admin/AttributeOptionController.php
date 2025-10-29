<?php

namespace App\Http\Controllers\admin;

use App\Models\AttributeOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestAttribute;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;

class AttributeOptionController extends Controller
{
    //
    function __construct(
        private AttributeOption $attributeOption
    ) {
        $this->middleware('checkAdminPermission:attribute_option.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:attribute_option.add-new,index')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $testAttributes = TestAttribute::all();
        return view('admin-views.attribute-option.index', compact('roles', 'testAttributes'));
    }

    public function list(Request $request): Factory|View|Application
    {

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->attributeOption->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('option_value', 'like', "%{$value}%");

                    // $q->orWhereHas('attribute', function ($attriubte) use ($value) {
                    //     $attriubte->where('attribute_name', 'like', "%{$value}%");
                    // });
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->attributeOption->latest();
        }
        $attributeOptions = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.attribute-option.list', compact('attributeOptions', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.attribute-option.create', compact('permission'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:test_attributes,id',
            'option_value' => 'required|string|max:255|unique:attribute_options,option_value',
        ]);
        try {
            $attribute = new TestAttribute([
                'attribute_id' => $request->attribute_id,
                'option_value' => $request->option_value,
            ]);
            $attribute->save();

            Toastr::success(translate('Attribute Option saved successfully!'));
            return redirect()->route('admin.test_attribute.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput(); //
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('admin-views.attribute-option.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
    {
        $testAttribute = TestAttribute::find($id);
        return view('admin-views.attribute-option.edit', compact('testAttribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'attribute_id' => 'required|exists:test_attributes,id' . $id,
            'option_value' => 'required|string|max:255|unique:attribute_options,option_value',
        ]);
        try {
            $attributeOption = TestAttribute::find($id);

            $attributeOption = new TestAttribute([
                'attribute_id' => $request->attribute_id,
                'option_value' => $request->option_value,
            ]);

            $attributeOption->save();

            Toastr::success(translate('Attribute Option Updated successfully!'));
            return redirect()->route('admin.test_attribute.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $attributeOption = AttributeOption::findOrFail($id);
            $attributeOption->delete();
            Toastr::success(translate('Attribute Option Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::success($e->getMessage());
        }
    }
}

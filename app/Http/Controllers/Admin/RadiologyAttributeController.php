<?php

namespace App\Http\Controllers\Admin;

use App\Models\Radiology;
use App\Models\RadiologyAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use GPBMetadata\Google\Api\Log;
use Illuminate\Support\Facades\Log as FacadesLog;

class RadiologyAttributeController extends Controller
{
    function __construct(
        private RadiologyAttribute $radiologyAttribute
    ) {
        $this->middleware('checkAdminPermission:radiology_attribute.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:radiology_attribute.add-new,index')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $radiologies = Radiology::all();
        // dd($radiologies);
        return view('admin-views.radiology-attribute.index', compact('roles', 'radiologies'));
    }

    public function fetchRadiologyAttributes(Request $request)
    {
        $radiology = Radiology::findOrFail($request->radiologyId);
        $attributes = $radiology->attributes()->get();

        return response()->json([
            'radiology' => $radiology,
            'attributes' => $attributes,
        ], 200);
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->radiologyAttribute->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('attribute_name', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->radiologyAttribute->latest();
        }
        $radiologyAttributes = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.radiology-attribute.list', compact('radiologyAttributes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        $radiologies = Radiology::all();
        return view('admin-views.radiology-attribute.create', compact('permission', 'radiologies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'radiology_id' => 'required|exists:radiologies,id',
            'attribute_name' => 'required|string|max:255',
            'default_required' => 'boolean',
            'result_type' => 'nullable|in:paragraph,short',
            'template' => 'nullable|string',
        ]);

        try {
            $attribute = new RadiologyAttribute([
                'radiology_id' => $request->radiology_id,
                'attribute_name' => $request->attribute_name,
                'default_required' => $request->default_required ?? false,
                'result_type' => $request->result_type ?? 'short', // Default to 'short' if not provided
                'template' => $request->template ?? null, // Default to 'short' if not provided
            ]);
            $attribute->save();

            Toastr::success(translate('Radiology Attribute saved successfully!'));
            return redirect()->route('admin.radiology_attribute.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $radiologyAttribute = RadiologyAttribute::findOrFail($id);
        return view('admin-views.radiology-attribute.show', compact('radiologyAttribute'));
    }

    public function edit($id)
    {
        $radiologies = Radiology::all();
        $radiologyAttribute = RadiologyAttribute::findOrFail($id);
        return view('admin-views.radiology-attribute.edit', compact('radiologyAttribute', 'radiologies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'radiology_id' => 'required|exists:radiologies,id',
            'attribute_name' => 'required|string|max:255',
            'default_required' => 'boolean',
            'result_type' => 'nullable|in:paragraph,short',
            'template' => 'nullable|string',
        ]);

        try {
            $attribute = RadiologyAttribute::findOrFail($id);

            $attribute->fill([
                'radiology_id' => $request->radiology_id,
                'attribute_name' => $request->attribute_name,
                'default_required' => $request->default_required ?? false,
                'result_type' => $request->result_type, // Default to 'short' if not provided
                'template' => $request->template ?? null, // Default to 'short' if not provided
            ]);

            $attribute->save();

            Toastr::success(translate('Radiology Attribute Updated successfully!'));
            return redirect()->route('admin.radiology_attribute.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $attribute = RadiologyAttribute::findOrFail($id);
            $attribute->delete();
            Toastr::success(translate('Radiology Attribute Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back();
        }
    }
}

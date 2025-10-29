<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use Spatie\Permission\Models\Permission;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;

class UnitController extends Controller
{
    function __construct(
        private Unit $unit
    ) {
        $this->middleware('checkAdminPermission:unit.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:unit.add-new,index')->only(['index']);
    }
    public function index()
    {
        return view('admin-views.unit.index');
    }

    public function list(Request $request)
    {
        $search = $request->get('search');
        $query = Unit::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $units = $query->paginate(Helpers::pagination_limit());

        return view('admin-views.unit.list', compact('units', 'search'));
    }

    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.unit.create', compact('permission'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
            'code' => 'required|string|max:50|unique:units,code',
            'description' => 'nullable|string',
        ]);

        try {
            Unit::create($request->all());
            Toastr::success('Unit created successfully!');
            return redirect()->route('admin.unit.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('admin-views.unit.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => "required|string|max:255|unique:units,name,{$id}",
            'code' => "required|string|max:50|unique:units,code,{$id}",
            'description' => 'nullable|string',
        ]);

        try {
            $unit = Unit::findOrFail($id);
            $unit->update($request->all());
            Toastr::success('Unit updated successfully!');
            return redirect()->route('admin.unit.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit->delete();
            Toastr::success('Unit deleted successfully!');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
        return back();
    }
}

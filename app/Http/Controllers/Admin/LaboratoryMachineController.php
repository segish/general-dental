<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LaboratoryMachine;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;

class LaboratoryMachineController extends Controller
{
    public function index()
    {
        return view('admin-views.laboratory-machine.index');
    }

    public function list(Request $request)
    {
        $search = $request->get('search');
        $query = LaboratoryMachine::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('model', 'like', "%{$search}%")
                ->orWhere('serial_number', 'like', "%{$search}%")
                ->orWhere('manufacturer', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }

        $machines = $query->paginate(Helpers::pagination_limit());

        return view('admin-views.laboratory-machine.list', compact('machines', 'search'));
    }

    public function create()
    {
        return view('admin-views.laboratory-machine.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:laboratory_machines,name',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:laboratory_machines,code',
        ]);

        try {
            LaboratoryMachine::create($request->all());
            Toastr::success('Laboratory Machine created successfully!');
            return redirect()->route('admin.laboratory-machine.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $machine = LaboratoryMachine::findOrFail($id);
        return view('admin-views.laboratory-machine.edit', compact('machine'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => "required|string|max:255|unique:laboratory_machines,name,{$id}",
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'description' => 'nullable|string',
            'code' => "required|string|unique:laboratory_machines,code,{$id}",
        ]);

        try {
            $machine = LaboratoryMachine::findOrFail($id);
            $machine->update($request->all());
            Toastr::success('Laboratory Machine updated successfully!');
            return redirect()->route('admin.laboratory-machine.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $machine = LaboratoryMachine::findOrFail($id);
            $machine->delete();
            Toastr::success('Laboratory Machine deleted successfully!');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
        return back();
    }
}

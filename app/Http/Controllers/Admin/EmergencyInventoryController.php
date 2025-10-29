<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{MedicineCategory, Medicine, EmergencyInventory, EmergencyMedicine, Supplier};
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class EmergencyInventoryController extends Controller
{
    public function __construct(private EmergencyInventory $emergencyInventory)
    {
        $this->middleware('checkAdminPermission:emergency_inventory.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:emergency_inventory.add-new,index')->only(['index']);
    }

    public function index()
    {
        $inventory = EmergencyInventory::all();
        $medicines = EmergencyMedicine::all();
        $suppliers = Supplier::all();
        return view('admin-views.emergency-inventory.index', compact('inventory', 'medicines', 'suppliers'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->emergencyInventory->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->whereHas('medicine', function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    })
                    ->orWhere('batch_number', 'like', "%{$value}%")
                    ->orWhere('quantity', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->emergencyInventory->latest();
        }
        $inventory = $query->with('medicine')->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.emergency-inventory.list', compact('inventory', 'search'));
    }
    public function edit($id)
    {
        $inventory = EmergencyInventory::findOrFail($id);
        $medicines = EmergencyMedicine::all();
        $suppliers = Supplier::all();
        return view('admin-views.emergency-inventory.edit', compact('inventory', 'medicines', 'suppliers'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'emergency_medicine_id' => 'required|exists:medicines,id',
            'batch_number' => 'nullable|string',
            'quantity' => 'required|integer',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'expiry_date' => 'nullable|date',
            'received_date' => 'nullable|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'low_stock_threshold' => 'nullable|integer',
            'expiry_alert_days' => 'nullable|integer',
        ]);

        try {
            EmergencyInventory::create($request->all());
            Toastr::success('Emergency Inventory added successfully!');
            return redirect()->route('admin.emergency_inventory.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $inventory = EmergencyInventory::findOrFail($id);
        $inventory->update($request->all());
        Toastr::success('Emergency Inventory updated successfully!');
        return redirect()->route('admin.emergency_inventory.list');
    }

    public function destroy($id)
    {
        try {
            EmergencyInventory::findOrFail($id)->delete();
            Toastr::success('Emergency Inventory deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }
}

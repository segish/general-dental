<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyMedicine;
use App\Models\EmergencyMedicineCategory;
use App\Models\Unit;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class EmergencyMedicineController extends Controller
{

    public function __construct(private EmergencyMedicine $emergencyMedicine)
    {
        $this->middleware('checkAdminPermission:emergency-medicines.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:emergency-medicines.add-new,index')->only(['index']);
    }

    public function index()
    {
        $units = Unit::all();
        $categories = EmergencyMedicineCategory::all();
        return view('admin-views.emergency-medicine.index', compact('units', 'categories'));
    }

    public function list()
    {
        $medicines = EmergencyMedicine::with(['unit', 'category'])
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('item_type', 'like', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(25);
        return view('admin-views.emergency-medicine.list', compact('medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_id' => 'nullable|exists:units,id',
            'payment_timing' => 'required|in:prepaid,postpaid',
            'item_type' => 'required|in:medication,consumable,equipment',
            'category_id' => 'nullable|exists:medicine_categories,id',
            'low_stock_threshold' => 'required|integer|min:0',
            'expiry_alert_days' => 'required|integer|min:0',
        ]);

        EmergencyMedicine::create($request->all());

        Toastr::success(translate('Emergency medicine added successfully!'));
        return redirect()->route('admin.emergency-medicines.list');
    }

    public function edit($id)
    {
        $medicine = EmergencyMedicine::findOrFail($id);
        $units = Unit::all();
        $categories = MedicineCategory::all();
        return view('admin-views.emergency-medicine.edit', compact('medicine', 'units', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_id' => 'nullable|exists:units,id',
            'payment_timing' => 'required|in:prepaid,postpaid',
            'item_type' => 'required|in:medication,consumable,equipment',
            'category_id' => 'nullable|exists:medicine_categories,id',
            'low_stock_threshold' => 'required|integer|min:0',
            'expiry_alert_days' => 'required|integer|min:0',
        ]);

        $medicine = EmergencyMedicine::findOrFail($id);
        $medicine->update($request->all());

        Toastr::success(translate('Emergency medicine updated successfully!'));
        return redirect()->route('admin.emergency-medicines.list');
    }

    public function destroy($id)
    {
        $medicine = EmergencyMedicine::findOrFail($id);
        $medicine->delete();

        Toastr::success(translate('Emergency medicine deleted successfully!'));
        return back();
    }
}

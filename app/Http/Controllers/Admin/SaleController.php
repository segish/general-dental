<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Sale, SaleDetail, Medicine, Patient, Prescription};
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class SaleController extends Controller
{
    public function __construct(private Sale $sale)
    {
        $this->middleware('checkAdminPermission:sales.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:sales.add-new,index')->only(['index']);
    }

    public function index()
    {
        $sales = Sale::all();
        $patients = Patient::all();
        $prescriptions = Prescription::all();
        $medicines = Medicine::all();
        return view('admin-views.sale.index', compact('sales', 'patients', 'prescriptions', 'medicines'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->sale->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('total_amount', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->sale->latest();
        }
        $sales = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.sale.list', compact('sales', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'prescription_id' => 'nullable|exists:prescriptions,id',
            'sale_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'sale_type' => 'required|in:Internal,External',
            'medicines' => 'required|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            $sale = Sale::create($request->only(['patient_id', 'prescription_id', 'sale_date', 'total_amount', 'sale_type']));

            foreach ($request->medicines as $medicine) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $medicine['medicine_id'],
                    'quantity' => $medicine['quantity'],
                    'unit_price' => $medicine['unit_price'],
                    'subtotal' => $medicine['quantity'] * $medicine['unit_price'],
                ]);
            }
            
            Toastr::success('Sale recorded successfully!');
            return redirect()->route('admin.sales.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
        $sale->update($request->only(['patient_id', 'prescription_id', 'sale_date', 'total_amount', 'sale_type']));
        Toastr::success('Sale updated successfully!');
        return back();
    }

    public function destroy($id)
    {
        try {
            Sale::findOrFail($id)->delete();
            Toastr::success('Sale deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{PharmacyStockAdjustment, Medicine, PharmacyInventory, Admin};
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class PharmacyStockAdjustmentController extends Controller
{
    public function __construct(private PharmacyStockAdjustment $adjustment)
    {
        $this->middleware('checkAdminPermission:pharmacy_stock_adjustments.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:pharmacy_stock_adjustments.add-new,index')->only(['index']);
    }

    public function index(): Factory|View|Application
    {
        $adjustments = PharmacyStockAdjustment::all();
        $medicines = Medicine::all();
        $pharmacyInventories = PharmacyInventory::all();
        $admins = Admin::all();
        return view('admin-views.pharmacy-stock-adjustment.index', compact('adjustments', 'medicines', 'pharmacyInventories', 'admins'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->adjustment->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('adjustment_type', 'like', "%{$value}%")
                        ->orWhere('status', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->adjustment->latest();
        }
        $adjustments = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.pharmacy-stock-adjustment.list', compact('adjustments', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'pharmacy_inventory_id' => 'required|exists:pharmacy_inventory,id',
            'quantity' => 'required|integer',
            'adjustment_type' => 'required|in:Damage,Expiration,Correction,Other',
            'reason' => 'nullable|string',
            'requested_by' => 'required|exists:admins,id',
            'approved_by' => 'nullable|exists:admins,id',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        try {
            PharmacyStockAdjustment::create($request->all());
            Toastr::success('Stock adjustment recorded successfully!');
            return redirect()->route('admin.pharmacy_stock_adjustments.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $adjustment = PharmacyStockAdjustment::findOrFail($id);
        $adjustment->update($request->only(['quantity', 'adjustment_type', 'reason', 'approved_by', 'status']));
        Toastr::success('Stock adjustment updated successfully!');
        return back();
    }

    public function destroy($id)
    {
        try {
            PharmacyStockAdjustment::findOrFail($id)->delete();
            Toastr::success('Stock adjustment deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyPrescription;
use App\Models\PrescriptionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Foundation\Application;
use App\CentralLogics\Helpers;
use App\Events\NewMenuTestResultCreated;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\BusinessSetting;
use App\Models\EmergencyInventory;
use App\Models\EmergencyPrescriptionDetail;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmergencyPrescriptionController extends Controller
{
    function __construct(
        private EmergencyPrescription $emergencyPrescription

    ) {
        $this->middleware('checkAdminPermission:emergency_prescriptions.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:emergency_prescriptions.add-new,store')->only(['store']);
    }
    public function list(Request $request): Factory|View|Application
    {
        $query = $this->emergencyPrescription->with(['doctor', 'billing', 'visit.patient', 'details.medicine.medicine', 'details.issuedBy:id,f_name,l_name'])->latest();
        $fourMonthsFromNow = Carbon::now()->addMonths(4);
        $emergencyPrescreptions = EmergencyInventory::with('medicine')
            ->where('quantity', '>', 0)
            ->where(function ($query) use ($fourMonthsFromNow) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', $fourMonthsFromNow);
            })
            ->get();

        $prescreptions = $query->paginate(Helpers::pagination_limit());
        return view('admin-views.emergency-prescreptions.list', compact('prescreptions', 'emergencyPrescreptions'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'visit_id' => 'required|exists:visits,id',
                'doctor_id' => 'required|exists:admins,id',
                'emergency_inventory_id' => 'required|exists:medicines,id',
                'dosage' => 'nullable|string',
                'dose_duration' => 'nullable|integer',
                'dose_time' => 'nullable|in:Before Meal,After Meal,With Meal,Anytime',
                'dose_interval' => 'nullable',
                'quantity' => 'required|integer',
                'comment' => 'nullable|string',
            ]);
            DB::beginTransaction();
            // Check if a prescription already exists for the visit
            $prescription = EmergencyPrescription::firstOrCreate([
                'visit_id' => $request->visit_id,
                'doctor_id' => $request->doctor_id,
                'prescribed_date' => now()->toDateString(),
            ]);

            // Add prescription detail
            $detail = $prescription->details()->create([
                'emergency_inventory_id' => $request->emergency_inventory_id,
                'dosage' => $request->dosage,
                'dose_duration' => $request->dose_duration,
                'dose_time' => $request->dose_time,
                'dose_interval' => $request->dose_interval,
                'quantity' => $request->quantity,
                'comment' => $request->comment,
            ]);
            $this->generateBill($prescription, $detail);

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    'New Inclinic Prescription added for ' . $prescription->visit->patient->full_name,
                    '/admin/emergency_prescriptions/list',
                    'New Inclinic Prescription',
                    'emergency_prescriptions.list'
                ));
            }
            DB::commit();
            return response()->json(['message' => 'Prescription added successfully', 'visit_id' => $prescription->visit_id], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Prescription add failed', 'error' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $prescreption = EmergencyPrescription::findOrFail($id);

        // Delete the role
        $prescreption->delete();
        Toastr::success(translate('Prescreption Deleted Successfully!'));
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'detail_id' => 'required|exists:emergency_prescription_details,id',
            'doctor_id' => 'required|exists:admins,id',
            'emergency_inventory_id' => 'required|exists:medicines,id',
            'dosage' => 'nullable|string',
            'dose_duration' => 'nullable|integer',
            'dose_time' => 'nullable|in:Before Meal,After Meal,With Meal,Anytime',
            'dose_interval' => 'nullable',
            'quantity' => 'required|integer',
            'comment' => 'nullable|string',
        ]);
        try {
            DB::beginTransaction();
            $prescriptionDetail = EmergencyPrescriptionDetail::with('prescription')->findOrFail($request->detail_id);
            $prescriptionDetail->update([
                'emergency_inventory_id' => $request->emergency_inventory_id,
                'dosage' => $request->dosage,
                'dose_duration' => $request->dose_duration,
                'dose_time' => $request->dose_time,
                'dose_interval' => $request->dose_interval,
                'quantity' => $request->quantity,
                'comment' => $request->comment,
            ]);
            $prescriptionDetail->save();
            $this->updateBill($prescriptionDetail->prescription);

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    'Inclinic Prescription updated for ' . $prescriptionDetail->prescription->visit->patient->full_name,
                    '/admin/emergency_prescriptions/list',
                    'Inclinic Prescription Updated',
                    'emergency_prescriptions.list'
                ));
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Prescription updated successfully!',
                'visit_id' => $prescriptionDetail->prescription->visit_id
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Prescription update failed!',
            ]);
        }
    }

    public function updateIssuedStatus(Request $request)
    {
        try {
            $request->validate([
                'result_id' => 'required|exists:emergency_prescription_details,id',
                'process_status' => 'required|in:issued,cancelled,pending',
                'quantity' => 'required',
            ]);
            DB::beginTransaction();
            $prescriptionDetail = EmergencyPrescriptionDetail::findOrFail($request->result_id);
            $pendingQuantity = $prescriptionDetail->quantity - ($prescriptionDetail->issued_quantity + $prescriptionDetail->cancelled_quantity);
            $visit_id = $prescriptionDetail->prescription->visit_id;
            if ($pendingQuantity < $request->quantity) {
                DB::rollBack();
                return response()->json(['message' => 'quantity cannot exceed pending quantity'], 500);
            }
            $inventoryId = $prescriptionDetail->emergency_inventory_id;

            if ($request->process_status == 'issued') {
                $Inventory = EmergencyInventory::find($inventoryId);
                if (!$Inventory || $Inventory->quantity < $request->quantity) {
                    DB::rollBack();
                    return response()->json(['message' => 'availabel item is not enough ' . $Inventory->quantity . ' left'], 500);
                }
                $prescriptionDetail->increment('issued_quantity', $request->quantity);
                $Inventory->decrement('quantity', $request->quantity);
            }

            if ($request->process_status == 'cancelled') {
                if ($prescriptionDetail->status == 'issued') {
                    $Inventory = EmergencyInventory::find($inventoryId);
                    if (!$Inventory) {
                        DB::rollBack();
                        return response()->json(['message' => 'this product has been deleted'], 500);
                    }
                    $Inventory->increment('quantity', $request->quantity);
                }
                $prescriptionDetail->increment('cancelled_quantity', $request->quantity);
            }

            $prescriptionDetail->update([
                'status' => $request->process_status,
                'issued_by' => auth('admin')->user()->id,
            ]);

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    'Inclinic Prescription ' . $request->process_status . ' for ' . $prescriptionDetail->prescription->visit->patient->full_name,
                    '/admin/patient/view/' . $prescriptionDetail->prescription->visit->patient->id . '?active=' . $prescriptionDetail->prescription->visit_id,
                    'Inclinic Prescription Status',
                    'emergency_prescriptions.list'
                ));
            }
            DB::commit();
            return response()->json(['message' => 'Issued status updated successfully', 'visit_id' => $visit_id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage(), 'error' => $th->getMessage()], 500);
        }
    }

    private function generateBill(EmergencyPrescription $prescription, EmergencyPrescriptionDetail $detail)
    {
        $totalAmount = 0;

        // foreach ($prescription->details as $detail) {
        $inventoryItem = EmergencyInventory::find($detail->emergency_inventory_id);
        if ($inventoryItem) {
            $totalAmount += $inventoryItem->selling_price * $detail->quantity;
        }
        // }

        $billing = null;

        $UnpaidBill = Billing::where('visit_id', $prescription->visit_id)
            ->where('status', 'unpaid')
            ->where('emergency_medicine_issuance_id', $prescription->id)
            ->first();

        if ($UnpaidBill) {
            $billing = $UnpaidBill;
            $UnpaidBill->update([
                'total_amount' => $UnpaidBill->total_amount + $totalAmount,
            ]);
        } else { // Create billing record
            $billing = Billing::create([
                'visit_id' => $prescription->visit_id,
                'admin_id' => $prescription->doctor_id,
                'emergency_medicine_issuance_id' => $prescription->id,
                'bill_date' => now(),
                'total_amount' => $totalAmount,
                'discount' => 0,
                'amount_paid' => 0,
                'status' => 'unpaid',
                'note' => 'Auto-generated bill for self-requested test'
            ]);
        }
        // foreach ($prescription->details as $detail) {
        $inventoryItem = EmergencyInventory::find($detail->emergency_inventory_id);
        BillingDetail::create([
            'billing_id' => $billing->id,
            'emergency_medicine_issuance_id' => $detail->id,
            'quantity' => $detail->quantity,
            'unit_cost' => $inventoryItem->selling_price
        ]);
        // }
    }

    private function updateBill(EmergencyPrescription $prescription)
    {
        $totalAmount = 0;

        foreach ($prescription->details as $detail) {
            $inventoryItem = EmergencyInventory::find($detail->emergency_inventory_id);
            if ($inventoryItem) {
                $totalAmount += $inventoryItem->selling_price * $detail->quantity;
            }
        }
        if ($prescription->billing) {
            $prescription->billing->delete();
        }
        // Create billing record
        $billing = Billing::create([
            'visit_id' => $prescription->visit_id,
            'admin_id' => $prescription->doctor_id, // Assigned to the collector
            'emergency_medicine_issuance_id' => $prescription->id,
            'bill_date' => now(),
            'total_amount' => $totalAmount,
            'discount' => 0,
            'amount_paid' => 0,
            'status' => 'unpaid',
            'note' => 'Auto-generated bill for self-requested test'
        ]);

        foreach ($prescription->details as $detail) {
            $inventoryItem = EmergencyInventory::find($detail->emergency_inventory_id);
            BillingDetail::create([
                'billing_id' => $billing->id,
                'emergency_medicine_issuance_id' => $detail->id,
                'quantity' => $detail->quantity,
                'unit_cost' => $inventoryItem->selling_price
            ]);
        }
    }
}

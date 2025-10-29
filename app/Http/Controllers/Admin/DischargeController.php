<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discharge;
use App\Models\Visit;
use Carbon\Carbon;
use App\Models\BillingDetail;
use App\Models\Billing;
use Illuminate\Support\Facades\DB;

class DischargeController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'discharge_date' => 'nullable|date',
            'discharge_type' => 'nullable|in:Recovered,Referred,Death,Absconded',
            'discharge_notes' => 'nullable|string',
            'remarks' => 'nullable|string',
            'attending_physician' => 'nullable|exists:admins,id',
        ]);

        DB::beginTransaction();

        try {
            $visit = Visit::with('ipdRecord.bed')->findOrFail($data['visit_id']);
            $ipdRecord = $visit->ipdRecord;

            if (!$ipdRecord) {
                return response()->json(['error' => 'IPD Record not found for the visit.'], 404);
            }

            $admissionDate = Carbon::parse($ipdRecord->admission_date);
            $dischargeDate = $data['discharge_date'] ? Carbon::parse($data['discharge_date']) : now();

            // Check if discharge date is after admission date
            if ($dischargeDate->lessThan($admissionDate)) {
                return response()->json(['error' => 'Discharge date cannot be before admission date.'], 422);
            }

            $numberOfDays = $admissionDate->diffInDays($dischargeDate) ?: 1; // at least 1 day
            $bedPricePerDay = $ipdRecord->bed?->price ?? 0;
            $totalAmount = $numberOfDays * $bedPricePerDay;

            // Set admission_date in the data
            $data['admission_date'] = $admissionDate;
            $data['stay_days'] = $numberOfDays;

            // Create discharge record
            $discharge = Discharge::create($data);

            // Create billing
            $billing = Billing::create([
                'visit_id' => $visit->id,
                'admin_id' => auth('admin')->id(),
                'billing_service_id' => null,
                'laboratory_request_id' => null,
                'billing_from_discharge_id' => $discharge->id,
                'bill_date' => now(),
                'total_amount' => $totalAmount,
                'discount' => 0,
                'amount_paid' => 0,
                'status' => 'pending',
                'note' => 'Auto-generated billing for bed stay during discharge',
            ]);

            // Create billing detail
            BillingDetail::create([
                'billing_id' => $billing->id,
                'billing_service_id' => null,
                'quantity' => $numberOfDays,
                'unit_cost' => $bedPricePerDay,
                'billing_from_discharge_id' => $discharge->id,
            ]);

            if ($ipdRecord->bed) {
                $ipdRecord->bed->update(['status' => 'available']);
            }
            
            DB::commit();

            return response()->json(['message' => 'Discharge and billing saved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }


    // Update an existing discharge
    public function update(Request $request, Discharge $discharge)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'discharge_date' => 'nullable|date',
            'discharge_type' => 'nullable|in:Recovered,Referred,Death,Absconded',
            'discharge_notes' => 'nullable|string',
            'remarks' => 'nullable|string',
            'attending_physician' => 'nullable|exists:admins,id',
        ]);

        $discharge->update($data);

        return response()->json(['message' => 'Discharge updated successfully.']);
    }
}

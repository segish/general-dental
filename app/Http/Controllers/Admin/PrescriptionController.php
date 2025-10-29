<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewMenuTestResultCreated;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Prescription;
use App\Models\Visit;
use App\Models\PrescriptionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'visit_id' => 'required|exists:visits,id',
                'doctor_id' => 'required|exists:admins,id',
                'medicine_id' => 'required|exists:medicines,id',
                'dosage' => 'nullable|string',
                'dose_duration' => 'nullable|integer',
                'dose_time' => 'nullable|in:Before Meal,After Meal,With Meal,Anytime',
                'dose_interval' => 'nullable',
                'quantity' => 'nullable|integer',
                'comment' => 'nullable|string',
            ]);
            DB::beginTransaction();
            // Check if a prescription already exists for the visit
            $prescription = Prescription::firstOrCreate([
                'visit_id' => $request->visit_id,
                'doctor_id' => $request->doctor_id,
                'prescribed_date' => now()->toDateString(),
            ]);

            // Add prescription detail
            $prescription->details()->create([
                'medicine_id' => $request->medicine_id,
                'dosage' => $request->dosage,
                'dose_duration' => $request->dose_duration,
                'dose_time' => $request->dose_time,
                'dose_interval' => $request->dose_interval,
                'quantity' => $request->quantity,
                'comment' => $request->comment,
            ]);
            $has_pharmacy = BusinessSetting::where('key', 'has_pharmacy')->first()?->value ?? '0';
            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                if ($has_pharmacy) {
                    event(new NewMenuTestResultCreated(
                        'New Prescription added for ' . $prescription->visit->patient->full_name,
                        '/admin/pos',
                        'New Prescription',
                        'prescriptions.list'
                    ));
                }
            }

            DB::commit();
            return response()->json(['message' => 'Prescription added successfully', 'visit_id' => $prescription->visit_id], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json(['message' => 'Prescription add failed', 'error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'dosage' => 'nullable|string',
            'dose_duration' => 'nullable|integer',
            'dose_time' => 'nullable|in:Before Meal,After Meal,With Meal,Anytime',
            'dose_interval' => 'nullable',
            'quantity' => 'nullable|integer',
            'comment' => 'nullable|string',
        ]);

        $prescriptionDetail = PrescriptionDetail::findOrFail($id);

        $prescriptionDetail->update([
            'medicine_id' => $request->medicine_id,
            'dosage' => $request->dosage,
            'dose_duration' => $request->dose_duration,
            'dose_time' => $request->dose_time,
            'dose_interval' => $request->dose_interval,
            'quantity' => $request->quantity,
            'comment' => $request->comment,
        ]);

        $has_pharmacy = BusinessSetting::where('key', 'has_pharmacy')->first();
        if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
            if ($has_pharmacy) {
                event(new NewMenuTestResultCreated(
                    'Prescription updated for ' . $prescriptionDetail->prescription->visit->patient->full_name,
                    '/admin/pos',
                    'Prescription Updated',
                    'prescriptions.list'
                ));
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Prescription updated successfully!',
            'visit_id' => $prescriptionDetail->prescription->visit_id
        ]);
    }

    public function generatePdf($id)
    {
        $visit = Visit::findOrFail($id);

        $pdf = PDF::loadView('admin-views.patients.pdf', compact('visit'))
            ->setPaper('a5', 'portrait');

        return $pdf->stream('prescription.pdf');
    }

    public function downloadPdf($id)
    {
        $visit = Visit::findOrFail($id);

        $pdf = PDF::loadView('admin-views.patients.pdf', compact('visit'))
            ->setPaper('a5', 'portrait');

        return $pdf->download('prescription.pdf');
    }
}

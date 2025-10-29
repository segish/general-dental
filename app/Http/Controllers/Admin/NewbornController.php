<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newborn;
use App\Models\DeliverySummary;
use App\Models\Patient;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;

class NewbornController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_summary_id'        => 'required|exists:delivery_summaries,id',
            'name'                       => 'nullable|string|max:255',
            'bcg_date'                   => 'nullable|date',
            'polio_0'                    => 'nullable|boolean',
            'vit_k'                      => 'nullable|boolean',
            'ttc'                        => 'nullable|boolean',
            'baby_mother_bonding'        => 'nullable|boolean',
            'para'                       => 'nullable|integer',
            'prom'                       => 'nullable|boolean',
            'prom_hours'                 => 'nullable|integer',
            'birth_weight'               => 'nullable|numeric|min:0|max:999.99',
            'temp'                       => 'nullable|numeric|min:0|max:999.99',
            'pr'                         => 'nullable|integer',
            'rr'                         => 'nullable|integer',
            'hiv_counts_and_testing_offered' => 'nullable|boolean',
            'hiv_testing_accepted'       => 'nullable|boolean',
            'hiv_test_result'            => 'nullable|string|max:255',
            'arv_px_mother'              => 'nullable|string|max:255',
            'arv_px_newborn'             => 'nullable|string|max:255',
            'apgar_score'                => 'nullable|string|max:255',
            'sex'                        => 'nullable|in:male,female',
            'length_cm'                  => 'nullable|numeric|min:0|max:999.99',
            'head_circumference_cm'      => 'nullable|numeric|min:0|max:999.99',
            'term_status'                => 'nullable|in:Term,Preterm',
            'resuscitated'               => 'nullable|boolean',
            'dysmorphic_faces'           => 'nullable|boolean',
            'neonatal_evaluation'        => 'nullable|string',
            'plan'                       => 'nullable|string',
            'remarks'                    => 'nullable|string',
        ]);



        DB::beginTransaction();

        try {
            $deliverySummary = DeliverySummary::find($validated['delivery_summary_id']);

            $patientData['registration_date'] = now();
            $patientData['date_of_birth'] = $deliverySummary->date;
            $patientData['full_name'] = $request->input('name') ?? 'Baby ' . $deliverySummary->pregnancy->patient->full_name;
            $patientData['gender'] = $request->input('sex');
            $patientData['phone'] = $deliverySummary->pregnancy->patient->phone;
            $patientData['address'] = $deliverySummary->pregnancy->patient->address;
            $patientData['registration_no'] = $this->generateUniqueRegistrationNumber();
            $patientData['mother_id'] = $deliverySummary->pregnancy->patient->id;

            $patient = Patient::create($patientData);
            $patient->newborns()->create($validated);
            DB::commit();
            return response()->json(['message' => 'New Born created successfully.', 'visit_id' => $deliverySummary->visit_id], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create New Born request. Please try again.', 'message' => $th->getMessage()], 500);
        }
    }

    private function generateUniqueRegistrationNumber(): string
    {
        $prefix = trim(BusinessSetting::where('key', 'patient_reg_prefix')->first()?->value ?? '');
        $suffix = trim(BusinessSetting::where('key', 'patient_reg_suffix')->first()?->value ?? '');

        do {
            $code = strtoupper($prefix . date('ymd') . mt_rand(1000, 9999) . $suffix);
        } while (Patient::where('registration_no', $code)->exists());

        return $code;
    }
    public function update(Request $request, $id)
    {
        $newborn = Newborn::findOrFail($id);

        $validated = $request->validate([
            'delivery_summary_id'        => 'required|exists:delivery_summaries,id',
            'name'                       => 'nullable|string|max:255',
            'bcg_date'                   => 'nullable|date',
            'polio_0'                    => 'nullable|boolean',
            'vit_k'                      => 'nullable|boolean',
            'ttc'                        => 'nullable|boolean',
            'baby_mother_bonding'        => 'nullable|boolean',
            'para'                       => 'nullable|integer',
            'prom'                       => 'nullable|boolean',
            'prom_hours'                 => 'nullable|integer',
            'birth_weight'               => 'nullable|numeric|min:0|max:999.99',
            'temp'                       => 'nullable|numeric|min:0|max:999.99',
            'pr'                         => 'nullable|integer',
            'rr'                         => 'nullable|integer',
            'hiv_counts_and_testing_offered' => 'nullable|boolean',
            'hiv_testing_accepted'       => 'nullable|boolean',
            'hiv_test_result'            => 'nullable|string|max:255',
            'arv_px_mother'              => 'nullable|string|max:255',
            'arv_px_newborn'             => 'nullable|string|max:255',
            'apgar_score'                => 'nullable|string|max:255',
            'sex'                        => 'nullable|in:male,female',
            'length_cm'                  => 'nullable|numeric|min:0|max:999.99',
            'head_circumference_cm'      => 'nullable|numeric|min:0|max:999.99',
            'term_status'                => 'nullable|in:Term,Preterm',
            'resuscitated'               => 'nullable|boolean',
            'dysmorphic_faces'           => 'nullable|boolean',
            'neonatal_evaluation'        => 'nullable|string',
            'plan'                       => 'nullable|string',
            'remarks'                    => 'nullable|string',
        ]);

        // Handle checkboxes: default to 0 if unchecked
        $checkboxes = [
            'polio_0',
            'vit_k',
            'ttc',
            'baby_mother_bonding',
            'prom',
            'resuscitated',
            'dysmorphic_faces',
            'hiv_counts_and_testing_offered',
            'hiv_testing_accepted'
        ];
        foreach ($checkboxes as $field) {
            $validated[$field] = $request->has($field) ? 1 : 0;
        }

        DB::beginTransaction();
        try {
            // Update newborn record
            $newborn->update($validated);

            // Optionally update patient info if needed
            $patient = $newborn->patient;
            if ($patient) {
                $patient->update([
                    'full_name' => $request->input('name') ?? $patient->full_name,
                    'gender'    => $request->input('sex') ?? $patient->gender,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Newborn record updated successfully.',
                'visit_id' => $newborn->deliverySummary->visit_id
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update newborn record. Please try again.',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

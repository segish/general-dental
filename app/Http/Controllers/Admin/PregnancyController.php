<?php

namespace App\Http\Controllers\admin;

use App\Models\Pregnancy;
use App\Models\PrenatalCheckup;
use App\Models\Newborn;
use App\Models\PastPregnancy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Patient;

class PregnancyController extends Controller
{
    // Store a new pregnancy
    public function store(Request $request)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:visits,id|unique:pregnancies,visit_id',
            'patient_id' => 'required|exists:patients,id',
            'lmp' => 'nullable|date',
            'edd' => 'nullable|date',
            'gravida' => 'nullable|integer',
            'para' => 'nullable|integer',
            'children_alive' => 'nullable|integer',
            'anc_reg_no' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,Prefer not to say',
            'status' => 'required|in:ongoing,completed,aborted',
            'booking_bp_diastolic' => 'nullable|integer',
            'last_birth_weight_kg' => 'nullable|numeric',

            // Booleans (checkboxes)
            'is_high_risk' => 'boolean',
            'previous_stillbirth_or_neonatal_loss' => 'boolean',
            'hypertension_in_last_pregnancy' => 'boolean',
            'reproductive_tract_surgery' => 'boolean',
            'multiple_pregnancy' => 'boolean',
            'rh_issue' => 'boolean',
            'vaginal_bleeding' => 'boolean',
            'pelvic_mass' => 'boolean',
            'diabetes' => 'boolean',
            'renal_disease' => 'boolean',
            'cardiac_disease' => 'boolean',
            'chronic_hypertension' => 'boolean',
            'substance_abuse' => 'boolean',

            // Optional text
            'serious_medical_disease' => 'nullable|string',
            'remarks' => 'nullable|string',

            // Count
            'spontaneous_abortions_count' => 'nullable|integer',
        ]);

        // Set default checkbox values if not submitted
        foreach (
            [
                'is_high_risk',
                'previous_stillbirth_or_neonatal_loss',
                'hypertension_in_last_pregnancy',
                'reproductive_tract_surgery',
                'multiple_pregnancy',
                'rh_issue',
                'vaginal_bleeding',
                'pelvic_mass',
                'diabetes',
                'renal_disease',
                'cardiac_disease',
                'chronic_hypertension',
                'substance_abuse',
            ] as $field
        ) {
            $data[$field] = $request->has($field);
        }

        try {

            $patient = Patient::findOrFail($data['patient_id']);
            $data['mother_age'] = $patient->age;
            $pregnancy = Pregnancy::create($data);

            if (!empty($data['marital_status']) && $pregnancy->patient) {
                $pregnancy->patient->update(['marital_status' => $data['marital_status']]);
            }

            return response()->json([
                'message' => 'Pregnancy record created successfully.',
                'pregnancy' => $pregnancy,
                'visit_id' => $pregnancy->visit_id
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating pregnancy record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:pregnancies,visit_id',
            'patient_id' => 'required|exists:patients,id',
            'lmp' => 'nullable|date',
            'edd' => 'nullable|date',
            'gravida' => 'nullable|integer',
            'para' => 'nullable|integer',
            'children_alive' => 'nullable|integer',
            'anc_reg_no' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,Prefer not to say',
            'status' => 'required|in:ongoing,completed,aborted',
            'booking_bp_diastolic' => 'nullable|integer',
            'last_birth_weight_kg' => 'nullable|numeric',

            // Booleans
            'is_high_risk' => 'boolean',
            'previous_stillbirth_or_neonatal_loss' => 'boolean',
            'hypertension_in_last_pregnancy' => 'boolean',
            'reproductive_tract_surgery' => 'boolean',
            'multiple_pregnancy' => 'boolean',
            'rh_issue' => 'boolean',
            'vaginal_bleeding' => 'boolean',
            'pelvic_mass' => 'boolean',
            'diabetes' => 'boolean',
            'renal_disease' => 'boolean',
            'cardiac_disease' => 'boolean',
            'chronic_hypertension' => 'boolean',
            'substance_abuse' => 'boolean',

            'serious_medical_disease' => 'nullable|string',
            'remarks' => 'nullable|string',

            'spontaneous_abortions_count' => 'nullable|integer',
        ]);

        $pregnancy = Pregnancy::findOrFail($id);

        // Set checkboxes manually
        foreach (
            [
                'is_high_risk',
                'previous_stillbirth_or_neonatal_loss',
                'hypertension_in_last_pregnancy',
                'reproductive_tract_surgery',
                'multiple_pregnancy',
                'rh_issue',
                'vaginal_bleeding',
                'pelvic_mass',
                'diabetes',
                'renal_disease',
                'cardiac_disease',
                'chronic_hypertension',
                'substance_abuse',
            ] as $field
        ) {
            $data[$field] = $request->has($field);
        }

        $pregnancy->update($data);

        if (!empty($data['marital_status']) && $pregnancy->patient) {
            $pregnancy->patient->update(['marital_status' => $data['marital_status']]);
        }

        return response()->json(['message' => 'Pregnancy record updated successfully.', 'visit_id' => $pregnancy->visit_id], 200);
    }
}

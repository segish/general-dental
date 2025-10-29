<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrenatalVisit;
use Illuminate\Support\Facades\Validator;

class PrenatalVisitController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->validate([
            'visit_id' => 'required|exists:visits,id|unique:prenatal_visits,visit_id',
            'pregnancy_id' => 'required|exists:pregnancies,id',
            'gestational_age' => 'nullable|integer',
            'bp' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'pallor' => 'nullable|string',
            'uterine_height' => 'nullable|string',
            'fetal_heart_beat' => 'nullable|string',
            'presentation' => 'nullable|string',
            'urine_infection' => 'nullable|string',
            'urine_protein' => 'nullable|string',
            'rapid_syphilis_test' => 'nullable|string',
            'hemoglobin' => 'nullable|string',
            'blood_group_rh' => 'nullable|string',
            'tt_dose' => 'nullable|string',
            'iron_folic_acid' => 'boolean',
            'mebendazole' => 'boolean',
            'tin_use' => 'boolean',
            'arv_px_type' => 'nullable|string',
            'remarks' => 'nullable|string',
            'danger_signs' => 'nullable|string',
            'action_advice_counseling' => 'nullable|string',
            'next_follow_up' => 'nullable|date',
        ]);

        foreach (
            [
                'iron_folic_acid',
                'mebendazole',
                'tin_use',
            ] as $field
        ) {
            $data[$field] = $request->has($field);
        }

        try {
            $visit = PrenatalVisit::create($data);
            return response()->json(['message' => 'Pregnancy Folllow Up Created Successfully.', 'visit_id' => $visit->visit_id], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create visit: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $visit = PrenatalVisit::findOrFail($id);
        return response()->json($visit);
    }

    public function update(Request $request, $id)
    {
        $visit = PrenatalVisit::findOrFail($id);

        $data = $request->validate([
            'pregnancy_id' => 'required|exists:pregnancies,id',
            'gestational_age' => 'nullable|integer',
            'bp' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'pallor' => 'nullable|string',
            'uterine_height' => 'nullable|string',
            'fetal_heart_beat' => 'nullable|string',
            'presentation' => 'nullable|string',
            'urine_infection' => 'nullable|string',
            'urine_protein' => 'nullable|string',
            'rapid_syphilis_test' => 'nullable|string',
            'hemoglobin' => 'nullable|string',
            'blood_group_rh' => 'nullable|string',
            'tt_dose' => 'nullable|string',
            'iron_folic_acid' => 'nullable|boolean',
            'mebendazole' => 'nullable|boolean',
            'tin_use' => 'nullable|boolean',
            'arv_px_type' => 'nullable|string',
            'remarks' => 'nullable|string',
            'danger_signs' => 'nullable|string',
            'action_advice_counseling' => 'nullable|string',
            'next_follow_up' => 'nullable|date',
        ]);


        // Set checkboxes manually
        foreach (
            [
                'iron_folic_acid',
                'mebendazole',
                'tin_use',
            ] as $field
        ) {
            $data[$field] = $request->has($field);
        }

        $visit->update($data);

        return response()->json(['success' => true, 'visit_id' => $visit]);
    }
}

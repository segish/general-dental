<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewMenuTestResultCreated;
use App\Http\Controllers\Controller;
use App\Models\NurseAssessment;
use Illuminate\Http\Request;
use App\Models\AssessmentCategory;
use App\Models\BusinessSetting;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;

class NurseAssessmentController extends Controller
{
    public function __construct(private NurseAssessment $nurseAssessment)
    {
        $this->middleware('checkAdminPermission:nurse_assessment.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:nurse_assessment.add-new,index')->only(['index', 'store']);
    }

    // Load Modal with Vital Signs
    public function index()
    {
        $vitalSigns = AssessmentCategory::where('category_type', 'Vital Sign')->with('unit')->get();
        return view('admin.nurse_assessment.index', compact('vitalSigns'));
    }


    public function store(Request $request)
    {
        $vitalSignData = [];
        $visit = Visit::findOrFail($request->visit_id);

        foreach ($request->input('test_values') as $signId => $testValue) {
            if (!empty($testValue)) {
                $vitalSignData[] = [
                    'nurse_id' => $request->nurse_id,
                    'visit_id' => $request->visit_id,
                    'category_id' => $signId,
                    'test_name' => AssessmentCategory::find($signId)->name,
                    'test_value' => $testValue,
                    'unit_name' => $request->input('unit_names.' . $signId),
                    'notes' => $request->notes,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($vitalSignData)) {
            NurseAssessment::insert($vitalSignData);
        }

        if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
            event(new NewMenuTestResultCreated(
                'New Vital Sign reported for ' . $visit->patient->full_name,
                '/admin/patient/view/' . $visit->patient->id . '?active=' . $visit->id,
                'New Vital Sign',
                'nurse_assessment.list'
            ));
        }

        return response()->json(['message' => 'Vital signs recorded successfully!', 'visit_id' => $request->visit_id], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'test_value' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $assessment = NurseAssessment::findOrFail($id);

        $assessment->update([
            'test_value' => $request->test_value,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Assessment updated successfully!',
            'visit_id' => $assessment->visit_id
        ]);
    }
}

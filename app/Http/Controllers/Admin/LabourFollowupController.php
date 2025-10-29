<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NurseAssessment;
use Illuminate\Http\Request;
use App\Models\AssessmentCategory;
use App\Models\LabourFollowup;
use Illuminate\Support\Facades\Auth;

class LabourFollowupController extends Controller
{
    public function __construct(private LabourFollowup $labourFollowup)
    {
        $this->middleware('checkAdminPermission:labour_followup.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:labour_followup.add-new,index')->only(['index', 'store']);
    }

    public function store(Request $request)
    {
        $vitalSignData = [];

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
            LabourFollowup::insert($vitalSignData);
        }

        return response()->json(['message' => 'Labour Followup recorded successfully!', 'visit_id' => $request->visit_id], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'test_value' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $assessment = LabourFollowup::findOrFail($id);

        $assessment->update([
            'test_value' => $request->test_value,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Labour Followup updated successfully!',
            'visit_id' => $assessment->visit_id
        ]);
    }
}

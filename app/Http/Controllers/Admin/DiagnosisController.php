<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DiagnosisController extends Controller
{
    public function __construct(private DiagnosisTreatment $diagnosisTreatment)
    {
        $this->middleware('checkAdminPermission:diagnosis.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:diagnosis.add-new,index')->only(['index', 'store']); // Added store method
    }

    public function index()
    {
        // Your index logic if needed
        return view('admin.diagnosis.index'); // Replace with your view
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visit_id' => 'required|exists:visits,id',
            'doctor_id' => 'required|exists:admins,id',
            'diagnosis' => 'required|string',
            'treatment' => 'nullable|string',
            'condition_ids' => 'nullable|array', // multiple diseases
            'condition_ids.*' => 'exists:medical_conditions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Create the main diagnosis_treatment record
            $diagnosis = $this->diagnosisTreatment->create([
                'visit_id' => $request->visit_id,
                'doctor_id' => $request->doctor_id,
                'diagnosis' => $request->diagnosis,
                'treatment' => $request->treatment,
            ]);

            // Attach diseases (optional)
            if ($request->has('condition_ids')) {
                $diagnosis->diseases()->sync($request->condition_ids);
            }

            return response()->json(['message' => 'Diagnosis & Treatment added successfully', 'visit_id' => $diagnosis->visit_id], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function list()
    {
        // Your list logic if needed
    }
    public function edit($id)
    {
        try {
            $diagnosis = $this->diagnosisTreatment->with('diseases')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $diagnosis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving medical record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {

        // return response()->json($request->all(), 500);
        $validator = Validator::make($request->all(), [
            'diagnosis' => 'required|string',
            'treatment' => 'nullable|string',
            'condition_ids' => 'nullable|array',
            'condition_ids.*' => 'exists:medical_conditions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $diagnosis = $this->diagnosisTreatment->findOrFail($id);

            DB::beginTransaction();

            $diagnosis->update([
                'diagnosis' => $request->diagnosis,
                'treatment' => $request->treatment,
            ]);

            // Sync conditions if provided
            if ($request->has('condition_ids')) {
                $diagnosis->diseases()->sync($request->condition_ids);
            } else {
                $diagnosis->diseases()->detach(); // Remove all if not provided
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Diagnosis & Treatment updated successfully', 'visit_id' => $diagnosis->visit_id], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}

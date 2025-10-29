<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalRecordController extends Controller
{
    public function __construct(private MedicalRecord $medicalRecord)
    {
        $this->middleware('checkAdminPermission:medical_record.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:medical_record.add-new,index')->only(['index', 'store']);
        $this->middleware('checkAdminPermission:medical_record.edit,edit')->only(['edit']);
        $this->middleware('checkAdminPermission:medical_record.update,update')->only(['update']);
    }

    public function index()
    {
        // Your index logic if needed
        return view('admin.medical_records.index'); // Replace with your view
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visit_id' => 'required|exists:visits,id',
            'doctor_id' => 'required|exists:admins,id',
            'chief_complaint' => 'required|string',
            'symptoms' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->medicalRecord->create($request->all());
            return response()->json(['message' => 'Medical record created successfully', 'visit_id' => $request->visit_id], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating medical record: ' . $e->getMessage()], 500);
        }
    }

    public function list()
    {
        // Your list logic if needed
    }

    public function edit($id)
    {
        try {
            $medicalRecord = $this->medicalRecord->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $medicalRecord
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
        $validator = Validator::make($request->all(), [
            'chief_complaint' => 'required|string',
            'symptoms' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $medicalRecord = $this->medicalRecord->findOrFail($id);
            $medicalRecord->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Medical record updated successfully',
                'visit_id' => $medicalRecord->visit_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating medical record: ' . $e->getMessage()
            ], 500);
        }
    }
}

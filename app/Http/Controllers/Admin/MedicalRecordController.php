<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordField;
use App\Models\MedicalRecordValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get all active fields
        $fields = MedicalRecordField::active()->ordered()->with('options')->get();

        // Build dynamic validation rules
        $dynamicRules = [];
        foreach ($fields as $field) {
            $ruleKey = 'field_' . $field->short_code;
            if ($field->is_required) {
                $dynamicRules[$ruleKey] = 'required';
            } else {
                $dynamicRules[$ruleKey] = 'nullable';
            }

            // Add validation for array types
            if (in_array($field->field_type, ['multiselect', 'checkbox'])) {
                $dynamicRules[$ruleKey] .= '|array';
            }
        }

        $validator = Validator::make($request->all(), array_merge([
            'visit_id' => 'required|exists:visits,id',
            'doctor_id' => 'required|exists:admins,id',
        ], $dynamicRules));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Check if medical record already exists for this visit
            $medicalRecord = MedicalRecord::where('visit_id', $request->visit_id)->first();

            if (!$medicalRecord) {
                $medicalRecord = MedicalRecord::create([
                    'visit_id' => $request->visit_id,
                    'doctor_id' => $request->doctor_id,
                ]);
            } else {
                // Update doctor if changed
                $medicalRecord->update(['doctor_id' => $request->doctor_id]);
            }

            // Save field values
            foreach ($fields as $field) {
                $fieldKey = 'field_' . $field->short_code;
                $value = $request->input($fieldKey);

                if ($value !== null) {
                    // Handle array values (multiselect, checkbox)
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }

                    MedicalRecordValue::updateOrCreate(
                        [
                            'medical_record_id' => $medicalRecord->id,
                            'medical_record_field_id' => $field->id,
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Medical record created successfully',
                'visit_id' => $request->visit_id
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating medical record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        // Your list logic if needed
    }

    public function edit($id)
    {
        try {
            $medicalRecord = $this->medicalRecord->with(['values.field.options'])->findOrFail($id);

            // Build data array with field values
            $data = [
                'id' => $medicalRecord->id,
                'visit_id' => $medicalRecord->visit_id,
                'doctor_id' => $medicalRecord->doctor_id,
            ];

            // Add field values
            foreach ($medicalRecord->values as $value) {
                $fieldKey = 'field_' . $value->field->short_code;
                $data[$fieldKey] = $value->decoded_value;
            }

            return response()->json([
                'success' => true,
                'data' => $data
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
        $medicalRecord = $this->medicalRecord->findOrFail($id);

        // Get all active fields
        $fields = MedicalRecordField::active()->ordered()->with('options')->get();

        // Build dynamic validation rules
        $dynamicRules = [];
        foreach ($fields as $field) {
            $ruleKey = 'field_' . $field->short_code;
            if ($field->is_required) {
                $dynamicRules[$ruleKey] = 'required';
            } else {
                $dynamicRules[$ruleKey] = 'nullable';
            }

            // Add validation for array types
            if (in_array($field->field_type, ['multiselect', 'checkbox'])) {
                $dynamicRules[$ruleKey] .= '|array';
            }
        }

        $validator = Validator::make($request->all(), $dynamicRules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Update field values
            foreach ($fields as $field) {
                $fieldKey = 'field_' . $field->short_code;
                $value = $request->input($fieldKey);

                if ($value !== null) {
                    // Handle array values (multiselect, checkbox)
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }

                    MedicalRecordValue::updateOrCreate(
                        [
                            'medical_record_id' => $medicalRecord->id,
                            'medical_record_field_id' => $field->id,
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                } else {
                    // Remove value if null
                    MedicalRecordValue::where('medical_record_id', $medicalRecord->id)
                        ->where('medical_record_field_id', $field->id)
                        ->delete();
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Medical record updated successfully',
                'visit_id' => $medicalRecord->visit_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating medical record: ' . $e->getMessage()
            ], 500);
        }
    }
}

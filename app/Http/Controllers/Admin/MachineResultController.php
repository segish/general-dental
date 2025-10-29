<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specimen;
use App\Models\TestResult;
use App\Models\TestResultAttribute;
use App\Models\TestAttributeReference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\ReferenceEvaluatorService;
use App\Events\TestResultCreated;
use App\Events\NewMenuTestResultCreated;
use App\Models\BusinessSetting;

class MachineResultController extends Controller
{
    public function store(Request $request)
    {
        Log::info('MachineResultController@store - Incoming request', [
            'request_data' => $request->all()
        ]);

        try {
            $data = $request->validate([
                'sample_id' => 'required|string',
                'results' => 'required|array',
            ]);
            Log::info('Validation passed', ['validated_data' => $data]);
        } catch (\Exception $e) {
            Log::error('Validation failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 422);
        }

        DB::beginTransaction();

        try {
            $sampleCode = $data['sample_id'];
            $specimen = null;
            $specimen = Specimen::with('laboratoryRequestTests.test.attributes')
                ->where('specimen_code', $sampleCode)
                ->first();

            if (!$specimen && strlen($sampleCode) <= 6) {
                $paddedCode = str_pad($sampleCode, 6, '0', STR_PAD_LEFT);
                $day = substr($paddedCode, 0, 2);
                $serial = substr($paddedCode, 2);
                $tryDates = [now(), now()->subDay(), now()->subDays(2)];

                foreach ($tryDates as $date) {
                    $prefix = $date->format('ym');
                    $fullCode = $prefix . $day . $serial;

                    $specimen = Specimen::with('laboratoryRequestTests.test.attributes')
                        ->where('specimen_code', $fullCode)
                        ->first();

                    if ($specimen) break;
                }
            }

            if (!$specimen) {
                throw new \Exception("Specimen not found for sample ID: {$sampleCode}");
            }

            Log::info('Specimen retrieved successfully', ['specimen_id' => $specimen->id]);

            $evaluator = new ReferenceEvaluatorService();
            $firstLabTest = $specimen->laboratoryRequestTests->first();
            $patient = $firstLabTest?->laboratoryRequest?->visit?->patient;
            $visit = $firstLabTest?->laboratoryRequest?->visit;
            $gender = $patient?->gender;
            $age = $patient?->age;

            $allTestsProcessed = true;
            $errors = [];

            foreach ($specimen->laboratoryRequestTests as $labTest) {
                $existingResult = TestResult::where('laboratory_request_test_id', $labTest->id)->first();
                if ($existingResult) {
                    continue;
                }

                $attributes = $labTest->test->attributes;

                $foundAttributes = [];

                foreach ($attributes as $attribute) {
                    $attributeName = $attribute->attribute_name;
                    if (array_key_exists($attributeName, $data['results'])) {
                        $foundAttributes[] = $attribute;
                    }
                }

                // ❌ Skip test if no attributes found at all
                if (empty($foundAttributes)) {
                    continue;
                }

                // ✅ Always create TestResult
                $testResult = TestResult::create([
                    'laboratory_request_test_id' => $labTest->id,
                    'result_status' => null,
                    'processed_by' => null,
                    'process_status' => 'completed',
                    'process_end_time' => Carbon::now(),
                ]);

                foreach ($labTest->test->attributes as $attribute) {
                    $attributeName = $attribute->attribute_name;

                    if (!array_key_exists($attributeName, $data['results'])) {
                        $errors[] = "Missing attribute '{$attributeName}' for test ID {$labTest->id}";
                        $allTestsProcessed = false;
                        continue;
                    }

                    $resultValue = is_array($data['results'][$attributeName])
                        ? implode(',', $data['results'][$attributeName])
                        : $data['results'][$attributeName];

                    $references = TestAttributeReference::where('test_attribute_id', $attribute->id)->get();

                    $referenceData = $references->map(function ($ref) {
                        return [
                            'gender' => $ref->gender,
                            'min_age' => $ref->min_age,
                            'max_age' => $ref->max_age,
                            'is_pregnant' => $ref->is_pregnant,
                            'min_value' => $ref->min_value,
                            'max_value' => $ref->max_value,
                            'reference_text' => $ref->reference_text,
                            'is_default' => $ref->is_default,
                        ];
                    })->toArray();

                    $evaluation = 'N/A';
                    if ($references->isNotEmpty()) {
                        $evaluation = $evaluator->evaluate(
                            $attribute->id,
                            $resultValue,
                            $gender,
                            $age
                        );
                    }

                    TestResultAttribute::create([
                        'test_result_id' => $testResult->id,
                        'attribute_id' => $attribute->id,
                        'result_value' => $resultValue,
                        'reference_values' => $referenceData,
                        'comments' => $evaluation,
                    ]);
                }
            }

            DB::commit();

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                if (!$allTestsProcessed) {
                    event(new NewMenuTestResultCreated(
                        'Half Processed Test Result added for ' . $visit->patient->full_name . ' from the machine',
                        '/admin/patient/view/' . $visit->patient->id . '?active=' . $visit->id,
                        'Half Processed Test Result',
                        'laboratory_result.list'
                    ));
                }
                return response()->json([
                    'message' => 'Some results were saved, but some attributes were missing.',
                    'errors' => $errors
                ], 207); // Multi-Status
            }


            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                if ($visit) {
                    event(new NewMenuTestResultCreated(
                        'New Test Result added for ' . $visit->patient->full_name . ' from the machine',
                        '/admin/patient/view/' . $visit->patient->id . '?active=' . $visit->id,
                        'New Test Result',
                        'laboratory_result.list'
                    ));
                }
            }

            return response()->json(['message' => 'Results saved successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

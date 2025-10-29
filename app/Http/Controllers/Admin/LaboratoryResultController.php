<?php

namespace App\Http\Controllers\admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TestResult;
use App\Models\TestResultAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Events\NewMenuTestResultCreated;
use App\Models\BusinessSetting;
use App\Models\LaboratoryRequest;
use App\Models\PatientReport;
use App\Models\Specimen;
use App\Models\Test;
use App\Models\TestAttributeReference;
use App\Services\ReferenceEvaluatorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaboratoryResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function viewTestResult($testResultId)
    {
        // Retrieve the test result by its ID
        $testResult = TestResult::with('laboratoryRequestTest.test') // Eager load test relation
            ->find($testResultId);

        if (!$testResult) {
            return response()->json(['error' => 'Test result not found.'], 404);
        }

        // Get the related test result attributes
        $attributes = TestResultAttribute::with('attribute.options') // Assuming the attributes have a relation to 'testAttribute'
            ->where('test_result_id', $testResultId)
            ->get();

        // Return the test result and attributes as a JSON response
        return response()->json([
            'testResult' => [
                'id' => $testResult->id,
                'process_status' => $testResult->process_status,
                'verify_status' => $testResult->verify_status,
                'comments' => $testResult->comments,
                'additional_note' => $testResult->additional_note,
                'result_status' => $testResult->result_status,
                'image' => $testResult->image ? json_decode($testResult->image) : [],
            ],
            'attributes' => $attributes->map(function ($attribute) {
                return [
                    'id' => $attribute->attribute->id,
                    'default_required' => $attribute->attribute->default_required,
                    'name' => $attribute->attribute->attribute_name, // Assuming a 'testAttribute' relation exists
                    'has_options' => $attribute->attribute->has_options, // Assuming a 'testAttribute' relation exists
                    'options' => $attribute->attribute->options,
                    'selected_option_value' => $attribute->selected_option_value,
                    'result_value' => $attribute->result_value,
                    'comments' => $attribute->comments,
                ];
            })
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'laboratory_request_test_id' => 'required|exists:laboratory_request_test,id',
            'processed_by' => 'required|exists:admins,id',
            'additional_note' => 'nullable|string',
            'comments' => 'nullable|string',
            'result_status' => 'nullable|in:Normal,Abnormal,Critical,Pending,Inconclusive,Positive,Negative,Reactive,Non-Reactiv,Indeterminate',
            'images.*' => 'nullable|file|mimes:png,jpg,jpeg,dcm',
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();

            $img_names = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $image_data = Helpers::upload('lab_results', 'png', $img);
                    $img_names[] = $image_data; // Store the image name or path in the array
                }
            }

            // Store test result
            $testResult = TestResult::create([
                'laboratory_request_test_id' => $request->input('laboratory_request_test_id'),
                'additional_note' => $request->input('additional_note'),
                'comments' => $request->input('comments'),
                'result_status' => $request->input('result_status'),
                'processed_by' => $request->input('processed_by'),
                'process_end_time' => now(),
                'process_status' => 'completed',
                'image' => $img_names, // Save the array directly; Laravel will JSON-encode it
            ]);


            // Handle attributes
            $evaluator = new ReferenceEvaluatorService();

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^attribute_(\d+)$/', $key, $matches)) {
                    $attributeId = $matches[1];

                    $resultValue = is_array($value) ? null : $value;
                    $selectedOptionValue = is_array($value) ? implode(',', $value) : null;

                    // Get all references
                    $references = TestAttributeReference::where('test_attribute_id', $attributeId)->get();

                    // Format references for logging
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

                    // Get patient data for evaluation
                    $patient = $testResult->laboratoryRequestTest->laboratoryRequest->visit->patient;
                    $gender = $patient->gender ?? null;
                    $age = $patient->age ?? null;

                    // Call the evaluator service
                    $evaluation = 'N/A';
                    if ($references->isNotEmpty()) {
                        $evaluation = $evaluator->evaluate(
                            $attributeId,
                            $resultValue ?? $selectedOptionValue,
                            $gender,
                            $age
                        );
                    }

                    // Save result and evaluation
                    TestResultAttribute::create([
                        'test_result_id' => $testResult->id,
                        'attribute_id' => $attributeId,
                        'result_value' => $resultValue ?? $selectedOptionValue,
                        'reference_values' => $referenceData,
                        'comments' => $evaluation, // Store the evaluation result here
                    ]);
                }
            }


            // Load the relationships we need
            // $orderToBroadcast = MenueOrder::with('items.product')->find($order->id);
            // Commit the transaction
            DB::commit();
            // DB::rollBack();

            return response()->json(['message' => 'Test result stored successfully.', 'visit_id' => $testResult->laboratoryRequestTest->laboratoryRequest->visit_id], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            return response()->json(['error' => 'Failed to store test result.', 'message' => $e->getMessage()], 500);
        }
    }


    public function storeCustomResult(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'laboratory_request_id' => 'required|exists:laboratory_requests,id',
            'tests' => 'required|array',
            'tests.*.attributes' => 'required|array',
            'tests.*.attributes.laboratory_request_test_id' => 'required|exists:laboratory_request_test,id',
            'tests.*.attributes.images.*' => 'nullable|file|mimes:png,jpg,jpeg,dcm',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->tests as $test) {
                $attributes = $test['attributes']; // Access as array

                // Check if there's at least one value to process
                $hasValues = false;


                foreach ($attributes as $key => $value) {
                    if (preg_match('/^attribute_\d+$/', $key) && !is_null($value)) {
                        $hasValues = true;
                        break;
                    }
                }

                // Check additional_note
                if (!$hasValues && !empty($attributes['additional_note'])) {
                    $hasValues = true;
                }

                // Check images
                if (!$hasValues && isset($attributes['images']) && is_array($attributes['images']) && count($attributes['images']) > 0) {
                    $hasValues = true;
                }


                // Skip this test if nothing to process
                if (!$hasValues) {
                    continue;
                }

                $testForTime = Test::findOrFail($attributes['test_id']);
                $timeOffset = Carbon::now()
                    ->subHours($testForTime->time_taken_hour)
                    ->subMinutes($testForTime->time_taken_min);
                // store specimen
                $specimen = Specimen::create([
                    'specimen_code' => $this->generateUniqueSpecimenCode(),
                    'checker_id' => auth('admin')->user()->id,
                    'laboratory_request_id' => $request->laboratory_request_id,
                    'status' => 'accepted',
                    'checking_start_time' => $timeOffset,
                    'specimen_taken_at' => $timeOffset,
                    'checking_end_time' => now(),
                ]);

                $specimen->laboratoryRequestTests()->attach($attributes['laboratory_request_test_id']);

                // Handle images if provided
                $img_names = [];
                if (isset($attributes['images']) && is_array($attributes['images'])) {
                    foreach ($attributes['images'] as $img) {
                        $image_data = Helpers::upload('lab_results', 'png', $img);
                        $img_names[] = $image_data;
                    }
                }

                // Store test result
                $testResult = TestResult::create([
                    'laboratory_request_test_id' => $attributes['laboratory_request_test_id'],
                    'additional_note' => $attributes['additional_note'] ?? null, // root-level
                    'comments' => $attributes['comments'] ?? null,
                    'result_status' => $attributes['result_status'] ?? null,
                    'processed_by' => auth('admin')->user()->id,
                    'process_end_time' => now(),
                    'process_status' => 'completed',
                    'image' => $img_names, // Will be JSON encoded automatically
                ]);

                // Handle attribute results
                $evaluator = new ReferenceEvaluatorService();
                foreach ($attributes as $key => $value) {
                    if (preg_match('/^attribute_(\d+)$/', $key, $matches)) {
                        $attributeId = $matches[1];

                        $resultValue = is_array($value) ? null : $value;
                        $selectedOptionValue = is_array($value) ? implode(',', $value) : null;

                        // Get all references for this attribute
                        $references = TestAttributeReference::where('test_attribute_id', $attributeId)->get();

                        // Format reference data
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

                        // Get patient data for evaluation
                        $patient = $testResult->laboratoryRequestTest->laboratoryRequest->visit->patient ?? null;
                        $gender = $patient->gender ?? null;
                        $age = $patient->age ?? null;

                        $evaluation = 'N/A';
                        if ($references->isNotEmpty()) {
                            $evaluation = $evaluator->evaluate(
                                $attributeId,
                                $resultValue ?? $selectedOptionValue,
                                $gender,
                                $age
                            );
                        }

                        // Store each attribute result
                        TestResultAttribute::create([
                            'test_result_id' => $testResult->id,
                            'attribute_id' => $attributeId,
                            'result_value' => $resultValue ?? $selectedOptionValue,
                            'reference_values' => $referenceData,
                            'comments' => $evaluation, // store evaluation result
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Test result stored successfully.',
                'visit_id' => $testResult->laboratoryRequestTest->laboratoryRequest->visit_id
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to store test result.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueSpecimenCode(): string
    {
        $datePart = now()->format('ymd'); // e.g. "250708"
        $serial = 1;

        do {
            $serialPart = str_pad($serial, 4, '0', STR_PAD_LEFT); // "0001", "0002", etc.
            $code = $datePart . $serialPart; // e.g. "2507080001"
            $exists = Specimen::where('specimen_code', $code)->exists();
            $serial++;
        } while ($exists);

        return $code;
    }


    public function updateProcessStatus(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'result_id' => 'required|exists:test_results,id',
                'process_status' => 'required|string|in:completed,in process,rejected,pending',
            ]);

            $testResult = TestResult::findOrFail($request->input('result_id'));

            // Update the status
            $testResult->process_status = $request->input('process_status');
            $testResult->save();

            return response()->json([
                'success' => true,
                'message' => 'Test result processed status updated successfully.',
                'visit_id' => $testResult->laboratoryRequestTest->laboratoryRequest->visit_id
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating test result processed status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update test result processed status. Please try again.',
            ], 500);
        }
    }

    public function updateVerifyStatus(Request $request)
    {
        try {
            $request->validate([
                'result_id' => 'required|exists:test_results,id',
                'processed_by' => 'nullable|exists:admins,id',
                'verify_status' => 'required|in:pending,checking,approved,rejected',
            ]);
            DB::beginTransaction();
            $testResult = TestResult::findOrFail($request->result_id);

            // Update the verify_status
            $testResult->verify_status = $request->verify_status;

            // Set verified_by when changing the status (assuming auth()->id() is the logged-in admin ID)
            if (in_array($request->verify_status, ['checking', 'approved', 'rejected'])) {
                $testResult->verified_by = auth('admin')->user()->id;
            }

            if (in_array($request->verify_status, ['approved', 'rejected'])) {
                $testResult->verify_end_time = now();
            }
            $testResult->processed_by = $request->processed_by ?? auth('admin')->user()->id;
            $testResult->save();

            // Update LaboratoryRequestTest status based on verify_status
            $labRequestTest = $testResult->laboratoryRequestTest;
            if ($labRequestTest) {
                switch ($request->verify_status) {
                    case 'approved':
                        $labRequestTest->status = 'completed';
                        break;
                    case 'rejected':
                        $labRequestTest->status = 'rejected';
                        break;
                    case 'checking':
                        $labRequestTest->status = 'in process';
                        break;
                    case 'pending':
                        $labRequestTest->status = 'pending';
                        break;
                }
                $labRequestTest->save();
            }

            $request = $labRequestTest->laboratoryRequest;
            $request = LaboratoryRequest::findOrFail($request->id);
            $request->status = 'in process';
            $request->save();
            $tests = $request->tests;

            // Check if all tests are completed
            $allCompleted = $tests->every(function ($test) {
                return $test->status === 'completed';
            });

            if ($allCompleted) {
                $request->status = 'completed';
                $request->save();
            }

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                if ($testResult->verify_status == 'approved') {
                    event(new NewMenuTestResultCreated(
                        'New Test Result added for ' . $testResult->laboratoryRequestTest->laboratoryRequest->visit->patient->full_name,
                        '/admin/patient/view/' . $testResult->laboratoryRequestTest->laboratoryRequest->visit->patient->id . '?active=' . $testResult->laboratoryRequestTest->laboratoryRequest->visit_id,
                        'New Test Result',
                        'laboratory_result.list'
                    ));
                }
            }

            DB::commit();
            return response()->json(['message' => 'Verify status and related test status updated successfully.', 'visit_id' => $testResult->laboratoryRequestTest->laboratoryRequest->visit_id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update verify status. Please try again.', 'message' => $th->getMessage()], 500);
        }
    }
    public function bulkUpdateVerifyStatus(Request $request)
    {
        try {
            $request->validate([
                'result_ids' => 'required|array',
                'result_ids.*' => 'exists:test_results,id',
                'processed_by' => 'nullable|exists:admins,id',
                'verify_status' => 'required|in:pending,checking,approved,rejected',
            ]);

            DB::beginTransaction();

            $visitId = null; // to return after processing all

            foreach ($request->result_ids as $resultId) {
                $testResult = TestResult::findOrFail($resultId);

                // Update verify_status
                $testResult->verify_status = $request->verify_status;

                // Set verified_by when changing the status
                if (in_array($request->verify_status, ['checking', 'approved', 'rejected'])) {
                    $testResult->verified_by = auth('admin')->user()->id;
                }

                if (in_array($request->verify_status, ['approved', 'rejected'])) {
                    $testResult->verify_end_time = now();
                }

                $testResult->processed_by = $request->processed_by ?? auth('admin')->user()->id;
                $testResult->save();

                // Update related LaboratoryRequestTest
                $labRequestTest = $testResult->laboratoryRequestTest;
                if ($labRequestTest) {
                    switch ($request->verify_status) {
                        case 'approved':
                            $labRequestTest->status = 'completed';
                            break;
                        case 'rejected':
                            $labRequestTest->status = 'rejected';
                            break;
                        case 'checking':
                            $labRequestTest->status = 'in process';
                            break;
                        case 'pending':
                            $labRequestTest->status = 'pending';
                            break;
                    }
                    $labRequestTest->save();

                    // Update LaboratoryRequest status
                    $labRequest = $labRequestTest->laboratoryRequest;
                    if ($labRequest) {
                        $labRequest->status = 'in process';
                        $labRequest->save();

                        $tests = $labRequest->tests;
                        $allCompleted = $tests->every(fn($test) => $test->status === 'completed');
                        if ($allCompleted) {
                            $labRequest->status = 'completed';
                            $labRequest->save();
                        }

                        $visitId = $labRequest->visit_id; // keep last one (all should belong to same visit usually)
                    }
                }
            }

            // Trigger event if live and approved
            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                if ($testResult->verify_status == 'approved') {
                    event(new NewMenuTestResultCreated(
                        'New Test Result added for ' . $testResult->laboratoryRequestTest->laboratoryRequest->visit->patient->full_name,
                        '/admin/patient/view/' . $testResult->laboratoryRequestTest->laboratoryRequest->visit->patient->id . '?active=' . $testResult->laboratoryRequestTest->laboratoryRequest->visit_id,
                        'New Test Result',
                        'laboratory_result.list'
                    ));
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Verify status updated successfully for selected test results.',
                'visit_id' => $visitId,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update verify status. Please try again.',
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    public function generatePdf($id)
    {
        $testResult = TestResult::findOrFail($id);

        $paperSize = $testResult->laboratoryRequestTest->test->paper_size ?? 'A4';
        $paperOrientation = $testResult->laboratoryRequestTest->test->paper_orientation ?? 'portrait';

        // Generate PDF with dynamic paper size and orientation
        // $pdf = Pdf::loadView('admin-views.pdf-components.main-pdf-viewer', [
        //     'testResult' => $testResult
        // ])->setPaper($paperSize, $paperOrientation);

        $sortedAttributes = collect($testResult->attributes)->sortBy(function ($item) {
            return $item->attribute->index ?? PHP_INT_MAX;
        });

        $pdf = Pdf::loadView('admin-views.pdf-components.main-pdf-viewer', [
            'testResult' => $testResult,
            'attributes' => $sortedAttributes,
        ])->setPaper($paperSize, $paperOrientation);

        return $pdf->stream('patientsReport.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline; filename="patientsReport.pdf"',
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }


    public function groupedPdf($laboratoryRequestId)
    {
        $laboratoryRequest = LaboratoryRequest::with([
            'visit.patient',
            'testResults2.laboratoryRequestTest.test',
        ])->findOrFail($laboratoryRequestId);

        $groupedTestResults = $laboratoryRequest->testResults2->filter(function ($result) {
            return optional($result->laboratoryRequestTest->test)->page_display === 'group'
                && $result->process_status === 'completed';
        });

        if ($groupedTestResults->isEmpty()) {
            return back()->with('error', 'No completed grouped test results available.');
        }

        $pdf = Pdf::loadView('admin-views.pdf-components.main-grouped-pdf-viewer', [
            'testResults' => $groupedTestResults,
            'visit' => $laboratoryRequest->visit,
            'laboratoryRequest' => $laboratoryRequest,
        ]);

        return $pdf->stream("Grouped_Test_Results_Request_{$laboratoryRequestId}.pdf");
    }

    public function downloadPdf(Request $request)
    {
        $query = PatientReport::query();

        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('service_date', [$request->from_date, $request->to_date]);
        }

        $patientsReport = $query->get();

        // Load the view for the PDF content
        $pdf = PDF::loadView('admin-views.out_Patient.pdf', compact('patientsReport'))
            ->setPaper('a4', 'landscape');

        // Force download the PDF
        return $pdf->download('patientsReport.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'additional_note' => 'nullable|string',
                'comments' => 'nullable|string',
                'result_status' => 'nullable|in:Normal,Abnormal,Critical,Pending,Inconclusive,Positive,Negative,Reactive,Non-Reactive,Indeterminate',
                'images.*' => 'nullable|file|mimes:png,jpg,jpeg',
            ]);

            // Start database transaction
            DB::beginTransaction();

            $testResult = TestResult::findOrFail($id);

            // Handle image uploads
            $img_names = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $image_data = Helpers::upload('assets/lab_results/', 'png', $img);
                    $img_names[] = $image_data;
                }

                // Update image field if new images were uploaded
                if (!empty($img_names)) {
                    $testResult->image = $img_names;
                }
            }

            // Update test result fields
            $testResult->additional_note = $request->input('additional_note');
            $testResult->comments = $request->input('comments');
            $testResult->result_status = $request->input('result_status');
            $testResult->save();

            // Handle attributes
            $evaluator = new ReferenceEvaluatorService();

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^attribute_(\d+)$/', $key, $matches)) {
                    $attributeId = $matches[1];

                    $resultValue = is_array($value) ? null : $value;
                    $selectedOptionValue = is_array($value) ? implode(',', $value) : null;

                    // Get all references
                    $references = TestAttributeReference::where('test_attribute_id', $attributeId)->get();

                    // Format references for logging
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

                    // Get patient data for evaluation
                    $patient = $testResult->laboratoryRequestTest->laboratoryRequest->visit->patient;
                    $gender = $patient->gender ?? null;
                    $age = $patient->age ?? null;

                    // Call the evaluator service
                    $evaluation = 'N/A';
                    if ($references->isNotEmpty()) {
                        $evaluation = $evaluator->evaluate(
                            $attributeId,
                            $resultValue ?? $selectedOptionValue,
                            $gender,
                            $age
                        );
                    }

                    // Find or create the attribute
                    $attribute = TestResultAttribute::where('test_result_id', $testResult->id)
                        ->where('attribute_id', $attributeId)
                        ->first();

                    if (!$attribute) {
                        $attribute = new TestResultAttribute([
                            'test_result_id' => $testResult->id,
                            'attribute_id' => $attributeId,
                        ]);
                    }

                    // Update attribute values
                    $attribute->result_value = $resultValue;
                    // $attribute->selected_option_value = $selectedOptionValue;
                    $attribute->reference_values = $referenceData;
                    $attribute->comments = $evaluation;
                    $attribute->save();
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Test result updated successfully.', 'visit_id' => $testResult->laboratoryRequestTest->laboratoryRequest->visit_id], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            Log::error('Error updating test result: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update test result.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $testResult = TestResult::findOrFail($id);

            // Delete associated attributes
            $testResult->attributes()->delete();

            // Delete the test result
            $testResult->delete();

            DB::commit();

            return response()->json(['message' => 'Test result deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete test result: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete test result'], 500);
        }
    }
}

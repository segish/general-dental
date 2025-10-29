<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RadiologyResult;
use App\Models\RadiologyResultAttribute;
use App\Models\RadiologyRequestTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Events\NewMenuTestResultCreated;
use App\Models\BusinessSetting;
use App\Models\RadiologyRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RadiologyResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $radiologyResults = RadiologyResult::with(['requestTest', 'processor', 'verifier'])
            ->latest()
            ->paginate(10);

        return view('admin.radiology-results.index', compact('radiologyResults'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.radiology-results.create');
    }

    /**
     * View a specific radiology result
     *
     * @param int $resultId
     * @return \Illuminate\Http\Response
     */
    public function viewResult($resultId)
    {
        // Retrieve the radiology result by its ID
        $radiologyResult = RadiologyResult::with(['requestTest', 'processor', 'verifier', 'attributes'])
            ->find($resultId);

        if (!$radiologyResult) {
            return response()->json(['error' => 'Radiology result not found.'], 404);
        }

        // Return the radiology result as a JSON response
        return response()->json([
            'radiologyResult' => [
                'process_status' => $radiologyResult->process_status,
                'verify_status' => $radiologyResult->verify_status,
                'result_status' => $radiologyResult->result_status,
                'comments' => $radiologyResult->comments,
                'additional_note' => $radiologyResult->additional_note,
                'image' => $radiologyResult->image ? json_decode($radiologyResult->image) : [],
                'processed_by' => $radiologyResult->processor ? $radiologyResult->processor->name : null,
                'verified_by' => $radiologyResult->verifier ? $radiologyResult->verifier->name : null,
                'process_end_time' => $radiologyResult->process_end_time,
                'verify_start_time' => $radiologyResult->verify_start_time,
                'verify_end_time' => $radiologyResult->verify_end_time,
            ],
            'attributes' => $radiologyResult->attributes->map(function ($attribute) {
                return [
                    'name' => $attribute->name,
                    'value' => $attribute->value,
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
            'radiology_request_test_id' => 'required|exists:radiology_request_test,id',
            'processed_by' => 'required|exists:admins,id',
            'additional_note' => 'nullable|string',
            'comments' => 'nullable|string',
            'result_status' => 'nullable|in:Normal,Abnormal,Critical,Pending,Inconclusive,Positive,Negative,Reactive,Non-Reactive,Indeterminate',
            'images.*' => 'nullable|file|mimes:png,jpg,jpeg,dcm',
        ]);

        try {
            $img_names = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $image_data = Helpers::upload('assets/radiology_results/', 'png', $img);
                    $img_names[] = $image_data; // Store the image name or path in the array
                }
            }

            // Store radiology result
            $radiologyResult = RadiologyResult::create([
                'radiology_request_test_id' => $request->input('radiology_request_test_id'),
                'additional_note' => $request->input('additional_note'),
                'comments' => $request->input('comments'),
                'result_status' => $request->input('result_status'),
                'processed_by' => $request->input('processed_by'),
                'process_end_time' => now(),
                'process_status' => 'completed',
                'image' => json_encode($img_names),
            ]);

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^attribute_(\d+)$/', $key, $matches)) {
                    $attributeId = $matches[1];

                    $resultValue = is_array($value) ? null : $value;

                    RadiologyResultAttribute::create([
                        'radiology_result_id' => $radiologyResult->id,
                        'radiology_attribute_id' => $attributeId,
                        'result_value' => $resultValue,
                    ]);
                }
            }

            return response()->json(['message' => 'Radiology result stored successfully.', 'visit_id' => $radiologyResult->requestTest->request->visit_id], 200);
        } catch (\Exception $e) {
            Log::error('Failed to store radiology result: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to store radiology result.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the process status of a radiology result
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProcessStatus(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'result_id' => 'required|exists:radiology_results,id',
                'process_status' => 'required|string|in:completed,in process,rejected,pending',
            ]);

            $radiologyResult = RadiologyResult::findOrFail($request->input('result_id'));

            // Update the status
            $radiologyResult->process_status = $request->input('process_status');

            // Set process_end_time if status is completed
            if ($request->input('process_status') === 'completed') {
                $radiologyResult->process_end_time = now();
            }

            $radiologyResult->save();

            return response()->json([
                'success' => true,
                'message' => 'Radiology result process status updated successfully.',
                'visit_id' => $radiologyResult->requestTest->request->visit_id
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating radiology result process status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update radiology result process status. Please try again.',
                'visit_id' => $radiologyResult->requestTest->request->visit_id
            ], 500);
        }
    }

    /**
     * Update the verify status of a radiology result
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateVerifyStatus(Request $request)
    {
        try {
            $request->validate([
                'result_id' => 'required|exists:radiology_results,id',
                'verify_status' => 'required|in:pending,checking,approved,rejected',
            ]);
            DB::beginTransaction();
            $radiologyResult = RadiologyResult::findOrFail($request->result_id);

            // Update the verify_status
            $radiologyResult->verify_status = $request->verify_status;

            // Set verified_by when changing the status
            if (in_array($request->verify_status, ['checking', 'approved', 'rejected'])) {
                $radiologyResult->verified_by = auth('admin')->user()->id;

                // Set verify_start_time if status is checking
                if ($request->verify_status === 'checking') {
                    $radiologyResult->verify_start_time = now();
                }
            }

            if (in_array($request->verify_status, ['approved', 'rejected'])) {
                $radiologyResult->verify_end_time = now();
            }

            $radiologyResult->save();

            // Update RadiologyRequestTest status based on verify_status
            $radiologyRequestTest = $radiologyResult->requestTest;
            if ($radiologyRequestTest) {
                switch ($request->verify_status) {
                    case 'approved':
                        $radiologyRequestTest->status = 'completed';
                        break;
                    case 'rejected':
                        $radiologyRequestTest->status = 'rejected';
                        break;
                    case 'checking':
                        $radiologyRequestTest->status = 'in process';
                        break;
                    case 'pending':
                        $radiologyRequestTest->status = 'pending';
                        break;
                }
                $radiologyRequestTest->save();
            }
            $request = $radiologyRequestTest->request;
            $request = RadiologyRequest::findOrFail($request->id);
            $request->status = 'in process';
            $request->save();
            $radiologies = $request->radiologies;

            // Check if all radiologies are completed
            $allCompleted = $radiologies->every(function ($radiology) {
                return $radiology->status === 'completed';
            });

            if ($allCompleted) {
                $request->status = 'completed';
                $request->save();
            }

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                if ($radiologyResult->verify_status === 'approved') {
                    event(new NewMenuTestResultCreated(
                        'New Radiology Result added for ' . $radiologyResult->requestTest->request->visit->patient->full_name,
                        '/admin/patient/view/' . $radiologyResult->requestTest->request->visit->patient->id . '?active=' . $radiologyResult->requestTest->request->visit_id,
                        'New Radiology Result',
                        'radiology_result.list'
                    ));
                }
            }

            DB::commit();
            return response()->json(['message' => 'Verify status and related test status updated successfully.', 'visit_id' => $radiologyRequestTest->request->visit_id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update radiology result verify status. Please try again.',
                'visit_id' => $radiologyRequestTest->request->visit_id
            ], 500);
        }
    }

    /**
     * Generate PDF for a radiology result
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function generatePdf($id)
    {
        $radiologyResult = RadiologyResult::with(['requestTest.radiology', 'processedBy', 'verifiedBy', 'attributes.attribute', 'radiologyRequestTest.request.visit.patient'])
            ->findOrFail($id);
        // Generate PDF
        $pdf = Pdf::loadView('admin-views.pdf-components.main-radiology-pdf-viewer', [
            'radiologyResult' => $radiologyResult
        ])->setPaper('a4', 'portrait');

        // return view('admin-views.pdf-components.main-radiology-pdf-viewer', compact('radiologyResult'));

        return $pdf->stream('radiology_result.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline; filename="radiology_result.pdf"',
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $radiologyResult = RadiologyResult::with(['requestTest', 'processor', 'verifier', 'attributes'])
            ->findOrFail($id);

        return view('admin.radiology-results.show', compact('radiologyResult'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $radiologyResult = RadiologyResult::with(['requestTest', 'attributes.result', 'attributes.attribute'])
            ->findOrFail($id);

        return $radiologyResult;
        // return view('admin.radiology-results.edit', compact('radiologyResult'));
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
        // Validate the request
        $validatedData = $request->validate([
            'radiology_request_test_id' => 'required|exists:radiology_request_test,id',
            'processed_by' => 'required|exists:admins,id',
            'additional_note' => 'nullable|string',
            'comments' => 'nullable|string',
            'result_status' => 'nullable|in:Normal,Abnormal,Critical,Pending,Inconclusive,Positive,Negative,Reactive,Non-Reactive,Indeterminate',
            'images.*' => 'nullable|file|mimes:png,jpg,jpeg,dcm',
            'images_to_remove.*' => 'nullable|string',
        ]);

        try {
            $radiologyResult = RadiologyResult::findOrFail($id);

            // Handle image removal
            if ($request->has('images_to_remove')) {
                $currentImages = json_decode($radiologyResult->image, true) ?? [];
                $imagesToRemove = $request->input('images_to_remove');

                // Remove images from storage
                foreach ($imagesToRemove as $imageName) {
                    $imagePath = storage_path('app/public/radiology_results/' . $imageName);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // Update the image array
                $currentImages = array_diff($currentImages, $imagesToRemove);
                $radiologyResult->image = json_encode(array_values($currentImages));
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $img_names = json_decode($radiologyResult->image, true) ?? [];
                foreach ($request->file('images') as $img) {
                    $image_data = Helpers::upload('assets/radiology_results/', 'png', $img);
                    $img_names[] = $image_data;
                }
                $radiologyResult->image = json_encode($img_names);
            }

            // Update radiology result
            $radiologyResult->radiology_request_test_id = $request->input('radiology_request_test_id');
            // $radiologyResult->additional_note = $request->input('additional_note');
            // $radiologyResult->comments = $request->input('comments');
            $radiologyResult->result_status = $request->input('result_status');
            $radiologyResult->processed_by = $request->input('processed_by');

            $radiologyResult->save();



            $radiologyResult->attributes()->delete();
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^attribute_(\d+)$/', $key, $matches)) {
                    $attributeId = $matches[1];

                    $resultValue = is_array($value) ? null : $value;

                    RadiologyResultAttribute::create([
                        'radiology_result_id' => $radiologyResult->id,
                        'radiology_attribute_id' => $attributeId,
                        'result_value' => $resultValue,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Radiology result updated successfully.',
                'visit_id' => $radiologyResult->requestTest->request->visit_id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update radiology result: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update radiology result.', 'message' => $e->getMessage()], 500);
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
            $radiologyResult = RadiologyResult::findOrFail($id);

            // Delete associated attributes
            $radiologyResult->attributes()->delete();

            // Delete the radiology result
            $radiologyResult->delete();

            return response()->json(['message' => 'radiology deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to delete radiology result: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete radiology result. ' . $e->getMessage());
        }
    }
}

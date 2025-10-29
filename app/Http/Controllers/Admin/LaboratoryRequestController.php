<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LaboratoryRequest;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\Test;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\CentralLogics\Helpers;
use App\Events\NewMenuTestResultCreated;
use App\Models\BusinessSetting;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaboratoryRequestController extends Controller
{
    function __construct(
        private LaboratoryRequest $laboratoryRequest

    ) {
        $this->middleware('checkAdminPermission:patient.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:patient.add-new,index')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function fetchTestType(Request $request)
    {
        // Retrieve the LaboratoryRequest by ID
        $laboratoryRequest = LaboratoryRequest::findOrFail($request->laboratoryRequestId);

        // Fetch the related LaboratoryRequestTest records and include Test data + Result + Specimens
        $tests = $laboratoryRequest->tests()
            ->with(['test', 'specimens', 'result']) // Eager load Test, Specimens, and Result
            ->get()
            ->filter(function ($laboratoryRequestTest) {
                // Check if the test has NO result and has at least one accepted specimen
                $hasAcceptedSpecimen = $laboratoryRequestTest->specimens->contains('status', 'accepted');
                $hasNoResult = $laboratoryRequestTest->result === null;

                return $hasAcceptedSpecimen && $hasNoResult;
            })
            ->map(function ($laboratoryRequestTest) {
                return [
                    'laboratory_request_test_id' => $laboratoryRequestTest->id,
                    'test_id' => $laboratoryRequestTest->test->id,
                    'test_name' => $laboratoryRequestTest->test->test_name,
                    'category' => $laboratoryRequestTest->test->testCategory->name,
                ];
            });

        return response()->json($tests->values(), 200);
    }


    public function fetchTestTypeCustom(Request $request)
    {
        // Retrieve the LaboratoryRequest by ID
        $laboratoryRequest = LaboratoryRequest::findOrFail($request->laboratoryRequestId);

        $laboratoryRequest = $laboratoryRequest->tests()
            ->whereHas('test', function ($q) {
                $q->where('result_source', 'manual');
            })
            ->with(['test.attributes.options', 'test.attributes.unit', 'specimens', 'result'])
            ->get()
            ->filter(function ($laboratoryRequestTest) {
                // Check if the test has NO result
                return $laboratoryRequestTest->result === null;
            })
            ->map(function ($laboratoryRequestTest) {
                // Add the pivot ID to the existing model data
                $laboratoryRequestTest->laboratory_request_test_id = $laboratoryRequestTest->id;
                return $laboratoryRequestTest;
            })
            ->values();

        return response()->json($laboratoryRequest, 200);
    }



    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $query = $this->laboratoryRequest->with('visit.patient')->latest();

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->laboratoryRequest->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%");
                    // ->orWhere('full_name', 'like', "%{$value}%")
                    // ->orWhere('phone', 'like', "%{$value}%")
                    // ->orWhere('email', 'like', "%{$value}%")
                    // ->orWhere('registration_no', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        }
        $laboratoryRequests = $query->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.laboratory-requests.list', compact('laboratoryRequests', 'search'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'visit_id' => 'required|exists:visits,id',
                'requested_by' => 'required|in:physician,self,other healthcare',
                'order_status' => 'required|in:urgent,routine',
                'fasting' => 'required|in:yes,no',
                'referring_dr' => 'nullable|string|max:255',
                'referring_institution' => 'nullable|string|max:255',
                'card_no' => 'nullable|string|max:255',
                'hospital_ward' => 'nullable|string|max:255',
                'relevant_clinical_data' => 'nullable|string|max:255',
                'current_medication' => 'nullable|string|max:255',
                'collected_by' => 'required|exists:admins,id',
                'test_ids' => 'required|array',
                'test_ids.*' => 'exists:tests,id',
            ]);

            DB::beginTransaction();
            // Create the laboratory request
            $laboratoryRequest = LaboratoryRequest::create($request->except('test_ids'));

            // Initialize an array to store active test IDs for bill generation
            $activeTestIds = [];

            foreach ($request->test_ids as $testId) {
                $test = Test::find($testId);

                // Add the test to the laboratory request (whether it's active or not)
                if ($test) {
                    $laboratoryRequest->tests()->create(['test_id' => $testId]);

                    // If the test is active, add it to the active tests array for billing
                    if ($test->is_active) {
                        $activeTestIds[] = $testId;
                    }
                }
            }

            // Generate bill only for active tests
            if (!empty($activeTestIds)) {
                $this->generateBill($laboratoryRequest, $activeTestIds);
            }
            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    'New Laboratory Request added for ' . $laboratoryRequest->visit->patient->full_name,
                    '/admin/patient/view/' . $laboratoryRequest->visit->patient->id . '?active=' . $laboratoryRequest->visit_id,
                    'New Laboratory Request',
                    'laboratory_request.list'
                ));
            }
            DB::commit();
            return response()->json(['message' => 'Laboratory request created successfully.', 'visit_id' => $laboratoryRequest->visit_id], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error creating laboratory request: ' . $th->getMessage());
            return response()->json(['error' => 'Failed to create laboratory request. Please try again.', 'message' => $th->getMessage()], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function addTests(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'id' => 'required|exists:laboratory_requests,id',
                'test_ids' => 'required|array',
                'test_ids.*' => 'exists:tests,id',
            ]);

            DB::beginTransaction();
            // Create the laboratory request
            $laboratoryRequest = LaboratoryRequest::findOrFail($request->id);

            // Initialize an array to store active test IDs for bill generation
            $activeTestIds = [];

            foreach ($request->test_ids as $testId) {
                $test = Test::find($testId);

                // Add the test to the laboratory request (whether it's active or not)
                if ($test) {
                    $laboratoryRequest->tests()->create(['test_id' => $testId]);

                    // If the test is active, add it to the active tests array for billing
                    if ($test->is_active) {
                        $activeTestIds[] = $testId;
                    }
                }
            }

            // Generate bill only for active tests
            if (!empty($activeTestIds)) {
                $this->generateBillOnAddTests($laboratoryRequest, $activeTestIds);
            }
            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    'New Laboratory Request added for ' . $laboratoryRequest->visit->patient->full_name,
                    '/admin/patient/view/' . $laboratoryRequest->visit->patient->id . '?active=' . $laboratoryRequest->visit_id,
                    'New Laboratory Request',
                    'laboratory_request.list'
                ));
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tests added successfully.', 'visit_id' => $laboratoryRequest->visit_id], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error adding tests: ' . $th->getMessage());
            return response()->json(['error' => 'Error adding tests: ' . $th->getMessage()], 500);
        }
    }


    /**
     * Generate a bill for the laboratory request
     */
    private function generateBill(LaboratoryRequest $laboratoryRequest)
    {
        // Fetch tests associated with the laboratory request that are active
        $testIds = $laboratoryRequest->tests->pluck('test_id');

        // Fetch only active tests
        $tests = Test::whereIn('id', $testIds)
            ->where('is_active', true) // Filter only active tests
            ->get();

        // Calculate the total cost for active tests
        $totalAmount = $tests->sum('cost'); // Calculate total cost

        if ($totalAmount > 0) {
            // Create billing record for active tests
            $billing = Billing::create([
                'visit_id' => $laboratoryRequest->visit_id,
                'admin_id' => $laboratoryRequest->collected_by, // Assigned to the collector
                'laboratory_request_id' => $laboratoryRequest->id,
                'bill_date' => now(),
                'total_amount' => $totalAmount,
                'discount' => 0,
                'amount_paid' => 0,
                'status' => 'unpaid',
                'note' => 'Auto-generated bill for self-requested test'
            ]);

            // Create billing details for each active test
            foreach ($tests as $test) {
                BillingDetail::create([
                    'billing_id' => $billing->id,
                    'test_id' => $test->id,
                    'quantity' => 1,
                    'unit_cost' => $test->cost
                ]);
            }
        } else {
            // If there are no active tests, skip billing creation
            return;
        }
    }


    /**
     * Generate a bill for exisiting request add tests
     */
    private function generateBillOnAddTests(LaboratoryRequest $laboratoryRequest, $activeTestIds)
    {
        // Fetch tests associated with the laboratory request that are active
        $testIds = $activeTestIds;

        // Fetch only active tests
        $tests = Test::whereIn('id', $testIds)
            ->where('is_active', true) // Filter only active tests
            ->get();

        // Calculate the total cost for active tests
        $totalAmount = $tests->sum('cost'); // Calculate total cost

        if ($totalAmount > 0) {
            // Create billing record for active tests
            $billing = Billing::create([
                'visit_id' => $laboratoryRequest->visit_id,
                'admin_id' => $laboratoryRequest->collected_by, // Assigned to the collector
                'laboratory_request_id' => $laboratoryRequest->id,
                'bill_date' => now(),
                'total_amount' => $totalAmount,
                'discount' => 0,
                'amount_paid' => 0,
                'status' => 'unpaid',
                'note' => 'Auto-generated bill for self-requested test'
            ]);

            // Create billing details for each active test
            foreach ($tests as $test) {
                BillingDetail::create([
                    'billing_id' => $billing->id,
                    'test_id' => $test->id,
                    'quantity' => 1,
                    'unit_cost' => $test->cost
                ]);
            }
        } else {
            // If there are no active tests, skip billing creation
            return;
        }
    }


    /**
     * Generate a bill for the laboratory request
     */
    private function updateBill(LaboratoryRequest $laboratoryRequest, $activeTestIds)
    {
        // Fetch tests associated with the laboratory request that are active
        $testIds = $activeTestIds;

        // Fetch only active tests
        $tests = Test::whereIn('id', $testIds)
            ->where('is_active', true) // Filter only active tests
            ->get();

        // Calculate the total cost for active tests
        $totalAmount = $tests->sum('cost'); // Calculate total cost

        $existingBilling = Billing::where('laboratory_request_id', $laboratoryRequest->id)
            ->where('visit_id', $laboratoryRequest->visit_id)
            ->first();

        if ($existingBilling) {
            if ($existingBilling->amount_paid > 0) {
                throw new \Exception('Laboratory billing already paid');
                return;
            }
            $existingBilling->delete();
        }

        if ($totalAmount > 0) {
            // Create billing record for active tests
            $billing = Billing::create([
                'visit_id' => $laboratoryRequest->visit_id,
                'admin_id' => $laboratoryRequest->collected_by, // Assigned to the collector
                'laboratory_request_id' => $laboratoryRequest->id,
                'bill_date' => now(),
                'total_amount' => $totalAmount,
                'discount' => 0,
                'amount_paid' => 0,
                'status' => 'unpaid',
                'note' => 'Auto-generated bill for self-requested test'
            ]);

            // Create billing details for each active test
            foreach ($tests as $test) {
                BillingDetail::create([
                    'billing_id' => $billing->id,
                    'test_id' => $test->id,
                    'quantity' => 1,
                    'unit_cost' => $test->cost
                ]);
            }
        } else {
            // If there are no active tests, skip billing creation
            return;
        }
    }

    public function generatePdf($id)
    {
        $visit = Visit::findOrFail($id);

        // Generate the PDF
        $pdf = PDF::loadView('admin-views.patients.requestpdf', compact('visit'));

        // Return the PDF inline (view in browser)
        return $pdf->stream('Laboratory Request.pdf');
    }

    public function downloadPdf($id)
    {
        $visit = Visit::findOrFail($id);

        // Load the view for the PDF content
        $pdf = PDF::loadView('admin-views.patients.requestpdf', compact('visit'));

        // Force download the PDF
        return $pdf->download('LaboratoryRequest.pdf');
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
        try {
            $laboratoryRequest = LaboratoryRequest::with([
                'visit.patient',
                'tests.test',
                'tests.specimens',
                'tests.result'
            ])->findOrFail($id);

            // Get all active tests for the dropdown
            $tests = Test::all();

            return response()->json([
                'success' => true,
                'data' => [
                    'laboratoryRequest' => $laboratoryRequest,
                    'tests' => $tests
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving laboratory request: ' . $e->getMessage()
            ], 500);
        }
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
        // Validate the request data
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'requested_by' => 'required|in:physician,self,other healthcare',
            'order_status' => 'required|in:urgent,routine',
            'fasting' => 'required|in:yes,no',
            'referring_dr' => 'nullable|string|max:255',
            'referring_institution' => 'nullable|string|max:255',
            'card_no' => 'nullable|string|max:255',
            'hospital_ward' => 'nullable|string|max:255',
            'relevant_clinical_data' => 'nullable|string|max:255',
            'current_medication' => 'nullable|string|max:255',
            'collected_by' => 'required|exists:admins,id',
            'test_ids' => 'required|array',
            'test_ids.*' => 'exists:tests,id',
        ]);

        try {
            DB::beginTransaction();
            // Find the laboratory request
            $laboratoryRequest = LaboratoryRequest::findOrFail($id);

            // Update the laboratory request
            $laboratoryRequest->update($request->except('test_ids'));

            // Get existing test IDs
            $existingTestIds = $laboratoryRequest->tests->pluck('test_id')->toArray();

            // Get new test IDs from request
            $newTestIds = $request->test_ids;

            // Find tests to add and remove
            // $testsToAdd = array_diff($newTestIds, $existingTestIds);
            // $testsToRemove = array_diff($existingTestIds, $newTestIds);

            $activeTestIds = [];

            // Remove tests that are no longer selected
            $laboratoryRequest->tests()
                // ->whereIn('test_id', $testsToRemove)
                ->delete();

            // Add new tests
            foreach ($newTestIds as $testId) {
                $test = Test::find($testId);
                if ($test) {
                    $laboratoryRequest->tests()->create(['test_id' => $testId]);
                }
                // If the test is active, add it to the active tests array for billing
                if ($test->is_active) {
                    $activeTestIds[] = $testId;
                }
            }


            // Generate bill for any new active tests
            if (!empty($activeTestIds)) {
                $this->updateBill($laboratoryRequest, $activeTestIds);
            }

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    'Laboratory Request updated for ' . $laboratoryRequest->visit->patient->full_name,
                    '/admin/patient/view/' . $laboratoryRequest->visit->patient->id . '?active=' . $laboratoryRequest->visit_id,
                    'Laboratory Request updated',
                    'laboratory_request.list'
                ));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Laboratory request updated successfully',
                'visit_id' => $laboratoryRequest->visit_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating laboratory request: ' . $e->getMessage()
            ], 500);
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
        //
    }
}

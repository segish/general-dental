<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RadiologyRequest;
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
use App\Models\Radiology;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RadiologyRequestController extends Controller
{
    function __construct(
        private RadiologyRequest $radiologyRequest
    ) {
        $this->middleware('checkAdminPermission:radiology_request.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:radiology_request.add-new,index')->only(['index']);
    }

    public function index()
    {
        return view('admin-views.radiology-requests.index');
    }

    public function fetchTestType(Request $request)
    {
        // Retrieve the RadiologyRequest by ID
        $radiologyRequest = RadiologyRequest::findOrFail($request->radiologyRequestId);

        $radiologies = $radiologyRequest->radiologies()
            ->with(['radiology', 'result'])
            ->get()
            ->filter(function ($radiologyRequestTest) {
                // Check if the test has NO result
                return $radiologyRequestTest->result === null;
            })
            ->map(function ($radiologyRequestTest) {
                return [
                    'radiology_request_test_id' => $radiologyRequestTest->id,
                    'radiology_id' => $radiologyRequestTest->radiology->id,
                    'radiology_name' => $radiologyRequestTest->radiology->radiology_name,
                ];
            });

        return response()->json($radiologies->values(), 200);
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $query = $this->radiologyRequest->with(['visit.patient', 'radiologies'])->latest();

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->radiologyRequest->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhereHas('visit.patient', function ($q) use ($value) {
                            $q->where('full_name', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%");
                        });
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        }
        $radiologyRequests = $query->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.radiology-requests.list', compact('radiologyRequests', 'search'));
    }

    public function store(Request $request)
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
            'relevant_clinical_data' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'collected_by' => 'required|exists:admins,id',
            'radiology_ids' => 'required|array',
            'radiology_ids.*' => 'exists:radiologies,id',
            'additional_note' => 'nullable|string'
        ]);

        // Create the radiology request
        $radiologyRequest = RadiologyRequest::create($request->except('radiology_ids'));

        // Initialize an array to store active radiology IDs for bill generation
        $activeRadiologyIds = [];

        foreach ($request->radiology_ids as $radId) {
            $radiology = Radiology::find($radId);

            // Add the test to the radiology request
            if ($radiology) {
                $radiologyRequest->radiologies()->create(['radiology_id' => $radId]);

                // If the radiology is active, add it to the active radiologies array for billing
                if ($radiology->is_active) {
                    $activeRadiologyIds[] = $radId;
                }
            }
        }

        // Generate bill only for active radiologies
        if (!empty($activeRadiologyIds)) {
            $this->generateBill($radiologyRequest);
        }

        if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
            event(new NewMenuTestResultCreated(
                'New Radiology Request added for ' . $radiologyRequest->visit->patient->full_name,
                '/admin/patient/view/' . $radiologyRequest->visit->patient->id . '?active=' . $radiologyRequest->visit_id,
                'New Radiology Request',
                'radiology_request.list'
            ));
        }
        return response()->json(['message' => 'Radiology request created successfully.', 'visit_id' => $radiologyRequest->visit_id], 200);
    }

    public function edit($id)
    {
        try {
            $radiologyRequest = RadiologyRequest::with([
                'visit.patient',
                'radiologies.radiology',
                'radiologies.result'
            ])->findOrFail($id);

            // Get all active tests for the dropdown
            $radiologies = Radiology::all();

            return response()->json([
                'success' => true,
                'data' => [
                    'radiologyRequest' => $radiologyRequest,
                    'radiologies' => $radiologies
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving laboratory request: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateBill(RadiologyRequest $radiologyRequest)
    {
        // Fetch radiologies associated with the radiology request that are active
        $radiologyIds = $radiologyRequest->radiologies->pluck('radiology_id');

        // Fetch only active radiologies
        $radiologies = Radiology::whereIn('id', $radiologyIds)
            ->where('is_active', true)
            ->get();

        // Calculate the total cost for active radiologies
        $totalAmount = $radiologies->sum('cost');

        if ($totalAmount > 0) {
            // Create billing record
            $billing = Billing::create([
                'visit_id' => $radiologyRequest->visit_id,
                'admin_id' => $radiologyRequest->collected_by,
                'radiology_request_id' => $radiologyRequest->id,
                'bill_date' => now(),
                'total_amount' => $totalAmount,
                'discount' => 0,
                'amount_paid' => 0,
                'status' => 'unpaid',
                'note' => 'Auto-generated bill for radiology request'
            ]);

            // Create billing details for each active test
            foreach ($radiologies as $radiology) {
                BillingDetail::create([
                    'billing_id' => $billing->id,
                    'radiology_id' => $radiology->id,
                    'quantity' => 1,
                    'unit_cost' => $radiology->cost
                ]);
            }
        }
    }

    public function generatePdf($id)
    {
        $visit = Visit::findOrFail($id);
        $pdf = PDF::loadView('admin-views.radiology-requests.pdf', compact('visit'));
        return $pdf->stream('Radiology Request.pdf');
    }

    public function downloadPdf($id)
    {
        $visit = Visit::findOrFail($id);
        $pdf = PDF::loadView('admin-views.radiology-requests.pdf', compact('visit'));
        return $pdf->download('RadiologyRequest.pdf');
    }

    public function show($id)
    {
        $radiologyRequest = RadiologyRequest::with(['visit.patient', 'radiologies', 'billing'])->findOrFail($id);
        return view('admin-views.radiology-requests.show', compact('radiologyRequest'));
    }

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
            'relevant_clinical_data' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'collected_by' => 'required|exists:admins,id',
            'radiology_ids' => 'required|array',
            'radiology_ids.*' => 'exists:radiologies,id',
            'additional_note' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            // Find the radiology request
            $radiologyRequest = RadiologyRequest::findOrFail($id);

            // Update the radiology request
            $radiologyRequest->update($request->except('radiology_ids'));

            // Get existing radiology IDs
            $existingRadiologyIds = $radiologyRequest->radiologies->pluck('radiology_id')->toArray();

            // Get new radiology IDs from request
            $newRadiologyIds = $request->radiology_ids;

            $activeRadiologyIds = [];

            // Remove existing radiologies
            $radiologyRequest->radiologies()->delete();

            // Add new radiologies
            foreach ($newRadiologyIds as $radId) {
                $radiology = Radiology::find($radId);
                if ($radiology) {
                    $radiologyRequest->radiologies()->create(['radiology_id' => $radId]);
                }
                // If the radiology is active, add it to the active radiologies array for billing
                if ($radiology->is_active) {
                    $activeRadiologyIds[] = $radId;
                }
            }

            // Generate bill for any new active radiologies
            if (!empty($activeRadiologyIds)) {
                $this->updateBill($radiologyRequest, $activeRadiologyIds);
            }

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    'Radiology Request updated for ' . $radiologyRequest->visit->patient->full_name,
                    '/admin/patient/view/' . $radiologyRequest->visit->patient->id . '?active=' . $radiologyRequest->visit_id,
                    'Radiology Request Updated',
                    'radiology_request.list'
                ));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Radiology request updated successfully',
                'visit_id' => $radiologyRequest->visit_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating radiology request: ' . $e->getMessage()
            ], 500);
        }
    }

    private function updateBill(RadiologyRequest $radiologyRequest, $activeRadiologyIds)
    {
        // Fetch radiologies associated with the radiology request that are active
        $radiologies = Radiology::whereIn('id', $activeRadiologyIds)
            ->where('is_active', true)
            ->get();

        // Calculate the total cost for active radiologies
        $totalAmount = $radiologies->sum('cost');

        $existingBilling = Billing::where('radiology_request_id', $radiologyRequest->id)
            ->where('visit_id', $radiologyRequest->visit_id)
            ->first();

        if ($existingBilling) {
            if ($existingBilling->amount_paid > 0) {
                throw new \Exception('Radiology billing already paid');
                return;
            }
            $existingBilling->delete();
        }

        if ($totalAmount > 0) {
            // Create billing record
            $billing = Billing::create([
                'visit_id' => $radiologyRequest->visit_id,
                'admin_id' => $radiologyRequest->collected_by,
                'radiology_request_id' => $radiologyRequest->id,
                'bill_date' => now(),
                'total_amount' => $totalAmount,
                'discount' => 0,
                'amount_paid' => 0,
                'status' => 'unpaid',
                'note' => 'Auto-generated bill for radiology request'
            ]);

            // Create billing details for each active radiology
            foreach ($radiologies as $radiology) {
                BillingDetail::create([
                    'billing_id' => $billing->id,
                    'radiology_id' => $radiology->id,
                    'quantity' => 1,
                    'unit_cost' => $radiology->cost
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $radiologyRequest = RadiologyRequest::findOrFail($id);
        $radiologyRequest->delete();
        return response()->json(['message' => 'Radiology request deleted successfully.'], 200);
    }
}

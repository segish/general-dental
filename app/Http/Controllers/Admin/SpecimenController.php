<?php

namespace App\Http\Controllers\admin;

use App\Models\TestCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Specimen;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\Test;
use App\Models\LaboratoryRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpecimenController extends Controller
{
    function __construct(
        private Specimen $specimen,
    ) {
        $this->middleware('checkAdminPermission:specimen.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:specimen.add-new,index')->only(['index']);
    }

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();

        return view('admin-views.specimen.index', compact('roles'));
    }

    public function list(Request $request): Factory|View|Application
    {

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->specimen->with(['laboratoryRequest.visit.patient', 'specimenType', 'specimenOrigin'])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('specimen_code', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->specimen->with(['laboratoryRequest.visit.patient', 'specimenType', 'specimenOrigin'])->latest();
        }
        $specimens = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.specimen.list', compact('specimens', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.specimen.create', compact('permission'));
    }

    public function getTests(Request $request)
    {
        $laboratoryRequest = LaboratoryRequest::findOrFail($request->laboratory_request_id);

        // Get all test IDs already linked to a specimen
        $assignedTestIds = DB::table('specimen_laboratory_request_test')
            ->join('specimens', 'specimens.id', '=', 'specimen_laboratory_request_test.specimen_id')
            ->where('specimens.laboratory_request_id', $laboratoryRequest->id)
            ->pluck('specimen_laboratory_request_test.laboratory_request_test_id')
            ->toArray();

        // Get unassigned AND active/inhouse tests
        $unassignedTests = $laboratoryRequest->tests()
            ->whereNotIn('id', $assignedTestIds)
            ->whereHas('test', function ($query) {
                $query->where('is_active', true)
                    ->where('is_inhouse', true);
            })
            ->with(['test.testCategory'])
            ->get();

        return response()->json([
            'tests' => $unassignedTests->map(function ($test) {
                return [
                    'test_id' => $test->id,
                    'test_name' => $test->test->test_name,
                    'category' => $test->test->testCategory->name ?? 'Unknown',
                ];
            }),
            'pagination' => [
                'more' => false
            ]
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
        $validatedData = $request->validate([
            'checker_id' => 'nullable|exists:admins,id',
            'specimen_origin_id' => 'nullable|exists:specimen_origins,id',
            'laboratory_request_id' => 'required|exists:laboratory_requests,id',
            'status' => 'required|in:pending,in process,accepted,rejected',
            'notes' => 'nullable|string',
            'checking_start_time' => 'nullable|date',
            'specimen_taken_at' => 'nullable|date',
            'laboratory_request_test_ids' => 'required|array',
            'laboratory_request_test_ids.*' => 'required',
        ]);

        try {
            $specimen = DB::transaction(function () use ($validatedData) {
                $validatedData['specimen_code'] = $this->generateUniqueSpecimenCode();

                $specimen = Specimen::create($validatedData);

                $specimen->laboratoryRequestTests()->attach($validatedData['laboratory_request_test_ids']);

                return $specimen;
            });

            Toastr::success(translate('Specimen and Laboratory Request Tests Saved Successfully!'));

            return response()->json([
                'message' => 'Specimen and Laboratory Request Tests Saved Successfully!',
                'visit_id' => $specimen->laboratoryRequest->visit_id
            ], 200);
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return response()->json(['error' => $e->getMessage()], 500);
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


    public function updateSpecimenStatus(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'specimen_id' => 'required|exists:specimens,id',
                'status' => 'required|string|in:accepted,in process,rejected,pending',
            ]);

            // Start a database transaction
            DB::beginTransaction();

            // Find the specimen
            $specimen = Specimen::findOrFail($request->input('specimen_id'));

            // Update the status
            $specimen->status = $request->input('status');
            if ($specimen->status === 'accepted') {
                $specimen->checking_end_time = now();
            }
            $specimen->save();

            // If the specimen is accepted, create a billing record
            if ($specimen->status === 'accepted') {
                $existingBilling = Billing::where('laboratory_request_id', $specimen->laboratory_request_id)->first();

                if (!$existingBilling) {
                    $laboratoryRequest = $specimen->laboratoryRequest;
                    $testIds = $laboratoryRequest->tests->pluck('test_id');
                    $tests = Test::whereIn('id', $testIds)->get();
                    $totalAmount = $tests->sum('cost');

                    $billing = Billing::create([
                        'visit_id' => $laboratoryRequest->visit->id,
                        'admin_id' => $laboratoryRequest->collected_by,
                        'laboratory_request_id' => $laboratoryRequest->id,
                        'bill_date' => now(),
                        'total_amount' => $totalAmount,
                        'discount' => 0,
                        'amount_paid' => 0,
                        'status' => 'pending',
                        'note' => 'Auto-generated billing for accepted specimen',
                    ]);

                    foreach ($tests as $test) {
                        BillingDetail::create([
                            'billing_id' => $billing->id,
                            'test_id' => $test->id,
                            'quantity' => 1,
                            'unit_cost' => $test->cost
                        ]);
                    }
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Specimen status updated successfully.',
                'visit_id' =>  $specimen->laboratoryRequest->visit->id
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error updating specimen status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update specimen status. Please try again.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('admin-views.specimen.show', compact('role', 'rolePermissions'));
    }
    public function edit($id)
    {
        $testType = Specimen::find($id);


        return view('admin-views.specimen.edit', compact('testType'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:specimens,id',
            'specimen_origin_id' => 'required|exists:specimen_origins,id',
            'specimen_taken_at' => 'nullable|date',
        ]);

        try {
            $specimen = Specimen::findOrFail($request->id);
            $specimen->update($request->only([
                'specimen_origin_id',
                'specimen_taken_at'
            ]));

            return response()->json(['message' => 'Updated', 'visit_id' => $specimen->laboratoryRequest->visit_id], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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

            $specimen = Specimen::findOrFail($id);

            // Detach laboratory request tests
            $specimen->laboratoryRequestTests()->detach();

            // Delete the specimen
            $specimen->delete();

            DB::commit();

            return response()->json(['message' => 'Specimen deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete specimen: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete specimen'], 500);
        }
    }
}

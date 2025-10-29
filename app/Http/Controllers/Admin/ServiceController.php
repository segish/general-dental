<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BillingService;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\PatientProcedure;
use App\Models\ServiceCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    function __construct(
        private BillingService $service,
    ) {
        $this->middleware('checkAdminPermission:service.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:service.add-new,index')->only(['index']);
    }

    public function index(Request $request)
    {
        $serviceCategories = ServiceCategory::all();
        return view('admin-views.services.index', compact('serviceCategories'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request->get('search');

        if ($search) {
            $key = explode(' ', $search);
            $query = $this->service->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('service_name', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $search];
        } else {
            $query = $this->service->latest();
        }

        $services = $query->paginate(10)->appends($query_param);
        return view('admin-views.services.list', compact('services', 'search'));
    }

    public function create()
    {
        return view('admin-views.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255|unique:billing_services',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_type' => 'required|in:one-time,recurring',
            'billing_interval_days' => 'nullable|integer|min:1',
            'payment_timing' => 'required|in:prepaid,postpaid',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        try {
            BillingService::create($request->all());
            Toastr::success('Service saved successfully!');
            return redirect()->route('admin.service.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }

    public function add_service_billing(Request $request)
    {

        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'billing_service_id' => 'required|exists:billing_services,id',
        ]);

        try {
            DB::beginTransaction();
            $billingService = BillingService::findOrFail($request->billing_service_id);

            PatientProcedure::create([
                'visit_id' => $request->visit_id,
                'doctor_id' => auth('admin')->id(),
                'billing_service_id' => $billingService->id,
                'procedure_notes' => $request->procedure_notes,
            ]);

            // Create Billing record
            $billing = Billing::create([
                'admin_id' => auth('admin')->id(),
                'visit_id' => $request->visit_id,
                'laboratory_request_id' => null,
                'emergency_medicine_issuance_id' => null,
                'patient_procedures_id' => null,
                'billing_service_id' => $billingService->id,
                'bill_date' => now()->toDateString(),
                'total_amount' => $billingService->price * 1,
                'discount' => 0,
                'amount_paid' => 0,
                'status' => 'unpaid',
                'note' => null,
            ]);

            // Create BillingDetails record
            BillingDetail::create([
                'billing_id' => $billing->id,
                'quantity' => 1,
                'unit_cost' => $billingService->price,
                'billing_service_id' => $billingService->id,
                'test_id' => null,
                'emergency_medicine_issuance_id' => null,
                'patient_procedures_id' => null,
            ]);
            DB::commit();

            return back()->with('success', 'Service successfully added to billing.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    public function edit($id)
    {
        $service = BillingService::findOrFail($id);
        $serviceCategories = ServiceCategory::all();
        return view('admin-views.services.edit', compact('service', 'serviceCategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255|unique:billing_services,service_name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_type' => 'required|in:one-time,recurring',
            'billing_interval_days' => 'nullable|integer|min:1',
            'payment_timing' => 'required|in:prepaid,postpaid',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        try {
            $service = BillingService::findOrFail($id);
            $service->update($request->all());
            Toastr::success('Service updated successfully!');
            return redirect()->route('admin.service.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }

    public function destroy($id)
    {
        try {
            BillingService::findOrFail($id)->delete();
            Toastr::success('Service deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }
}

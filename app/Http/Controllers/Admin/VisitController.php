<?php

namespace App\Http\Controllers\Admin;

use App\Models\Visit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Admin;
use App\Models\IPDRecord;
use App\Models\OPDRecord;
use App\Models\Appointment;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use App\CentralLogics\Helpers;
use App\Events\NewMenuTestResultCreated;
use App\Models\Bed;
use App\Models\Ward;
use App\Models\BillingDetail;
use App\Models\Billing;
use Illuminate\Support\Facades\DB;
use App\Models\BillingService;
use App\Models\BusinessSetting;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class VisitController extends Controller
{
    public function __construct(private Visit $visit)
    {
        $this->middleware('checkAdminPermission:visit.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:visit.add-new,index')->only(['index']);
    }

    public function index(Request $request): Factory|View|Application
    {
        // Data for Add New Visit tab
        $patients = Patient::latest()->get();
        $wards = Ward::latest()->get();
        $beds = Bed::where('status', 'available')->latest()->get();
        $serviceCategories = ServiceCategory::all();

        $doctors = Admin::whereHas('roles.permissions', function ($query) {
            $query->where('name', 'medical_record.add-new');
        })->latest()->get();

        $appointments = Appointment::whereDate('date', '>=', now()->toDateString())
            ->latest()
            ->get();

        // Data for Visit List tab
        $query_param = [];
        $search = $request['search'];
        $visit_type = $request['visit_type'];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->visit->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('visit_type', 'like', "%{$value}%")
                        ->orWhere('visit_datetime', 'like', "%{$value}%");
                }
            })
                ->orWhereHas('patient', function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->where('full_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->visit;
        }

        // Apply visit_type filter if provided
        if ($request->has('visit_type') && !empty($visit_type)) {
            $query = $query->where('visit_type', $visit_type);
            $query_param['visit_type'] = $visit_type;
        }

        $visits = $query->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        // Data for Invoice List tab
        $billing_query_param = [];
        $billing_search = $request['billing_search'];
        $billing_date = $request['billing_date'];

        // Define relationships once
        $with = [
            'billingDetail.test',
            'billingDetail.dischargeService.visit.ipdRecord.bed',
            'billingDetail.radiology',
            'billingDetail.billingService',
            'billingDetail.prescreption.medicine.medicine',
            'payments',
            'visit.patient',
            'admin',
            'canceledByAdmin'
        ];

        $billing_query = Billing::with($with);

        if ($request->has('billing_search') || $request->has('billing_date')) {
            $billing_query->where(function ($q) use ($billing_search) {
                $key = explode(' ', $billing_search);
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhereHas('visit.patient', function ($subQ) use ($value) {
                            $subQ->where('full_name', 'like', "%{$value}%")
                                ->orWhere('registration_no', 'like', "%{$value}%");
                        });
                }
            });

            if ($billing_date) {
                $billing_query->whereDate('created_at', '=', $billing_date);
            }

            $billing_query_param = ['billing_search' => $billing_search, 'billing_date' => $billing_date];
        }

        $billing_query->latest();

        $patientsWithBilling = Patient::has('billings')->get();
        $billings = $billing_query->paginate(Helpers::pagination_limit())->appends($billing_query_param);

        return view('admin-views.visit.index', compact(
            'patients',
            'beds',
            'wards',
            'doctors',
            'appointments',
            'serviceCategories',
            'visits',
            'search',
            'visit_type',
            'billings',
            'patientsWithBilling',
            'billing_search'
        ));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $visit_type = $request['visit_type'];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->visit->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('visit_type', 'like', "%{$value}%")
                        ->orWhere('visit_datetime', 'like', "%{$value}%");
                }
            })
                ->orWhereHas('patient', function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->where('full_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->visit;
        }

        // Apply visit_type filter if provided
        if ($request->has('visit_type') && !empty($visit_type)) {
            $query = $query->where('visit_type', $visit_type);
            $query_param['visit_type'] = $visit_type;
        }

        $visits = $query->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.visit.list', compact('visits', 'search', 'visit_type'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Admin::all();
        return view('admin-views.visit.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:admins,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'visit_type' => 'required|in:IPD,OPD',
            'visit_datetime' => 'required|date',
            'notes' => 'nullable|string',
            'service_category_id' => 'required|exists:service_categories,id',
            // IPD fields
            'ward_id' => 'required_if:visit_type,IPD|exists:wards,id',
            'bed_id' => 'required_if:visit_type,IPD|exists:beds,id',
            'admitting_doctor_id' => 'required_if:visit_type,IPD|exists:admins,id',
            'admission_date' => 'required_if:visit_type,IPD|date',

            // OPD fields
        ]);
        try {
            DB::beginTransaction();

            // Generate a unique visit code
            $visitCode = $this->generateUniqueVisitCode();

            // Create visit record
            $visit = Visit::create([
                'code' => $visitCode,
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_id' => $request->appointment_id,
                'visit_type' => $request->visit_type,
                'visit_datetime' => Carbon::parse($request->visit_datetime),
                'additional_notes' => $request->notes,
                'service_category_id' => $request->service_category_id,
            ]);

            if ($request->visit_type == 'IPD') {
                IpdRecord::create([
                    'visit_id' => $visit->id,
                    'ward_id' => $request->ward_id,
                    'bed_id' => $request->bed_id,
                    'admitting_doctor_id' => $request->admitting_doctor_id,
                    'admission_date' => Carbon::parse($request->admission_date),
                    'ipd_status' => 'Admitted',
                ]);
                Bed::where('id', $request->bed_id)->update(['status' => 'occupied']);
            } else if ($request->visit_type == 'OPD') {
                OpdRecord::create([
                    'visit_id' => $visit->id,
                ]);
            }

            // Create billing for recurring services with interval check
            $recurringServices = BillingService::where('billing_type', 'recurring')
                ->where('is_active', true)
                ->where('service_category_id', $request->service_category_id)
                ->get();

            $billingCreated = false;
            $totalAmount = 0;
            $billingDetails = [];

            foreach ($recurringServices as $service) {
                $intervalDays = $service->billing_interval_days ?? 0;

                // Find last visit with billing for this service and patient
                $lastBilledVisit = Visit::where('patient_id', $request->patient_id)
                    ->where('id', '!=', $visit->id)
                    ->whereHas('billings.billingDetail', function ($q) use ($service) {
                        $q->where('billing_service_id', $service->id);
                    })
                    ->orderByDesc('visit_datetime')
                    ->first();

                $shouldCreateBilling = true;

                if ($lastBilledVisit && $intervalDays > 0) {
                    $lastVisitDate = \Carbon\Carbon::parse($lastBilledVisit->visit_datetime);
                    $newVisitDate = \Carbon\Carbon::parse($request->visit_datetime);
                    $diffDays = $lastVisitDate->diffInDays($newVisitDate);

                    if ($diffDays < $intervalDays) {
                        $shouldCreateBilling = false;
                    }
                }

                if ($shouldCreateBilling) {
                    $billingCreated = true;
                    $totalAmount += $service->price;

                    $billingDetails[] = [
                        'billing_service_id' => $service->id,
                        'quantity' => 1,
                        'unit_cost' => $service->price,
                    ];
                }
            }

            if ($billingCreated) {
                $billing = Billing::create([
                    'visit_id' => $visit->id,
                    'admin_id' => auth('admin')->id(),
                    'billing_service_id' => $billingDetails[0]['billing_service_id'], // for reference
                    'laboratory_request_id' => null,
                    'bill_date' => now(),
                    'total_amount' => $totalAmount,
                    'discount' => 0,
                    'amount_paid' => 0,
                    'status' => 'pending',
                    'note' => 'Auto-generated billing for recurring services',
                ]);

                foreach ($billingDetails as $detail) {
                    BillingDetail::create([
                        'billing_id' => $billing->id,
                        ...$detail
                    ]);
                }
            }

            if (optional(BusinessSetting::where('key', 'is_live')->first())->value) {
                event(new NewMenuTestResultCreated(
                    'New Visit added for ' . $visit->patient->full_name,
                    '/admin/patient/view/' . $visit->patient->id . '?active=' . $visit->id,
                    'New Visit',
                    'visit.list'
                ));
            }

            DB::commit();

            Toastr::success(translate('Visit recorded successfully!'));
            return redirect()->route('admin.visit.add-new', ['active' => 'invoice-list']);
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Generate a unique visit code.
     *
     * @return string
     */
    private function generateUniqueVisitCode()
    {
        do {
            $code = strtoupper('VIS-' . date('y') . date('m') . date('d') . mt_rand(1000, 9999));
        } while (Visit::where('code', $code)->exists());

        return $code;
    }

    public function show($id)
    {
        $visit = Visit::findOrFail($id);
        return view('admin-views.visit.show', compact('visit'));
    }

    public function edit($id)
    {
        $visit = Visit::findOrFail($id);
        $patients = Patient::all();
        $doctors = Admin::all();
        return view('admin-views.visit.edit', compact('visit', 'patients', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'exists:patients,id',
            'doctor_id' => 'nullable|exists:admins,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'visit_type' => 'in:IPD,OPD',
            'visit_datetime' => 'date',
            'notes' => 'nullable|string',
        ]);
        try {
            $visit = Visit::findOrFail($id);
            $visit->update($request->all());
            Toastr::success(translate('Visit updated successfully!'));
            return redirect()->route('admin.visit.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            Visit::findOrFail($id)->delete();
            Toastr::success(translate('Visit deleted successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }

    public function update_payment(Request $request)
    {
        $request->validate([
            'received_by_id' => 'required|exists:admins,id',
            'billing_id' => 'required|exists:billings,id',
            'amount_left' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,wallet',
            'fn_no' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $billing = Billing::findOrFail($request->billing_id);
            $billing->amount_paid += $request->amount_left;
            if ($billing->amount_paid >= $billing->total_amount) {
                $billing->status = 'paid';
            } elseif ($billing->amount_paid == 0) {
                $billing->status = 'unpaid';
            } else {
                $billing->status = 'partial';
            }
            $billing->save();
            Payment::create([
                'billing_id' => $billing->id,
                'amount_paid' => $request->amount_left,
                'payment_method' => $request->payment_method,
                'fn_no' => $request->fn_no,
                'received_by_id' => $request->received_by_id,
                'invoice_no' => $this->generateUniqueInvoiceNo()
            ]);
            DB::commit();
            Toastr::success(translate('Updated Successfully!'));
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error(translate('An error occurred: ' . $e->getMessage()));
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function cancelOrRefund(Request $request)
    {
        $request->validate([
            'cancel_reason' => 'required|string',
            'billing_id' => 'required|exists:billings,id',
        ]);

        try {
            $billing = Billing::findOrFail($request->billing_id);

            if ($billing->amount_paid > 0) {
                $billing->status = 'refunded';
            } else {
                $billing->status = 'canceled';
            }

            $billing->cancel_reason = $request->get('cancel_reason');
            $billing->canceled_by = $request->get('canceled_by');
            $billing->is_canceled = 1;

            $billing->save();

            return response()->json(['message' => 'Billing canceled successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to cancel billing. Please try again.', 'message' => $e->getMessage()], 500);
        }
    }

    private function generateUniqueInvoiceNo()
    {
        $year = date('y');
        $lastInvoice = Payment::where('invoice_no', 'like', "INV-$year-%")
            ->latest('id')
            ->first();
        if ($lastInvoice && preg_match('/INV-\d{2}-(\d+)/', $lastInvoice->invoice_no, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        return 'INV-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function addDiscount(Request $request)
    {
        $request->validate([
            'billing_id' => 'required|exists:billings,id',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
        ]);

        try {
            $billing = Billing::findOrFail($request->billing_id);

            $amountLeft = $billing->total_amount - $billing->amount_paid;

            $discountAmount = 0;
            if ($request->discount_type === 'fixed') {
                $discountAmount = min($request->discount_value, $amountLeft);
            } elseif ($request->discount_type === 'percent') {
                $discountAmount = $amountLeft * ($request->discount_value / 100);
            }

            $discountAmount = max(0, min($discountAmount, $amountLeft));

            $billing->discount_type = $request->discount_type;
            $billing->discount = $request->discount_value;
            $billing->discounted_from_amount = $amountLeft;
            $billing->discounted_amount = $discountAmount;
            $billing->total_after_discount = $amountLeft - $discountAmount;

            $billing->save();

            Toastr::success(translate('Discount added successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function removeDiscount($id)
    {
        try {
            $billing = Billing::findOrFail($id);

            if ($billing->is_canceled || $billing->amount_paid >= $billing->total_amount) {
                Toastr::error(translate('Cannot remove discount from this billing.'));
                return redirect()->route('admin.visit.add-new');
            }

            $billing->discount_type = null;
            $billing->discount = 0;
            $billing->discounted_from_amount = null;
            $billing->discounted_amount = null;
            $billing->total_after_discount = null;

            $billing->save();

            Toastr::success(translate('Discount removed successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

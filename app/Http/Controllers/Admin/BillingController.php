<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BillingReportExport;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\Payment;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use App\Models\Admin;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    function __construct(
        private Billing $billing,
        private Payment $payment,
    ) {
        $this->middleware('checkAdminPermission:invoice.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:invoice.add-new,index')->only(['index']);
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
        // Validate the request data
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

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $date = $request['date'];

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

        $query = $this->billing->with($with);

        if ($request->has('search') || $request->has('date')) {
            $query->where(function ($q) use ($search) {
                $key = explode(' ', $search);
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhereHas('visit.patient', function ($subQ) use ($value) {
                            $subQ->where('full_name', 'like', "%{$value}%")
                                ->orWhere('registration_no', 'like', "%{$value}%");
                        });
                }
            });

            if ($date) {
                $query->whereDate('created_at', '=', $date);
            }

            $query_param = ['search' => $search, 'date' => $date];
        }

        $query->latest();

        $patientsWithBilling = Patient::has('billings')->get();
        $billings = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.billings.list', compact('billings', 'patientsWithBilling', 'search'));
    }


    public function paymentList(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $receiver_id = $request['receiver_id'];
        $payment_method = $request['payment_method'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        // Define relationships once
        $with = [
            'billing.visit.patient',
            'billing.admin',
            'billing.billingDetail.test',
            'billing.billingDetail.dischargeService.visit.ipdRecord.bed',
            'billing.billingDetail.radiology',
            'billing.billingDetail.billingService',
            'billing.billingDetail.prescreption.medicine.medicine',
            'billing.payments',
            'billing.visit.patient',
            'billing.admin',
            'billing.canceledByAdmin',
            'receivedBy'
        ];

        $query = Payment::with($with);

        // Apply filters
        if (
            $request->has('search') || $request->has('receiver_id') ||
            $request->has('payment_method') || $request->has('start_date') || $request->has('end_date')
        ) {

            // Enhanced search filter - searches across multiple fields
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $key = explode(' ', $search);
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('payment_method', 'like', "%{$value}%")
                            ->orWhere('invoice_no', 'like', "%{$value}%")
                            ->orWhereHas('billing.visit.patient', function ($subQ) use ($value) {
                                $subQ->where('full_name', 'like', "%{$value}%")
                                    ->orWhere('registration_no', 'like', "%{$value}%")
                                    ->orWhere('phone', 'like', "%{$value}%");
                            });
                    }
                });
            }

            // Receiver filter
            if ($receiver_id) {
                $query->where('received_by_id', $receiver_id);
            }

            // Payment method filter
            if ($payment_method) {
                $query->where('payment_method', $payment_method);
            }

            // Date range filters
            if ($start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            }
            if ($end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            }

            $query->latest();
            $query_param = [
                'search' => $search,
                'receiver_id' => $receiver_id,
                'payment_method' => $payment_method,
                'start_date' => $start_date,
                'end_date' => $end_date
            ];
        } else {
            $query->latest();
        }

        // Get data for dropdowns
        $patientsWithPayments = Patient::has('billings.payments')->get();
        $receivers = Admin::whereHas('payments')->distinct()->get();
        $paymentMethods = ['cash', 'bank_transfer', 'wallet'];

        // Calculate statistics for filtered results
        $statsQuery = clone $query;
        $filteredPayments = $statsQuery->get();

        $stats = [
            'total_count' => $filteredPayments->count(),
            'total_amount' => $filteredPayments->sum('amount_paid'),
            'unique_receivers' => $filteredPayments->pluck('received_by_id')->unique()->count(),
            'unique_patients' => $filteredPayments->pluck('billing.visit.patient.id')->unique()->count(),
            'payment_methods' => $filteredPayments->groupBy('payment_method')->map->count(),
        ];

        $payments = $query->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.billings.payment-list', compact(
            'payments',
            'patientsWithPayments',
            'receivers',
            'paymentMethods',
            'stats',
            'search',
            'receiver_id',
            'payment_method',
            'start_date',
            'end_date'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.billings.create', compact('permission'));
    }

    private function generateUniqueTransactionReference()
    {
        $reference = '';

        do {
            $reference = 'TXN' . Str::random(10);;
        } while (Payment::where('transaction_reference', $reference)->exists());

        return $reference;
    }
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('admin-views.billings.show', compact('role', 'rolePermissions'));
    }

    public function view($id)
    {
        $billing = Billing::find($id);


        return view('admin-views.billings.view', compact('billing'));
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

            // Calculate remaining amount
            $amountLeft = $billing->total_amount - $billing->amount_paid;

            // Calculate discounted amount
            $discountAmount = 0;
            if ($request->discount_type === 'fixed') {
                $discountAmount = min($request->discount_value, $amountLeft); // cannot exceed remaining
            } elseif ($request->discount_type === 'percent') {
                $discountAmount = $amountLeft * ($request->discount_value / 100);
            }

            // Clamp discount amount
            $discountAmount = max(0, min($discountAmount, $amountLeft));

            // Fill billing
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
            // Find billing by ID or fail
            $billing = Billing::findOrFail($id);

            // Optional: only allow removing discount if billing is pending
            if ($billing->is_canceled || $billing->amount_paid >= $billing->total_amount) {
                Toastr::error(translate('Cannot remove discount from this billing.'));
                return redirect()->route('admin.billing.list');
            }

            // Remove discount
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


    public function edit($id)
    {
        $billing = Billing::find($id);
        return view('admin-views.billings.edit', compact('billing'));
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
            'billing_name' => 'required|string|max:255|unique:billings,billing_name,' . $id,
            'description' => 'required|string',
        ]);

        try {
            $billing = Billing::findOrFail($id);

            $billing->billing_name = $request->get('billing_name');
            $billing->description = $request->get('description');
            $billing->cost = $request->get('cost');


            $billing->save();

            Toastr::success(translate('billing Updated successfully!'));
            return redirect()->route('admin.billing.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
        }
    }

    public function generatePdf($id)
    {
        $billing = Billing::findOrFail($id);

        $paperSize = $testResult->laboratoryRequestTest->test->paper_size ?? 'A5';

        // Generate PDF with dynamic paper size and orientation
        $pdf = Pdf::loadView('admin-views.billings.pdf', [
            'billing' => $billing
        ])->setPaper($paperSize);


        return $pdf->stream('billing_invoice.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline; filename="billing_invoice.pdf"',
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }

    public function downloadBillingReport(Request $request)
    {
        $request->validate([
            'patient_id' => 'nullable|exists:patients,id', // Make patient_id optional
            'date_from' => 'nullable|date', // Make date_from optional
            'date_to' => 'nullable|date', // Make date_to optional
            'format' => 'required|in:excel,pdf',
        ]);

        // Start the query for billing records
        $billingsQuery = Billing::query();

        // If a patient_id is specified, filter by patient
        if ($request->filled('patient_id')) {
            $billingsQuery->where('patient_id', $request->patient_id);
        }

        // Handle the date filters
        if ($request->filled('date_from') && $request->filled('date_to')) {
            // Both dates are specified
            $billingsQuery->whereBetween('bill_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            // Only date_from is specified
            $billingsQuery->where('bill_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            // Only date_to is specified
            $billingsQuery->where('bill_date', '<=', $request->date_to);
        }

        // Get the billing records
        $billings = $billingsQuery->get();

        // If no billings are found, you might want to handle that case (optional)
        if ($billings->isEmpty()) {
            return response()->json(['message' => 'No billing records found.'], 404);
        }

        // Generate the report based on the selected format
        if ($request->format === 'excel') {
            return Excel::download(new BillingReportExport($billings), 'Billing_Report.xlsx');
        } elseif ($request->format === 'pdf') {
            $pdf = PDF::loadView('admin-views.billings.report_pdf', compact('billings'));
            return $pdf->download('Billing_Report.pdf');
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
            $billing = Billing::findOrFail($id);

            $billing->delete();
            Toastr::success(translate('billing Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::success($e->getMessage());
        }
    }
}

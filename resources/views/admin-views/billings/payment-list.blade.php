@extends('layouts.admin.app')

@section('title', translate('payments List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/billing.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('payments_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $payments->total() }}</span>
        </div>
        @php
            $currency_code = \App\Models\BusinessSetting::where('key', 'currency')->first()->value;
            $currency_position =
                \App\CentralLogics\Helpers::get_business_settings('currency_symbol_position') ?? 'right';
        @endphp

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                                    <span class="avatar-initials">
                                        <i class="tio-money"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="row">
                                    <div class="col-7">
                                        <h6 class="card-title text-nowrap mb-1">{{ translate('Total Amount') }}</h6>
                                        <span class="d-block h4 text-primary mb-0">
                                            {{ $currency_position === 'left' ? $currency_code . ' ' . number_format($stats['total_amount'], 2) : number_format($stats['total_amount'], 2) . ' ' . $currency_code }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm avatar-soft-success avatar-circle">
                                    <span class="avatar-initials">
                                        <i class="tio-receipt"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="row">
                                    <div class="col-7">
                                        <h6 class="card-title text-nowrap mb-1">{{ translate('Total Payments') }}</h6>
                                        <span
                                            class="d-block h4 text-success mb-0">{{ number_format($stats['total_count']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm avatar-soft-info avatar-circle">
                                    <span class="avatar-initials">
                                        <i class="tio-user"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="row">
                                    <div class="col-7">
                                        <h6 class="card-title text-nowrap mb-1">{{ translate('Receivers') }}</h6>
                                        <span
                                            class="d-block h4 text-info mb-0">{{ number_format($stats['unique_receivers']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm avatar-soft-warning avatar-circle">
                                    <span class="avatar-initials">
                                        <i class="tio-user"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="row">
                                    <div class="col-7">
                                        <h6 class="card-title text-nowrap mb-1">{{ translate('Patients') }}</h6>
                                        <span
                                            class="d-block h4 text-warning mb-0">{{ number_format($stats['unique_patients']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="tio-credit-card"></i> {{ translate('Payment Methods Breakdown') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($stats['payment_methods'] as $method => $count)
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="flex-shrink-0">
                                            <div class="avatar avatar-sm avatar-circle">
                                                <span
                                                    class="avatar-initials bg-{{ $method == 'cash' ? 'success' : ($method == 'bank_transfer' ? 'info' : 'warning') }}">
                                                    <i
                                                        class="tio-{{ $method == 'cash' ? 'money' : ($method == 'bank_transfer' ? 'account-balance' : 'wallet') }}"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">{{ translate(ucfirst(str_replace('_', ' ', $method))) }}
                                            </h6>
                                            <span class="text-muted">{{ number_format($count) }}
                                                {{ translate('payments') }}</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span
                                                class="badge badge-soft-{{ $method == 'cash' ? 'success' : ($method == 'bank_transfer' ? 'info' : 'warning') }}">
                                                {{ $stats['total_count'] > 0 ? number_format(($count / $stats['total_count']) * 100, 1) : 0 }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <form action="{{ url()->current() }}" method="GET" id="filter-form">
                            <div class="row gy-3">
                                <!-- Search and Date Row -->
                                <div class="col-lg-12">
                                    <div class="row gy-2">
                                        <div class="col-lg-4 col-md-6">
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{ translate('Search by Patient Name, Phone, Reg No, Invoice No, Payment Method') }}"
                                                aria-label="Search" value="{{ $search }}" autocomplete="off">
                                        </div>
                                        <div class="col-lg-2 col-md-6">
                                            <input type="date" name="start_date" class="form-control"
                                                value="{{ $start_date }}"
                                                placeholder="{{ translate('Start Date') }}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6">
                                            <input type="date" name="end_date" class="form-control"
                                                value="{{ $end_date }}" placeholder="{{ translate('End Date') }}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6">
                                            <select name="receiver_id" class="form-control js-select2-custom">
                                                <option value="">{{ translate('All Receivers') }}</option>
                                                @foreach ($receivers as $receiver)
                                                    <option value="{{ $receiver->id }}"
                                                        {{ $receiver_id == $receiver->id ? 'selected' : '' }}>
                                                        {{ $receiver->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-6">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="tio tio-search"></i>
                                                {{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter Row -->
                                <div class="col-lg-12">
                                    <div class="row gy-2">
                                        <div class="col-lg-3 col-md-6">
                                            <select name="payment_method" class="form-control js-select2-custom">
                                                <option value="">{{ translate('All Payment Methods') }}</option>
                                                @foreach ($paymentMethods as $method)
                                                    <option value="{{ $method }}"
                                                        {{ $payment_method == $method ? 'selected' : '' }}>
                                                        {{ translate(ucfirst(str_replace('_', ' ', $method))) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-9 col-md-6">
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="clearFilters()">
                                                    <i class="tio tio-clear"></i> {{ translate('Clear Filters') }}
                                                </button>
                                                @if ($search || $receiver_id || $payment_method || $start_date || $end_date)
                                                    <span class="badge badge-soft-info align-self-center">
                                                        {{ translate('Filters Applied') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <td>Invoice No.</td>
                                    <th>Patient</th>
                                    <th>Phone</th>
                                    <th>Amount</th>
                                    <th>Received By</th>
                                    <th>Payment Method</th>
                                    <th>Date</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($payments as $key => $payment)
                                    <tr>
                                        <td>{{ $payment->invoice_no }}</td>
                                        <td>{{ $payment->billing->visit->patient->full_name ?? 'N/A' }}</td>
                                        <td>{{ $payment->billing->visit->patient->phone ?? 'N/A' }}</td>
                                        <td>{{ $payment->amount_paid }}</td>
                                        <td>{{ $payment->receivedBy->full_name ?? 'N/A' }}</td>
                                        <td>
                                            <span
                                                class="badge badge-soft-{{ $payment->payment_method == 'cash' ? 'success' : ($payment->payment_method == 'bank_transfer' ? 'info' : 'warning') }}">
                                                {{ translate(ucfirst(str_replace('_', ' ', $payment->payment_method))) }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">

                                                @if (auth('admin')->user()->can('invoice.view'))
                                                    <button class="btn btn-outline-primary square-btn"
                                                        onclick="viewBillingDetails(@js([
    'id' => $payment->billing->id,
    'visit' => [
        'patient' => $payment->billing->visit->patient
            ? [
                'full_name' => $payment->billing->visit->patient->full_name,
                'phone' => $payment->billing->visit->patient->phone,
            ]
            : null,
    ],
    'bill_date' => $payment->billing->bill_date,
    'total_amount' => $payment->billing->total_amount,
    'discount' => $payment->billing->discount ?? 0,
    'amount_paid' => $payment->billing->amount_paid,
    'status' => $payment->billing->status,
    'note' => $payment->billing->note,
    'is_canceled' => $payment->billing->is_canceled,
    'cancel_reason' => $payment->billing->cancel_reason,
    'admin' => $payment->billing->admin
        ? [
            'f_name' => $payment->billing->admin->f_name,
            'l_name' => $payment->billing->admin->l_name,
        ]
        : null,
    'canceled_by_admin' => $payment->billing->canceledByAdmin
        ? [
            'f_name' => $payment->billing->canceledByAdmin->f_name,
            'l_name' => $payment->billing->canceledByAdmin->l_name,
        ]
        : null,
    'laboratory_request_id' => $payment->billing->laboratory_request_id,
    'radiology_request_id' => $payment->billing->radiology_request_id,
    'billing_service_id' => $payment->billing->billing_service_id,
    'billing_from_discharge_id' => $payment->billing->billing_from_discharge_id,
    'emergency_medicine_issuance_id' => $payment->billing->emergency_medicine_issuance_id,
    'patient_procedures_id' => $payment->billing->patient_procedures_id,
    'billing_detail' => $payment->billing->billingDetail
        ? $payment->billing->billingDetail
            ->map(function ($detail) {
                return [
                    'test' => $detail->test ? ['test_name' => $detail->test->test_name] : null,
                    'radiology' => $detail->radiology ? ['radiology_name' => $detail->radiology->radiology_name] : null,
                    'billing_service' => $detail->billingService ? ['service_name' => $detail->billingService->service_name] : null,
                    'prescreption' => $detail->prescreption
                        ? [
                            'medicine' => $detail->prescreption->medicine
                                ? [
                                    'medicine' => $detail->prescreption->medicine->medicine
                                        ? [
                                            'name' => $detail->prescreption->medicine->medicine->name,
                                        ]
                                        : null,
                                ]
                                : null,
                            'quantity' => $detail->prescreption->quantity,
                        ]
                        : null,
                    'discharge_service' => $detail->dischargeService
                        ? [
                            'visit' => $detail->dischargeService->visit
                                ? [
                                    'ipd_record' => $detail->dischargeService->visit->ipdRecord
                                        ? [
                                            'bed' => $detail->dischargeService->visit->ipdRecord->bed
                                                ? [
                                                    'bed_number' => $detail->dischargeService->visit->ipdRecord->bed->bed_number,
                                                ]
                                                : null,
                                        ]
                                        : null,
                                ]
                                : null,
                            'stay_days' => $detail->dischargeService->stay_days,
                        ]
                        : null,
                    'unit_cost' => $detail->unit_cost,
                ];
            })
            ->toArray()
        : [],
    'payments' => $payment->billing->payments
        ? $payment->billing->payments
            ->map(function ($payment) {
                return [
                    'payment_method' => $payment->payment_method,
                    'amount_paid' => $payment->amount_paid,
                    'invoice_no' => $payment->invoice_no,
                ];
            })
            ->toArray()
        : [],
    'patient_procedures' => $payment->billing->patientProcedures
        ? [
            'procedure_name' => $payment->billing->patientProcedures->procedure_name,
            'cost' => $payment->billing->patientProcedures->cost,
        ]
        : null,
]))">
                                                        <i class="tio tio-visible"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->

                    <!-- Pagination -->
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $payments->links() !!}
                        </div>
                    </div>
                    @if (count($payments) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_billing" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Edit Billing') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="edit_billing_form">
                        @csrf
                        <input type="hidden" name="billing_id" id="billing_id" />

                        <div>
                            <h5>Total Amount: <span id="total_amount"></span></h5>
                            <h5>Amount Received: <span id="amount_paid"></span></h5>
                            <h5>Amount Left: <span id="amount_left_display"></span></h5>
                        </div>

                        <div class="form-group mt-4">
                            <label class="input-label" for="amount_left">{{ translate('Amount:') }}</label>
                            <input type="number" step="0.01" name="amount_left" id="amount_left"
                                class="form-control" placeholder="Enter amount" required />
                        </div>

                        <div class="form-group mt-4">
                            <label class="input-label" for="payment_method">{{ translate('Payment Method:') }}</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="cash">{{ translate('Cash') }}</option>
                                <option value="bank_transfer">{{ translate('Bank Transfer') }}</option>
                                <option value="wallet">{{ translate('Wallet') }}</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Billing Details Modal -->
    <div class="modal fade" id="billingModal" tabindex="-1" aria-labelledby="billingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="billingModalLabel">Billing Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="billingDetailsContent">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        // Clear all filters function
        function clearFilters() {
            document.getElementById('filter-form').reset();
            window.location.href = '{{ url()->current() }}';
        }

        // Auto-submit form when select2 values change
        $(document).ready(function() {
            $('.js-select2-custom').on('change', function() {
                $('#filter-form').submit();
            });
        });

        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.invoice.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
    <script>
        $(document).ready(function() {



            $('.edit-billing').click(function() {
                var billingId = $(this).data('id');
                $('#billing_id').val(billingId);
                var totalAmount = $(this).data('total-amount');
                var amountPaid = $(this).data('amount-paid');

                $('#edit_billing_form h5:eq(0)').text('Total Amount: ' + totalAmount);
                $('#edit_billing_form h5:eq(1)').text('Amount Received: ' + amountPaid);
                $('#edit_billing_form h5:eq(2)').text('Amount Left: ' + (totalAmount - amountPaid));

            });
        });

        $('#edit_billing_form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            var formData = new FormData(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.invoice.update_payment') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#edit_billing').modal('hide'); // Hide modal after success
                    $('#edit_billing_form')[0].reset(); // Reset form fields
                    location.reload(); // Reload page to reflect changes
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error, {
                            closeButton: true,
                            progressBar: true
                        });
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
                            });
                    }
                }
            });
        });

        function formatCurrency(amount) {
            var currencySymbol = "{{ $currency_code }}";
            var currencyPosition = "{{ $currency_position }}";
            return currencyPosition === 'left' ? `${currencySymbol} ${amount}` : `${amount} ${currencySymbol}`;
        }

        function formatCurrency(amount) {
            var currencySymbol = "{{ $currency_code }}";
            var currencyPosition = "{{ $currency_position }}";
            return currencyPosition === 'left' ? `${currencySymbol} ${amount}` : `${amount} ${currencySymbol}`;
        }

        function viewBillingDetails(billingData) {

            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                return new Intl.DateTimeFormat('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                }).format(new Date(dateString));
            }

            var modalBody = document.getElementById('billingDetailsContent');

            var content = `
                <div class="p-3">
                    <h4 class="text-primary mb-3"><i class="tio tio-receipt"></i> Billing Information</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Patient:</strong> <span class="text-dark">${billingData.visit.patient ? billingData.visit.patient.full_name : 'N/A'}</span></p>
                            <p><strong>Billing Date:</strong> <span class="text-secondary">${formatDate(billingData.bill_date)}</span></p>
                            <p><strong>Total Amount:</strong> <span class="badge bg-dark text-white px-2 py-1"> ${formatCurrency(billingData.total_amount)}</span></p>
                            <p><strong>Discount:</strong> <span class="badge bg-secondary text-white px-2 py-1"> ${formatCurrency(billingData.discount)}</span></p>
                        </div>

                        <div class="col-md-6">
                            <p><strong>Amount Paid:</strong> <span class="badge bg-primary text-white px-2 py-1"> ${formatCurrency(billingData.amount_paid)}</span></p>
                            <p><strong>Status:</strong>
                                <span class="badge ${billingData.status === 'paid' ? 'bg-success' : (billingData.status === 'pending' || billingData.status === 'refunded' ||
                                    billingData.status === 'canceled' ? 'bg-warning text-dark' : 'bg-danger')} px-2 py-1">
                                    ${billingData.status.charAt(0).toUpperCase() + billingData.status.slice(1)}
                                </span>
                            </p>
                            <p><strong>Created By:</strong> <span class="text-dark">${billingData.admin ? billingData.admin.f_name + ' ' + billingData.admin.l_name : 'N/A'}</span></p>
                        </div>
                    </div>
                    <p><strong>Note:</strong> <span class="text-muted">${billingData.note ? billingData.note : 'No additional notes'}</span></p>
                    ${billingData.is_canceled == 1 ? `
                                                                                                                                                                                                        <p><strong>Canceled/Refunded By:</strong> <span class="text-muted">${billingData.canceled_by_admin ? billingData.canceled_by_admin.f_name + ' ' + billingData.canceled_by_admin.l_name : 'N/A'}</span></p>
                                                                                                                                                                                                        <p><strong>Reason:</strong> <span class="text-muted">${billingData.cancel_reason ? billingData.cancel_reason : 'No additional notes'}</span></p>
                                                                                                                                                                                                        ` : ''
                    }
                    <hr class="my-3">

                    <!-- Display Test Details -->
                    ${billingData.laboratory_request_id ? `
                                                                                                                                                                                                        <h5 class="text-secondary"><i class="tio tio-lab"></i> Test Details</h5>
                                                                                                                                                                                                        <ul class="list-group mb-3">
                                                                                                                                                                                                            ${billingData.billing_detail && billingData.billing_detail.length > 0 ?
                                                                                                                                                                                                                billingData.billing_detail.map(detail => `
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="tio tio-vial"></i> ${detail.test ? detail.test.test_name : 'N/A'}</span>
                                        <span class="badge bg-dark text-white px-2 py-1"> ${formatCurrency(detail.unit_cost)} </span>
                                    </li>
                                `).join('')
                                                                                                                                                                                                                : '<li class="list-group-item text-muted">No test details available</li>'
                                                                                                                                                                                                            }
                                                                                                                                                                                                        </ul>` : ''
                    }

                    <!-- Display Test Details -->
                    ${billingData.radiology_request_id ? `
                                                                                                                                                                                                        <h5 class="text-secondary"><i class="tio tio-lab"></i> Radiology Details</h5>
                                                                                                                                                                                                        <ul class="list-group mb-3">
                                                                                                                                                                                                            ${billingData.billing_detail && billingData.billing_detail.length > 0 ?
                                                                                                                                                                                                                billingData.billing_detail.map(detail => `
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="tio tio-vial"></i> ${detail.radiology ? detail.radiology.radiology_name : 'N/A'}</span>
                                        <span class="badge bg-dark text-white px-2 py-1"> ${formatCurrency(detail.unit_cost)} </span>
                                    </li>
                                `).join('')
                                                                                                                                                                                                                : '<li class="list-group-item text-muted">No test details available</li>'
                                                                                                                                                                                                            }
                                                                                                                                                                                                        </ul>` : ''
                    }

                    <!-- Display Billing Services -->
                    ${billingData.billing_service_id ? `
                                                                                                                                                                                                    <h5 class="text-secondary"><i class="tio tio-receipt"></i> Billing Services</h5>
                                                                                                                                                                                                    <ul class="list-group mb-3">
                                                                                                                                                                                                        ${billingData.billing_detail && billingData.billing_detail.length > 0
                                                                                                                                                                                                            ? billingData.billing_detail.map(detail => `
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="tio tio-list"></i> ${detail.billing_service ? detail.billing_service.service_name : 'N/A'}</span>
                                        <span class="badge bg-dark text-white px-2 py-1"> ${formatCurrency(detail.unit_cost)} </span>
                                    </li>
                                `).join('')
                                                                                                                                                                                                            : '<li class="list-group-item text-muted">No billing services available</li>'
                                                                                                                                                                                                        }
                                                                                                                                                                                                    </ul>
                                                                                                                                                                                                ` : ''}
                    <!-- Display Billing from Discharge -->
                    ${billingData.billing_from_discharge_id ? `
                                                                                                                                                                            <h5 class="text-secondary"><i class="tio tio-receipt"></i> Discharge Billing Details</h5>
                                                                                                                                                                            <ul class="list-group mb-3">
                                                                                                                                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                                                                                                <span><i class="tio tio-list"></i> Bed Name</span>
                                                                                                                                                                                <span><i class="tio tio-list"></i>Days</span>
                                                                                                                                                                                <span><i class="tio tio-list"></i>Daily price</span>
                                                                                                                                                                            </li>
                                                                                                                                                                                ${billingData.billing_detail && billingData.billing_detail.length > 0
                                                                                                                                                                                    ? billingData.billing_detail.map(detail => {
                                                                                                                                                                                        const bedNumber = detail.discharge_service.visit.ipd_record.bed.bed_number ?? 'N/A';
                                                                                                                                                                                        const days = detail.discharge_service.stay_days ?? 1; // fallback to 1 if null
                                                                                                                                                                                        return `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="tio tio-list"></i> ${bedNumber}</span>
                                <span><i class="tio tio-list"></i>${days} day${days > 1 ? 's' : ''}</span>
                                <span class="badge bg-dark text-white px-2 py-1">${formatCurrency(detail.unit_cost)}</span>
                                </li>
                            `;
                                                                                                                                                                                    }).join('')
                                                                                                                                                                                    : '<li class="list-group-item text-muted">No billing details found for this discharge</li>'
                                                                                                                                                                                }
                                                                                                                                                                            </ul>
                                                                                                                                                                        ` : ''}

                    <!-- Display Emergency Services -->
                    ${billingData.emergency_medicine_issuance_id ? `
                                                                                                    <h5 class="text-secondary"><i class="tio tio-receipt"></i>  Inclinic Items</h5>
                                                                                                    <ul class="list-group mb-3">
                                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                    <span><i class="tio tio-list"></i> Service Name</span>
                                                                                                    <span><i class="tio tio-list"></i>Quantity</span>
                                                                                                    <span><i class="tio tio-list"></i>Unit price</span>
                                                                                                    </li>
                                                                                                    ${
                                                                                                        billingData.billing_detail && billingData.billing_detail.length > 0
                                                                                                            ? billingData.billing_detail.map(detail => `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="tio tio-list"></i> ${detail.prescreption ? detail.prescreption.medicine.medicine.name : 'N/A'}</span>
                                    <span><i class="tio tio-list"></i> ${detail.prescreption ? detail.prescreption.quantity : '0'}</span>
                                    <span class="badge bg-dark text-white px-2 py-1"> ${formatCurrency(detail.unit_cost)} </span>
                                </li>
                            `).join('')
                                                                                                                                                                                                                                                                        : '<li class="list-group-item text-muted">No billing services available</li>'
                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                            </ul>
                                                                                                                                                                                                                                                        ` : ''}


                    <!-- Display Patient Procedures -->
                    ${billingData.patient_procedures_id ? `
                                                                                                                                                                                                                                                                                    <h5 class="text-secondary"><i class="tio tio-surgery"></i> Patient Procedures</h5>
                                                                                                                                                                                                                                                                                    <ul class="list-group mb-3">
                                                                                                                                                                                                                                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                                                                                                                                                                                                            <span><i class="tio tio-scissors"></i> Procedure: ${billingData.patient_procedures ? billingData.patient_procedures.procedure_name : 'N/A'}</span>
                                                                                                                                                                                                                                                                                            <span class="badge bg-dark text-white px-2 py-1"> ${formatCurrency(billingData.patient_procedures ? billingData.patient_procedures.cost : 0)}</span>
                                                                                                                                                                                                                                                                                        </li>
                                                                                                                                                                                                                                                                                    </ul>` : ''
                    }

                    <h5 class="text-secondary"><i class="tio tio-credit-card"></i> Payment History</h5>
                    <ul class="list-group">
                        ${billingData.payments && billingData.payments.length > 0 ?
                            billingData.payments.map(payment => `
                                                                                                                                                                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                                                                                                                                <span><i class="tio tio-wallet"></i>Received ${payment.payment_method} - <strong>${formatCurrency(payment.amount_paid)}</strong></span>
                                                                                                                                                                                                                <span class="text-muted">${payment.invoice_no || 'N/A'}</span>
                                                                                                                                                                                                            </li>
                                                                                                                                                                                                        `).join(''):''
                        }
                            ${billingData.is_canceled == 1 && billingData.status == 'refunded' ?
                            `<li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                                                                                                                                <span><i class="tio tio-wallet"></i> ${billingData.status} - <strong>${formatCurrency(billingData.amount_paid)}</strong></span>
                                                                                                                                                                                                                <span class="text-muted">By ${billingData.canceled_by_admin.f_name + ' ' + billingData.canceled_by_admin.l_name || 'N/A'}</span>
                                                                                                                                                                                                            </li>`:''}
                            ${(billingData.payments && billingData.payments.length == 0 && (billingData.is_canceled == 0 || billingData.status == 'canceled')) ?
                            '<li class="list-group-item text-muted">No payment history available</li>':''}
                    </ul>
                </div>
            `;

            modalBody.innerHTML = content;

            $('#billingModal').modal('show');
        }
    </script>
    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush
@push('script')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush

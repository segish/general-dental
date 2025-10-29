@extends('layouts.admin.app')

@section('title', translate('billings List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ \App\CentralLogics\translate('billings_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $billings->total() }}</span>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Search by Patient Name / Reg No') }}"
                                            aria-label="Search" value="{{ $search }}" autocomplete="off">
                                        <input type="date" name="date" class="form-control"
                                            value="{{ request('date') }}" />

                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Patient') }}</th>
                                    <th>{{ \App\CentralLogics\translate('By') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Total') }}</th>
                                    {{-- <th>{{ \App\CentralLogics\translate('Discount Type') }}</th> --}}
                                    <th>{{ \App\CentralLogics\translate('Discount') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Received') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Amount Left') }}</th>
                                    {{-- <th>{{ \App\CentralLogics\translate('Total after Discount') }}</th> --}}
                                    <th>{{ \App\CentralLogics\translate('Status') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Date') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($billings as $key => $billing)
                                    <tr>
                                        <td>{{ $billings->firstitem() + $key }}</td>
                                        <td>{{ $billing->visit->patient->full_name }}</td>
                                        {{-- <td>
                                            @php
                                                $testNames = $billing->billingDetail
                                                    ->map(function ($detail) {
                                                        return $detail->test->test_name;
                                                    })
                                                    ->implode(', ');
                                            @endphp
                                            {{ $testNames }}
                                        </td> --}}

                                        <td>{{ $billing->admin->f_name }}</td>

                                        <td>
                                            @if ($billing->laboratory_request_id)
                                                {{-- @if ($billing->billingDetail)
                                                    @foreach ($billing->billingDetail as $detail)
                                                        {{ $detail->test ? $detail->test->test_name . ',' : 'N/A,' }}
                                                    @endforeach
                                                @endif --}}

                                                Laboratory service
                                            @elseif ($billing->radiology_request_id)
                                                Radiology service
                                            @elseif($billing->billing_service_id)
                                                {{-- @if ($billing->billingDetail)
                                                    @foreach ($billing->billingDetail as $detail)
                                                        {{ $detail->billingService ? $detail->billingService->service_name . ',' : 'N/A,' }}
                                                    @endforeach
                                                @endif --}}
                                                Card/Procedure
                                            @elseif($billing->emergency_medicine_issuance_id)
                                                {{-- {{ $billing->emergency_medicine_issuance ? $billing->emergency_medicine_issuance->medicine_name : 'N/A' }} --}}
                                                inclinic service
                                            @elseif($billing->patient_procedures_id)
                                                {{-- {{ $billing->patient_procedures ? $billing->patient_procedures->procedure_name : 'N/A' }} --}}
                                                store service
                                            @elseif($billing->billing_from_discharge_id)
                                                {{-- {{ $billing->patient_procedures ? $billing->patient_procedures->procedure_name : 'N/A' }} --}}
                                                discharge service
                                            @endif
                                        </td>

                                        <td>{{ $billing->total_amount }}</td>
                                        {{-- <td>{{ $billing->discount_type ?? 'N/A' }}</td> --}}
                                        <td>{{ $billing->discounted_amount ?? 0 }}</td>
                                        <td>{{ $billing->amount_paid }}</td>
                                        <td>
                                            @php
                                                if ($billing->discounted_amount > 0) {
                                                    $amountLeft =
                                                        $billing->total_after_discount - $billing->amount_paid;
                                                } else {
                                                    $amountLeft = $billing->total_amount - $billing->amount_paid;
                                                }
                                            @endphp
                                            {{ $amountLeft }}
                                        </td>
                                        {{-- <td>{{ $billing->total_after_discount ?? 'N/A' }}</td> --}}
                                        <td>

                                            @if ($billing->amount_paid >= $billing->total_amount && $billing->is_canceled == 0)
                                                <span style="font-weight: bold" class="text-success"> Paid </span>
                                            @endif

                                            @if ($billing->amount_paid == 0 && $billing->is_canceled == 0)
                                                <span style="font-weight: bold" class="text-danger" style="color: red">
                                                    Unpaid </span>
                                            @endif

                                            @if ($billing->amount_paid > 0 && $billing->amount_paid < $billing->total_amount && $billing->is_canceled == 0)
                                                <span style="font-weight: bold; color:rgba(255, 138, 14, 0.776)">Partial
                                                    ({{ $billing->total_amount - $billing->amount_paid }})
                                                </span>
                                            @endif

                                            @if ($billing->is_canceled == 1)
                                                <span style="font-weight: bold; color: rgba(255, 138, 14, 0.776);">
                                                    {{ $billing->status }} </span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($billing->created_at)->format('M d, Y') }}</td>

                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">

                                                @if (auth('admin')->user()->can('invoice.view'))
                                                    <button class="btn btn-outline-primary square-btn"
                                                        onclick="viewBillingDetails(@js($billing))">
                                                        <i class="tio tio-visible"></i>
                                                    </button>
                                                @endif
                                                @if (auth('admin')->user()->can('invoice.pdf') &&
                                                        !($billing->amount_paid < $billing->total_amount) &&
                                                        !$billing->is_canceled)
                                                    <a class="btn btn-sm btn-outline-primary mr-2 px-1 py-1"
                                                        href="{{ route('admin.invoice.pdf', [$billing->id]) }}"
                                                        target="_blank">

                                                        <i class="tio tio-receipt"></i>
                                                    </a>
                                                @endif

                                                @if (auth('admin')->user()->can('invoice.edit') &&
                                                        $billing->amount_paid < $billing->total_amount &&
                                                        !$billing->is_canceled)
                                                    <button class="btn btn-outline-primary square-btn edit-billing"
                                                        data-toggle="modal" data-target="#edit_billing"
                                                        data-id="{{ $billing->id }}"
                                                        data-total-amount="{{ $billing->total_amount }}"
                                                        data-amount-paid="{{ $billing->amount_paid }}"
                                                        data-discount-value="{{ $billing->discount }}"
                                                        data-discount-type="{{ $billing->discount_type }}"
                                                        data-discounted-amount="{{ $billing->discounted_amount }}"
                                                        data-total_after_discount="{{ $billing->total_after_discount }}">
                                                        <i class="tio tio-edit"></i>
                                                    </button>
                                                @endif

                                                @if (auth('admin')->user()->can('invoice.add-discount') &&
                                                        !$billing->is_canceled &&
                                                        $billing->discounted_amount == null)
                                                    <button class="btn btn-outline-warning square-btn add-discount"
                                                        data-toggle="modal" data-target="#add_discount"
                                                        data-id="{{ $billing->id }}"
                                                        data-total-amount="{{ $billing->total_amount }}"
                                                        data-amount-paid="{{ $billing->amount_paid }}">
                                                        <i class="tio tio-ticket"></i>
                                                    </button>
                                                @endif

                                                @if (auth('admin')->user()->can('invoice.remove-discount') &&
                                                        !$billing->is_canceled &&
                                                        $billing->amount_paid < $billing->total_amount &&
                                                        $billing->discounted_amount > 0)
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('billing-{{ $billing->id }}','{{ \App\CentralLogics\translate('Want to Remove this discount?') }}')">
                                                        <i class="tio tio-remove"></i>
                                                    </a>
                                                @endif


                                                @if (auth('admin')->user()->can('invoice.cancel-or-refund') && !$billing->is_canceled)
                                                    <button class="btn btn-outline-danger square-btn cancel-or-refund"
                                                        data-toggle="modal" data-target="#cancel_or_refund"
                                                        data-id="{{ $billing->id }}"
                                                        data-total-amount="{{ $billing->total_amount }}"
                                                        data-amount-paid="{{ $billing->amount_paid }}">
                                                        <i class="tio tio-undo"></i>
                                                    </button>
                                                @endif

                                            </div>
                                            <form action="{{ route('admin.invoice.remove-discount', [$billing->id]) }}"
                                                method="post" id="billing-{{ $billing->id }}">
                                                @csrf
                                            </form>
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
                            {!! $billings->links() !!}
                        </div>
                    </div>
                    @if (count($billings) == 0)
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
                        <input type="text" hidden name="received_by_id" value="{{ auth('admin')->user()->id }}">

                        <div>
                            <h5>Total Amount: <span id="total_amount"></span></h5>
                            <h5>Amount Received: <span id="amount_paid"></span></h5>
                            <h5>Amount Left: <span id="amount_left_display"></span></h5>

                            <!-- Discount info, only show if exists -->
                            <div id="discount_info" style="display:none;">
                                <h5>Discounted Amount: <span id="discounted_amount"></span></h5>
                                <h5>Amount after Discount: <span id="amount_after_discount"></span></h5>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label class="input-label" for="fn_no">{{ translate('FS No') }}</label>
                            <input type="text" name="fn_no" id="fn_no" class="form-control"
                                placeholder="Enter FS No from receipt" />
                        </div>

                        <div class="form-group mt-4">
                            <label class="input-label" for="amount_left">{{ translate('Amount:') }}<span
                                    class="input-label-secondary text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount_left" id="amount_left"
                                class="form-control" placeholder="Enter amount" required min="1" />
                        </div>

                        <div class="form-group mt-4">
                            <label class="input-label" for="payment_method">{{ translate('Payment Method:') }}<span
                                    class="input-label-secondary text-danger">*</span></label>
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


    <div class="modal fade" id="add_discount" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add Discount') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="add_discount_form">
                        @csrf
                        <input type="hidden" name="billing_id" id="billing_id" />
                        <input type="text" hidden name="received_by_id" value="{{ auth('admin')->user()->id }}">

                        <div>
                            <h5>Total Amount: <span id="total_amount"></span></h5>
                            <h5>Amount Received: <span id="amount_paid"></span></h5>
                            <h5>Amount Left: <span id="amount_left_display"></span></h5>
                            <h5>Discounted Amount: <span id="discounted_amount"></span></h5>
                            <h5>Amount after Discount: <span id="amount_after_discount"></span></h5>
                        </div>
                        <div class="form-group mt-4">
                            <label for="discount_type">{{ translate('Discount Type') }}<span
                                    class="input-label-secondary text-danger">*</span></label>
                            <select class="form-control" id="discount_type" name="discount_type">
                                <option value="=" disabled selected>Select Discount Type</option>
                                <option value="fixed">Fixed Amount</option>
                                <option value="percent">Percentage</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label class="input-label" for="discount_value">{{ translate('discount_value:') }}<span
                                    class="input-label-secondary text-danger">*</span></label>
                            <input type="number" step="0.01" name="discount_value" id="discount_value"
                                class="form-control" placeholder="Enter amount" required min="1" />
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="cancel_or_refund" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Cancel or Refund Billing') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="cancel_or_refund_form">
                        @csrf
                        <input type="hidden" name="billing_id" id="billing_id_cancel" />
                        <input type="text" hidden name="canceled_by" value="{{ auth('admin')->user()->id }}">

                        <div>
                            <h5>Total Amount: <span id="total_amount"></span></h5>
                            <h5>Amount Received: <span id="amount_paid"></span></h5>
                            <h5>Amount Left: <span id="amount_left_display_cancel"></span></h5>
                        </div>

                        <div class="form-group mt-4">
                            <label class="input-label"
                                for="cancel_reason">{{ translate('Reason for Cancel or Refund') }}</label>
                            <textarea name="cancel_reason" id="cancel_reason" class="form-control"
                                placeholder="Enter Reason for Cancel or Refund" rows="3"></textarea>
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
    @php
        $currency_code = \App\Models\BusinessSetting::where('key', 'currency')->first()->value;
        $currency_position = \App\CentralLogics\Helpers::get_business_settings('currency_symbol_position') ?? 'right';
    @endphp
@endsection

@push('script_2')
    <script>
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
        function disableButton(button) {
            const originalText = button.html();
            button.prop('disabled', true);
            button.html('<i class="tio-sync spin"></i> Loading...');
            return originalText;
        }

        // Function to re-enable button
        function enableButton(button, originalText) {
            button.prop('disabled', false);
            button.html(originalText);
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.edit-billing').click(function() {
                const button = $(this);
                const modal = $('#edit_billing');

                const billingId = button.data('id');
                const totalAmount = parseFloat(button.data('total-amount')) || 0;
                const amountPaid = parseFloat(button.data('amount-paid')) || 0;
                const discountedAmount = parseFloat(button.data('discounted-amount')) || 0;

                // Calculate remaining amount
                let amountLeft = totalAmount - amountPaid;

                // If there is a discount, use total after discount
                if (discountedAmount > 0) {
                    const totalAfterDiscount = parseFloat(button.data('total_after_discount')) ||
                        amountLeft;
                    amountLeft = totalAfterDiscount - amountPaid;
                }

                // Fill modal
                modal.find('#billing_id').val(billingId);
                modal.find('#total_amount').text(totalAmount.toFixed(2));
                modal.find('#amount_paid').text(amountPaid.toFixed(2));
                modal.find('#amount_left_display').text(amountLeft.toFixed(2));
                modal.find('#amount_left').attr('max', amountLeft.toFixed(2)).val(amountLeft.toFixed(2));
                modal.find('#amount_left').val('');
                // Show discount info only if discountedAmount > 0
                if (discountedAmount > 0) {
                    modal.find('#discount_info').show();
                    modal.find('#discounted_amount').text(discountedAmount.toFixed(2));
                    modal.find('#amount_after_discount').text((totalAmount - discountedAmount - amountPaid)
                        .toFixed(2));
                } else {
                    modal.find('#discount_info').hide();
                    modal.find('#discounted_amount').text('0.00');
                    modal.find('#amount_after_discount').text(amountLeft.toFixed(2));
                }
            });



            $('#edit_billing_form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                var formData = new FormData(this);

                const submitButton = $(this).find('button[type="submit"]');
                const originalText = disableButton(submitButton);

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
                        toastr.success('{{ translate('Payment updated successfully!') }}', {
                            closeButton: true,
                            progressBar: true
                        });
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
                    },
                    complete: function() {
                        setTimeout(function() {
                            enableButton(submitButton, originalText);
                        }, 5000);
                    }
                });
            });

            $('.cancel-or-refund').click(function() {
                var billingId = $(this).data('id');
                $('#billing_id_cancel').val(billingId);
                console.log(billingId);
                var totalAmount = $(this).data('total-amount');
                var amountPaid = $(this).data('amount-paid');
                var amountLeft = totalAmount - amountPaid;

                // Update the text in the modal
                $('#cancel_or_refund_form h5:eq(0)').text('Total Amount: ' + totalAmount);
                $('#cancel_or_refund_form h5:eq(1)').text('Amount Received: ' + amountPaid);
                $('#cancel_or_refund_form h5:eq(2)').text('Amount Left: ' + amountLeft);

                var modal_title = $('#cancel_or_refund').find('.modal-title');
                var reason_label = $('#cancel_or_refund').find('.input-label');
                var reason_placeholder = $('#cancel_or_refund').find('#cancel_reason');
                if (amountPaid <= 0) {
                    modal_title.text('Cancel Billing');
                    reason_label.text('Reason for Cancelation');
                    reason_placeholder.attr('placeholder', 'Enter Reason for Cancelation');
                } else {
                    modal_title.text('Refund Payment');
                    reason_label.text('Reason for Refund');
                    reason_placeholder.attr('placeholder', 'Enter Reason for Refund');
                }
            });

            $('#cancel_or_refund_form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                var formData = new FormData(this);

                const submitButton = $(this).find('button[type="submit"]');
                const originalText = disableButton(submitButton);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route('admin.invoice.cancel-or-refund') }}',
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#cancel_or_refund').modal('hide'); // Hide modal after success
                        $('#cancel_or_refund_form')[0].reset(); // Reset form fields
                        toastr.success('{{ translate('Payment updated successfully!') }}', {
                            closeButton: true,
                            progressBar: true
                        });
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
                    },
                    complete: function() {
                        setTimeout(function() {
                            enableButton(submitButton, originalText);
                        }, 5000);
                    }
                });
            });

        });



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
                                                        <span><i class="tio tio-list"></i> Item Name</span>
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
        $(document).ready(function() {
            $('#add_discount').on('show.bs.modal', function(e) {
                const button = $(e.relatedTarget);
                const modal = $(this);

                const totalAmount = parseFloat(button.attr('data-total-amount')) || 0;
                const amountPaid = parseFloat(button.attr('data-amount-paid')) || 0;
                const amountLeft = Math.max(totalAmount - amountPaid, 0);

                // Fill modal
                modal.find('#billing_id').val(button.attr('data-id'));
                modal.find('#total_amount').text(totalAmount.toFixed(2));
                modal.find('#amount_paid').text(amountPaid.toFixed(2));
                modal.find('#amount_left_display').text(amountLeft.toFixed(2));

                // Reset
                modal.find('#discount_type').val('');
                modal.find('#discount_value').val('');
                modal.find('#discounted_amount').text('0.00');
                modal.find('#amount_after_discount').text(amountLeft.toFixed(2));

                // --- Live calculation based on remaining amount ---
                modal.find('#discount_type, #discount_value').off('input change').on('input change',
                    function() {
                        const type = modal.find('#discount_type').val();
                        const val = parseFloat(modal.find('#discount_value').val());
                        let discount = 0;

                        if (!isNaN(val)) {
                            if (type === 'fixed') {
                                discount = val; // fixed amount applied to remaining
                            } else if (type === 'percent') {
                                discount = amountLeft * (val / 100); // percentage of remaining
                            }
                        }

                        // Clamp: discount can’t exceed amount left
                        discount = Math.min(discount, amountLeft);

                        modal.find('#discounted_amount').text(discount.toFixed(2));
                        modal.find('#amount_after_discount').text((amountLeft - discount).toFixed(2));
                    });
            });



            $('#add_discount_form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                var formData = new FormData(this);

                const submitButton = $(this).find('button[type="submit"]');
                const originalText = disableButton(submitButton);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route('admin.invoice.add-discount') }}',
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#add_discount').modal('hide'); // Hide modal after success
                        $('#add_discount_form')[0].reset(); // Reset form fields
                        toastr.success('{{ translate('Payment updated successfully!') }}', {
                            closeButton: true,
                            progressBar: true
                        });
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
                    },
                    complete: function() {
                        setTimeout(function() {
                            enableButton(submitButton, originalText);
                        }, 5000);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush

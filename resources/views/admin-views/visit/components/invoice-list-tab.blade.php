<!-- Invoice List Tab Content -->
<div class="tab-pane fade {{ request()->get('active') == 'invoice-list' ? 'show active' : '' }}" id="invoice-list" role="tabpanel" aria-labelledby="invoice-list-tab">
    <div class="px-20 py-3">
        <div class="row gy-2 align-items-center">
            <div class="col-lg-6 col-sm-6 col-md-6">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <input id="datatableSearch_" type="search" name="billing_search" class="form-control"
                            placeholder="{{ translate('Search by Patient Name / Reg No') }}" aria-label="Search"
                            value="{{ $billing_search }}" autocomplete="off">
                        <input type="date" name="billing_date" class="form-control"
                            value="{{ request('billing_date') }}" />

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive datatable-custom">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
                <tr>
                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                    <th>{{ \App\CentralLogics\translate('Patient') }}</th>
                    <th>{{ \App\CentralLogics\translate('By') }}</th>
                    <th>{{ \App\CentralLogics\translate('Type') }}</th>
                    <th>{{ \App\CentralLogics\translate('Amount') }}</th>
                    <th>{{ \App\CentralLogics\translate('Status') }}</th>
                    <th>{{ \App\CentralLogics\translate('Date') }}</th>
                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                </tr>
            </thead>

            <tbody id="set-rows">
                @foreach ($billings as $key => $billing)
                    @php
                        $amountPaid = $billing->amount_paid;
                        // use discounted calculation if available
                        if ($billing->discounted_amount > 0) {
                            $amountLeft = $billing->total_after_discount - $billing->amount_paid;
                            $total = $billing->total_after_discount;
                        } else {
                            $amountLeft = $billing->total_amount - $billing->amount_paid;
                            $total = $billing->total_amount;
                        }
                    @endphp
                    <tr @if($amountLeft > 0) style="background-color: #ffe3e0 !important;" @endif>
                        <td>{{ $billings->firstitem() + $key }}</td>
                        <td>{{ $billing->visit->patient->full_name }}</td>
                        <td>{{ $billing->admin->f_name }}</td>
                        <td>
                            @if ($billing->laboratory_request_id)
                                Laboratory
                            @elseif ($billing->radiology_request_id)
                                Radiology
                            @elseif($billing->billing_service_id)
                                Card/Procedure
                            @elseif($billing->emergency_medicine_issuance_id)
                                inclinic
                            @elseif($billing->patient_procedures_id)
                                store
                            @elseif($billing->billing_from_discharge_id)
                                discharge
                            @endif
                        </td>
                        <td>
                            <span class="text-danger" style="font-weight:bold;">
                                {{ number_format($amountLeft, 2) }}
                            </span>
                            <span> / </span>
                            <span class="text-dark" style="font-weight:bold;">
                                {{ number_format($total, 2) }}
                            </span>
                            <span> - </span>
                            <span class="text-success" style="font-weight:bold;">
                                {{ number_format($amountPaid, 2) }}
                            </span>
                        </td>
                        <td>
                            @if ($billing->amount_paid >= $total && $billing->is_canceled == 0)
                                <span style="font-weight: bold" class="text-success"> Paid </span>
                            @elseif ($billing->amount_paid == 0 && $billing->is_canceled == 0)
                                <span style="font-weight: bold" class="text-danger" style="color: red">
                                    Unpaid </span>
                            @elseif ($billing->amount_paid > 0 && $billing->amount_paid < $total && $billing->is_canceled == 0)
                                <span style="font-weight: bold; color:rgba(255, 138, 14, 0.776)">Partial
                                    ({{ number_format($total - $amountPaid, 2) }})
                                </span>
                            @elseif ($billing->is_canceled == 1)
                                <span style="font-weight: bold; color: rgba(255, 138, 14, 0.776);">
                                    {{ $billing->status }} </span>
                            @endif
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($billing->created_at)->format('M d, Y') }}<br>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($billing->created_at)->format('h:i A') }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                @if (auth('admin')->user()->can('invoice.view'))
                                    <button class="btn btn-outline-primary square-btn"
                                        onclick="viewBillingDetails(@js($billing))">
                                        <i class="tio tio-visible"></i>
                                    </button>
                                @endif
                                @if (auth('admin')->user()->can('invoice.pdf') &&
                                        !($billing->amount_paid < $total) &&
                                        !$billing->is_canceled)
                                    <a class="btn btn-sm btn-outline-primary mr-2 px-1 py-1"
                                        href="{{ route('admin.invoice.pdf', [$billing->id]) }}" target="_blank">
                                        <i class="tio tio-receipt"></i>
                                    </a>
                                @endif

                                @if (auth('admin')->user()->can('invoice.edit') &&
                                        $billing->amount_paid < $total &&
                                        !$billing->is_canceled)
                                    <button class="btn btn-outline-primary square-btn edit-billing" data-toggle="modal"
                                        data-target="#edit_billing" data-id="{{ $billing->id }}"
                                        data-total-amount="{{ $billing->total_amount }}"
                                        data-amount-paid="{{ $billing->amount_paid }}"
                                        data-discount-value="{{ $billing->discount }}"
                                        data-discount-type="{{ $billing->discount_type }}"
                                        data-discounted-amount="{{ $billing->discounted_amount }}"
                                        data-total_after_discount="{{ $billing->total_after_discount }}">
                                        <i class="tio tio-edit"></i>
                                    </button>
                                @endif

                                @if (auth('admin')->user()->can('invoice.add-discount') && !$billing->is_canceled && $billing->discounted_amount == null)
                                    <button class="btn btn-outline-warning square-btn add-discount" data-toggle="modal"
                                        data-target="#add_discount" data-id="{{ $billing->id }}"
                                        data-total-amount="{{ $billing->total_amount }}"
                                        data-amount-paid="{{ $billing->amount_paid }}">
                                        <i class="tio tio-ticket"></i>
                                    </button>
                                @endif

                                @if (auth('admin')->user()->can('invoice.remove-discount') &&
                                        !$billing->is_canceled &&
                                        $billing->amount_paid < $total &&
                                        $billing->discounted_amount > 0)
                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                        onclick="form_alert('billing-{{ $billing->id }}','{{ \App\CentralLogics\translate('Want to Remove this discount?') }}')">
                                        <i class="tio tio-remove"></i>
                                    </a>
                                @endif

                                @if (auth('admin')->user()->can('invoice.cancel-or-refund') && !$billing->is_canceled)
                                    <button class="btn btn-outline-danger square-btn cancel-or-refund"
                                        data-toggle="modal" data-target="#cancel_or_refund"
                                        data-id="{{ $billing->id }}" data-total-amount="{{ $billing->total_amount }}"
                                        data-amount-paid="{{ $billing->amount_paid }}">
                                        <i class="tio tio-undo"></i>
                                    </button>
                                @endif
                            </div>
                            <form action="{{ route('admin.visit.remove-discount', [$billing->id]) }}" method="post"
                                id="billing-{{ $billing->id }}">
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
            <img class="mb-3" src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                alt="Image Description" style="width: 7rem;">
            <p class="mb-0">{{ translate('No data to show') }}</p>
        </div>
    @endif
</div>

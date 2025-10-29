@extends('layouts.admin.app')

@section('title', translate('Order Details'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img src="{{ asset(config('app.asset_path') . '/admin/img/icons/all_orders.png') }}" alt="">
                {{ \App\CentralLogics\translate('order_details') }}
                <span class="badge badge-soft-dark rounded-50 fz-14">{{ $order->details->count() }}</span>
            </h2>
        </div>
        <div class="row" id="printableArea">
            <div class="col-lg-{{ $order->user_id == null ? 12 : 8 }} mb-3 mb-lg-0">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="mb-3 text-dark d-print-none">
                            <div class="row gy-3">
                                <div class="col-sm-6">
                                    <div class="d-flex flex-column justify-content-between h-100">
                                        <div class="d-flex flex-column gap-2">
                                            <h2 class="page-header-title">{{ \App\CentralLogics\translate('order') }}
                                                #{{ $order['id'] }}</h2>

                                            <div>

                                                <h4 class="page-header-title"> INV_NO - #
                                                    @if ($order->invoice_no)
                                                        {{ $order->invoice_no }}
                                                    @else
                                                        N/A
                                                    @endif

                                                </h4>


                                                {{-- @endif --}}


                                            </div>

                                            <div class="">
                                                <i class="tio-date-range"></i>
                                                {{ date('d M Y h:i a', strtotime($order['created_at'])) }}
                                            </div>
                                            @if ($order->amount_paid < $order->subtotal + $order->total_tax_amount)
                                                <h5 class="text-success m-0">
                                                    {{ \App\CentralLogics\translate('Amount Received') }} :
                                                    <label
                                                        class="">{{ Helpers::set_symbol($order->amount_paid) }}</label>
                                                    <i class="tio-add" id="add-icon" style="cursor: pointer;"></i>
                                                </h5>

                                                <form id="amount-form"
                                                    action="{{ route('admin.pos.orders.updateAmount', $order->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row mx-1">
                                                        <input type="text" class=" form-control col-10"
                                                            name="amount_received" id="amount-received"
                                                            placeholder="Enter amount" required>
                                                        <button type="submit" class="btn col-2 btn-outline-success">
                                                            <i class="tio-add"></i>
                                                        </button>
                                                    </div>
                                                </form>


                                                <h5 class="text-danger m-0">
                                                    {{ \App\CentralLogics\translate('Amount UnPaid') }} : <label
                                                        class=" ">
                                                        {{ Helpers::set_symbol($order->subtotal + $order->total_tax_amount - $order->amount_paid) }}
                                                    </label>
                                                </h5>
                                            @endif
                                        </div>

                                        {{-- @if ($order['order_type'] != 'pos') --}}

                                        {{-- @endif --}}

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex flex-column gap-2 align-items-sm-end">
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-primary" target="_blank" type="button"
                                                onclick="viewInvoice('{{ $order->id }}')">
                                                <i class="tio-print"></i>
                                                {{ \App\CentralLogics\translate('print_invoice') }}
                                            </button>
                                            {{-- <a class="btn btn-primary" target="_blank"
                                                href={{ route('admin.orders.generate-invoice', [$order['id']]) }}>
                                                <i class="tio-print"></i>
                                                {{ \App\CentralLogics\translate('print_invoice') }}
                                            </a> --}}
                                        </div>



                                        <div class="d-flex justify-content-sm-end gap-2">
                                            <div>{{ \App\CentralLogics\translate('payment_Method') }}:</div>
                                            <div>{{ str_replace('_', ' ', $order['payment_method']) }}</div>
                                        </div>

                                        <div class="d-flex justify-content-sm-end align-items-center gap-2">

                                            {{-- @if ($order['transaction_reference'] == null && $order['order_type'] != 'pos')
                                        <div>{{\App\CentralLogics\translate('reference_Code')}}:</div>
                                        <button class="btn btn-outline-primary btn-sm py-1" data-toggle="modal" data-target=".bd-example-modal-sm">
                                            {{\App\CentralLogics\translate('add')}}
                                        </button>
                                        @elseif($order['order_type']!='pos')
                                        <div>{{\App\CentralLogics\translate('reference_Code')}}:</div>
                                        <div>{{$order['transaction_reference']}}</div>
                                        @endif --}}
                                        </div>

                                        <div class="d-flex justify-content-sm-end align-items-center gap-2">
                                            <div>{{ \App\CentralLogics\translate('Payment_Status') }}:</div>
                                            @if (auth('admin')->user()->can('pos.order_approve'))

                                                <form
                                                    action="{{ route('admin.order.updatePaymentStatus', ['id' => $order['id']]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="payment_status" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value="paid" style="color: green;"
                                                            {{ $order['payment_status'] == 'paid' ? 'selected' : '' }}>
                                                            {{ \App\CentralLogics\translate('paid') }}
                                                        </option>
                                                        <option value="unpaid" style="color: red;"
                                                            {{ $order['payment_status'] == 'unpaid' ? 'selected' : '' }}>
                                                            {{ \App\CentralLogics\translate('partially_paid') }}
                                                        </option>
                                                    </select>
                                                </form>
                                            @else
                                                @if ($order['payment_status'] == 'unpaid' && $order->amount_paid <= 0)
                                                    <span class="text-danger text-capitalize">
                                                        {{ \App\CentralLogics\translate('unpaid') }}
                                                    </span>
                                                @elseif ($order['payment_status'] == 'partial' && $order->amount_paid < $order->subtotal + $order->total_tax_amount)
                                                    <span class="text-warning text-capitalize">
                                                        {{ \App\CentralLogics\translate('partial') }}
                                                    </span>
                                                @else
                                                    <span class="text-success text-capitalize">
                                                        {{ \App\CentralLogics\translate('paid') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-sm-end align-items-center gap-2">
                                            <div>{{ \App\CentralLogics\translate('Amount_paid') }}:</div>
                                            <span class="text-success">
                                                {{ $order->amount_paid }}
                                            </span>
                                        </div>


                                        {{-- <div class="d-flex justify-content-sm-end gap-2 mt-4">
                                        @if ($order['is_approved'])
                                          <h3 class="badge badge-soft-success " style="font-size: 20px"> Order Approved</span>
                                        @else
                                            @if (auth('admin')->user()->can('pos.order_approve'))

                                                <a class="btn btn-outline-info btn-md py-1" href="javascript:"
                                                    onclick="form_alert('order-{{$order['id']}}','{{\App\CentralLogics\translate('Want to Approve this Order ?')}}')">
                                                    {{\App\CentralLogics\translate('Approve')}}
                                                </a>
                                            @else
                                          <h3 class="badge badge-soft-danger " style="font-size: 20px">
                                            Order Not Approved
                                          </span>

                                            @endif

                                          <form
                                          id="order-{{$order['id']}}"
                                          action="{{route('admin.pos.approve_order',[$order['id']])}}" method="post">
                                            @csrf
                                             <input  name="is_approved" value="1" hidden/>
                                          </form>
                                        @endif
                                    </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php($item_amount = 0)
                        @php($sub_total = 0)
                        @php($total_tax = 0)
                        @php($total_dis_on_pro = 0)
                        @php($total_item_discount = 0)
                        <div class="invoice-border"></div>
                        <table class="table table-bordered mt-3 text-dark">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ \App\CentralLogics\translate('ID') }}</th>
                                    <th class="border-bottom-0">{{ \App\CentralLogics\translate('Name') }}</th>
                                    <th class="border-bottom-0">{{ \App\CentralLogics\translate('Batch') }}</th>
                                    {{-- <th class="border-bottom-0">{{\App\CentralLogics\translate('Exp.Date')}}</th> --}}
                                    <th class="border-bottom-0">{{ \App\CentralLogics\translate('Unit') }}</th>
                                    <th class="border-bottom-0">{{ \App\CentralLogics\translate('Qty') }}</th>
                                    <th class="border-bottom-0">{{ \App\CentralLogics\translate('Price') }}</th>
                                    <th class="border-bottom-0">{{ \App\CentralLogics\translate('Discount(tot)') }}</th>
                                    <th class="border-bottom-0">{{ \App\CentralLogics\translate('Tax(tot)') }}</th>
                                    <th class="border-bottom-0">{{ \App\CentralLogics\translate('Sub Total') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php($sub_total = 0)
                                @php($total_tax = 0)
                                @php($total_dis_on_pro = 0)
                                @foreach ($order->details as $index => $detail)
                                    <tr>
                                        <td class="">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="">
                                            {{ $detail->inventory ? ($detail->inventory->product ? $detail->inventory->product->name : 'product deleted') : 'inventory deleted' }}
                                        </td>


                                        <td class="">
                                            @if ($detail->inventory)
                                                {{ $detail->inventory->batch_number }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td class="">
                                            {{ $detail->inventory ? ($detail->inventory->product ? ($detail->inventory->product->unit ? $detail->inventory->product->unit->code : 'unit deleted') : 'product deleted') : 'inventory deleted' }}
                                        </td>

                                        <td class="">
                                            {{ $detail['quantity'] }}
                                        </td>

                                        <td class="">
                                            {{ Helpers::set_symbol($detail['price']) }}
                                        </td>

                                        <td class="">
                                            {{ Helpers::set_symbol($detail['discount_on_product']) }}
                                        </td>

                                        <td class="">
                                            {{ Helpers::set_symbol($detail['tax_amount']) }}
                                        </td>

                                        <td>
                                            @php($amount = $detail['price'] * $detail['quantity'] - $detail['discount_on_product'])
                                            {{ Helpers::set_symbol($amount) }}
                                        </td>
                                    </tr>
                                    @php($sub_total += $amount)
                                    @php($total_tax += $detail['tax_amount'])
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row pt-3 pb-2">
                            <div class="col-7">
                                <p style="display: flex; gap:10px;">Payment Method : - {{ $order->payment_method }} </p>

                                {{-- <p style="display: flex; gap:10px;">Amount In Words : {{\App\CentralLogics\translate( \Rmunate\Utilities\SpellNumber::value($order->order_amount)->locale('en')->currency('Birr')->fraction('cents')->toMoney()  )}} --}}
                            </div>

                            <div class="col-5">
                                <table class="table table-nowrap table-align-middle card-table">
                                    <tr>
                                        <th
                                            style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                            Sub Total</th>
                                        <td
                                            style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                            {{ Helpers::set_symbol($order->subtotal + $order['extra_discount']) }}</td>
                                    </tr>
                                    <tr>
                                        <th
                                            style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                            Total Discount</th>
                                        <td
                                            style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                            {{ Helpers::set_symbol($order->medicine_total_discount) }}</td>
                                    </tr>
                                    <tr>
                                        <th
                                            style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                            Tax</th>
                                        <td
                                            style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                            {{ Helpers::set_symbol($order->total_tax_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <th
                                            style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                            Grand Total</th>
                                        <td
                                            style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                            {{ Helpers::set_symbol($order->subtotal + $order->total_tax_amount) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div>
                            {{-- <p class="mt-3 mb-3"><strong>Note:</strong> Must be Paid within 60 days. Due Date: {{ date('M d, Y', strtotime('+60 days')) }}</p> --}}

                        </div>
                        <div class="invoice-border"></div>

                        <div class="mt-3">
                            <div class="text-right" style="display: flex; gap:10px;">
                                <strong>{{ \App\CentralLogics\translate('PREPARED BY ') }}:</strong>
                                <p>{{ auth('admin')->user()->f_name }} {{ auth('admin')->user()->l_name }} </p>
                            </div>

                            <div class="text-right" style="display: flex; gap:10px;">
                                <strong>{{ \App\CentralLogics\translate('APPROVED BY ') }}:</strong>
                                <p> __________________________ </p>
                            </div>

                            <div class="text-right" style="display: flex; gap:10px;">
                                <strong>{{ \App\CentralLogics\translate('CUSTOMER NAME AND SIGN ') }}:</strong>
                                <p> __________________________ </p>

                            </div>
                        </div>


                        <!-- End Row -->

                    </div>


                    <!-- End Body -->
                </div>
            </div>


            {{-- @if ($order->customer) --}}
            {{-- @if ($order->user_id != null) --}}
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-header-title"><i class="tio tio-user"></i>
                            {{ \App\CentralLogics\translate('Customer_Information') }}
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="media gap-3">
                            @if ($order->customer)
                                <div class="avatar-lg rounded-circle">
                                    <img class="img-fit rounded-circle"
                                        onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}'"
                                        src="{{ asset('public/storage/profile/' . $order->customer->image) }}"
                                        alt="Image Description">
                                </div>
                                <div class="media-body d-flex flex-column gap-1 text-dark">
                                    <div>{{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}</div>
                                    <div>{{ \App\Models\Order::where('user_id', $order['user_id'])->count() }}
                                        {{ translate('orders') }}
                                    </div>
                                    <a class="text-dark"
                                        href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                    <a class="text-dark"
                                        href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                                </div>
                            @elseif ($order->patient)
                                <div class="media-body d-flex flex-column gap-1 text-dark">
                                    <div>{{ $order->patient['full_name'] }}
                                        <span class="badge bg-soft-success">Patient (form prescreption)</span>
                                    </div>
                                    <a class="text-dark"
                                        href="tel:{{ $order->patient['phone'] }}">{{ $order->patient['phone'] }}</a>
                                    <a class="text-dark"
                                        href="mailto:{{ $order->patient['email'] }}">{{ $order->patient['email'] }}</a>
                                </div>
                            @else
                                <div class="media-body d-flex flex-column gap-1 text-dark">
                                    <div>Walk-In Customer</div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            {{-- @endif --}}
        </div>
        <!-- End Row -->
    </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="mySmallModalLabel">{{ \App\CentralLogics\translate('reference') }}
                        {{ \App\CentralLogics\translate('code') }} {{ \App\CentralLogics\translate('add') }}
                    </h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal"
                        aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.orders.add-payment-ref-code', [$order['id']]) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <!-- Input Group -->
                        <div class="form-group">
                            <input type="text" name="transaction_reference" class="form-control"
                                placeholder="EX : Code123" required>
                        </div>
                        <!-- End Input Group -->

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">{{ \App\CentralLogics\translate('submit') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End Modal -->

    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">
                        Invoice PDF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Empty iframe that will load the PDF when the modal opens -->
                    <iframe id="pdfIframe" width="100%" height="500px"></iframe>
                </div>
                <div class="modal-footer">
                    <!-- Button to download PDF -->
                    <a href="{{ route('admin.pos.download', $order->id) }}" class="btn btn-success">Download</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="shipping-address-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalTopCoverTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-top-cover bg-dark text-center">
                    <figure class="position-absolute right-0 bottom-0 left-0" style="margin-bottom: -1px;">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                            viewBox="0 0 1920 100.1">
                            <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z" />
                        </svg>
                    </figure>

                    <div class="modal-close">
                        <button type="button" class="btn btn-icon btn-sm btn-ghost-light" data-dismiss="modal"
                            aria-label="Close">
                            <svg width="16" height="16" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor"
                                    d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- End Header -->

                <div class="modal-top-cover-icon">
                    <span class="icon icon-lg icon-light icon-circle icon-centered shadow-soft">
                        <i class="tio-location-search"></i>
                    </span>
                </div>

                {{-- @php($address = \App\Model\CustomerAddress::find($order['delivery_address_id']))
                @if (isset($address))
                    <form action="{{ route('admin.order.update-shipping', [$order['delivery_address_id']]) }}"
                        method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ \App\CentralLogics\translate('type') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address_type"
                                        value="{{ $address['address_type'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ \App\CentralLogics\translate('contact') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_number"
                                        value="{{ $address['contact_person_number'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ \App\CentralLogics\translate('name') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_name"
                                        value="{{ $address['contact_person_name'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ \App\CentralLogics\translate('address') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address"
                                        value="{{ $address['address'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('road') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="road"
                                        value="{{ $address['road'] }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('house') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="house"
                                        value="{{ $address['house'] }}">
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('floor') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="floor"
                                        value="{{ $address['floor'] }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ \App\CentralLogics\translate('latitude') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="latitude"
                                        value="{{ $address['latitude'] }}" required>
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ \App\CentralLogics\translate('longitude') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="longitude"
                                        value="{{ $address['longitude'] }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white"
                                data-dismiss="modal">{{ \App\CentralLogics\translate('close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ \App\CentralLogics\translate('save') }}
                                {{ \App\CentralLogics\translate('changes') }}</button>
                        </div>
                    </form>
                @endif --}}
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@push('script_2')
    <script>
        document.getElementById('add-icon').addEventListener('click', function() {
            var form = document.getElementById('amount-form');
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });

        function viewInvoice(id) {
            // Construct the URL using the passed id parameter
            let pdfUrl = '{{ route('admin.pos.pdf', '') }}/' + id;
            $('#pdfIframe').attr('src', pdfUrl);
            $('#pdfModal').modal('show');
        }

        function addDeliveryMan(id) {
            $.ajax({
                type: "GET",
                url: '{{ url(' / ') }}/admin/orders/add-delivery-man/' + id,
                data: $('#product_form').serialize(),
                success: function(data) {
                    if (data.status == true) {
                        toastr.success('{{ translate('Delivery man successfully assigned/changed') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.error(
                            '{{ translate('Deliveryman man can not assign/change in that status') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                    }
                },
                error: function() {
                    toastr.error('{{ translate('Add valid data') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function last_location_view() {
            toastr.warning('{{ translate('Only available when order is out for delivery!') }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endpush

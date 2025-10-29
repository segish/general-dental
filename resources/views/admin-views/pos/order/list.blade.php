@extends('layouts.admin.app')

@section('title', translate('Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img src="{{ asset(config('app.asset_path') . '/admin/img/icons/all_orders.png') }}" alt="">
                {{ \App\CentralLogics\translate('pos_orders') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $orders->total() }}</span>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="#" id="form-data" method="GET">
                        <div class="row align-items-end gy-3 gx-2">
                            <div class="col-12 pb-0">
                                <h4>{{ \App\CentralLogics\translate('Select_Date_Range') }}</h4>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="">
                                    <label for="form_date">{{ \App\CentralLogics\translate('Start_Date') }}</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ $start_date }}"
                                        class="js-flatpickr form-control flatpickr-custom min-h-40px" placeholder="yy-mm-dd"
                                        data-hs-flatpickr-options='{ "dateFormat": "Y-m-d"}'>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mt-2 mt-sm-0">
                                <div class="">
                                    <label for="to_date">{{ \App\CentralLogics\translate('End_date') }}</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ $end_date }}"
                                        class="js-flatpickr form-control flatpickr-custom min-h-40px" placeholder="yy-mm-dd"
                                        data-hs-flatpickr-options='{ "dateFormat": "Y-m-d"}'>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mt-2 mt-sm-0 __btn-row">
                                <a href="{{ route('admin.pos.orders') }}" id=""
                                    class="btn w-100 btn--reset min-h-45px">{{ translate('clear') }}</a>
                                <button type="submit"
                                    class="btn btn-primary btn-block">{{ \App\CentralLogics\translate('Show_Data') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Header -->
            <div class="p-3">
                <div class="row justify-content-between align-items-center gy-2">
                    <div class="col-12">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('Search by ...') }}" aria-label="Search"
                                    value="{{ $search }}" autocomplete="off">
                                <select name="payment_status" id="payment_status" class="form-control">
                                    <option value="" {{ $payment_status == '' ? 'selected' : '' }}>
                                        {{ \App\CentralLogics\translate('--Payment Status--') }}</option>
                                    <option value="paid" {{ $payment_status == 'paid' ? 'selected' : '' }}>
                                        {{ \App\CentralLogics\translate('paid_orders') }}</option>
                                    <option value="unpaid" {{ $payment_status == 'unpaid' ? 'selected' : '' }}>
                                        {{ \App\CentralLogics\translate('unpaid_orders') }}</option>
                                </select>

                                <select name="payment_method" id="payment_method" class="form-control">
                                    <option value="" {{ $payment_method == '' ? 'selected' : '' }}>
                                        {{ \App\CentralLogics\translate('--Payment Method--') }}</option>
                                    <option value="cash" {{ $payment_method == 'cash' ? 'selected' : '' }}>
                                        {{ \App\CentralLogics\translate('cash') }}</option>
                                    <option value="credit" {{ $payment_method == 'credit' ? 'selected' : '' }}>
                                        {{ \App\CentralLogics\translate('credit') }}</option>
                                </select>

                                <div>
                                    <select name="sale_id" id="sale_id" class="form-control js-select2-custom">
                                        <option value="" {{ $sale_id == '' ? 'selected' : '' }}>
                                            {{ \App\CentralLogics\translate('--Filter By Sales--') }}</option>
                                        @foreach ($sales as $sale)
                                            <option value="{{ $sale->id }}"
                                                {{ $sale->id == old('sale', request('sale')) ? 'selected' : '' }}>
                                                {{ $sale->f_name }} {{ $sale->l_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group-append">
                                    <button type="submit"
                                        class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                        {{-- <div>
                            <button type="button" class="btn btn-outline-primary" data-toggle="dropdown" aria-expanded="false">
                                <i class="tio-download-to"></i>
                                Export
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right w-auto">
                                <li>
                                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                       href="{{route('admin.pos.orders.export', ['branch_id'=>Request::get('branch_id'), 'start_date'=>Request::get('start_date'), 'end_date'=>Request::get('end_date'), 'search'=>Request::get('search')])}}">
                                        <img width="14" src="{{asset(config('app.asset_path') . '/admin/img/icons/excel.png')}}" alt="">
                                        {{\App\CentralLogics\translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-dark">
                    <thead class="thead-light">
                        <tr>
                            {{-- <th>{{ \App\CentralLogics\translate('SL') }}</th> --}}
                            <th>{{ \App\CentralLogics\translate('order_ID') }}</th>
                            <th>{{ \App\CentralLogics\translate('Order_date') }}</th>
                            <th>{{ \App\CentralLogics\translate('customer_info') }}</th>
                            <th>{{ \App\CentralLogics\translate('sales') }}</th>
                            <th>{{ \App\CentralLogics\translate('Products') }}</th>
                            <th>{{ \App\CentralLogics\translate('total_amount') }}</th>
                            <th>{{ \App\CentralLogics\translate('Status') }}</th>
                            <th class="text-center">{{ \App\CentralLogics\translate('actions') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach ($orders as $key => $order)
                            <tr class="status-{{ $order['order_status'] }} class-all">
                                {{-- <td>{{ $key + $orders->firstItem() }}</td> --}}
                                <td>
                                    <a class="text-dark"
                                        href="{{ route('admin.pos.order-details', ['id' => $order['id']]) }}">{{ $order['id'] }}</a>
                                </td>
                                <td>
                                    <div>{{ date('d M Y', strtotime($order['created_at'])) }}</div>
                                    <div class="fs-12">{{ date('h:i A', strtotime($order['created_at'])) }}</div>
                                </td>
                                <td>
                                    @if ($order->customer)
                                        <a class="text-dark text-capitalize"
                                            href="{{ route('admin.customer.view', [$order['user_id']]) }}">
                                            <h6 class="mb-0">
                                                {{ $order->customer['fullname'] }}</h6>
                                        </a>
                                        <small class="badge-soft-success badge">{{ $order->buyer_type }}</small>
                                    @elseif($order->patient)
                                        <a class="text-dark text-capitalize"
                                            href="{{ route('admin.patient.view', [$order['patient_id']]) }}">
                                            <h6 class="mb-0">
                                                {{ $order->patient['full_name'] }}</h6>
                                        </a>
                                        <small class="badge-soft-success badge">{{ $order->buyer_type }}</small>
                                        {{-- @elseif($order->user_id != null && !isset($order->customer))
                                        <h6 class="text-muted text-capitalize">
                                            {{ \App\CentralLogics\translate('customer_deleted') }}</h6>
                                        <small class="badge-soft-success badge">{{ $order->buyer_type }}</small> --}}
                                    @else
                                        <h6 class="text-muted text-capitalize">
                                            {{ \App\CentralLogics\translate('walk_in_customer') }}</h6>
                                        <small class="badge-soft-success badge">{{ $order->buyer_type }}</small>
                                    @endif
                                </td>

                                <td>
                                    <label>
                                        @if ($order->user)
                                            {{ $order->user->f_name . ' ' . $order->user->l_name }}
                                        @else
                                            ---
                                        @endif
                                    </label>
                                </td>

                                <td>
                                    <label>
                                        @foreach ($order->details as $detail)
                                            <li>
                                                {{ $detail->inventory ? ($detail->inventory->product ? $detail->inventory->product->name : 'product deleted') : 'inventory deleted' }}
                                                ({{ $detail->quantity }})
                                            </li>
                                        @endforeach
                                    </label>
                                </td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <div style="display: flex; font-weight: bolder; flex-direction: column">
                                            <div>
                                                <span class="text-success">
                                                    {{ Helpers::set_symbol($order['total']) }} </span>
                                            </div>
                                            {{-- <span class="text-success">{{ \App\CentralLogics\translate('paid') }}</span> --}}
                                        @else
                                            <div style="display: flex; font-weight: bolder; flex-direction: column">
                                                <div>

                                                    {{ Helpers::set_symbol($order['total']) }} -
                                                    <span class="text-success">
                                                        {{ Helpers::set_symbol($order['amount_paid']) }} </span>
                                                    =
                                                    <span class="text-danger">
                                                        {{ Helpers::set_symbol($order['total'] - $order['amount_paid']) }}
                                                    </span>
                                                </div>

                                                {{-- <span class="text-danger">{{\App\CentralLogics\translate('unpaid')}}</span> --}}

                                                {{-- @if ($order->credit_end_date != null)
                                                <span class="text-danger  ">
                                                    ({{ \Carbon\Carbon::parse($order->credit_end_date)->format('M d, Y') }})</span>
                                            @endif --}}
                                            </div>
                                    @endif
                                </td>
                                <td class="text-capitalize">
                                    <span
                                        class="badge badge-soft-{{ $order->payment_status == 'unpaid' ? 'danger' : ($order->payment_status == 'partial' ? 'warning' : 'success') }}">
                                        {{ \App\CentralLogics\translate($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="btn btn-outline-primary square-btn"
                                            href="{{ route('admin.pos.order-details', ['id' => $order['id']]) }}">
                                            <i class="tio-visible"></i>
                                        </a>
                                        @if (auth('admin')->user()->hasRole('Super Admin'))
                                            <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                onclick="form_alert('order-{{ $order['id'] }}','{{ \App\CentralLogics\translate('Want to delete this Order ?') }}')"><i
                                                    class="tio tio-delete"></i></a>
                                            <form action="{{ route('admin.pos.delete', [$order['id']]) }}" method="post"
                                                id="order-{{ $order['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        @endif


                                        <button class="btn btn-outline-info square-btn" target="_blank" type="button"
                                            onclick="viewInvoice('{{ $order->id }}')"><i class="tio-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    <!-- Pagination -->
                    {!! $orders->links() !!}
                </div>
            </div>
            @if (count($orders) == 0)
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

    <div class="modal fade" id="print-invoice" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ \App\CentralLogics\translate('print_invoice') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 justify-content-center">
                        <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                            value="Proceed, If thermal printer is ready." />
                        <a href="{{ url()->previous() }}"
                            class="btn btn-danger non-printable">{{ translate('Back') }}</a>
                    </div>
                    <hr class="non-printable">
                    <div class="row" id="printableArea">

                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <a href="#" id="downloadPdfBtn" class="btn btn-success">Download</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        function viewInvoice(id) {
            // Construct the URL using the passed id parameter
            let pdfUrl = '{{ route('admin.pos.pdf', '') }}/' + id;
            $('#pdfIframe').attr('src', pdfUrl);
            // Update download button href with the current order ID
            $('#downloadPdfBtn').attr('href', '{{ route('admin.pos.download', '') }}/' + id);
            $('#pdfModal').modal('show');
        }
    </script>


    <script>
        function print_invoice(order_id) {
            $.get({
                url: '{{ url('/') }}/admin/pos/invoice/' + order_id,
                dataType: 'json',
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log("success...")
                    $('#print-invoice').modal('show');
                    $('#printableArea').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            location.reload();
        }
    </script>
@endpush

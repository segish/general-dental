@extends('layouts.admin.app')

@section('title', translate('Product Performance Report'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @php
        $currency_code = \App\Models\BusinessSetting::where('key', 'currency')->first();
        $currency_symbol = \App\Models\Currency::where('currency_code', $currency_code->value)->first();
    @endphp
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/product.png') }}"
                    alt="">
                {{ translate('Product Performance Report') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-8 col-sm-8 col-md-6">
                                <form id="filterForm" class="d-flex gap-2">
                                    <div class="input-group">
                                        <input type="date" id="start_date" name="start_date" class="form-control">
                                    </div>
                                    <div class="input-group">
                                        <input type="date" id="end_date" name="end_date" class="form-control">
                                    </div>
                                    <div class="input-group">
                                        <select class="form-control" id="limit" name="limit">
                                            <option value="5">Top 5</option>
                                            <option value="10" selected>Top 10</option>
                                            <option value="20">Top 20</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        {{ translate('Filter') }}
                                    </button>
                                </form>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-success" id="downloadExcel">
                                        <i class="tio-download"></i>
                                        {{ translate('Download Excel') }}
                                    </a>
                                    <a href="#" class="btn btn-danger" id="downloadPdf">
                                        <i class="tio-download"></i>
                                        {{ translate('Download PDF') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row p-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                        <img width="20"
                                            src="{{ asset(config('app.asset_path') . '/admin/img/icons/top-selling.png') }}"
                                            alt="">
                                        {{ translate('Top Selling Products') }}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-thead-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{ translate('SL') }}</th>
                                                    <th>{{ translate('Product Name') }}</th>
                                                    <th>{{ translate('Total Quantity') }}</th>
                                                    <th>{{ translate('Total Revenue') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="topSellingProducts">
                                                <!-- Data will be loaded here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                        <img width="20"
                                            src="{{ asset(config('app.asset_path') . '/admin/img/icons/low-selling.png') }}"
                                            alt="">
                                        {{ translate('Low Selling Products') }}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-thead-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{ translate('SL') }}</th>
                                                    <th>{{ translate('Product Name') }}</th>
                                                    <th>{{ translate('Total Quantity') }}</th>
                                                    <th>{{ translate('Total Revenue') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lowSellingProducts">
                                                <!-- Data will be loaded here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function setSymbol(amount) {
            const setting = @json(\App\Models\BusinessSetting::where('key', 'currency_symbol_position')->first());
            const currency_code = @json($currency_code);
            const currency_symbol = @json($currency_symbol);

            const position = setting && setting.value ? setting.value : 'right';
            const formattedAmount = Number(amount).toFixed(2);
            const symbol = currency_symbol.currency_symbol;

            if (position === 'left') {
                return symbol + formattedAmount;
            } else {
                return formattedAmount + symbol;
            }
        }
        $(document).ready(function() {
            // Set default dates
            var today = new Date();
            var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            $('#start_date').val(firstDay.toISOString().split('T')[0]);
            $('#end_date').val(lastDay.toISOString().split('T')[0]);

            function loadData() {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var limit = $('#limit').val();

                // Load top selling products
                $.ajax({
                    url: '{{ route('admin.pharmacy-reports.top-selling-products') }}',
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        limit: limit
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(response) {
                        if (response.success) {
                            var html = '';
                            response.data.forEach(function(product, index) {
                                html += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${product.name}</td>
                                        <td>${product.total_quantity}</td>
                                        <td>${setSymbol(product.total_revenue)}</td>
                                    </tr>
                                `;
                            });
                            $('#topSellingProducts').html(html);
                        }
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });

                // Load low selling products
                $.ajax({
                    url: '{{ route('admin.pharmacy-reports.low-selling-products') }}',
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        limit: limit
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(response) {
                        if (response.success) {
                            var html = '';
                            response.data.forEach(function(product, index) {
                                html += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${product.name}</td>
                                        <td>${product.total_quantity}</td>
                                        <td>${setSymbol(product.total_revenue)}</td>
                                    </tr>
                                `;
                            });
                            $('#lowSellingProducts').html(html);
                        }
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            }

            // Load data on page load
            loadData();

            // Load data when form is submitted
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                loadData();
            });

            // Download Excel
            $('#downloadExcel').on('click', function(e) {
                e.preventDefault();
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var limit = $('#limit').val();
                window.location.href =
                    `{{ route('admin.pharmacy-reports.product-performance.excel') }}?start_date=${startDate}&end_date=${endDate}&limit=${limit}`;
            });

            // Download PDF
            $('#downloadPdf').on('click', function(e) {
                e.preventDefault();
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var limit = $('#limit').val();
                window.location.href =
                    `{{ route('admin.pharmacy-reports.product-performance.pdf') }}?start_date=${startDate}&end_date=${endDate}&limit=${limit}`;
            });
        });
    </script>
@endpush

@extends('layouts.admin.app')

@section('title', translate('Test Report'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/css/datepicker.css">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/order_report.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('Test Report') }}
            </h2>
        </div>

        <!-- Date Filter -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('admin.reports.test') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label for="from_date" class="form-label">{{ translate('From Date') }}</label>
                            <input type="date" class="form-control" id="from_date" name="from_date"
                                value="{{ request('from_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-sm-4">
                            <label for="to_date" class="form-label">{{ translate('To Date') }}</label>
                            <input type="date" class="form-control" id="to_date" name="to_date"
                                value="{{ request('to_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-sm-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                        </div>
                    </div>

                    <!-- Quick Filter Buttons -->
                    <div class="row mt-3 justify-content-between">
                        <div class="col-8">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.reports.test', ['period' => 'today']) }}"
                                    class="btn btn-outline-primary {{ request('period') == 'today' ? 'active' : '' }}">
                                    {{ translate('Today') }}
                                </a>
                                <a href="{{ route('admin.reports.test', ['period' => 'this_week']) }}"
                                    class="btn btn-outline-primary {{ request('period') == 'this_week' ? 'active' : '' }}">
                                    {{ translate('This Week') }}
                                </a>
                                <a href="{{ route('admin.reports.test', ['period' => 'this_month']) }}"
                                    class="btn btn-outline-primary {{ request('period') == 'this_month' ? 'active' : '' }}">
                                    {{ translate('This Month') }}
                                </a>
                                <a href="{{ route('admin.reports.test', ['period' => 'this_year']) }}"
                                    class="btn btn-outline-primary {{ request('period') == 'this_year' ? 'active' : '' }}">
                                    {{ translate('This Year') }}
                                </a>
                                <a href="{{ route('admin.reports.test') }}" class="btn btn-outline-secondary">
                                    {{ translate('Reset') }}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.reports.test', array_merge(request()->query(), ['download' => 'excel'])) }}"
                                class="btn btn-success d-flex align-items-center px-2">
                                <img src="{{ asset(config('app.asset_path') . '/admin/img/icons/excel.png') }}"
                                    alt="Excel" style="height: 24px; width: auto;">
                            </a>
                            <a href="{{ route('admin.reports.test', array_merge(request()->query(), ['download' => 'pdf'])) }}"
                                class="btn btn-danger d-flex align-items-center px-2">
                                <img src="{{ asset(config('app.asset_path') . '/admin/img/icons/pdf.png') }}"
                                    alt="PDF" style="height: 24px; width: auto;">
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card -->
        <div class="card mb-3">
            <div class="row g-2" id="order_stats">

                <div class="col-sm-3">
                    <!-- Card -->
                    <div class="dashboard--card">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Total Tests') }}</h5>
                        <h2 class="dashboard--card__title">
                            @if (request('from_date') && request('to_date'))
                                {{ $totalTests['filtered'] }}
                            @else
                                {{ $totalTests['this_year'] }}
                            @endif
                        </h2>
                        <img width="30" src="{{ asset(config('app.asset_path') . '/admin/img/icons/all_orders.png') }}"
                            class="dashboard--card__img" alt="pending">
                    </div>
                    <!-- End Card -->
                </div>

                <div class="col-sm-3">
                    <!-- Card -->
                    <div class="dashboard--card">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Completed') }}</h5>
                        <h2 class="dashboard--card__title">
                            @if (request('from_date') && request('to_date'))
                                {{ $statusCompleted['filtered'] }}
                            @else
                                {{ $statusCompleted['this_year'] }}
                            @endif
                        </h2>
                        <img width="30" src="{{ asset(config('app.asset_path') . '/admin/img/icons/confirmed.png') }}"
                            class="dashboard--card__img" alt="pending">
                    </div>
                    <!-- End Card -->
                </div>

                <div class="col-sm-3">
                    <!-- Card -->
                    <div class="dashboard--card">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Rejected') }}</h5>
                        <h2 class="dashboard--card__title">
                            @if (request('from_date') && request('to_date'))
                                {{ $statusRejected['filtered'] }}
                            @else
                                {{ $statusRejected['this_year'] }}
                            @endif
                        </h2>
                        <img width="30" src="{{ asset(config('app.asset_path') . '/admin/img/icons/canceled.png') }}"
                            class="dashboard--card__img" alt="confirmed">
                    </div>
                    <!-- End Card -->
                </div>

                <div class="col-sm-3">
                    <!-- Card -->
                    <div class="dashboard--card">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Pending') }}</h5>
                        <h2 class="dashboard--card__title">
                            @if (request('from_date') && request('to_date'))
                                {{ $statusPending['filtered'] }}
                            @else
                                {{ $statusPending['this_year'] }}
                            @endif
                        </h2>
                        <img width="30" src="{{ asset(config('app.asset_path') . '/admin/img/icons/pending.png') }}"
                            class="dashboard--card__img" alt="pending">
                    </div>
                    <!-- End Card -->
                </div>
            </div>
        </div>
        <!-- End Card -->

        <div class="row g-2">
            <div class="col-12">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img width="20"
                                src="{{ asset(config('app.asset_path') . '/admin/img/icons/business_overview.png') }}"
                                alt="Tests By Type">
                            {{ translate('Tests By Type') }}
                            @if (request('from_date') && request('to_date'))
                                <span class="badge bg-primary ms-2">
                                    {{ \Carbon\Carbon::parse(request('from_date'))->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse(request('to_date'))->format('d M Y') }}
                                </span>
                            @elseif(request('period'))
                                <span class="badge bg-primary ms-2">
                                    @if (request('period') == 'today')
                                        {{ translate('Today') }}
                                    @elseif(request('period') == 'this_week')
                                        {{ translate('This Week') }}
                                    @elseif(request('period') == 'this_month')
                                        {{ translate('This Month') }}
                                    @elseif(request('period') == 'this_year')
                                        {{ translate('This Year') }}
                                    @endif
                                </span>
                            @else
                                <span class="badge bg-primary ms-2">{{ translate('This Year') }}</span>
                            @endif
                        </h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body" id="Tests By Type">
                        <!-- Chart -->
                        <div class="chartjs-custom position-relative h-400">
                            <canvas id="doughnutChart"></canvas>
                        </div>
                        <!-- End Chart -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/vendor/chart.js.extensions/chartjs-extensions.js">
    </script>
    <script
        src="{{ asset(config('app.asset_path') . '/admin') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
@endpush
@push('script_2')
    <script>
        // Convert the data to JSON for JavaScript
        const testData = @json($testsByTypeToday);
        const labels = testData.map(item => item.test_type); // Extract test names
        const data = testData.map(item => item.count); // Extract counts

        const ctx = document.getElementById('doughnutChart').getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels, // Dynamic labels
                datasets: [{
                    data: data, // Dynamic data
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                    ], // Add more colors if needed
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                "legend": {
                    "display": true,
                    "position": "bottom",
                    "align": "center",
                    "labels": {
                        "fontColor": "#758590",
                        "fontSize": 14,
                        padding: 20
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                }
            }
        });
    </script>

    <script>
        $('#from_date,#to_date').change(function() {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        });
    </script>
@endpush

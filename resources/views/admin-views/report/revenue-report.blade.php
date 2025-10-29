@extends('layouts.admin.app')

@section('title', translate('Revenue Report'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')

    <div class="container py-4">
        <h2 class="mb-4">Revenue Report ({{ $startDate->toDateString() }} - {{ $endDate->toDateString() }})</h2>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <!-- Filter Form (Left) -->
            <form method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-auto">
                        <input type="date" name="start_date" class="form-control"
                            value="{{ request('start_date', $startDate->toDateString()) }}">
                    </div>
                    <div class="col-auto">
                        <input type="date" name="end_date" class="form-control"
                            value="{{ request('end_date', $endDate->toDateString()) }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <!-- Download Buttons (Right) -->
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.revenue.excel', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                    class="btn btn-success d-flex align-items-center px-2">
                    <img src="{{ asset(config('app.asset_path') . '/admin/img/icons/excel.png') }}" alt="Excel"
                        style="height: 24px; width: auto;">
                </a>
                <a href="{{ route('admin.reports.revenue.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                    class="btn btn-danger d-flex align-items-center px-2">
                    <img src="{{ asset(config('app.asset_path') . '/admin/img/icons/pdf.png') }}" alt="PDF"
                        style="height: 24px; width: auto;">
                </a>

            </div>
        </div>
        <!-- Card -->
        <div class="card mb-3">
            <div class="row g-2" id="order_stats">

                <div class="col-sm-3">
                    <!-- Card -->
                    <div class="dashboard--card">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Todays Total Bill') }}</h5>
                        <h2 class="dashboard--card__title">
                            {{ $total_bills }}
                        </h2>
                        <img width="30" src="{{ asset(config('app.asset_path') . '/admin/img/icons/all_orders.png') }}"
                            class="dashboard--card__img" alt="pending">
                    </div>
                    <!-- End Card -->
                </div>

                <div class="col-sm-3">
                    <!-- Card -->
                    <div class="dashboard--card">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Total Paid') }}</h5>
                        <h2 class="dashboard--card__title">
                            {{ $total_paid }}
                        </h2>
                        <img width="30" src="{{ asset(config('app.asset_path') . '/admin/img/icons/confirmed.png') }}"
                            class="dashboard--card__img" alt="pending">
                    </div>
                    <!-- End Card -->
                </div>

                <div class="col-sm-3">
                    <!-- Card -->
                    <div class="dashboard--card">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Total Unpaid') }}</h5>
                        <h2 class="dashboard--card__title">
                            {{ $total_unpaid }}
                        </h2>
                        <img width="30" src="{{ asset(config('app.asset_path') . '/admin/img/icons/canceled.png') }}"
                            class="dashboard--card__img" alt="confirmed">
                    </div>
                    <!-- End Card -->
                </div>

                <div class="col-sm-3">
                    <!-- Card -->
                    <div class="dashboard--card">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Partial Paid') }}</h5>
                        <h2 class="dashboard--card__title">
                            {{ $partial_paid }}
                            <img width="30"
                                src="{{ asset(config('app.asset_path') . '/admin/img/icons/pending.png') }}"
                                class="dashboard--card__img" alt="pending">
                    </div>
                    <!-- End Card -->
                </div>
            </div>
        </div>
        <!-- End Card -->

        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                            Revenue Overview (Total, Paid & Outstanding)
                                        </h4>
                                    </div>
                                    <div class="card-body" id="Tests By Type">
                                        <div class="chartjs-custom position-relative h-150">
                                            <canvas id="revenueChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                            Service-Wise Billing Breakdown
                                        </h4>
                                    </div>
                                    <div class="card-body" id="Tests By Type">
                                        <div class="chartjs-custom position-relative h-150">
                                            <canvas id="serviceRevenueChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (auth('admin')->user()->can('dashboard.earning-statistics'))
            <div class="card mb-3">
                <!-- Body -->
                <div class="card-body">
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-md-6">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                <img width="20"
                                    src="{{ asset(config('app.asset_path') . '/admin/img/icons/earning_statictics.png') }}"
                                    alt="Earning Statistics">
                                {{ translate('Weekly, Monthly & Yearly Earnings Breakdown') }}
                            </h4>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <ul class="option-select-btn mb-0">
                                <li>
                                    <label>
                                        <input type="radio" name="statistics2" hidden checked>
                                        <span data-earn-type="yearEarn" onclick="earningStatisticsUpdate('yearEarn')">This
                                            Year</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="statistics2" hidden="">
                                        <span data-earn-type="MonthEarn" onclick="earningStatisticsUpdate('MonthEarn')">This
                                            Month</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="statistics2" hidden="">
                                        <span data-earn-type="WeekEarn" onclick="earningStatisticsUpdate('WeekEarn')">This
                                            Week</span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <!-- End Row -->

                    <!-- Bar Chart -->
                    <div class="chartjs-custom" id="set-new-graph" style="height: 20rem">
                        <canvas id="updatingData"
                            data-hs-chartjs-options='{
                    "type": "bar",
                    "data": {
                        "labels": ["Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                        "datasets": [
                        {
                            "data": [{{ $earning[1] }},{{ $earning[2] }},{{ $earning[3] }},{{ $earning[4] }},{{ $earning[5] }},{{ $earning[6] }},{{ $earning[7] }},{{ $earning[8] }},{{ $earning[9] }},{{ $earning[10] }},{{ $earning[11] }},{{ $earning[12] }}],
                            "backgroundColor": "#673ab7",
                            "borderColor": "#673ab7"
                        }
                        ]
                    },
                    "options": {
                        "legend": {
                            "display": false,
                            "position": "top",
                            "align": "center",
                            "labels": {
                                "fontColor": "#758590",
                                "fontSize": 14
                            }
                        },
                        "scales": {
                            "yAxes": [{
                                "gridLines": {
                                    "color": "rgba(180, 208, 224, 0.3)",
                                    "borderDash": [8, 4],
                                    "drawBorder": false,
                                    "zeroLineColor": "rgba(180, 208, 224, 0.3)"
                                },
                                "ticks": {
                                    "beginAtZero": true,
                                    "fontSize": 12,
                                    "fontColor": "#5B6777",
                                    "padding": 10,
                                    "postfix": "{{ Helpers::currency_symbol() }}"
                                }
                            }],
                            "xAxes": [{
                                "gridLines": {
                                    "color": "rgba(180, 208, 224, 0.3)",
                                    "display": true,
                                    "drawBorder": true,
                                    "zeroLineColor": "rgba(180, 208, 224, 0.3)"
                                },
                                "ticks": {
                                    "fontSize": 12,
                                    "fontColor": "#5B6777",
                                    "fontFamily": "Open Sans, sans-serif",
                                    "padding": 5
                                },
                                "categoryPercentage": 0.5,
                                "maxBarThickness": "7"
                            }]
                        },
                        "cornerRadius": 3,
                        "tooltips": {
                            "prefix": " ",
                            "hasIndicator": true,
                            "mode": "index",
                            "intersect": false
                        },
                        "hover": {
                            "mode": "nearest",
                            "intersect": true
                        }
                    }
                    }'></canvas>
                    </div>
                    <!-- End Bar Chart -->
                </div>
                <!-- End Body -->
            </div>
        @endif
        <div class="card mb-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Revenue</th>
                        <th>Total Paid</th>
                        <th>Outstanding</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenues as $revenue)
                        <tr>
                            <td>{{ $revenue->date }}</td>
                            <td>{{ number_format($revenue->total_revenue, 2) }}</td>
                            <td>{{ number_format($revenue->total_paid, 2) }}</td>
                            <td>{{ number_format($revenue->outstanding, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
        const ctx = document.getElementById('revenueChart').getContext('2d');

        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                        label: 'Total Revenue',
                        data: chartData.total_revenue,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    },
                    {
                        label: 'Total Paid',
                        data: chartData.total_paid,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    },
                    {
                        label: 'Outstanding',
                        data: chartData.outstanding,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        const cty = document.getElementById('serviceRevenueChart').getContext('2d');

        new Chart(cty, {
            type: 'doughnut', // Changed from 'pie' to 'doughnut'
            data: {
                labels: {!! $revenueByService->pluck('service_type') !!},
                datasets: [{
                    data: {!! $revenueByService->pluck('total_revenue') !!},
                    backgroundColor: ['#36A2EB', '#4BC0C0', '#FF6384', '#FFCE56', '#9966FF'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    <script>
        function earningStatisticsUpdate(t) {
            console.log('EARNING UPDATE HERE', t)
            let value = t;
            // let value = $(t).attr('data-earn-type');
            $.ajax({
                url: '{{ route('admin.dashboard.earning-statistics') }}',
                type: 'GET',
                data: {
                    type: value
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(response_data) {
                    document.getElementById("updatingData").remove();
                    let graph = document.createElement('canvas');
                    graph.setAttribute("id", "updatingData");
                    document.getElementById("set-new-graph").appendChild(graph);
                    var ctx = document.getElementById("updatingData").getContext("2d");

                    let options = {
                        responsive: true,
                        bezierCurve: false,
                        maintainAspectRatio: false,
                        legend: {
                            display: false,
                            position: "top",
                            align: "center",
                            labels: {
                                fontColor: "#758590",
                                fontSize: 14
                            }
                        },
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    color: "rgba(180, 208, 224, 0.3)",
                                    borderDash: [8, 4],
                                    drawBorder: false,
                                    zeroLineColor: "rgba(180, 208, 224, 0.3)"
                                },
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 12,
                                    fontColor: "#5B6777",
                                    padding: 10,
                                    postfix: "{{ Helpers::currency_symbol() }}"
                                }
                            }],
                            xAxes: [{
                                gridLines: {
                                    color: "rgba(180, 208, 224, 0.3)",
                                    display: true,
                                    drawBorder: true,
                                    zeroLineColor: "rgba(180, 208, 224, 0.3)"
                                },
                                ticks: {
                                    fontSize: 12,
                                    fontColor: "#5B6777",
                                    fontFamily: "Open Sans, sans-serif",
                                    padding: 5
                                },
                                categoryPercentage: 0.5,
                                maxBarThickness: "7"
                            }]
                        },
                        cornerRadius: 3,
                        tooltips: {
                            prefix: " ",
                            hasIndicator: true,
                            mode: "index",
                            intersect: false
                        },
                        hover: {
                            mode: "nearest",
                            intersect: true
                        }
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [],
                            datasets: [{
                                label: "{{ translate('Earning') }}",
                                data: [],
                                backgroundColor: "#673ab7",
                                borderColor: "#673ab7"
                            }]
                        },
                        options: options
                    });
                    myChart.data.labels = response_data.earning_label;
                    myChart.data.datasets[0].data = response_data.earning;
                    myChart.update();
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        $(document).ready(function() {
            earningStatisticsUpdate('yearEarn');
        });
    </script>
@endpush

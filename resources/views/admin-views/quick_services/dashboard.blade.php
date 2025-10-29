@extends('layouts.admin.app')

@section('title', translate('Dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="">
            <h2 class="mb-1 text--primary">{{ \App\CentralLogics\translate('welcome') }},
                {{ auth('admin')->user()->f_name }}.</h2>
            <p class="text-dark fs-12">{{ \App\CentralLogics\translate('welcome') }}
                {{ \App\CentralLogics\translate('admin') }},
                {{ \App\CentralLogics\translate('_here_is_your_business_statistics') }}.</p>


        </div>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-auto">
                        <h4 class="d-flex align-items-center gap-10 mb-0">
                            <img width="20"
                                src="{{ asset(config('app.asset_path') . '/admin/img/icons/business_analytics.png') }}"
                                alt="Business Analytics">
                            {{ translate('Nurse Report') }}
                        </h4>
                    </div>
                    {{-- <div class="col-auto">
                        <select class="custom-select mn-w200" name="statistics_type" onchange="order_stats_update(this.value)">
                            <option value="overall" {{session()->has('statistics_type') && session('statistics_type') == 'overall'?'selected':''}}>
                                {{ translate('Overall Statistics') }}
                            </option>
                            <option value="today" {{session()->has('statistics_type') && session('statistics_type') == 'today'?'selected':''}}>
                                {{ translate("Today's Statistics") }}
                            </option>
                            <option value="this_month" {{session()->has('statistics_type') && session('statistics_type') == 'this_month'?'selected':''}}>
                                {{ translate("This Month's Statistics") }}
                            </option>
                        </select>
                    </div> --}}
                </div>
                <div class="row g-2" id="order_stats">
                    <div class="col-sm-6">
                        <!-- Card -->
                        <a class="dashboard--card" href="{{ route('admin.patient.list') }}">
                            <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Your_Patients') }}</h5>
                            <h2 class="dashboard--card__title">{{ $patient_count }}</h2>
                            <img width="30"
                                src="{{ asset(config('app.asset_path') . '/admin/img/icons/pending.png') }}"
                                class="dashboard--card__img" alt="pending">
                        </a>
                        <!-- End Card -->
                    </div>

                    <div class="col-sm-6">
                        <!-- Card -->
                        <a class="dashboard--card" href="{{ route('admin.appointment.list') }}">
                            <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Appointments') }}</h5>
                            <h2 class="dashboard--card__title">{{ $appointment_count }}</h2>
                            <img width="30"
                                src="{{ asset(config('app.asset_path') . '/admin/img/icons/confirmed.png') }}"
                                class="dashboard--card__img" alt="confirmed">
                        </a>
                        <!-- End Card -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <div class="card mb-3">
            <div class="card-body">
                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-auto">
                        <h4 class="d-flex align-items-center gap-10 mb-0">
                            <img width="20"
                                src="{{ asset(config('app.asset_path') . '/admin/img/icons/business_analytics.png') }}"
                                alt="Business Analytics">
                            {{ translate('Today Appointments') }}
                        </h4>
                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ \App\CentralLogics\translate('Patient Name') }}</th>
                                <th>{{ \App\CentralLogics\translate('Date') }}</th>
                                <th>{{ \App\CentralLogics\translate('Time') }}</th>
                                <th>{{ \App\CentralLogics\translate('Status') }}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                            @php
                                $today = date_create('now', new DateTimeZone('Africa/Addis_Ababa'))->format('Y-m-d');
                                $appointmentsForToday = $appointments->filter(function ($appointment) use ($today) {
                                    $appointmentDate = date_create($appointment->date)
                                        ->setTimezone(new DateTimeZone('Africa/Addis_Ababa'))
                                        ->format('Y-m-d');
                                    return $appointmentDate == $today;
                                });
                            @endphp

                            @if (count($appointmentsForToday) > 0)
                                @foreach ($appointmentsForToday as $key => $appointment)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.patient.view', [$appointment->patient['id']]) }}">
                                                {{ $appointment->patient->full_name }}
                                            </a>
                                        </td>
                                        <td>{{ date_create($appointment->date)->setTimezone(new DateTimeZone('Africa/Addis_Ababa'))->format('M d, Y') }}
                                        </td>
                                        <td>
                                            {{ date_create($appointment->appointmentSlot->start_time)->setTimezone(new DateTimeZone('Africa/Addis_Ababa'))->format('g:i a') }}
                                            -
                                            {{ date_create($appointment->appointmentSlot->end_time)->setTimezone(new DateTimeZone('Africa/Addis_Ababa'))->format('g:i a') }}
                                        </td>

                                        <td>
                                            <select name="status" class="form-control" id="update_status"
                                                data-appointment-id="{{ $appointment->id }}">
                                                <option value="pending"
                                                    {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="confirmed"
                                                    {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed
                                                </option>
                                                <option value="done"
                                                    {{ $appointment->status == 'done' ? 'selected' : '' }}>Done</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                    @if (count($appointmentsForToday) < 1)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No appointment for today!') }}</p>
                        </div>
                    @endif
                </div>
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
        var ctx = document.getElementById('business-overview');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [

                ],
                datasets: [{
                    label: 'Business',
                    data: [],
                    backgroundColor: [
                        '#673ab7',
                        '#346751',
                        '#343A40',
                        '#7D5A50',
                        '#C84B31',
                    ],
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
        function order_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('admin.roles.list') }}",
                type: "post",
                data: {
                    statistics_type: type,
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    $('#order_stats').html(data.view)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }
    </script>

    <script>
        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function() {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

        // CALL WHEN TAB IS CLICKED
        // =======================================================
        $('[data-toggle="chart-bar"]').click(function(e) {
            let keyDataset = $(e.currentTarget).attr('data-datasets')

            if (keyDataset === 'lastWeek') {
                updatingChart.data.labels = ["Apr 22", "Apr 23", "Apr 24", "Apr 25", "Apr 26", "Apr 27", "Apr 28",
                    "Apr 29", "Apr 30", "Apr 31"
                ];
                updatingChart.data.datasets = [{
                        "data": [120, 250, 300, 200, 300, 290, 350, 100, 125, 320],
                        "backgroundColor": "#377dff",
                        "hoverBackgroundColor": "#377dff",
                        "borderColor": "#377dff"
                    },
                    {
                        "data": [250, 130, 322, 144, 129, 300, 260, 120, 260, 245, 110],
                        "backgroundColor": "#e7eaf3",
                        "borderColor": "#e7eaf3"
                    }
                ];
                updatingChart.update();
            } else {
                updatingChart.data.labels = ["May 1", "May 2", "May 3", "May 4", "May 5", "May 6", "May 7", "May 8",
                    "May 9", "May 10"
                ];
                updatingChart.data.datasets = [{
                        "data": [200, 300, 290, 350, 150, 350, 300, 100, 125, 220],
                        "backgroundColor": "#377dff",
                        "hoverBackgroundColor": "#377dff",
                        "borderColor": "#377dff"
                    },
                    {
                        "data": [150, 230, 382, 204, 169, 290, 300, 100, 300, 225, 120],
                        "backgroundColor": "#e7eaf3",
                        "borderColor": "#e7eaf3"
                    }
                ]
                updatingChart.update();
            }
        })


        // INITIALIZATION OF BUBBLE CHARTJS WITH DATALABELS PLUGIN
        // =======================================================
        $('.js-chart-datalabels').each(function() {
            $.HSCore.components.HSChartJS.init($(this), {
                plugins: [ChartDataLabels],
                options: {
                    plugins: {
                        datalabels: {
                            anchor: function(context) {
                                var value = context.dataset.data[context.dataIndex];
                                return value.r < 20 ? 'end' : 'center';
                            },
                            align: function(context) {
                                var value = context.dataset.data[context.dataIndex];
                                return value.r < 20 ? 'end' : 'center';
                            },
                            color: function(context) {
                                var value = context.dataset.data[context.dataIndex];
                                return value.r < 20 ? context.dataset.backgroundColor : context.dataset
                                    .color;
                            },
                            font: function(context) {
                                var value = context.dataset.data[context.dataIndex],
                                    fontSize = 25;

                                if (value.r > 50) {
                                    fontSize = 35;
                                }

                                if (value.r > 70) {
                                    fontSize = 55;
                                }

                                return {
                                    weight: 'lighter',
                                    size: fontSize
                                };
                            },
                            offset: 2,
                            padding: 0
                        }
                    }
                },
            });
        });
    </script>

    <!-- Earning Statistics Chart -->

    <script>
        function earningStatisticsUpdate(t) {
            let value = $(t).attr('data-earn-type');
            $.ajax({
                url: '{{ route('admin.dashboard') }}',
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
    </script>
@endpush

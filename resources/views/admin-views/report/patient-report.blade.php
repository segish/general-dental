@extends('layouts.admin.app')

@section('title', translate('Patient Report'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Patient Report ({{ $startDate->toDateString() }} - {{ $endDate->toDateString() }})</h2>
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
                <a href="#" id="excel" data-format="excel" data-bs-toggle="modal" data-bs-target="#reportModal"
                    class="btn btn-success d-flex align-items-center px-2">
                    <img src="{{ asset(config('app.asset_path') . '/admin/img/icons/excel.png') }}" alt="Excel"
                        style="height: 24px; width: auto;">
                </a>
                <a href="#" id="pdf" data-format="pdf" data-bs-toggle="modal" data-bs-target="#reportModal"
                    class="btn btn-danger d-flex align-items-center px-2">
                    <img src="{{ asset(config('app.asset_path') . '/admin/img/icons/pdf.png') }}" alt="PDF"
                        style="height: 24px; width: auto;">
                </a>
            </div>
        </div>
    </div>
    <!-- Modal for report selection -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="reportForm" method="GET">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ translate('Select Report to Download') }}</h5>
                            <button type="button" class="close" data-dismiss="modal"
                                aria-label="{{ translate('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="file_format" id="file_format">
                            <input type="hidden" name="start_date" id="start_date">
                            <input type="hidden" name="end_date" id="end_date">

                            <div class="form-group">
                                <label class="input-label">{{ translate('Choose Report Type') }}</label>
                                <select id="reportType" name="report_type" class="form-control js-select2-custom" required>
                                    <option value="">Choose a Service</option>
                                    <option value="patient_demographics">Patient Demographics Report</option>
                                    <option value="patient_visit_summary">Patient Visit Summary</option>
                                    <option value="visit_frequency_by_patient">Visit Frequency by Patient</option>
                                    <option value="laboratory_test_report">Laboratory Test Report</option>
                                    <option value="billing_summary_report">Billing Summary Report</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary float-start"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary float-end">Download</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Card -->
    <div class="card mb-3">
        <div class="row g-2" id="order_stats">

            <div class="col-sm-3">
                <!-- Card -->
                <div class="dashboard--card">
                    <h5 class="dashboard--card__subtitle">
                        {{ \App\CentralLogics\translate('Unique Patients Visited') }}
                    </h5>
                    <h2 class="dashboard--card__title">
                        {{ $totalPatientsVisited }}
                    </h2>
                </div>
                <!-- End Card -->
            </div>

            <div class="col-sm-3">
                <!-- Card -->
                <div class="dashboard--card">
                    <h5 class="dashboard--card__subtitle">
                        {{ \App\CentralLogics\translate('Outpatient Visits (OPD)') }}
                    </h5>
                    <h2 class="dashboard--card__title">
                        {{ $opdVisits }}
                    </h2>
                </div>
                <!-- End Card -->
            </div>

            <div class="col-sm-3">
                <!-- Card -->
                <div class="dashboard--card">
                    <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Inpatient Visits (IPD)') }}
                    </h5>
                    <h2 class="dashboard--card__title">
                        {{ $ipdVisits }}
                    </h2>
                </div>
                <!-- End Card -->
            </div>

            <div class="col-sm-3">
                <!-- Card -->
                <div class="dashboard--card">
                    <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Total Visits') }}</h5>
                    <h2 class="dashboard--card__title">
                        {{ $totalVisits }}
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
                                        Patients by Age
                                    </h4>
                                </div>
                                <div class="card-body" id="Tests By Type">
                                    <div class="chartjs-custom position-relative h-150">
                                        <canvas id="ageGroupChart" height="150"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                        Top 10 Most Ordered Lab Tests
                                    </h4>
                                </div>
                                <div class="card-body" id="Tests By Type">
                                    <div class="chartjs-custom position-relative h-150">
                                        <canvas id="topLabTestsChart" height="150"></canvas>
                                    </div>
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
@push('script')
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/vendor/chart.js.extensions/chartjs-extensions.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script
        src="{{ asset(config('app.asset_path') . '/admin') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
@endpush
@push('script_2')
<script>
    $(document).ready(function () {
        $('#excel, #pdf').on('click', function () {
            // Get file format from clicked button
            let file_format = $(this).data('format');
            $('#file_format').val(file_format);

            // Get current date inputs from the filter form
            let startDate = $('input[name="start_date"]').val();
            let endDate = $('input[name="end_date"]').val();

            // Set them in the modal form
            $('#start_date').val(startDate);
            $('#end_date').val(endDate);
        });

        // On form submit, dynamically change action URL
        $('#reportForm').on('submit', function (e) {
            e.preventDefault();

            let reportType = $('#reportType').val();
            if (!reportType) {
                alert('Please select a report type.');
                return;
            }

            let action = "{{ url('admin/reports/patients') }}/" + reportType;
            this.action = action;

            this.submit();
        });
    });
</script>


    <script>
        const ctxAge = document.getElementById('ageGroupChart').getContext('2d');
        new Chart(ctxAge, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($ageGroups)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($ageGroups)) !!},
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    borderColor: '#fff',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Patient Age Group Distribution'
                    }
                }
            }
        });
    </script>
    <script>
        const ctx = document.getElementById('topLabTestsChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topLabTests->pluck('test.test_name')) !!},
                datasets: [{
                    label: 'Test Count',
                    data: {!! json_encode($topLabTests->pluck('total')) !!},
                    backgroundColor: '#36A2EB',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Most Frequently Ordered Lab Tests'
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endpush

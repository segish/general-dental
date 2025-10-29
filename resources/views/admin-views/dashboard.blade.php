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


        <!-- Card -->
        <div class="row g-3 mb-4" id="order_stats">
            @if (auth('admin')->user()->can('dashboard.view_patient_count'))
                <div class="col-sm-6 col-lg-3">
                    <!-- Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-lg avatar-soft-primary avatar-circle">
                                        <span class="avatar-initials" style="background-color: rgb(0, 168, 232, 0.1) !important;">
                                            <i class="tio-user-big text-primary" style="font-size: 1.5rem;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">{{ \App\CentralLogics\translate('Patients') }}
                                    </h6>
                                    <h3 class="card-text text-dark mb-0">{{ $data['patient_count'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.patient.list') }}" class="btn btn-soft-primary btn-sm">
                                    <i class="tio-visible me-1"></i> View All
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endif

            @if (auth('admin')->user()->can('dashboard.view_revenue'))
                <div class="col-sm-6 col-lg-3">
                    <!-- Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-lg avatar-soft-success avatar-circle">
                                        <span class="avatar-initials">
                                            <i class="tio-money text-success" style="font-size: 1.5rem;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">
                                        {{ \App\CentralLogics\translate('Total Revenue Today') }}</h6>
                                    <h3 class="card-text text-dark mb-0">{{ $data['total_amount_today'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-soft-success text-success">
                                    <i class="tio-trending-up me-1"></i> Today
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endif

            @if (auth('admin')->user()->can('dashboard.view_staff_count'))
                <div class="col-sm-6 col-lg-3">
                    <!-- Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-lg avatar-soft-info avatar-circle">
                                        <span class="avatar-initials">
                                            <i class="tio-group-senior text-info" style="font-size: 1.5rem;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">
                                        {{ \App\CentralLogics\translate('Staff Count') }}</h6>
                                    <h3 class="card-text text-dark mb-0">{{ $data['staff'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-soft-info text-info">
                                    <i class="tio-verified me-1"></i> Active
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endif

            @if (auth('admin')->user()->can('dashboard.view_pending_tests'))
                <div class="col-sm-6 col-lg-3">
                    <!-- Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-lg avatar-soft-warning avatar-circle">
                                        <span class="avatar-initials">
                                            <i class="tio-time text-warning" style="font-size: 1.5rem;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">
                                        {{ \App\CentralLogics\translate('Pending Tests') }}</h6>
                                    <h3 class="card-text text-dark mb-0">{{ $data['pending_tests'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-soft-warning text-warning">
                                    <i class="tio-clock me-1"></i> Pending
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endif

            @if (auth('admin')->user()->can('dashboard.view_completed_tests'))
                <div class="col-sm-6 col-lg-3">
                    <!-- Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-lg avatar-soft-success avatar-circle">
                                        <span class="avatar-initials">
                                            <i class="tio-checkmark-circle text-success" style="font-size: 1.5rem;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">
                                        {{ \App\CentralLogics\translate('Completed Tests') }}</h6>
                                    <h3 class="card-text text-dark mb-0">{{ $data['completed_tests'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-soft-success text-success">
                                    <i class="tio-done me-1"></i> Completed
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endif

            {{-- @if (auth('admin')->user()->can('dashboard.view_total_samples_collected'))
                <div class="col-sm-3">
                    <a class="dashboard--card" href="#">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Total Samples Collected') }}
                        </h5>
                        <h2 class="dashboard--card__title">{{ $data['total_samples_collected'] ?? 0 }}</h2>
                    </a>
                </div>
            @endif --}}


            {{-- @if (auth('admin')->user()->can('dashboard.view_critical_alerts'))
                <div class="col-sm-3">
                    <a class="dashboard--card" href="#">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Critical Alerts') }}</h5>
                        <h2 class="dashboard--card__title">{{ $data['critical_alerts'] ?? 0 }}</h2>
                    </a>
                </div>
            @endif --}}

            @if (auth('admin')->user()->can('dashboard.view_patients_registered_today'))
                <div class="col-sm-6 col-lg-3">
                    <!-- Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-lg avatar-soft-primary avatar-circle">
                                        <span class="avatar-initials" style="background-color: rgb(0, 168, 232, 0.1) !important;">
                                            <i class="tio-user-add text-primary" style="font-size: 1.5rem;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">
                                        {{ \App\CentralLogics\translate('Patients Registered Today') }}</h6>
                                    <h3 class="card-text text-dark mb-0">{{ $data['patients_registered_today'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-soft-primary text-primary">
                                    <i class="tio-calendar me-1"></i> Today
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endif

            @if (auth('admin')->user()->can('dashboard.view_pending_payments'))
                <div class="col-sm-6 col-lg-3">
                    <!-- Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-lg avatar-soft-danger avatar-circle">
                                        <span class="avatar-initials">
                                            <i class="tio-money-vs text-danger" style="font-size: 1.5rem;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">
                                        {{ \App\CentralLogics\translate('Pending Payments') }}</h6>
                                    <h3 class="card-text text-dark mb-0">{{ $data['pending_payments'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-soft-danger text-danger">
                                    <i class="tio-warning me-1"></i> Pending
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endif

            {{-- @if (auth('admin')->user()->can('dashboard.view_samples_received_today'))
                <div class="col-sm-3">
                    <a class="dashboard--card" href="#">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Samples Received Today') }}
                        </h5>
                        <h2 class="dashboard--card__title">{{ $data['samples_received_today'] ?? 0 }}</h2>
                    </a>
                </div>
            @endif --}}

            @if (auth('admin')->user()->can('dashboard.view_tests_completed_today'))
                <div class="col-sm-6 col-lg-3">
                    <!-- Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-lg avatar-soft-success avatar-circle">
                                        <span class="avatar-initials">
                                            <i class="tio-checkmark-circle text-success" style="font-size: 1.5rem;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">
                                        {{ \App\CentralLogics\translate('Tests Completed Today') }}</h6>
                                    <h3 class="card-text text-dark mb-0">{{ $data['tests_completed_today'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-soft-success text-success">
                                    <i class="tio-done me-1"></i> Today
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endif

            {{-- @if (auth('admin')->user()->can('dashboard.view_test_result_processed_today'))
                <div class="col-sm-3">
                    <a class="dashboard--card" href="#">
                        <h5 class="dashboard--card__subtitle">
                            {{ \App\CentralLogics\translate('Test Result Processed Today') }}</h5>
                        <h2 class="dashboard--card__title">{{ $data['test_result_processed_today'] ?? 0 }}</h2>
                    </a>
                </div>
            @endif --}}

            {{-- @if (auth('admin')->user()->can('dashboard.view_test_result_approved_today'))
                <div class="col-sm-3">
                    <a class="dashboard--card" href="#">
                        <h5 class="dashboard--card__subtitle">
                            {{ \App\CentralLogics\translate('Test Result Approved Today') }}</h5>
                        <h2 class="dashboard--card__title">{{ $data['test_result_approved_today'] ?? 0 }}</h2>
                    </a>
                </div>
            @endif --}}

            {{-- @if (auth('admin')->user()->can('dashboard.view_pending_test_reports'))
                <div class="col-sm-3">
                    <a class="dashboard--card" href="#">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Pending Test Reports') }}
                        </h5>
                        <h2 class="dashboard--card__title">{{ $data['pending_test_reports'] ?? 0 }}</h2>
                    </a>
                </div>
            @endif --}}

            {{-- @if (auth('admin')->user()->can('dashboard.view_pending_sample_collections'))
                <div class="col-sm-3">
                    <a class="dashboard--card" href="#">
                        <h5 class="dashboard--card__subtitle">
                            {{ \App\CentralLogics\translate('Pending Sample Collections') }}</h5>
                        <h2 class="dashboard--card__title">{{ $data['pending_sample_collections'] ?? 0 }}</h2>
                    </a>
                </div>
            @endif --}}

            {{-- @if (auth('admin')->user()->can('dashboard.view_rejected_samples'))
                <div class="col-sm-3">
                    <!-- Card -->
                    <a class="dashboard--card" href="#">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Rejected Samples') }}</h5>
                        <h2 class="dashboard--card__title">{{ $data['rejected_samples'] ?? 0 }}</h2>
                    </a>
                    <!-- End Card -->
                </div>
            @endif --}}

            {{-- @if (auth('admin')->user()->can('dashboard.view_critical_samples_tests'))
                <div class="col-sm-3">
                    <!-- Card -->
                    <a class="dashboard--card" href="#">
                        <h5 class="dashboard--card__subtitle">{{ \App\CentralLogics\translate('Critical Samples Tests') }}
                        </h5>
                        <h2 class="dashboard--card__title">{{ $data['critical_samples_tests'] ?? 0 }}</h2>
                    </a>
                    <!-- End Card -->
                </div>
            @endif --}}
        </div>
        <!-- End Card -->

        <!-- Today's Activities Tabs -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-calendar text-primary"></i>
                    {{ \App\CentralLogics\translate('Today\'s Activities') }}
                </h4>
            </div>
            <div class="card-body p-0">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="todayTabs" role="tablist">
                    @if (auth('admin')->user()->can('dashboard.view_todays_billings_list'))
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="billings-tab" data-toggle="tab" href="#billings"
                                role="tab" aria-controls="billings" aria-selected="true">
                                <i class="tio-receipt mr-1"></i>
                                {{ \App\CentralLogics\translate('Billings') }}
                                <span class="badge badge-primary ml-2">{{ count($data['todays_billing'] ?? []) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (auth('admin')->user()->can('dashboard.view_todays_visit_list'))
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ !auth('admin')->user()->can('dashboard.view_todays_billings_list') ? 'active' : '' }}"
                                id="visits-tab" data-toggle="tab" href="#visits" role="tab" aria-controls="visits"
                                aria-selected="{{ !auth('admin')->user()->can('dashboard.view_todays_billings_list') ? 'true' : 'false' }}">
                                <i class="tio-user mr-1"></i>
                                {{ \App\CentralLogics\translate('Visits') }}
                                <span class="badge badge-info ml-2">{{ count($data['todays_visit'] ?? []) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (auth('admin')->user()->can('dashboard.view_todays_laboratory_requests'))
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ !(auth('admin')->user()->can('dashboard.view_todays_billings_list') || auth('admin')->user()->can('dashboard.view_todays_visit_list')) ? 'active' : '' }}"
                                id="lab-requests-tab" data-toggle="tab" href="#lab-requests" role="tab"
                                aria-controls="lab-requests"
                                aria-selected="{{ !(auth('admin')->user()->can('dashboard.view_todays_billings_list') || auth('admin')->user()->can('dashboard.view_todays_visit_list')) ? 'true' : 'false' }}">
                                <i class="tio-lab mr-1"></i>
                                {{ \App\CentralLogics\translate('Lab Requests') }}
                                <span
                                    class="badge badge-warning ml-2">{{ count($data['todays_lab_requset'] ?? []) }}</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="todayTabsContent">
                    @if (auth('admin')->user()->can('dashboard.view_todays_billings_list'))
                        <div class="tab-pane fade show active" id="billings" role="tabpanel"
                            aria-labelledby="billings-tab">
                            <div class="p-4">
                                <div class="table-responsive">
                                    <table
                                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Patient') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Created By') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Bill Type') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Total Amount') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Received Amount') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Billing Status') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Date') }}</th>
                                                <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($data['todays_billing']) > 0)
                                                @foreach ($data['todays_billing'] as $key => $billing)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $billing->visit->patient->full_name }}</td>
                                                        <td>{{ $billing->admin->f_name }}</td>
                                                        <td>
                                                            @if ($billing->laboratory_request_id)
                                                                <span class="badge bg-soft-info text-info">Laboratory
                                                                    service</span>
                                                            @elseif ($billing->radiology_request_id)
                                                                <span class="badge bg-soft-warning text-warning">Radiology
                                                                    service</span>
                                                            @elseif($billing->billing_service_id)
                                                                <span class="badge bg-soft-secondary text-secondary">Other
                                                                    service</span>
                                                            @elseif($billing->emergency_medicine_issuance_id)
                                                                <span class="badge bg-soft-primary text-primary">Inclinic
                                                                    service</span>
                                                            @elseif($billing->patient_procedures_id)
                                                                <span class="badge bg-soft-success text-success">Store
                                                                    service</span>
                                                            @endif
                                                        </td>
                                                        <td><strong>{{ $billing->total_amount }}</strong></td>
                                                        <td><strong>{{ $billing->amount_paid }}</strong></td>
                                                        <td>
                                                            @if ($billing->amount_paid >= $billing->total_amount && $billing->is_canceled == 0)
                                                                <span class="badge badge-soft-success">Paid</span>
                                                            @elseif ($billing->amount_paid == 0 && $billing->is_canceled == 0)
                                                                <span class="badge badge-soft-danger">Unpaid</span>
                                                            @elseif ($billing->amount_paid > 0 && $billing->amount_paid < $billing->total_amount && $billing->is_canceled == 0)
                                                                <span class="badge badge-soft-warning">Partial
                                                                    ({{ $billing->total_amount - $billing->amount_paid }})
                                                                </span>
                                                            @elseif ($billing->is_canceled == 1)
                                                                <span
                                                                    class="badge bg-secondary">{{ $billing->status }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($billing->created_at)->format('M d, Y') }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-1 justify-content-center">
                                                                @if (auth('admin')->user()->can('invoice.view'))
                                                                    <button class="btn btn-outline-primary btn-sm"
                                                                        onclick="viewBillingDetails(@js($billing))"
                                                                        title="View Details">
                                                                        <i class="tio-visible"></i>
                                                                    </button>
                                                                @endif
                                                                @if (auth('admin')->user()->can('invoice.pdf') &&
                                                                        !($billing->amount_paid < $billing->total_amount) &&
                                                                        !$billing->is_canceled)
                                                                    <a class="btn btn-outline-success btn-sm"
                                                                        href="{{ route('admin.invoice.pdf', [$billing->id]) }}"
                                                                        target="_blank" title="Download PDF">
                                                                        <i class="tio-receipt"></i>
                                                                    </a>
                                                                @endif
                                                                @if (auth('admin')->user()->can('invoice.edit') &&
                                                                        $billing->amount_paid < $billing->total_amount &&
                                                                        !$billing->is_canceled)
                                                                    <button
                                                                        class="btn btn-outline-warning btn-sm edit-billing"
                                                                        data-toggle="modal" data-target="#edit_billing"
                                                                        data-id="{{ $billing->id }}"
                                                                        data-total-amount="{{ $billing->total_amount }}"
                                                                        data-amount-paid="{{ $billing->amount_paid }}"
                                                                        title="Edit Payment">
                                                                        <i class="tio-edit"></i>
                                                                    </button>
                                                                @endif
                                                                @if (auth('admin')->user()->can('invoice.cancel-or-refund') && !$billing->is_canceled)
                                                                    <button
                                                                        class="btn btn-outline-danger btn-sm cancel-or-refund"
                                                                        data-toggle="modal"
                                                                        data-target="#cancel_or_refund"
                                                                        data-id="{{ $billing->id }}"
                                                                        data-total-amount="{{ $billing->total_amount }}"
                                                                        data-amount-paid="{{ $billing->amount_paid }}"
                                                                        title="Cancel/Refund">
                                                                        <i class="tio-undo"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            <form
                                                                action="{{ route('admin.invoice.delete', [$billing->id]) }}"
                                                                method="post" id="billing-{{ $billing->id }}">
                                                                @csrf @method('delete')
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="9" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="tio-receipt-outlined" style="font-size: 3rem;"></i>
                                                            <p class="mt-2 mb-0">No billings found for today</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth('admin')->user()->can('dashboard.view_todays_visit_list'))
                        <div class="tab-pane fade {{ !auth('admin')->user()->can('dashboard.view_todays_billings_list') ? 'show active' : '' }}"
                            id="visits" role="tabpanel" aria-labelledby="visits-tab">
                            <div class="p-4">
                                <div class="table-responsive">
                                    <table
                                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Patient Name') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Doctor Name') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Visit Type') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Visit Date') }}</th>
                                                <th class="text-center">{{ \App\CentralLogics\translate('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($data['todays_visit']) > 0)
                                                @foreach ($data['todays_visit'] as $key => $visit)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar avatar-sm avatar-circle me-2">
                                                                    <span
                                                                        class="avatar-initials bg-soft-primary text-primary">
                                                                        {{ substr($visit->patient->full_name, 0, 2) }}
                                                                    </span>
                                                                </div>
                                                                <span
                                                                    class="fw-medium">{{ $visit->patient->full_name }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($visit->doctor)
                                                                <span
                                                                    class="badge bg-soft-info text-info">{{ $visit->doctor->full_name }}</span>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-soft-primary text-primary">{{ $visit->visit_type }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <span
                                                                    class="fw-medium">{{ $visit->created_at->format('M d, Y') }}</span>
                                                                <small
                                                                    class="text-muted">{{ $visit->created_at->format('h:i A') }}</small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-1 justify-content-center">
                                                                @if (auth('admin')->user()->can('visit.view'))
                                                                    <a class="btn btn-outline-primary btn-sm"
                                                                        href="{{ route('admin.patient.view', [$visit->patient->id]) . '?active=' . $visit->id }}"
                                                                        title="View Patient">
                                                                        <i class="tio-visible"></i>
                                                                    </a>
                                                                @endif
                                                                @if (auth('admin')->user()->can('visit.delete'))
                                                                    <button class="btn btn-outline-danger btn-sm"
                                                                        onclick="form_alert('visit-{{ $visit->id }}','{{ \App\CentralLogics\translate('Want to delete this visit?') }}')"
                                                                        title="Delete Visit">
                                                                        <i class="tio-delete"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="tio-user-outlined" style="font-size: 3rem;"></i>
                                                            <p class="mt-2 mb-0">No visits found for today</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth('admin')->user()->can('dashboard.view_todays_laboratory_requests'))
                        <div class="tab-pane fade {{ !(auth('admin')->user()->can('dashboard.view_todays_billings_list') || auth('admin')->user()->can('dashboard.view_todays_visit_list')) ? 'show active' : '' }}"
                            id="lab-requests" role="tabpanel" aria-labelledby="lab-requests-tab">
                            <div class="p-4">
                                <div class="table-responsive">
                                    <table
                                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Patient Name') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Requested By') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Issued By') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Order Status') }}</th>
                                                <th>{{ \App\CentralLogics\translate('Status') }}</th>
                                                <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($data['todays_lab_requset']) > 0)
                                                @foreach ($data['todays_lab_requset'] as $key => $request)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar avatar-sm avatar-circle me-2">
                                                                    <span
                                                                        class="avatar-initials bg-soft-warning text-warning">
                                                                        {{ substr($request->visit->patient->full_name, 0, 2) }}
                                                                    </span>
                                                                </div>
                                                                <span
                                                                    class="fw-medium">{{ $request->visit->patient->full_name }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($request->requested_by === 'physician')
                                                                <span class="badge bg-soft-info text-info">Physician
                                                                    (In-Clinic)
                                                                </span>
                                                            @elseif ($request->requested_by === 'self')
                                                                <span
                                                                    class="badge bg-soft-primary text-primary">Self</span>
                                                            @elseif ($request->requested_by === 'other healthcare')
                                                                <span class="badge bg-soft-secondary text-secondary">Other
                                                                    Healthcare</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($request->billing && $request->billing->admin)
                                                                <span
                                                                    class="fw-medium">{{ $request->billing->admin->fullname }}</span>
                                                            @else
                                                                <span class="text-muted">---</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($request->order_status === 'urgent')
                                                                <span class="badge bg-danger">Urgent</span>
                                                            @elseif ($request->order_status === 'routine')
                                                                <span
                                                                    class="badge bg-soft-success text-success">Routine</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($request->status === 'pending')
                                                                <span class="badge bg-warning text-dark">Pending</span>
                                                            @elseif ($request->status === 'in process')
                                                                <span class="badge bg-info">In Process</span>
                                                            @elseif ($request->status === 'completed')
                                                                <span class="badge bg-success">Completed</span>
                                                            @elseif ($request->status === 'rejected')
                                                                <span class="badge bg-danger">Rejected</span>
                                                            @else
                                                                <span class="badge bg-secondary">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-1 justify-content-center">
                                                                @if (auth('admin')->user()->can('patient.view'))
                                                                    <a class="btn btn-outline-primary btn-sm"
                                                                        href="{{ route('admin.patient.view', [$request->visit->patient->id]) . '?active=' . $request->visit->id }}"
                                                                        title="View Patient">
                                                                        <i class="tio-visible"></i>
                                                                    </a>
                                                                @endif
                                                                @if (auth('admin')->user()->can('patient.delete'))
                                                                    <button class="btn btn-outline-danger btn-sm"
                                                                        onclick="form_alert('patient-{{ $request['id'] }}','{{ \App\CentralLogics\translate('Want to delete this patient ?') }}')"
                                                                        title="Delete Request">
                                                                        <i class="tio-delete"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            <form
                                                                action="{{ route('admin.patient.delete', [$request['id']]) }}"
                                                                method="post" id="patient-{{ $request['id'] }}">
                                                                @csrf @method('delete')
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="tio-lab-outlined" style="font-size: 3rem;"></i>
                                                            <p class="mt-2 mb-0">No laboratory requests found for today</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Card -->
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
                                {{ translate('Earning_statistics') }}
                            </h4>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <ul class="option-select-btn mb-0">
                                <li>
                                    <label>
                                        <input type="radio" name="statistics2" hidden checked>
                                        <span data-earn-type="yearEarn" onclick="earningStatisticsUpdate(this)">This
                                            Year</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="statistics2" hidden="">
                                        <span data-earn-type="MonthEarn" onclick="earningStatisticsUpdate(this)">This
                                            Month</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="statistics2" hidden="">
                                        <span data-earn-type="WeekEarn" onclick="earningStatisticsUpdate(this)">This
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
        <!-- End Card -->

        @if (auth('admin')->user()->can('dashboard.view_department_count'))
            <div class="row g-2">
                <div class="col-12">
                    <!-- Card -->
                    <div class="card h-100">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                <img width="20"
                                    src="{{ asset(config('app.asset_path') . '/admin/img/icons/business_overview.png') }}"
                                    alt="business overview">
                                {{ translate('Total Business Overview') }}
                            </h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body" id="business-overview-board">
                            <!-- Chart -->
                            <div class="chartjs-custom position-relative h-400">
                                <canvas id="business-overview"></canvas>
                            </div>
                            <!-- End Chart -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Card -->
                </div>
            </div>
        @endif


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
        $(document).ready(function() {
            $('.edit-billing').click(function() {
                var billingId = $(this).data('id');
                $('#billing_id').val(billingId);
                var totalAmount = $(this).data('total-amount');
                var amountPaid = $(this).data('amount-paid');

                // Update the text in the modal
                $('#edit_billing_form h5:eq(0)').text('Total Amount: ' + totalAmount);
                $('#edit_billing_form h5:eq(1)').text('Amount Received: ' + amountPaid);

                // Calculate the remaining amount
                var amountLeft = totalAmount - amountPaid;

                // Set the max value of the "Amount Left" input field and update the displayed amount left
                $('#amount_left').attr('max', amountLeft.toFixed(
                    2)); // Ensure max value is 2 decimal places
                $('#amount_left').val(''); // Reset the field in case of any previous data
                $('#amount_left_display').text(amountLeft.toFixed(2)); // Update the displayed amount left
            });

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
        var ctx = document.getElementById('business-overview');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    '{{ translate('Staff') }} ( {{ $data['staff'] ?? 0 }} )',
                    '{{ translate('patient') }} ( {{ $data['patient'] ?? 0 }} )',
                    '{{ translate('department') }} ( {{ $data['department'] ?? 0 }} )',
                ],
                datasets: [{
                    label: 'Business',
                    data: ['{{ $data['staff'] ?? 0 }}', '{{ $data['patient'] ?? 0 }}',
                        '{{ $data['department'] ?? 0 }}'
                    ],
                    backgroundColor: [
                        '#673ab7',
                        '#346751',
                        '#343A40',
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
            console.log('EARNING UPDATE HERE')
            let value = $(t).attr('data-earn-type');
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
    </script>
@endpush

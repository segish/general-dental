@extends('layouts.admin.app')

@section('title', translate('patients Report List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ \App\CentralLogics\translate('patients_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $patientsReport->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4 col-sm-8 col-md-6">
                                <form action="{{route('admin.out_patient_report.pdf')}}" method="get" enctype="multipart/form-data" target="_blank">
                                    <div class="input-group">
                                        <!-- From Date Input -->
                                        <input type="date" name="from_date" class="form-control"
                                               placeholder="From Date" value="{{ request('from_date') }}" required>

                                        <!-- To Date Input -->
                                        <input type="date" name="to_date" class="form-control mx-2"
                                               placeholder="To Date" value="{{ request('to_date') }}" required>

                                        <!-- Submit Button -->
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                {{ \App\CentralLogics\translate('Submit') }}
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
                                    <th>{{ \App\CentralLogics\translate('full_name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Age') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Gender') }}</th>
                                    <th>{{ \App\CentralLogics\translate('MRN') }}</th>
                                    <th>{{ \App\CentralLogics\translate('NCoD') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Service Date') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Status') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($patientsReport as $key => $patientReport)
                                    <tr>
                                        <td>{{ $patientsReport->firstitem() + $key }}</td>

                                        <td>{{ $patientReport->patient->full_name }}</td>
                                        <td>{{ $patientReport->patient->age }}</td>
                                        <td>{{ $patientReport->patient->gender }}</td>
                                        <td>{{ $patientReport->mrn }}</td>
                                        <td>{{ $patientReport->ncod }}</td>
                                        <td>{{ \Carbon\Carbon::parse($patientReport->service_date)->format('M j, Y') }}
                                        </td>
                                        <td>{{ $patientReport->status }}</td>

                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('patient.view'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.patient.view', [$patientReport['id']]) }}">
                                                        <i class="tio tio-visible"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('patient.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.patient.edit', [$patientReport['id']]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('patient.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('patient-{{ $patientReport['id'] }}','{{ \App\CentralLogics\translate('Want to delete this patient ?') }}')"><i
                                                            class="tio tio-delete"></i></a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.patient.delete', [$patientReport['id']]) }}"
                                                method="post" id="patient-{{ $patientReport['id'] }}">
                                                @csrf @method('delete')
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
                            {!! $patientsReport->links() !!}
                        </div>
                    </div>
                    @if (count($patientsReport) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{ asset('/assets/admin') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function loadPdf() {
            // Get the date values from the inputs
            var fromDate = document.getElementById('from_date').value;
            var toDate = document.getElementById('to_date').value;

            // Check if both dates are selected
            if (fromDate && toDate) {
                // Construct the route with the date parameters
                var routeUrl =
                    "{{ route('admin.out_patient_report.pdf', ['from_date' => ':from_date', 'to_date' => ':to_date']) }}";
                routeUrl = routeUrl.replace(':from_date', fromDate).replace(':to_date', toDate);

                // Redirect to the generated URL
                window.location.href = routeUrl;
            } else {
                alert("Please select both From and To dates.");
            }
        }
    </script>
@endpush

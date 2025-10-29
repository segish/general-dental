@extends('layouts.admin.app')

@section('title', translate('Add Medical Certification'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/assetsadmin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assetsadmin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('Add New Medical Certificate') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="javascript:" method="post" id="medical_certification_form" enctype="multipart/form-data">
                    @csrf
                    <div id="from_part_2">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Patient') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <select name="patient_id" id="patient-select"
                                                class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>Select a patient</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Select the Visit') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <select name="visit_id" class="form-control js-select2-custom"
                                                id="medical-history-select" required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('') }}</option>
                                                @foreach ($visits as $visit)
                                                    <option value="{{ $visit->id }}"
                                                        data-patient-id="{{ $visit->patient->id }}">
                                                        {{ $visit->patient->full_name }} (Dr.
                                                        {{ $visit->doctor->full_name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Date -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="date">{{ \App\CentralLogics\translate('Date') }}</label>
                                            <input type="date" name="date" class="form-control" required>
                                        </div>
                                    </div>
                                    <!-- Treated From Date -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="treated_from">{{ \App\CentralLogics\translate('Treated From') }}</label>
                                            <input type="date" name="treated_from" class="form-control" required>
                                        </div>
                                    </div>
                                    <!-- Treated From Date -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="treated_to">{{ \App\CentralLogics\translate('Treated To') }}</label>
                                            <input type="date" name="treated_to" class="form-control" required>
                                        </div>
                                    </div>
                                    <!-- Rest Required -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="rest_required">{{ \App\CentralLogics\translate('Rest Required') }}</label>
                                            <input type="text" name="rest_required" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Diagnosis -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="diagnosis">{{ \App\CentralLogics\translate('Diagnosis') }}</label>
                                            <textarea name="diagnosis" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <!-- Remark -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="remark">{{ \App\CentralLogics\translate('Remark') }}</label>
                                            <textarea name="remark" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn btn-primary">{{ \App\CentralLogics\translate('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#patient-select').select2({
                ajax: {
                    url: '{{ route('admin.appointment.get-patients') }}', // Add comma here
                    dataType: 'json',
                    delay: 250, // Debounce for better performance
                    data: function(params) {
                        return {
                            search: params.term, // Search term entered by the user
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(patient => ({
                                id: patient.id,
                                text: `${patient.full_name} - ${patient.registration_no} - ${patient.phone}`,
                            })),
                        };
                    },
                    cache: true,
                },
                width: '100%',
                dropdownAutoWidth: true,
                minimumInputLength: 2, // Start searching after 2 characters
            });
        });
    </script>
    <script>
        $('#medical_certification_form').on('submit', function(e) {
            e.preventDefault(); // prevent page refresh

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post({
                url: '{{ route('admin.medical_certification.store') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.success('{{ translate('Medical Certification Saved successfully!') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setTimeout(function() {
                        location.href = '{{ route('admin.medical_certification.list') }}';
                    }, 2000);
                },
                error: function(xhr) {
                    if (xhr.status === 409 && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.error('Something went wrong!', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#patient-select').change(function() {
                var selectedPatientId = $(this).val();
                var hasMedicalHistory = false; // Flag to check if there's any medical history

                // Filter medical histories based on the selected patient
                $('#medical-history-select option').each(function() {
                    var patientId = $(this).data('patient-id');
                    if (patientId != selectedPatientId) {
                        $(this).hide(); // Hide options that don't match
                    } else {
                        $(this).show(); // Show matching options
                        hasMedicalHistory = true; // Set flag to true if a match is found
                    }
                });

                // Reset the medical history selection
                $('#medical-history-select').val('').change();

                // Show toast message if no medical history is available
                if (!hasMedicalHistory) {
                    toastr.warning('No medical histories available for the selected patient.');
                }
            });
        });
    </script>
@endpush

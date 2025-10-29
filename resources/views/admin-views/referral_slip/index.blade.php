@extends('layouts.admin.app')

@section('title', translate('Add Referral Form'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/assetsadmin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assetsadmin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('Add New Referral Form') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="javascript:" method="post" id="referral_form" enctype="multipart/form-data">
                    @csrf
                    <div id="form_part_2">
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
                                    <!-- From Department -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('From Department') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="text" name="from_department" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- To Department -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('To Department') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="text" name="to_department" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="date">{{ \App\CentralLogics\translate('Date') }}</label>
                                            <input type="date" name="date" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- Clinical Finding -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="clinical_finding">{{ \App\CentralLogics\translate('Clinical Finding') }}</label>
                                            <textarea name="clinical_finding" class="form-control" rows="3" required></textarea>
                                        </div>
                                    </div>

                                    <!-- Diagnosis -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="diagnosis">{{ \App\CentralLogics\translate('Diagnosis') }}</label>
                                            <textarea type="text" name="diagnosis" rows="3" class="form-control" required></textarea>
                                        </div>
                                    </div>

                                    <!-- Reasons for Referral -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="reasons_for_referral">{{ \App\CentralLogics\translate('Reasons for Referral') }}</label>
                                            <textarea name="reasons_for_referral" class="form-control" rows="3" required></textarea>
                                        </div>
                                    </div>

                                    <!-- Referred By -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="referred_by">{{ \App\CentralLogics\translate('Referred By') }}</label>
                                            <input type="text" name="referred_by" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- Investigation Result -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="investigation_result">{{ \App\CentralLogics\translate('Investigation Result') }}</label>
                                            <textarea name="investigation_result" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <!-- Rx Given -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="rx_given">{{ \App\CentralLogics\translate('Rx Given') }}</label>
                                            <textarea name="rx_given" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <!-- Finding -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="finding">{{ \App\CentralLogics\translate('Finding') }}</label>
                                            <textarea name="finding" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <!-- Treatment Given -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="treatment_given">{{ \App\CentralLogics\translate('Treatment Given') }}</label>
                                            <textarea name="treatment_given" class="form-control" rows="3"></textarea>
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

@push('script')
@endpush

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
    <script>
        $('#referral_form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.referral_slip.store') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.errors) {
                        data.errors.forEach(error => toastr.error(error.message, {
                            CloseButton: true,
                            ProgressBar: true
                        }));
                    } else {
                        toastr.success('{{ translate('Referral Form Saved successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(() => location.href = '{{ route('admin.referral_slip.list') }}',
                            2000);
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
                }
            });
        });
    </script>

    <!-- Additional scripts as needed -->
@endpush

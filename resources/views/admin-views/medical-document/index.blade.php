@extends('layouts.admin.app')

@section('title', translate('Add Medical Document'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ \App\CentralLogics\translate('Add New Medical Document') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="javascript:" method="post" id="consent_form" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row">
                                {{-- type selection --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Type') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="type" id="type-select" class="form-control js-select2-custom"
                                            required>
                                            <option value="" selected disabled>Select a type</option>
                                            <option value="abortion">Abortion Form</option>
                                            <option value="consent">Consent Form</option>
                                            <option value="certification">Medical Certification</option>
                                            <option value="examination">Medical Examination</option>
                                            <option value="referal">Patient Referral</option>
                                            <option value="circumcision ">Circumcision </option>

                                        </select>
                                    </div>
                                </div>
                                <!-- Patient Selection -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Patient') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="patient_id" id="patient-select" class="form-control js-select2-custom"
                                            required>
                                            <option value="" selected disabled>Select a patient</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Select the Visit') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="visit_id" class="form-control js-select2-custom"
                                            id="medical-history-select" required>
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('please select patient first') }}</option>
                                            {{-- @foreach ($visits as $visit)
                                                <option value="{{ $visit->id }}"
                                                    data-patient-id="{{ $visit->patient->id }}">
                                                    {{ $visit->patient->full_name }} (Dr.
                                                    {{ $visit->doctor ? $visit->doctor->full_name : '---' }})
                                                </option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>

                                <!-- Common Fields - Always Visible -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Date') }}</label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Form Language') }}</label>
                                        <select name="language" class="form-control js-select2-custom" required>
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('Select form language') }}</option>
                                            <option value="amharic">Amharic</option>
                                            <option value="english" selected>English</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Notes') }}</label>
                                        <textarea name="notes" class="form-control" rows="1"></textarea>
                                    </div>
                                </div>

                                <!-- Consent Form Specific Fields -->
                                <div id="consent-fields" class="form-section col-12 row" style="display: none;">
                                    <div class="col-12 text-center">
                                        <h4 class="mb-3">{{ translate('Consent Form Details') }}</h4>
                                    </div>
                                    <!-- Witness 1 -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Witness 1 Name') }}</label>
                                            <input type="text" name="witness_1_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Witness 1 Relationship') }}</label>
                                            <input type="text" name="witness_1_relationship" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Witness 2 -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Witness 2 Name') }}</label>
                                            <input type="text" name="witness_2_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Witness 2 Relationship') }}</label>
                                            <input type="text" name="witness_2_relationship" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Certification Form Specific Fields -->
                                <div id="certification-fields" class="form-section col-12 row" style="display: none;">
                                    <div class="col-12 text-center">
                                        <h4 class="mb-3">{{ translate('Certification Form Details') }}</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Recommendation') }}</label>
                                            <input type="text" name="diagnosis" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Date of Rest') }}</label>
                                            <input type="number" name="date_of_rest" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Examination Form Specific Fields -->
                                <div id="examination-fields" class="form-section col-12 row" style="display: none;">
                                    <div class="col-12 text-center">
                                        <h4 class="mb-3">{{ translate('Examination Form Details') }}</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('To') }}</label>
                                            <input type="text" name="to" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Number') }}</label>
                                            <input type="text" name="number" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Referral Form Specific Fields -->
                                <div id="referal-fields" class="form-section col-12 row" style="display: none;">
                                    <div class="col-12 text-center">
                                        <h4 class="mb-3">{{ translate('Referral Form Details') }}</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('From Hospital') }}</label>
                                            <input type="text" name="from_hospital" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('To Hospital') }}</label>
                                            <input type="text" name="to_hospital" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('From Department') }}</label>
                                            <input type="text" name="from_department" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('To Department') }}</label>
                                            <input type="text" name="to_department" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Clinical Findings') }}</label>
                                            <textarea name="clinical_findings" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Diagnosis') }}</label>
                                            <textarea name="dignosis" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Rx Given') }}</label>
                                            <textarea name="rx_given" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Reason') }}</label>
                                            <textarea name="reason" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Laboratory Request Form Specific Fields -->
                                <div id="laboratory-request-fields" class="form-section" style="display: none;">
                                    <div class="col-12">
                                        {{-- <h4 class="mb-3">{{ translate('Laboratory Request Details') }}</h4> --}}
                                    </div>
                                    <!-- Add laboratory request specific fields here if needed -->
                                </div>

                                <!-- Abortion Form Specific Fields -->
                                <div id="abortion-fields" class="form-section" style="display: none;">
                                    <div class="col-12">
                                        {{-- <h4 class="mb-3">{{ translate('Abortion Form Details') }}</h4> --}}
                                    </div>
                                    <!-- Add abortion form specific fields here if needed -->
                                </div>

                                <!-- circumcision Form Specific Fields -->
                                <div id="circumcision-fields" class="form-section" style="display: none;">
                                    <div class="col-12">
                                        {{-- <h4 class="mb-3">{{ translate('circumcision Form Details') }}</h4> --}}
                                    </div>
                                    <!-- Add circumcision form specific fields here if needed -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
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
            $.HSCore.components.HSSelect2.init($('#patient-select'), {
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
            $('#patient-select').on('change', function() {
                var patientId = $(this).val();
                var $visitSelect = $('#medical-history-select');
                $visitSelect.empty().append('<option value="" selected disabled>Select a visit</option>');

                if (patientId) {
                    $.ajax({
                        url: '/admin/appointment/get-patient-visits/' + patientId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(visits) {
                            if (visits.length > 0) {
                                visits.forEach(function(visit) {
                                    var doctorName = visit.doctor_name || '---';
                                    var visitDate = visit.visit_datetime || '';
                                    $visitSelect.append(
                                        `<option value="${visit.id}">${visit.patient_full_name} (Dr. ${doctorName}) - ${visitDate}</option>`
                                    );
                                });
                            } else {
                                toastr.warning('No visits found for this patient.');
                            }
                        },
                        error: function() {
                            toastr.error('Failed to fetch visits.');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        // Show/hide form fields based on selected type
        $(document).ready(function() {
            // Remove required attribute from all fields initially
            $('.form-section input, .form-section textarea').prop('required', false);

            $('#type-select').change(function() {
                var selectedType = $(this).val();

                // Hide all type-specific sections first
                $('.form-section').hide();

                // Remove required attribute from all fields
                $('.form-section input, .form-section textarea').prop('required', false);

                // Show the section for the selected type
                if (selectedType) {
                    $('#' + selectedType + '-fields').show();

                    // Add required attribute to visible fields
                    $('#' + selectedType + '-fields input, #' + selectedType + '-fields textarea').prop(
                        'required', true);
                }
            });
        });
    </script>
    <script>
        $('#consent_form').on('submit', function(e) {
            e.preventDefault(); // Prevent page reload

            var formData = new FormData(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post({
                url: '{{ route('admin.medical_document.store') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.success('{{ translate('Medical Document saved successfully!') }}');
                    setTimeout(() => {
                        location.href = '{{ route('admin.medical_document.list') }}';
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
@endpush

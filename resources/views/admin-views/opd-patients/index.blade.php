@extends('layouts.admin.app')

@section('title', translate('Add New Visit'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/visit.png') }}" alt="">
                {{ translate('Add New Visit') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.visit.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Patient') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="patient_id" class="form-control js-select2-custom" required>
                                            <option selected disabled>Select patient</option>
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Doctor') }}</label>
                                        <select name="doctor_id" class="form-control js-select2-custom">
                                            <option selected disabled>Select doctor</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Appointment') }}</label>
                                        <select name="appointment_id" class="form-control js-select2-custom">
                                            <option selected disabled>Select appointment</option>
                                            @foreach ($appointments as $appointment)
                                                <option value="{{ $appointment->id }}">
                                                    {{ $appointment->appointment_date }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Visit Type -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Visit Type') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="visit_type" id="visit_type" class="form-control js-select2-custom"
                                            required>
                                            <option value="" selected disabled>Select visit type</option>
                                            <option value="IPD">IPD</option>
                                            <option value="OPD">OPD</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- IPD Fields -->
                                <div id="ipd_fields" class="row d-none">
                                    <div class="col-md-4">
                                        <label class="input-label">Ward<span class="text-danger">*</span></label>
                                        <select name="ward_id" class="form-control js-select2-custom">
                                            @foreach ($wards as $ward)
                                                <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label">Bed<span class="text-danger">*</span></label>
                                        <select name="bed_id" class="form-control js-select2-custom">
                                            @foreach ($beds as $bed)
                                                <option value="{{ $bed->id }}">{{ $bed->bed_number }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label">Admitting Doctor<span
                                                class="text-danger">*</span></label>
                                        <select name="admitting_doctor_id" class="form-control js-select2-custom">
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label">Admission Date<span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="admission_date" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Visit Date and Time') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="datetime-local" name="visit_datetime" class="form-control" required
                                            id="visit_datetime">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Notes') }}</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="Enter visit notes"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
    <script>
        let now = new Date();
        let year = now.getFullYear();
        let month = (now.getMonth() + 1).toString().padStart(2, '0');
        let day = now.getDate().toString().padStart(2, '0');
        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');

        document.getElementById('visit_datetime').value = `${year}-${month}-${day}T${hours}:${minutes}`;
    </script>
    <script>
        $('#visit_type').change(function() {
            if ($(this).val() === 'IPD') {
                $('#ipd_fields').removeClass('d-none');
                $('#opd_fields').addClass('d-none');
            } else if ($(this).val() === 'OPD') {
                $('#opd_fields').removeClass('d-none');
                $('#ipd_fields').addClass('d-none');
            } else {
                $('#ipd_fields, #opd_fields').addClass('d-none');
            }
        });
    </script>
@endpush

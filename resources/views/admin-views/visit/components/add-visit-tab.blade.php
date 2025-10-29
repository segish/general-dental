<!-- Add New Visit Tab Content -->
<div class="tab-pane fade  {{ request()->get('active') == 'add-visit' ? 'show active' : '' }}" id="add-visit" role="tabpanel" aria-labelledby="add-visit-tab">
    <form action="{{ route('admin.visit.store') }}" method="post" id="visit_form">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="input-label">{{ translate('Patient') }}<span
                            class="input-label-secondary text-danger">*</span></label>
                    <div class="input-group d-flex flex-nowrap">
                        <select name="patient_id" id="patient-select" class="form-control js-select2-custom" required>
                            <option value="" selected disabled>Select a patient</option>
                        </select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#addPatientModal">
                                <i class="tio-add"></i>new
                            </button>
                        </div>
                    </div>
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
                            <option value="{{ $appointment->id }}"
                                data-patient-id="{{ $appointment->patient->id ?? '' }}"
                                data-doctor-id="{{ $appointment->doctor->id ?? '' }}"
                                data-date="{{ $appointment->date }}">
                                {{ $appointment->date }} -
                                {{ $appointment->patient->full_name ?? 'No Patient' }} with
                                Dr. {{ $appointment->doctor->full_name ?? 'No Doctor' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="input-label">{{ translate('Visit Service Category') }}<span
                            class="text-danger">*</span></label>
                    <select name="service_category_id" id="service_category_id" class="form-control js-select2-custom"
                        required>
                        <option value="" selected disabled>
                            {{ \App\CentralLogics\translate('Select service category') }}</option>
                        @foreach ($serviceCategories as $serviceCategory)
                            <option value="{{ $serviceCategory->id }}">{{ $serviceCategory->name }}
                            </option>
                        @endforeach
                    </select>
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
            <!-- Visit Type -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="input-label">{{ translate('Visit Type') }}<span class="text-danger">*</span></label>
                    <select name="visit_type" id="visit_type" class="form-control js-select2-custom" required>
                        <option value="" selected disabled>Select visit type</option>
                        <option value="OPD" selected>OPD</option>
                        <option value="IPD">IPD</option>
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
                    <label class="input-label">Admitting Doctor<span class="text-danger">*</span></label>
                    <select name="admitting_doctor_id" class="form-control js-select2-custom">
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="input-label">Admission Date<span class="text-danger">*</span></label>
                    <input type="datetime-local" id="admission_date" name="admission_date" class="form-control">
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
            <button type="submit" class="btn btn-primary"
                id="visit_submit_button">{{ translate('Submit') }}</button>
        </div>
    </form>
</div>

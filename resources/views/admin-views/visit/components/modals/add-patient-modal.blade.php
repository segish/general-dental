<!-- Add Patient Modal -->
<div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog" aria-labelledby="addPatientModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPatientModalLabel">{{ translate('Add New Patient') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="patient_modal_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="modal_full_name">{{ translate('Full Name') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="full_name" id="modal_full_name" class="form-control"
                                    placeholder="{{ translate('Ex : JOHN') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="modal_gender">{{ translate('Gender') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="gender" id="modal_gender" class="form-control js-select2-custom"
                                    required>
                                    <option value="" selected disabled>{{ translate('Select Gender') }}</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="modal_phone">{{ translate('Phone Number') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="phone" id="modal_phone" class="form-control"
                                    placeholder="{{ translate('Ex : 09xxxxxxxx') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="modal_date_of_birth">{{ translate('Date of Birth') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="date" class="form-control" name="date_of_birth" id="modal_date_of_birth"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="modal_age_years">{{ translate('Years') }}</label>
                                <input type="number" name="age_years" id="modal_age_years" class="form-control"
                                    placeholder="{{ translate('Ex : 1') }}" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="modal_age_months">{{ translate('Months') }}</label>
                                <input type="number" name="age_months" id="modal_age_months" class="form-control"
                                    placeholder="{{ translate('Ex : 3') }}" min="0" max="11"
                                    value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="modal_age_days">{{ translate('Days') }}</label>
                                <input type="number" name="age_days" id="modal_age_days" class="form-control"
                                    placeholder="{{ translate('Ex : 15') }}" min="0" max="30"
                                    value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="modal_blood_group">{{ translate('Blood Group') }}</label>
                                <select name="blood_group" id="modal_blood_group"
                                    class="form-control js-select2-custom">
                                    <option value="" selected disabled>{{ translate('Blood Group') }}</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="modal_marital_status">{{ translate('Marital Status') }}</label>
                                <select name="marital_status" id="modal_marital_status"
                                    class="form-control js-select2-custom">
                                    <option value="" selected disabled>{{ translate('Select Marital Status') }}
                                    </option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="modal_registration_date">{{ translate('Registration Date') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="date" class="form-control" name="registration_date"
                                    id="modal_registration_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="modal_address">{{ translate('Address') }}</label>
                                <input type="text" name="address" id="modal_address" class="form-control"
                                    placeholder="Addis Ababa">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="modal_email">{{ translate('Email') }}</label>
                                <input type="text" name="email" id="modal_email" class="form-control"
                                    placeholder="{{ translate('Ex : example@gmail.com') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="modal_is_flexible_payment">{{ translate('Flexible Payment Allowed') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="is_flexible_payment" id="modal_is_flexible_payment"
                                    class="form-control js-select2-custom" required>
                                    <option value="" selected disabled>
                                        {{ translate('Select payment flexibility') }}</option>
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ translate('Close') }}</button>
                <button type="button" class="btn btn-primary"
                    id="modal_patient_submit_button">{{ translate('Add Patient') }}</button>
            </div>
        </div>
    </div>
</div>

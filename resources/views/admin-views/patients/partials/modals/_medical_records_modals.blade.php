<div class="modal fade" id="add-nurse_assessment_test" tabindex="-1" role="dialog" aria-labelledby="nurseAssessmentLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nurseAssessmentLabel">{{ translate('Add Vital Signs') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="nurseAssessmentForm">
                <div class="modal-body">
                    <input type="hidden" name="visit_id" id="visit_id">
                    <input type="text" hidden name="nurse_id" value="{{ auth('admin')->user()->id }}">

                    <!-- Dynamically Generated Vital Signs Inputs -->
                    <div id="vitalSignsFields" class="d-flex flex-wrap"></div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label for="notes">{{ translate('Notes') }}</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save Assessment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Nurse Assessment Modal -->
<div class="modal fade" id="edit-nurse_assessment_test" tabindex="-1" role="dialog"
    aria-labelledby="editNurseAssessmentLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNurseAssessmentLabel">{{ translate('Edit Vital Sign') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editNurseAssessmentForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="assessment_id" id="edit_assessment_id">
                    <input type="hidden" name="visit_id" id="edit_visit_id">
                    <input type="hidden" name="nurse_id" value="{{ auth('admin')->user()->id }}">

                    <div class="form-group">
                        <label for="edit_test_name">{{ translate('Vital Sign') }}</label>
                        <input type="text" class="form-control" id="edit_test_name" readonly>
                    </div>

                    <div class="form-group">
                        <label for="edit_test_value">{{ translate('Value') }}</label>
                        <input type="text" class="form-control" name="test_value" id="edit_test_value" required>
                        <input type="hidden" name="unit_name" id="edit_unit_name">
                    </div>

                    <div class="form-group">
                        <label for="edit_notes">{{ translate('Notes') }}</label>
                        <textarea class="form-control" name="notes" id="edit_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Update Assessment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="add-medical-record" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add New Medical Record') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="medical_test_form" method="post">
                    <input type="text" hidden name="visit_id">
                    <input type="text" hidden name="doctor_id" value="{{ auth('admin')->user()->id }}">

                    @csrf

                    @if (isset($medicalRecordFields) && $medicalRecordFields->count() > 0)
                        @include('admin-views.patients.partials.dynamic-medical-record-fields', [
                            'values' => [],
                        ])
                    @else
                        <div class="alert alert-info">
                            {{ translate('No medical record fields defined. Please add fields first.') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editMedicalRecordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_medical_record_form" method="POST" action="javascript:">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Edit Medical Record') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body" id="edit-medical-record-fields">
                    @if (isset($medicalRecordFields) && $medicalRecordFields->count() > 0)
                        @include('admin-views.patients.partials.dynamic-medical-record-fields', [
                            'values' => [],
                        ])
                    @else
                        <div class="alert alert-info">
                            {{ translate('No medical record fields defined.') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="add-diagnosis-treatment" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Diagnosis & Treatment') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="diagnosis_treatment_form">
                    @csrf
                    <input type="text" hidden name="visit_id">
                    <input type="text" hidden name="doctor_id" value="{{ auth('admin')->user()->id }}">

                    <div class="form-group">
                        <label class="input-label">{{ translate('Diagnosis') }}<span
                                class="text-danger">*</span></label>
                        <textarea name="diagnosis" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ translate('Select Diseases') }}
                        </label>
                        <select name="condition_ids[]" id="condition_ids" class="form-control js-select2-custom"
                            multiple>
                            @foreach ($conditions as $condition)
                                <option value="{{ $condition->id }}">
                                    {{ $condition->name }}({{ $condition->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ translate('Treatment') }}</label>
                        <textarea name="treatment" class="form-control"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Diagnosis & Treatment Modal -->
<div class="modal fade" id="editDiagnosisTreatmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_diagnosis_treatment_form" method="POST" action="javascript:">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Edit Diagnosis & Treatment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ translate('Diagnosis') }}</label>
                        <textarea name="diagnosis" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Treatment') }}</label>
                        <textarea name="treatment" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Diseases') }}</label>
                        <select name="condition_ids[]" class="form-control js-select2-custom" multiple>
                            @foreach ($diseases as $disease)
                                <option value="{{ $disease->id }}">{{ $disease->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pregnancyModal" tabindex="-1">
    <div class="modal-dialog modal-xl"> <!-- Made it extra large -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pregnancyModalLabel">{{ translate('Add Pregnancy Record') }}</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <form id="pregnancyForm">
                    @csrf
                    @if (isset($pregnancy_edit))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="_method" value="POST" id="formMethod">
                    <input type="hidden" name="patient_id" value="{{ $patient->id ?? '' }}">
                    <input type="hidden" name="doctor_id" value="{{ auth('admin')->id() }}">
                    <input type="hidden" name="visit_id" id="visit_id" value="{{ old('visit_id', $pregnancy_edit->visit_id ?? '') }}">

                    <div class="row">
                        <!-- Core Info -->
                        <div class="col-md-4 form-group">
                            <label>{{ translate('ANC Registration No') }}</label>
                            <input type="text" name="anc_reg_no" class="form-control"
                                value="{{ old('anc_reg_no', $pregnancy_edit->anc_reg_no ?? '') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{ translate('LMP') }}</label>
                            <input type="date" name="lmp" class="form-control"
                                value="{{ old('lmp', $pregnancy_edit->lmp ?? '') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{ translate('EDD') }}</label>
                            <input type="date" name="edd" class="form-control" 
                                value="{{ old('edd', $pregnancy_edit->edd ?? '') }}">
                        </div>

                        <div class="col-md-4 form-group">
                            <label>{{ translate('Gravida') }}</label>
                            <input type="number" name="gravida" class="form-control"
                                value="{{ old('gravida', $pregnancy_edit->gravida ?? '') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{ translate('Para') }}</label>
                            <input type="number" name="para" class="form-control"
                                value="{{ old('para', $pregnancy_edit->para ?? '') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{ translate('Alive Children') }}</label>
                            <input type="number" name="children_alive" class="form-control"
                                value="{{ old('children_alive', $pregnancy_edit->children_alive ?? '') }}">
                        </div>
                        <!-- Marital Status and Pregnancy Status -->
                        <div class="col-md-6 form-group">
                            <label>{{ translate('Marital Status') }}<span
                                    class="input-label-secondary text-danger">*</span></label>
                            <select name="marital_status" class="form-control js-select2-custom">
                                <option value="">{{ translate('Select Status') }}</option>
                                @foreach (['single', 'married', 'divorced', 'widowed', 'Prefer not to say'] as $status)
                                    <option value="{{ $status }}"
                                        {{ old('marital_status', $pregnancy_edit->marital_status ?? '') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>{{ translate('Pregnancy Status') }}</label>
                            <select name="status" class="form-control js-select2-custom" required>
                                @foreach (['ongoing', 'completed', 'aborted'] as $status)
                                    <option value="{{ $status }}"
                                        {{ old('status', $pregnancy_edit->status ?? '') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Risk Factor Checkboxes -->
                        @php
                            $checkboxes = [
                                'is_high_risk' => 'High Risk Pregnancy',
                                'previous_stillbirth_or_neonatal_loss' => 'Previous Stillbirth/Neonatal Loss',
                                'hypertension_in_last_pregnancy' => 'Hypertension in Last Pregnancy',
                                'reproductive_tract_surgery' => 'Reproductive Tract Surgery',
                                'multiple_pregnancy' => 'Multiple Pregnancy',
                                'rh_issue' => 'Rh Issue',
                                'vaginal_bleeding' => 'Vaginal Bleeding',
                                'pelvic_mass' => 'Pelvic Mass',
                                'diabetes' => 'Diabetes',
                                'renal_disease' => 'Renal Disease',
                                'cardiac_disease' => 'Cardiac Disease',
                                'chronic_hypertension' => 'Chronic Hypertension',
                                'substance_abuse' => 'Substance Abuse',
                            ];
                        @endphp
                        @foreach ($checkboxes as $field => $label)
                            <div class="col-md-4 form-group">
                                <label>
                                    <input type="checkbox" name="{{ $field }}" value="1"
                                        {{ old($field, $pregnancy_edit->$field ?? false) ? 'checked' : '' }}>
                                    {{ translate($label) }}
                                </label>
                            </div>
                        @endforeach

                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>{{ translate('Spontaneous Abortions Count') }}</label>
                            <input type="number" name="spontaneous_abortions_count" class="form-control"
                                value="{{ old('spontaneous_abortions_count', $pregnancy_edit->spontaneous_abortions_count ?? '') }}">
                        </div>

                        <div class="col-md-4 form-group">
                            <label>{{ translate('Last Birth Weight (kg)') }}</label>
                            <input type="number" step="0.01" name="last_birth_weight_kg" class="form-control"
                                value="{{ old('last_birth_weight_kg', $pregnancy_edit->last_birth_weight_kg ?? '') }}">
                        </div>

                        <div class="col-md-4 form-group">
                            <label>{{ translate('Booking BP (Diastolic)') }}</label>
                            <input type="number" name="booking_bp_diastolic" class="form-control"
                                value="{{ old('booking_bp_diastolic', $pregnancy_edit->booking_bp_diastolic ?? '') }}">
                        </div>

                        <!-- Serious Medical Disease and Remark -->
                        <div class="col-md-6 form-group">
                            <label>{{ translate('Serious Medical Disease (if any)') }}</label>
                            <textarea name="serious_medical_disease" class="form-control" rows="3">{{ old('serious_medical_disease', $pregnancy_edit->serious_medical_disease ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>{{ translate('Remark') }}</label>
                            <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $pregnancy_edit->remarks ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($pregnancy_edit) ? translate('Update') : translate('Submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Prenatal Visit Modal -->
<div class="modal fade" id="prenatalVisitModal" tabindex="-1" aria-labelledby="prenatalVisitModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="prenatalVisitForm">
                @csrf
                <input type="hidden" name="id" id="prenatal_visit_id">
                <input type="hidden" name="pregnancy_id" id="pregnancy_id" value="{{ $pregnancy->id ?? '' }}">
                <input type="text" hidden name="visit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="prenatalVisitModalLabel">Prenatal Visit Follow Up</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>Gestational Age</label>
                            <input type="number" name="gestational_age" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Blood Pressure (BP)</label>
                            <input type="text" name="bp" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Pallor</label>
                            <input type="text" name="pallor" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Uterine Height</label>
                            <input type="text" name="uterine_height" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Fetal Heart Beat</label>
                            <input type="text" name="fetal_heart_beat" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Presentation</label>
                            <input type="text" name="presentation" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Urine Infection</label>
                            <input type="text" name="urine_infection" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Urine Protein</label>
                            <input type="text" name="urine_protein" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Rapid Syphilis Test</label>
                            <input type="text" name="rapid_syphilis_test" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Hemoglobin</label>
                            <input type="text" name="hemoglobin" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Blood Group & RH</label>
                            <input type="text" name="blood_group_rh" class="form-control">
                        </div>

                        @php
                            $checkboxes = [
                                'iron_folic_acid' => 'Iron Folic Acid',
                                'mebendazole' => 'Mebendazole',
                                'tin_use' => 'TIN Use',
                            ];
                        @endphp
                        @foreach ($checkboxes as $field => $label)
                            <div class="col-md-4 form-group">
                                <label>
                                    <input type="checkbox" name="{{ $field }}" value="1"
                                        {{ old($field, $pregnancy->$field ?? false) ? 'checked' : '' }}>
                                    {{ translate($label) }}
                                </label>
                            </div>
                        @endforeach

                        <div class="col-md-4">
                            <label>TT Dose</label>
                            <input type="text" name="tt_dose" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>ARV Px Type</label>
                            <input type="text" name="arv_px_type" class="form-control">
                        </div>

                        <div class="col-md-4 mt-2">
                            <label>Next Follow-up Date</label>
                            <input type="date" name="next_follow_up" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Danger Signs</label>
                            <textarea name="danger_signs" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label>Action/Advice/Counseling</label>
                            <textarea name="action_advice_counseling" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="prenatalVisitHistoryModal" tabindex="-1" role="dialog"
    aria-labelledby="prenatalVisitHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="prenatalVisitHistoryForm">
            @csrf
            <input type="hidden" name="id" id="history_sheet_id">
            <input type="text" hidden name="visit_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="prenatalVisitHistoryModalLabel">Prenatal Visit Follow Up History
                        Sheet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="history">History</label>
                        <textarea class="form-control" name="history" id="history" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="physical_findings">Physical Findings</label>
                        <textarea class="form-control" name="physical_findings" id="physical_findings" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="progress_notes">Progress Notes</label>
                        <textarea class="form-control" name="progress_notes" id="progress_notes" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="savePrenatalVisitHistoryBtn" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Delivery Summary Modal -->
<div class="modal fade" id="delivery-summary-modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delivery-summary-modal-title">Add Delivery Summary</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <form id="delivery-summary-form">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="delivery_summary_id" id="delivery_summary_id">
                    <input type="hidden" name="pregnancy_id" value="{{ $pregnancy->id ?? '' }}">
                    <input type="text" hidden name="visit_id">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Delivered By (User)</label>
                            <select name="delivered_by" class="form-control" id="delivered_by">
                                <option selected disabled>Select</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Delivery Date</label>
                            <input type="date" name="date" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Delivery Time</label>
                            <input type="time" name="time" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Delivery Mode</label>
                            <select name="delivery_mode" class="form-control">
                                <option value="">Select</option>
                                <option>SVD</option>
                                <option>SVD Vacuum</option>
                                <option>'SVD Forceps</option>
                                <option>C-Section</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Placenta</label>
                            <select name="placenta" class="form-control">
                                <option value="">Select</option>
                                <option>Completed</option>
                                <option>Incomplete</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>CCT</label>
                            <input type="text" name="cct" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="form-check col-md-3">
                            <input type="checkbox" class="form-check-input" name="mrp" value="1">
                            <label class="form-check-label">MRP</label>
                        </div>

                        <div class="form-check col-md-3">
                            <input type="checkbox" class="form-check-input" name="laceration_repair" value="1">
                            <label class="form-check-label">Laceration Repair</label>
                        </div>
                        <div class="form-check col-md-3">
                            <input type="checkbox" class="form-check-input" name="misoprostol" value="1">
                            <label class="form-check-label">Misoprostol</label>
                        </div>

                        <div class="form-check col-md-3">
                            <input type="checkbox" class="form-check-input" name="episiotomy" value="1">
                            <label class="form-check-label">Episiotomy</label>
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="form-group col-md-4">
                            <label>Laceration Degree</label>
                            <select name="laceration_degree" class="form-control">
                                <option value="">Select</option>
                                <option>1st Degree</option>
                                <option>2nd Degree</option>
                                <option>3rd Degree</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>AMSTL</label>
                            <select name="amstl" class="form-control">
                                <option value="">Select</option>
                                <option>Ergometrine</option>
                                <option>Oxytocine</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Newborn Type</label>
                            <select name="newborn_type" class="form-control">
                                <option value="">Select</option>
                                <option>Single</option>
                                <option>Multiple</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="form-group col-md-4">
                            <label>Apgar Score</label>
                            <input type="text" name="apgar_score" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Delivery Outcome</label>
                            <select name="delivery_outcome" class="form-control">
                                <option value="">Select</option>
                                <option>Alive</option>
                                <option>Stillbirth</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Stillbirth Type</label>
                            <select name="stillbirth_type" class="form-control">
                                <option value="">Select</option>
                                <option>Macerated</option>
                                <option>Fresh</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-row">

                        <div class="form-group col-md-3">
                            <label>Term Status</label>
                            <select name="term_status" class="form-control">
                                <option value="">Select</option>
                                <option>Term</option>
                                <option>Preterm</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Obstetric Complication</label>
                            <select name="obstetric_complication" class="form-control">
                                <option value="">Select</option>
                                <option>Eclampsia</option>
                                <option>PPH</option>
                                <option>APH</option>
                                <option>PROM/Sepsis</option>
                                <option>Obstructed/Prolonged Labor</option>
                                <option>Ruptured Uterus</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Obstetric Management Status</label>
                            <select name="obstetric_management_status" class="form-control">
                                <option value="">Select</option>
                                <option>Managed</option>
                                <option>Referred</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Feeding Option</label>
                            <select name="feeding_option" class="form-control">
                                <option value="">Select</option>
                                <option>EBF</option>
                                <option>RF</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-check col-md-4"><input type="checkbox" name="ruptured_uterus_repaired"
                                value="1"> Ruptured Uterus Repaired</div>
                        <div class="form-check col-md-4"><input type="checkbox" name="hysterectomy" value="1">
                            Hysterectomy</div>
                        <div class="form-check col-md-4"><input type="checkbox" name="referred_for_support"
                                value="1">
                            Referred for Support</div>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary" id="delivery-summary-submit-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Newborn Modal -->
<div class="modal fade" id="newborn-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Newborn Record') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <form id="newborn-form">
                @csrf
                <input type="hidden" name="id" id="newborn-id">
                <input type="hidden" name="delivery_summary_id" id="delivery_summary_id"
                    value="{{ $pregnancy->deliverySummary->id ?? '' }}">

                <div class="modal-body row">
                    @php
                        $fields = [
                            ['name' => 'name', 'type' => 'text'],
                            ['name' => 'prom_hours', 'type' => 'number'],
                            ['name' => 'birth_weight', 'type' => 'number', 'step' => '0.01'],
                            ['name' => 'temp', 'type' => 'number', 'step' => '0.01'],
                            ['name' => 'pr', 'type' => 'number'],
                            ['name' => 'rr', 'type' => 'number'],
                        ];
                    @endphp

                    @foreach ($fields as $field)
                        <div class="form-group col-md-3">
                            <label>{{ ucfirst(str_replace('_', ' ', $field['name'])) }}</label>
                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                                id="{{ $field['name'] }}" class="form-control"
                                @if (isset($field['step'])) step="{{ $field['step'] }}" @endif>
                        </div>
                    @endforeach
                    <div class="form-group col-md-3">
                        <label>APGAR Score</label>
                        <input type="text" name="apgar_score" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>BCG Date</label>
                        <input type="date" name="bcg_date" class="form-control">
                    </div>

                    <div class="form-check col-md-3"><input type="checkbox" name="polio_0" value="1">
                        Polio 0
                    </div>
                    <div class="form-check col-md-3"><input type="checkbox" name="vit_k" value="1">
                        Vitamin K
                    </div>
                    <div class="form-check col-md-3"><input type="checkbox" name="ttc" value="1">
                        TTC</div>
                    <div class="form-check col-md-3"><input type="checkbox" name="baby_mother_bonding"
                            value="1">
                        Baby-Mother Bonding</div>

                    <div class="form-group col-md-4">
                        <label>Sex</label>
                        <select name="sex" class="form-control">
                            <option value="">Select</option>
                            <option>male</option>
                            <option>female</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Length (cm)</label>
                        <input type="number" step="0.01" name="length_cm" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Head Circumference (cm)</label>
                        <input type="number" step="0.01" name="head_circumference_cm" class="form-control">
                    </div>
                    <div class="form-check col-md-6"><input type="checkbox" name="hiv_counts_and_testing_offered"
                            value="1"> HIV Counts and Testing Offered
                    </div>
                    <div class="form-check col-md-6"><input type="checkbox" name="hiv_testing_accepted"
                            value="1">
                        HIV Testing Accepted</div>
                    <div class="form-group col-md-3">
                        <label>HIV Test Result</label>
                        <select name="hiv_test_result" id="hiv_test_result" class="form-control">
                            <option value="">Select</option>
                            @foreach (['Positive', 'Negative', 'Unknown'] as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3"><label>ARV Px Mother</label><input type="text"
                            name="arv_px_mother" class="form-control"></div>
                    <div class="form-group col-md-3"><label>ARV Px Newborn</label><input type="text"
                            name="arv_px_newborn" class="form-control"></div>


                    <div class="form-group col-md-3">
                        <label>Para</label>
                        <select name="para" id="para" class="form-control">
                            <option value="">Select</option>
                            @for ($i = 0; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    @foreach (['prom', 'resuscitated', 'dysmorphic_faces'] as $checkbox)
                        <div class="form-group col-md-4">
                            <label>
                                <input type="checkbox" name="{{ $checkbox }}" id="{{ $checkbox }}"
                                    value="1">
                                {{ ucfirst(str_replace('_', ' ', $checkbox)) }}
                            </label>
                        </div>
                    @endforeach

                    <div class="form-group col-md-6">
                        <label>Neonatal Evaluation</label>
                        <textarea name="neonatal_evaluation" id="neonatal_evaluation" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Plan</label>
                        <textarea name="plan" id="plan" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Discharge Modal -->
<div class="modal fade" id="dischargeModal" tabindex="-1" role="dialog" aria-labelledby="dischargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="dischargeForm">
            @csrf
            <input type="hidden" name="visit_id">
            <input type="hidden" name="id" id="discharge_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Discharge Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>Discharge Date</label>
                        <input type="date" name="discharge_date" id="discharge_date" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Discharge Type</label>
                        <select name="discharge_type" id="discharge_type" class="form-control">
                            <option value="">Select</option>
                            <option value="Recovered">Recovered</option>
                            <option value="Referred">Referred</option>
                            <option value="Death">Death</option>
                            <option value="Absconded">Absconded</option>
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Attending Physician</label>
                        <select name="attending_physician" id="attending_physician" class="form-control">
                            <option value="">Select</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Discharge Notes</label>
                        <textarea name="discharge_notes" id="discharge_notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="add-labour_followup_test" tabindex="-1" role="dialog"
    aria-labelledby="labourFollowupLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labourFollowupLabel">{{ translate('Add Labour Folowup') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="labourFollowupForm">
                <div class="modal-body">
                    <input type="hidden" name="visit_id" id="visit_id">
                    <input type="text" hidden name="nurse_id" value="{{ auth('admin')->user()->id }}">

                    <!-- Dynamically Generated Vital Signs Inputs -->
                    <div id="labourFollowupFields" class="d-flex flex-wrap"></div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label for="notes">{{ translate('Notes') }}</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save Followup') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="edit-labour_follwup_test" tabindex="-1" role="dialog"
    aria-labelledby="editLabourFollowupLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabourFollowupLabel">{{ translate('Edit Labour Follow up') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editLabourFollowupForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="assessment_id" id="edit_assessment_id">
                    <input type="hidden" name="visit_id" id="edit_visit_id">
                    <input type="hidden" name="nurse_id" value="{{ auth('admin')->user()->id }}">

                    <div class="form-group">
                        <label for="edit_test_name">{{ translate('Labour Follow up') }}</label>
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
                    <button type="submit" class="btn btn-primary">{{ translate('Update Followup') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

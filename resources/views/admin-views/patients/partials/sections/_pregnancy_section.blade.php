@php
    $currentpregnancy = $visit->pregnancy ?? null;
@endphp
@if (
    (auth('admin')->user()->can('pregnancy.add-new') && $visit->pregnancy) ||
        (auth('admin')->user()->can('prenatal_visit.add-new') && $visit->prenatalVisit) ||
        (auth('admin')->user()->can('prenatal_visit_history.add-new') && $visit->prenatalVisitHistory))

    <!-- Pregnancy & Maternity Care Tabs -->
    <fieldset class="border border-primary mt-4 p-3 rounded">
        <legend class="float-none w-auto px-3 py-1 bg-light border border-primary rounded-sm"
            style="font-weight: bold; font-size: 18px; color:white; background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%)">
            <div class="pr-1">
                Pregnancy & Maternity Care
            </div>
        </legend>

        <div class="p-3">
            <ul class="nav nav-tabs" id="pregnancyTabs" role="tablist">
                @if (auth('admin')->user()->can('pregnancy.add-new') && $visit->pregnancy)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pregnancy-info-tab" data-toggle="tab" href="#pregnancy-info"
                            role="tab" aria-controls="pregnancy-info" aria-selected="true">
                            <i class="tio-heart mr-1"></i>Pregnancy Info
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('prenatal_visit.add-new') && $visit->prenatalVisit)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('pregnancy.add-new') && $visit->pregnancy) ? 'active' : '' }}"
                            id="prenatal-visit-tab" data-toggle="tab" href="#prenatal-visit" role="tab"
                            aria-controls="prenatal-visit"
                            aria-selected="{{ !(auth('admin')->user()->can('pregnancy.add-new') && $visit->pregnancy) ? 'true' : 'false' }}">
                            <i class="tio-calendar mr-1"></i>Follow Up
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('prenatal_visit_history.add-new') && $visit->prenatalVisitHistory)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('pregnancy.add-new') && $visit->pregnancy) && !(auth('admin')->user()->can('prenatal_visit.add-new') && $visit->prenatalVisit) ? 'active' : '' }}"
                            id="prenatal-history-tab" data-toggle="tab" href="#prenatal-history" role="tab"
                            aria-controls="prenatal-history"
                            aria-selected="{{ !(auth('admin')->user()->can('pregnancy.add-new') && $visit->pregnancy) && !(auth('admin')->user()->can('prenatal_visit.add-new') && $visit->prenatalVisit) ? 'true' : 'false' }}">
                            <i class="tio-history mr-1"></i>History Sheet
                        </a>
                    </li>
                @endif
            </ul>

            <div class="tab-content border border-primary rounded-bottom" id="pregnancyTabsContent">
                <!-- Pregnancy Information Tab -->
                @if (auth('admin')->user()->can('pregnancy.add-new') && $visit->pregnancy)
                    <div class="tab-pane fade show active p-3" id="pregnancy-info" role="tabpanel"
                        aria-labelledby="pregnancy-info-tab">
                        <div class="row">
                            <div class="col-md-4 mb-3"><strong>ANC Reg. No:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $currentpregnancy->anc_reg_no ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Status:</strong>
                                <p class="mb-0 text-muted text-capitalize">
                                    {{ $currentpregnancy->status ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Mother Age:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $currentpregnancy->mother_age ?? 'Not Specified' }}
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3"><strong>Gravida:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $currentpregnancy->gravida ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Para:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $currentpregnancy->para ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Children Alive:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $currentpregnancy->children_alive ?? 'Not Specified' }}
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3"><strong>LMP:</strong>
                                <p class="mb-0 text-muted">
                                    {{ optional($currentpregnancy->lmp)->format('d-M-Y') ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>EDD:</strong>
                                <p class="mb-0 text-muted">
                                    {{ optional($currentpregnancy->edd)->format('d-M-Y') ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Last Birth Weight (kg):</strong>
                                <p class="mb-0 text-muted">
                                    {{ $currentpregnancy->last_birth_weight_kg ?? 'Not Specified' }}
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3"><strong>Booking BP Diastolic:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $currentpregnancy->booking_bp_diastolic ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Spontaneous Abortions:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $currentpregnancy->spontaneous_abortions_count ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Marital Status:</strong>
                                <p class="mb-0 text-muted text-capitalize">
                                    {{ $currentpregnancy->marital_status ?? 'Not Specified' }}
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12 mb-2"><strong>Risk Factors:</strong></div>

                            @php
                                $riskFields = [
                                    'is_high_risk' => 'High Risk',
                                    'previous_stillbirth_or_neonatal_loss' => 'Previous Stillbirth or Neonatal Loss',
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

                            @foreach ($riskFields as $field => $label)
                                <div class="col-md-4 mb-2">
                                    <strong>{{ $label }}:</strong>
                                    <p class="mb-0 text-muted">
                                        {{ $currentpregnancy->$field ? 'Yes' : 'No' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        @if ($currentpregnancy->serious_medical_disease)
                            <div class="row mt-2">
                                <div class="col-md-12"><strong>Serious Medical Disease:</strong>
                                    <p class="mb-0 text-muted">
                                        {{ $currentpregnancy->serious_medical_disease }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($currentpregnancy->remarks)
                            <div class="row mt-2">
                                <div class="col-md-12"><strong>Remarks:</strong>
                                    <p class="mb-0 text-muted">
                                        {{ $currentpregnancy->remarks }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if (auth('admin')->user()->can('pregnancy.edit'))
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary btn-sm" id="editPregnancyBtn"
                                    data-pregnancy="{{ $currentpregnancy }}">
                                    <i class="tio-edit"></i>
                                    {{ translate('Edit') }}
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Prenatal Visit Tab -->
                @if (auth('admin')->user()->can('prenatal_visit.add-new') && $visit->prenatalVisit)
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('pregnancy.add-new') && $visit->pregnancy) ? 'show active' : '' }} p-3"
                        id="prenatal-visit" role="tabpanel" aria-labelledby="prenatal-visit-tab">
                        @php
                            $prenatalVisit = $visit->prenatalVisit ?? null;
                        @endphp
                        <div class="row">
                            <div class="col-md-4 mb-3"><strong>Gestational Age (weeks):</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->gestational_age ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Blood Pressure:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->bp ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Weight (kg):</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->weight ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Pallor:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->pallor ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Uterine Height:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->uterine_height ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Fetal Heart Beat:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->fetal_heart_beat ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Presentation:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->presentation ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Urine Infection:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->urine_infection ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Urine Protein:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->urine_protein ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Rapid Syphilis Test:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->rapid_syphilis_test ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Hemoglobin:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->hemoglobin ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Blood Group / Rh:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->blood_group_rh ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>TT Dose:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->tt_dose ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Iron Folic Acid:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->iron_folic_acid ? 'Yes' : 'No' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Mebendazole:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->mebendazole ? 'Yes' : 'No' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>TIN Use:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->tin_use ? 'Yes' : 'No' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>ARV Px Type:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->arv_px_type ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Next Follow-up Date:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->next_follow_up ?? 'Not Scheduled' }}
                                </p>
                            </div>

                            <div class="col-md-12 mb-3"><strong>Remarks:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->remarks ?? 'None' }}
                                </p>
                            </div>

                            <div class="col-md-12 mb-3"><strong>Danger Signs:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->danger_signs ?? 'None' }}
                                </p>
                            </div>

                            <div class="col-md-12 mb-3"><strong>Action / Advice / Counseling:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $prenatalVisit->action_advice_counseling ?? 'None' }}
                                </p>
                            </div>
                        </div>

                        @if (auth('admin')->user()->can('prenatal_visit.edit'))
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-edit-prenatal-visit"
                                    data-id="{{ $prenatalVisit->id }}">
                                    <i class="tio-edit"></i>
                                    {{ translate('Edit') }}
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Prenatal Visit History Tab -->
                @if (auth('admin')->user()->can('prenatal_visit_history.add-new') && $visit->prenatalVisitHistory)
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('pregnancy.add-new') && $visit->pregnancy) && !(auth('admin')->user()->can('prenatal_visit.add-new') && $visit->prenatalVisit) ? 'show active' : '' }} p-3"
                        id="prenatal-history" role="tabpanel" aria-labelledby="prenatal-history-tab">
                        @php
                            $prenatalVisitHistory = $visit->prenatalVisitHistory ?? null;
                        @endphp
                        <div class="col-md-12 mb-3"><strong>History:</strong>
                            <p class="mb-0 text-muted">
                                {{ $prenatalVisitHistory->history ?? 'None' }}
                            </p>
                        </div>

                        <div class="col-md-12 mb-3"><strong>Physical Findings:</strong>
                            <p class="mb-0 text-muted">
                                {{ $prenatalVisitHistory->physical_findings ?? 'None' }}
                            </p>
                        </div>

                        <div class="col-md-12 mb-3"><strong>Progress Notes:</strong>
                            <p class="mb-0 text-muted">
                                {{ $prenatalVisitHistory->progress_notes ?? 'None' }}
                            </p>
                        </div>

                        <div class="col-md-12 mb-3"><strong>Remarks:</strong>
                            <p class="mb-0 text-muted">
                                {{ $prenatalVisitHistory->remarks ?? 'None' }}
                            </p>
                        </div>

                        @if (auth('admin')->user()->can('prenatal_visit_history.edit'))
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary btn-sm"
                                    id="btn-edit-prenatal-visit-history" data-id="{{ $prenatalVisitHistory->id }}">
                                    <i class="tio-edit"></i>
                                    {{ translate('Edit') }}
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </fieldset>
@endif


@if (
    $visit->deliverySummary ||
        $visit->deliverySummary?->newborns ||
        $visit->discharge ||
        (auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0))

    <!-- Pregnancy & Maternity Care Tabs -->
    <fieldset class="border border-primary mt-4 p-3 rounded">
        <legend class="float-none w-auto px-3 py-1 bg-light border border-primary rounded-sm"
            style="font-weight: bold; font-size: 18px; color:white; background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%)">
            <div class="pr-1">
                Delivery Day Informations
            </div>
        </legend>

        <div class="p-3">
            <ul class="nav nav-tabs" id="pregnancyTabs" role="tablist">
                @if (auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="labour-followup-tab" data-toggle="tab"
                            href="#labour-followup" role="tab" aria-controls="labour-followup"
                            aria-selected="false">
                            <i class="tio-trending-up mr-1"></i>Labour Follow-up
                        </a>
                    </li>
                @endif

                @if ($visit->deliverySummary)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0) ? 'active' : '' }}"
                            id="delivery-summary-tab" data-toggle="tab" href="#delivery-summary" role="tab"
                            aria-controls="delivery-summary"
                            aria-selected="{{ !(auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0) ? 'true' : 'false' }}">
                            <i class="tio-walking mr-1"></i>Delivery Summary
                        </a>
                    </li>
                @endif

                @if ($visit->deliverySummary?->newborns)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0) && !$visit->deliverySummary ? 'active' : '' }}"
                            id="newborns-tab" data-toggle="tab" href="#newborns" role="tab"
                            aria-controls="newborns"
                            aria-selected="{{ !(auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0) && !$visit->deliverySummary ? 'true' : 'false' }}">
                            <i class="tio-heart mr-1"></i>Newborns
                        </a>
                    </li>
                @endif

                @if ($visit->discharge)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0) && !$visit->deliverySummary && !$visit->deliverySummary?->newborns ? 'active' : '' }}"
                            id="discharge-tab" data-toggle="tab" href="#discharge" role="tab"
                            aria-controls="discharge"
                            aria-selected="{{ !(auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0) && !$visit->deliverySummary && !$visit->deliverySummary?->newborns ? 'true' : 'false' }}">
                            <i class="tio-sign-out mr-1"></i>Discharge
                        </a>
                    </li>
                @endif
            </ul>

            <div class="tab-content border border-primary rounded-bottom" id="deliveryTabsContent">

                @if (auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0)
                    <div class="tab-pane  show active fade p-3" id="labour-followup" role="tabpanel"
                        aria-labelledby="labour-followup-tab">
                        @if ($visit->labourFollowups && $visit->labourFollowups->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Examination</th>
                                            <th>Value</th>
                                            <th>Recorded At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($visit->labourFollowups->sortByDesc('created_at')->values() as $index => $assessment)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $assessment->test_name }}</td>
                                                <td>{{ $assessment->test_value }}
                                                    @if ($assessment->unit_name)
                                                        <small
                                                            class="text-muted">({{ $assessment->unit_name }})</small>
                                                    @endif
                                                </td>
                                                <td>{{ $assessment->created_at ? $assessment->created_at->format('M d, Y h:i A') : 'N/A' }}
                                                </td>
                                                <td>
                                                    @if (auth('admin')->user()->can('labour_followup.update'))
                                                        <button type="button"
                                                            class="btn btn-sm btn-primary edit-labour-followup"
                                                            data-id="{{ $assessment->id }}"
                                                            data-test-name="{{ $assessment->test_name }}"
                                                            data-test-value="{{ $assessment->test_value }}"
                                                            data-unit-name="{{ $assessment->unit_name }}"
                                                            data-notes="{{ $assessment->notes }}"
                                                            title="Edit Assessment"
                                                            onclick="editLabourFollowup({{ $assessment->id }}, '{{ $assessment->test_name }}', '{{ $assessment->test_value }}', '{{ $assessment->unit_name }}', '{{ $assessment->notes }}')">
                                                            <i class="tio-edit"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
                <!-- Delivery Summary Tab -->
                @php
                    $delivery = $visit->deliverySummary ?? null;
                @endphp
                @if ($delivery)
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0) ? 'show active' : '' }} p-3"
                        id="delivery-summary" role="tabpanel" aria-labelledby="delivery-summary-tab">

                        <div class="row">
                            <div class="col-md-4 mb-3"><strong>Date:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->date ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Time:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->time ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Delivered By:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->deliveredBy->name ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Delivery Mode:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->delivery_mode ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Placenta:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->placenta ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>CCT:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->cct ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>MRP:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->mrp ? 'Yes' : 'No' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Laceration Repair:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->laceration_repair ? 'Yes' : 'No' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Laceration Degree:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->laceration_degree ?? 'Not Specified' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>AMSTL:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->amstl ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Misoprostol:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->misoprostol ? 'Yes' : 'No' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Episiotomy:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->episiotomy ? 'Yes' : 'No' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Newborn Type:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->newborn_type ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Apgar Score:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->apgar_score ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Delivery Outcome:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->delivery_outcome ?? 'Not Specified' }}
                                </p>
                            </div>

                            @if ($delivery->delivery_outcome === 'Stillbirth')
                                <div class="col-md-4 mb-3"><strong>Stillbirth Type:</strong>
                                    <p class="mb-0 text-muted">
                                        {{ $delivery->stillbirth_type ?? 'Not Specified' }}
                                    </p>
                                </div>
                            @endif

                            <div class="col-md-4 mb-3"><strong>Obstetric Complication:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->obstetric_complication ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Management Status:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->obstetric_management_status ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Uterus Repaired:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->ruptured_uterus_repaired ? 'Yes' : 'No' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3"><strong>Hysterectomy:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->hysterectomy ? 'Yes' : 'No' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Feeding Option:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->feeding_option ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3"><strong>Referred for Support:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->referred_for_support ? 'Yes' : 'No' }}
                                </p>
                            </div>

                            <div class="col-md-12 mb-3"><strong>Remarks:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $delivery->remarks ?? 'None' }}
                                </p>
                            </div>

                            @if (auth('admin')->user()->can('delivery_summary.edit'))
                                <div class="col-12 text-right">
                                    <button type="button" class="btn btn-primary btn-sm"
                                        id="edit-delivery-summary-btn" data-summary="{{ $delivery }}">
                                        <i class="tio-edit"></i>
                                        {{ translate('Edit') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Newborns Tab -->
                @if ($visit->deliverySummary?->newborns)
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0) && !$visit->deliverySummary ? 'show active' : '' }} p-3"
                        id="newborns" role="tabpanel" aria-labelledby="newborns-tab">
                        @foreach ($visit->deliverySummary?->newborns ?? [] as $newborn)
                            <div class="border mb-3 p-3 rounded">
                                <h6 class="text-primary mb-3">Newborn {{ $loop->iteration }}</h6>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <strong>Name:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->name ?? 'Not Specified' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3"><strong>Apgar Score:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->apgar_score ?? 'Not Specified' }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <strong>BCG Date:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->bcg_date ? \Carbon\Carbon::parse($newborn->bcg_date)->format('Y-m-d') : 'Not Specified' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Polio 0:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->polio_0 ? 'Yes' : 'No' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Vitamin K:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->vit_k ? 'Yes' : 'No' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>TTC:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->ttc ? 'Yes' : 'No' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Baby-Mother Bonding:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->baby_mother_bonding ? 'Yes' : 'No' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Para:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->para ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>PROM:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->prom ? 'Yes' : 'No' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>PROM Hours:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->prom_hours ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Birth Weight (kg):</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->birth_weight ?? 'Not Specified' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Temperature (°C):</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->temp ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Pulse Rate (PR):</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->pr ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Respiratory Rate (RR):</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->rr ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>HIV Counts and Testing Offered:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->hiv_counts_and_testing_offered ? 'Yes' : 'No' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>HIV Testing Accepted:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->hiv_testing_accepted ? 'Yes' : 'No' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>HIV Test Result:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->hiv_test_result ?? 'Not Specified' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>ARV Prophylaxis (Mother):</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->arv_px_mother ?? 'Not Specified' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>ARV Prophylaxis (Newborn):</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->arv_px_newborn ?? 'Not Specified' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Apgar Score:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->apgar_score ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Sex:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->sex ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Length (cm):</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->length_cm ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Head Circumference (cm):</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->head_circumference_cm ?? 'Not Specified' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Term Status:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->term_status ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Resuscitated:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->resuscitated ? 'Yes' : 'No' }}</p>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <strong>Dysmorphic Faces:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->dysmorphic_faces ? 'Yes' : 'No' }}
                                        </p>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <strong>Neonatal Evaluation:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->neonatal_evaluation ?? 'Not Specified' }}
                                        </p>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <strong>Plan:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->plan ?? 'Not Specified' }}</p>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <strong>Remarks:</strong>
                                        <p class="mb-0 text-muted">
                                            {{ $newborn->remarks ?? 'None' }}</p>
                                    </div>
                                </div>
                                @if (auth('admin')->user()->can('newborn.edit'))
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn btn-primary btn-sm" id="edit-newborn-btn"
                                            data-newborn="{{ $newborn }}">
                                            <i class="tio-edit"></i>
                                            {{ translate('Edit') }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($visit->discharge)
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('labour_followup.list') && $visit->labourFollowups->count() > 0) && !$visit->deliverySummary && !$visit->deliverySummary?->newborns ? 'show active' : '' }} p-3"
                        id="discharge" role="tabpanel" aria-labelledby="discharge-tab">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Admission Date:</strong>
                                <p class="mb-0 text-muted">
                                    {{ \Carbon\Carbon::parse($visit->discharge->admission_date)->format('Y-m-d') }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Discharge Date:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->discharge->discharge_date ? \Carbon\Carbon::parse($visit->discharge->discharge_date)->format('Y-m-d') : 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Stay Days:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->discharge->stay_days }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Discharge Type:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->discharge->discharge_type ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Bed Number:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->ipdRecord->bed->bed_number ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Attending Physician:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->discharge->physician->full_name ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Discharge Notes:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->discharge->discharge_notes ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Remarks:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->discharge->remarks ?? 'Not Specified' }}
                                </p>
                            </div>

                            @if (auth('admin')->user()->can('discharge.edit'))
                                <div class="col-12 text-right">
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="editDischarge({{ $visit->discharge->id }})">
                                        <i class="tio-edit"></i>
                                        {{ translate('Edit') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </fieldset>
@endif

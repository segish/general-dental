@if (
    (auth('admin')->user()->can('medical_record.add-new') && $visit->medicalRecord) ||
        (auth('admin')->user()->can('diagnosis.add-new') && $visit->diagnosisTreatment) ||
        (auth('admin')->user()->can('nurse_assessment.list') && $visit->nurseAssessments->count() > 0))

    <!-- Medical Records & Assessments Tabs -->
    <fieldset class="border border-primary mt-3 p-3 rounded">
        <legend class="float-none w-auto px-3 py-1 bg-light border border-primary rounded-sm"
            style="font-weight: bold; font-size: 18px; color:white; background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%)">
            <div class="pr-1">
                Medical Records & Assessments
            </div>
        </legend>

        <div class="p-3">
            <ul class="nav nav-tabs" id="medicalTabs" role="tablist">
                @if (auth('admin')->user()->can('medical_record.add-new') && $visit->medicalRecord)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="medical-record-tab" data-toggle="tab" href="#medical-record"
                            role="tab" aria-controls="medical-record" aria-selected="true">
                            <i class="tio-document-text mr-1"></i>Medical Record
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('diagnosis.add-new') && $visit->diagnosisTreatment)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('medical_record.add-new') && $visit->medicalRecord) ? 'active' : '' }}"
                            id="diagnosis-tab" data-toggle="tab" href="#diagnosis" role="tab"
                            aria-controls="diagnosis"
                            aria-selected="{{ !(auth('admin')->user()->can('medical_record.add-new') && $visit->medicalRecord) ? 'true' : 'false' }}">
                            <i class="tio-clinic mr-1"></i>Diagnosis & Treatment
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('nurse_assessment.list') && $visit->nurseAssessments->count() > 0)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('medical_record.add-new') && $visit->medicalRecord) && !(auth('admin')->user()->can('diagnosis.add-new') && $visit->diagnosisTreatment) ? 'active' : '' }}"
                            id="nurse-assessment-tab" data-toggle="tab" href="#nurse-assessment" role="tab"
                            aria-controls="nurse-assessment"
                            aria-selected="{{ !(auth('admin')->user()->can('medical_record.add-new') && $visit->medicalRecord) && !(auth('admin')->user()->can('diagnosis.add-new') && $visit->diagnosisTreatment) ? 'true' : 'false' }}">
                            <i class="tio-pin mr-1"></i>Nurse Assessments
                        </a>
                    </li>
                @endif
            </ul>

            <div class="tab-content border border-primary rounded-bottom" id="medicalTabsContent">
                <!-- Medical Record Tab -->
                @if (auth('admin')->user()->can('medical_record.add-new') && $visit->medicalRecord)
                    <div class="tab-pane fade show active p-3" id="medical-record" role="tabpanel"
                        aria-labelledby="medical-record-tab">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Chief Complaint:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->medicalRecord->chief_complaint ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Symptoms:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->medicalRecord->symptoms ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Medical History:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->medicalRecord->medical_history ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Additional Notes:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->medicalRecord->additional_notes ?? 'Not Specified' }}
                                </p>
                            </div>
                            @if (auth('admin')->user()->can('medical_record.edit'))
                                <div class="col-12 text-right">
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="editMedicalRecord({{ $visit->medicalRecord->id }})">
                                        <i class="tio-edit"></i>
                                        {{ translate('Edit') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Diagnosis & Treatment Tab -->
                @if (auth('admin')->user()->can('diagnosis.add-new') && $visit->diagnosisTreatment)
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('medical_record.add-new') && $visit->medicalRecord) ? 'show active' : '' }} p-3"
                        id="diagnosis" role="tabpanel" aria-labelledby="diagnosis-tab">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Diagnosis:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->diagnosisTreatment->diagnosis ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Treatment:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->diagnosisTreatment->treatment ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Diseases:</strong>
                                @if ($visit->diagnosisTreatment && $visit->diagnosisTreatment->diseases->count())
                                    <ul class="mb-0 text-muted">
                                        @foreach ($visit->diagnosisTreatment->diseases as $disease)
                                            <li>{{ $disease->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mb-0 text-muted">No diseases specified</p>
                                @endif
                            </div>
                            @if (auth('admin')->user()->can('diagnosis.edit'))
                                <div class="col-12 text-right">
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="editDiagnosisTreatment({{ $visit->diagnosisTreatment->id }})">
                                        <i class="tio-edit"></i>
                                        {{ translate('Edit') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Nurse Assessments Tab -->
                @if (auth('admin')->user()->can('nurse_assessment.list') && $visit->nurseAssessments->count() > 0)
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('medical_record.add-new') && $visit->medicalRecord) && !(auth('admin')->user()->can('diagnosis.add-new') && $visit->diagnosisTreatment) ? 'show active' : '' }} p-3"
                        id="nurse-assessment" role="tabpanel" aria-labelledby="nurse-assessment-tab">
                        @if ($visit->nurseAssessments && $visit->nurseAssessments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Vital sign</th>
                                            <th>Value</th>
                                            <th>Recorded At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($visit->nurseAssessments->sortByDesc('created_at')->values() as $index => $assessment)
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
                                                    @if (auth('admin')->user()->can('nurse_assessment.update'))
                                                        <button type="button"
                                                            class="btn btn-sm btn-primary edit-nurse-assessment"
                                                            data-id="{{ $assessment->id }}"
                                                            data-test-name="{{ $assessment->test_name }}"
                                                            data-test-value="{{ $assessment->test_value }}"
                                                            data-unit-name="{{ $assessment->unit_name }}"
                                                            data-notes="{{ $assessment->notes }}"
                                                            title="Edit Assessment"
                                                            onclick="editNurseAssessment({{ $assessment->id }}, '{{ $assessment->test_name }}', '{{ $assessment->test_value }}', '{{ $assessment->unit_name }}', '{{ $assessment->notes }}')">
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
            </div>
        </div>
    </fieldset>
@endif

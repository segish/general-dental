@if (
    (auth('admin')->user()->can('medical_document.list') && $visit->documents->count() > 0) ||
        (auth('admin')->user()->can('visit_document.list') && $visit->visitDocuments->count() > 0))

    <!-- Documents Tabs -->
    <fieldset class="border border-primary mt-3 p-3 rounded">
        <legend class="float-none w-auto px-3 py-1 bg-light border border-primary rounded-sm"
            style="font-weight: bold; font-size: 18px; color:white; background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%)">
            <div class="pr-1">
                <i class="tio-folder mr-2"></i>Documents
            </div>
        </legend>

        <div class="p-3">
            <ul class="nav nav-tabs" id="documentsTabs" role="tablist">
                @if (auth('admin')->user()->can('medical_document.list') && $visit->documents->count() > 0)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="medical-documents-tab" data-toggle="tab"
                            href="#medical-documents" role="tab" aria-controls="medical-documents"
                            aria-selected="true">
                            <i class="tio-receipt mr-1"></i>Medical Forms
                        </a>
                    </li>
                @endif
                @if (auth('admin')->user()->can('visit_document.list') && $visit->visitDocuments->count() > 0)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $visit->documents->count() == 0 ? 'active' : '' }}"
                            id="visit-documents-tab" data-toggle="tab" href="#visit-documents" role="tab"
                            aria-controls="visit-documents"
                            aria-selected="{{ $visit->documents->count() == 0 ? 'true' : 'false' }}">
                            <i class="tio-upload mr-1"></i>Uploaded Files
                        </a>
                    </li>
                @endif
            </ul>

            <div class="tab-content border border-primary rounded-bottom" id="documentsTabsContent">
                <!-- Medical Documents Tab -->
                @if (auth('admin')->user()->can('medical_document.list') && $visit->documents->count() > 0)
                    <div class="tab-pane fade show active p-3" id="medical-documents" role="tabpanel"
                        aria-labelledby="medical-documents-tab">

                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Filled By') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Type') }}</th>
                                        <th class="text-center">{{ \App\CentralLogics\translate('Action') }}</th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">
                                    @foreach ($visit->documents as $key => $consentForm)
                                        <tr>
                                            <td>{{ 1 + $key }}</td>
                                            <td>{{ $consentForm->doctor->full_name }}</td>
                                            <td>{{ $consentForm->type }}</td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if (auth('admin')->user()->can('medical_document.pdf'))
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-outline-primary square-btn"
                                                            data-toggle="modal" data-target="#pdfDocumentModal"
                                                            onclick="loadDocumentPdf('{{ route('admin.medical_document.pdf', $consentForm->id) }}', '{{ $consentForm->type }}', '{{ $consentForm->id }}')"
                                                            title="View PDF">
                                                            <i class="tio tio-visible"></i>
                                                        </a>
                                                    @endif
                                                    @if (auth('admin')->user()->can('medical_document.update'))
                                                        <button class="btn btn-outline-warning square-btn"
                                                            onclick="editMedicalDocument({{ $consentForm->id }})"
                                                            title="Edit Document">
                                                            <i class="tio tio-edit"></i>
                                                        </button>
                                                    @endif
                                                    @if (auth('admin')->user()->can('medical_document.delete'))
                                                        <button class="btn btn-outline-danger square-btn"
                                                            onclick="deleteMedicalDocument({{ $consentForm->id }})"
                                                            title="Delete Document">
                                                            <i class="tio tio-delete"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Visit Documents Tab -->
                @if (auth('admin')->user()->can('visit_document.list') && $visit->visitDocuments->count() > 0)
                    <div class="tab-pane fade {{ $visit->documents->count() == 0 ? 'show active' : '' }} p-3"
                        id="visit-documents" role="tabpanel" aria-labelledby="visit-documents-tab">

                        <div class="row" id="visitDocumentsContainer">
                            @foreach ($visit->visitDocuments as $document)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="document-item" data-document-id="{{ $document->id }}">
                                        <div class="d-flex align-items-start">
                                            <div class="document-icon mr-3">
                                                <i class="{{ $document->file_icon }}"></i>
                                            </div>
                                            <div class="flex-grow-1" style="min-width: 0;">
                                                <h6 class="mb-1 text-truncate" title="{{ $document->original_name }}"
                                                    style="max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    {{ $document->original_name }}
                                                </h6>
                                                <small class="text-muted d-block">
                                                    <i
                                                        class="tio-user mr-1"></i>{{ $document->uploadedBy->full_name ?? 'Unknown' }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <i
                                                        class="tio-time mr-1"></i>{{ $document->created_at->format('M d, Y h:i A') }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <i class="tio-data mr-1"></i>{{ $document->file_size_formatted }}
                                                </small>
                                                @if ($document->note)
                                                    <div class="mt-2">
                                                        <small class="text-info">
                                                            <i
                                                                class="tio-note mr-1"></i>{{ Str::limit($document->note, 50) }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="document-actions mt-3">
                                            @if ($document->file_type === 'image')
                                                <button class="btn btn-sm btn-outline-primary"
                                                    onclick="viewDocument({{ $document->id }})" title="View Image">
                                                    <i class="tio-visible"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline-info"
                                                    onclick="viewDocument({{ $document->id }})" title="View Document">
                                                    <i class="tio-visible"></i>
                                                </button>
                                            @endif

                                            <a href="{{ route('admin.visit_document.download', $document->id) }}"
                                                class="btn btn-sm btn-outline-success" title="Download">
                                                <i class="tio-download"></i>
                                            </a>

                                            @if (auth('admin')->user()->can('visit_document.update'))
                                                <button class="btn btn-sm btn-outline-warning"
                                                    onclick="editDocumentNote({{ $document->id }}, '{{ $document->note }}')"
                                                    title="Edit Note">
                                                    <i class="tio-edit"></i>
                                                </button>
                                            @endif

                                            @if (auth('admin')->user()->can('visit_document.delete'))
                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteDocument({{ $document->id }})" title="Delete">
                                                    <i class="tio-delete"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </fieldset>

    <!-- Single Modal for displaying PDF (moved outside the loop) -->
    <div class="modal fade" id="pdfDocumentModal" tabindex="-1" role="dialog"
        aria-labelledby="pdfDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfDocumentModalLabel">
                        Document PDF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="documentpdfIframe" width="100%" height="500px"></iframe>
                </div>
                <div class="modal-footer">
                    <!-- Button to download PDF -->
                    <a href="#" id="downloadPdfBtn" class="btn btn-success">Download PDF</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Medical Document Modal -->
    <div class="modal fade" id="editMedicalDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Edit Medical Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <form id="editMedicalDocumentForm" class="container-fluid">
                        @csrf
                        @method('POST')

                        <!-- Hidden document_id -->
                        <input type="hidden" name="document_id" id="edit_document_id">
                        <input type="hidden" name="visit_id" id="edit_visit_id">

                        <div class="row">
                            <!-- Document Type -->
                            <div class="col-md-4 mb-3">
                                <label for="edit_type">Document Type</label>
                                <select class="form-control" id="edit_type" name="type" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="consent">Consent Form</option>
                                    <option value="certification">Medical Certification</option>
                                    <option value="examination">Medical Examination</option>
                                    <option value="police_certificate">Police Certificate</option>
                                    <option value="referal">Referral</option>
                                    <option value="abortion">Abortion</option>
                                    <option value="circumcision">Circumcision</option>
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="col-md-4 mb-3">
                                <label for="edit_date">Date</label>
                                <input type="date" class="form-control" id="edit_date" name="date" required>
                            </div>

                            <!-- Language -->
                            <div class="col-md-4 mb-3">
                                <label for="edit_language">Language</label>
                                <select class="form-control" id="edit_language" name="language" required>
                                    <option value="amharic">Amharic</option>
                                    <option value="english">English</option>
                                </select>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_notes">Notes</label>
                                <textarea class="form-control" id="edit_notes" name="notes" rows="2"></textarea>
                            </div>
                        </div>

                        <!-- Consent Fields -->
                        <div id="edit_consent_fields" class="type-specific d-none">
                            <h6>Consent Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label>Witness 1 Name</label>
                                    <input type="text" class="form-control" name="witness_1_name"
                                        id="edit_witness_1_name">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Witness 1 Relationship</label>
                                    <input type="text" class="form-control" name="witness_1_relationship"
                                        id="edit_witness_1_relationship">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Witness 2 Name</label>
                                    <input type="text" class="form-control" name="witness_2_name"
                                        id="edit_witness_2_name">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Witness 2 Relationship</label>
                                    <input type="text" class="form-control" name="witness_2_relationship"
                                        id="edit_witness_2_relationship">
                                </div>
                            </div>
                        </div>

                        <!-- Certification Fields -->
                        <div id="edit_certification_fields" class="type-specific d-none">
                            <h6>Certification Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label>Recomendation</label>
                                    <input type="text" class="form-control" name="diagnosis" id="edit_diagnosis">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Days of Rest</label>
                                    <input type="number" class="form-control" name="date_of_rest"
                                        id="edit_date_of_rest">
                                </div>
                            </div>
                        </div>

                        <!-- Examination Fields -->
                        <div id="edit_examination_fields" class="type-specific d-none">
                            <h6>Medical Examination Details</h6>

                            <!-- Section I: Patient Self Declaration -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">I. Patient Self Declaration</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label>Past Diseases (if any)</label>
                                            <textarea class="form-control" name="past_diseases" id="edit_past_diseases" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label>Hospitalization History (period, place and reason)</label>
                                            <textarea class="form-control" name="hospitalization_history" id="edit_hospitalization_history" rows="2"></textarea>
                                        </div>
                                        {{-- <div class="col-md-6 mb-2">
                                            <label>Self Declaration Verified</label>
                                            <input type="text" class="form-control"
                                                name="self_declaration_verified" id="edit_self_declaration_verified">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Patient Signature</label>
                                            <input type="text" class="form-control" name="patient_signature"
                                                id="edit_patient_signature">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Patient Signature Date</label>
                                            <input type="date" class="form-control" name="patient_signature_date"
                                                id="edit_patient_signature_date">
                                        </div> --}}
                                    </div>
                                </div>
                            </div>

                            <!-- Section II: Doctor's Examination -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">II. Doctor's Examination</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label>General Appearance</label>
                                            <textarea class="form-control" name="general_appearance" id="edit_general_appearance" rows="2"></textarea>
                                        </div>

                                        <!-- HEENT Section -->
                                        <div class="col-md-12 mb-2">
                                            <label><strong>HEENT (Head, Eyes, Ears, Nose, Throat)</strong></label>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Visual Acuity OD</label>
                                            <input type="text" class="form-control" name="visual_acuity_od"
                                                id="edit_visual_acuity_od">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Visual Acuity OS</label>
                                            <input type="text" class="form-control" name="visual_acuity_os"
                                                id="edit_visual_acuity_os">
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label>Hearing Test (able to hear normal voice at 4 meters)</label>
                                            <input type="text" class="form-control" name="hearing_test"
                                                id="edit_hearing_test">
                                        </div>

                                        <!-- Lung Examination -->
                                        <div class="col-md-8 mb-2">
                                            <label>Lung Examination</label>
                                            <textarea class="form-control" name="lung_examination" id="edit_lung_examination" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label>Lung X-ray</label>
                                            <input type="text" class="form-control" name="lung_xray"
                                                id="edit_lung_xray">
                                        </div>

                                        <!-- Cardiovascular System -->
                                        <div class="col-md-12 mb-2">
                                            <label><strong>CVS (Cardiovascular System)</strong></label>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label>Heart Condition</label>
                                            <textarea class="form-control" name="heart_condition" id="edit_heart_condition" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label>Blood Pressure</label>
                                            <input type="text" class="form-control" name="blood_pressure"
                                                id="edit_blood_pressure">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label>Pulse</label>
                                            <input type="text" class="form-control" name="pulse"
                                                id="edit_pulse">
                                        </div>

                                        <!-- Other Examinations -->
                                        <div class="col-md-12 mb-2">
                                            <label>Abdomen Examination</label>
                                            <textarea class="form-control" name="abdomen_examination" id="edit_abdomen_examination" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label>GUT (Gastrointestinal/Urinary Tract)</label>
                                            <textarea class="form-control" name="gut_examination" id="edit_gut_examination" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label>Musculoskeletal System</label>
                                            <textarea class="form-control" name="musculoskeletal_examination" id="edit_musculoskeletal_examination"
                                                rows="2"></textarea>
                                        </div>

                                        <!-- Neurological Examination -->
                                        <div class="col-md-12 mb-2">
                                            <label><strong>Neurological Examination</strong></label>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Mental Status</label>
                                            <input type="text" class="form-control" name="mental_status"
                                                id="edit_mental_status">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Nervous System Symptoms</label>
                                            <input type="text" class="form-control" name="nervous_system_symptoms"
                                                id="edit_nervous_system_symptoms">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section III: Laboratory Examination -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">III. Laboratory Examination</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label>HIV Result</label>
                                            <input type="text" class="form-control" name="hiv_result"
                                                id="edit_hiv_result">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Syphilis Result</label>
                                            <input type="text" class="form-control" name="syphilis_result"
                                                id="edit_syphilis_result">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>HBsAg Result</label>
                                            <input type="text" class="form-control" name="hbsag_result"
                                                id="edit_hbsag_result">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>WBC Result</label>
                                            <input type="text" class="form-control" name="wbc_result"
                                                id="edit_wbc_result">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>HCV Result</label>
                                            <input type="text" class="form-control" name="hcv_result"
                                                id="edit_hcv_result">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>ESR Result</label>
                                            <input type="text" class="form-control" name="esr_result"
                                                id="edit_esr_result">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Blood Group</label>
                                            <input type="text" class="form-control" name="blood_group"
                                                id="edit_blood_group">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Pregnancy Test</label>
                                            <input type="text" class="form-control" name="pregnancy_test"
                                                id="edit_pregnancy_test">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section IV: Final Statement -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">IV. Final Statement</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label>Final Medical Status</label>
                                            <input type="text" class="form-control" name="final_medical_status"
                                                id="edit_final_medical_status">
                                        </div>
                                        {{-- <div class="col-md-6 mb-2">
                                            <label>To (Organization/Department)</label>
                                            <input type="text" class="form-control" name="to"
                                                id="edit_to">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Certificate Number</label>
                                            <input type="text" class="form-control" name="number"
                                                id="edit_number">
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Referral Fields -->
                        <div id="edit_referal_fields" class="type-specific d-none">
                            <h6>Referral Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label>From Hospital</label>
                                    <input type="text" class="form-control" name="from_hospital"
                                        id="edit_from_hospital">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>To Hospital</label>
                                    <input type="text" class="form-control" name="to_hospital"
                                        id="edit_to_hospital">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>From Department</label>
                                    <input type="text" class="form-control" name="from_department"
                                        id="edit_from_department">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>To Department</label>
                                    <input type="text" class="form-control" name="to_department"
                                        id="edit_to_department">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label>Clinical Findings</label>
                                    <textarea class="form-control" name="clinical_findings" id="edit_clinical_findings"></textarea>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label>Diagnosis</label>
                                    <input type="text" class="form-control" name="dignosis" id="edit_dignosis">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label>Rx Given</label>
                                    <input type="text" class="form-control" name="rx_given" id="edit_rx_given">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label>Reason</label>
                                    <input type="text" class="form-control" name="reason" id="edit_reason">
                                </div>
                            </div>
                        </div>

                        <!-- Police Certificate Fields -->
                        <div id="edit_police_certificate_fields" class="type-specific d-none">
                            <h6>Police Certificate Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label>Letter Number</label>
                                    <input type="text" class="form-control" name="letter_number"
                                        id="edit_letter_number">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Examination Date</label>
                                    <input type="date" class="form-control" name="examination_date"
                                        id="edit_examination_date">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label>Issued Idea</label>
                                    <textarea class="form-control" name="issued_idea" id="edit_issued_idea" rows="3"></textarea>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label>Victim History</label>
                                    <textarea class="form-control" name="victim_history" id="edit_victim_history" rows="3"></textarea>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label>Injury Finding</label>
                                    <textarea class="form-control" name="injury_finding" id="edit_injury_finding" rows="3"></textarea>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label>Doctor Recommendation</label>
                                    <textarea class="form-control" name="doctor_recommendation" id="edit_doctor_recommendation" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update Document</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endif
@if (
    !$visit->medicalRecord &&
        !$visit->diagnosisTreatment &&
        !$visit->nurseAssessments->count() > 0 &&
        !$visit->pregnancy &&
        !$visit->prenatalVisit &&
        !$visit->prenatalVisitHistory &&
        !$visit->deliverySummary &&
        !$visit->deliverySummary?->newborns &&
        !$visit->discharge &&
        !$visit->labourFollowups->count() > 0 &&
        !$visit->laboratoryRequest &&
        !$visit->radiologyRequest &&
        !$visit->prescription->count() > 0 &&
        !$visit->emergencyPrescriptions->count() > 0 &&
        !$visit->procedures->count() > 0 &&
        !$visit->documents->count() > 0 &&
        !$visit->visitDocuments->count() > 0)
    <!-- Empty State for Visit Documents -->
    <div class="text-center py-4">
        <div class="mb-3">
            <i class="tio-inbox text-muted" style="font-size: 3rem;"></i>
        </div>
        <h6 class="text-muted">No data recorded yet for this visit</h6>
    </div>
@endif

@push('script_2')
    <script>
        function loadDocumentPdf(pdfUrl, documentType, documentId) {
            // Set the iframe source
            document.getElementById('documentpdfIframe').src = pdfUrl;

            // Update the modal title
            document.getElementById('pdfDocumentModalLabel').textContent = documentType + ' PDF';

            // Update the download button href
            document.getElementById('downloadPdfBtn').href = '{{ route('admin.medical_document.download', ':id') }}'
                .replace(':id', documentId);
        }

        function editMedicalDocument(documentId) {
            // Fetch document data via AJAX
            $.ajax({
                url: '{{ route('admin.medical_document.edit', ':id') }}'.replace(':id', documentId),
                method: 'GET',
                success: function(data) {
                    // Populate form fields with existing data
                    $('#edit_document_id').val(data.id);
                    $('#edit_visit_id').val(data.visit_id);
                    $('#edit_type').val(data.type);
                    $('#edit_date').val(data.date);
                    $('#edit_language').val(data.language);
                    $('#edit_notes').val(data.notes);

                    // Show type-specific fields based on document type
                    $('.type-specific').addClass('d-none');
                    if (data.type) {
                        $('#edit_' + data.type + '_fields').removeClass('d-none');
                    }

                    // Populate type-specific fields
                    if (data.type === 'consent') {
                        $('#edit_witness_1_name').val(data.witness_1_name || '');
                        $('#edit_witness_1_relationship').val(data.witness_1_relationship || '');
                        $('#edit_witness_2_name').val(data.witness_2_name || '');
                        $('#edit_witness_2_relationship').val(data.witness_2_relationship || '');
                    } else if (data.type === 'certification') {
                        $('#edit_diagnosis').val(data.diagnosis || '');
                        $('#edit_date_of_rest').val(data.date_of_rest || '');
                    } else if (data.type === 'examination') {
                        // Populate all examination fields
                        $('#edit_to').val(data.to || '');
                        $('#edit_number').val(data.number || '');
                        $('#edit_past_diseases').val(data.past_diseases || '');
                        $('#edit_hospitalization_history').val(data.hospitalization_history || '');
                        $('#edit_self_declaration_verified').val(data.self_declaration_verified || '');
                        $('#edit_patient_signature').val(data.patient_signature || '');
                        $('#edit_patient_signature_date').val(data.patient_signature_date || '');
                        $('#edit_general_appearance').val(data.general_appearance || '');
                        $('#edit_visual_acuity_od').val(data.visual_acuity_od || '');
                        $('#edit_visual_acuity_os').val(data.visual_acuity_os || '');
                        $('#edit_hearing_test').val(data.hearing_test || '');
                        $('#edit_lung_examination').val(data.lung_examination || '');
                        $('#edit_lung_xray').val(data.lung_xray || '');
                        $('#edit_heart_condition').val(data.heart_condition || '');
                        $('#edit_blood_pressure').val(data.blood_pressure || '');
                        $('#edit_pulse').val(data.pulse || '');
                        $('#edit_abdomen_examination').val(data.abdomen_examination || '');
                        $('#edit_gut_examination').val(data.gut_examination || '');
                        $('#edit_musculoskeletal_examination').val(data.musculoskeletal_examination || '');
                        $('#edit_mental_status').val(data.mental_status || '');
                        $('#edit_nervous_system_symptoms').val(data.nervous_system_symptoms || '');
                        $('#edit_hiv_result').val(data.hiv_result || '');
                        $('#edit_syphilis_result').val(data.syphilis_result || '');
                        $('#edit_hbsag_result').val(data.hbsag_result || '');
                        $('#edit_wbc_result').val(data.wbc_result || '');
                        $('#edit_hcv_result').val(data.hcv_result || '');
                        $('#edit_esr_result').val(data.esr_result || '');
                        $('#edit_blood_group').val(data.blood_group || '');
                        $('#edit_pregnancy_test').val(data.pregnancy_test || '');
                        $('#edit_final_medical_status').val(data.final_medical_status || '');
                    } else if (data.type === 'referal') {
                        $('#edit_from_hospital').val(data.from_hospital || '');
                        $('#edit_to_hospital').val(data.to_hospital || '');
                        $('#edit_from_department').val(data.from_department || '');
                        $('#edit_to_department').val(data.to_department || '');
                        $('#edit_clinical_findings').val(data.clinical_findings || '');
                        $('#edit_dignosis').val(data.dignosis || '');
                        $('#edit_rx_given').val(data.rx_given || '');
                        $('#edit_reason').val(data.reason || '');
                    } else if (data.type === 'police_certificate') {
                        $('#edit_letter_number').val(data.letter_number || '');
                        $('#edit_examination_date').val(data.examination_date || '');
                        $('#edit_issued_idea').val(data.issued_idea || '');
                        $('#edit_victim_history').val(data.victim_history || '');
                        $('#edit_injury_finding').val(data.injury_finding || '');
                        $('#edit_doctor_recommendation').val(data.doctor_recommendation || '');
                    }

                    // Show the modal
                    $('#editMedicalDocumentModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Error loading document data', {
                        closeButton: true,
                        progressBar: true
                    });
                }
            });
        }

        function deleteMedicalDocument(documentId) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: 'Are you sure you want to delete this medical document? This action cannot be undone.',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#673ab7',
                cancelButtonText: '{{ translate('No') }}',
                confirmButtonText: '{{ translate('Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.medical_document.delete', ':id') }}'.replace(':id',
                            documentId),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            toastr.success('Medical document deleted successfully!', {
                                closeButton: true,
                                progressBar: true
                            });
                            // Reload the page to reflect changes
                            location.reload();
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                toastr.error(xhr.responseJSON.message, {
                                    closeButton: true,
                                    progressBar: true
                                });
                            } else {
                                toastr.error('Error deleting document', {
                                    closeButton: true,
                                    progressBar: true
                                });
                            }
                        }
                    });
                }
            });
        }

        // Handle edit form submission
        $(document).ready(function() {
            // Toggle type-specific fields in edit modal
            $('#edit_type').on('change', function() {
                var selectedType = $(this).val();
                $('.type-specific').addClass('d-none');
                if (selectedType) {
                    $('#edit_' + selectedType + '_fields').removeClass('d-none');
                }
            });

            // Handle edit form submission
            $('#editMedicalDocumentForm').on('submit', function(event) {
                event.preventDefault();

                const submitButton = $(this).find('button[type="submit"]');
                const originalText = disableButton(submitButton);

                var formData = new FormData(this);
                var documentId = $('#edit_document_id').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route('admin.medical_document.update', ':id') }}'.replace(':id',
                        documentId),
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        toastr.success('Medical document updated successfully!', {
                            closeButton: true,
                            progressBar: true
                        });
                        $('#editMedicalDocumentModal').modal('hide');
                        $('#editMedicalDocumentForm')[0].reset();

                        // Reload the page to reflect changes
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                toastr.error(value[0], {
                                    closeButton: true,
                                    progressBar: true
                                });
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message, {
                                closeButton: true,
                                progressBar: true
                            });
                        } else {
                            toastr.error('An error occurred while updating the document.', {
                                closeButton: true,
                                progressBar: true
                            });
                        }
                    },
                    complete: function() {
                        setTimeout(function() {
                            enableButton(submitButton, originalText);
                        }, 2000);
                    }
                });
            });
        });
    </script>
@endpush

<style>
    .document-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #fff;
        transition: all 0.3s ease;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .document-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .document-icon {
        font-size: 2rem;
        color: #6c757d;
        flex-shrink: 0;
    }

    .document-actions {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .document-actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .document-item .flex-grow-1 {
        min-width: 0;
        overflow: hidden;
    }

    .document-item h6 {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        word-break: break-all;
    }

    .document-item small {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

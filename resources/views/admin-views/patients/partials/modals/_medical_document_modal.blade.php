<!-- Add Medical Document Modal -->
<div class="modal fade" id="add-medical_document" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">Add Medical Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form id="medicalDocumentForm" class="container-fluid">
                    @csrf

                    <!-- Hidden visit_id -->
                    <input type="hidden" name="visit_id" id="visit_id">

                    <div class="row">
                        <!-- Document Type -->
                        <div class="col-md-4 mb-3">
                            <label for="type">Document Type</label>
                            <select class="form-control" id="type" name="type" required>
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
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>

                        <!-- Language -->
                        <div class="col-md-4 mb-3">
                            <label for="language">Language</label>
                            <select class="form-control" id="language" name="language" required>
                                <option value="amharic">Amharic</option>
                                <option value="english">English</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Consent Fields -->
                    <div id="consent_fields" class="type-specific d-none">
                        <h6>Consent Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Witness 1 Name</label>
                                <input type="text" class="form-control" name="witness_1_name">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Witness 1 Relationship</label>
                                <input type="text" class="form-control" name="witness_1_relationship">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Witness 2 Name</label>
                                <input type="text" class="form-control" name="witness_2_name">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Witness 2 Relationship</label>
                                <input type="text" class="form-control" name="witness_2_relationship">
                            </div>
                        </div>
                    </div>

                    <!-- Certification Fields -->
                    <div id="certification_fields" class="type-specific d-none">
                        <h6>Certification Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Recomendation</label>
                                <input type="text" class="form-control" name="diagnosis">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Days of Rest</label>
                                <input type="number" class="form-control" name="date_of_rest">
                            </div>
                        </div>
                    </div>

                    <!-- Examination Fields -->
                    <div id="examination_fields" class="type-specific d-none">
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
                                        <textarea class="form-control" name="past_diseases" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Hospitalization History (period, place and reason)</label>
                                        <textarea class="form-control" name="hospitalization_history" rows="2"></textarea>
                                    </div>
                                    {{-- <div class="col-md-6 mb-2">
                                        <label>Self Declaration Verified</label>
                                        <input type="text" class="form-control" name="self_declaration_verified">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Patient Signature</label>
                                        <input type="text" class="form-control" name="patient_signature">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Patient Signature Date</label>
                                        <input type="date" class="form-control" name="patient_signature_date">
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
                                        <label>1, General Appearance</label>
                                        <textarea class="form-control" name="general_appearance" rows="2"></textarea>
                                    </div>

                                    <!-- HEENT Section -->
                                    <div class="col-md-12 mb-2">
                                        <label><strong>2, HEENT (Head, Eyes, Ears, Nose, Throat)</strong></label>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Visual Acuity OD</label>
                                        <input type="text" class="form-control" name="visual_acuity_od">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Visual Acuity OS</label>
                                        <input type="text" class="form-control" name="visual_acuity_os">
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Hearing Test (able to hear normal voice at 4 meters)</label>
                                        <input type="text" class="form-control" name="hearing_test">
                                    </div>

                                    <!-- Lung Examination -->
                                    <div class="col-md-8 mb-2">
                                        <label>Lung Examination</label>
                                        <textarea class="form-control" name="lung_examination" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label>Lung X-ray</label>
                                        <input type="text" class="form-control" name="lung_xray">
                                    </div>

                                    <!-- Cardiovascular System -->
                                    <div class="col-md-12 mb-2">
                                        <label><strong>3, CVS (Cardiovascular System)</strong></label>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Heart Condition</label>
                                        <textarea class="form-control" name="heart_condition" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label>Blood Pressure</label>
                                        <input type="text" class="form-control" name="blood_pressure">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label>Pulse</label>
                                        <input type="text" class="form-control" name="pulse">
                                    </div>

                                    <!-- Other Examinations -->
                                    <div class="col-md-12 mb-2">
                                        <label>Abdomen Examination</label>
                                        <textarea class="form-control" name="abdomen_examination" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>GUT (Gastrointestinal/Urinary Tract)</label>
                                        <textarea class="form-control" name="gut_examination" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Musculoskeletal System</label>
                                        <textarea class="form-control" name="musculoskeletal_examination" rows="2"></textarea>
                                    </div>

                                    <!-- Neurological Examination -->
                                    <div class="col-md-12 mb-2">
                                        <label><strong>4, Neurological Examination</strong></label>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Mental Status</label>
                                        <input type="text" class="form-control" name="mental_status">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Nervous System Symptoms</label>
                                        <input type="text" class="form-control" name="nervous_system_symptoms">
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
                                        <input type="text" class="form-control" name="hiv_result">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Syphilis Result</label>
                                        <input type="text" class="form-control" name="syphilis_result">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>HBsAg Result</label>
                                        <input type="text" class="form-control" name="hbsag_result">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>WBC Result</label>
                                        <input type="text" class="form-control" name="wbc_result">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>HCV Result</label>
                                        <input type="text" class="form-control" name="hcv_result">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>ESR Result</label>
                                        <input type="text" class="form-control" name="esr_result">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Blood Group</label>
                                        <input type="text" class="form-control" name="blood_group">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Pregnancy Test</label>
                                        <input type="text" class="form-control" name="pregnancy_test">
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
                                        <input type="text" class="form-control" name="final_medical_status">
                                    </div>
                                    {{-- <div class="col-md-6 mb-2">
                                        <label>To (Organization/Department)</label>
                                        <input type="text" class="form-control" name="to">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Certificate Number</label>
                                        <input type="text" class="form-control" name="number">
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Fields -->
                    <div id="referal_fields" class="type-specific d-none">
                        <h6>Referral Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>From Hospital</label>
                                <input type="text" class="form-control" name="from_hospital">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>To Hospital</label>
                                <input type="text" class="form-control" name="to_hospital">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>From Department</label>
                                <input type="text" class="form-control" name="from_department">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>To Department</label>
                                <input type="text" class="form-control" name="to_department">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Clinical Findings</label>
                                <textarea class="form-control" name="clinical_findings"></textarea>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Diagnosis</label>
                                <input type="text" class="form-control" name="dignosis">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Rx Given</label>
                                <input type="text" class="form-control" name="rx_given">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Reason</label>
                                <input type="text" class="form-control" name="reason">
                            </div>
                        </div>
                    </div>

                    <!-- Police Certificate Fields -->
                    <div id="police_certificate_fields" class="type-specific d-none">
                        <h6>Police Certificate Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Letter Number</label>
                                <input type="text" class="form-control" name="letter_number">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Examination Date</label>
                                <input type="date" class="form-control" name="examination_date">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Issued Idea</label>
                                <textarea class="form-control" name="issued_idea" rows="3"></textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Victim History</label>
                                <textarea class="form-control" name="victim_history" rows="3"></textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Injury Finding</label>
                                <textarea class="form-control" name="injury_finding" rows="3"></textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Doctor Recommendation</label>
                                <textarea class="form-control" name="doctor_recommendation" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save Document</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<!-- JS -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Fill visit_id from button attribute
        $('#add-medical_document').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var visitId = button.data('visit-id');
            $(this).find('#visit_id').val(visitId);
        });

        // Toggle type-specific fields
        $('#type').on('change', function() {
            var selectedType = $(this).val();
            $('.type-specific').addClass('d-none');
            if (selectedType) {
                $('#' + selectedType + '_fields').removeClass('d-none');
            }
        });

        // AJAX handler
        $('#medicalDocumentForm').on('submit', function(event) {
            event.preventDefault();

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.medical_document.store') }}', // your route here
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.success(
                        '{{ translate('Medical Document Created successfully!') }}', {
                            closeButton: true,
                            progressBar: true
                        });
                    $('#add-medical_document').modal('hide');
                    $('#medicalDocumentForm')[0].reset();

                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();
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
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
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

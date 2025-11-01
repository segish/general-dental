@php
    // Fetch config and patient settings
    $config = \App\Models\BusinessSetting::where('key', 'is_flexible_payment')->first();
    $isGlobalFlexible = $config && $config->value == 1;
    $isPatientFlexible = $visit->patient->is_flexible_payment ?? false;

    // All billings of this visit
    $billings = $visit->billings;

    // Billing checker (only keep unpaid/pending/partial items with field present)
    $checkBilling = function ($billings, $field) {
        return $billings->filter(
            fn($billing) => $billing->$field !== null && in_array($billing->status, ['pending', 'unpaid', 'partial']), // unpaid states
        );
    };

    // Check each billing type
    $emergencyBillings = $checkBilling($billings, 'emergency_medicine_issuance_id');
    $labBillings = $checkBilling($billings, 'laboratory_request_id');
    $radiologyBillings = $checkBilling($billings, 'radiology_request_id');
    $billingServiceBillings = $checkBilling($billings, 'billing_service_id');

    // Logic: allow only if there's no unpaid billing OR both are flexible
    $canAddEmergency = $emergencyBillings->isEmpty() || ($isGlobalFlexible && $isPatientFlexible);
    $canAddLab = $labBillings->isEmpty() || ($isGlobalFlexible && $isPatientFlexible);
    $canAddRadiology = $radiologyBillings->isEmpty() || ($isGlobalFlexible && $isPatientFlexible);
    $canAddBillingService = $billingServiceBillings->isEmpty() || ($isGlobalFlexible && $isPatientFlexible);
    //dd($canAddEmergency, $canAddLab, $canAddRadiology, $canAddBillingService);
@endphp
<div class="card-header d-flex flex-column flex-sm-row justify-content-sm-end gap-2 gap-sm-4 flex-wrap">

    @if (auth('admin')->user()->can('nurse_assessment.add-new') &&
            $visit->serviceCategory &&
            in_array('vital sign', $visit->serviceCategory->service_type))
        <script>
            var vitalSigns = @json($vitalSigns);
        </script>
        <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_nurse_assessment_test"
            type="button" data-toggle="modal" data-target="#add-nurse_assessment_test" data-visit-id="{{ $visit->id }}"
            title="Add Physical Test" style="border: 1px solid #d3d3d3;">
            <i class="tio-temperature"></i>
            {{ translate('Vital Sign') }}
        </button>
    @endif

    @if (auth('admin')->user()->can('medical_document.add-new'))
        <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_medical_document" type="button"
            data-toggle="modal" data-target="#add-medical_document" data-visit-id="{{ $visit->id }}"
            title="Add Medical Document" style="border: 1px solid #d3d3d3;">
            <i class="tio-file-add"></i>
            {{ translate('Document') }}
        </button>
    @endif

    @if (auth('admin')->user()->can('labour_followup.add-new') &&
            $visit->serviceCategory &&
            $visit->visit_type == 'IPD' &&
            in_array('Labour Followup', $visit->serviceCategory->service_type))
        <script>
            var labourFollowups = @json($labourFollowups);
        </script>
        <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_labour_followup_test"
            type="button" data-toggle="modal" data-target="#add-labour_followup_test"
            data-visit-id="{{ $visit->id }}" title="Add Physical Test" style="border: 1px solid #d3d3d3;">
            <i class="tio-appointment"></i>
            {{ translate('Labour Followup') }}
        </button>
    @endif

    @if (auth('admin')->user()->can('medical_record.add-new') &&
            !$visit->medicalRecord &&
            $visit->serviceCategory &&
            in_array('medical record', $visit->serviceCategory->service_type))
        @unless ($canAddBillingService)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)">
                <i class="tio-warning"></i> {{ translate('Medical Record') }}
            </button>
        @else
            <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_medical_record" type="button"
                data-toggle="modal" data-target="#add-medical-record" data-visit-id="{{ $visit->id }}">
                <i class="tio-document-text"></i> {{ translate('Medical Record') }}
            </button>
        @endunless
    @endif

    @if (!$pregnancy && $visit->serviceCategory && in_array('pregnancy', $visit->serviceCategory->service_type))
        @unless ($canAddBillingService)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)">
                <i class="tio-warning"></i> {{ translate('Pregnancy') }}
            </button>
        @else
            @if (auth('admin')->user()->can('pregnancy.store'))
                <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" type="button" id="addPregnancyBtn"
                    data-visit-id="{{ $visit->id }}">
                    <i class="tio-pregnancy"></i> {{ translate('Pregnancy') }}
                </button>
            @endif
        @endunless
    @endif

    @if (
        $pregnancy &&
            $visit->serviceCategory &&
            in_array('pregnancy history', $visit->serviceCategory->service_type) &&
            !$visit->prenatalVisit &&
            auth('admin')->user()->can('prenatal_visit.store'))
        {{-- Check if the user can add a billing service --}}
        @unless ($canAddBillingService)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)">
                <i class="tio-warning"></i> {{ translate('Follow Up') }}
            </button>
        @else
            @if (auth('admin')->user()->can('prenatal_visit.store'))
                <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                    id="btnAddPrenatalVisit" data-visit-id="{{ $visit->id }}">
                    <i class="tio-appointment"></i> {{ translate('Follow Up') }}
                </button>
            @endif
        @endunless
    @endif

    @if (
        $pregnancy &&
            $visit->serviceCategory &&
            in_array('pregnancy history', $visit->serviceCategory->service_type) &&
            !$visit->prenatalVisitHistory &&
            auth('admin')->user()->can('prenatal_visit_history.store'))
        {{-- Check if the user can add a billing service --}}
        @unless ($canAddBillingService)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)">
                <i class="tio-warning"></i> {{ translate('History Sheet') }}
            </button>
        @else
            @if (auth('admin')->user()->can('prenatal_visit_history.store'))
                <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                    id="btnAddPrenatalVisitHistory" data-visit-id="{{ $visit->id }}">
                    <i class="tio-history"></i> {{ translate('History Sheet') }}
                </button>
            @endif
        @endunless
    @endif

    @if (auth('admin')->user()->can('pregnancy_followup.add-new') &&
            !$visit->medicalRecord &&
            $visit->serviceCategory &&
            in_array('pregnancy history', $visit->serviceCategory->service_type))
        @unless ($canAddBillingService)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)">
                <i class="tio-warning"></i> {{ translate('Pregnancy Followup') }}
            </button>
        @else
            <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_pregnancy_followup"
                type="button" data-toggle="modal" data-target="#add-pregnancy-followup"
                data-visit-id="{{ $visit->id }}">
                <i class="tio-appointment"></i> {{ translate('Antenatal Follow Up') }}
            </button>
        @endunless
    @endif

    @if (auth('admin')->user()->can('diagnosis.add-new') &&
            $visit->medicalRecord &&
            !$visit->diagnosisTreatment &&
            $visit->serviceCategory &&
            in_array('diagnosis', $visit->serviceCategory->service_type))
        @unless ($canAddBillingService)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)">
                <i class="tio-warning"></i> {{ translate('Diagnosis/Treatment') }}
            </button>
        @else
            <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_diagnosis_treatment"
                type="button" data-toggle="modal" data-target="#add-diagnosis-treatment"
                data-visit-id="{{ $visit->id }}">
                <i class="tio-medicaments"></i> {{ translate('Diagnosis/Treatment') }}
            </button>
        @endunless
    @endif
    @if (auth('admin')->user()->can('delivery_summary.add-new') &&
            $visit->visit_type == 'IPD' &&
            $pregnancy &&
            !$visit->deliverySummary &&
            $visit->serviceCategory &&
            in_array('delivery summary', $visit->serviceCategory->service_type))
        @unless ($canAddBillingService)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)">
                <i class="tio-warning"></i> {{ translate('Delivery Summary') }}
            </button>
        @else
            <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" id="open-create-delivery-summary"
                type="button" data-visit-id="{{ $visit->id }}">
                <i class="tio-document-text"></i> {{ translate('Delivery Summary') }}
            </button>
        @endunless
    @endif

    @if (auth('admin')->user()->can('newborn.add-new') &&
            $visit->visit_type == 'IPD' &&
            $visit->deliverySummary &&
            $visit->serviceCategory &&
            in_array('newborn', $visit->serviceCategory->service_type))
        @unless ($canAddBillingService)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)">
                <i class="tio-warning"></i> {{ translate('New Born') }}
            </button>
        @else
            <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" id="add-newborn-btn" type="button"
                data-delivery-summary-id="{{ $visit->deliverySummary->id }}">
                <i class="tio-hospital"></i> {{ translate('New Born') }}
            </button>
        @endunless
    @endif

    @if (auth('admin')->user()->can('discharge.add-new') && !$visit->discharge && $visit->visit_type == 'IPD')
        <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" id="btnCreateDischarge" type="button"
            data-visit-id="{{ $visit->id }}">
            <i class="tio-sign-out"></i> {{ translate('Discharge') }}
        </button>
    @endif

    @if (auth('admin')->user()->can('laboratory_request.add-new') &&
            !$visit->laboratoryRequest &&
            $visit->serviceCategory &&
            in_array('lab test', $visit->serviceCategory->service_type))
        <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_medical_history"
            type="button" data-toggle="modal" data-target="#add-laboratory_request" title="Add Laboratory Request"
            data-visit-id="{{ $visit->id }}">
            <i class="tio-test-tube"></i>
            {{ translate('Laboratory Request') }}
        </button>
    @endif

    @if (auth('admin')->user()->can('radiology_request.add-new') &&
            !$visit->radiologyRequest &&
            $visit->serviceCategory &&
            in_array('radiology', $visit->serviceCategory->service_type))
        <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_radiology_request"
            type="button" data-toggle="modal" data-target="#add-radiology_request" title="Add Radiology Request"
            data-visit-id="{{ $visit->id }}">
            <i class="tio-photo-camera"></i>
            {{ translate('Radiology Request') }}
        </button>
    @endif

    {{-- Emergency/Inclinic Items Button --}}
    @if (auth('admin')->user()->can('emergency_prescriptions.add-new') &&
            $visit->serviceCategory &&
            in_array('prescription', $visit->serviceCategory->service_type))
        <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_Emergency_Prescirption_test"
            type="button" data-toggle="modal" data-target="#add-Emergency-Prescirption_test"
            data-visit-id="{{ $visit->id }}" title="Add Emergency Prescirption"
            style="border: 1px solid #d3d3d3;"
            @if (!$canAddEmergency) onclick="showBillingToast(event)" data-toggle="" data-target="" @endif>
            <i class="tio-pill"></i>
            {{ translate('Inclinic Items') }}
        </button>
    @endif
    {{-- Prescription Button --}}
    @if (auth('admin')->user()->can('prescriptions.add-new') &&
            $visit->serviceCategory &&
            in_array('prescription', $visit->serviceCategory->service_type))
        <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_Prescirption_test"
            type="button" data-toggle="modal" data-target="#add-Prescirption_test"
            data-visit-id="{{ $visit->id }}" title="Add Prescirption" style="border: 1px solid #d3d3d3;">
            <i class="tio-pill"></i>
            {{ translate('Prescirption') }}
        </button>
    @endif

    @if (auth('admin')->user()->can('service.add-service-billing') &&
            $visit->serviceCategory &&
            in_array('billing service', $visit->serviceCategory->service_type))
        <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_service_billing_btn"
            type="button" data-toggle="modal" data-target="#add-service-billing-modal"
            data-visit-id="{{ $visit->id }}" title="Add Service Billing" style="border: 1px solid #d3d3d3;">
            <i class="tio-receipt"></i>
            {{ translate('Billing Service') }}
        </button>
    @endif
    @php
        $showSpecimenButton = false;

        if ($visit->laboratoryrequest && $visit->laboratoryrequest->tests) {
            // Get only active and in-house tests
            $activeInhouseTests = $visit->laboratoryrequest->tests->filter(function ($test) {
                return $test->test && $test->test->is_active && $test->test->is_inhouse;
            });

            // If there are active tests
            if ($activeInhouseTests->isNotEmpty()) {
                // Check if at least one of them does NOT have a specimen
                $hasTestWithoutSpecimen = $activeInhouseTests->contains(function ($test) {
                    return !$test->specimens()->exists();
                });

                $showSpecimenButton = $hasTestWithoutSpecimen;
            }
        }
    @endphp


    @if (auth('admin')->user()->can('specimen.add-new') &&
            !in_array($visit->status, ['completed', 'rejected']) &&
            $visit->laboratoryrequest &&
            // $visit->laboratoryrequest->status != 'completed' &&
            $visit->serviceCategory &&
            in_array('lab test', $visit->serviceCategory->service_type) &&
            $showSpecimenButton)
        @unless ($canAddLab)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)" title="Cannot add specimen due to unpaid/pending billing.">
                <i class="tio-warning"></i>
                {{ translate('Specimen') }}
            </button>
        @else
            <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_medical_lab_test"
                type="button" data-toggle="modal" data-target="#add-medical_lab_test"
                data-laboratory-request-id="{{ $visit->laboratoryrequest->id }}"
                data-tests="{{ json_encode($visit->laboratoryrequest->tests) }}" title="Add Specimen"
                style="border: 1px solid #d3d3d3;">
                <i class="tio-flask"></i>
                {{ translate('Specimen') }}
            </button>
        @endunless
    @endif


    @php
        $hasTestWithAcceptedSpecimenAndNoResult = false;

        if ($visit->laboratoryrequest && $visit->laboratoryrequest->tests) {
            $hasTestWithAcceptedSpecimenAndNoResult = $visit->laboratoryrequest->tests->contains(function ($test) {
                // Only manual-entry tests
                if ($test->test->result_source !== 'manual') {
                    return false;
                }

                // Check for accepted specimen
                $hasAcceptedSpecimen = $test->specimens->contains('status', 'accepted');

                // Check if result is not filled
                $hasNoResult = $test->result === null;

                return $hasAcceptedSpecimen && $hasNoResult;
            });
        }
    @endphp
    @php
        $hasTestWithNoResult = false;

        if ($visit->laboratoryrequest && $visit->laboratoryrequest->tests) {
            $hasTestWithNoResult = $visit->laboratoryrequest->tests->contains(function ($test) {
                // Only manual-entry tests
                if ($test->test->result_source !== 'manual') {
                    return false;
                }

                $hasNoResult = $test->result === null;

                return $hasNoResult;
            });
        }
    @endphp


    @php
        $laboratoryRequest = $visit->laboratoryrequest;
    @endphp

    {{-- @if (auth('admin')->user()->can('laboratory_result.add-new') && $laboratoryRequest && !in_array($laboratoryRequest->status, ['completed', 'rejected']) && $hasTestWithAcceptedSpecimenAndNoResult && $visit->serviceCategory && in_array('lab test', $visit->serviceCategory->service_type))
        @unless ($canAddLab)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)" title="Cannot add result due to pending/unpaid bill.">
                <i class="tio-warning"></i>
                {{ translate('Result') }}
            </button>
        @else
            <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_result" type="button"
                data-toggle="modal" data-target="#add-result_test"
                data-laboratory-request-id="{{ $visit->laboratoryrequest->id }}" title="Add Result"
                style="border: 1px solid #d3d3d3;">
                <i class="tio-add"></i>
                {{ translate('Result') }}
            </button>
        @endunless
    @endif --}}

    @if (auth('admin')->user()->can('laboratory_result.add-new') &&
            $laboratoryRequest &&
            // !in_array($laboratoryRequest->status, ['completed', 'rejected']) &&
            $hasTestWithNoResult &&
            $visit->serviceCategory &&
            in_array('lab test', $visit->serviceCategory->service_type))
        @unless ($canAddLab)
            <button class="btn btn-warning rounded text-nowrap me-2 mb-2 mb-sm-0" type="button"
                onclick="showBillingToast(event)" title="Cannot add result due to pending/unpaid bill.">
                <i class="tio-warning"></i>
                {{ translate('Lab Result') }}
            </button>
        @else
            <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_custom_result" type="button"
                data-toggle="modal" data-target="#add-result_test_custom"
                data-laboratory-request-id="{{ $visit->laboratoryrequest->id }}" title="Add Result"
                style="border: 1px solid #d3d3d3;">
                <i class="tio-lab"></i>
                {{ translate('Lab Result') }}
            </button>
        @endunless
    @endif

    @php
        $hasRadiologyWithNoResult = false;

        if ($visit->radiologyRequest && $visit->radiologyRequest->radiologies) {
            $hasRadiologyWithNoResult = $visit->radiologyRequest->radiologies->contains(function ($radiology) {
                $hasNoResult = $radiology->result === null;
                return $hasNoResult;
            });
        }
    @endphp

    @php
        $radiologyRequest = $visit->radiologyRequest;
    @endphp

    @if (auth('admin')->user()->can('radiology_result.add-new') &&
            $radiologyRequest &&
            !in_array($radiologyRequest->status, ['completed', 'rejected']) &&
            $hasRadiologyWithNoResult &&
            $visit->serviceCategory &&
            in_array('radiology', $visit->serviceCategory->service_type))
        <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_radiology_result"
            type="button" data-toggle="modal" data-target="#add-result_radiology"
            data-radiology-request-id="{{ $visit->radiologyRequest->id }}" title="Add Result"
            style="border: 1px solid #d3d3d3;">
            <i class="tio-photo-landscape"></i>
            {{ translate('Radiology Result') }}
        </button>
    @endif

    @if (auth('admin')->user()->can('visit_document.store'))
        <button class="btn btn-light rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_visit_document"
            type="button" data-toggle="modal" data-target="#add-visit-document"
            data-visit-id="{{ $visit->id }}" title="Upload Visit Documents" style="border: 1px solid #d3d3d3;">
            <i class="tio-upload"></i>
            {{ translate('Upload Files') }}
        </button>
    @endif

    @if (auth('admin')->user()->can('dental_chart.add-new') &&
            $visit->serviceCategory &&
            in_array('dental_chart', $visit->serviceCategory->service_type))
        <button class="btn btn-success rounded text-nowrap me-2 mb-2 mb-sm-0" id="add_new_dental_chart"
            type="button" data-toggle="modal" data-target="#add-dental-chart" data-visit-id="{{ $visit->id }}"
            title="Add Dental Chart">
            <i class="tio-chart-line-up"></i>
            {{ translate('Dental Chart') }}
        </button>
    @endif
</div>

@push('script_2')
    <script>
        $(document).on('click', '#add_new_custom_result', function() {
            var laboratoryRequestId = $(this).data('laboratory-request-id');
            $('#lab_test_custom_form input[name="laboratory_request_id"]').val(laboratoryRequestId);
            // Show loading and disable submit
            $('#attributes_container_custom').html('');
            $('#attributes_container_custom').html(
                '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
            );
            $('#submit_custom_result').prop('disabled', true);

            $.ajax({
                url: '{{ route('admin.laboratory_request.fetchTestTypeCustom') }}',
                type: 'GET',
                data: {
                    laboratoryRequestId: laboratoryRequestId
                },
                success: function(data) {
                    // Clear previous content
                    var $container = $('#attributes_container_custom');
                    $container.empty();
                    $('#submit_custom_result').prop('disabled', false);


                    // Add hidden input for laboratory request ID
                    $container.append(
                        '<input type="hidden" name="laboratory_request_id" value="' +
                        laboratoryRequestId + '">'
                    );

                    data.forEach(function(testData, testIndex) {
                        var test = testData.test;
                        var isFirst = (testIndex === 0);

                        // === Bootstrap Card wrapper ===
                        var card = $('<div>')
                            .addClass('card shadow-sm mb-3')
                            .css({
                                'border-left': '6px solid #6610f2', // side color (orange-red)
                                'border-radius': '0.5rem'
                            });

                        // IDs for header/collapse
                        var headerId = 'heading_' + testIndex;
                        var collapseId = 'collapse_' + testIndex;

                        var cardHeader = $('<div>')
                            .addClass(
                                'card-header d-flex justify-content-between align-items-center text-white'
                            )
                            .css({
                                'background': 'linear-gradient(90deg, #6610f2 0%, #007bff 100%)',
                                'border-radius': '0.5rem 0.5rem 0 0'
                            })
                            .attr('id', headerId);

                        cardHeader.append(
                            $('<h5>').addClass('mb-0 fw-bold').css({
                                'font-size': '1.25rem',
                                'color': 'white'
                            })
                            .text(test.test_name)
                        );

                        var toggleBtn = $('<button>')
                            .addClass('btn btn-sm btn-light')
                            .attr({
                                type: 'button',
                                'data-toggle': 'collapse', // ✅ Bootstrap 4
                                'data-target': '#' + collapseId, // ✅ Bootstrap 4
                                'aria-expanded': isFirst ? 'true' : 'false',
                                'aria-controls': collapseId
                            })
                            .toggleClass('collapsed', !isFirst)
                            .html('<i class="tio tio-chevron-down"></i>');

                        cardHeader.append(toggleBtn);
                        card.append(cardHeader);

                        // Collapsible body
                        var cardBody = $('<div>')
                            .attr({
                                id: collapseId,
                                'aria-labelledby': headerId
                            })
                            .addClass('collapse' + (isFirst ? ' show' : ''));

                        var bodyInner = $('<div>').addClass('card-body bg-white');

                        // Hidden inputs for test ID and laboratory_request_test_id
                        bodyInner.append(
                            $('<input>').attr({
                                type: 'hidden',
                                name: 'tests[' + testIndex + '][attributes][test_id]',
                                value: test.id
                            })
                        );

                        bodyInner.append(
                            $('<input>').attr({
                                type: 'hidden',
                                name: 'tests[' + testIndex +
                                    '][attributes][laboratory_request_test_id]',
                                value: testData.laboratory_request_test_id
                            })
                        );

                        // Group attributes by test_category
                        var groupedAttributes = {};
                        test.attributes.forEach(function(attribute) {
                            if (!groupedAttributes[attribute.test_category]) {
                                groupedAttributes[attribute.test_category] = [];
                            }
                            groupedAttributes[attribute.test_category].push(attribute);
                        });

                        // Create fields for each category
                        Object.keys(groupedAttributes).forEach(function(category) {
                            var categoryDiv = $('<div>').addClass(
                                'category-section mb-3');

                            // Category title
                            categoryDiv.append(
                                $('<h6>').addClass(
                                    'category-title mb-2 rounded text-center')
                                .css({
                                    'background': 'linear-gradient(90deg, #007bff 0%, #6610f2 100%)',
                                    'color': 'white',
                                })
                                .text(category)
                            );

                            // Row for attributes
                            var rowDiv = $('<div>').addClass('row');

                            // Add attributes
                            groupedAttributes[category].forEach(function(attribute) {
                                var colDiv = $('<div>').addClass(
                                    'col-md-4 mb-2');
                                var field;

                                if (attribute.attribute_type ===
                                    'Quantitative') {
                                    field = createQuantitativeField(attribute,
                                        testIndex);
                                } else if (attribute.attribute_type ===
                                    'Qualitative') {
                                    field = createQualitativeField(attribute,
                                        testIndex);
                                }

                                if (field) {
                                    colDiv.append(field);
                                    rowDiv.append(colDiv);
                                }
                            });

                            categoryDiv.append(rowDiv);
                            bodyInner.append(categoryDiv);
                        });

                        // Additional Note
                        var noteDiv = $('<div>').addClass('row pl-2 mb-2');
                        var noteCol = $('<div>').addClass('col-12');
                        var noteGroup = $('<div>').addClass('form-group');
                        noteGroup.append(
                            $('<h6>').addClass('category-title mb-2 rounded text-center')
                            .css({
                                'background': 'linear-gradient(90deg, #007bff 0%, #6610f2 100%)',
                                'color': 'white',
                            })
                            .text('Additional Note and Images')
                        );
                        noteGroup.append(
                            $('<label>').addClass('input-label')
                            .attr('for', 'tests_' + testIndex + '_additional_note')
                            .text('Additional Note')
                        );
                        noteGroup.append(
                            $('<textarea>').attr({
                                name: 'tests[' + testIndex +
                                    '][attributes][additional_note]',
                                id: 'tests_' + testIndex + '_additional_note',
                                class: 'form-control',
                                placeholder: 'Enter additional note'
                            })
                        );
                        noteCol.append(noteGroup);
                        noteDiv.append(noteCol);
                        bodyInner.append(noteDiv);

                        // Attach Photos
                        var photosDiv = $('<div>').addClass('mb-2');
                        photosDiv.append(
                            $('<label>').addClass('text-capitalize').text('Attach Photos')
                        );
                        var photosRow = $('<div>').addClass('row').attr('id', 'photos_' +
                            testIndex);
                        var photoCol = $('<div>').addClass('col-md-4 mb-2');
                        var fileWrapper = $('<div>').addClass('file-upload-wrapper');
                        fileWrapper.append(
                            $('<input>').attr({
                                type: 'file',
                                name: 'tests[' + testIndex + '][attributes][images][]',
                                class: 'file-upload',
                                accept: 'image/*',
                                multiple: true
                            })
                        );
                        photoCol.append(fileWrapper);
                        photosRow.append(photoCol);
                        photosDiv.append(photosRow);
                        bodyInner.append(photosDiv);

                        // Mount body
                        cardBody.append(bodyInner);
                        card.append(cardBody);

                        // Add card to container
                        $container.append(card);

                        // === NEW: Add event listener for Bootstrap collapse ===
                        cardBody.on('show.bs.collapse', function() {
                            // Find all required fields inside this collapsing card body
                            $(this).find('[data-is-required="true"]').prop('required',
                                true);
                        }).on('hide.bs.collapse', function() {
                            // Find all required fields inside this collapsing card body
                            $(this).find('[data-is-required="true"]').prop('required',
                                false);
                        });

                        // If the card is initially shown, apply the required attributes
                        if (isFirst) {
                            cardBody.trigger('show.bs.collapse');
                        }
                    });
                },
                error: function(error) {
                    $('#attributes_container_custom').html(
                        '<div class="alert alert-danger text-center">Failed to load data. Please try again.</div>'
                    );
                    $('#submit_custom_result').prop('disabled', false);
                }
            });
        });

        // Helper function to create quantitative field
        function createQuantitativeField(attribute, testIndex) {
            var fieldGroup = $('<div>').addClass('form-group');

            // Create the label element
            var label = $('<label>').attr('for', 'test_' + testIndex + '_attr_' + attribute.id)
                .text(attribute.attribute_name)
                .addClass('input-label');

            // Add a required indicator if default_required is true
            if (attribute.default_required == 1) {
                label.append(
                    $('<span>').addClass('input-label-secondary text-danger').text('*')
                );
            }
            fieldGroup.append(label);

            var input = $('<input>').attr({
                type: 'number',
                step: 'any',
                name: 'tests[' + testIndex + '][attributes][attribute_' + attribute.id + ']',
                id: 'test_' + testIndex + '_attr_' + attribute.id,
                class: 'form-control',
                placeholder: 'Enter value'
            });

            // Set a custom data attribute instead of the required attribute
            if (attribute.default_required == 1) {
                input.attr('data-is-required', 'true');
            }

            if (attribute.unit_id) {
                var inputGroup = $('<div>').addClass('input-group');
                inputGroup.append(input);
                inputGroup.append(
                    $('<span>').addClass('input-group-text').text(attribute.unit.code)
                );
                fieldGroup.append(inputGroup);
            } else {
                fieldGroup.append(input);
            }

            return fieldGroup;
        }

        // Helper function to create qualitative field
        function createQualitativeField(attribute, testIndex) {
            var fieldGroup = $('<div>').addClass('form-group');

            // Create the label element
            var label = $('<label>').attr('for', 'test_' + testIndex + '_attr_' + attribute.id)
                .text(attribute.attribute_name)
                .addClass('input-label');

            // Add a required indicator if default_required is true
            if (attribute.default_required == 1) {
                label.append(
                    $('<span>').addClass('input-label-secondary text-danger').text('*')
                );
            }
            fieldGroup.append(label);

            var select = $('<select>').attr({
                name: 'tests[' + testIndex + '][attributes][attribute_' + attribute.id + ']',
                id: 'test_' + testIndex + '_attr_' + attribute.id,
                class: 'form-control'
            });

            // Set a custom data attribute instead of the required attribute
            if (attribute.default_required == 1) {
                select.attr('data-is-required', 'true');
            }

            select.append($('<option>').attr('value', '').text('Select an option'));

            attribute.options.forEach(function(option) {
                select.append(
                    $('<option>').attr('value', option.option_value).text(option.option_value)
                );
            });

            fieldGroup.append(select);
            return fieldGroup;
        }

        // Visit Document Upload Functionality
        $(document).on('click', '#add_new_visit_document', function() {
            var visitId = $(this).data('visit-id');
            $('#visit_document_visit_id').val(visitId);
        });

        // File preview functionality
        $('#files').on('change', function() {
            var files = this.files;
            var previewContainer = $('#previewContainer');
            var filePreview = $('#filePreview');

            previewContainer.empty();

            if (files.length > 0) {
                filePreview.show();

                Array.from(files).forEach(function(file) {
                    var previewItem = $('<div class="col-md-4 mb-2">');
                    var fileItem = $('<div class="file-preview-item">');

                    if (file.type.startsWith('image/')) {
                        var img = $('<img>').attr('src', URL.createObjectURL(file));
                        fileItem.append(img);
                    } else {
                        var icon = $('<i class="tio-file" style="font-size: 2rem; color: #6c757d;"></i>');
                        fileItem.append(icon);
                    }

                    var fileName = $('<div class="mt-2"><small class="text-truncate d-block" title="' + file
                        .name + '">' + file.name + '</small></div>');
                    var fileSize = $('<div><small class="text-muted">' + formatFileSize(file.size) +
                        '</small></div>');

                    fileItem.append(fileName);
                    fileItem.append(fileSize);
                    previewItem.append(fileItem);
                    previewContainer.append(previewItem);
                });
            } else {
                filePreview.hide();
            }
        });

        // Visit document form submission
        $('#visitDocumentForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var submitButton = $('#submitVisitDocument');
            var originalText = submitButton.html();

            // Disable submit button and show loading
            submitButton.prop('disabled', true).html('<i class="tio-loading mr-1"></i>Uploading...');

            $.ajax({
                url: '{{ route('admin.visit_document.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#add-visit-document').modal('hide');
                        // Reload the page to show new documents
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while uploading documents.');
                    }
                },
                complete: function() {
                    submitButton.prop('disabled', false).html(originalText);
                }
            });
        });

        // Edit document note
        function editDocumentNote(documentId, currentNote) {
            $('#edit_document_id').val(documentId);
            $('#edit_note').val(currentNote);
            $('#edit-visit-document').modal('show');
        }

        // Edit document form submission
        $('#editVisitDocumentForm').on('submit', function(e) {
            e.preventDefault();

            var documentId = $('#edit_document_id').val();
            var formData = {
                note: $('#edit_note').val(),
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };

            var submitButton = $('#submitEditVisitDocument');
            var originalText = submitButton.html();

            submitButton.prop('disabled', true).html('<i class="tio-loading mr-1"></i>Updating...');

            $.ajax({
                url: '{{ route('admin.visit_document.update', '') }}/' + documentId,
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#edit-visit-document').modal('hide');
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while updating the document.');
                    }
                },
                complete: function() {
                    submitButton.prop('disabled', false).html(originalText);
                }
            });
        });

        // Delete document
        function deleteDocument(documentId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this document? This action cannot be undone.",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.visit_document.delete', '') }}/' + documentId,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                location.reload();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error('An error occurred while deleting the document.');
                            }
                        }
                    });
                }
            });
        }

        // View document
        function viewDocument(documentId) {
            $('#documentViewerContent').html(
                '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            $('#documentViewerModal').modal('show');

            // Set download link
            $('#downloadDocumentBtn').attr('href', '{{ route('admin.visit_document.download', '') }}/' + documentId);

            // Load document content
            var viewUrl = '{{ route('admin.visit_document.view', '') }}/' + documentId;
            $('#documentViewerContent').html('<iframe src="' + viewUrl +
                '" style="width: 100%; height: 70vh; border: none;"></iframe>');
        }

        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            var k = 1024;
            var sizes = ['Bytes', 'KB', 'MB', 'GB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>

    <!-- Dental Charting JavaScript -->
    <script>
        let dentalChartCanvas = null;
        let dentalChartFabric = null;
        let currentTool = 'select';
        let undoHistory = [];

        // Initialize dental chart modal
        $(document).on('click', '#add_new_dental_chart', function() {
            var visitId = $(this).data('visit-id');
            $('#dental_chart_visit_id').val(visitId);

            // Reset form
            $('#dentalChartForm')[0].reset();
            $('#chart_type').val('');
            $('#image_upload_group').hide();

            // Initialize canvas when modal opens
            $('#add-dental-chart').on('shown.bs.modal', function() {
                initializeDentalChartCanvas();
            });
        });

        // Initialize canvas
        function initializeDentalChartCanvas() {
            const canvasElement = document.getElementById('dentalChartCanvas');
            if (!canvasElement) return;

            // Get container width for responsive canvas
            const container = canvasElement.parentElement;
            const containerWidth = container.clientWidth - 20; // Subtract padding

            // Check chart type for periodontal chart (special dimensions)
            const chartType = $('#chart_type').val();
            let canvasWidth, canvasHeight;

            if (chartType === 'periodontal') {
                // Periodontal chart specific dimensions to match image size (1024x1280)
                canvasWidth = 1024;
                canvasHeight = 1280;
            } else {
                // Default dimensions for other chart types
                canvasWidth = Math.max(800, containerWidth); // Minimum 800px width
                canvasHeight = 600;
            }

            dentalChartFabric = new fabric.Canvas('dentalChartCanvas', {
                width: canvasWidth,
                height: canvasHeight,
                backgroundColor: '#ffffff'
            });

            // Update canvas element size to match fabric canvas
            canvasElement.style.width = canvasWidth + 'px';
            canvasElement.style.height = canvasHeight + 'px';

            // Reset undo history
            undoHistory = [];

            // Save initial state
            saveState();

            // Save state after any drawing/modification/deletion
            dentalChartFabric.on('object:added', function() {
                saveState();
            });

            dentalChartFabric.on('object:modified', function() {
                saveState();
            });

            dentalChartFabric.on('object:removed', function() {
                saveState();
            });

            dentalChartFabric.on('path:created', function() {
                saveState();
            });

            // Tool selection
            $('[data-tool]').on('click', function() {
                $('[data-tool]').removeClass('active');
                $(this).addClass('active');
                currentTool = $(this).data('tool');
                setTool();
            });

            // Color picker
            $('#strokeColor').on('change', function() {
                if (dentalChartFabric) {
                    dentalChartFabric.freeDrawingBrush.color = this.value;
                }
            });

            // Undo button
            $('#undoCanvas').on('click', function() {
                undo();
            });

            // Clear button
            $('#clearCanvas').on('click', function() {
                Swal.fire({
                    title: '{{ translate('Are you sure?') }}',
                    text: 'Are you sure you want to clear the canvass?',
                    showCancelButton: true,
                    cancelButtonColor: '#3085d6',
                    confirmButtonColor: '#d33',
                    cancelButtonText: '{{ translate('No') }}',
                    confirmButtonText: '{{ translate('Yes') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        dentalChartFabric.clear();
                        dentalChartFabric.backgroundColor = '#ffffff';
                        dentalChartFabric.renderAll();
                        saveState();
                    }
                });
            });

            // Save draft button
            $('#saveDraft').on('click', function() {
                saveChartData();
                toastr.success('Draft saved locally');
            });

            // Chart type change
            $('#chart_type').on('change', function() {
                const chartType = $(this).val();
                if (chartType === 'image_annotation') {
                    $('#image_upload_group').show();
                } else {
                    $('#image_upload_group').hide();
                }

                // Resize canvas based on chart type
                if (dentalChartFabric) {
                    let newWidth, newHeight;

                    if (chartType === 'periodontal') {
                        // Periodontal chart dimensions (1024x1280)
                        newWidth = 1024;
                        newHeight = 1280;
                    } else {
                        // Default dimensions for other chart types
                        const container = document.getElementById('dentalChartCanvas').parentElement;
                        const containerWidth = container.clientWidth - 20;
                        newWidth = Math.max(800, containerWidth);
                        newHeight = 600;
                    }

                    // Resize canvas
                    dentalChartFabric.setDimensions({
                        width: newWidth,
                        height: newHeight
                    });

                    // Update canvas element size
                    const canvasElement = document.getElementById('dentalChartCanvas');
                    canvasElement.style.width = newWidth + 'px';
                    canvasElement.style.height = newHeight + 'px';

                    // Load periodontal chart template if selected
                    if (chartType === 'periodontal') {
                        loadPeriodontalChartTemplate();
                    } else if (chartType !== 'image_annotation') {
                        // Clear background if switching away from periodontal (unless it's image annotation)
                        dentalChartFabric.setBackgroundImage('', dentalChartFabric.renderAll.bind(
                            dentalChartFabric));
                        saveState();
                    }
                }
            });

            // Image upload handler
            $('#chart_image').on('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        fabric.Image.fromURL(event.target.result, function(img) {
                            img.scaleToWidth(dentalChartFabric.width);
                            img.scaleToHeight(dentalChartFabric.height);
                            dentalChartFabric.setBackgroundImage(img, dentalChartFabric.renderAll.bind(
                                dentalChartFabric));
                            saveState();
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            setTool();
        }

        // Load periodontal chart template
        function loadPeriodontalChartTemplate() {
            if (!dentalChartFabric) return;

            const templateUrl = '{{ asset('assets/admin/img/dental-templates/periodontal-chart.png') }}';

            // Clear canvas first
            dentalChartFabric.clear();
            dentalChartFabric.backgroundColor = '#ffffff';

            // Load the periodontal chart template as background image
            fabric.Image.fromURL(templateUrl, function(img) {
                if (img) {
                    // Image dimensions are 1024x1280, canvas should match exactly
                    // No scaling needed - just place at 0,0
                    img.set({
                        left: 0,
                        top: 0,
                        selectable: false,
                        evented: false,
                        scaleX: 1,
                        scaleY: 1
                    });

                    dentalChartFabric.setBackgroundImage(img, function() {
                        dentalChartFabric.renderAll();
                        saveState();
                    }, {
                        crossOrigin: 'anonymous'
                    });
                } else {
                    // If image fails to load, show a message
                    console.error('Periodontal chart template not found. Please ensure the image is at: ' +
                        templateUrl);
                    toastr.warning('Periodontal chart template not found. Please contact administrator.');
                }
            }, {
                crossOrigin: 'anonymous'
            });
        }

        // Set drawing tool
        function setTool() {
            if (!dentalChartFabric) return;

            dentalChartFabric.isDrawingMode = false;
            dentalChartFabric.selection = true;
            dentalChartFabric.defaultCursor = 'default';

            switch (currentTool) {
                case 'select':
                    dentalChartFabric.isDrawingMode = false;
                    dentalChartFabric.selection = true;
                    break;
                case 'path':
                    dentalChartFabric.isDrawingMode = true;
                    dentalChartFabric.freeDrawingBrush.color = $('#strokeColor').val();
                    dentalChartFabric.freeDrawingBrush.width = 2;
                    break;
                case 'circle':
                    dentalChartFabric.on('mouse:down', createCircle);
                    break;
                case 'rect':
                    dentalChartFabric.on('mouse:down', createRect);
                    break;
                case 'line':
                    dentalChartFabric.on('mouse:down', createLine);
                    break;
                case 'text':
                    dentalChartFabric.on('mouse:down', createText);
                    break;
            }

        }

        // Create shapes
        function createCircle(opts) {
            if (currentTool !== 'circle') return;
            const pointer = dentalChartFabric.getPointer(opts.e);
            const circle = new fabric.Circle({
                left: pointer.x,
                top: pointer.y,
                radius: 20,
                fill: '',
                stroke: $('#strokeColor').val(),
                strokeWidth: 2
            });
            dentalChartFabric.add(circle);
            dentalChartFabric.off('mouse:down', createCircle);
        }

        function createRect(opts) {
            if (currentTool !== 'rect') return;
            const pointer = dentalChartFabric.getPointer(opts.e);
            const rect = new fabric.Rect({
                left: pointer.x,
                top: pointer.y,
                width: 50,
                height: 50,
                fill: '',
                stroke: $('#strokeColor').val(),
                strokeWidth: 2
            });
            dentalChartFabric.add(rect);
            dentalChartFabric.off('mouse:down', createRect);
        }

        function createLine(opts) {
            if (currentTool !== 'line') return;
            const pointer = dentalChartFabric.getPointer(opts.e);
            const line = new fabric.Line([pointer.x, pointer.y, pointer.x + 50, pointer.y], {
                stroke: $('#strokeColor').val(),
                strokeWidth: 2
            });
            dentalChartFabric.add(line);
            dentalChartFabric.off('mouse:down', createLine);
        }

        function createText(opts) {
            if (currentTool !== 'text') return;
            const pointer = dentalChartFabric.getPointer(opts.e);
            const text = new fabric.Text('Text', {
                left: pointer.x,
                top: pointer.y,
                fontSize: 20,
                fill: $('#strokeColor').val()
            });
            dentalChartFabric.add(text);
            dentalChartFabric.off('mouse:down', createText);
        }

        // Undo functionality
        function saveState() {
            if (dentalChartFabric) {
                const state = dentalChartFabric.toJSON();
                undoHistory.push(JSON.stringify(state));
                if (undoHistory.length > 20) {
                    undoHistory.shift();
                }
            }
        }

        function undo() {
            if (undoHistory.length > 1) {
                undoHistory.pop(); // Remove current state
                const previousState = undoHistory[undoHistory.length - 1];
                if (previousState) {
                    dentalChartFabric.loadFromJSON(previousState, function() {
                        dentalChartFabric.renderAll();
                    });
                }
            } else {}
        }

        // Save chart data
        function saveChartData() {
            if (!dentalChartFabric) return;
            const chartData = dentalChartFabric.toJSON();
            $('#chart_data_json').val(JSON.stringify(chartData));
        }

        // Dental Chart Form Submission
        $('#dentalChartForm').on('submit', function(e) {
            e.preventDefault();

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            // Save canvas data
            saveChartData();

            const formData = new FormData(this);

            $.ajax({
                url: '{{ route('admin.dental_chart.store') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#add-dental-chart').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred while saving the chart.');
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        // View Dental Chart
        function viewDentalChart(chartId) {
            $.ajax({
                url: '{{ route('admin.dental_chart.edit', '') }}/' + chartId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const chart = response.data;

                        // Set modal title
                        $('#viewDentalChartLabel').text(chart.title ? chart.title : 'View Dental Chart');

                        // Set chart info
                        const chartInfo = `
                            <div class="text-left">
                                <h6 class="mb-2"><strong>${chart.title ? chart.title : chart.chart_type}</strong>
                                    <span class="badge badge-info">${chart.chart_type}</span>
                                </h6>
                                ${chart.notes ? '<p class="mb-1 text-muted"><strong>Notes:</strong> ' + chart.notes + '</p>' : ''}
                                <small class="text-muted">Created on ${new Date(chart.created_at).toLocaleString()}</small>
                            </div>
                        `;
                        $('#viewChartInfo').html(chartInfo);

                        // Initialize view canvas when modal opens
                        $('#viewDentalChartModal').off('shown.bs.modal').on('shown.bs.modal', function() {
                            // Small delay to ensure modal is fully rendered
                            setTimeout(function() {
                                initializeViewDentalChartCanvas(chart);
                            }, 100);
                        });

                        $('#viewDentalChartModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error loading chart data.');
                }
            });
        }

        // Initialize view canvas
        function initializeViewDentalChartCanvas(chart) {
            const canvasElement = document.getElementById('viewDentalChartCanvas');
            if (!canvasElement) return;

            // Determine canvas dimensions based on chart type
            let originalWidth, originalHeight;

            if (chart.chart_type === 'periodontal') {
                originalWidth = 1024;
                originalHeight = 1280;
            } else {
                originalWidth = 800;
                originalHeight = 600;
            }

            // Get the actual modal body width after modal is shown
            const modalBody = canvasElement.closest('.modal-body');

            // Get actual dimensions of modal content area
            // Use modal body width minus padding (40px on each side = 80px total, plus some extra margin)
            const modalContentWidth = modalBody ? (modalBody.clientWidth - 100) : (window.innerWidth * 0.9 - 100);

            // Calculate scale based on width only - fill modal width with padding
            const scale = modalContentWidth / originalWidth;

            // Calculate canvas dimensions maintaining aspect ratio
            const canvasWidth = modalContentWidth; // Use full available width
            const canvasHeight = originalHeight * scale; // Height based on aspect ratio

            // Create or clear existing canvas
            if (window.viewChartCanvas) {
                window.viewChartCanvas.dispose();
            }

            window.viewChartCanvas = new fabric.Canvas('viewDentalChartCanvas', {
                width: canvasWidth,
                height: canvasHeight,
                backgroundColor: '#ffffff',
                selection: false, // Disable selection in view mode
                interactive: false // Disable interactions in view mode
            });

            // Set canvas element size
            // Use 100% width to fill container, height in pixels to maintain aspect ratio
            canvasElement.style.width = '100%';
            canvasElement.style.height = canvasHeight + 'px';

            // Set the actual canvas internal width/height to match display
            // This ensures Fabric.js renders correctly at full width
            canvasElement.width = canvasWidth;
            canvasElement.height = canvasHeight;

            // Also set the parent container to full width
            const canvasContainer = canvasElement.parentElement;
            if (canvasContainer) {
                canvasContainer.style.width = '100%';
                canvasContainer.style.display = 'block';
            }

            // Load chart data and scale it
            if (chart.chart_data) {
                let chartData = chart.chart_data;

                // If it's a string, parse it; if it's already an object, use it directly
                if (typeof chartData === 'string') {
                    chartData = JSON.parse(chartData);
                }

                // Scale the chart data before loading
                scaleChartData(chartData, scale);

                // Update canvas dimensions in chart data
                chartData.width = canvasWidth;
                chartData.height = canvasHeight;

                // Load background image first if exists (for image_annotation type)
                if (chart.image_path) {
                    const imageUrl = '{{ asset('storage') }}/' + chart.image_path;
                    fabric.Image.fromURL(imageUrl, function(img) {
                        img.scale(scale);
                        window.viewChartCanvas.setBackgroundImage(img, function() {
                            // Then load chart data
                            window.viewChartCanvas.loadFromJSON(chartData, function() {
                                window.viewChartCanvas.renderAll();
                            });
                        }, {
                            crossOrigin: 'anonymous'
                        });
                    }, {
                        crossOrigin: 'anonymous'
                    });
                } else if (chart.chart_type === 'periodontal') {
                    // Load periodontal template if it's a periodontal chart
                    const templateUrl = '{{ asset('assets/admin/img/dental-templates/periodontal-chart.png') }}';
                    fabric.Image.fromURL(templateUrl, function(img) {
                        img.scale(scale);
                        window.viewChartCanvas.setBackgroundImage(img, function() {
                            // Then load chart data
                            window.viewChartCanvas.loadFromJSON(chartData, function() {
                                window.viewChartCanvas.renderAll();
                            });
                        }, {
                            crossOrigin: 'anonymous'
                        });
                    }, {
                        crossOrigin: 'anonymous'
                    });
                } else {
                    // Load chart data directly
                    window.viewChartCanvas.loadFromJSON(chartData, function() {
                        window.viewChartCanvas.renderAll();
                    });
                }
            } else {
                window.viewChartCanvas.renderAll();
            }
        }

        // Helper function to scale chart data
        function scaleChartData(chartData, scale) {
            // Scale all objects
            if (chartData.objects && Array.isArray(chartData.objects)) {
                chartData.objects.forEach(function(obj) {
                    if (obj.left !== undefined) obj.left = (obj.left || 0) * scale;
                    if (obj.top !== undefined) obj.top = (obj.top || 0) * scale;
                    if (obj.scaleX !== undefined) obj.scaleX = (obj.scaleX || 1) * scale;
                    if (obj.scaleY !== undefined) obj.scaleY = (obj.scaleY || 1) * scale;
                    if (obj.width !== undefined) obj.width = (obj.width || 0) * scale;
                    if (obj.height !== undefined) obj.height = (obj.height || 0) * scale;
                    if (obj.strokeWidth !== undefined) obj.strokeWidth = (obj.strokeWidth || 1) * scale;
                    if (obj.fontSize !== undefined) obj.fontSize = (obj.fontSize || 16) * scale;
                    if (obj.radius !== undefined) obj.radius = (obj.radius || 0) * scale;
                    if (obj.rx !== undefined) obj.rx = (obj.rx || 0) * scale;
                    if (obj.ry !== undefined) obj.ry = (obj.ry || 0) * scale;
                });
            }

            // Scale background image if exists
            if (chartData.backgroundImage) {
                if (chartData.backgroundImage.scaleX !== undefined) {
                    chartData.backgroundImage.scaleX = (chartData.backgroundImage.scaleX || 1) * scale;
                }
                if (chartData.backgroundImage.scaleY !== undefined) {
                    chartData.backgroundImage.scaleY = (chartData.backgroundImage.scaleY || 1) * scale;
                }
                if (chartData.backgroundImage.left !== undefined) {
                    chartData.backgroundImage.left = (chartData.backgroundImage.left || 0) * scale;
                }
                if (chartData.backgroundImage.top !== undefined) {
                    chartData.backgroundImage.top = (chartData.backgroundImage.top || 0) * scale;
                }
            }
        }

        // Clean up view canvas when modal is closed
        $('#viewDentalChartModal').on('hidden.bs.modal', function() {
            if (window.viewChartCanvas) {
                window.viewChartCanvas.dispose();
                window.viewChartCanvas = null;
            }
        });

        // Edit Dental Chart
        function editDentalChart(chartId) {
            $.ajax({
                url: '{{ route('admin.dental_chart.edit', '') }}/' + chartId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const chart = response.data;
                        $('#edit_chart_id').val(chart.id);
                        $('#edit_chart_type').val(chart.chart_type);
                        $('#edit_chart_title').val(chart.title);
                        $('#edit_chart_notes').val(chart.notes);

                        // Initialize edit canvas
                        $('#editDentalChartModal').on('shown.bs.modal', function() {
                            initializeEditDentalChartCanvas(chart);
                        });

                        $('#editDentalChartModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error loading chart data.');
                }
            });
        }

        // Initialize edit canvas
        function initializeEditDentalChartCanvas(chart) {
            const canvasElement = document.getElementById('editDentalChartCanvas');
            if (!canvasElement) return;

            // Get container width for responsive canvas
            const container = canvasElement.parentElement;
            const containerWidth = container.clientWidth - 20; // Subtract padding

            // Determine canvas dimensions based on chart type
            let canvasWidth, canvasHeight;

            if (chart.chart_type === 'periodontal') {
                // Periodontal chart specific dimensions to match image size (1024x1280)
                canvasWidth = 1024;
                canvasHeight = 1280;
            } else {
                // Default dimensions for other chart types
                canvasWidth = Math.max(800, containerWidth); // Minimum 800px width
                canvasHeight = 600;
            }

            dentalChartFabric = new fabric.Canvas('editDentalChartCanvas', {
                width: canvasWidth,
                height: canvasHeight,
                backgroundColor: '#ffffff'
            });

            // Update canvas element size to match fabric canvas
            canvasElement.style.width = canvasWidth + 'px';
            canvasElement.style.height = canvasHeight + 'px';

            // Load background image first if it exists (for image_annotation type)
            if (chart.image_path) {
                const imageUrl = '{{ asset('storage') }}/' + chart.image_path;
                fabric.Image.fromURL(imageUrl, function(img) {
                    img.scaleToWidth(dentalChartFabric.width);
                    img.scaleToHeight(dentalChartFabric.height);
                    dentalChartFabric.setBackgroundImage(img, function() {
                        // Load chart data after background image is set
                        loadChartDataForEdit(chart);
                    }, {
                        crossOrigin: 'anonymous'
                    });
                }, {
                    crossOrigin: 'anonymous'
                });
            } else if (chart.chart_type === 'periodontal') {
                // For periodontal charts, load template first if no background image exists
                const templateUrl = '{{ asset('assets/admin/img/dental-templates/periodontal-chart.png') }}';
                fabric.Image.fromURL(templateUrl, function(img) {
                    if (img) {
                        // Image dimensions are 1024x1280, canvas should match exactly
                        // No scaling needed - just place at 0,0
                        img.set({
                            left: 0,
                            top: 0,
                            selectable: false,
                            evented: false,
                            scaleX: 1,
                            scaleY: 1
                        });
                        dentalChartFabric.setBackgroundImage(img, function() {
                            // Load chart data after template is set
                            loadChartDataForEdit(chart);
                        }, {
                            crossOrigin: 'anonymous'
                        });
                    } else {
                        // If template fails to load, just load chart data
                        loadChartDataForEdit(chart);
                    }
                }, {
                    crossOrigin: 'anonymous'
                });
            } else {
                // Load chart data directly (background should be in JSON if it exists)
                loadChartDataForEdit(chart);
            }

            function loadChartDataForEdit(chart) {
                if (chart.chart_data) {
                    let chartData = chart.chart_data;
                    // If it's a string, parse it; if it's already an object, use it directly
                    if (typeof chartData === 'string') {
                        chartData = JSON.parse(chartData);
                    }
                    dentalChartFabric.loadFromJSON(chartData, function() {
                        dentalChartFabric.renderAll();
                    });
                } else {
                    dentalChartFabric.renderAll();
                }
            }

            // Reset undo history
            undoHistory = [];

            // Save initial state after loading
            setTimeout(function() {
                saveState();
            }, 100);

            // Save state after any drawing/modification/deletion
            dentalChartFabric.on('object:added', function() {
                saveState();
            });

            dentalChartFabric.on('object:modified', function() {
                saveState();
            });

            dentalChartFabric.on('object:removed', function() {
                saveState();
            });

            dentalChartFabric.on('path:created', function() {
                saveState();
            });

            // Set up tools (same as create)
            $('[data-tool]').on('click', function() {
                $('[data-tool]').removeClass('active');
                $(this).addClass('active');
                currentTool = $(this).data('tool');
                setTool();
            });

            $('#strokeColor').on('change', function() {
                if (dentalChartFabric) {
                    dentalChartFabric.freeDrawingBrush.color = this.value;
                }
            });

            $('#editStrokeColor').on('change', function() {
                if (dentalChartFabric) {
                    dentalChartFabric.freeDrawingBrush.color = this.value;
                }
            });

            $('#editClearCanvas').on('click', function() {
                Swal.fire({
                    title: '{{ translate('Are you sure?') }}',
                    text: 'Are you sure you want to clear the canvas?',
                    showCancelButton: true,
                    cancelButtonColor: '#3085d6',
                    confirmButtonColor: '#d33',
                    cancelButtonText: '{{ translate('No') }}',
                    confirmButtonText: '{{ translate('Yes') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        dentalChartFabric.clear();
                        dentalChartFabric.backgroundColor = '#ffffff';
                        dentalChartFabric.renderAll();
                        saveState();
                    }
                });
            });

            $('#editUndoCanvas').on('click', function() {
                undo();
            });

            setTool();
        }

        // Edit form submission
        $('#editDentalChartForm').on('submit', function(e) {
            e.preventDefault();

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            saveChartData();
            $('#edit_chart_data_json').val($('#chart_data_json').val());

            const formData = new FormData(this);
            const chartId = $('#edit_chart_id').val();

            // Ensure chart_data is included
            if ($('#chart_data_json').val()) {
                formData.append('chart_data', $('#chart_data_json').val());
            }

            $.ajax({
                url: '{{ route('admin.dental_chart.update', '') }}/' + chartId,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#editDentalChartModal').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred while updating the chart.');
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        // Delete Dental Chart
        function deleteDentalChart(chartId) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: '{{ translate('You want to delete this dental chart?') }}',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('No') }}',
                confirmButtonText: '{{ translate('Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.dental_chart.delete', '') }}/' + chartId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                const currentUrl = new URL(window.location.href);
                                currentUrl.searchParams.set('active', response.visit_id);
                                location.href = currentUrl.toString();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('{{ translate('Failed to delete dental chart') }}');
                        }
                    });
                }
            });
        }

        // Load charts in view mode
        $(document).ready(function() {
            $('[id^="chart-canvas-"]').each(function() {
                const canvasId = $(this).attr('id');
                const chartId = canvasId.replace('chart-canvas-', '');

                // Fetch chart data and render
                // This would be done via AJAX or passed from backend
                // For now, placeholder
            });
        });
    </script>
@endpush

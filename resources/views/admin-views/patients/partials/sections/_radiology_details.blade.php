@if (
    (auth('admin')->user()->can('radiology_result.add-new') ||
        auth('admin')->user()->can('radiology_request.add-new') ||
        auth('admin')->user()->can('medical_record.add-new') ||
        auth('admin')->user()->can('radiology_result.list')) &&
        $visit->radiologyRequest)

    <!-- Radiology Details Tabs -->
    <fieldset class="border border-primary mt-3 p-3 rounded">
        <legend class="float-none w-auto px-3 py-1 bg-light border border-primary rounded-sm"
            style="font-weight: bold; font-size: 18px; color:white; background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%)">
            <div class="pr-1">
                Radiology Details
            </div>
        </legend>

        <div class="p-3">
            <ul class="nav nav-tabs" id="radiologyTabs" role="tablist">
                @if (auth('admin')->user()->can('radiology_result.add-new') ||
                        auth('admin')->user()->can('radiology_request.add-new') ||
                        auth('admin')->user()->can('medical_record.add-new'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="rad-request-tab" data-toggle="tab" href="#rad-request"
                            role="tab" aria-controls="rad-request" aria-selected="true">
                            <i class="tio-document-text mr-1"></i>Request Details
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('radiology_result.add-new') || auth('admin')->user()->can('radiology_result.list'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('radiology_result.add-new') || auth('admin')->user()->can('radiology_request.add-new') || auth('admin')->user()->can('medical_record.add-new')) ? 'active' : '' }}"
                            id="rad-results-tab" data-toggle="tab" href="#rad-results" role="tab"
                            aria-controls="rad-results"
                            aria-selected="{{ !(auth('admin')->user()->can('radiology_result.add-new') || auth('admin')->user()->can('radiology_request.add-new') || auth('admin')->user()->can('medical_record.add-new')) ? 'true' : 'false' }}">
                            <i class="tio-checkmark-circle mr-1"></i>Radiology Results
                        </a>
                    </li>
                @endif
            </ul>

            <div class="tab-content border border-primary rounded-bottom" id="radiologyTabsContent">
                <!-- Request Details Tab -->
                @if (auth('admin')->user()->can('radiology_result.add-new') ||
                        auth('admin')->user()->can('radiology_request.add-new') ||
                        auth('admin')->user()->can('medical_record.add-new'))
                    <div class="tab-pane fade show active p-3" id="rad-request" role="tabpanel"
                        aria-labelledby="rad-request-tab">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Referring Doctor:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->radiologyRequest->referring_dr ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Referring Institution:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->radiologyRequest->referring_institution ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Card Number:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->radiologyRequest->card_no ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Hospital Ward:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->radiologyRequest->hospital_ward ?? 'Not Specified' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Requested By:</strong>
                                <p class="mb-0 text-muted">
                                    {{ ucfirst($visit->radiologyRequest->requested_by) }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Order Status:</strong>
                                <p class="mb-0 text-muted">
                                    {{ ucfirst($visit->radiologyRequest->order_status) }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Fasting Required:</strong>
                                <p class="mb-0 text-muted">
                                    {{ ucfirst($visit->radiologyRequest->fasting) }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Relevant Clinical Data:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->radiologyRequest->relevant_clinical_data ?? 'No Details Provided' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Current Medication:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->radiologyRequest->current_medication ?? 'No Details Provided' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Additional Note:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->radiologyRequest->additional_note ?? 'No Additional Notes' }}
                                </p>
                            </div>

                            @php
                                $hasInactiveRadiology = $visit->radiologyRequest->radiologies->contains(function (
                                    $radiologies,
                                ) {
                                    return !$radiologies->radiology->is_active; // Look for at least one inactive test
                                });
                            @endphp

                            @if (auth('admin')->user()->can('radiology_request.pdf') && $hasInactiveRadiology)
                                <div class="text-end mt-3">
                                    <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal"
                                        data-target="#radpdfModal"
                                        onclick="loadRadPdf('{{ route('admin.radiology_request.pdf', $visit->id) }}')">
                                        View Radiology Request PDF
                                    </a>
                                </div>
                            @endif

                            @if (auth('admin')->user()->can('radiology_request.edit') &&
                                    !optional($visit->radiologyRequest)->radiologyResults2->isNotEmpty() &&
                                    (!$visit->radiologyRequest->billing ||
                                        optional(optional($visit->radiologyRequest)->billing)->status == 'unpaid'))
                                <div class="col-12 text-right">
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="editRadiologyRequest({{ $visit->radiologyRequest->id }})">
                                        <i class="tio-edit"></i>
                                        {{ translate('Edit') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Radiology Results Tab -->
                @if (auth('admin')->user()->can('radiology_result.add-new') || auth('admin')->user()->can('radiology_result.list'))
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('radiology_result.add-new') || auth('admin')->user()->can('radiology_request.add-new') || auth('admin')->user()->can('medical_record.add-new')) ? 'show active' : '' }} p-3"
                        id="rad-results" role="tabpanel" aria-labelledby="rad-results-tab">
                        @php
                            $radiologyResults = optional($visit->radiologyRequest)->radiologyResults2;
                        @endphp
                        @if ($radiologyResults && $radiologyResults->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Radiology</th>
                                            <th>Process Status</th>
                                            <th>Verify Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($radiologyResults as $radiologyResult)
                                            <tr>
                                                <td>{{ $radiologyResult->radiologyRequestTest->radiology->radiology_name ?? 'Not Specified' }}
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        class="badge badge-{{ $radiologyResult->process_status == 'completed' ? 'success' : ($radiologyResult->process_status == 'in process' ? 'warning' : ($radiologyResult->process_status == 'rejected' ? 'danger' : 'info')) }}"
                                                        onclick="handleRadiologyResultStatusClick('{{ $radiologyResult->id }}', '{{ $radiologyResult->process_status }}')">
                                                        {{ ucwords($radiologyResult->process_status) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        class="badge badge-{{ $radiologyResult->verify_status == 'approved' ? 'success' : ($radiologyResult->verify_status == 'checking' ? 'warning' : ($radiologyResult->verify_status == 'rejected' ? 'danger' : 'info')) }}"
                                                        onclick="handleRadiologyResultApprovalStatusClick('{{ $radiologyResult->id }}', '{{ $radiologyResult->verify_status }}')">
                                                        {{ ucwords($radiologyResult->verify_status) }}
                                                    </a>
                                                </td>
                                                <td class="d-flex align-items-center">
                                                    <button class="btn btn-sm btn-outline-info mr-2 px-1 py-1"
                                                        onclick="viewRadiologyResult({{ json_encode($radiologyResult) }})">
                                                        <i class="tio tio-visible"></i>
                                                    </button>

                                                    @if ($radiologyResult->verify_status == 'approved' && auth('admin')->user()->can('radiology_result.pdf'))
                                                        <a class="btn btn-sm btn-outline-primary mr-2 px-1 py-1"
                                                            href="{{ route('admin.radiology_result.pdf', [$radiologyResult->id]) }}"
                                                            target="_blank">
                                                            <i class="tio tio-receipt"></i>
                                                        </a>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-primary mr-2 px-1 py-1"
                                                            onclick="editRadiologyResult('{{ $radiologyResult->id }}')">
                                                            <i class="tio tio-edit"></i>
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-sm btn-outline-danger px-1 py-1"
                                                        onclick="deleteRadiologyResult('{{ $radiologyResult->id }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-danger">No Result found for this Radiology.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </fieldset>

    <!-- Modal for displaying PDF -->
    <div class="modal fade" id="radpdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">
                        Radiology Request PDF
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Empty iframe that will load the PDF when the modal opens -->
                    <iframe id="RadpdfIframe" width="100%" height="500px"></iframe>
                </div>
                <div class="modal-footer">
                    <!-- Button to download PDF -->
                    <a href="{{ route('admin.radiology_request.download', $visit->id) }}"
                        class="btn btn-success">Download PDF</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif

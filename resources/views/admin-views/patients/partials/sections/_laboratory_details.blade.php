@if (
    (auth('admin')->user()->can('laboratory_result.add-new') ||
        auth('admin')->user()->can('laboratory_request.add-new') ||
        auth('admin')->user()->can('medical_record.add-new') ||
        auth('admin')->user()->can('specimen.add-new') ||
        auth('admin')->user()->can('specimen.list') ||
        auth('admin')->user()->can('laboratory_result.list')) &&
        $visit->laboratoryRequest)

    <fieldset class="border border-primary mt-3 p-3 rounded">
        <legend class="float-none w-auto px-3 py-1 bg-light border border-primary rounded-sm"
            style="font-weight: bold; font-size: 18px; color:white; background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%)">
            <div class="pr-1">Laboratory Details</div>
        </legend>
        <!-- Laboratory Details Tabs -->
        <div class="">
            <ul class="nav nav-tabs" id="laboratoryTabs" role="tablist">
                @if (auth('admin')->user()->can('laboratory_result.add-new') ||
                        auth('admin')->user()->can('laboratory_request.add-new') ||
                        auth('admin')->user()->can('medical_record.add-new'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="request-tab" data-toggle="tab" href="#request" role="tab"
                            aria-controls="request" aria-selected="true">
                            <i class="tio-document-text mr-1"></i>Request Details
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('specimen.add-new') || auth('admin')->user()->can('specimen.list'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('medical_record.add-new')) ? 'active' : '' }}"
                            id="specimen-tab" data-toggle="tab" href="#specimen" role="tab" aria-controls="specimen"
                            aria-selected="{{ !(auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('medical_record.add-new')) ? 'true' : 'false' }}">
                            <i class="tio-test-tube mr-1"></i>Specimens
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_result.list'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('medical_record.add-new') || (auth('admin')->user()->can('specimen.add-new') || auth('admin')->user()->can('specimen.list'))) ? 'active' : '' }}"
                            id="results-tab" data-toggle="tab" href="#results" role="tab" aria-controls="results"
                            aria-selected="{{ !(auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('medical_record.add-new') || (auth('admin')->user()->can('specimen.add-new') || auth('admin')->user()->can('specimen.list'))) ? 'true' : 'false' }}">
                            <i class="tio-checkmark-circle mr-1"></i>Test Results
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('medical_record.add-new') || (auth('admin')->user()->can('specimen.add-new') || auth('admin')->user()->can('specimen.list'))) ? 'active' : '' }}"
                            id="categorized-tab" data-toggle="tab" href="#categorized" role="tab"
                            aria-controls="categorized"
                            aria-selected="{{ !(auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('medical_record.add-new') || (auth('admin')->user()->can('specimen.add-new') || auth('admin')->user()->can('specimen.list'))) ? 'true' : 'false' }}">
                            <i class="tio-category mr-1"></i>Categorized Result
                        </a>
                    </li>
                @endif
            </ul>

            <div class="tab-content border border-primary rounded-bottom" id="laboratoryTabsContent">
                <!-- Request Details Tab -->
                @if (auth('admin')->user()->can('laboratory_result.add-new') ||
                        auth('admin')->user()->can('laboratory_request.add-new') ||
                        auth('admin')->user()->can('medical_record.add-new'))
                    <div class="tab-pane fade show active p-3" id="request" role="tabpanel"
                        aria-labelledby="request-tab">
                        <div class="row">
                            @if ($visit->laboratoryRequest->referring_dr)
                                <div class="col-md-6 mb-3">
                                    <strong>Referring Doctor:</strong>
                                    <p class="mb-0 text-muted">
                                        {{ $visit->laboratoryRequest->referring_dr ?? 'Not Specified' }}
                                    </p>
                                </div>
                            @endif
                            @if ($visit->laboratoryRequest->referring_institution)
                                <div class="col-md-6 mb-3">
                                    <strong>Referring Institution:</strong>
                                    <p class="mb-0 text-muted">
                                        {{ $visit->laboratoryRequest->referring_institution ?? 'Not Specified' }}
                                    </p>
                                </div>
                            @endif
                            @if ($visit->laboratoryRequest->card_no)
                                <div class="col-md-6 mb-3">
                                    <strong>Card Number:</strong>
                                    <p class="mb-0 text-muted">
                                        {{ $visit->laboratoryRequest->card_no ?? 'Not Specified' }}
                                    </p>
                                </div>
                            @endif
                            @if ($visit->laboratoryRequest->hospital_ward)
                                <div class="col-md-6 mb-3">
                                    <strong>Hospital Ward:</strong>
                                    <p class="mb-0 text-muted">
                                        {{ $visit->laboratoryRequest->hospital_ward ?? 'Not Specified' }}
                                    </p>
                                </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <strong>Requested By:</strong>
                                <p class="mb-0 text-muted">
                                    {{ ucfirst($visit->laboratoryRequest->requested_by) }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Order Status:</strong>
                                <p class="mb-0 text-muted">
                                    {{ ucfirst($visit->laboratoryRequest->order_status) }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Fasting Required:</strong>
                                <p class="mb-0 text-muted">
                                    {{ ucfirst($visit->laboratoryRequest->fasting) }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Relevant Clinical Data:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->laboratoryRequest->relevant_clinical_data ?? 'No Details Provided' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Current Medication:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->laboratoryRequest->current_medication ?? 'No Details Provided' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Additional Note:</strong>
                                <p class="mb-0 text-muted">
                                    {{ $visit->laboratoryRequest->additional_note ?? 'No Additional Notes' }}
                                </p>
                            </div>

                            <div class="col-md-12 mb-3">
                                <strong>Requested Tests:</strong>
                                @if ($visit->laboratoryRequest && $visit->laboratoryRequest->tests->count())
                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                        @foreach ($visit->laboratoryRequest->tests as $item)
                                            <span class="badge badge-soft-info py-1 px-1" style="font-size: 1rem;">
                                                <i class="tio-laboratory"></i> {{ $item->test->test_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mb-0 text-muted">No tests requested.</p>
                                @endif
                            </div>

                            @php
                                $hasInactiveTest = $visit->laboratoryRequest->tests->contains(function ($test) {
                                    return !$test->test->is_active; // Look for at least one inactive test
                                });
                            @endphp

                            <div class="d-flex col-12 justify-content-end gap-3">
                                @if (auth('admin')->user()->can('laboratory_request.pdf') && $hasInactiveTest)
                                    <div class="text-end pl-2">
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#LabpdfModal"
                                            onclick="loadLabPdf('{{ route('admin.laboratory_request.pdf', $visit->id) }}')">
                                            <i class="tio-upload"></i>
                                            Out Request PDF
                                        </a>
                                    </div>
                                @endif
                                @if (auth('admin')->user()->can('laboratory_request.edit') &&
                                        !optional($visit->laboratoryRequest)->testResults2->isNotEmpty() &&
                                        !optional($visit->laboratoryRequest)->specimens->isNotEmpty() &&
                                        (!$visit->laboratoryRequest->billingMatchingThisRequest() ||
                                            optional(optional($visit->laboratoryRequest)->billingMatchingThisRequest())->amount_paid == 0))
                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="editLaboratoryRequest({{ $visit->laboratoryRequest->id }})">
                                            <i class="tio-edit"></i>
                                            {{ translate('Edit') }}
                                        </button>
                                    </div>
                                @endif
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="addTestes({{ $visit->laboratoryRequest->id }})">
                                        <i class="tio-add"></i>
                                        {{ translate('Add Tests') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Specimen Details Tab -->
                @if (auth('admin')->user()->can('specimen.add-new') || auth('admin')->user()->can('specimen.list'))
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('medical_record.add-new')) ? 'show active' : '' }} p-3"
                        id="specimen" role="tabpanel" aria-labelledby="specimen-tab">
                        @php
                            $specimens = optional($visit->laboratoryRequest)->specimens;
                        @endphp
                        @if ($specimens && $specimens->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Machine Code</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($specimens as $specimen)
                                            <tr>
                                                <td>
                                                    @if ($specimen->laboratoryRequestTests->isNotEmpty())
                                                        @foreach ($specimen->laboratoryRequestTests as $lrt)
                                                            @php
                                                                $test = $lrt->test;
                                                            @endphp
                                                            {{ $test->test_name }}
                                                            ({{ $test->specimenType->name ?? 'Not Specified' }})
                                                            <br>
                                                        @endforeach
                                                    @else
                                                        No tests assigned
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $codeStr = (string) $specimen->specimen_code;

                                                        // Extract parts from full specimen code
                                                        $day = (int) substr($codeStr, 4, 2); // Remove leading 0 if any
                                                        $serial = substr($codeStr, 6, 4); // Last 4 digits

                                                        $machineCode = $day . $serial; // Concatenate as string
                                                    @endphp
                                                    {{ $machineCode }}
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        class="badge badge-{{ $specimen->status == 'accepted' ? 'success' : ($specimen->status == 'in process' ? 'warning' : ($specimen->status == 'rejected' ? 'danger' : 'info')) }}"
                                                        onclick="handleStatusClick('{{ $specimen->id }}', '{{ $specimen->status }}')">
                                                        {{ ucwords($specimen->status) }}
                                                    </a>
                                                </td>
                                                <td class="d-flex align-items-center">
                                                    <button class="btn btn-sm btn-outline-info mr-2 px-1 py-1"
                                                        onclick="viewSpecimen(@js($specimen))">
                                                        <i class="tio tio-visible"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary mr-2 px-1 py-1"
                                                        onclick='editSpecimen(@json($specimen))'>
                                                        <i class="tio tio-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger px-1 py-1"
                                                        onclick="deleteSpecimen('{{ $specimen->id }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-danger">No specimens found for this request.</p>
                        @endif
                    </div>
                @endif

                <!-- Test Results Tab -->
                @if (auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_result.list'))
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('medical_record.add-new') || (auth('admin')->user()->can('specimen.add-new') || auth('admin')->user()->can('specimen.list'))) ? 'show active' : '' }} p-3"
                        id="results" role="tabpanel" aria-labelledby="results-tab">
                        @php
                            $testResults = optional($visit->laboratoryRequest)->testResults2;
                        @endphp
                        @if ($testResults && $testResults->isNotEmpty())
                            @php
                                $groupedTestResults = $testResults->filter(function ($result) {
                                    return optional($result->laboratoryRequestTest->test)->page_display === 'group' &&
                                        $result->verify_status === 'approved';
                                });
                            @endphp
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Test</th>
                                            <th>Process Status</th>
                                            <th>Verify Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($testResults as $testResult)
                                            <tr>
                                                <td>{{ $testResult->laboratoryRequestTest->test->test_name ?? 'Not Specified' }}
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        class="badge badge-{{ $testResult->process_status == 'completed'
                                                            ? 'success'
                                                            : ($testResult->process_status == 'in process'
                                                                ? 'warning'
                                                                : ($testResult->process_status == 'rejected'
                                                                    ? 'danger'
                                                                    : 'info')) }}"
                                                        onclick="handleResultStatusClick('{{ $testResult->id }}', '{{ $testResult->process_status }}')"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="{{ $testResult->process_status === 'completed' ? 'Status completed – cannot be changed' : '' }}">
                                                        {{ ucwords($testResult->process_status) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @php
                                                        $verifyStatus = $testResult->verify_status;
                                                        $badgeClass =
                                                            $verifyStatus === 'approved'
                                                                ? 'success'
                                                                : ($verifyStatus === 'checking'
                                                                    ? 'warning'
                                                                    : ($verifyStatus === 'rejected'
                                                                        ? 'danger'
                                                                        : 'info'));

                                                        $tooltipText =
                                                            $verifyStatus === 'approved'
                                                                ? 'This result is already approved and cannot be changed'
                                                                : '';
                                                    @endphp

                                                    <a href="javascript:void(0)"
                                                        class="badge badge-{{ $badgeClass }}" data-toggle="tooltip"
                                                        title="{{ $tooltipText }}"
                                                        onclick="handleResultApprovalStatusClick('{{ $testResult->id }}', '{{ $verifyStatus }}')">
                                                        {{ ucwords($verifyStatus) }}
                                                    </a>
                                                </td>
                                                <td class="d-flex align-items-center">
                                                    <button class="btn btn-sm btn-outline-info mr-2 px-1 py-1"
                                                        onclick='viewTestResult(@json($testResult))'>
                                                        <i class="tio tio-visible"></i>
                                                    </button>

                                                    @if ($testResult->verify_status == 'approved' && auth('admin')->user()->can('laboratory_result.pdf'))
                                                        <a class="btn btn-sm btn-outline-primary mr-2 px-1 py-1"
                                                            href="{{ route('admin.laboratory_result.pdf', [$testResult->id]) }}"
                                                            target="_blank">
                                                            <i class="tio tio-receipt"></i>
                                                        </a>
                                                    @else
                                                        @php
                                                            $isMachineResult =
                                                                $testResult->laboratoryRequestTest->test
                                                                    ->result_source === 'machine';
                                                        @endphp

                                                        <button class="btn btn-sm btn-outline-primary mr-2 px-1 py-1"
                                                            onclick="{{ $isMachineResult
                                                                ? "toastr.warning('This result is from a machine and cannot be edited manually.')"
                                                                : "editTestResult('{$testResult->id}')" }}">
                                                            <i class="tio tio-edit"></i>
                                                        </button>
                                                    @endif
                                                    @if ($testResult->verify_status != 'approved' && auth('admin')->user()->can('laboratory_result.delete'))
                                                        <button class="btn btn-sm btn-outline-danger px-1 py-1"
                                                            onclick="deleteTestResult('{{ $testResult->id }}')">
                                                            <i class="tio tio-delete"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($groupedTestResults->count() > 1)
                                <div class="mb-2 text-right">
                                    <a href="{{ route('admin.laboratory_result.grouped_pdf', [$visit->laboratoryRequest->id]) }}"
                                        class="btn btn-sm btn-success" target="_blank">
                                        <i class="tio tio-download"></i> Download Grouped PDF
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-danger">No Result found for this request.</p>
                        @endif
                    </div>

                    <!-- Categorized View Tab -->
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('laboratory_result.add-new') || auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('medical_record.add-new') || (auth('admin')->user()->can('specimen.add-new') || auth('admin')->user()->can('specimen.list'))) ? 'show active' : '' }} p-3"
                        id="categorized" role="tabpanel" aria-labelledby="categorized-tab">
                        @php
                            $testResults = optional($visit->laboratoryRequest)->testResults2;
                        @endphp
                        @if ($testResults && $testResults->isNotEmpty())
                            @php
                                $groupedResults = $testResults->groupBy(function ($result) {
                                    return optional($result->laboratoryRequestTest->test->testCategory)->id ??
                                        'uncategorized';
                                });
                            @endphp
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Completion Status</th>
                                            <th>Verification Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groupedResults as $categoryId => $categoryResults)
                                            @php
                                                $category =
                                                    $categoryResults->first()->laboratoryRequestTest->test
                                                        ->testCategory ?? null;
                                                $idsJson = $categoryResults->pluck('id')->toJson();

                                                $processStatuses = $categoryResults->pluck('process_status')->unique();
                                                $verifyStatuses = $categoryResults->pluck('verify_status')->unique();

                                                // Completion Status
                                                if (
                                                    $processStatuses->count() === 1 &&
                                                    $processStatuses->contains('completed')
                                                ) {
                                                    $completionStatus = 'Completed';
                                                    $completionClass = 'success';
                                                } elseif ($processStatuses->contains('rejected')) {
                                                    $completionStatus = 'Rejected';
                                                    $completionClass = 'danger';
                                                } elseif (
                                                    $processStatuses->contains('completed') &&
                                                    $processStatuses->contains('in process')
                                                ) {
                                                    $completionStatus = 'Partially Completed';
                                                    $completionClass = 'warning';
                                                } elseif ($processStatuses->contains('in process')) {
                                                    $completionStatus = 'In Process';
                                                    $completionClass = 'info';
                                                } else {
                                                    $completionStatus = 'Pending';
                                                    $completionClass = 'secondary';
                                                }

                                                // Verification Status
                                                if (
                                                    $verifyStatuses->count() === 1 &&
                                                    $verifyStatuses->contains('approved')
                                                ) {
                                                    $verificationStatus = 'approved';
                                                    $verificationClass = 'success';
                                                } elseif ($verifyStatuses->contains('rejected')) {
                                                    $verificationStatus = 'Rejected';
                                                    $verificationClass = 'danger';
                                                } elseif (
                                                    $verifyStatuses->contains('approved') &&
                                                    $verifyStatuses->contains('checking')
                                                ) {
                                                    $verificationStatus = 'Partially Verified';
                                                    $verificationClass = 'warning';
                                                } elseif ($verifyStatuses->contains('checking')) {
                                                    $verificationStatus = 'Checking';
                                                    $verificationClass = 'info';
                                                } else {
                                                    $verificationStatus = 'Pending';
                                                    $verificationClass = 'secondary';
                                                }
                                            @endphp

                                            <tr>
                                                <td>{{ $category->name ?? 'Uncategorized' }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $completionClass }}">
                                                        {{ $completionStatus }}
                                                    </span>
                                                </td>
                                                <td>

                                                    <a href="javascript:void(0)"
                                                        class="badge badge-{{ $verificationClass }}"
                                                        data-toggle="tooltip"
                                                        onclick="handleBulkResultApprovalStatusClick({{ $idsJson }}, '{{ $verificationStatus }}')">
                                                        {{ $verificationStatus }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-info"
                                                        onclick='viewTestCategory(@json($categoryResults), "{{ $category->name ?? 'Uncategorized' }}")'>
                                                        <i class="tio tio-visible"></i> View
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-danger">No Result found for this request.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </fieldset>

    <!-- Modal for displaying PDF -->
    <div class="modal fade" id="LabpdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">
                        Laboratory Request PDF
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Empty iframe that will load the PDF when the modal opens -->
                    <iframe id="LabpdfIframe" width="100%" height="500px"></iframe>
                </div>
                <div class="modal-footer">
                    <!-- Button to download PDF -->
                    <a href="{{ route('admin.laboratory_request.download', $visit->id) }}"
                        class="btn btn-success">Download PDF</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Modal for Viewing Test Result -->
<div class="modal fade" id="viewTestResultModal" tabindex="-1" role="dialog"
    aria-labelledby="viewTestResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTestResultModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Overall Test Result</h5>
                <div id="testResultStatus"></div>
                <div id="testResultComments"></div>
                <div id="testResultAdditionalNote"></div>
                <div id="testResultProcessedBy"></div>
                <div id="testResultVerifiedBy"></div>
                <h5 class="mt-4">Images</h5>
                <div id="testResultImage"></div>

                <h5 class="mt-4">Attributes</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Attribute</th>
                            <th>Result</th>
                            <th>ABN</th>
                            <th>Reference Range</th>
                        </tr>
                    </thead>
                    <tbody id="testResultAttributes">
                        <!-- Dynamically filled with JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Viewing Test Result -->
<div class="modal fade" id="viewCategorizedTestResultModal" tabindex="-1" role="dialog"
    aria-labelledby="viewCategorizedTestResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCategorizedTestResultModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Overall Test Result</h5>
                <div id="categorizedResults"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('script_2')
    <script>
        function handleResultApprovalStatusClick(resultId, currentApprovalStatus) {
            if (currentApprovalStatus === 'approved') {
                // Prevent changes if already approved
                toastr.info('This result has already been approved and cannot be changed.', 'Action Restricted', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
                return;
            }

            @if (auth('admin')->user()->can('laboratory_result.verify-status.update'))
                showUpdateResultApprovalStatusModal(resultId, currentApprovalStatus);
            @else
                toastr.warning('You do not have permission to update result approval status.', 'Access Denied', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
            @endif
        }


        function handleBulkResultApprovalStatusClick(resultIds, currentApprovalStatus) {
            if (currentApprovalStatus === 'approved') {
                // Prevent changes if already approved
                toastr.info('This result has already been approved and cannot be changed.', 'Action Restricted', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
                return;
            }

            @if (auth('admin')->user()->can('laboratory_result.verify-status.update'))
                $('#result_ids_container').empty(); // clear old values
                resultIds.forEach(id => {
                    $('#result_ids_container').append(
                        `<input type="hidden" name="result_ids[]" value="${id}">`
                    );
                });

                $('#bulkUpdateResultApprovalStatusModal').modal('show');
            @else
                toastr.warning('You do not have permission to update result approval status.', 'Access Denied', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
            @endif
        }


        function showUpdateResultApprovalStatusModal(resultId, currentApprovalStatus) {
            // Set up modal content dynamically
            document.getElementById('approvalResultIdInput').value = resultId;

            // Show the modal
            $('#updateResultApprovalStatusModal').modal('show');
        }

        function handleStatusClick(specimenId, currentStatus) {
            @if (auth('admin')->user()->can('specimen.status.update'))
                // If the user has permission, show the update modal
                showUpdateStatusModal(specimenId, currentStatus);
            @else
                // If the user does not have permission, show an interactive popup
                toastr.warning('You do not have permission to update this status.', 'Access Denied', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
            @endif
        }

        function handleResultStatusClick(resultId, currentStatus) {
            @if (auth('admin')->user()->can('laboratory_result.process-status.update'))
                if (currentStatus === 'completed') {
                    toastr.info('This result is already completed and cannot be changed.', 'Not Allowed', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3000,
                    });
                    return;
                }

                // Show the update modal
                showUpdateResultStatusModal(resultId, currentStatus);
            @else
                // No permission
                toastr.warning('You do not have permission to update this result process status.', 'Access Denied', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
            @endif
        }
    </script>
    <script>
        function addTestes(id) {
            const button = $(event.target);
            const originalText = disableButton(button);

            $.ajax({
                url: "{{ route('admin.laboratory_request.edit', '') }}/" + id,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        const request = response.data.laboratoryRequest;
                        const tests = response.data.tests;

                        $('#add_tests_to_request_form input[name="id"]').val(request.id);

                        // const testIds = request.tests.map(test => test.test_id);

                        // const selectTests = $('#add_tests_to_request_form select[name="test_ids[]"]');

                        // selectTests.find('option').filter(function() {
                        //     return testIds.includes(Number(this.value));
                        // }).remove();

                        // selectTests.trigger('change');

                        $('#addTestsModal').modal('show');
                    } else {
                        toastr.error(response.message || 'Error retrieving laboratory request');
                    }
                    enableButton(button, originalText);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error retrieving laboratory request');
                    enableButton(button, originalText);
                }
            });
        }

        function editLaboratoryRequest(id) {
            const button = $(event.target);
            const originalText = disableButton(button);

            $.ajax({
                url: "{{ route('admin.laboratory_request.edit', '') }}/" + id,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        const request = response.data.laboratoryRequest;
                        const tests = response.data.tests;

                        // Set form values
                        $('#edit_laboratory_request_form input[name="id"]').val(request.id);
                        $('#edit_laboratory_request_form input[name="visit_id"]').val(request.visit_id);
                        $('#edit_laboratory_request_form select[name="requested_by"]').val(request.requested_by)
                            .trigger('change');
                        $('#edit_laboratory_request_form select[name="order_status"]').val(request.order_status)
                            .trigger('change');
                        $('#edit_laboratory_request_form select[name="fasting"]').val(request.fasting).trigger(
                            'change');
                        $('#edit_laboratory_request_form input[name="referring_dr"]').val(request.referring_dr);
                        $('#edit_laboratory_request_form input[name="referring_institution"]').val(request
                            .referring_institution);
                        $('#edit_laboratory_request_form input[name="card_no"]').val(request.card_no);
                        $('#edit_laboratory_request_form input[name="hospital_ward"]').val(request
                            .hospital_ward);
                        $('#edit_laboratory_request_form textarea[name="relevant_clinical_data"]').val(request
                            .relevant_clinical_data);
                        $('#edit_laboratory_request_form textarea[name="current_medication"]').val(request
                            .current_medication);
                        $('#edit_laboratory_request_form textarea[name="additional_note"]').val(request
                            .additional_note);

                        // Get test IDs from the tests array
                        const testIds = request.tests.map(test => test.test_id);
                        $('#edit_laboratory_request_form select[name="test_ids[]"]').val(testIds).trigger(
                            'change');

                        $('#editLaboratoryRequestModal').modal('show');
                    } else {
                        toastr.error(response.message || 'Error retrieving laboratory request');
                    }
                    enableButton(button, originalText);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error retrieving laboratory request');
                    enableButton(button, originalText);
                }
            });
        }
    </script>
    <script>
        // Current patient context (from server)
        const patientGender = ((@json(optional($visit->patient)->gender)) || '').toLowerCase();
        const patientAge = Number(@json(optional($visit->patient)->age)); // falls back to NaN if not numeric

        function viewTestResult(testResult) {
            // const testResult = JSON.parse(testResults);
            // Header Information
            $('#viewTestResultModalLabel').html(`${testResult.laboratory_request_test.test.test_name} Test Result`);
            $('#testResultStatus').html(`
                    <strong>Status:</strong> ${ucwords(testResult.process_status || '')}<br>
                    <strong>Verification Status:</strong> ${ucwords(testResult.verify_status || '')}<br>
                    <strong>Comments:</strong> ${testResult.comments || 'No comments available.'}
                `);
            $('#testResultAdditionalNote').html(
                `<strong>Additional Note:</strong> ${testResult.additional_note || 'No additional notes.'}`);
            $('#testResultProcessedBy').html(
                `<strong>Processed By:</strong> ${testResult.processed_by ? testResult.processed_by.f_name + ' ' + testResult.processed_by.l_name : 'Not Processed Yet'}`
            );
            $('#testResultVerifiedBy').html(
                `<strong>Verified By:</strong> ${testResult.verified_by ? testResult.verified_by.f_name + ' ' + testResult.verified_by.l_name : 'Not Verified Yet'}`
            );

            // Image display
            let images = testResult.image || [];
            if (images.length > 0) {
                let imageHtml = '';
                images.forEach((image) => {
                    imageHtml += `<a href="/storage/app/public/assets/${image}" target="_blank">
                    <img src="/storage/app/public/assets/${image}" alt="Test Image" class="img-fluid" style="max-width: 50px; margin-top: 10px; cursor: pointer;">
                </a>`;
                });
                $('#testResultImage').html(imageHtml);
            } else {
                $('#testResultImage').html('<strong>No images available.</strong>');
            }

            // Display attributes
            if (testResult.attributes && Array.isArray(testResult.attributes)) {
                let attributesHtml = '';

                // ✅ Sort by index if available
                testResult.attributes.sort((a, b) => {
                    const indexA = a.attribute?.index;
                    const indexB = b.attribute?.index;

                    if (indexA != null && indexB != null) {
                        return indexA - indexB;
                    }

                    if (indexA != null) return -1;
                    if (indexB != null) return 1;

                    return 0;
                });

                testResult.attributes.forEach((attribute) => {
                    let unit = attribute.attribute?.unit ? attribute.attribute?.unit.code : '';
                    let references = attribute.attribute?.attribute_references || [];
                    let referenceText = references.length ? buildReferenceDisplay(references) :
                        'No reference range';

                    attributesHtml += `
                        <tr>
                            <td>${attribute.attribute?.attribute_name || ''}</td>
                            <td>${attribute.result_value || ''}${unit ? `(${unit})` : ''}</td>
                            <td class="${getCommentClass(attribute.comments)}">
                                ${attribute.comments || ''}
                            </td>
                            <td>${referenceText}${unit ? `(${unit})` : ''}</td>
                        </tr>
                    `;
                });

                $('#testResultAttributes').html(attributesHtml);
            } else {
                $('#testResultAttributes').html(
                    '<tr><td colspan="4" class="text-center">No attributes available.</td></tr>'
                );
            }

            $('#viewTestResultModal').modal('show');

        }

        function viewTestCategory(testResults, categoryName) {
            $('#viewCategorizedTestResultModalLabel').html(`${categoryName} Test Results`);

            let html = '';

            testResults.forEach(testResult => {
                let testName = testResult.laboratory_request_test?.test?.test_name || 'Unnamed Test';

                // Sub-header for each test
                html += `<h5 class="mt-3 bg-primary text-white rounded text-center">${testName}</h5>`;
                // html += `
            //     <p><strong>Status:</strong> ${ucwords(testResult.process_status || '')}
            //     | <strong>Verification:</strong> ${ucwords(testResult.verify_status || '')}</p>
            // `;

                // Attributes
                if (testResult.attributes && Array.isArray(testResult.attributes) && testResult.attributes.length >
                    0) {
                    html += `
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Attribute</th>
                                    <th>Result</th>
                                    <th>ABN</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    // sort attributes
                    testResult.attributes.sort((a, b) => {
                        let indexA = a.attribute?.index ?? 9999;
                        let indexB = b.attribute?.index ?? 9999;
                        return indexA - indexB;
                    });

                    testResult.attributes.forEach(attribute => {
                        let unit = attribute.attribute?.unit?.code || '';
                        let references = attribute.attribute?.attribute_references || [];
                        let referenceText = references.length ? buildReferenceDisplay(references) :
                            'No reference range';

                        html += `
                    <tr>
                        <td>${attribute.attribute?.attribute_name || ''}</td>
                        <td>${attribute.result_value || ''}${unit ? ` (${unit})` : ''}</td>
                        <td class="${getCommentClass(attribute.comments)}">${attribute.comments || ''}</td>
                        <td>${referenceText}${unit ? ` (${unit})` : ''}</td>
                    </tr>
                `;
                    });

                    html += `</tbody></table>`;
                } else {
                    html += `<p class="text-muted">No attributes available.</p>`;
                }
                html += `<hr style="border-primary-top: 2px dashed #0a58ca; margin: 0 0 1rem 0;">`;
            });

            $('#categorizedResults').html(html);

            $('#viewCategorizedTestResultModal').modal('show');
        }


        // Helper functions
        function convertOperator(op) {
            switch (op) {
                case '>=':
                    return '≥ ';
                case '<=':
                    return '≤ ';
                case '>':
                    return '>';
                case '<':
                    return '<';
                case '=':
                    return '=';
                default:
                    return '';
            }
        }

        function formatRange(lower, upper, lowerOp, upperOp) {
            if (lower !== null && upper !== null) {
                return `${lower} – ${upper}`;
            } else if (lower !== null) {
                return `${convertOperator(lowerOp)}${lower}`;
            } else if (upper !== null) {
                return `${convertOperator(upperOp)}${upper}`;
            } else {
                return '';
            }
        }

        function formatReferenceText(ref) {
            if (ref.reference_text) {
                return ref.reference_text;
            }

            let parts = [];

            if (ref.min_age !== null && ref.max_age !== null) {
                parts.push(`Age ${ref.min_age} – ${ref.max_age}`);
            }

            const range = formatRange(ref.lower_limit, ref.upper_limit, ref.lower_operator, ref.upper_operator);
            if (range) parts.push(range);

            return range;
        }


        function buildReferenceDisplay(references) {
            // Filter by patient gender and age when available
            const byGender = (ref) => {
                if (!ref.gender) return true; // applies to all genders
                return patientGender && ref.gender.toLowerCase() === patientGender;
            };

            const byAge = (ref) => {
                if (Number.isNaN(patientAge)) return true; // cannot filter without age
                const minOk = (ref.min_age == null) || (patientAge >= Number(ref.min_age));
                const maxOk = (ref.max_age == null) || (patientAge <= Number(ref.max_age));
                return minOk && maxOk;
            };

            const filtered = references.filter(r => byGender(r) && byAge(r));

            const list = filtered.length ? filtered : references; // fallback to all if none matched

            const isReferenceText = list.some(r => r.reference_text);

            return list.map(ref => {
                let gender = ref.gender ? ref.gender.charAt(0).toUpperCase() + ref.gender.slice(1) + ' ' : '';
                return `${formatReferenceText(ref)}`;
            }).join(isReferenceText ? ', ' : '<br>');
        }

        function getCommentClass(comment) {
            if (!comment) return '';
            const normalized = comment.toLowerCase();

            switch (normalized) {
                case 'normal':
                    return 'text-success'; // Green text
                case 'high':
                    return 'text-danger'; // Red text
                case 'low':
                    return 'text-warning'; // Yellow text
                case 'abnormal':
                    return 'text-danger'; // Purple text (you can use a custom class for purple)
                default:
                    return '';
            }
        }
    </script>
@endpush

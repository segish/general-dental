<div class="modal fade" id="add-laboratory_request" tabindex="-1">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add New Laboratory Request') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="laboratory_request_form" enctype="multipart/form-data">
                    @csrf
                    <input type="text" hidden name="visit_id">
                    <input type="text" hidden name="collected_by" value="{{ auth('admin')->user()->id }}">
                    <input type="text" hidden name="status" value="pending">

                    <div class="row pl-2">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="order_status">{{ \App\CentralLogics\translate('Order Status') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="order_status" class="form-control js-select2-custom" required>
                                    <option value="" disabled>
                                        {{ \App\CentralLogics\translate('Select Order Status') }}</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="routine" selected>routine</option>
                                </select>
                            </div>
                        </div>
                        @if (!auth('admin')->user()->can('medical_record.add-new') || !auth('admin')->user()->can('pregnancy.add-new'))
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label" for="requested_by">
                                        {{ \App\CentralLogics\translate('Requested By') }}
                                        <span class="input-label-secondary text-danger">*</span>
                                    </label>


                                    {{-- Show the select dropdown if user has permission --}}
                                    <select name="requested_by" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select Requested By') }}
                                        </option>
                                        <option value="physician">Physician (In-Clinic)</option>
                                        <option value="self">Self</option>
                                        <option value="other healthcare">Other Healthcare Provider</option>
                                    </select>
                                    {{-- Pass default value as hidden input if user does not have permission --}}

                                </div>
                            </div>
                        @else
                            <input type="hidden" name="requested_by" value="physician">
                        @endif


                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="fasting">{{ \App\CentralLogics\translate('Fasting Status') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="fasting" class="form-control js-select2-custom" required>
                                    <option value="" disabled>
                                        {{ \App\CentralLogics\translate('Select Fasting Status') }}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no" selected>No</option>
                                </select>
                            </div>
                        </div>

                        @if (!auth('admin')->user()->can('medical_record.add-new'))
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label" for="referring_dr">
                                        {{ \App\CentralLogics\translate('Referring Doctor') }}
                                    </label>
                                    {{-- Pass the admin's name as a hidden input --}}

                                    {{-- Show the input field for other users --}}
                                    <input type="text" name="referring_dr" class="form-control"
                                        placeholder="{{ translate('Enter Referring Doctor') }}">
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="referring_dr" value="{{ auth('admin')->user()->name }}">
                        @endif

                        @if (!auth('admin')->user()->can('medical_record.add-new'))
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="referring_institution">{{ \App\CentralLogics\translate('Referring Institution') }}</label>
                                    <input type="text" name="referring_institution" class="form-control"
                                        placeholder="{{ translate('enter referring institution') }}">
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="referring_institution">
                        @endif


                        @if (!auth('admin')->user()->can('medical_record.add-new'))
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="card_no">{{ \App\CentralLogics\translate('Card Number') }}</label>
                                    <input type="text" name="card_no" class="form-control"
                                        placeholder="{{ translate('enter card number') }}">
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="referring_institution">
                        @endif

                        <div class="col-6">
                            <div class="form-group form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="is_outside_clinic">
                                <label class="form-check-label" for="is_outside_clinic">
                                    {{ translate('Refer to Other Clinics') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Test Type') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="test_ids[]" id="test_ids" class="form-control js-select2-custom"
                                    multiple required>
                                    @foreach ($tests as $test)
                                        <option value="{{ $test->id }}">{{ $test->test_name }} -
                                            ({{ $test->testCategory->name }})
                                        </option>
                                    @endforeach
                                </select>
                                <template id="testsOptionsTemplate">
                                    @foreach ($tests as $test)
                                        <option value="{{ $test->id }}">{{ $test->test_name }} -
                                            ({{ $test->testCategory->name }})
                                        </option>
                                    @endforeach
                                </template>
                                <template id="outTestsOptionsTemplate">
                                    @foreach ($outTests as $test)
                                        <option value="{{ $test->id }}">{{ $test->test_name }} -
                                            ({{ $test->testCategory->name }})
                                        </option>
                                    @endforeach
                                </template>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="relevant_clinical_data">{{ \App\CentralLogics\translate('Relevant Clinical Data') }}</label>
                                <div class="form-group">
                                    <textarea name="relevant_clinical_data" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="current_medication">{{ \App\CentralLogics\translate('Current Medication') }}</label>
                                <div class="form-group">
                                    <textarea name="current_medication" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Additional Note') }}</label>
                                <div class="form-group">
                                    <textarea name="additional_note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" id=""
                            class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Laboratory Request Modal -->
<div class="modal fade" id="editLaboratoryRequestModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Edit Laboratory Request') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="edit_laboratory_request_form"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="visit_id">
                    <input type="hidden" name="collected_by" value="{{ auth('admin')->user()->id }}">
                    <input type="hidden" name="status" value="pending">

                    <div class="row pl-2">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="order_status">{{ \App\CentralLogics\translate('Order Status') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="order_status" class="form-control js-select2-custom" required>
                                    <option value="" selected disabled>
                                        {{ \App\CentralLogics\translate('Select Order Status') }}</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="routine">Routine</option>
                                </select>
                            </div>
                        </div>
                        @if (!auth('admin')->user()->can('medical_record.add-new'))
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="requested_by">{{ \App\CentralLogics\translate('Requested By') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="requested_by" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select Requested By') }}</option>
                                        <option value="physician">Physician (In-Clinic)</option>
                                        <option value="self">Self</option>
                                        <option value="other healthcare">Other Healthcare Provider</option>
                                    </select>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="requested_by" value="physician">
                        @endif

                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                    for="fasting">{{ \App\CentralLogics\translate('Fasting Status') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="fasting" class="form-control js-select2-custom" required>
                                    <option value="" selected disabled>
                                        {{ \App\CentralLogics\translate('Select Fasting Status') }}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        @if (!auth('admin')->user()->can('medical_record.add-new'))
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="referring_dr">{{ \App\CentralLogics\translate('Referring Doctor') }}</label>
                                    <input type="text" name="referring_dr" class="form-control"
                                        placeholder="{{ translate('Enter Referring Doctor') }}">
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="referring_dr" value="{{ auth('admin')->user()->name }}">
                        @endif

                        @if (!auth('admin')->user()->can('medical_record.add-new'))
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="referring_institution">{{ \App\CentralLogics\translate('Referring Institution') }}</label>
                                    <input type="text" name="referring_institution" class="form-control"
                                        placeholder="{{ translate('enter referring institution') }}">
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="referring_institution">
                        @endif

                        @if (!auth('admin')->user()->can('medical_record.add-new'))
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="card_no">{{ \App\CentralLogics\translate('Card Number') }}</label>
                                    <input type="text" name="card_no" class="form-control"
                                        placeholder="{{ translate('enter card number') }}">
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="card_no">
                        @endif

                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Test Type') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="test_ids[]" class="form-control js-select2-custom" multiple required>
                                    @foreach ($tests as $test)
                                        <option value="{{ $test->id }}">{{ $test->test_name }} -
                                            ({{ $test->testCategory->name }})
                                        </option>
                                    @endforeach
                                    @foreach ($outTests as $test)
                                        <option value="{{ $test->id }}">{{ $test->test_name }} -
                                            ({{ $test->testCategory->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="relevant_clinical_data">{{ \App\CentralLogics\translate('Relevant Clinical Data') }}</label>
                                <div class="form-group">
                                    <textarea name="relevant_clinical_data" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="current_medication">{{ \App\CentralLogics\translate('Current Medication') }}</label>
                                <div class="form-group">
                                    <textarea name="current_medication" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Additional Note') }}</label>
                                <div class="form-group">
                                    <textarea name="additional_note" class="form-control"></textarea>
                                </div>
                            </div>
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
</div>

<!-- Add Tests Modal -->
<div class="modal fade" id="addTestsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Laboratory Tests To Existing Request') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="add_tests_to_request_form"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="input-label">{{ translate('Test Type') }}<span
                                    class="input-label-secondary text-danger">*</span></label>
                            <div class="d-flex align-items-center mb-2">
                                <div class="form-check mr-3">
                                    <input type="checkbox" class="form-check-input" id="addtests_is_outside_clinic">
                                    <label class="form-check-label"
                                        for="addtests_is_outside_clinic">{{ translate('Refer to Other Clinics') }}</label>
                                </div>
                            </div>
                            <select name="test_ids[]" id="addtests_test_ids" class="form-control js-select2-custom"
                                multiple required>
                                @foreach ($tests as $test)
                                    <option value="{{ $test->id }}">{{ $test->test_name }} -
                                        ({{ $test->testCategory->name }})
                                    </option>
                                @endforeach
                            </select>
                            <template id="addTestsOptionsTemplate">
                                @foreach ($tests as $test)
                                    <option value="{{ $test->id }}">{{ $test->test_name }} -
                                        ({{ $test->testCategory->name }})
                                    </option>
                                @endforeach
                            </template>
                            <template id="addOutTestsOptionsTemplate">
                                @foreach ($outTests as $test)
                                    <option value="{{ $test->id }}">{{ $test->test_name }} -
                                        ({{ $test->testCategory->name }})
                                    </option>
                                @endforeach
                            </template>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ translate('Add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="add-result_test" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Test Result') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="lab_test_form" enctype="multipart/form-data">
                    @csrf
                    <input type="text" hidden name="laboratory_request_test_id">
                    <input type="text" hidden name="processed_by" value="{{ auth('admin')->user()->id }}">
                    <div class="row pl-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="test_name">{{ \App\CentralLogics\translate('Select Test Type') }}</label>
                                <select name="test_type_id[]" id="test_name_result"
                                    class="form-control js-select2-custom" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="attributes_container"></div>

                    <div class="row pl-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Additional Note') }}</label>
                                <div class="form-group">
                                    <textarea name="additional_note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Comment') }}</label>
                                <div class="form-group">
                                    <textarea name="comments" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="specimenStatusInput">Result Status</label>
                        <select class="form-control" name="result_status">
                            <option value="Normal">Normal</option>
                            <option value="Abnormal">Abnormal</option>
                            <option value="Critical">Critical</option>
                            <option value="Pending">Pending</option>
                            <option value="Inconclusive">Inconclusive</option>
                            <option value="Positive">Positive</option>
                            <option value="Negative">Negative</option>
                            <option value="Reactive">Reactive</option>
                            <option value="Non-Reactive">Non-Reactive</option>
                            <option value="Indeterminate">Indeterminate</option>
                        </select>
                    </div>
                    <div class="">
                        <div class="mb-2">
                            <label class="text-capitalize">{{ \App\CentralLogics\translate('Attach Photos') }}</label>
                            <small class="text-danger"> * ( {{ \App\CentralLogics\translate('ratio') }} 1:1
                                )</small>
                        </div>
                        <div class="row" id="coba2"></div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" id=""
                            class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="add-result_test_custom" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Report Test Result') }}</h5>
            </div>

            <div class="modal-body bg-light">
                <form action="javascript:" method="post" id="lab_test_custom_form" enctype="multipart/form-data">
                    @csrf
                    <div id="attributes_container_custom"></div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" id="submit_custom_result" class="btn btn-primary">
                            {{ translate('Submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editTestResultModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_test_result_form" method="POST" action="javascript:">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Edit Test Result') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <div id="attributes_container_edit"></div>

                    <div class="form-group">
                        <label>{{ translate('Additional Note') }}</label>
                        <textarea name="additional_note" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Comments') }}</label>
                        <textarea name="comments" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Result Status') }}</label>
                        <select name="result_status" class="form-control js-select2-custom">
                            <option value="Normal">Normal</option>
                            <option value="Abnormal">Abnormal</option>
                            <option value="Critical">Critical</option>
                            <option value="Pending">Pending</option>
                            <option value="Inconclusive">Inconclusive</option>
                            <option value="Positive">Positive</option>
                            <option value="Negative">Negative</option>
                            <option value="Reactive">Reactive</option>
                            <option value="Non-Reactive">Non-Reactive</option>
                            <option value="Indeterminate">Indeterminate</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Images') }}</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="updateResultApprovalStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="updateResultApprovalStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateResultApprovalStatusModalLabel">Update Result Verify Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateResultApprovalStatusForm">
                    @csrf
                    <input type="hidden" id="approvalResultIdInput" name="result_id">
                    <div class="form-group">
                        <label>Processed By</label>
                        <select class="form-control" name="processed_by" id="processed_by">
                            <option value="" selected disabled>Select processed by</option>
                            @foreach ($labTechs as $labTech)
                                <option value="{{ $labTech->id }}"
                                    {{ auth('admin')->user()->id == $labTech->id ? 'selected' : '' }}>
                                    {{ $labTech->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="approvalStatusInput">Verify Status</label>
                        <select class="form-control" id="approvalStatusInput" name="verify_status">
                            <option value="approved">Approved</option>
                            <option value="checking">Checking</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Verify Status</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkUpdateResultApprovalStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="bulkUpdateResultApprovalStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUpdateResultApprovalStatusModalLabel">Update Result Verify Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="bulkUpdateResultApprovalStatusForm">
                    @csrf
                    <div id="result_ids_container"></div>
                    <div class="form-group">
                        <label>Processed By</label>
                        <select class="form-control" name="processed_by">
                            <option value="" selected disabled>Select processed by</option>
                            @foreach ($labTechs as $labTech)
                                <option value="{{ $labTech->id }}"
                                    {{ auth('admin')->user()->id == $labTech->id ? 'selected' : '' }}>
                                    {{ $labTech->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="approvalStatusInput">Verify Status</label>
                        <select class="form-control"name="verify_status">
                            <option value="approved">Approved</option>
                            <option value="checking">Checking</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Verify Status</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateResultStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="updateResultStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateResultStatusModalLabel">Update Result Process Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateResultStatusForm">
                    @csrf
                    <input type="hidden" id="resultIdInput" name="result_id">
                    <div class="form-group">
                        <label for="specimenStatusInput">Process Status</label>
                        <select class="form-control" id="resultStatusInput" name="process_status">
                            <option value="completed">Completed</option>
                            <option value="in process">In Process</option>
                            <option value="rejected">Rejected</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>



@push('script_2')
    <script>
        function replaceSelectOptions($select, activeTemplateSelector, inactiveTemplateSelector, isOutside) {
            // Save currently selected values
            const selectedValues = $select.val() || [];

            const hadSelect2 = $select.hasClass('js-select2-custom') && $select.data('select2');
            if (hadSelect2) {
                $select.select2('destroy');
            }
            $select.empty();

            // Choose the correct template based on checkbox state
            const templateSelector = isOutside ? inactiveTemplateSelector : activeTemplateSelector;
            const template = document.querySelector(templateSelector);

            if (template) {
                $select.append($(template.innerHTML.trim()));
            }

            // Get all available options in the new list
            const newOptions = $select.find('option').map(function() {
                return $(this).val();
            }).get();

            // Only restore values that exist in the current list
            const validSelectedValues = selectedValues.filter(val => newOptions.includes(val));
            $select.val(validSelectedValues);

            if ($select.hasClass('js-select2-custom')) {
                $select.select2();
            }
        }

        $(document).on('change', '#is_outside_clinic', function() {
            const isOutside = $(this).is(':checked');
            const $select = $('#test_ids');
            replaceSelectOptions($select, '#testsOptionsTemplate', '#outTestsOptionsTemplate', isOutside);
        });

        // $(document).on('change', '#edit_is_outside_clinic', function() {
        //     const isOutside = $(this).is(':checked');
        //     const $select = $('#edit_test_ids');
        //     replaceSelectOptions($select, '#editTestsOptionsTemplate', '#editOutTestsOptionsTemplate', isOutside);
        // });

        $(document).on('change', '#addtests_is_outside_clinic', function() {
            const isOutside = $(this).is(':checked');
            const $select = $('#addtests_test_ids');
            replaceSelectOptions($select, '#addTestsOptionsTemplate', '#addOutTestsOptionsTemplate', isOutside);
        });
    </script>
    <script>
        // Form submission handler
        $('#lab_test_custom_form').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var submitBtn = $('#submit_custom_result');

            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: '{{ route('admin.laboratory_result.store-custom') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    submitBtn.prop('disabled', false).html('{{ translate('Submit') }}');
                    toastr.success(response.message);
                    $('#add-result_test_custom').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html('{{ translate('Submit') }}');
                    toastr.error('Error submitting results');
                    console.error('Submission error:', xhr.responseText);
                }
            });
        });
    </script>

    <script>
        $('#edit_laboratory_request_form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.laboratory_request.update', '') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#editLaboratoryRequestModal').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while updating the laboratory request.');
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });
    </script>

    <script>
        $('#add_tests_to_request_form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.laboratory_request.add-tests') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#add_tests_to_request_form')[0].reset();
                        $('#addTestsModal').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while updating the laboratory request.');
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        $('#updateResultApprovalStatusForm').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.laboratory_result.verify-status.update') }}',
                data: formData,
                success: function(response) {
                    $('#updateResultApprovalStatusModal').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                    toastr.success('Result Approval status updated successfully!', {
                        closeButton: true,
                        progressBar: true,
                    });
                },
                error: function(error) {
                    console.error(error);
                    toastr.error('Failed to update result approval status. Please try again.', {
                        closeButton: true,
                        progressBar: true,
                    });
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        $('#bulkUpdateResultApprovalStatusForm').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.laboratory_result.bulk-verify-status.update') }}',
                data: formData,
                success: function(response) {
                    $('#bulkUpdateResultApprovalStatusModal').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                    toastr.success('Result Approval status updated successfully!', {
                        closeButton: true,
                        progressBar: true,
                    });
                },
                error: function(error) {
                    console.error(error);
                    toastr.error('Failed to update result approval status. Please try again.', {
                        closeButton: true,
                        progressBar: true,
                    });
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });
    </script>
@endpush

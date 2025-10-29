<div class="modal fade" id="add-radiology_request" tabindex="-1">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add New Radiology Request') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="radiology_request_form" enctype="multipart/form-data">
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
                        @if (!auth('admin')->user()->can('medical_record.add-new'))
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

                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Radiology Type') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="radiology_ids[]" id="radiology_ids"
                                    class="form-control js-select2-custom" multiple required>
                                    @foreach ($radiologies as $radiology)
                                        <option value="{{ $radiology->id }}">{{ $radiology->radiology_name }}
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

                    <div class="d-flex justify-content-end">
                        <button type="submit" id=""
                            class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editRadiologyRequestModal" tabindex="-1" role="dialog"
    aria-labelledby="editRadiologyRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRadiologyRequestModalLabel">Edit Radiology Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit_radiology_request_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_radiology_request_id" name="id">
                    <input type="hidden" name="visit_id" id="edit_visit_id">
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
                                <label class="input-label">{{ translate('Radiology Type') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="radiology_ids[]" id="radiology_ids_edit"
                                    class="form-control js-select2-custom" multiple required>
                                    @foreach ($radiologies as $radiology)
                                        <option value="{{ $radiology->id }}">{{ $radiology->radiology_name }}
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


<div class="modal fade" id="add-result_radiology" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Radiology Result') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="radiology_test_form" enctype="multipart/form-data">
                    @csrf
                    <input type="text" hidden name="radiology_request_test_id">
                    <input type="text" hidden name="processed_by" value="{{ auth('admin')->user()->id }}">
                    <div class="row pl-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="test_name">{{ \App\CentralLogics\translate('Select Radiology Type') }}</label>
                                <select name="radiology_type_id[]" id="radiology_name_result"
                                    class="form-control js-select2-custom" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="radiology_attributes_container"></div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" id=""
                            class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Radiology Result -->
<div class="modal fade" id="editRadiologyResultModal" tabindex="-1" role="dialog"
    aria-labelledby="editRadiologyResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRadiologyResultModalLabel">Edit Radiology Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editRadiologyResultForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="editRadiologyResultId" name="radiology_result_id">
                    <input type="hidden" name="processed_by" value="{{ auth('admin')->user()->id }}">
                    <input type="hidden" name="radiology_request_test_id" id="radiology_request_test_id">

                    <div id="radiologyAttributesContainer" class="mt-3">
                        {{-- <h6>Test Attributes</h6> --}}
                        <!-- Dynamically filled with JavaScript -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="updateRadiologyResultStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="updateRadiologyResultStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateRadiologyResultStatusModalLabel">Update Radiology Result Process
                    Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateRadiologyResultStatusForm">
                    @csrf
                    <input type="hidden" id="radiologyResultIdInput" name="result_id">
                    <div class="form-group">
                        <label for="radiologyResultStatusInput">Process Status</label>
                        <select class="form-control" id="radiologyResultStatusInput" name="process_status">
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

<div class="modal fade" id="updateRadiologyResultApprovalStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="updateRadiologyResultApprovalStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateRadiologyResultApprovalStatusModalLabel">Update Result Verify
                    Status
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateRadiologyResultApprovalStatusForm">
                    @csrf
                    <input type="hidden" id="approvalRadiologyResultIdInput" name="result_id">
                    <div class="form-group">
                        <label for="approvalRadiologyStatusInput">Verify Status</label>
                        <select class="form-control" id="approvalRadiologyStatusInput" name="verify_status">
                            <option value="pending">Pending</option>
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


<!-- Modal for Viewing Radiology Result -->
<div class="modal fade" id="viewRadiologyResultModal" tabindex="-1" role="dialog"
    aria-labelledby="viewRadiologyResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRadiologyResultModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Overall Test Result</h5>
                <div id="radiologyResultStatus"></div>
                <div id="radiologyResultComments"></div>
                <div id="radiologyResultAdditionalNote"></div>
                <div id="radiologyResultProcessedBy"></div>
                <div id="radiologyResultVerifiedBy"></div>
                <h5 class="mt-4">Images</h5>
                <div id="radiologyResultImage"></div>

                <h5 class="mt-4">Attributes</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Attribute</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody id="radiologyResultAttributes">
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

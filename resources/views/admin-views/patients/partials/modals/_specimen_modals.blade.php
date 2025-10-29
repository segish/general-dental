<div class="modal fade" id="add-medical_lab_test" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('add_new_specimen') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="medical_lab_test_form" enctype="multipart/form-data">
                    @csrf
                    <input type="text" hidden name="laboratory_request_id" id="modal_laboratory_request_id">
                    <input type="hidden" name="checker_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="checking_start_time" value="{{ now() }}">
                    <input type="hidden" name="status" value="pending">

                    <div class="row pl-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="test_name">{{ \App\CentralLogics\translate('Select Test Type') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="laboratory_request_test_ids[]" id="test_name"
                                    class="form-control js-select2-custom" multiple required>
                                    <!-- Test options will be populated here -->
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="test_name">{{ \App\CentralLogics\translate('Specimen Origin') }}</label>
                                <select name="specimen_origin_id" id="specimen_origin_id"
                                    class="form-control js-select2-custom">
                                    <option value="" selected disabled>Select specimen origin</option>
                                    @foreach ($specimenOrigins as $origin)
                                        <option value="{{ $origin->id }}">{{ $origin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label" for="specimen_taken_at">
                                    {{ \App\CentralLogics\translate('Specimen Taken At') }}<span
                                        class="input-label-secondary text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <input type="datetime-local" name="specimen_taken_at" class="form-control" required
                                        id="specimen_taken_at">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="" class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editSpecimenModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_specimen_form" method="POST" action="javascript:">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Edit Specimen') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <!-- Same input fields as Add Modal with different IDs -->
                    <div class="form-group">
                        <label>{{ translate('Specimen Origin') }}</label>
                        <select name="specimen_origin_id" class="form-control js-select2-custom" required>
                            @foreach ($specimenOrigins as $origin)
                                <option value="{{ $origin->id }}">{{ $origin->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Taken At') }}</label>
                        <input type="datetime-local" name="specimen_taken_at" class="form-control" required>
                    </div>

                    <!-- Add Test Edit functionality separately if needed -->

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="updateSpecimenStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="updateSpecimenStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateSpecimenStatusModalLabel">Update Specimen Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateSpecimenStatusForm">
                    @csrf
                    <input type="hidden" id="specimenIdInput" name="specimen_id">
                    <div class="form-group">
                        <label for="specimenStatusInput">Status</label>
                        <select class="form-control" id="specimenStatusInput" name="status">
                            <option value="accepted">Accepted</option>
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

<!-- Modal -->
<div class="modal fade" id="viewSpecimenModal" tabindex="-1" aria-labelledby="viewSpecimenModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md"> <!-- Adjusted modal size -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSpecimenModalLabel">Specimen Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="specimenDetailsContent" class="container-fluid">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

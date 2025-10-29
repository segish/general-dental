<!-- Add Dental Chart Modal -->
<div class="modal fade" id="add-dental-chart" tabindex="-1" role="dialog" aria-labelledby="dentalChartLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dentalChartLabel">{{ translate('Add Dental Chart') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="dentalChartForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="visit_id" id="dental_chart_visit_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Chart Type') }}<span
                                        class="text-danger">*</span></label>
                                <select name="chart_type" id="chart_type" class="form-control" required>
                                    <option value="">Select Chart Type</option>
                                    <option value="odontogram">Odontogram (Tooth Chart)</option>
                                    <option value="periodontal">Periodontal Chart</option>
                                    <option value="treatment_plan">Treatment Plan</option>
                                    <option value="clinical_drawing">Clinical Drawing</option>
                                    <option value="image_annotation">Image Annotation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Title') }}</label>
                                <input type="text" name="title" id="chart_title" class="form-control"
                                    placeholder="Enter chart title">
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload (for image_annotation type) -->
                    <div class="form-group" id="image_upload_group" style="display: none;">
                        <label class="input-label">{{ translate('Upload Background Image') }}</label>
                        <input type="file" name="image" id="chart_image" class="form-control" accept="image/*">
                        <small class="text-muted">Upload X-ray or clinical photo for annotation</small>
                    </div>

                    <!-- Drawing Canvas Container -->
                    <div class="form-group">
                        <label class="input-label">{{ translate('Dental Chart') }}</label>
                        <div class="border rounded p-2" style="background: #f8f9fa;">
                            <canvas id="dentalChartCanvas" height="600"
                                style="width: 100%; border: 1px solid #ddd; cursor: crosshair;"></canvas>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-secondary" id="clearCanvas">
                                <i class="tio-clear"></i> Clear
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" id="undoCanvas">
                                <i class="tio-back"></i> Undo
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" id="saveDraft">
                                <i class="tio-save"></i> Save Draft
                            </button>
                        </div>
                    </div>

                    <!-- Toolbar for Drawing Tools -->
                    <div class="form-group">
                        <label class="input-label">{{ translate('Drawing Tools') }}</label>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary active" data-tool="select"
                                title="Select/Move">
                                <i class="tio-cursor"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="circle"
                                title="Circle">
                                <i class="tio-circle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="rect"
                                title="Rectangle">
                                <i class="tio-square"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="line"
                                title="Line">
                                <i class="tio-remove"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="text"
                                title="Text">
                                <i class="tio-text"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="path"
                                title="Freehand">
                                <i class="tio-edit"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <label class="input-label">{{ translate('Color') }}</label>
                            <input type="color" id="strokeColor" value="#000000"
                                class="form-control form-control-color">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label class="input-label">{{ translate('Notes') }}</label>
                        <textarea name="notes" id="chart_notes" class="form-control" rows="3"
                            placeholder="Enter any additional notes"></textarea>
                    </div>

                    <!-- Hidden inputs for chart data -->
                    <input type="hidden" name="chart_data" id="chart_data_json">
                    <input type="hidden" name="tooth_data" id="tooth_data_json">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save Chart') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Dental Chart Modal -->
<div class="modal fade" id="editDentalChartModal" tabindex="-1" role="dialog"
    aria-labelledby="editDentalChartLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDentalChartLabel">{{ translate('Edit Dental Chart') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editDentalChartForm">
                @csrf
                <input type="hidden" name="chart_id" id="edit_chart_id">
                <div class="modal-body">
                    <!-- Same structure as add modal -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Chart Type') }}</label>
                                <select name="chart_type" id="edit_chart_type" class="form-control" readonly>
                                    <option value="odontogram">Odontogram (Tooth Chart)</option>
                                    <option value="periodontal">Periodontal Chart</option>
                                    <option value="treatment_plan">Treatment Plan</option>
                                    <option value="clinical_drawing">Clinical Drawing</option>
                                    <option value="image_annotation">Image Annotation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Title') }}</label>
                                <input type="text" name="title" id="edit_chart_title" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ translate('Dental Chart') }}</label>
                        <div class="border rounded p-2" style="background: #f8f9fa;">
                            <canvas id="editDentalChartCanvas" height="600"
                                style="width: 100%; border: 1px solid #ddd; cursor: crosshair;"></canvas>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-secondary" id="editClearCanvas">
                                <i class="tio-clear"></i> Clear
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" id="editUndoCanvas">
                                <i class="tio-back"></i> Undo
                            </button>
                        </div>
                    </div>

                    <!-- Toolbar for Edit Modal -->
                    <div class="form-group">
                        <label class="input-label">{{ translate('Drawing Tools') }}</label>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary active" data-tool="select"
                                title="Select/Move">
                                <i class="tio-cursor"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="circle"
                                title="Circle">
                                <i class="tio-circle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="rect"
                                title="Rectangle">
                                <i class="tio-square"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="line"
                                title="Line">
                                <i class="tio-remove"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="text"
                                title="Text">
                                <i class="tio-text"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-tool="path"
                                title="Freehand">
                                <i class="tio-edit"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <label class="input-label">{{ translate('Color') }}</label>
                            <input type="color" id="editStrokeColor" value="#000000"
                                class="form-control form-control-color">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ translate('Notes') }}</label>
                        <textarea name="notes" id="edit_chart_notes" class="form-control" rows="3"></textarea>
                    </div>

                    <input type="hidden" name="chart_data" id="edit_chart_data_json">
                    <input type="hidden" name="tooth_data" id="edit_tooth_data_json">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Update Chart') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Visit Document Upload Modal -->
<div class="modal fade" id="add-visit-document" tabindex="-1" role="dialog" aria-labelledby="addVisitDocumentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVisitDocumentModalLabel">
                    <i class="tio-upload mr-2"></i>Upload Visit Documents
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="visitDocumentForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="visit_document_visit_id" name="visit_id">

                    <!-- File Upload Section -->
                    <div class="form-group">
                        <label for="files" class="input-label">
                            <i class="tio-file mr-1"></i>Select Files
                            <span class="text-danger">*</span>
                        </label>
                        <div class="file-upload-wrapper">
                            <input type="file" class="form-control file-upload" style="height: 100%;" id="files" name="files[]"
                                multiple accept="image/*,.pdf,.doc,.docx,.txt" required>
                            <small class="form-text text-muted">
                                Supported formats: Images (JPG, PNG, GIF), PDF, Word documents, Text files. Max 10MB per
                                file.
                            </small>
                        </div>
                    </div>

                    <!-- Note Section -->
                    <div class="form-group">
                        <label for="note" class="input-label">
                            <i class="tio-note mr-1"></i>Note (Optional)
                        </label>
                        <textarea class="form-control" id="note" name="note" rows="3"
                            placeholder="Add a note about these documents..."></textarea>
                    </div>

                    <!-- Preview Section -->
                    <div id="filePreview" class="mt-3" style="display: none;">
                        <h6><i class="tio-image mr-1"></i>Selected Files:</h6>
                        <div id="previewContainer" class="row"></div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                            <i class="tio-clear mr-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitVisitDocument">
                            <i class="tio-upload mr-1"></i>Upload Documents
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Visit Document Edit Modal -->
<div class="modal fade" id="edit-visit-document" tabindex="-1" role="dialog"
    aria-labelledby="editVisitDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVisitDocumentModalLabel">
                    <i class="tio-edit mr-2"></i>Edit Document Note
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editVisitDocumentForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_document_id" name="document_id">

                    <div class="form-group">
                        <label for="edit_note" class="input-label">
                            <i class="tio-note mr-1"></i>Note
                        </label>
                        <textarea class="form-control" id="edit_note" name="note" rows="3"
                            placeholder="Add a note about this document..."></textarea>
                    </div>

                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                            <i class="tio-clear mr-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitEditVisitDocument">
                            <i class="tio-save mr-1"></i>Update Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Document Viewer Modal -->
<div class="modal fade" id="documentViewerModal" tabindex="-1" role="dialog"
    aria-labelledby="documentViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentViewerModalLabel">
                    <i class="tio-visible mr-2"></i>Document Viewer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="documentViewerContent" class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="tio-clear mr-1"></i>Close
                </button>
                <a href="#" id="downloadDocumentBtn" class="btn btn-primary" target="_blank">
                    <i class="tio-download mr-1"></i>Download
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .file-upload-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-upload {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    .file-upload:hover {
        border-color: #007bff;
        background-color: #e3f2fd;
    }

    .file-upload:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .file-preview-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f8f9fa;
    }

    .file-preview-item img {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
        border-radius: 4px;
    }

    .document-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #fff;
        transition: all 0.3s ease;
    }

    .document-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .document-icon {
        font-size: 2rem;
        color: #6c757d;
    }

    .document-actions {
        display: flex;
        gap: 5px;
    }

    .document-actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>

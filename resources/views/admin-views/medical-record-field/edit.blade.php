@extends('layouts.admin.app')

@section('title', translate('Update Medical Record Field'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/product.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('Update Medical Record Field') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.medical_record_field.update', $field->id) }}" method="post"
                            id="field-form">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label" for="name">
                                            {{ \App\CentralLogics\translate('Field Name') }} <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="{{ translate('e.g., Chief Complaint') }}" required maxlength="255"
                                            value="{{ $field->name }}" id="name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label" for="short_code">
                                            {{ \App\CentralLogics\translate('Short Code') }} <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="short_code" class="form-control"
                                            placeholder="{{ translate('e.g., chief_complaint') }}" required maxlength="255"
                                            value="{{ $field->short_code }}" id="short_code" pattern="[a-z0-9_]+"
                                            title="Only lowercase letters, numbers, and underscores allowed">
                                        <small
                                            class="text-muted">{{ translate('Use lowercase letters, numbers, and underscores only') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label" for="field_type">
                                            {{ \App\CentralLogics\translate('Field Type') }} <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select name="field_type" id="field_type" class="form-control" required>
                                            <option value="">{{ translate('Select field type') }}</option>
                                            <option value="text" {{ $field->field_type == 'text' ? 'selected' : '' }}>
                                                {{ translate('Text Input') }}</option>
                                            <option value="textarea"
                                                {{ $field->field_type == 'textarea' ? 'selected' : '' }}>
                                                {{ translate('Textarea') }}</option>
                                            <option value="select" {{ $field->field_type == 'select' ? 'selected' : '' }}>
                                                {{ translate('Select (Single)') }}</option>
                                            <option value="multiselect"
                                                {{ $field->field_type == 'multiselect' ? 'selected' : '' }}>
                                                {{ translate('Select (Multiple)') }}</option>
                                            <option value="checkbox"
                                                {{ $field->field_type == 'checkbox' ? 'selected' : '' }}>
                                                {{ translate('Checkbox Group') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="input-label" for="is_required">
                                            {{ \App\CentralLogics\translate('Required') }}
                                        </label>
                                        <select name="is_required" id="is_required" class="form-control">
                                            <option value="0" {{ !$field->is_required ? 'selected' : '' }}>
                                                {{ translate('No') }}</option>
                                            <option value="1" {{ $field->is_required ? 'selected' : '' }}>
                                                {{ translate('Yes') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="input-label" for="order">
                                            {{ \App\CentralLogics\translate('Order') }}
                                        </label>
                                        <input type="number" name="order" class="form-control" min="0"
                                            value="{{ $field->order }}" id="order">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label" for="status">
                                            {{ \App\CentralLogics\translate('Status') }}
                                        </label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" {{ $field->status ? 'selected' : '' }}>
                                                {{ translate('Active') }}</option>
                                            <option value="0" {{ !$field->status ? 'selected' : '' }}>
                                                {{ translate('Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Options Section (for select, multiselect, checkbox) -->
                            <div id="options-section" style="display: none;">
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>{{ \App\CentralLogics\translate('Field Options') }} <span
                                            class="text-danger">*</span></h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="add-option-btn">
                                        <i class="tio-add"></i> {{ translate('Add Option') }}
                                    </button>
                                </div>
                                <div id="options-container">
                                    <!-- Options will be loaded here -->
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <a href="{{ route('admin.medical_record_field.list') }}"
                                    class="btn btn-secondary">{{ \App\CentralLogics\translate('Cancel') }}</a>
                                <button type="submit"
                                    class="btn btn-primary">{{ \App\CentralLogics\translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        let optionIndex = 0;

        // Load existing options on page load
        @if ($field->options && count($field->options) > 0)
            @foreach ($field->options->sortBy('order') as $index => $option)
                addOption('{{ addslashes($option->option_value) }}', '{{ addslashes($option->option_label) }}');
            @endforeach
        @endif

        // Show/hide options section based on field type
        function toggleOptionsSection() {
            const fieldType = $('#field_type').val();
            const needsOptions = ['select', 'multiselect', 'checkbox'].includes(fieldType);

            if (needsOptions) {
                $('#options-section').show();
                if ($('#options-container').children().length === 0) {
                    addOption();
                }
            } else {
                $('#options-section').hide();
            }
        }

        // Initial toggle on page load
        toggleOptionsSection();

        $('#field_type').on('change', function() {
            toggleOptionsSection();
        });

        // Add new option row
        $('#add-option-btn').on('click', function() {
            addOption();
        });

        function addOption(optionValue = '', optionLabel = '') {
            const optionHtml = `
                <div class="row mb-2 option-row" data-index="${optionIndex}">
                    <div class="col-md-5">
                        <input type="text" name="options[${optionIndex}][option_value]"
                            class="form-control" placeholder="{{ translate('Option Value') }}"
                            value="${optionValue}" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="options[${optionIndex}][option_label]"
                            class="form-control" placeholder="{{ translate('Option Label') }}"
                            value="${optionLabel}" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-block remove-option">
                            <i class="tio-delete"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#options-container').append(optionHtml);
            optionIndex++;
        }

        // Remove option row
        $(document).on('click', '.remove-option', function() {
            $(this).closest('.option-row').remove();
        });

        // Form validation
        $('#field-form').on('submit', function(e) {
            const fieldType = $('#field_type').val();
            const needsOptions = ['select', 'multiselect', 'checkbox'].includes(fieldType);

            if (needsOptions && $('#options-container').children().length === 0) {
                e.preventDefault();
                toastr.error('{{ translate('Please add at least one option') }}');
                return false;
            }
        });
    </script>
@endpush

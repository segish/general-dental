@extends('layouts.admin.app')

@section('title', translate('Edit Test Attribute'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('Edit Test Attribute') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.test_attribute.update', $testAttribute->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Test Type') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="test_id" id="test_id" class="form-control js-select2-custom"
                                            required>
                                            <option disabled>Select test type</option>
                                            @foreach ($tests as $test)
                                                <option value="{{ $test->id }}"
                                                    {{ $testAttribute->test_id == $test->id ? 'selected' : '' }}>
                                                    {{ $test->test_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Attribute Name -->
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="attribute_name">{{ \App\CentralLogics\translate('Attribute Name') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="attribute_name" class="form-control"
                                            placeholder="{{ translate('Enter Attribute Name') }}" required maxlength="100"
                                            id="attribute_name" value="{{ $testAttribute->attribute_name }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="test_category">{{ \App\CentralLogics\translate('Attriubte Category') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="test_category" class="form-control js-select2-custom" required>
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('Select attribute category') }}</option>
                                            <option {{ $testAttribute->test_category == 'Macroscopic' ? 'selected' : '' }}
                                                value="Macroscopic">Macroscopic</option>
                                            <option {{ $testAttribute->test_category == 'Microscopic' ? 'selected' : '' }}
                                                value="Microscopic">Microscopic</option>
                                            <option {{ $testAttribute->test_category == 'Chemical' ? 'selected' : '' }}
                                                value="Chemical">Chemical</option>
                                            <option {{ $testAttribute->test_category == 'Text' ? 'selected' : '' }}
                                                value="Text">Text</option>
                                            <option {{ $testAttribute->test_category == 'Result' ? 'selected' : '' }}
                                                value="Result">Result</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="input-label" for="index">
                                            {{ \App\CentralLogics\translate('Index') }}
                                        </label>
                                        <input type="number" name="index" class="form-control"
                                            placeholder="{{ translate('Enter Index') }}" id="index"
                                            min="1" step="1" value="{{ $testAttribute->index }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="attribute_type">{{ \App\CentralLogics\translate('Attribute Type') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="attribute_type" class="form-control js-select2-custom" required>
                                            <option disabled>
                                                {{ \App\CentralLogics\translate('Select attribute type') }}</option>
                                            <option value="Qualitative"
                                                {{ $testAttribute->attribute_type == 'Qualitative' ? 'selected' : '' }}>
                                                Qualitative</option>
                                            <option value="Quantitative"
                                                {{ $testAttribute->attribute_type == 'Quantitative' ? 'selected' : '' }}>
                                                Quantitative</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="has_options">{{ \App\CentralLogics\translate('Has Options') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="has_options" id="has_options" class="form-control js-select2-custom"
                                            required>
                                            <option disabled>
                                                {{ \App\CentralLogics\translate('Attribute has option?') }}</option>
                                            <option value="0"
                                                {{ $testAttribute->has_options == 0 ? 'selected' : '' }}>No</option>
                                            <option value="1"
                                                {{ $testAttribute->has_options == 1 ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 ">
                                    <!-- Unit -->
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="unit">{{ \App\CentralLogics\translate('Unit') }}</label>
                                        <select name="unit_id" class="form-control js-select2-custom">
                                            <option value="">
                                                {{ \App\CentralLogics\translate('Select Unit') }}</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ $testAttribute->unit_id == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="default_required">{{ \App\CentralLogics\translate('Default Required') }}</label>
                                        <select name="default_required" class="form-control js-select2-custom"
                                            id="default_required">
                                            <option value="0"
                                                {{ $testAttribute->default_required == 0 ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('No') }}</option>
                                            <option value="1"
                                                {{ $testAttribute->default_required == 1 ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('Yes') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Attribute Reference Section - Will be shown when has_options is Yes -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">{{ \App\CentralLogics\translate('Edit References') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            {{-- Hidden input to track how many reference rows are added --}}
                                            <input type="hidden" id="reference-counter" name="reference_counter"
                                                value="{{ count($testAttribute->attributeReferences) }}">

                                            <div id="reference_container">
                                                <!-- Existing reference fields will be added here dynamically -->
                                                @foreach ($testAttribute->attributeReferences as $index => $reference)
                                                    <div class="reference-row border p-3 mb-2">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label>Gender</label>
                                                                <select name="references[{{ $index }}][gender]"
                                                                    class="form-control js-select2-custom">
                                                                    <option value=""
                                                                        {{ !$reference->gender ? 'selected disabled' : '' }}>
                                                                        Select Gender</option>
                                                                    <option value="male"
                                                                        {{ $reference->gender == 'male' ? 'selected' : '' }}>
                                                                        Male</option>
                                                                    <option value="female"
                                                                        {{ $reference->gender == 'female' ? 'selected' : '' }}>
                                                                        Female</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Min Age</label>
                                                                <input type="number"
                                                                    name="references[{{ $index }}][min_age]"
                                                                    class="form-control"
                                                                    value="{{ $reference->min_age }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Max Age</label>
                                                                <input type="number"
                                                                    name="references[{{ $index }}][max_age]"
                                                                    class="form-control"
                                                                    value="{{ $reference->max_age }}">
                                                            </div>
                                                            {{-- <div class="col-md-3">
                                                                <label>Pregnant?</label>
                                                                <select
                                                                    name="references[{{ $index }}][is_pregnant]"
                                                                    class="form-control js-select2-custom">
                                                                    <option value=""
                                                                        {{ is_null($reference->is_pregnant) ? 'selected disabled' : '' }}>
                                                                        Pregnancy</option>
                                                                    <option value="1"
                                                                        {{ $reference->is_pregnant == 1 ? 'selected' : '' }}>
                                                                        Yes</option>
                                                                    <option value="0"
                                                                        {{ $reference->is_pregnant === 0 ? 'selected' : '' }}>
                                                                        No</option>
                                                                </select>
                                                            </div> --}}
                                                            <div class="col-md-3 mt-2">
                                                                <label>Lower Limit</label>
                                                                <div class="d-flex">
                                                                    <select
                                                                        name="references[{{ $index }}][lower_operator]"
                                                                        class="form-control js-select2-custom">
                                                                        <option value=""
                                                                            {{ !$reference->lower_operator ? 'selected disabled' : '' }}>
                                                                            Operators</option>
                                                                        <option value=">"
                                                                            {{ $reference->lower_operator == '>' ? 'selected' : '' }}>
                                                                            ></option>
                                                                        <option value=">="
                                                                            {{ $reference->lower_operator == '>=' ? 'selected' : '' }}>
                                                                            >=</option>
                                                                        <option value="="
                                                                            {{ $reference->lower_operator == '=' ? 'selected' : '' }}>
                                                                            =</option>
                                                                    </select>
                                                                    <input type="number"
                                                                        name="references[{{ $index }}][lower_limit]"
                                                                        class="form-control" step="any"
                                                                        value="{{ $reference->lower_limit }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 mt-2">
                                                                <label>Upper Limit</label>
                                                                <div class="d-flex">
                                                                    <select
                                                                        name="references[{{ $index }}][upper_operator]"
                                                                        class="form-control js-select2-custom">
                                                                        <option value=""
                                                                            {{ !$reference->upper_operator ? 'selected disabled' : '' }}>
                                                                            Operators</option>
                                                                        <option value="<"
                                                                            {{ $reference->upper_operator == '<' ? 'selected' : '' }}>
                                                                            <</option>
                                                                        <option value="<="
                                                                            {{ $reference->upper_operator == '<=' ? 'selected' : '' }}>
                                                                            <=</option>
                                                                        <option value="="
                                                                            {{ $reference->upper_operator == '=' ? 'selected' : '' }}>
                                                                            =</option>
                                                                    </select>
                                                                    <input type="number"
                                                                        name="references[{{ $index }}][upper_limit]"
                                                                        class="form-control" step="any"
                                                                        value="{{ $reference->upper_limit }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mt-2">
                                                                <label>Reference Text</label>
                                                                <input type="text"
                                                                    name="references[{{ $index }}][reference_text]"
                                                                    class="form-control"
                                                                    value="{{ $reference->reference_text }}">
                                                            </div>
                                                            <div
                                                                class="col-md-2 mt-2 d-flex align-items-end justify-content-end">
                                                                <button type="button"
                                                                    class="btn btn-danger remove-reference">
                                                                    <i class="tio-delete"></i>
                                                                </button>
                                                            </div>
                                                            <input type="hidden"
                                                                name="references[{{ $index }}][id]"
                                                                value="{{ $reference->id }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="mt-3">
                                                <button type="button" id="add_reference" class="btn btn-sm btn-primary">
                                                    {{ \App\CentralLogics\translate('Add Reference') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Options Section - Will be shown when has_options is Yes -->
                            <div id="options_section" class="row mb-3"
                                style="{{ $testAttribute->has_options ? '' : 'display: none;' }}">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">{{ \App\CentralLogics\translate('Attribute Options') }}
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="options_container">
                                                <!-- Existing option fields will be added here dynamically -->
                                                @if ($testAttribute->has_options && $testAttribute->options)
                                                    @foreach ($testAttribute->options as $index => $option)
                                                        <div class="option-row mb-2">
                                                            <div class="input-group">
                                                                <input type="text" name="options[]"
                                                                    class="form-control"
                                                                    placeholder="{{ translate('Enter option value') }}"
                                                                    required value="{{ $option->option_value }}">
                                                                <div class="input-group-append">
                                                                    <button type="button"
                                                                        class="btn btn-danger remove-option">
                                                                        <i class="tio-delete"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="mt-3">
                                                <button type="button" id="add_option" class="btn btn-sm btn-primary">
                                                    {{ \App\CentralLogics\translate('Add Option') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit and Reset Buttons -->
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset"
                                    class="btn btn-secondary">{{ \App\CentralLogics\translate('Reset') }}</button>
                                <button type="submit"
                                    class="btn btn-primary">{{ \App\CentralLogics\translate('Update') }}</button>
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result).show();
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                $('#viewer').hide(); // Hide the image if no file is selected
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
        });
    </script>

    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <!-- Script for handling options -->
    <script>
        $(document).ready(function() {
            // Handle has_options change
            $('#has_options').on('change', function() {
                if ($(this).val() == 1) {
                    $('#options_section').show();
                    // Add at least one option field by default if none exists
                    if ($('#options_container .option-row').length === 0) {
                        addOptionField();
                    }
                } else {
                    $('#options_container').empty();
                    $('#options_section').hide();
                }
            });

            // Add option button click handler
            $('#add_option').on('click', function() {
                addOptionField();
            });

            // Function to add a new option field
            function addOptionField() {
                const optionCount = $('#options_container .option-row').length;
                const optionHtml = `
                    <div class="option-row mb-2">
                        <div class="input-group">
                            <input type="text" name="options[]" class="form-control" placeholder="{{ translate('Enter option value') }}" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger remove-option">
                                    <i class="tio-delete"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#options_container').append(optionHtml);
            }

            // Remove option button click handler
            $(document).on('click', '.remove-option', function() {
                $(this).closest('.option-row').remove();
            });

            $('#add_reference').on('click', function() {
                let counter = parseInt($('#reference-counter').val());

                const refFields = `
        <div class="reference-row border p-3 mb-2">
            <div class="row">
                <div class="col-md-4">
                    <label>Gender</label>
                    <select name="references[${counter}][gender]" class="form-control js-select2-custom">
                        <option value="" selected disabled>Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Min Age</label>
                    <input type="number" name="references[${counter}][min_age]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Max Age</label>
                    <input type="number" name="references[${counter}][max_age]" class="form-control">
                </div>
                {{-- <div class="col-md-3">
                    <label>Pregnant?</label>
                    <select name="references[${counter}][is_pregnant]" class="form-control js-select2-custom">
                        <option value="" selected disabled>Pregnancy</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div> --}}
                <div class="col-md-3 mt-2">
                    <label>Lower Limit</label>
                    <div class="d-flex">
                        <select name="references[${counter}][lower_operator]" class="form-control js-select2-custom">
                            <option value="" selected disabled>Operators</option>
                            <option value=">">></option>
                            <option value=">=">>=</option>
                            <option value="=">=</option>
                        </select>
                        <input type="number" name="references[${counter}][lower_limit]" class="form-control" step="any">
                    </div>
                </div>
                <div class="col-md-3 mt-2">
                    <label>Upper Limit</label>
                    <div class="d-flex">
                        <select name="references[${counter}][upper_operator]" class="form-control js-select2-custom">
                            <option value="" selected disabled>Operators</option>
                            <option value="<"><</option>
                            <option value="<="><=</option>
                            <option value="=">=</option>
                        </select>
                        <input type="number" name="references[${counter}][upper_limit]" class="form-control" step="any">
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <label>Reference Text</label>
                    <input type="text" name="references[${counter}][reference_text]" class="form-control">
                </div>
                <div class="col-md-2 mt-2 d-flex align-items-end justify-content-end">
                    <button type="button" class="btn btn-danger remove-reference">
                        <i class="tio-delete"></i>
                    </button>
                </div>
            </div>
        </div>`;

                const $newFields = $(refFields).appendTo('#reference_container');
                $('#reference-counter').val(counter + 1);

                $.HSCore.components.HSSelect2.init($newFields.find('.js-select2-custom'));
            });

        });
        $(document).on('click', '.remove-reference', function() {
            $(this).closest('.reference-row').remove();
        });
    </script>
@endpush

@push('script')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush

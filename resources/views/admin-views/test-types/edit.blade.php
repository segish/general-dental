@extends('layouts.admin.app')

@section('title', translate('Update Test Type'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset(config('app.asset_path') . '/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/product.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('update_test_type') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.test.update', $testType->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Test Name') }}</label>
                                        <input type="text" name="test_name" class="form-control"
                                            placeholder="{{ translate('New Test Name') }}" required
                                            value="{{ $testType->test_name }}">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Title') }}</label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="{{ translate('Enter Title') }}" required
                                            value="{{ $testType->title }}">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Test Category') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="test_category_id" id="test_category_id"
                                            class="form-control js-select2-custom" required>
                                            @foreach ($testCategories as $testCategorie)
                                                <option value="{{ $testCategorie->id }}"
                                                    {{ $testCategorie->id == $testType->test_category_id ? 'selected' : '' }}>
                                                    {{ $testCategorie->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Cost') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="cost" class="form-control"
                                            placeholder="{{ translate('New test cost') }}" required
                                            value="{{ $testType->cost }}" min="0" step="0.01">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Time Taken (Hour)') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="time_taken_hour" class="form-control"
                                            placeholder="{{ translate('Hours') }}" required
                                            value="{{ $testType->time_taken_hour }}" min="0" max="23">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Time Taken (Minutes)') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="time_taken_min" class="form-control"
                                            placeholder="{{ translate('Minutes') }}" required
                                            value="{{ $testType->time_taken_min }}" min="0" max="59">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Result Type') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="result_type" class="form-control js-select2-custom" required>
                                            <option value="multi-type"
                                                {{ $testType->result_type == 'multi-type' ? 'selected' : '' }}>
                                                {{ translate('Multi-Type') }}
                                            </option>
                                            <option value="numeric"
                                                {{ $testType->result_type == 'numeric' ? 'selected' : '' }}>
                                                {{ translate('Numeric Result Type Test') }}
                                            </option>
                                            <option value="text"
                                                {{ $testType->result_type == 'text' ? 'selected' : '' }}>
                                                {{ translate('Test Type Test') }}
                                            </option>
                                            <option value="other"
                                                {{ $testType->result_type == 'other' ? 'selected' : '' }}>
                                                {{ translate('Other') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Paper Size') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="paper_size" class="form-control js-select2-custom" required>
                                            <option value="A4" {{ $testType->paper_size == 'A4' ? 'selected' : '' }}>
                                                {{ translate('A4') }}
                                            </option>
                                            <option value="A5" {{ $testType->paper_size == 'A5' ? 'selected' : '' }}>
                                                {{ translate('A5') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Paper Orientation') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="paper_orientation" class="form-control js-select2-custom" required>
                                            <option value="portrait"
                                                {{ $testType->paper_orientation == 'portrait' ? 'selected' : '' }}>
                                                {{ translate('Portrait') }}
                                            </option>
                                            <option value="landscape"
                                                {{ $testType->paper_orientation == 'landscape' ? 'selected' : '' }}>
                                                {{ translate('Landscape') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Page Display') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="page_display" class="form-control js-select2-custom" required>
                                            <option value="single"
                                                {{ $testType->page_display == 'single' ? 'selected' : '' }}>
                                                {{ translate('Single') }}
                                            </option>
                                            <option value="group"
                                                {{ $testType->page_display == 'group' ? 'selected' : '' }}>
                                                {{ translate('Group') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="test_name">{{ \App\CentralLogics\translate('Specimen Type') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="specimen_type_id" id="specimen_type_id"
                                            class="form-control js-select2-custom" required>
                                            <option value="" disabled selected>
                                                {{ translate('Select Specimen Type') }}
                                            </option>
                                            @foreach ($specimenTypes as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ $testType->specimen_type_id == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Result Source') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="result_source" id="result_source"
                                            class="form-control js-select2-custom" required>
                                            <option value="" disabled selected>
                                                {{ translate('Select Result Source') }}
                                            </option>
                                            <option value="machine"
                                                {{ $testType->result_source == 'machine' ? 'selected' : '' }}>
                                                Machine</option>
                                            <option value="manual"
                                                {{ $testType->result_source == 'manual' ? 'selected' : '' }}>
                                                Manual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="is_active">{{ \App\CentralLogics\translate('Is Active') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="is_active" class="form-control js-select2-custom" required>
                                            <option value="1" {{ $testType->is_active == 1 ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('Yes') }}</option>
                                            <option value="0" {{ $testType->is_active == 0 ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('No') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Description') }}</label>
                                        <textarea name="description" class="form-control">{{ $testType->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Additional Notes') }}</label>
                                        <textarea name="additional_notes" class="ckeditor form-control">{{ $testType->additional_notes }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
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
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
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
@endpush

@push('script')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush

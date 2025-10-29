@extends('layouts.admin.app')

@section('title', translate('add_new_test_category'))

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
                {{ \App\CentralLogics\translate('Add new test type') }}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.test.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Test Name') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="test_name" class="form-control"
                                            placeholder="{{ translate('New test nane') }}" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Test Title') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="{{ translate('Title Name for the test') }}" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Test Category') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="test_category_id" id="test_category_id"
                                            class="form-control js-select2-custom" required>
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('Select test category') }}</option>
                                            @foreach ($testCategories as $testCategorie)
                                                <option value="{{ $testCategorie->id }}">{{ $testCategorie->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Cost') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="number" name="cost" class="form-control"
                                            placeholder="{{ translate('Enter cost') }}" required min="0"
                                            step="0.01">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Result Type') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="result_type" id="result_type" class="form-control js-select2-custom"
                                            required>
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('Select result type') }}</option>
                                            <option value="multi-type">Multiple Type Test</option>
                                            <option value="numeric">Numeric Result Type Test</option>
                                            <option value="text">Test Type Test</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Paper Size') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="paper_size" id="paper_size" class="form-control js-select2-custom"
                                            required>
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('Select paper size') }}
                                            </option>
                                            <option value="A4">A4</option>
                                            <option value="A5">A5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Time Taken hour') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="number" name="time_taken_hour" class="form-control"
                                            placeholder="{{ translate('Enter test time taken') }}" required min="0"
                                            step="1">

                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Time Taken Minutes') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="number" name="time_taken_min" class="form-control"
                                            placeholder="{{ translate('Enter test time taken') }}" required min="0"
                                            step="1">

                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Paper Orientation') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="paper_orientation" id="paper_orientation"
                                            class="form-control js-select2-custom" required>
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('Select paper orientation') }}
                                            </option>
                                            <option value="portrait">Portrait</option>
                                            <option value="landscape">Landscape</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Page Display') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="page_display" id="page_display"
                                            class="form-control js-select2-custom" required>
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('Select page display type') }}
                                            </option>
                                            <option value="single">Single</option>
                                            <option value="group">Group</option>
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
                                            <option value="" selected disabled>Select specimen type</option>
                                            @foreach ($specimenTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
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
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('Select result source') }}
                                            </option>
                                            <option value="machine">Machine</option>
                                            <option value="manual">Manual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ \App\CentralLogics\translate('description') }}</label>
                                        <div class="form-group">
                                            <textarea name="description" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Additional Notes') }}</label>
                                        <textarea name="additional_notes" class="ckeditor form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset"
                                    class="btn btn-secondary">{{ \App\CentralLogics\translate('reset') }}</button>
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

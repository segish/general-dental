@extends('layouts.admin.app')

@section('title', translate('Update Radiology Attribute'))

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
                {{ \App\CentralLogics\translate('Update Radiology Attribute') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.radiology_attribute.update', $radiologyAttribute->id) }}"
                            method="post">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Radiology Type') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="radiology_id" id="radiology_id" class="form-control js-select2-custom"
                                            required>
                                            <option selected disabled>Select radiology type</option>
                                            @foreach ($radiologies as $radiology)
                                                <option value="{{ $radiology->id }}"
                                                    {{ $radiology->id == $radiologyAttribute->radiology_id ? 'selected' : '' }}>
                                                    {{ $radiology->radiology_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="attribute_name">{{ \App\CentralLogics\translate('Attribute Name') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="attribute_name" class="form-control"
                                            placeholder="{{ translate('Enter Attribute Name') }}" required maxlength="100"
                                            id="attribute_name" value="{{ $radiologyAttribute->attribute_name }}">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="result_type">{{ \App\CentralLogics\translate('Result Type') }}</label>
                                        <select name="result_type" class="form-control" id="result_type">
                                            <option value="short"
                                                {{ $radiologyAttribute->result_type == 'short' ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('Short word/Numeric') }}</option>
                                            <option value="paragraph"
                                                {{ $radiologyAttribute->result_type == 'paragraph' ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('Paragraph') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="default_required">{{ \App\CentralLogics\translate('Default Required') }}</label>
                                        <select name="default_required" class="form-control" id="default_required">
                                            <option value="0"
                                                {{ $radiologyAttribute->default_required == 0 ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('No') }}</option>
                                            <option value="1"
                                                {{ $radiologyAttribute->default_required == 1 ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('Yes') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="template">{{ \App\CentralLogics\translate('Template') }}</label>
                                        <textarea name="template" class="form-control ckeditor" placeholder="{{ translate('Enter Template') }}"
                                            value="{{ $radiologyAttribute->template }}">{{ $radiologyAttribute->template }}</textarea>
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
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush

@push('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.ckeditor').forEach((el) => {
                ClassicEditor
                    .create(el)
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endpush

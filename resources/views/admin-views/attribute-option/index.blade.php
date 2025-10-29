@extends('layouts.admin.app')

@section('title', translate('Add New Attribute Option'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('Add New Attribute Option') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.attribute_option.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Test Attribute') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="attribute_id" id="attribute_id" class="form-control js-select2-custom"
                                            required>
                                            <option selected disabled>Select test attribute</option>
                                            @foreach ($testAttributes as $testAttribute)
                                                <option value="{{ $testAttribute->id }}">
                                                    {{ $testAttribute->attribute_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="option_value">{{ \App\CentralLogics\translate('Option Value') }}</label>
                                        <input type="text" name="option_value" class="form-control"
                                            placeholder="{{ translate('Option Value') }}" required maxlength="255"
                                            id="option_value">
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
@endpush

@push('script')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush

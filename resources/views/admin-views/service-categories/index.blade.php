@extends('layouts.admin.app')

@section('title', translate('Add New Service Category'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset(config('app.asset_path') . '/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/service.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('add_new_billing_service') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.service_category.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="name">{{ \App\CentralLogics\translate('Service Category Name') }}</label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ translate('New service category') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="service_type">{{ \App\CentralLogics\translate('Service Type') }}</label>
                                    <select name="service_type[]" class="form-control js-select2-custom" required multiple>
                                        <option value="" disabled>
                                            {{ \App\CentralLogics\translate('Select Service Type') }}</option>
                                        <option value="prescription">{{ \App\CentralLogics\translate('Prescription') }}
                                        </option>
                                        <option value="billing service">
                                            {{ \App\CentralLogics\translate('Medical Record') }}
                                        <option value="medical record">{{ \App\CentralLogics\translate('Medical Record') }}
                                        </option>
                                        <option value="diagnosis">{{ \App\CentralLogics\translate('Diagnosis') }}</option>
                                        <option value="lab test">{{ \App\CentralLogics\translate('Laboratory Test') }}
                                        </option>
                                        <option value="radiology">{{ \App\CentralLogics\translate('Radiology') }}</option>
                                        <option value="vital sign">{{ \App\CentralLogics\translate('Vital Sign') }}
                                        </option>
                                        <option value="pregnancy">{{ \App\CentralLogics\translate('Pregnancy') }}</option>
                                        <option value="delivery summary">
                                            {{ \App\CentralLogics\translate('delivery summary') }}</option>
                                        <option value="newborn">{{ \App\CentralLogics\translate('New Born') }}</option>
                                        <option value="discharge">{{ \App\CentralLogics\translate('Discharge') }}</option>
                                        <option value="pregnancy history">
                                            {{ \App\CentralLogics\translate('Pregnancy History') }}</option>
                                        <option value="pregnancy history">
                                            {{ \App\CentralLogics\translate('Labour Followup') }}</option>
                                        <option value="dental_chart">{{ \App\CentralLogics\translate('Dental Chart') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label class="input-label"
                                        for="description">{{ \App\CentralLogics\translate('Description') }}</label>
                                    <textarea name="description" class="ckeditor form-control"></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset"
                                    class="btn btn-secondary">{{ \App\CentralLogics\translate('Reset') }}</button>
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

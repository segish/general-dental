@extends('layouts.admin.app')

@section('title', translate('Add New Billing Service'))

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
                        <form action="{{ route('admin.service.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="service_name">{{ \App\CentralLogics\translate('service_name') }}</label>
                                    <input type="text" name="service_name" class="form-control"
                                        placeholder="{{ translate('New Service') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="price">{{ \App\CentralLogics\translate('Price') }}</label>
                                    <input type="number" name="price" class="form-control"
                                        placeholder="{{ \translate('Cost') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="billing_type">{{ \App\CentralLogics\translate('Billing Type') }}</label>
                                    <select name="billing_type" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select Billing Type') }}</option>
                                        <option value="one-time">{{ \App\CentralLogics\translate('One Time') }}</option>
                                        <option value="recurring">{{ \App\CentralLogics\translate('Recurring') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="billing_interval_days">{{ \App\CentralLogics\translate('Billing Interval (Days)') }}</label>
                                    <input type="number" name="billing_interval_days" class="form-control"
                                        placeholder="{{ \translate('Enter days for recurring billing') }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="payment_timing">{{ \App\CentralLogics\translate('Payment Timing') }}</label>
                                    <select name="payment_timing" class="form-control js-select2-custom" required>
                                        <option value="" disabled>
                                            {{ \App\CentralLogics\translate('Select payment timing') }}</option>
                                        <option value="prepaid" selected>{{ \App\CentralLogics\translate('Prepaid') }}</option>
                                        <option value="postpaid">{{ \App\CentralLogics\translate('Postpaid') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="service_category_id">{{ \App\CentralLogics\translate('Service Category') }}</label>
                                    <select name="service_category_id" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select Service Category') }}</option>
                                        @foreach ($serviceCategories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
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

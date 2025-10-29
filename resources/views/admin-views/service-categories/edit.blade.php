@extends('layouts.admin.app')

@section('title', translate('Edit Service Category'))

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
                {{ \App\CentralLogics\translate('edit_billing_service') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.service_category.update', $serviceCategory->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="name">{{ \App\CentralLogics\translate('Service Category Name') }}</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $serviceCategory->name) }}"
                                        placeholder="{{ translate('Service category name') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="service_type">{{ \App\CentralLogics\translate('Service Type') }}</label>
                                    <select name="service_type[]" class="form-control js-select2-custom" multiple required>
                                        @php
                                            $types = [
                                                'prescription',
                                                'medical record',
                                                'billing service',
                                                'diagnosis',
                                                'lab test',
                                                'radiology',
                                                'vital sign',
                                                'pregnancy',
                                                'delivery summary',
                                                'newborn',
                                                'discharge',
                                                'pregnancy history',
                                                'Labour Followup',
                                            ];
                                            $selectedTypes = old('service_type', $serviceCategory->service_type ?? []);
                                        @endphp
                                        @foreach ($types as $type)
                                            <option value="{{ $type }}"
                                                {{ in_array($type, $selectedTypes) ? 'selected' : '' }}>
                                                {{ ucwords($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label class="input-label"
                                        for="description">{{ \App\CentralLogics\translate('Description') }}</label>
                                    <textarea name="description" class="ckeditor form-control">{{ old('description', $serviceCategory->description) }}</textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('admin.service_category.list') }}" class="btn btn-secondary">
                                    {{ \App\CentralLogics\translate('Back') }}
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    {{ \App\CentralLogics\translate('Update') }}
                                </button>
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
                $.HSCore.components.HSSelect2.init($(this));
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

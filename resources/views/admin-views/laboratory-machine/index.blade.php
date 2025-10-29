@extends('layouts.admin.app')

@section('title', translate('Add New Laboratory Machine'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('Add New Laboratory Machine') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.laboratory-machine.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="name">{{ \App\CentralLogics\translate('Name') }}</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="{{ translate('Enter machine name') }}" required maxlength="100"
                                            id="name">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="model">{{ \App\CentralLogics\translate('Model') }}</label>
                                        <input type="text" name="model" class="form-control"
                                            placeholder="{{ translate('Enter machine model') }}" maxlength="100"
                                            id="model">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="serial_number">{{ \App\CentralLogics\translate('Serial Number') }}</label>
                                        <input type="text" name="serial_number" class="form-control"
                                            placeholder="{{ translate('Enter serial number') }}" maxlength="100"
                                            id="serial_number">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="manufacturer">{{ \App\CentralLogics\translate('Manufacturer') }}</label>
                                        <input type="text" name="manufacturer" class="form-control"
                                            placeholder="{{ translate('Enter manufacturer name') }}" maxlength="100"
                                            id="manufacturer">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="code">{{ \App\CentralLogics\translate('Code') }}</label>
                                        <input type="text" name="code" class="form-control"
                                            placeholder="{{ translate('Enter machine code') }}" required maxlength="100"
                                            id="code">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="description">{{ \App\CentralLogics\translate('Description') }}</label>
                                        <textarea name="description" class="ckeditor form-control" id="description"></textarea>
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
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush

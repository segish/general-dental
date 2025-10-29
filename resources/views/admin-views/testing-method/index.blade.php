@extends('layouts.admin.app')

@section('title', translate('Add New Testing Method'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('Add New Testing Method') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.testing-method.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="method_code">{{ \App\CentralLogics\translate('Method Code') }}</label>
                                        <input type="text" name="method_code" class="form-control"
                                            placeholder="{{ translate('Enter testing method code') }}" required maxlength="100"
                                            id="method_code">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="method_description">{{ \App\CentralLogics\translate('Method Description') }}</label>
                                        <textarea name="method_description" class="form-control" id="method_description" required></textarea>
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

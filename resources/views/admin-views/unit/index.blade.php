@extends('layouts.admin.app')

@section('title', translate('Add New Unit'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('Add New Unit') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.unit.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="name">{{ \App\CentralLogics\translate('Unit Name') }}</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="{{ translate('Enter unit name') }}" required maxlength="255"
                                            id="name">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="code">{{ \App\CentralLogics\translate('Unit Code') }}</label>
                                        <input type="text" name="code" class="form-control"
                                            placeholder="{{ translate('Enter unit code') }}" required maxlength="50"
                                            id="code">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="description">{{ \App\CentralLogics\translate('Unit Description') }}</label>
                                        <textarea name="description" class="form-control" id="description"></textarea>
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

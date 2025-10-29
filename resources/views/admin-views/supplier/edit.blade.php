@extends('layouts.admin.app')

@section('title', translate('Update Supplier'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/product.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('Update Supplier') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.supplier.update', $supplier->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="name">{{ \App\CentralLogics\translate('Supplier Name') }}</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="{{ translate('Enter supplier name') }}" required maxlength="255"
                                            value="{{ $supplier->name }}" id="name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="contact_person">{{ \App\CentralLogics\translate('Contact Person') }}</label>
                                        <input type="text" name="contact_person" class="form-control"
                                            placeholder="{{ translate('Enter contact person name') }}" maxlength="255"
                                            value="{{ $supplier->contact_person }}" id="contact_person">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="phone">{{ \App\CentralLogics\translate('Phone') }}</label>
                                        <input type="text" name="phone" class="form-control"
                                            placeholder="{{ translate('Enter phone number') }}" maxlength="20"
                                            value="{{ $supplier->phone }}" id="phone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="email">{{ \App\CentralLogics\translate('Email') }}</label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="{{ translate('Enter email address') }}" maxlength="255"
                                            value="{{ $supplier->email }}" id="email">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="address">{{ \App\CentralLogics\translate('Address') }}</label>
                                        <textarea name="address" class="form-control" id="address" placeholder="{{ translate('Enter address') }}">{{ $supplier->address }}</textarea>
                                    </div>
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
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush

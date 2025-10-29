@extends('layouts.admin.app')

@section('title', translate('Payment Setup'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/third-party.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('3rd_Party') }}
            </h2>
        </div>

        <div class="inline-page-menu my-4">
            @include('admin-views.business-settings.partial.third-party-nav')
        </div>

        <div class="row gy-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{ \App\CentralLogics\translate('payment') }}
                            {{ \App\CentralLogics\translate('method') }}</h5>
                    </div>
                    <div class="card-body">
                        @php($config = \App\CentralLogics\Helpers::get_business_settings('cash_on_delivery'))
                        <form action="{{ route('admin.business-settings.payment-method-update', ['cash_on_delivery']) }}"
                            method="post">
                            @csrf
                            @if (isset($config))
                                <div class="mb-2">
                                    <label
                                        class="control-label">{{ \App\CentralLogics\translate('cash_on_delivery') }}</label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <input id="active" type="radio" name="status" value="1"
                                        {{ $config['status'] == 1 ? 'checked' : '' }}>
                                    <label for="active"
                                        class="mb-0">{{ \App\CentralLogics\translate('active') }}</label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <input id="inactive" type="radio" name="status" value="0"
                                        {{ $config['status'] == 0 ? 'checked' : '' }}>
                                    <label for="inactive"
                                        class="mb-0">{{ \App\CentralLogics\translate('inactive') }}</label>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn btn-primary">{{ \App\CentralLogics\translate('save') }}</button>
                                </div>
                            @else
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn btn-primary">{{ \App\CentralLogics\translate('configure') }}</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{ \App\CentralLogics\translate('payment') }}
                            {{ \App\CentralLogics\translate('method') }}</h5>
                    </div>
                    <div class="card-body">
                        @php($config = \App\CentralLogics\Helpers::get_business_settings('digital_payment'))
                        <form action="{{ route('admin.business-settings.payment-method-update', ['digital_payment']) }}"
                            method="post">
                            @csrf
                            @if (isset($config))
                                <div class="mb-2">
                                    <label class="control-label">{{ \App\CentralLogics\translate('digital') }}
                                        {{ \App\CentralLogics\translate('payment') }}</label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <input id="active2" type="radio" name="status" value="1"
                                        {{ $config['status'] == 1 ? 'checked' : '' }}>
                                    <label for="active2"
                                        class="mb-0">{{ \App\CentralLogics\translate('active') }}</label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <input id="inactive2" type="radio" name="status" value="0"
                                        {{ $config['status'] == 0 ? 'checked' : '' }}>
                                    <label for="inactive2"
                                        class="mb-0">{{ \App\CentralLogics\translate('inactive') }}</label>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn btn-primary">{{ \App\CentralLogics\translate('save') }}</button>
                                </div>
                            @else
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn btn-primary">{{ \App\CentralLogics\translate('configure') }}</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    @php($config = \App\CentralLogics\Helpers::get_business_settings('chapa'))
                    <form
                        action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.payment-method-update', ['chapa']) : 'javascript:' }}"
                        method="post">
                        @csrf
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="text-uppercase mb-0">{{ \App\CentralLogics\translate('chapa') }}</h5>
                            @if (isset($config))
                                <label class="switcher">
                                    <input class="switcher_input" type="checkbox" name="status"
                                        {{ $config['status'] == 1 ? 'checked' : '' }}>
                                    <span class="switcher_control"></span>
                                </label>
                            @endif
                        </div>
                        <div class="card-body">
                            @if (isset($config))
                                <center class="mb-3">
                                    <img width="180"
                                        src="{{ asset(config('app.asset_path') . '/admin/img/chapa_logo.png') }}"
                                        alt="">
                                </center>
                                <div class="form-group">
                                    <label>{{ \App\CentralLogics\translate('chapa') }}
                                        {{ \App\CentralLogics\translate('client') }}
                                        {{ \App\CentralLogics\translate('id') }}</label>
                                    <input type="text" class="form-control" name="chapa_client_id"
                                        value="{{ env('APP_MODE') != 'demo' ? $config['chapa_client_id'] : '' }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ \App\CentralLogics\translate('chapasecret') }} </label>
                                    <input type="text" class="form-control" name="chapa_secret"
                                        value="{{ env('APP_MODE') != 'demo' ? $config['chapa_secret'] : '' }}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                        onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                        class="btn btn-primary">{{ \App\CentralLogics\translate('save') }}</button>
                                </div>
                            @else
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn btn-primary">{{ \App\CentralLogics\translate('configure') }}</button>
                                </div>
                            @endif
                        </div>
                    </form>


                </div>
            </div>


            <div class="col-md-6">
                <div class="card h-100">
                    @php($config = \App\CentralLogics\Helpers::get_business_settings('paypal'))
                    <form
                        action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.payment-method-update', ['paypal']) : 'javascript:' }}"
                        method="post">
                        @csrf
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="text-uppercase mb-0">{{ \App\CentralLogics\translate('paypal') }}</h5>
                            @if (isset($config))
                                <label class="switcher">
                                    <input class="switcher_input" type="checkbox" name="status"
                                        {{ $config['status'] == 1 ? 'checked' : '' }}>
                                    <span class="switcher_control"></span>
                                </label>
                            @endif
                        </div>
                        <div class="card-body">
                            @if (isset($config))
                                <center class="mb-3">
                                    <img width="180"
                                        src="{{ asset(config('app.asset_path') . '/admin/img/paypal.png') }}"
                                        alt="">
                                </center>
                                <div class="form-group">
                                    <label>{{ \App\CentralLogics\translate('paypal') }}
                                        {{ \App\CentralLogics\translate('client') }}
                                        {{ \App\CentralLogics\translate('id') }}</label>
                                    <input type="text" class="form-control" name="paypal_client_id"
                                        value="{{ env('APP_MODE') != 'demo' ? $config['paypal_client_id'] : '' }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ \App\CentralLogics\translate('paypalsecret') }} </label>
                                    <input type="text" class="form-control" name="paypal_secret"
                                        value="{{ env('APP_MODE') != 'demo' ? $config['paypal_secret'] : '' }}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                        onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                        class="btn btn-primary">{{ \App\CentralLogics\translate('save') }}</button>
                                </div>
                            @else
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn btn-primary">{{ \App\CentralLogics\translate('configure') }}</button>
                                </div>
                            @endif
                        </div>
                    </form>


                </div>
            </div>
            <!--<div class="col-md-6">
                    <div class="card h-100">
                        @php($config = \App\CentralLogics\Helpers::get_business_settings('stripe'))
                        <form action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.payment-method-update', ['stripe']) : 'javascript:' }}" method="post">
                            @csrf
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="text-uppercase mb-0">{{ \App\CentralLogics\translate('stripe') }}</h5>
                                @if (isset($config))
    <label class="switcher">
                                        <input class="switcher_input" type="checkbox" name="status" {{ $config['status'] == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
    @endif
                            </div>

                            <div class="card-body">
                                @if (isset($config))
    <center class="mb-3">
                                        <img width="120" src="{{ asset(config('app.asset_path') . '/admin/img/stripe.png') }}" alt="">
                                    </center>
                                    <div class="form-group">
                                        <label>{{ \App\CentralLogics\translate('published') }} {{ \App\CentralLogics\translate('key') }}</label>
                                        <input type="text" class="form-control" name="published_key"
                                               value="{{ env('APP_MODE') != 'demo' ? $config['published_key'] : '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ \App\CentralLogics\translate('api') }} {{ \App\CentralLogics\translate('key') }}</label>
                                        <input type="text" class="form-control" name="api_key"
                                               value="{{ env('APP_MODE') != 'demo' ? $config['api_key'] : '' }}">
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                                onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('save') }}</button>
                                    </div>
@else
    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{ \App\CentralLogics\translate('configure') }}</button>
                                    </div>
    @endif
                            </div>
                        </form>
                    </div>
                </div>-->

        </div>
    </div>
@endsection

@push('script_2')
@endpush

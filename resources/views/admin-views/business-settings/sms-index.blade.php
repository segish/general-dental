@extends('layouts.admin.app')

@section('title', translate('SMS Module Setup'))

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
                        <h5 class="mb-0">{{ \App\CentralLogics\translate('twilio_sms') }}</h5>
                    </div>
                    <div class="card-body">
                        <span
                            class="badge badge-soft-info  text-wrap mb-3">{{ translate('NB : #OTP# will be replace with otp') }}</span>
                        @php($config = \App\CentralLogics\Helpers::get_business_settings('twilio_sms'))
                        <form
                            action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.sms-module-update', ['twilio_sms']) : 'javascript:' }}"
                            method="post">
                            @csrf

                            <div class="mb-2">
                                <label class="control-label">{{ \App\CentralLogics\translate('twilio_sms') }}</label>
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <input id="status_active" type="radio" name="status" value="1"
                                    {{ isset($config) && $config['status'] == 1 ? 'checked' : '' }}>
                                <label for="status_active"
                                    class="mb-0">{{ \App\CentralLogics\translate('active') }}</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <input id="status_inactive" type="radio" name="status" value="0"
                                    {{ isset($config) && $config['status'] == 0 ? 'checked' : '' }}>
                                <label for="status_inactive" class="mb-0">{{ \App\CentralLogics\translate('inactive') }}
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="text-capitalize">{{ \App\CentralLogics\translate('sid') }}</label>
                                <input type="text" class="form-control" name="sid"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['sid'] ?? '' : '' }}">
                            </div>

                            <div class="form-group">
                                <label
                                    class="text-capitalize">{{ \App\CentralLogics\translate('messaging_service_sid') }}</label>
                                <input type="text" class="form-control" name="messaging_service_sid"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['messaging_service_sid'] ?? '' : '' }}">
                            </div>

                            <div class="form-group">
                                <label>{{ \App\CentralLogics\translate('token') }}</label>
                                <input type="text" class="form-control" name="token"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['token'] ?? '' : '' }}">
                            </div>

                            <div class="form-group">
                                <label>{{ \App\CentralLogics\translate('from') }}</label>
                                <input type="text" class="form-control" name="from"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['from'] ?? '' : '' }}">
                            </div>

                            <div class="form-group">
                                <label>{{ \App\CentralLogics\translate('otp_template') }}</label>
                                <input type="text" class="form-control" name="otp_template"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['otp_template'] ?? '' : '' }}">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                    onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                    class="btn btn-primary">{{ \App\CentralLogics\translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{ \App\CentralLogics\translate('yegara_sms') }}</h5>
                    </div>
                    <div class="card-body">
                        @php($config = \App\CentralLogics\Helpers::get_business_settings('yegara_sms'))
                        <form
                            action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.sms-module-update', ['yegara_sms']) : 'javascript:' }}"
                            method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{ \App\CentralLogics\translate('yegara_sms') }}</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <input id="status_active2" type="radio" name="status" value="1"
                                    {{ isset($config) && $config['status'] == 1 ? 'checked' : '' }}>
                                <label for="status_active2"
                                    class="mb-0">{{ \App\CentralLogics\translate('active') }}</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <input id="status_inactive2" type="radio" name="status" value="0"
                                    {{ isset($config) && $config['status'] == 0 ? 'checked' : '' }}>
                                <label for="status_inactive2" class="mb-0">{{ \App\CentralLogics\translate('inactive') }}
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize">{{ \App\CentralLogics\translate('server') }}</label><br>
                                <input type="text" class="form-control" name="server"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['server'] ?? '' : '' }}">
                            </div>
                            <div class="form-group">
                                <label>{{ \App\CentralLogics\translate('username') }}</label><br>
                                <input type="text" class="form-control" name="username"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['username'] ?? '' : '' }}">
                            </div>

                            <div class="form-group">
                                <label>{{ \App\CentralLogics\translate('password') }}</label><br>
                                <input type="text" class="form-control" name="password"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['password'] ?? '' : '' }}">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                    onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                    class="btn btn-primary mb-2">{{ \App\CentralLogics\translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{ \App\CentralLogics\translate('2factor_sms') }}</h5>
                    </div>
                    <div class="card-body">
                        <span class="badge badge-soft-info  text-wrap">
                            {{ translate("EX of SMS provider's template : your OTP is XXXX here, please check.") }} </span>
                        <span class="badge badge-soft-info  text-wrap mb-3">
                            {{ translate('NB : XXXX will be replace with otp') }}</span>
                        @php($config = \App\CentralLogics\Helpers::get_business_settings('2factor_sms'))
                        <form
                            action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.sms-module-update', ['2factor_sms']) : 'javascript:' }}"
                            method="post">
                            @csrf

                            <div class="mb-2">
                                <label class="control-label">{{ \App\CentralLogics\translate('2factor_sms') }}</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <input id="status_active3" type="radio" name="status" value="1"
                                    {{ isset($config) && $config['status'] == 1 ? 'checked' : '' }}>
                                <label for="status_active3" class="mb-0">{{ \App\CentralLogics\translate('active') }}</label>
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-4">
                                <input id="status_inactive3" type="radio" name="status" value="0"
                                    {{ isset($config) && $config['status'] == 0 ? 'checked' : '' }}>
                                <label for="status_inactive3" class="mb-0">{{ \App\CentralLogics\translate('inactive') }}
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize">{{ \App\CentralLogics\translate('api_key') }}</label>
                                <input type="text" class="form-control" name="api_key"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['api_key'] ?? '' : '' }}">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                    onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                    class="btn btn-primary">{{ \App\CentralLogics\translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{ \App\CentralLogics\translate('msg91_sms') }}</h5>
                    </div>
                    <div class="card-body">
                        <span
                            class="badge badge-soft-info text-wrap mb-3">{{ translate('NB : Keep an OTP variable in your SMS providers OTP Template.') }}</span><br>
                        @php($config = \App\CentralLogics\Helpers::get_business_settings('msg91_sms'))
                        <form
                            action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.sms-module-update', ['msg91_sms']) : 'javascript:' }}"
                            method="post">
                            @csrf

                            <div class="mb-2">
                                <label class="control-label">{{ \App\CentralLogics\translate('msg91_sms') }}</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <input id="status_active4" type="radio" name="status" value="1"
                                    {{ isset($config) && $config['status'] == 1 ? 'checked' : '' }}>
                                <label for="status_active4" class="mb-0">{{ \App\CentralLogics\translate('active') }}</label>
                                <br>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <input id="status_inactive4" type="radio" name="status" value="0"
                                    {{ isset($config) && $config['status'] == 0 ? 'checked' : '' }}>
                                <label for="status_inactive4" class="mb-0">{{ \App\CentralLogics\translate('inactive') }}
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize">{{ \App\CentralLogics\translate('template_id') }}</label><br>
                                <input type="text" class="form-control" name="template_id"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['template_id'] ?? '' : '' }}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize">{{ \App\CentralLogics\translate('authkey') }}</label><br>
                                <input type="text" class="form-control" name="authkey"
                                    value="{{ env('APP_MODE') != 'demo' ? $config['authkey'] ?? '' : '' }}">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                    onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                    class="btn btn-primary">{{ \App\CentralLogics\translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
@endsection

@push('script_2')
@endpush

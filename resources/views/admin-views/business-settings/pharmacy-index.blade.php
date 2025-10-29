@extends('layouts.admin.app')

@section('title', translate('pharmacy_company_setup'))

@section('content')
    <div class="content container-fluid">
        <div class="alert alert-warning sticky-top" id="alert_box" style="display:none">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <strong>Warning!</strong> {{ \App\CentralLogics\translate('language_warning') }} For documentaion <a
                href="https://documentation.6amtech.com/emarket/docs/1.0/app-setup#section3" target="_blank">click
                here</a>.
        </div>

        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/business-setup.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('pharmacy_company_Setup') }}
            </h2>
        </div>

        <div class="inline-page-menu mb-4">
            {{-- Assuming you might adjust this navigation partial for pharmacy settings --}}
            @include('admin-views.business-settings.partial.business-setup-nav')
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h4 class="d-flex align-items-center gap-2 mb-0">
                    <i class="tio-settings"></i>
                    {{ translate('General settings form') }}
                </h4>
            </div>
            <div class="card-body">
                {{-- Ensure this route handles the new keys and table: pharmacy_company_setting --}}
                <form action="{{ route('admin.pharmacy-company-setting.update-setup') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        {{-- **company_name** --}}
                        @php($company_name = \App\Models\PharmacyCompanySetting::where('key', 'company_name')->first()->value ?? '')
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label"
                                    for="company_name">{{ \App\CentralLogics\translate('Company Name') }}</label>
                                <input type="text" name="company_name" value="{{ $company_name }}"
                                    class="form-control" placeholder="{{ translate('Company Name') }}" required>
                            </div>
                        </div>

                        {{-- **logo** (Updated to show current logo and input) --}}
                        @php($logo = \App\Models\PharmacyCompanySetting::where('key', 'logo')->first()->value ?? 'default_logo.png')
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label" for="logo">{{ \App\CentralLogics\translate('Company Logo') }}</label>
                                <input type="file" name="logo" id="logo" class="form-control" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <small class="text-info">{{ translate('Preferred ratio is 4:1') }}</small>
                                <div class="text-center mt-3">
                                    <img style="max-height: 100px; border: 1px solid #ddd; border-radius: 5px;"
                                        onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}'"
                                        src="{{ asset(config('custom.upload_asset_path') . '/' . $logo) }}"
                                        alt="logo image" />
                                </div>
                            </div>
                        </div>

                        {{-- **phone** --}}
                        @php($phone = \App\Models\PharmacyCompanySetting::where('key', 'phone')->first()->value ?? '')
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label"
                                    for="phone">{{ \App\CentralLogics\translate('phone') }}</label>
                                <input type="text" value="{{ $phone }}" name="phone" class="form-control"
                                    placeholder="{{ translate('Company Phone Number') }}" required>
                            </div>
                        </div>

                        {{-- **vat_reg_no** --}}
                        @php($vat_reg_no = \App\Models\PharmacyCompanySetting::where('key', 'vat_reg_no')->first()->value ?? '')
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label"
                                    for="vat_reg_no">{{ \App\CentralLogics\translate('VAT Registration Number') }}</label>
                                <input type="text" value="{{ $vat_reg_no }}" name="vat_reg_no" class="form-control"
                                    placeholder="{{ translate('e.g., 123456789') }}">
                            </div>
                        </div>

                        {{-- **tin_no** --}}
                        @php($tin_no = \App\Models\PharmacyCompanySetting::where('key', 'tin_no')->first()->value ?? '')
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label"
                                    for="tin_no">{{ \App\CentralLogics\translate('TIN Number') }}</label>
                                <input type="text" value="{{ $tin_no }}" name="tin_no" class="form-control"
                                    placeholder="{{ translate('e.g., 987654321') }}">
                            </div>
                        </div>

                        {{-- **address** --}}
                        @php($address = \App\Models\PharmacyCompanySetting::where('key', 'address')->first()->value ?? '')
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label"
                                    for="address">{{ \App\CentralLogics\translate('address') }}</label>
                                <input type="text" value="{{ $address }}" name="address" class="form-control"
                                    placeholder="{{ translate('Company Address') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn-secondary">{{ translate('reset') }}</button>
                        <button type="submit" class="btn btn-primary">{{ translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    {{-- Add any necessary scripts here --}}
@endpush

@extends('layouts.admin.app')

@section('title', translate('FCM Settings'))

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

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">{{ translate('Firebase Push Notification Setup') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.business-settings.update-fcm') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @php($key = \App\Models\BusinessSetting::where('key', 'push_notification_key')->first()->value)
                    <div class="form-group">
                        <label class="input-label">{{ translate('server') }} {{ translate('key') }}</label>
                        <textarea name="push_notification_key" class="form-control" required>{{ env('APP_MODE') == 'demo' ? '' : $key }}</textarea>
                    </div>

                    <div class="row" style="display: none">
                        @php($project_id = \App\Models\BusinessSetting::where('key', 'fcm_project_id')->first()->value)
                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label class="input-label">FCM Project ID</label>
                                <input type="text" value="{{ $project_id }}" name="fcm_project_id"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    @if (env('APP_MODE') == 'demo')
                        <div class="d-flex justify-content-end">
                            <button type="button" onclick="call_demo()" class="btn btn-primary">{{ translate('save') }}
                            </button>
                        </div>
                    @else
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mb-2">{{ translate('save') }}</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>


        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ translate('push') }} {{ translate('messages') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.business-settings.update-fcm-messages') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        @php($opm = \App\Models\BusinessSetting::where('key', 'order_pending_message')->first()->value)
                        @php($data = json_decode($opm, true))
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="switcher" for="pending_status">
                                        <input type="checkbox" name="pending_status" class="switcher_input" value="1"
                                            id="pending_status" {{ $data['status'] == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                    <label for="pending_status"
                                        class="text-dark mb-0 cursor-pointer">{{ translate('order') }}
                                        {{ translate('pending') }} {{ translate('message') }}</label>
                                </div>
                                <textarea name="pending_message" class="form-control">{{ $data['message'] }}</textarea>
                            </div>
                        </div>

                        @php($ocm = \App\Models\BusinessSetting::where('key', 'order_confirmation_msg')->first()->value)
                        @php($data = json_decode($ocm, true))
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="switcher" for="confirm_status">
                                        <input type="checkbox" name="confirm_status" class="switcher_input" value="1"
                                            id="confirm_status" {{ $data['status'] == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                    <label for="confirm_status"
                                        class="text-dark mb-0 cursor-pointer">{{ translate('order') }}
                                        {{ translate('confirmation') }} {{ translate('message') }}</label>
                                </div>

                                <textarea name="confirm_message" class="form-control">{{ $data['message'] }}</textarea>
                            </div>
                        </div>

                        @php($oprm = \App\Models\BusinessSetting::where('key', 'order_processing_message')->first()->value)
                        @php($data = json_decode($oprm, true))
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="switcher" for="processing_status">
                                        <input type="checkbox" name="processing_status" class="switcher_input"
                                            value="1" id="processing_status" {{ $data['status'] == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                    <label for="processing_status"
                                        class="text-dark mb-0 cursor-pointer">{{ translate('order') }}
                                        {{ translate('processing') }} {{ translate('message') }}</label>
                                </div>

                                <textarea name="processing_message" class="form-control">{{ $data['message'] }}</textarea>
                            </div>
                        </div>

                        @php($ofdm = \App\Models\BusinessSetting::where('key', 'out_for_delivery_message')->first()->value)
                        @php($data = json_decode($ofdm, true))
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="switcher" for="out_for_delivery">
                                        <input type="checkbox" name="out_for_delivery_status" class="switcher_input"
                                            value="1" id="out_for_delivery" {{ $data['status'] == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                    <label for="out_for_delivery"
                                        class="text-dark mb-0 cursor-pointer">{{ translate('Order_Out_for_delivery_Message') }}</label>
                                </div>
                                <textarea name="out_for_delivery_message" class="form-control">{{ $data['message'] }}</textarea>
                            </div>
                        </div>

                        @php($odm = \App\Models\BusinessSetting::where('key', 'order_delivered_message')->first()->value)
                        @php($data = json_decode($odm, true))
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="switcher" for="delivered_status">
                                        <input type="checkbox" name="delivered_status" class="switcher_input"
                                            value="1" id="delivered_status" {{ $data['status'] == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                    <label for="delivered_status"
                                        class="text-dark mb-0 cursor-pointer">{{ translate('Order_Delivered_Message') }}</label>
                                </div>

                                <textarea name="delivered_message" class="form-control">{{ $data['message'] }}</textarea>
                            </div>
                        </div>



                        @php($data = \App\CentralLogics\Helpers::get_business_settings('returned_message'))
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="switcher" for="returned_status">
                                        <input type="checkbox" name="returned_status" class="switcher_input"
                                            value="1" id="returned_status"
                                            {{ isset($data['status']) && $data['status'] == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                    <label for="returned_status"
                                        class="text-dark mb-0 cursor-pointer">{{ translate('Order_returned_message') }}</label>
                                </div>
                                <textarea name="returned_message" class="form-control">{{ $data['message'] ?? '' }}</textarea>
                            </div>
                        </div>

                        @php($data = \App\CentralLogics\Helpers::get_business_settings('failed_message'))
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="switcher" for="failed_status">
                                        <input type="checkbox" name="failed_status" class="switcher_input"
                                            value="1" id="failed_status"
                                            {{ isset($data['status']) && $data['status'] == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                    <label for="failed_status"
                                        class="text-dark mb-0 cursor-pointer">{{ translate('Order_failed_message') }}</label>
                                </div>

                                <textarea name="failed_message" class="form-control">{{ $data['message'] ?? '' }}</textarea>
                            </div>
                        </div>

                        @php($data = \App\CentralLogics\Helpers::get_business_settings('canceled_message'))
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="switcher" for="canceled_status">
                                        <input type="checkbox" name="canceled_status" class="switcher_input"
                                            value="1" id="canceled_status"
                                            {{ isset($data['status']) && $data['status'] == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                    <label for="canceled_status"
                                        class="text-dark mb-0 cursor-pointer">{{ translate('Order_canceled_message') }}</label>
                                </div>

                                <textarea name="canceled_message" class="form-control">{{ $data['message'] ?? '' }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('save') }}</button>
                    </div>
                </form>
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
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
        });
    </script>
@endpush

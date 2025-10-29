<footer class="footer">
    <div class="row justify-content-between align-items-center gy-2">
        <div class="col-lg-4">
            <p class="text-capitalize text-center text-lg-left mb-0">
                {{\App\Models\BusinessSetting::where(['key'=>'footer_text'])->first()->value}}
            </p>
        </div>
        <div class="col-lg-8">
            <div class="d-flex justify-content-center justify-content-lg-end">
                <!-- List Dot -->
                <ul class="list-inline-menu justify-content-center">
                    <li>
                       @haspermission('business-settings.ecom-setup','admin')
                        <a href="{{route('admin.business-settings.ecom-setup')}}">
                            <span>{{\App\CentralLogics\translate('Business')}} {{\App\CentralLogics\translate('setup')}}</span>
                            <i class="tio-settings"></i>
                        </a>
                        @endhaspermission
                    </li>

                    <li>
                        <a href="{{route('admin.settings')}}">
                            <span>{{\App\CentralLogics\translate('profile')}}</span>
                            <i class="tio-user"></i>
                        </a>
                    </li>

                    <li>
                        @haspermission('system.dashboard','admin')
                        <a href="{{route('admin.dashboard')}}">
                            <span>{{\App\CentralLogics\translate('Home')}}</span>
                            <i class="tio-home-outlined"></i>
                        </a>
                        @endhaspermission
                    </li>
                    
                </ul>
                <!-- End List Dot -->
            </div>
        </div>
    </div>
</footer>

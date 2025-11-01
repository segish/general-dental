<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container text-capitalize">
            <div class="navbar-vertical-footer-offset">
                <div class="d-flex align-items-center gap-3 py-2 px-3 justify-content-between">
                    <!-- Logo -->
                    @php($restaurant_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first()->value)
                    <div class="navbar-brand w-75" aria-label="Front">
                        <img class="navbar-brand-logo"
                            onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img2.jpg') }}'"
                            src="{{ asset(config('custom.upload_asset_path') . '/' . $restaurant_logo) }}"
                            alt="Logo">
                        <img class="navbar-brand-logo-mini"
                            onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img2.jpg') }}'"
                            src="{{ asset(config('custom.upload_asset_path') . '/' . $restaurant_logo) }}"
                            alt="Logo">
                    </div>
                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mt-1">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align" title="Expand"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->

                </div>

                <!-- Content -->
                <div class="navbar-vertical-content">
                    <div class="sidebar--search-form py-3">
                        <div class="search--form-group">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="js-form-search form-control form--control"
                                id="search-bar-input" placeholder="Search Menu...">
                        </div>
                    </div>

                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        @if (auth('admin')->user()->hasRole('Super Admin'))
                            <!--Super Admin Dashboards -->
                        @endif
                        @haspermission('dashboard', 'admin')
                            <li class="navbar-vertical-aside-has-menu {{ Request::is('admin') ? 'show' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.dashboard') }}"
                                    title="{{ translate('Dashboards') }}">
                                    <i class="tio-home-vs-1-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{ \App\CentralLogics\translate('dashboard') }}
                                    </span>
                                </a>
                            </li>
                        @endhaspermission
                        {{-- Invoice Management --}}
                        @if (auth('admin')->user()->can('invoice.list') || auth('admin')->user()->can('invoice.payment-list'))
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/invoice*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <i class="tio-document nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('invoices') }}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/invoice*') ? 'block' : 'none' }}">

                                    @haspermission('invoice.list', 'admin')
                                        <li class="nav-item {{ Request::is('admin/invoice/list') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.invoice.list') }}"
                                                title="{{ translate('invoice list') }}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span
                                                    class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                            </a>
                                        </li>
                                    @endhaspermission
                                    @haspermission('invoice.payment-list', 'admin')
                                        <li
                                            class="nav-item {{ Request::is('admin/invoice/payment-list') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.invoice.payment-list') }}"
                                                title="{{ translate('Payment list') }}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span
                                                    class="text-truncate">{{ \App\CentralLogics\translate('Payment list') }}</span>
                                            </a>
                                        </li>
                                    @endhaspermission

                                </ul>
                            </li>

                        @endif

                        {{-- Report Management --}}
                        @if (auth('admin')->user()->can('reports.test') ||
                                auth('admin')->user()->can('reports.revenue') ||
                                auth('admin')->user()->can('reports.patients') ||
                                auth('admin')->user()->can('reports.disease'))
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/reports*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <i class="tio-chart-bar-1 nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Reports') }}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/reports*') ? 'block' : 'none' }}">

                                    @haspermission('reports.test', 'admin')
                                        <li class="nav-item {{ Request::is('admin/reports/test') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.reports.test') }}"
                                                title="{{ translate('Test Report') }}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span
                                                    class="text-truncate">{{ \App\CentralLogics\translate('Test Report') }}</span>
                                            </a>
                                        </li>
                                    @endhaspermission
                                    @haspermission('reports.revenue', 'admin')
                                        <li class="nav-item {{ Request::is('admin/reports/revenue') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.reports.revenue') }}"
                                                title="{{ translate('Revenue Report') }}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span
                                                    class="text-truncate">{{ \App\CentralLogics\translate('Revenue Report') }}</span>
                                            </a>
                                        </li>
                                    @endhaspermission
                                    @haspermission('reports.patients', 'admin')
                                        <li class="nav-item {{ Request::is('admin/reports/patients') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.reports.patients') }}"
                                                title="{{ translate('Patients Report') }}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span
                                                    class="text-truncate">{{ \App\CentralLogics\translate('Patients Report') }}</span>
                                            </a>
                                        </li>
                                    @endhaspermission
                                    @haspermission('reports.disease', 'admin')
                                        <li class="nav-item {{ Request::is('admin/reports/specimens') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.reports.disease') }}"
                                                title="{{ translate('Specimens Report') }}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span
                                                    class="text-truncate">{{ \App\CentralLogics\translate('Disease Report') }}</span>
                                            </a>
                                        </li>
                                    @endhaspermission
                                </ul>
                            </li>
                        @endif

                        {{-- Appointment Management --}}
                        @if (auth('admin')->user()->can('appointment_schedule.add-new') || auth('admin')->user()->can('appointment.list'))
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/appointment*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <i class="tio-calendar nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('appointments') }}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/appointment*') ? 'block' : 'none' }}">

                                    @haspermission('appointment_schedule.add-new', 'admin')
                                        @if (auth('admin')->check() && auth('admin')->user()->can('appointment_schedule.add-new'))
                                            <li
                                                class="nav-item {{ Request::is('admin/' . auth('admin')->user()->id . '/appointment-schedule/add-new') ? 'active' : '' }}">
                                                <a class="nav-link"
                                                    href="{{ route('admin.appointment_schedule.add-new', ['doctor_id' => auth('admin')->user()->id]) }}"
                                                    title="{{ translate('add new appointment') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('schedule') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endhaspermission


                                    @haspermission('appointment.list', 'admin')
                                        <li class="nav-item {{ Request::is('admin/appointment/list') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.appointment.list') }}"
                                                title="{{ translate('appointment list') }}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span
                                                    class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                            </a>
                                        </li>
                                    @endhaspermission
                                </ul>

                            </li>
                        @endif

                        @if (auth('admin')->user()->can('medical_condition.add-new') ||
                                auth('admin')->user()->can('medical_condition.list') ||
                                'condition_category.add-new' ||
                                auth('admin')->user()->can('condition_category.list'))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('condition_management') }}">{{ \App\CentralLogics\translate('condition_management') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('condition_category.add-new') || auth('admin')->user()->can('condition_category.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/condition_category*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-top-security-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('condition_categorys') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/condition_category*') ? 'block' : 'none' }}">

                                        @haspermission('condition_category.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/condition_category/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.condition_category.add-new') }}"
                                                    title="{{ translate('add new condition_category') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('condition_category.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/condition_category/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.condition_category.list') }}"
                                                    title="{{ translate('condition_category list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('medical_condition.add-new') || auth('admin')->user()->can('medical_condition.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/medical_condition*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-top-security-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('medical_conditions') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/medical_condition*') ? 'block' : 'none' }}">

                                        @haspermission('medical_condition.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medical_condition/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.medical_condition.add-new') }}"
                                                    title="{{ translate('add new medical_condition') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('medical_condition.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medical_condition/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.medical_condition.list') }}"
                                                    title="{{ translate('medical_condition list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('medical_record_field.add-new') || auth('admin')->user()->can('medical_record_field.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/medical_record_field*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-top-security-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('medical_record_fields') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/medical_record_field*') ? 'block' : 'none' }}">

                                        @haspermission('medical_record_field.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medical_record_field/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.medical_record_field.add-new') }}"
                                                    title="{{ translate('add new medical_record_field') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('medical_record_field.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medical_record_field/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.medical_record_field.list') }}"
                                                    title="{{ translate('medical_record_field list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                        @endif
                        {{-- Sercice Management --}}
                        @if (auth('admin')->user()->can('service.add-new') ||
                                auth('admin')->user()->can('service.list') ||
                                auth('admin')->user()->can('service_category.add-new') ||
                                auth('admin')->user()->can('service_category.list') ||
                                auth('admin')->user()->can('bed.add-new') ||
                                auth('admin')->user()->can('bed.list') ||
                                auth('admin')->user()->can('ward.add-new') ||
                                auth('admin')->user()->can('ward.list'))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('service_management') }}">{{ \App\CentralLogics\translate('service_management') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            @if (auth('admin')->user()->can('service.add-new') || auth('admin')->user()->can('service.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/service/*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-pregnancy nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('billing_Services') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/service*') ? 'block' : 'none' }}">
                                        @haspermission('service.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/service/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.service.add-new') }}"
                                                    title="{{ translate('add new service') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('service.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/service/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.service.list') }}"
                                                    title="{{ translate('service list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('service_category.add-new') || auth('admin')->user()->can('service_category.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/service-category*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-pregnancy nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Service Category') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/service-category*') ? 'block' : 'none' }}">
                                        @haspermission('service_category.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/service-category/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.service_category.add-new') }}"
                                                    title="{{ translate('add new service category') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('service_category.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/service-category/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.service_category.list') }}"
                                                    title="{{ translate('service category list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif
                            {{-- Bed and Ward Management --}}
                            @if (auth('admin')->user()->can('ward.add-new') || auth('admin')->user()->can('ward.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/ward*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-pregnancy nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Ward') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/ward*') ? 'block' : 'none' }}">
                                        @haspermission('ward.add-new', 'admin')
                                            <li class="nav-item {{ Request::is('admin/ward/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.ward.add-new') }}"
                                                    title="{{ translate('ward') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('ward.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/ward/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.ward.list') }}"
                                                    title="{{ translate('ward') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('bed.add-new') || auth('admin')->user()->can('bed.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/bed*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-pregnancy nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Bed') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/bed*') ? 'block' : 'none' }}">
                                        @haspermission('bed.add-new', 'admin')
                                            <li class="nav-item {{ Request::is('admin/bed/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.bed.add-new') }}"
                                                    title="{{ translate('bed') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('bed.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/bed/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.bed.list') }}"
                                                    title="{{ translate('bed') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                        @endif
                        {{-- Patient Management --}}
                        @if (auth('admin')->user()->can('patient.add-new') ||
                                auth('admin')->user()->can('patient.list') ||
                                auth('admin')->user()->can('visit.add-new') ||
                                auth('admin')->user()->can('visit.list') ||
                                auth('admin')->user()->can('ipd_patient.list') ||
                                auth('admin')->user()->can('opd_patient.list'))

                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('patient_management') }}">{{ \App\CentralLogics\translate('patient_management') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('patient.add-new') || auth('admin')->user()->can('patient.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/patient/view') ? '' : (Request::is('admin/patient*') ? 'active' : '') }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('patients') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/patient/view*') ? 'none' : (Request::is('admin/patient*') ? 'block' : 'none') }}">

                                        @haspermission('patient.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/patient/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.patient.add-new') }}"
                                                    title="{{ translate('add new patient') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('patient.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/patient/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.patient.list') }}"
                                                    title="{{ translate('patient list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('visit.add-new') || auth('admin')->user()->can('visit.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/visit*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('visits') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/visit*') ? 'block' : 'none' }}">

                                        @haspermission('visit.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/visit/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.visit.add-new', ['active' => 'add-visit']) }}"
                                                    title="{{ translate('add new visit') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('Add Visit & Invoice') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('visit.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/visit/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.visit.list') }}"
                                                    title="{{ translate('visit list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            {{-- @if (auth('admin')->user()->can('ipd_patient.add-new') || auth('admin')->user()->can('ipd_patient.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/ipd-patient*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('IPD Patients') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/ipd-patient*') ? 'block' : 'none' }}">

                                        @haspermission('ipd_patient.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/ipd-patient/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.ipd_patient.add-new') }}"
                                                    title="{{ translate('add new ipd patient') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('ipd_patient.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/ipd-patient/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.ipd_patient.list') }}"
                                                    title="{{ translate('ipd patient list') }}">
                                                    <span
                                                        class="tio-circle
                                                        nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('opd_patient.add-new') || auth('admin')->user()->can('opd_patient.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/opd-patient*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('OPD Patients') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/opd-patient*') ? 'block' : 'none' }}">

                                        @haspermission('opd_patient.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/opd-patient/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.opd_patient.add-new') }}"
                                                    title="{{ translate('add new opd patient') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('opd_patient.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/opd-patient/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.opd_patient.list') }}"
                                                    title="{{ translate('opd patient list') }}">
                                                    <span
                                                        class="tio-circle
                                                        nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif --}}
                        @endif

                        {{-- Labratory Management --}}
                        @if (auth('admin')->user()->can('laboratory_request.list') ||
                                auth('admin')->user()->can('specimen_origin.add-new') ||
                                auth('admin')->user()->can('specimen_origin.list') ||
                                auth('admin')->user()->can('specimen.list') ||
                                auth('admin')->user()->can('test.add-new') ||
                                auth('admin')->user()->can('test.list') ||
                                auth('admin')->user()->can('test_category.add-new') ||
                                auth('admin')->user()->can('test_category.list') ||
                                auth('admin')->user()->can('specimen_type.add-new') ||
                                auth('admin')->user()->can('specimen_type.list') ||
                                auth('admin')->user()->can('test_attribute.add-new') ||
                                auth('admin')->user()->can('test_attribute.list') ||
                                auth('admin')->user()->can('attribute_option.add-new') ||
                                auth('admin')->user()->can('attribute_option.list'))

                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('TEST & SAMPLE MANAGEMENT') }}">{{ \App\CentralLogics\translate('TEST & SAMPLE MANAGEMENT') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('assessment-categories.add-new') ||
                                    auth('admin')->user()->can('assessment-categories.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/assessment-categories*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-wishlist-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Vital Sign Categories') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/assessment-categories*') ? 'block' : 'none' }}">

                                        @haspermission('assessment-categories.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/assessment-categories/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.assessment-categories.add-new') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('Add New') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('assessment-categories.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/assessment-categories/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.assessment-categories.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('laboratory_request.add-new') || auth('admin')->user()->can('laboratory_request.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/laboratory-request*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-wishlist-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Test Request') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/laboratory-request*') ? 'block' : 'none' }}">

                                        @haspermission('laboratory_request.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/laboratory-request/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.laboratory_request.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('test.add-new') || auth('admin')->user()->can('test.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is(['admin/test/add-new', 'admin/test/list']) ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-test-tube nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Test Type') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/test*') ? 'block' : 'none' }}">

                                        @haspermission('test.add-new', 'admin')
                                            <li class="nav-item {{ Request::is('admin/test/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.test.add-new') }}"
                                                    title="{{ translate('Add New Sample Type') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('test.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/test/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.test.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('test_category.add-new') || auth('admin')->user()->can('test_category.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/test-category*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-category nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Test Category') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/test-category*') ? 'block' : 'none' }}">

                                        @haspermission('test_category.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/test-category/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.test_category.add-new') }}"
                                                    title="{{ translate('Add New Sample Type') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('test_category.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/test-category/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.test_category.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('specimen.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/specimen*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-category nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Specimens') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/specimen*') ? 'block' : 'none' }}">
                                        @haspermission('specimen.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/test-category/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.specimen.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('specimen_type.add-new') || auth('admin')->user()->can('specimen_type.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/specimen-type*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-flask nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Specimen Type') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/specimen-type*') ? 'block' : 'none' }}">

                                        @haspermission('specimen_type.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/specimen-type/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.specimen_type.add-new') }}"
                                                    title="{{ translate('Add New Sample Type') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('specimen_type.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/specimen-type/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.specimen_type.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('specimen_origin.add-new') || auth('admin')->user()->can('specimen_origin.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/specimen-origin*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-heart-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Specimen Source') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/specimen-origin*') ? 'block' : 'none' }}">

                                        @haspermission('specimen_origin.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/specimen-origin/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.specimen_origin.add-new') }}"
                                                    title="{{ translate('Add New Sample Type') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('specimen_origin.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/specimen-origin/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.specimen_origin.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('test_attribute.add-new') || auth('admin')->user()->can('test_attribute.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/test-attribute*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-protection nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Test Attribute') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/test-attribute*') ? 'block' : 'none' }}">

                                        @haspermission('test_attribute.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/test-attribute/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.test_attribute.add-new') }}"
                                                    title="{{ translate('Add New Sample Type') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('test_attribute.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/test-attribute/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.test_attribute.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('attribute_option.add-new') || auth('admin')->user()->can('attribute_option.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/attribute-option*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-selection nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Attribute Option') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/attribute-option*') ? 'block' : 'none' }}">

                                        @haspermission('attribute_option.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/attribute-option/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.attribute_option.add-new') }}"
                                                    title="{{ translate('Add New Sample Type') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('attribute_option.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/attribute-option/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.attribute_option.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                        @endif


                        {{-- Radiology Management --}}
                        @if (auth('admin')->user()->can('radiology_request.list') ||
                                auth('admin')->user()->can('radiology.add-new') ||
                                auth('admin')->user()->can('radiology.list') ||
                                auth('admin')->user()->can('radiology_attribute.add-new') ||
                                auth('admin')->user()->can('radiology_attribute.list'))

                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('RADIOLOGY MANAGEMENT') }}">{{ \App\CentralLogics\translate('RADIOLOGY MANAGEMENT') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('radiology_request.add-new') || auth('admin')->user()->can('radiology_request.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/radiology-request*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-wishlist-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Radiology Request') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/radiology-request*') ? 'block' : 'none' }}">

                                        @haspermission('radiology_request.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/radiology-request/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.radiology_request.list') }}"
                                                    title="{{ translate('Labratory list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('radiology.add-new') || auth('admin')->user()->can('radiology.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is(['admin/radiology/add-new', 'admin/radiology/list']) ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-test-tube nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Radiology Type') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/radiology*') ? 'block' : 'none' }}">

                                        @haspermission('radiology.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/radiology/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.radiology.add-new') }}"
                                                    title="{{ translate('Add New Radiology Type') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('radiology.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/radiology/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.radiology.list') }}"
                                                    title="{{ translate('Radiology list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('radiology_attribute.add-new') || auth('admin')->user()->can('radiology_attribute.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/radiology-attribute*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-protection nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Radiology Attribute') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/radiology-attribute*') ? 'block' : 'none' }}">

                                        @haspermission('radiology_attribute.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/radiology-attribute/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.radiology_attribute.add-new') }}"
                                                    title="{{ translate('Add New Radiology Attribute') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('radiology_attribute.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/radiology-attribute/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.radiology_attribute.list') }}"
                                                    title="{{ translate('Radiology Attribute list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                        @endif

                        @php($has_pharmacy = \App\Models\BusinessSetting::where('key', 'has_pharmacy')->first()?->value ?? '0')

                        {{-- Pharmacy Management --}}
                        @if (auth('admin')->user()->can('medicines.add-new') ||
                                auth('admin')->user()->can('medicines.list') ||
                                auth('admin')->user()->can('medicine_categories.add-new') ||
                                auth('admin')->user()->can('pharmacy-company-setting.view') ||
                                auth('admin')->user()->can('medicine_categories.list'))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('Medicine Management') }}">{{ \App\CentralLogics\translate('Medicine Management') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('medicines.add-new') || auth('admin')->user()->can('medicines.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/medicines*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Medicines') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/medicines*') ? 'block' : 'none' }}">

                                        @haspermission('medicines.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medicines/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.medicines.add-new') }}"
                                                    title="{{ translate('add new medicine') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('medicines.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medicines/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.medicines.list') }}"
                                                    title="{{ translate('medicines list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('medicine_categories.add-new') || auth('admin')->user()->can('medicine_categories.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/medicine-categories*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Medicine Categories') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/medicine-categories*') ? 'block' : 'none' }}">

                                        @haspermission('medicine_categories.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medicine-categories/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.medicine_categories.add-new') }}"
                                                    title="{{ translate('add new medicine category') }}">
                                                    <span
                                                        class="tio-circle
                                                        nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('medicine_categories.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medicine-categories/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.medicine_categories.list') }}"
                                                    title="{{ translate('medicine category list') }}">
                                                    <span
                                                        class="tio-circle
                                                        nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif
                        @endif
                        @if (
                            $has_pharmacy == '1' &&
                                (auth('admin')->user()->can('pharmacy_inventory.add-new') ||
                                    auth('admin')->user()->can('pharmacy_inventory.list') ||
                                    auth('admin')->user()->can('sales.add-new') ||
                                    auth('admin')->user()->can('sales.list') ||
                                    auth('admin')->user()->can('pharmacy_stock_adjustments.add-new') ||
                                    auth('admin')->user()->can('pharmacy_stock_adjustments.list')))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('Pharmacy Management') }}">{{ \App\CentralLogics\translate('Pharmacy Management') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            @haspermission('pharmacy-company-setting.view', 'admin')
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/ecom-setup') || Request::is('admin/business-settings/otp-setup') || Request::is('admin/business-settings/cookies-setup') || Request::is('admin/business-settings/delivery-fee-setup') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.pharmacy-company-setting.view') }}">
                                        <i class="tio-settings nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Pharmacy Setup') }}</span>
                                    </a>
                                </li>
                            @endhaspermission
                            @if (auth('admin')->user()->can('products.add-new') ||
                                    auth('admin')->user()->can('products.list') ||
                                    auth('admin')->user()->can('pharmacy-reports.product-performance'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/products*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('products') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/products*') ? 'block' : 'none' }}">

                                        @haspermission('products.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/products/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.products.add-new') }}"
                                                    title="{{ translate('add new medicine') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('products.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/products/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.products.list') }}"
                                                    title="{{ translate('Products list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('pharmacy-reports.product-performance', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-reports/product-performance') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.pharmacy-reports.product-performance') }}"
                                                    title="{{ translate('product report') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('product report') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('pharmacy_inventory.add-new') || auth('admin')->user()->can('pharmacy_inventory.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/pharmacy-inventory*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Pharmacy Inventory') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/pharmacy-inventory*') ? 'block' : 'none' }}">

                                        @haspermission('pharmacy_inventory.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-inventory/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.pharmacy_inventory.add-new') }}"
                                                    title="{{ translate('add new pharmacy inventory') }}">
                                                    <span
                                                        class="tio-circle
                                                    nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('pharmacy_inventory.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-inventory/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.pharmacy_inventory.list') }}"
                                                    title="{{ translate('pharmacy inventory list') }}">
                                                    <span
                                                        class="tio-circle
                                                    nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            <!-- POS Section -->
                            @if (auth('admin')->user()->can('pos.orders') ||
                                    auth('admin')->user()->can('pos.index') ||
                                    auth('admin')->user()->can('pharmacy-reports.revenue'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/pos*') || Request::is('admin/pharmacy-reports/revenue') ? 'active' : '' }}">


                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-shopping nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('POS') }}</span>
                                    </a>

                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/pos*') ? 'block' : 'none' }}">

                                        @haspermission('pos.index', 'admin')
                                            <li class="nav-item {{ Request::is('admin/pos') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.pos.index') }}"
                                                    title="{{ \App\CentralLogics\translate('pos') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('pos') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('pos.orders', 'admin')
                                            <li class="nav-item {{ Request::is('admin/pos/orders') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.pos.orders') }}"
                                                    title="{{ \App\CentralLogics\translate('orders') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">
                                                        {{ \App\CentralLogics\translate('orders') }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                        @haspermission('pharmacy-reports.revenue', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-reports/revenue') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.pharmacy-reports.revenue') }}"
                                                    title="{{ \App\CentralLogics\translate('reports') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">
                                                        {{ \App\CentralLogics\translate('Sales report') }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                            <!-- End POS -->

                            {{-- @if (auth('admin')->user()->can('pharmacy_stock_adjustments.add-new') || auth('admin')->user()->can('pharmacy_stock_adjustments.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/pharmacy-stock-adjustments*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Pharmacy Stock Adjustments') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/pharmacy-stock-adjustments*') ? 'block' : 'none' }}">

                                        @haspermission('pharmacy_stock_adjustments.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-stock-adjustments/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.pharmacy_stock_adjustments.add-new') }}"
                                                    title="{{ translate('add new pharmacy stock adjustments') }}">
                                                    <span
                                                        class="tio-circle
                                                        nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('pharmacy_stock_adjustments.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-stock-adjustments/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.pharmacy_stock_adjustments.list') }}"
                                                    title="{{ translate('pharmacy stock adjustments list') }}">
                                                    <span
                                                        class="tio-circle
                                                        nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif --}}
                        @endif


                        @if (auth('admin')->user()->can('emergency-medicines.add-new') ||
                                auth('admin')->user()->can('emergency-medicines.list') ||
                                auth('admin')->user()->can('emergency-medicine-categories.add-new') ||
                                auth('admin')->user()->can('emergency-medicine-categories.list') ||
                                auth('admin')->user()->can('emergency_inventory.add-new') ||
                                auth('admin')->user()->can('emergency_inventory.list') ||
                                auth('admin')->user()->can('emergency_prescriptions.list'))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('Inclinic Items Management') }}">{{ \App\CentralLogics\translate('Inclinic Items Management') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('emergency_medicine_categories.add-new') ||
                                    auth('admin')->user()->can('emergency_medicine_categories.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/emergency-medicine-categories*') || Request::is('admin/emergency_prescriptions*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Inclinic Item Categories') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/emergency-medicine-categories*') ? 'block' : 'none' }}">

                                        @haspermission('emergency_medicine_categories.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/emergency-medicine-categories/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.emergency_medicine_categories.add-new') }}"
                                                    title="{{ translate('add new Inclinic Item Category') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('emergency_medicine_categories.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/emergency-medicine-categories/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.emergency_medicine_categories.list') }}"
                                                    title="{{ translate('Inclinic Item Category list') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('emergency-medicines.add-new') || auth('admin')->user()->can('emergency-medicines.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/emergency-medicines*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Inclinic Items') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/emergency-medicines*') ? 'block' : 'none' }}">

                                        @haspermission('emergency-medicines.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/emergency-medicines/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.emergency-medicines.add-new') }}"
                                                    title="{{ translate('add new Inclinic Items') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('emergency-medicines.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/emergency-medicines/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.emergency-medicines.list') }}"
                                                    title="{{ translate('Inclinic Items list') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('emergency_inventory.add-new') ||
                                    auth('admin')->user()->can('emergency_inventory.list') ||
                                    auth('admin')->user()->can('emergency_prescriptions.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/emergency-inventory*') || Request::is('admin/emergency_prescriptions*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Inclinic Items Inventiry') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/emergency-inventory*') ? 'block' : 'none' }}">

                                        @haspermission('emergency_inventory.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/emergency-inventory/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.emergency_inventory.add-new') }}"
                                                    title="{{ translate('add new Inclinic Item to inventory') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('emergency_inventory.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/emergency-inventory/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.emergency_inventory.list') }}"
                                                    title="{{ translate('Inclinic Items in inventory list') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('emergency_prescriptions.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/emergency_prescriptions/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.emergency_prescriptions.list') }}"
                                                    title="{{ translate('Inclinic prescreptions list') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('Inclinic prescreptions') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif
                        @endif


                        {{-- @if (auth('admin')->user()->can('medicines.add-new') || auth('admin')->user()->can('medicines.list') || auth('admin')->user()->can('medicine_categories.add-new') || auth('admin')->user()->can('medicine_categories.list') || auth('admin')->user()->can('pharmacy_inventory.add-new') || auth('admin')->user()->can('pharmacy_inventory.list') || auth('admin')->user()->can('sales.add-new') || auth('admin')->user()->can('sales.list') || auth('admin')->user()->can('pharmacy_stock_adjustments.add-new') || auth('admin')->user()->can('pharmacy_stock_adjustments.list') || auth('admin')->user()->can('sale_details.add-new') || auth('admin')->user()->can('sale_details.list'))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('Pharmacy Management') }}">{{ \App\CentralLogics\translate('Store Management') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('medicines.add-new') || auth('admin')->user()->can('medicines.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/medicines*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Items') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/patient*') ? 'block' : 'none' }}">

                                        @haspermission('medicines.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medicines/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.medicines.add-new') }}"
                                                    title="{{ translate('add new medicine') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('medicines.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medicines/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.medicines.list') }}"
                                                    title="{{ translate('patient list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('medicine_categories.add-new') || auth('admin')->user()->can('medicine_categories.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/medicine-categories*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Items Categories') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/medicine-categories*') ? 'block' : 'none' }}">

                                        @haspermission('medicine_categories.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medicine-categories/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.medicine_categories.add-new') }}"
                                                    title="{{ translate('add new medicine category') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('medicine_categories.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medicine-categories/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.medicine_categories.list') }}"
                                                    title="{{ translate('medicine category list') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('pharmacy_inventory.add-new') || auth('admin')->user()->can('pharmacy_inventory.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/pharmacy-inventory*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Store Inventory') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/pharmacy-inventory*') ? 'block' : 'none' }}">

                                        @haspermission('pharmacy_inventory.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-inventory/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.pharmacy_inventory.add-new') }}"
                                                    title="{{ translate('add new pharmacy inventory') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('pharmacy_inventory.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-inventory/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.pharmacy_inventory.list') }}"
                                                    title="{{ translate('pharmacy inventory list') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                             @if (auth('admin')->user()->can('sales.add-new') || auth('admin')->user()->can('sales.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/sales*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Sales') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/sales*') ? 'block' : 'none' }}">

                                        @haspermission('sales.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/sales/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.sales.add-new') }}"
                                                    title="{{ translate('add new sales') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('sales.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/sales/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.sales.list') }}"
                                                    title="{{ translate('sales list') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('pharmacy_stock_adjustments.add-new') || auth('admin')->user()->can('pharmacy_stock_adjustments.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/pharmacy-stock-adjustments*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Store Stock Adjustments') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/pharmacy-stock-adjustments*') ? 'block' : 'none' }}">

                                        @haspermission('pharmacy_stock_adjustments.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-stock-adjustments/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.pharmacy_stock_adjustments.add-new') }}"
                                                    title="{{ translate('add new pharmacy stock adjustments') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('pharmacy_stock_adjustments.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/pharmacy-stock-adjustments/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.pharmacy_stock_adjustments.list') }}"
                                                    title="{{ translate('pharmacy stock adjustments list') }}">
                                                    <span
                                                        class="tio-circle
                                                nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif
                        @endif --}}
                        {{-- User Management --}}
                        @if (auth('admin')->user()->can('user.add-new') ||
                                auth('admin')->user()->can('user.list') ||
                                auth('admin')->user()->can('roles.list') ||
                                auth('admin')->user()->can('roles.add-new') ||
                                auth('admin')->user()->can('permissions.list') ||
                                auth('admin')->user()->can('department.add-new') ||
                                auth('admin')->user()->can('department.list'))

                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('User Management') }}">{{ \App\CentralLogics\translate('user_management') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <!-- Pages -->
                            @if (auth('admin')->user()->can('user.add-new') || auth('admin')->user()->can('user.list'))

                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/user*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('users') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/user*') ? 'block' : 'none' }}">

                                        @haspermission('user.add-new', 'admin')
                                            <li class="nav-item {{ Request::is('admin/user/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.user.add-new') }}"
                                                    title="{{ translate('add new user') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('user.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/user/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.user.list') }}"
                                                    title="{{ translate('user list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('roles.list') || auth('admin')->user()->can('roles.add-new'))

                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/roles*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-crown-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('role_management') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/user*') ? 'block' : 'none' }}">

                                        @haspermission('roles.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/roles/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.roles.add-new') }}"
                                                    title="{{ translate('add new roles') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new ') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission


                                        @haspermission('roles.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/roles/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.roles.list') }}"
                                                    title="{{ translate('roles list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('department.add-new') || auth('admin')->user()->can('department.list'))

                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/department*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <span class="nav-icon tio-documents-outlined"></span>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Department') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/department*') ? 'block' : 'none' }}">

                                        @haspermission('department.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/department/add-new') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.department.add-new') }}"
                                                    title="{{ translate('add new department') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('department.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/department/list') ? 'active' : '' }}">
                                                <a class="nav-link " href="{{ route('admin.department.list') }}"
                                                    title="{{ translate('department list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @haspermission('permissions.list', 'admin')
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/permissions/list') || Request::is('admin/permissions/*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.permissions.list') }}">
                                        <i class="tio-edit nav-icon"></i>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CentralLogics\translate('permission') }}
                                            {{ \App\CentralLogics\translate('list') }}
                                        </span>
                                    </a>
                                </li>
                            @endhaspermission
                        @endif


                        {{-- Medical Certificate Management --}}
                        {{-- @if (auth('admin')->user()->can('medical_certification.add-new') || auth('admin')->user()->can('medical_certification.list'))

                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('patient_management') }}">{{ \App\CentralLogics\translate('Medical Certification') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('medical_certification.add-new') || auth('admin')->user()->can('medical_certification.list'))

                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/medical_certification*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-user-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Medical Certification') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/medical_certification*') ? 'block' : 'none' }}">

                                        @haspermission('medical_certification.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/patient/add-new') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.medical_certification.add-new') }}"
                                                    title="{{ translate('Add Medical Certificate') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('medical_certification.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/medical_certification/list') ? 'active' : '' }}">
                                                <a class="nav-link "
                                                    href="{{ route('admin.medical_certification.list') }}"
                                                    title="{{ translate('Medical Certificate list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif
                        @endif --}}

                        {{-- Consent Form Management --}}

                        {{-- @if (auth('admin')->user()->can('medical_document.add-new') || auth('admin')->user()->can('medical_document.list'))

                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('patient_management') }}">{{ \App\CentralLogics\translate('Medical Forms') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('medical_document.add-new') || auth('admin')->user()->can('medical_document.list'))

                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/consent_form*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-document-text nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Medical Forms') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/consent_form*') ? 'block' : 'none' }}">

                                        @haspermission('medical_document.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/consent_form/add-new') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.medical_document.add-new') }}"
                                                    title="{{ translate('Add Medical Form') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('medical_document.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/consent_form/list') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.medical_document.list') }}"
                                                    title="{{ translate('Medical Form List') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif
                        @endif --}}

                        {{-- Referal Slip Form Management --}}
                        {{-- @if (auth('admin')->user()->can('referral_slip.add-new') || auth('admin')->user()->can('referral_slip.list'))

                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('patient_management') }}">{{ \App\CentralLogics\translate('Referral Slip') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('referral_slip.add-new') || auth('admin')->user()->can('referral_slip.list'))

                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/referral_slip*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <i class="tio-book nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('Referral Slip') }}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/referral_slip*') ? 'block' : 'none' }}">

                                        @haspermission('referral_slip.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/referral_slip/add-new') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.referral_slip.add-new') }}"
                                                    title="{{ translate('Add Referral Slip') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('referral_slip.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/referral_slip/list') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.referral_slip.list') }}"
                                                    title="{{ translate('Referral Slip List') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif
                        @endif --}}
                        {{-- Business Setup Management --}}
                        @if (auth('admin')->user()->can('business-settings.ecom-setup') ||
                                auth('admin')->user()->can('business-settings.sms-module') ||
                                auth('admin')->user()->can('business-settings.activity') ||
                                auth('admin')->user()->can('supplier.add-new') ||
                                auth('admin')->user()->can('supplier.list') ||
                                auth('admin')->user()->can('unit.add-new') ||
                                auth('admin')->user()->can('unit.list') ||
                                auth('admin')->user()->can('laboratory-machine.add-new') ||
                                auth('admin')->user()->can('laboratory-machine.list') ||
                                auth('admin')->user()->can('testing-method.add-new') ||
                                auth('admin')->user()->can('testing-method.list'))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                    title="{{ translate('System Management') }}">{{ \App\CentralLogics\translate('System Management') }}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            @if (auth('admin')->user()->can('supplier.add-new') || auth('admin')->user()->can('supplier.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/supplier*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <span class="nav-icon tio-devices-1"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CentralLogics\translate('Supplier') }}
                                        </span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/supplier*') ? 'block' : 'none' }}">

                                        @haspermission('supplier.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/supplier/add-new') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.supplier.add-new') }}"
                                                    title="{{ translate('add new supplier') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('supplier.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/supplier/list') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.supplier.list') }}"
                                                    title="{{ translate('supplier list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('unit.add-new') || auth('admin')->user()->can('unit.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/unit*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <span class="nav-icon tio-devices-1"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CentralLogics\translate('Unit') }}
                                        </span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/unit*') ? 'block' : 'none' }}">

                                        @haspermission('unit.add-new', 'admin')
                                            <li class="nav-item {{ Request::is('admin/unit/add-new') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.unit.add-new') }}"
                                                    title="{{ translate('add new unit') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('unit.list', 'admin')
                                            <li class="nav-item {{ Request::is('admin/unit/list') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.unit.list') }}"
                                                    title="{{ translate('unit list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('laboratory-machine.add-new') || auth('admin')->user()->can('laboratory-machine.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/laboratory-machine*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <span class="nav-icon tio-devices-1"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CentralLogics\translate('Laboratory Machine') }}
                                        </span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/laboratory-machine*') ? 'block' : 'none' }}">

                                        @haspermission('laboratory-machine.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/laboratory-machine/add-new') ? 'active' : '' }}">
                                                <a class="nav-link"
                                                    href="{{ route('admin.laboratory-machine.add-new') }}"
                                                    title="{{ translate('add new laboratory machine') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('laboratory-machine.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/laboratory-machine/list') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.laboratory-machine.list') }}"
                                                    title="{{ translate('laboratory machine list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission
                                    </ul>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('testing-method.add-new') || auth('admin')->user()->can('testing-method.list'))
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/testing-method*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                        href="javascript:">
                                        <span class="nav-icon tio-devices-1"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CentralLogics\translate('Testing Method') }}
                                        </span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{ Request::is('admin/testing-method*') ? 'block' : 'none' }}">

                                        @haspermission('testing-method.add-new', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/testing-method/add-new') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.testing-method.add-new') }}"
                                                    title="{{ translate('add new testing method') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{ \App\CentralLogics\translate('add') }}
                                                        {{ \App\CentralLogics\translate('new') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                        @haspermission('testing-method.list', 'admin')
                                            <li
                                                class="nav-item {{ Request::is('admin/testing-method/list') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.testing-method.list') }}"
                                                    title="{{ translate('testing method list') }}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{ \App\CentralLogics\translate('list') }}</span>
                                                </a>
                                            </li>
                                        @endhaspermission

                                    </ul>
                                </li>
                            @endif

                            @haspermission('business-settings.ecom-setup', 'admin')
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/ecom-setup') || Request::is('admin/business-settings/otp-setup') || Request::is('admin/business-settings/cookies-setup') || Request::is('admin/business-settings/delivery-fee-setup') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.business-settings.ecom-setup') }}">
                                        <i class="tio-settings nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('business_Setup') }}</span>
                                    </a>
                                </li>
                            @endhaspermission

                            @haspermission('business-settings.activity', 'admin')
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/activity') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.business-settings.activity') }}">
                                        <i class="tio-labels nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CentralLogics\translate('activity_logs') }}</span>
                                    </a>
                                </li>
                            @endhaspermission

                        @endif

                        <!-- End Pages -->


                        <li class="nav-item" style="padding-top: 100px">
                            <div class="nav-divider"></div>
                        </li>
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>


{{-- <script>
    $(document).ready(function () {
        $('.navbar-vertical-content').animate({
            scrollTop: $('#scroll-here').offset().top
        }, 'slow');
    });
</script> --}}

@push('script_2')
    <script>
        $(window).on('load', function() {
            if ($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });

        //Sidebar Menu Search
        var $rows = $('.navbar-vertical-content .navbar-nav > li');
        $('#search-bar-input').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush

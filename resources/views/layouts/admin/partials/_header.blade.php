<style>
    /* Ensure the text wraps and doesn't overflow */
    .notification-message {
        white-space: normal;
        /* Allow the text to wrap */
        word-wrap: break-word;
        /* Break long words if necessary */
        overflow-wrap: break-word;
        /* Ensure proper wrapping for different browsers */
        max-width: 400px;
        /* Set a max width for the dropdown to avoid it stretching */
    }
</style>
<div id="headerMain" class="d-none">
    <header id="header"
        class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <!-- Logo -->
                @php($restaurant_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first()->value)
                <div class="navbar-brand">
                    @if(file_exists(public_path(config('custom.upload_asset_path') . '/' . $restaurant_logo)))
                        <img class="navbar-brand-logo"
                            onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}'"
                            src="{{ asset(config('custom.upload_asset_path') . '/' . $restaurant_logo) }}" alt="Logo">
                        <img class="navbar-brand-logo-mini"
                            onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}'"
                            src="{{ asset(config('custom.upload_asset_path') . '/' . $restaurant_logo) }}" alt="Logo">
                    @else
                        <img class="navbar-brand-logo"
                            src="{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}" alt="Logo">
                        <img class="navbar-brand-logo-mini"
                            src="{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}" alt="Logo">
                    @endif
                </div>
                <!-- End Logo -->
            </div>

            <div class="navbar-nav-wrap-content-left d-xl-none">
                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                        data-placement="right" title="Collapse"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                        data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                        data-toggle="tooltip" data-placement="right" title="Expand"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->
            </div>

            <!-- Secondary Content -->
            <div class="navbar-nav-wrap-content-right">
                <!-- Navbar -->
                <ul class="navbar-nav align-items-center flex-row">
                    {{-- <li class="nav-item d-none d-sm-inline-block">
                        <!-- Notification -->
                        @if (!auth('admin')->user()->hasRole('Super Admin'))
                            <div class="hs-unfold">
                                @if (auth('admin')->user()->can('lab_result.add-new'))
                                    <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                    href="{{route('admin.patient.list')}}"
                                    >
                                        <i class="tio-notifications"></i>
                                        @php($message=\App\Models\MedicalHistory::where('lab_test_required', true)->where('lab_test_progress','pending')->count())
                                            <span class="btn-status btn-status-danger">{{ $message }}</span>
                                    </a>
                                @endif

                                @if (auth('admin')->user()->can('radiology_result.add-new'))
                                <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                    href="{{route('admin.patient.list')}}"
                                    >
                                        <i class="tio-notifications"></i>
                                            @php($message=\App\Models\MedicalHistory::where('radiology_test_required', true)->where('radiology_test_progress','pending')->count())
                                                <span class="btn-status btn-status-danger">{{ $message }}</span>
                                    </a>
                                @endif

                                @if (auth('admin')->user()->can('pharmacy.invoice.add-new'))
                                <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                    href="{{route('admin.pharmacy.invoice.list')}}"
                                    >
                                        <i class="tio-notifications"></i>

                                            @php($message= \App\Models\Billing::whereHas('billingDetails', function ($subQ) {
                                                $subQ->whereNotNull('medicine_id');
                                            })
                                            ->where('status', '=', 'unpaid')
                                            ->count()

                                            )
                                                <span class="btn-status btn-status-danger">{{ $message }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </li> --}}

                    <li class="nav-item ml-md-3">
                        <!-- Account -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                data-hs-unfold-options='{
                                     "target": "#notificationbardropdown",
                                     "type": "css-animation"
                                   }'>
                                <i class="tio-notifications"></i>
                                @php($message = \App\Models\Notification::where('status', 'unread')->count())
                                <span class="btn-status btn-status-danger notification-badge">{{ $message }}</span>
                            </a>

                            <div id="notificationbardropdown"
                                class="notification-list hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account max-w-full"
                                style="width: 350px;">

                                @php($notifications = \App\Models\Notification::where('status', 'unread')->get())
                                @foreach ($notifications as $notification)
                                    <div class="dropdown-item d-flex justify-content-between align-items-center">
                                        <span
                                            class="notification-message break-words max-w-full">{{ $notification->message }}</span>
                                        <a href="" class="text-primary">Read</a> <!-- Link to mark as read -->
                                    </div>
                                    <div class="dropdown-divider"></div>
                                @endforeach
                            </div>
                        </div>
                    </li>

                    <li class="nav-item ml-md-3">
                        <!-- Account -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper media align-items-center gap-3 bg-transparent dropdown-toggle dropdown-toggle-left-arrow"
                                href="javascript:;"
                                data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                                <div class="d-none d-md-block media-body text-right">
                                    <h5 class="profile-name text-capitalize mb-0">{{ auth('admin')->user()->f_name }}
                                    </h5>
                                    <span class="fs-12 text-capitalize">
                                        @if (!empty(auth('admin')->user()->getRoleNames()))
                                            @foreach (auth('admin')->user()->getRoleNames() as $v)
                                                {{ translate($v) }}
                                            @endforeach
                                        @else
                                            {{ translate('Guest') }}

                                        @endif
                                    </span>
                                </div>
                                <div class="avatar avatar-sm avatar-circle">
                                    @if (auth('admin')->user()->image &&
                                            file_exists(public_path(config('custom.upload_asset_path') . '/' . auth('admin')->user()->image)))
                                        <img class="avatar-img"
                                            onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}'"
                                            src="{{ asset(config('custom.upload_asset_path')) }}/{{ auth('admin')->user()->image }}"
                                            alt="Image Description">
                                    @else
                                        <img class="avatar-img"
                                            src="{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}"
                                            alt="Default Image">
                                    @endif
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            </a>

                            <div id="accountNavbarDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account">
                                <div class="dropdown-item-text">
                                    <div class="media gap-3 align-items-center">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            @if (auth('admin')->user()->image &&
                                                    file_exists(public_path(config('custom.upload_asset_path') . '/' . auth('admin')->user()->image)))
                                                <img class="avatar-img"
                                                    onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}'"
                                                    src="{{ asset(config('custom.upload_asset_path')) }}/{{ auth('admin')->user()->image }}"
                                                    alt="Image Description">
                                            @else
                                                <img class="avatar-img"
                                                    src="{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}"
                                                    alt="Default Image">
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{ auth('admin')->user()->f_name }}</span>
                                            <span class="card-text">{{ auth('admin')->user()->email }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('admin.settings') }}">
                                    <span class="text-truncate pr-2"
                                        title="Settings">{{ \App\CentralLogics\translate('settings') }}</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="javascript:"
                                    onclick="Swal.fire({
                                    title:'{{ translate('Do you want to logout?') }}',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#673ab7',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: '{{ translate('Yes') }}',
                                    cancelButtonText: '{{ translate('No') }}',
                                    denyButtonText: `{{ translate("Don't Logout") }}`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{ route('admin.auth.logout') }}';
                                    } else{
                                    Swal.fire('{{ translate('Canceled') }}', '', 'info')
                                    }
                                    })">
                                    <span class="text-truncate pr-2"
                                        title="Sign out">{{ \App\CentralLogics\translate('sign_out') }}</span>
                                </a>
                            </div>
                        </div>
                        <!-- End Account -->
                    </li>
                </ul>
                <!-- End Navbar -->
            </div>
            <!-- End Secondary Content -->
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    // Enable Pusher logging only for important events
    Pusher.logToConsole = true;
    Pusher.log = function(message) {
        // Only log important messages, filter out ping/pong
        if (!message.includes('pusher:ping') && !message.includes('pusher:pong')) {
            console.log(message);
        }
    };

    // Initialize Pusher globally
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        encrypted: true,
        forceTLS: true,
        enabledTransports: ['ws', 'wss']
    });

    // Debug Pusher connection
    pusher.connection.bind('connected', () => {
        console.log('✅ Real-time connection established');
    });

    pusher.connection.bind('connecting', () => {
        console.log('🔄 Attempting to connect to Pusher...');
    });

    pusher.connection.bind('disconnected', () => {
        console.log('❌ Real-time connection lost');
    });

    pusher.connection.bind('error', (err) => {
        console.error('❌ Connection error:', err);
    });

    function handleNewTestResult(data) {
        // console.log('🔬 Received new test result:', data);

        toastr.success(data.message);
        // Play notification sound
        try {
            const audio = new Audio('{{ asset(config('app.asset_path') . '/sound/notification.mp3') }}');
            audio.volume = 1.0;
            audio.play().catch(error => {
                console.error('🔇 Audio playback failed:', error);
            });
        } catch (e) {
            console.error('❌ Error playing notification sound:', e);
        }

        // Update notification count
        const notificationDropdownMenu = document.querySelector('.notification-dropdown-menu');
        const notificationBadge = document.querySelector('.notification-badge');
        if (notificationBadge) {
            const currentCount = parseInt(notificationBadge.textContent) || 0;
            const newCount = currentCount + 1;
            notificationBadge.textContent = newCount;
            notificationBadge.classList.remove('d-none');
        }

        // Create notification HTML
        const notificationHtml = `
        <a href="${data.link}">
            <h6 class="ml-4 fw-semibold text-primary">${data.title}</h6>
            <div class="dropdown-item d-flex justify-content-between align-items-center">
                <span
                    class="notification-message break-words max-w-full" style="color: black;">${data.message}</span>
                <a href="${data.link}" class="text-primary">Read</a> <!-- Link to mark as read -->
            </div>
            <div class="dropdown-divider"></div>
        </a>
        `;

        // Add notification to the list
        const notificationList = document.querySelector('.notification-list');
        if (notificationList) {
            notificationList.insertAdjacentHTML('afterbegin', notificationHtml);
            notificationList.classList.remove('hs-unfold-hidden');
            notificationList.classList.add('slideInUp');
        }
    }

    const allowedPermissions = @json(auth('admin')->check() ? auth('admin')->user()->getAllPermissions()->pluck('name') : []);

    const channel = pusher.subscribe('menu-testResults');
    console.log('📡 Subscribing to menu-testResults channel...');

    channel.bind('new.menu.testResult', function(data) {
        if (allowedPermissions.includes(data.permission)) {
            handleNewTestResult(data);
        }
    });
    // Log when the channel subscription is successful
    channel.bind('subscription_succeeded', function() {
        console.log('✅ Successfully subscribed to menu-testResults channel');
    });

    // Log any subscription errors
    channel.bind('subscription_error', function(status) {
        console.error('❌ Failed to subscribe to menu-testResults channel:', status);
    });

    channel.bind('pusher:subscription_succeeded', function() {
        console.log('✅ Pusher subscription succeeded');
    });

    channel.bind('pusher:subscription_error', function(error) {
        console.error('❌ Pusher subscription error:', error);
    });

    // Debug all events on the channel
    channel.bind_global(function(event, data) {
        // console.log('🔍 Channel event received:', event, data);
    });
</script>

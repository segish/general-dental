<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>@yield('title')</title>
    <!-- Favicon -->
    @php($icon = \App\Models\BusinessSetting::where(['key' => 'fav_icon'])->first()->value)
    <link rel="icon" type="image/x-icon" href="{{ asset(config('custom.upload_asset_path') . '/' . $icon) }}">
    <link rel="shortcut icon" href="">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/css/vendor.min.css">
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/css/style.css">
    @stack('css_or_js')

    <script
        src="{{ asset(config('app.asset_path') . '/admin') }}/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js">
    </script>
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/css/toastr.css">
</head>

<body class="footer-offset">

    {{-- loader --}}
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="loading" class="d--none">
                    <div class="loader-wrap">
                        <img width="200" src="{{ asset(config('app.asset_path') . '/admin/img/loader.gif') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- loader --}}

    <!-- Builder -->
    @include('layouts.admin.partials._front-settings')
    <!-- End Builder -->

    <!-- JS Preview mode only -->
    @include('layouts.admin.partials._header')
    @include('layouts.admin.partials._sidebar')
    <!-- END ONLY DEV -->

    <main id="content" role="main" class="main pointer-event">
        <!-- Content -->
        @yield('content')
        <!-- End Content -->

        <!-- Footer -->
        @include('layouts.admin.partials._footer')
        <!-- End Footer -->

        {{-- <div class="modal fade" id="popup-modal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <center>
                                    <h2 style="color: rgba(96,96,96,0.68)">
                                        <i class="tio-shopping-cart-outlined"></i>
                                        {{ translate('You have new order, Check Please.') }}
                                    </h2>
                                    <hr>
                                    <button onclick="ignore_order()"
                                        class="btn btn-warning mr-3">{{ translate('Ignore for now') }}</button>
                                    <button onclick="check_order()"
                                        class="btn btn-primary">{{ translate('Ok, let me check') }}</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

    </main>
    <!-- ========== END MAIN CONTENT ========== -->

    <!-- ========== END SECONDARY CONTENTS ========== -->
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/custom.js"></script>
    <!-- JS Implementing Plugins -->

    @stack('script')

    <!-- JS Front -->
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/vendor.min.js"></script>
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/theme.min.js"></script>
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/sweet_alert.js"></script>
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/toastr.js"></script>
    {!! Toastr::message() !!}

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif
    <!-- JS Plugins Init. -->
    <script>
        $(document).on('ready', function() {
            // ONLY DEV
            // =======================================================
            if (window.localStorage.getItem('hs-builder-popover') === null) {
                $('#builderPopover').popover('show')
                    .on('shown.bs.popover', function() {
                        $('.popover').last().addClass('popover-dark')
                    });

                $(document).on('click', '#closeBuilderPopover', function() {
                    window.localStorage.setItem('hs-builder-popover', true);
                    $('#builderPopover').popover('dispose');
                });
            } else {
                $('#builderPopover').on('show.bs.popover', function() {
                    return false
                });
            }
            // END ONLY DEV
            // =======================================================

            // BUILDER TOGGLE INVOKER
            // =======================================================
            $('.js-navbar-vertical-aside-toggle-invoker').click(function() {
                $('.js-navbar-vertical-aside-toggle-invoker i').tooltip('hide');
            });

            // INITIALIZATION OF MEGA MENU
            // =======================================================
            var megaMenu = new HSMegaMenu($('.js-mega-menu'), {
                desktop: {
                    position: 'left'
                }
            }).init();


            // INITIALIZATION OF NAVBAR VERTICAL NAVIGATION
            // =======================================================
            var sidebar = $('.js-navbar-vertical-aside').hsSideNav();


            // INITIALIZATION OF TOOLTIP IN NAVBAR VERTICAL MENU
            // =======================================================
            $('.js-nav-tooltip-link').tooltip({
                boundary: 'window'
            })

            $(".js-nav-tooltip-link").on("show.bs.tooltip", function(e) {
                if (!$("body").hasClass("navbar-vertical-aside-mini-mode")) {
                    return false;
                }
            });


            // INITIALIZATION OF UNFOLD
            // =======================================================
            $('.js-hs-unfold-invoker').each(function() {
                var unfold = new HSUnfold($(this)).init();
            });


            // INITIALIZATION OF FORM SEARCH
            // =======================================================
            $('.js-form-search').each(function() {
                new HSFormSearch($(this)).init()
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            $('.js-select2-patient-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });


            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();
            // INITIALIZATION OF CLIPBOARD
            // =======================================================
            $('.js-clipboard').each(function() {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });
        });
    </script>
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF SHOW PASSWORD
            // =======================================================
            $('.js-toggle-password').each(function() {
                new HSTogglePassword(this).init()
            });

            // INITIALIZATION OF FORM VALIDATION
            // =======================================================
            $('.js-validate').each(function() {
                $.HSCore.components.HSValidation.init($(this));
            });
        });
    </script>

    @stack('script_2')
    <audio id="myAudio">
        <source src="{{ asset(config('app.asset_path') . '/admin/sound/notification.mp3') }}" type="audio/mpeg">
    </audio>

    <script>
        var audio = document.getElementById("myAudio");

        function playAudio() {
            audio.play();
        }

        function pauseAudio() {
            audio.pause();
        }
    </script>
    <script>
        function route_alert(route, message) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#673ab7',
                cancelButtonText: '{{ translate('No') }}',
                confirmButtonText: '{{ translate('Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = route;
                }
            })
        }

        function form_alert(id, message) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#673ab7',
                cancelButtonText: '{{ translate('No') }}',
                confirmButtonText: '{{ translate('Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#' + id).submit()
                }
            })
        }

        function call_demo() {
            toastr.info('Disabled for demo version!')
        }

        /*============================================
        Reset Button Trigger Upload file
        ==============================================*/
        var initialImages = [];
        $(window).on('load', function() {
            $("form").find('img').each(function(index, value) {
                initialImages.push(value.src);
            })
        })

        $(document).ready(function() {
            $('form').on('reset', function(e) {
                $("form").find('img').each(function(index, value) {
                    $(value).attr('src', initialImages[index]);
                })
            });
        });
    </script>

    <!-- IE Support -->
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write(
            '<script src="{{ asset(config('app.asset_path') . '/admin') }}/vendor/babel-polyfill/polyfill.min.js"><\/script>'
        );
    </script>
</body>

</html>

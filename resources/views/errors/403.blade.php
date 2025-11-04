@extends('layouts.admin.app')

@section('title', 'Error 403 | eMarket')

@section('content')
    <div class="container">
        <div class="footer-height-offset d-flex justify-content-center align-items-center flex-column">
            <div class="row align-items-sm-center w-100">
                <div class="col-sm-6">
                    <div class="text-center text-sm-right mr-sm-4 mb-5 mb-sm-0">
                        <img class="w-60 w-sm-100 mx-auto" src="{{ asset('/assets/admin') }}/svg/illustrations/unlock.svg"
                            alt="Image Description" style="max-width: 15rem;">
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 text-center text-sm-left">
                    <h1 class="display-1 mb-0">403</h1>
                    <p class="lead">
                        {{ 'Sorry, you do not have permission to access this page.' }}
                    </p>
                    @if (auth('branch')->check())
                        <a class="btn btn-primary" href="{{ route('branch.dashboard') }}">Go to Dashboard</a>
                    @elseif(auth('admin')->check())
                        @php
                            $redirectRoute = 'admin.settings'; // Default fallback
                            if (auth('admin')->user()->hasRole('Super Admin')) {
                                if (auth('admin')->user()->can('dashboard')) {
                                    $redirectRoute = 'admin.dashboard';
                                }
                            } else {
                                if (auth('admin')->user()->can('doctor_dashboard')) {
                                    $redirectRoute = 'admin.doctor_dashboard';
                                } elseif (auth('admin')->user()->can('pharmacist_dashboard')) {
                                    $redirectRoute = 'admin.pharmacist_dashboard';
                                } elseif (auth('admin')->user()->can('lab_technician_dashboard')) {
                                    $redirectRoute = 'admin.lab_technician_dashboard';
                                } elseif (auth('admin')->user()->can('radiologist_dashboard')) {
                                    $redirectRoute = 'admin.radiologist_dashboard';
                                } elseif (auth('admin')->user()->can('receptionist_dashboard')) {
                                    $redirectRoute = 'admin.receptionist_dashboard';
                                } elseif (auth('admin')->user()->can('nurse_dashboard')) {
                                    $redirectRoute = 'admin.nurse_dashboard';
                                }
                            }
                        @endphp
                        <a class="btn btn-primary" href="{{ route($redirectRoute) }}">Go to Dashboard</a>
                    @else
                        <a class="btn btn-primary" href="{{ route('admin.auth.login') }}">Go to Login</a>
                    @endif
                </div>
            </div>
            <!-- End Row -->
        </div>
    </div>
@endsection

@push('css_or_js')
    <!-- Additional CSS if needed -->
@endpush

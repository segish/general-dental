@extends('layouts.admin.app')

@section('title', translate('Visit Management'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ translate('Visit Management') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs" id="visitManagementTabs" role="tablist">
                            @if (auth('admin')->user()->can('visit.add-new'))
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ request()->get('active') == 'add-visit' ? 'active' : '' }}" id="add-visit-tab" data-toggle="tab" href="#add-visit"
                                        role="tab" aria-controls="add-visit" aria-selected="{{ request()->get('active') == 'add-visit' ? 'true' : 'false' }}">
                                        <i class="tio-add mr-1"></i>{{ translate('Add New Visit') }}
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('invoice.list'))
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ request()->get('active') == 'invoice-list' ? 'active' : '' }}" id="invoice-list-tab" data-toggle="tab" href="#invoice-list"
                                        role="tab" aria-controls="invoice-list" aria-selected="{{ request()->get('active') == 'invoice-list' ? 'true' : 'false' }}">
                                        <i class="tio-receipt mr-1"></i>{{ translate('Invoice List') }}
                                        <span
                                            class="badge badge-soft-dark rounded-50 fs-14 ms-1">{{ $billings->total() }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('visit.list'))
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ request()->get('active') == 'visit-list' ? 'active' : '' }}" id="visit-list-tab" data-toggle="tab" href="#visit-list"
                                        role="tab" aria-controls="visit-list" aria-selected="{{ request()->get('active') == 'visit-list' ? 'true' : 'false' }}">
                                        <i class="tio-list mr-1"></i>{{ translate('Visit List') }}
                                        <span
                                            class="badge badge-soft-dark rounded-50 fs-14 ms-1">{{ $visits->total() }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="visitManagementTabsContent">
                            <!-- Include Tab Components -->
                            @if (auth('admin')->user()->can('visit.add-new'))
                                @include('admin-views.visit.components.add-visit-tab')
                            @endif
                            @if (auth('admin')->user()->can('visit.list'))
                                @include('admin-views.visit.components.visit-list-tab')
                            @endif
                            @if (auth('admin')->user()->can('invoice.list'))
                                @include('admin-views.visit.components.invoice-list-tab')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Modal Components -->
    @include('admin-views.visit.components.modals.add-patient-modal')
    @include('admin-views.visit.components.modals.billing-modals')

    @php
        $currency_code = \App\Models\BusinessSetting::where('key', 'currency')->first()->value;
        $currency_position = \App\CentralLogics\Helpers::get_business_settings('currency_symbol_position') ?? 'right';
    @endphp
@endsection

@push('script_2')
    <!-- Include JavaScript Components -->
    @include('admin-views.visit.components.scripts.visit-form-scripts')
    @include('admin-views.visit.components.scripts.patient-modal-scripts')
    @include('admin-views.visit.components.scripts.billing-scripts')
@endpush

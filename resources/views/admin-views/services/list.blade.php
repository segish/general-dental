@extends('layouts.admin.app')

@section('title', translate('Billing Services List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/service.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('billing_services_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $services->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4 col-sm-8 col-md-6">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Search by service Name') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('service.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.service.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('add_new_service') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                    <th>{{ \App\CentralLogics\translate('service_name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Category') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Price') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Billing Type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Billing Interval (Days)') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Payment Timing') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows">
                                @foreach ($services as $key => $service)
                                    <tr>
                                        <td>{{ $services->firstitem() + $key }}</td>
                                        <td>{{ $service->service_name }}</td>
                                        <td>{{ $service->serviceCategory->name }}</td>
                                        <td>{{ $service->price }}</td>
                                        <td>{{ ucfirst($service->billing_type) }}</td>
                                        <td>{{ $service->billing_interval_days ?? 'N/A' }}</td>
                                        <td>
                                            @if ($service->payment_timing == 'prepaid')
                                                Prepaid
                                            @elseif($service->payment_timing == 'postpaid')
                                                Postpaid
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('service.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.service.edit', [$service->id]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('service.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('service-{{ $service->id }}','{{ \App\CentralLogics\translate('Want to delete this service ?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.service.delete', [$service->id]) }}"
                                                method="post" id="service-{{ $service->id }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $services->links() !!}
                        </div>
                    </div>
                    @if (count($services) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

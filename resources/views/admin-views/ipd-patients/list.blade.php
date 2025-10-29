@extends('layouts.admin.app')

@section('title', translate('Visit List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/visit.png') }}" alt="">
                {{ \App\CentralLogics\translate('Visit List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $visits->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4 col-sm-8 col-md-6">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Search by visit type or date') }}"
                                            aria-label="Search" value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                {{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('visit.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.visit.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Visit') }}
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
                                    <th>{{ \App\CentralLogics\translate('Patient Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Doctor Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Visit Type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Visit Date') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows">
                                @foreach ($visits as $key => $visit)
                                    <tr>
                                        <td>{{ $visits->firstitem() + $key }}</td>
                                        <td>{{ $visit->patient->full_name }}</td>
                                        <td>{{ $visit->doctor->full_name ?? 'N/A' }}</td>
                                        <td>{{ $visit->visit_type }}</td>
                                        <td>{{ $visit->formatted_visit_date }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('visit.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.visit.edit', [$visit->id]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('visit.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('visit-{{ $visit->id }}','{{ \App\CentralLogics\translate('Want to delete this visit?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $visits->links() !!}
                        </div>
                    </div>
                    @if (count($visits) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin/svg/illustrations/sorry.svg') }}"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin.app')

@section('title', translate('Laboratory Machines List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ \App\CentralLogics\translate('Laboratory Machines List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $machines->total() }}</span>
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
                                            placeholder="{{ translate('Search by keyword') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('Search') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('laboratory-machine.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.laboratory-machine.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Machine') }}
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
                                    <th>{{ \App\CentralLogics\translate('Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Model') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Manufacturer') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Code') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($machines as $key => $machine)
                                    <tr>
                                        <td>{{ $machines->firstitem() + $key }}</td>
                                        <td>{{ $machine->name }}</td>
                                        <td>{{ $machine->model }}</td>
                                        <td>{{ $machine->manufacturer }}</td>
                                        <td>{{ $machine->code }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('laboratory-machine.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.laboratory-machine.edit', [$machine->id]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('laboratory-machine.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('machine-{{ $machine->id }}','{{ \App\CentralLogics\translate('Want to delete this machine?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.laboratory-machine.delete', [$machine->id]) }}"
                                                method="post" id="machine-{{ $machine->id }}">
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
                            {!! $machines->links() !!}
                        </div>
                    </div>
                    @if (count($machines) == 0)
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

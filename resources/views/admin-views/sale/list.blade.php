@extends('layouts.admin.app')

@section('title', translate('Sales List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/sales.png') }}" alt="">
                {{ translate('Sales List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $sales->total() }}</span>
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
                                            placeholder="{{ translate('Search by any term...') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('sales.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.sales.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Sale') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    <th>{{ translate('Patient Name') }}</th>
                                    <th>{{ translate('Prescription ID') }}</th>
                                    <th>{{ translate('Sale Date') }}</th>
                                    <th>{{ translate('Total Amount') }}</th>
                                    <th>{{ translate('Sale Type') }}</th>
                                    <th class="text-center">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $key => $sale)
                                    <tr>
                                        <td>{{ $sales->firstItem() + $key }}</td>
                                        <td>{{ $sale->patient ? $sale->patient->name : 'N/A' }}</td>
                                        <td>{{ $sale->prescription_id ?? 'N/A' }}</td>
                                        <td>{{ $sale->sale_date }}</td>
                                        <td>{{ $sale->total_amount }}</td>
                                        <td>{{ $sale->sale_type }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('sales.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.sales.edit', [$sale['id']]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('sales.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('sale-{{ $sale['id'] }}','{{ \App\CentralLogics\translate('Want to delete this sale?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.sales.delete', [$sale['id']]) }}" method="post"
                                                id="sale-{{ $sale['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {!! $sales->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteSale(id) {
            if (confirm("{{ translate('Are you sure you want to delete this sale?') }}")) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endsection

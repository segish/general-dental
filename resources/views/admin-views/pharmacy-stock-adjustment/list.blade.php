@extends('layouts.admin.app')

@section('title', translate('Pharmacy Stock Adjustments'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/stock_adjustment.png') }}"
                    alt="">
                {{ translate('Pharmacy Stock Adjustments') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $adjustments->total() }}</span>
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
                                            <button type="submit" class="btn btn-primary">{{ translate('Search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('pharmacy_stock_adjustments.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.pharmacy_stock_adjustments.add-new') }}"
                                        class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ translate('Add New Adjustment') }}
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
                                    <th>{{ translate('Medicine Name') }}</th>
                                    <th>{{ translate('Inventory Location') }}</th>
                                    <th>{{ translate('Quantity Adjusted') }}</th>
                                    <th>{{ translate('Adjustment Type') }}</th>
                                    <th>{{ translate('Requested By') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th class="text-center">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adjustments as $key => $adjustment)
                                    <tr>
                                        <td>{{ $adjustments->firstItem() + $key }}</td>
                                        <td>{{ $adjustment->medicine->name }}</td>
                                        <td>{{ $adjustment->pharmacyInventory->location }}</td>
                                        <td>{{ $adjustment->quantity }}</td>
                                        <td>{{ $adjustment->adjustment_type }}</td>
                                        <td>{{ $adjustment->requestedBy->name }}</td>
                                        <td>{{ $adjustment->status }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('pharmacy_stock_adjustments.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.pharmacy_stock_adjustments.edit', [$adjustment['id']]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('pharmacy_stock_adjustments.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('adjustment-{{ $adjustment['id'] }}','{{ translate('Want to delete this adjustment?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form
                                                action="{{ route('admin.pharmacy_stock_adjustments.delete', [$adjustment['id']]) }}"
                                                method="post" id="adjustment-{{ $adjustment['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {!! $adjustments->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteAdjustment(id) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: "{{ translate('Are you sure you want to delete this adjustment?') }}",
                showCancelButton: true,
                cancelButtonColor: '#3085d6',
                confirmButtonColor: '#d33',
                cancelButtonText: '{{ translate('No') }}',
                confirmButtonText: '{{ translate('Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection

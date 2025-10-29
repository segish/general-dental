@extends('layouts.admin.app')

@section('title', translate('Inventory List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/inventory.png') }}"
                    alt="">
                {{ translate('Inventory List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $inventories->total() }}</span>
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
                                            <button type="submit" class="btn btn-primary">
                                                {{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4 col-sm-8 col-md-6">
                                <form action="{{ url()->current() }}" method="GET" class="d-flex gap-2">
                                    <select name="low_stock" class="form-control" onchange="this.form.submit()">
                                        <option value="">{{ translate('Stock Status') }}</option>
                                        <option value="1" {{ request('low_stock') == '1' ? 'selected' : '' }}>
                                            {{ translate('Low Stock') }}</option>
                                    </select>
                                    <select name="expired" class="form-control" onchange="this.form.submit()">
                                        <option value="">{{ translate('Expiry Status') }}</option>
                                        <option value="expired" {{ request('expired') == 'expired' ? 'selected' : '' }}>
                                            {{ translate('Expired') }}</option>
                                        <option value="soon" {{ request('expired') == 'soon' ? 'selected' : '' }}>
                                            {{ translate('Expiring Soon') }}</option>
                                    </select>
                                    @if (request('search'))
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('pharmacy_inventory.add-new'))
                                <div class="col-lg-4 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.pharmacy_inventory.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Inventory') }}
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
                                    <th>{{ translate('Product') }}</th>
                                    <th>{{ translate('Batch Number') }}</th>
                                    <th>{{ translate('Quantity') }}</th>
                                    <th>{{ translate('Buying Price') }}</th>
                                    <th>{{ translate('Selling Price') }}</th>
                                    <th>{{ translate('Expiry Date') }}</th>
                                    <th>{{ translate('Supplier') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th class="text-center">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventories as $key => $inventory)
                                    <tr>
                                        <td>{{ $inventories->firstItem() + $key }}</td>
                                        <td>
                                            {{ $inventory->product->name }}
                                            <br>
                                            <small class="text-muted">{{ $inventory->product->medicine->name }}</small>
                                        </td>
                                        <td>{{ $inventory->batch_number }}</td>
                                        <td>{{ $inventory->quantity }}</td>
                                        <td>{{ Helpers::set_symbol(number_format($inventory->buying_price, 2)) }}</td>
                                        <td>{{ Helpers::set_symbol(number_format($inventory->selling_price, 2)) }}</td>
                                        <td>
                                            {{ date('d M Y', strtotime($inventory->expiry_date)) }}<br>
                                            @if ($inventory->isExpiringSoon())
                                                <span class="badge badge-soft-danger">Expiring Soon</span>
                                            @endif
                                        </td>
                                        <td>{{ $inventory->supplier->name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($inventory->quantity <= 0)
                                                <span class="badge badge-soft-danger">Out of Stock</span>
                                            @elseif($inventory->isLowStock())
                                                <span class="badge badge-soft-warning">Low Stock</span>
                                            @else
                                                <span class="badge badge-soft-success">In Stock</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('pharmacy_inventory.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.pharmacy_inventory.edit', [$inventory['id']]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('pharmacy_inventory.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('inventory-{{ $inventory['id'] }}','{{ \App\CentralLogics\translate('Want to delete this inventory item ?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form
                                                action="{{ route('admin.pharmacy_inventory.delete', [$inventory['id']]) }}"
                                                method="post" id="inventory-{{ $inventory['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {!! $inventories->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

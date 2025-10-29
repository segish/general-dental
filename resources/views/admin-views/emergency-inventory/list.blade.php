@extends('layouts.admin.app')

@section('title', translate('Inclinic Items Inventory'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/inventory.png') }}"
                    alt="">
                {{ translate('Inclinic Items Inventory') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $inventory->total() }}</span>
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
                            @if (auth('admin')->user()->can('emergency_inventory.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.emergency_inventory.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Inventory Item') }}
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
                                    <th>{{ translate('Batch Number') }}</th>
                                    <th>{{ translate('Quantity') }}</th>
                                    <th>{{ translate('Buying Price') }}</th>
                                    <th>{{ translate('Selling Price') }}</th>
                                    <th>{{ translate('Expiry Date') }}</th>
                                    <th>{{ translate('Supplier') }}</th>
                                    <th class="text-center">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventory as $key => $item)
                                    <tr>
                                        <td>{{ $inventory->firstItem() + $key }}</td>
                                        <td>{{ $item->medicine->name }}</td>
                                        <td>{{ $item->batch_number ? $item->batch_number : 'N/A' }}</td>
                                        <td class="{{ $item->isLowStock() ? 'text-danger' : 'text-success' }}">
                                            {{ $item->quantity }}
                                            @if ($item->isLowStock())
                                                <small class="badge bg-soft-danger text-danger">low</small>
                                            @endif
                                        </td>
                                        <td>{{ Helpers::set_symbol($item->buying_price) }}</td>
                                        <td>{{ Helpers::set_symbol($item->selling_price) }}</td>
                                        <td class="{{ $item->isExpiringSoon() ? 'text-danger' : 'text-success' }}">
                                            {{ $item->expiry_date ? date('d M Y', strtotime($item->expiry_date)) : 'N/A' }}
                                            @if ($item->isExpiringSoon())
                                                <br>
                                                <small class="badge bg-soft-danger text-danger">Expiring soon</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->supplier ? $item->supplier->name : 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('emergency_inventory.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.emergency_inventory.edit', [$item['id']]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('emergency_inventory.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('inventory-{{ $item['id'] }}','{{ \App\CentralLogics\translate('Want to delete this item?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.emergency_inventory.delete', [$item['id']]) }}"
                                                method="post" id="inventory-{{ $item['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {!! $inventory->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteInventory(id) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: "{{ translate('Are you sure you want to delete this inventory item?') }}",
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

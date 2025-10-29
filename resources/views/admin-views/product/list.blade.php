@extends('layouts.admin.app')

@section('title', translate('Product List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/product.png') }}"
                    alt="">
                {{ translate('Product List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $products->total() }}</span>
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
                            @if (auth('admin')->user()->can('products.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.products.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Product') }}
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
                                    <th>{{ translate('Image') }}</th>
                                    <th>{{ translate('Product Code') }}</th>
                                    <th>{{ translate('Product Name') }}</th>
                                    <th>{{ translate('Medicine') }}</th>
                                    <th>{{ translate('Unit') }}</th>
                                    <th>{{ translate('Tax') }}</th>
                                    <th>{{ translate('Discount') }}</th>
                                    <th>{{ translate('Total Stock') }}</th>
                                    <th class="text-center">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td>{{ $products->firstItem() + $key }}</td>
                                        <td>
                                            <img class="rounded" width="30" height="30"
                                                src="{{ asset('/storage/app/public/product/' . $product->image) }}"
                                                onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img2.jpg') }}'"
                                                alt="{{ $product->name }}">
                                        </td>
                                        <td>{{ $product->product_code }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->medicine->name ?? 'N/A' }}</td>
                                        <td>{{ $product->unit->name ?? 'N/A' }}</td>
                                        <td>{{ $product->tax ?? '0' }}%</td>
                                        <td>
                                            @if ($product->discount)
                                                {{ $product->discount }}
                                                ({{ $product->discount_type }})
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $product->stock ?? 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('products.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.products.edit', [$product['id']]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('products.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('product-{{ $product['id'] }}','{{ \App\CentralLogics\translate('Want to delete this product ?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.products.delete', [$product['id']]) }}"
                                                method="post" id="product-{{ $product['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {!! $products->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

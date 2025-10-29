@extends('layouts.admin.app')

@section('title', translate('Pharmacy Inventory'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/inventory.png') }}" alt="">
                {{ translate('Pharmacy Inventory') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.pharmacy_inventory.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Product') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="product_id" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>Select product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}
                                                ({{ $product->medicine->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Batch Number') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="batch_number" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Barcode') }}</label>
                                    <input type="text" name="barcode" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="input-label">{{ translate('Quantity') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="input-label">{{ translate('Buying Price') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="buying_price" class="form-control" min="0"
                                        step="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="input-label">{{ translate('Selling Price') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="selling_price" class="form-control" min="0"
                                        step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Expiry Date') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="expiry_date" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Received Date') }}</label>
                                    <input type="date" name="received_date" class="form-control"
                                        value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="input-label">{{ translate('Supplier') }}</label>
                                    <select name="supplier_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>Select supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="input-label">{{ translate('Manufacturer') }}</label>
                                    <input type="text" name="manufacturer" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

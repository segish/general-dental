@extends('layouts.admin.app')

@section('title', translate('Edit Inventory'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/inventory.png') }}"
                    alt="">
                {{ translate('Edit Inventory') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.pharmacy_inventory.update', [$inventory['id']]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="product_id">{{ translate('Product') }}<span
                                            class="text-danger">*</span></label>
                                        <select name="product_id" id="product_id" class="form-control js-select2" required>
                                            <option value="">{{ translate('Select Product') }}</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $inventory->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }} ({{ $product->medicine->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="batch_number">{{ translate('Batch Number') }}<span
                                            class="text-danger">*</span></label>
                                        <input type="text" name="batch_number" id="batch_number" class="form-control"
                                            value="{{ $inventory->batch_number }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="barcode">{{ translate('Barcode') }}</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control"
                                            value="{{ $inventory->barcode }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="input-label" for="quantity">{{ translate('Quantity') }}<span
                                            class="text-danger">*</span></label>
                                        <input type="number" name="quantity" id="quantity" class="form-control"
                                            value="{{ $inventory->quantity }}" required min="0" step="1">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="buying_price">{{ translate('Buying Price') }}<span
                                            class="text-danger">*</span></label>
                                        <input type="number" name="buying_price" id="buying_price" class="form-control"
                                            value="{{ $inventory->buying_price }}" required min="0" step="0.01">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="selling_price">{{ translate('Selling Price') }}<span
                                            class="text-danger">*</span></label>
                                        <input type="number" name="selling_price" id="selling_price" class="form-control"
                                            value="{{ $inventory->selling_price }}" required min="0" step="0.01">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="input-label" for="expiry_date">{{ translate('Expiry Date') }}<span
                                            class="text-danger">*</span></label>
                                        <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                            value="{{ date('Y-m-d', strtotime($inventory->expiry_date)) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="received_date">{{ translate('Received Date') }}</label>
                                        <input type="date" name="received_date" id="received_date" class="form-control"
                                            value="{{ date('Y-m-d', strtotime($inventory->received_date)) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="manufacturer">{{ translate('Manufacturer') }}</label>
                                        <input type="text" name="manufacturer" id="manufacturer" class="form-control"
                                            value="{{ $inventory->manufacturer }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label" for="supplier_id">{{ translate('Supplier') }}</label>
                                        <select name="supplier_id" id="supplier_id" class="form-control js-select2">
                                            <option value="">{{ translate('Select Supplier') }}</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ $inventory->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <button type="reset" class="btn btn-secondary">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.js-select2').select2();
        });
    </script>
@endpush

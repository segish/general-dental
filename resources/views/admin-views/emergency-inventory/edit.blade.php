@extends('layouts.admin.app')

@section('title', translate('Update Inclinic Item Inventory'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/inventory.png') }}"
                    alt="">
                {{ translate('Update Inclinic Item Inventory') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.emergency_inventory.update', $inventory->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Item') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="medicine_id" class="form-control" required>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}"
                                                {{ $inventory->medicine_id == $medicine->id ? 'selected' : '' }}>
                                                {{ $medicine->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Batch Number') }}</label>
                                    <input type="text" name="batch_number" class="form-control"
                                        value="{{ $inventory->batch_number }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Supplier') }}</label>
                                    <select name="supplier_id" class="form-control" >
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ $inventory->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="input-label">{{ translate('Quantity') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" required
                                        value="{{ $inventory->quantity }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="input-label">{{ translate('Buying Price') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="buying_price" class="form-control" required
                                        value="{{ $inventory->buying_price }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="input-label">{{ translate('Selling Price') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="selling_price" class="form-control" required
                                        value="{{ $inventory->selling_price }}">
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label class="input-label" for="expiry_date">{{ translate('Expiry Date') }}</label>
                                    <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                        value="{{ date('Y-m-d', strtotime($inventory->expiry_date)) }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Received Date') }}</label>
                                    <input type="date" name="received_date" class="form-control"
                                        value="{{ date('Y-m-d', strtotime($inventory->received_date)) }}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
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

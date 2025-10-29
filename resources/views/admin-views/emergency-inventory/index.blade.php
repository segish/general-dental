@extends('layouts.admin.app')

@section('title', translate('Inclinic Item Inventory'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/inventory.png') }}" alt="">
                {{ translate('Add Inclinic Item to Inventory') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.emergency_inventory.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Item') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="emergency_medicine_id" class="form-control js-select2-custom" required>
                                        <option value="">{{ translate('Select Item') }}</option>
                                        @foreach($medicines as $medicine)
                                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Batch Number') }}</label>
                                    <input type="text" name="batch_number" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Supplier') }}</label>
                                    <select name="supplier_id" class="form-control js-select2-custom">
                                        <option value="">{{ translate('Select Supplier') }}</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label class="input-label">{{ translate('Quantity') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="input-label">{{ translate('Buying Price') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="buying_price" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="input-label">{{ translate('Selling Price') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="selling_price" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Expiry Date') }}</label>
                                    <input type="date" name="expiry_date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Received Date') }}</label>
                                    <input type="date" name="received_date" class="form-control">
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

@extends('layouts.admin.app')

@section('title', translate('Products'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/product.png') }}" alt="">
                {{ translate('Products') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Medicine') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="medicine_id" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>Select medicine</option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Product Code') }}</label>
                                    <input type="text" name="product_code" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Product Name') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Unit') }}</label>
                                    <select name="unit_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>Select unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Tax (%)') }}</label>
                                    <input type="number" name="tax" class="form-control" min="0" max="100"
                                        step="0.01">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Discount') }}</label>
                                    <input type="number" name="discount" class="form-control" min="0"
                                        step="0.01">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Discount Type') }}</label>
                                    <select name="discount_type" class="form-control js-select2-custom">
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Low Stock Threshold') }}</label>
                                    <input type="number" name="low_stock_threshold" class="form-control" min="1"
                                        value="10">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Expiry Alert Days') }}</label>
                                    <input type="number" name="expiry_alert_days" class="form-control" min="1"
                                        value="30">
                                </div>
                                <div class="col-md-12">
                                    <label class="input-label">{{ translate('Product Image') }}</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
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

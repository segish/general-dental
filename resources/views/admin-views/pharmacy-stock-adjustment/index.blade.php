@extends('layouts.admin.app')

@section('title', translate('Pharmacy Stock Adjustment'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/stock.png') }}" alt="">
                {{ translate('Pharmacy Stock Adjustment') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.pharmacy_stock_adjustments.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="input-label">{{ translate('Medicine') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="medicine_id" class="form-control js-select2-custom" required>
                                        <option value="">{{ translate('Select Medicine') }}</option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="input-label">{{ translate('Pharmacy Inventory') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="pharmacy_inventory_id" class="form-control js-select2-custom" required>
                                        <option value="">{{ translate('Select Inventory') }}</option>
                                        @foreach ($pharmacyInventories as $inventory)
                                            <option value="{{ $inventory->id }}">{{ $inventory->location }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Quantity') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Adjustment Type') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="adjustment_type" class="form-control js-select2-custom" required>
                                        <option value="">{{ translate('Select Adjustment Type') }}</option>
                                        <option value="Damage">Damage</option>
                                        <option value="Expiration">Expiration</option>
                                        <option value="Correction">Correction</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Reason') }}</label>
                                    <input type="text" name="reason" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Requested By') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="requested_by" class="form-control js-select2-custom" required>
                                        <option value="">{{ translate('Select Pharmacist') }}</option>
                                        @foreach ($admins as $admin)
                                            <option value="{{ $admin->id }}">{{ $admin->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <button type="submit"
                                    class="btn btn-primary">{{ translate('Submit Adjustment') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

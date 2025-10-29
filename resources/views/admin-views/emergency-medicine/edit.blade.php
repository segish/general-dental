@extends('layouts.admin.app')

@section('title', translate('Edit Inclinic Item'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/medicine.png') }}" alt="">
                {{ translate('Edit Inclinic Item') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.emergency-medicines.update', $medicine->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Name') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $medicine->name }}"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Description') }}</label>
                                    <textarea name="description" class="form-control" rows="1">{{ $medicine->description }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Unit') }}</label>
                                    <select name="unit_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>{{ translate('Select Unit') }}</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ $medicine->unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Payment Timing') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="payment_timing" class="form-control js-select2-custom" required>
                                        <option value="prepaid"
                                            {{ $medicine->payment_timing == 'prepaid' ? 'selected' : '' }}>
                                            {{ translate('Prepaid') }}
                                        </option>
                                        <option value="postpaid"
                                            {{ $medicine->payment_timing == 'postpaid' ? 'selected' : '' }}>
                                            {{ translate('Postpaid') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Item Type') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="item_type" class="form-control js-select2-custom" required>
                                        <option value="medication"
                                            {{ $medicine->item_type == 'medication' ? 'selected' : '' }}>
                                            {{ translate('Medication') }}
                                        </option>
                                        <option value="consumable"
                                            {{ $medicine->item_type == 'consumable' ? 'selected' : '' }}>
                                            {{ translate('Consumable') }}
                                        </option>
                                        <option value="equipment"
                                            {{ $medicine->item_type == 'equipment' ? 'selected' : '' }}>
                                            {{ translate('Equipment') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Category') }}</label>
                                    <select name="category_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>{{ translate('Select Category') }}
                                        </option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $medicine->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="input-label">{{ translate('Low Stock Threshold') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="low_stock_threshold" class="form-control"
                                        value="{{ $medicine->low_stock_threshold }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="input-label">{{ translate('Expiry Alert Days') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="expiry_alert_days" class="form-control"
                                        value="{{ $medicine->expiry_alert_days }}" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

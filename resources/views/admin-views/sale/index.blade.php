@extends('layouts.admin.app')

@section('title', translate('Sales Entry'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/sales.png') }}" alt="">
                {{ translate('Sales Entry') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.sales.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="input-label">{{ translate('Patient Name') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="patient_id" class="form-control js-select2-custom" required>
                                        <option value="">{{ translate('Select Patient') }}</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="input-label">{{ translate('Prescription ID') }}</label>
                                    <input type="text" name="prescription_id" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Sale Date') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="sale_date" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Total Amount') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="total_amount" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Sale Type') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="sale_type" class="form-control js-select2-custom" required>
                                        <option value="">{{ translate('Select Sale Type') }}</option>
                                        <option value="Internal">Internal</option>
                                        <option value="External">External</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="input-label">{{ translate('Received By') }}</label>
                                    <input type="text" name="received_by" class="form-control">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary">{{ translate('Submit Sale') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

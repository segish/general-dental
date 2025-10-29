@extends('layouts.admin.app')

@section('title', translate('Add Condition Category'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assetsadmin/img/icons/category.png') }}" alt="">
                {{ translate('Add New Condition Category') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.condition_category.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Category Name') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Type') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="type" class="form-control js-select2-custom" required>
                                            <option value="" selected disabled>{{ translate('Select a type') }}
                                            </option>
                                            <option value="Medical Record">Medical Record</option>
                                            <option value="Medical History">Medical History</option>
                                            <option value="Diagnoses">Diagnoses</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Description') }}</label>
                                        <textarea name="description" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Initialize select2
            $('.js-select2-custom').select2();
        });
    </script>
@endpush

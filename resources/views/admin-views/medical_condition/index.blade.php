@extends('layouts.admin.app')

@section('title', translate('Add Medical Condition'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ translate('Add New Medical Condition') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.medical_condition.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Condition Name') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Category') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="category_id" class="form-control js-select2-custom" required>
                                            <option value="" selected disabled>{{ translate('Select a category') }}
                                            </option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Code') }}</label>
                                        <input type="text" name="code" class="form-control"
                                            placeholder="{{ translate('Optional unique code') }}">
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

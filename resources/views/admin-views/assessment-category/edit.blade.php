@extends('layouts.admin.app')

@section('title', translate('Edit Assessment Category'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/category.png') }}" alt="">
                {{ translate('Edit Assessment Category') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.assessment-categories.update', $category->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Name') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $category->name }}"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Category Type') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="category_type" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>{{ translate('Select Category Type') }}
                                        </option>
                                        <option value="Vital Sign"
                                            {{ $category->category_type == 'Vital Sign' ? 'selected' : '' }}>
                                            {{ translate('Vital Sign') }}
                                        </option>
                                        <option value="Physical Tests"
                                            {{ $category->category_type == 'Physical Tests' ? 'selected' : '' }}>
                                            {{ translate('Physical Tests') }}
                                        </option>
                                        <option value="Physical Tests"
                                            {{ $category->category_type == 'Labour Followup' ? 'selected' : '' }}>
                                            {{ translate('Labour Followup') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Unit') }}</label>
                                    <select name="unit_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>{{ translate('Select Unit') }}</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ $category->unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
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

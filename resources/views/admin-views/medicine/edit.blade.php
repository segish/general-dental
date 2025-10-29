@extends('layouts.admin.app')

@section('title', translate('Update Medicine'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/medicine.png') }}"
                    alt="">
                {{ translate('Update Medicine') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.medicines.update', $medicine->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Medicine Code') }}</label>
                                    <input type="text" name="code" class="form-control"
                                        value="{{ $medicine->code }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Medicine Name') }}</label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ $medicine->name }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Category') }}</label>
                                    <select name="category_id" class="form-control js-select2-custom">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $medicine->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Status') }}</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $medicine->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $medicine->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="input-label">{{ translate('Description') }}</label>
                                    <textarea name="description" class="form-control">{{ $medicine->description }}</textarea>
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

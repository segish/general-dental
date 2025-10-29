@extends('layouts.admin.app')

@section('title', translate('Update Inclinic Item Category'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/category.png') }}"
                    alt="">
                {{ translate('Update Inclinic Item Category') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.emergency_medicine_categories.update', $category->id) }}"
                            method="post">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Category Name') }}</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="{{ translate('New Category Name') }}" required
                                            value="{{ $category->name }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Description') }}</label>
                                        <textarea name="description" class="form-control" placeholder="{{ translate('Category Description') }}">{{ $category->description }}</textarea>
                                    </div>
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

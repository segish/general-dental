@extends('layouts.admin.app')

@section('title', translate('Medicines'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/medicine.png') }}" alt="">
                {{ translate('Medicines') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.medicines.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Medicine Code') }}</label>
                                    <input type="text" name="code" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Medicine Name') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Category') }}</label>
                                    <select name="category_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>Select category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Status') }}</label>
                                    <select name="status" class="form-control js-select2-custom" value="active">
                                        <option value="" selected disabled>Select status</option>
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">InActive</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="input-label">{{ translate('Description') }}</label>
                                    <textarea name="description" class="form-control"></textarea>
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

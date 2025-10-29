@extends('layouts.admin.app')

@section('title', translate('Edit Condition Category'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assetsadmin/img/icons/category.png') }}" alt="">
                {{ translate('Edit Condition Category') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.condition_category.update', $category->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Category Name') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $category->name }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Type') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="type" class="form-control js-select2-custom" required>
                                            <option value="" disabled>{{ translate('Select a type') }}</option>
                                            <option value="Medical Record" {{ $category->type == 'Medical Record' ? 'selected' : '' }}>Medical Record</option>
                                            <option value="Medical History" {{ $category->type == 'Medical History' ? 'selected' : '' }}>Medical History</option>
                                            <option value="Diagnoses" {{ $category->type == 'Diagnoses' ? 'selected' : '' }}>Diagnoses</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Description') }}</label>
                                        <textarea name="description" class="form-control" rows="4">{{ $category->description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('admin.condition_category.list') }}"
                                    class="btn btn-secondary">{{ translate('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
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

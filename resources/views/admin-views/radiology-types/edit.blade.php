@extends('layouts.admin.app')

@section('title', translate('Update Radiology'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset(config('app.asset_path') . '/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/product.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('update_radiology') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.radiology.update', $radiology->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Radiology Name') }}</label>
                                        <input type="text" name="radiology_name" class="form-control"
                                            placeholder="{{ translate('New Radiology Name') }}" required
                                            value="{{ $radiology->radiology_name }}">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Title') }}</label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="{{ translate('Enter Title') }}" required
                                            value="{{ $radiology->title }}">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Cost') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="cost" class="form-control"
                                            placeholder="{{ translate('New radiology cost') }}" required
                                            value="{{ $radiology->cost }}" min="0" step="0.01">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Time Taken (Hour)') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="time_taken_hour" class="form-control"
                                            placeholder="{{ translate('Hours') }}" required
                                            value="{{ $radiology->time_taken_hour }}" min="0" max="23">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Time Taken (Minutes)') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="time_taken_min" class="form-control"
                                            placeholder="{{ translate('Minutes') }}" required
                                            value="{{ $radiology->time_taken_min }}" min="0" max="59">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Paper Size') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="paper_size" class="form-control js-select2-custom" required>
                                            <option value="A4" {{ $radiology->paper_size == 'A4' ? 'selected' : '' }}>
                                                {{ translate('A4') }}
                                            </option>
                                            <option value="A5" {{ $radiology->paper_size == 'A5' ? 'selected' : '' }}>
                                                {{ translate('A5') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Paper Orientation') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="paper_orientation" class="form-control js-select2-custom" required>
                                            <option value="portrait"
                                                {{ $radiology->paper_orientation == 'portrait' ? 'selected' : '' }}>
                                                {{ translate('Portrait') }}
                                            </option>
                                            <option value="landscape"
                                                {{ $radiology->paper_orientation == 'landscape' ? 'selected' : '' }}>
                                                {{ translate('Landscape') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Is Inhouse') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="is_inhouse" class="form-control js-select2-custom" required>
                                            <option value="1" {{ $radiology->is_inhouse == 1 ? 'selected' : '' }}>
                                                {{ translate('Yes') }}
                                            </option>
                                            <option value="0" {{ $radiology->is_inhouse == 0 ? 'selected' : '' }}>
                                                {{ translate('No') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="is_active">{{ \App\CentralLogics\translate('Is Active') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="is_active" class="form-control js-select2-custom" required>
                                            <option value="1" {{ $radiology->is_active == 1 ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('Yes') }}</option>
                                            <option value="0" {{ $radiology->is_active == 0 ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\translate('No') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Description') }}</label>
                                        <textarea name="description" class="form-control">{{ $radiology->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Additional Notes') }}</label>
                                        <textarea name="additional_notes" class="ckeditor form-control">{{ $radiology->additional_notes }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
        });
    </script>

    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush

@push('script')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush

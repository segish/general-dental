@extends('layouts.admin.app')

@section('title', translate('Add New Test'))

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
                {{ \App\CentralLogics\translate('add_new_test') }}
            </h2>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.service.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('test_name') }}</label>
                                    <input type="text" name="test_name" class="form-control"
                                        placeholder="{{ translate('New Test') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="password">{{ \App\CentralLogics\translate('Category') }}</label>
                                    <select name="test_category_id" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('select category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('cost') }}</label>
                                    <input type="number" name="cost" class="form-control"
                                        placeholder="{{ translate('Cost') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Time Taken') }}</label>
                                    <input type="text" name="time_taken" class="form-control"
                                        placeholder="{{ translate('30 miniute') }}" required>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ \App\CentralLogics\translate('description') }}</label>
                                        <div class="form-group">
                                            <textarea name="description" class="ckeditor form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset"
                                    class="btn btn-secondary">{{ \App\CentralLogics\translate('reset') }}</button>
                                <button type="submit"
                                    class="btn btn-primary">{{ \App\CentralLogics\translate('Submit') }}</button>
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

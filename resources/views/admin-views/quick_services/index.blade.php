@extends('layouts.admin.app')

@section('title', translate('Add new nurse'))

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
                {{ \App\CentralLogics\translate('add_new_nurse') }}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">

                <form action="{{ route('admin.nurse.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div id="from_part_2">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="f_name">{{ \App\CentralLogics\translate('first_name') }}</label>
                                            <input type="text" name="f_name" class="form-control"
                                                placeholder="{{ translate('Ex : JOHN') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="l_name">{{ \App\CentralLogics\translate('last_name') }}</label>
                                            <input type="text" name="l_name" class="form-control"
                                                placeholder="{{ translate('Ex : Doe') }}" required>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="phone">{{ \App\CentralLogics\translate('phone_number') }}</label>
                                            <input type="text" name="phone" class="form-control"
                                                placeholder="{{ translate('Ex : 09xxxxxxxx') }}" required>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="email">{{ \App\CentralLogics\translate('email') }}</label>
                                            <input type="text" name="email" class="form-control"
                                                placeholder="{{ translate('Ex : example@gmail.com') }}" required>
                                        </div>
                                    </div>



                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="password">{{ \App\CentralLogics\translate('password') }}</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="{{ translate('') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="password">{{ \App\CentralLogics\translate('role') }}</label>
                                            <select name="roles[]" class="form-control js-select2-custom" multiple required>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- <div class="form-group">
                                            <strong>Role:</strong>
                                            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
                                        </div> --}}
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="experience">{{ \App\CentralLogics\translate('experience') }}</label>
                                            <input type="number" name="experience" class="form-control"
                                                placeholder="{{ translate('Ex : 23') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="gender">{{ \App\CentralLogics\translate('gender') }}</label>
                                            <select name="gender" class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Gender') }}</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="password">{{ \App\CentralLogics\translate('Department') }}</label>
                                            <select name="department_id" class="form-control js-select2-custom" required>
                                                @foreach ($departments as $dep)
                                                    <option value="" selected disabled>
                                                        {{ \App\CentralLogics\translate('Select Department') }}</option>
                                                    <option value="{{ $dep->id }}">{{ $dep->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- <div class="form-group">
                                            <strong>Role:</strong>
                                            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
                                        </div> --}}
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ \App\CentralLogics\translate('about') }}</label>
                                            <div class="form-group">
                                                <textarea name="about" class="ckeditor form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-start gap-1">
                                                <label class="mb-0">{{ translate('Image') }}</label>
                                            </div>
                                            <div class="d-flex justify-content-start mt-4">
                                                <div class="upload-file">
                                                    <input type="file" name="image" id="customFileEg1"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                        class="upload-file__input" required>
                                                    <div class="upload-file__img">
                                                        <img width="150" id="viewer"
                                                            src="{{ asset(config('app.asset_path') . '/admin/img/icons/upload_img.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">

                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="reset"
                                            class="btn btn-secondary">{{ \App\CentralLogics\translate('reset') }}</button>
                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CentralLogics\translate('submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>

@endsection

@push('script')
@endpush


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

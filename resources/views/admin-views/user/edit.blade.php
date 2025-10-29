@extends('layouts.admin.app')

@section('title', translate('Update user'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset(config('app.asset_path') . '/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{ \App\CentralLogics\translate('user') }}
                        {{ \App\CentralLogics\translate('update') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="javascript:" method="post" id="user_form" enctype="multipart/form-data">
                    @csrf
                    @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                    @php($language = $language->value ?? null)



                    <div class="row">

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="f_name">{{ \App\CentralLogics\translate('first_name') }}</label>
                                            <input type="text" name="f_name" value="{{ $user->f_name }}"
                                                class="form-control" placeholder="{{ translate('Ex : John') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="l_name">{{ \App\CentralLogics\translate('last_name') }}</label>
                                            <input type="text" name="l_name" value="{{ $user->l_name }}"
                                                class="form-control" placeholder="{{ translate('Ex : Doe') }}" required>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="phone">{{ \App\CentralLogics\translate('phone_number') }}</label>
                                            <input type="text" name="phone" value="{{ $user->phone }}"
                                                class="form-control" placeholder="{{ translate('Ex : 09xxxxxxxx') }}"
                                                required>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="email">{{ \App\CentralLogics\translate('email') }}</label>
                                            <input type="text" name="email" value="{{ $user->email }}"
                                                class="form-control"
                                                placeholder="{{ translate('Ex : example@gmail.com') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="password">{{ \App\CentralLogics\translate('new_password') }}</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="{{ translate('') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="password">{{ \App\CentralLogics\translate('role') }}</label>
                                            <select name="roles[]" class="form-control js-select2-custom" multiple required>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        {{ in_array($role->name, $user->roles->pluck('name')->toArray()) ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="password">{{ \App\CentralLogics\translate('Department') }}</label>
                                            <select name="department_id" class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Department') }}</option>
                                                @foreach ($departments as $dep)
                                                    <option value="{{ $dep->id }}"
                                                        {{ $user->department_id == $dep->id ? 'selected' : '' }}>
                                                        {{ $dep->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="password">{{ \App\CentralLogics\translate('status') }}</label>
                                            <select name="status" class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select status') }}</option>
                                                <option value="1" {{ $user['status'] == 1 ? 'selected' : '' }}>
                                                    {{ \App\CentralLogics\translate('active') }}</option>
                                                <option value="0" {{ $user['status'] == 0 ? 'selected' : '' }}>
                                                    {{ \App\CentralLogics\translate('inActive') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="signature">{{ \App\CentralLogics\translate('Digital Signature') }}</label>
                                            @if ($user->signature)
                                                <div class="mb-2">
                                                    <img src="{{ $user->signature_url }}" alt="Current Signature"
                                                        style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; padding: 5px;">
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ \App\CentralLogics\translate('Current signature') }}</small>
                                                </div>
                                            @endif
                                            <input type="file" name="signature" class="form-control" accept="image/*">
                                            <small
                                                class="text-muted">{{ \App\CentralLogics\translate('Upload a new signature image to replace the current one (PNG, JPG, JPEG)') }}</small>
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


            {{--
                        <div class="form-group">
                            <label>{{\App\CentralLogics\translate('user')}} {{\App\CentralLogics\translate('image')}}</label><small
                                style="color: red">* ( {{\App\CentralLogics\translate('ratio')}} 1:1 )</small>
                            <div>
                                <div class="row mb-3">
                                    @foreach (json_decode($user['image'], true) as $img)
                                        <div class="col-3">
                                            <img style="height: 200px;width: 100%"
                                                 src="{{asset('/storage/app/public/user')}}/{{$img}}">
                                            <a href="{{route('admin.user.remove-image',[$user['id'],$img])}}"
                                               style="margin-top: -35px;border-radius: 0"
                                               class="btn btn-danger btn-block btn-sm">{{translate('Remove')}}</a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row" id="coba"></div>
                            </div>
                        </div> --}}
        </div>
        <hr>
        </form>
    </div>
    </div>
    </div>

@endsection

@push('script')
@endpush

@push('script_2')
    <script src="{{ asset(config('app.asset_path') . '/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        $(".lang_link").click(function(e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == 'en') {
                $("#from_part_2").removeClass('d-none');
            } else {
                $("#from_part_2").addClass('d-none');
            }


        })
    </script>
    <script type="text/javascript">
        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 4,
                rowHeight: '215px',
                groupClassName: 'col-3',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset(config('app.asset_path') . '/admin/img/400x400/img2.jpg') }}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error('{{ translate('Please only input png or jpg type file') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ translate('File size too big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>

    <script>
        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }
    </script>

    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/tags-input.min.js"></script>



    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        @if ($language)
            @foreach (json_decode($language) as $lang)
                var {{ $lang }}_quill = new Quill('#{{ $lang }}_editor', {
                    theme: 'snow'
                });
            @endforeach
        @else
            var en_quill = new Quill('#editor', {
                theme: 'snow'
            });
        @endif

        $('#user_form').on('submit', function() {

            var formData = new FormData(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.user.update', [$user['id']]) }}',
                // data: $('#user_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.errors) {
                        console.log(data);
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        console.log(data);
                        toastr.success('{{ translate('user updated successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.user.list') }}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush

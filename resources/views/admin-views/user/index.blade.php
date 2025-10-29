@extends('layouts.admin.app')

@section('title', translate('Add new User'))

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
                {{ \App\CentralLogics\translate('add_new_user') }}
            </h2>
        </div>
        <div class="row">
            <div class="col-12">

                <form action="javascript:" method="post" id="user_form" enctype="multipart/form-data">
                    @csrf
                    @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = 'bn')
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
                                                for="phone">{{ \App\CentralLogics\translate('phone_number') }}</label>
                                            <input type="text" name="phone" class="form-control"
                                                placeholder="{{ translate('Ex : 09xxxxxxxx') }}" required>
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
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="password">{{ \App\CentralLogics\translate('Department') }}</label>
                                            <select name="department_id" class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Department') }}</option>
                                                @foreach ($departments as $dep)
                                                    <option value="{{ $dep->id }}">{{ $dep->name }}</option>
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
                                                <option value="1">{{ \App\CentralLogics\translate('active') }}
                                                </option>
                                                <option value="0">{{ \App\CentralLogics\translate('inActive') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="signature">{{ \App\CentralLogics\translate('Digital Signature') }}</label>
                                            <input type="file" name="signature" class="form-control" accept="image/*">
                                            <small
                                                class="text-muted">{{ \App\CentralLogics\translate('Upload a scanned image of your signature (PNG, JPG, JPEG)') }}</small>
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
            if (lang == '{{ $default_lang }}') {
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
                groupClassName: 'col-auto',
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


    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/tags-input.min.js"></script>

    <script>
        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append(
                '<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i +
                '"><input type="text" class="form-control" name="choice[]" value="' + n +
                '" placeholder="Choice Title" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' +
                i +
                '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>'
            );
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        function combination_update() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


        }
    </script>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        @if ($language)
            @foreach (json_decode($language) as $lang)
                var en_quill = new Quill('#{{ $lang }}_editor', {
                    theme: 'snow'
                });
            @endforeach
        @else
            var bn_quill = new Quill('#editor', {
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
                url: '{{ route('admin.user.store') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('User Saved successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.user.list') }}';
                        }, 2000);
                    }
                },

                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        // Display each error message using Toastr
                        $.each(errors, function(key, value) {
                            toastr.error(value[0], {
                                closeButton: true,
                                progressBar: true
                            });
                        });
                    } else {
                        toastr.error(
                            '{{ translate('Something went wrong. Please try again later.') }}', {
                                closeButton: true,
                                progressBar: true
                            });
                    }
                }
            });
        });
    </script>

    <script>
        function update_qty() {
            var total_qty = 0;
            var qty_elements = $('input[name^="stock_"]');
            for (var i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if (qty_elements.length > 0) {
                $('input[name="total_stock"]').attr("readonly", true);
                $('input[name="total_stock"]').val(total_qty);
                console.log(total_qty)
            } else {
                $('input[name="total_stock"]').attr("readonly", false);
            }
        }
    </script>
@endpush

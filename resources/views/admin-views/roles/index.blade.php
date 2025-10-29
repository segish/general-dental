@extends('layouts.admin.app')

@section('title', translate('Add new role'))

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
                {{ \App\CentralLogics\translate('add_new_role') }}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">
                <form action="javascript:" method="post" id="role_form" enctype="multipart/form-data">
                    @csrf
                    @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = 'bn')

                    <input type="text" name="guard_name" value="{{ $guard }}" hidden>
                    <div id="from_part_2">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="name">{{ \App\CentralLogics\translate('Role_name') }}</label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="{{ translate('Ex : Manager') }}" required>
                                        </div>
                                    </div>



                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label" for="guard_name">{{\App\CentralLogics\translate('guard')}}</label>
                                            <select name="guard_name" id="guard_name"  class="form-control js-select2-custom" required>
                                                @foreach ($guards as $guardName => $guardConfig)
                                                    <option value="{{ $guardName }}" >{{ ucfirst($guardName) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Permissions:</strong>
                                            <div class="row">
                                                @foreach ($permission as $group => $permissions)
                                                    <div class="col-xs-12 col-sm-12 col-md-12 pb-3">
                                                        <div class="row d-flex justify-content-between align-items-center">
                                                            <h4>
                                                                {{ Form::checkbox('check_all_group[]', $group, false, ['class' => 'check-all-group']) }}
                                                                {{ ucfirst($group) }}
                                                            </h4>
                                                            <button class="btn btn-link toggle-button" type="button"
                                                                data-toggle="collapse" data-target="#{{ $group }}"
                                                                aria-expanded="false" aria-controls="{{ $group }}">
                                                                <i class="toggle-icon tio-add font-weight-bold"></i>
                                                            </button>
                                                        </div>
                                                        <div id="{{ $group }}" class=" row collapse">
                                                            @foreach ($permissions as $value)
                                                                <div class="col-xs-6 col-sm-6 col-md-4">
                                                                    <label>
                                                                        {{ Form::checkbox('permission[]', $value->id, false, ['class' => 'name', 'data-group' => $group]) }}
                                                                        {{ $value->name }}
                                                                    </label>
                                                                    <br />
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <hr />
                                                    </div>
                                                @endforeach
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

@push('script')
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var guardNameSelect = document.getElementById('guard_name');

            guardNameSelect.addEventListener('change', function() {
                var selectedGuard = guardNameSelect.value;
                console.log('Selected Guard:', selectedGuard);



                $.ajax({
                    url: '{{ url('admin/permissions/permission-list') }}',
                    method: 'GET',
                    data: selectedGuard,
                    success: function(data) {
                        console.log('Permissions from Controller:', data);
                    },
                    error: function(error) {
                        console.error('Error fetching permissions:', error);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Add an event listener for the Bootstrap collapse event
            $('.toggle-button').on('click', function() {
                var icon = $('i.toggle-icon', this);

                if ($(this).attr('aria-expanded') === 'false') {
                    icon.removeClass('tio-add').addClass('tio-remove');
                } else {
                    icon.removeClass('tio-remove').addClass('tio-add');
                }
            });
        });
    </script>
    <script>
        // JavaScript to handle "Check All" functionality
        $(document).ready(function() {
            $('.check-all-group').change(function() {
                var group = $(this).val();
                $('input[data-group="' + group + '"]').prop('checked', this.checked);
            });
        });
    </script>

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

    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
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

        $('#role_form').on('submit', function() {

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.roles.store') }}',
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
                        toastr.success('{{ translate('role Saved successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.roles.list') }}';
                        }, 2000);
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

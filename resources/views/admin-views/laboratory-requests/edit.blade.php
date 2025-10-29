@extends('layouts.admin.app')

@section('title', translate('update new Patient'))

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
                {{ \App\CentralLogics\translate('update_patient') }}
            </h2>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Edit Laboratory Request</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.laboratory_request.list') }}">Laboratory Requests</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form id="editLaboratoryRequestForm">
                                        @csrf
                                        <input type="hidden" name="visit_id" value="{{ $laboratoryRequest->visit_id }}">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="requested_by">Requested By</label>
                                                    <select class="form-control" id="requested_by" name="requested_by"
                                                        required>
                                                        <option value="physician"
                                                            {{ $laboratoryRequest->requested_by == 'physician' ? 'selected' : '' }}>
                                                            Physician</option>
                                                        <option value="self"
                                                            {{ $laboratoryRequest->requested_by == 'self' ? 'selected' : '' }}>
                                                            Self</option>
                                                        <option value="other healthcare"
                                                            {{ $laboratoryRequest->requested_by == 'other healthcare' ? 'selected' : '' }}>
                                                            Other Healthcare</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="order_status">Order Status</label>
                                                    <select class="form-control" id="order_status" name="order_status"
                                                        required>
                                                        <option value="urgent"
                                                            {{ $laboratoryRequest->order_status == 'urgent' ? 'selected' : '' }}>
                                                            Urgent</option>
                                                        <option value="routine"
                                                            {{ $laboratoryRequest->order_status == 'routine' ? 'selected' : '' }}>
                                                            Routine</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="fasting">Fasting Required</label>
                                                    <select class="form-control" id="fasting" name="fasting" required>
                                                        <option value="yes"
                                                            {{ $laboratoryRequest->fasting == 'yes' ? 'selected' : '' }}>
                                                            Yes</option>
                                                        <option value="no"
                                                            {{ $laboratoryRequest->fasting == 'no' ? 'selected' : '' }}>No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="collected_by">Collected By</label>
                                                    <select class="form-control" id="collected_by" name="collected_by"
                                                        required>
                                                        @foreach ($admins as $admin)
                                                            <option value="{{ $admin->id }}"
                                                                {{ $laboratoryRequest->collected_by == $admin->id ? 'selected' : '' }}>
                                                                {{ $admin->f_name }} {{ $admin->l_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="referring_dr">Referring Doctor</label>
                                                    <input type="text" class="form-control" id="referring_dr"
                                                        name="referring_dr" value="{{ $laboratoryRequest->referring_dr }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="referring_institution">Referring Institution</label>
                                                    <input type="text" class="form-control" id="referring_institution"
                                                        name="referring_institution"
                                                        value="{{ $laboratoryRequest->referring_institution }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="card_no">Card Number</label>
                                                    <input type="text" class="form-control" id="card_no" name="card_no"
                                                        value="{{ $laboratoryRequest->card_no }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="hospital_ward">Hospital Ward</label>
                                                    <input type="text" class="form-control" id="hospital_ward"
                                                        name="hospital_ward"
                                                        value="{{ $laboratoryRequest->hospital_ward }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="relevant_clinical_data">Relevant Clinical Data</label>
                                                    <textarea class="form-control" id="relevant_clinical_data" name="relevant_clinical_data" rows="3">{{ $laboratoryRequest->relevant_clinical_data }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="current_medication">Current Medication</label>
                                                    <textarea class="form-control" id="current_medication" name="current_medication" rows="3">{{ $laboratoryRequest->current_medication }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Tests</label>
                                                    <select class="form-control select2" id="test_ids" name="test_ids[]"
                                                        multiple="multiple" required>
                                                        @foreach ($tests as $test)
                                                            <option value="{{ $test->id }}"
                                                                {{ in_array($test->id, $laboratoryRequest->tests->pluck('test_id')->toArray()) ? 'selected' : '' }}>
                                                                {{ $test->test_name }} ({{ $test->testCategory->name }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">Update Laboratory
                                                    Request</button>
                                                <a href="{{ route('admin.laboratory_request.list') }}"
                                                    class="btn btn-secondary">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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

        $('#patient_form').on('submit', function() {

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.patient.update', [$patient['id']]) }}',
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
                        toastr.success('{{ translate('Patient Updated successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.patient.list') }}';
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

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Select tests',
                allowClear: true
            });

            // Handle form submission
            $('#editLaboratoryRequestForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin.laboratory_request.update', $laboratoryRequest->id) }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href =
                                "{{ route('admin.laboratory_request.list') }}";
                        }, 2000);
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message ||
                            'Error updating laboratory request');
                    }
                });
            });
        });
    </script>
@endpush

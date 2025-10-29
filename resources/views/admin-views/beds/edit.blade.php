@extends('layouts.admin.app')

@section('title', translate('update new bed'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assets/admin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('update_bed') }}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.bed.update', $bed->id) }}" method="post">
                    {{-- <form action="javascript:" method="post" id="bed_form" enctype="multipart/form-data"> --}}
                    @csrf
                    <div id="from_part_2">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="bed_number">{{ \App\CentralLogics\translate('Bed Name') }}</label>
                                            <input type="text" name="bed_number" class="form-control"
                                                placeholder="{{ translate('') }}" value="{{ $bed->bed_number }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="bed_id">{{ \App\CentralLogics\translate('bed') }}</label>
                                            <select name="ward_id" class="form-control js-select2-custom" required>
                                                @foreach ($wards as $wardOption)
                                                    <option value="{{ $wardOption->id }}"
                                                        {{ $bed->ward_id == $wardOption->id ? 'selected' : '' }}>
                                                        {{ $wardOption->ward_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Price') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="number" name="price" class="form-control"
                                                placeholder="{{ translate('Enter price') }}" value="{{ $bed->price }}"
                                                required min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="status">{{ \App\CentralLogics\translate('Status') }}</label>
                                            <select name="status" class="form-control js-select2-custom" required>
                                                <option value="{{ $bed->status }}" selected>
                                                    {{ $bed->status }}</option>
                                                <option value="available">Available</option>
                                                <option value="occupied">Occupied</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="occupancy_status">{{ \App\CentralLogics\translate('occupancy_status') }}</label>
                                            <select name="occupancy_status" class="form-control js-select2-custom" required>
                                                <option value="{{ $bed->occupancy_status }}" selected>
                                                    {{ $bed->occupancy_status }}</option>
                                                <option value="cleaning">Cleaning</option>
                                                <option value="maintenance">Maintenance</option>
                                                <option value="normal">Normal</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="type">{{ \App\CentralLogics\translate('bed_type') }}</label>
                                            <select name="type" class="form-control js-select2-custom" required>
                                                <option value="{{ $bed->type }}" selected>{{ $bed->type }}
                                                </option>
                                                <option value="regular">Regular Bed</option>
                                                <option value="icu">ICU Bed</option>
                                                <option value="maternity">Maternity Bed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ \App\CentralLogics\translate('notes') }}</label>
                                            <div class="form-group">
                                                <textarea name="additional_notes" class="ckeditor form-control">{{ $bed->additional_notes }}</textarea>
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
    <script src="{{ asset('/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
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
                    image: '{{ asset('/assets/admin/img/400x400/img2.jpg') }}',
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

    use Spatie\Permission\Models\Role;


    <script src="{{ asset('/assets/admin') }}/js/tags-input.min.js"></script>

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
        $('#bed_form').on('submit', function() {

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.bed.update', [$bed['id']]) }}',
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
                        toastr.success('{{ translate('bed Updated successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.bed.list') }}';
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

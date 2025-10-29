@extends('layouts.admin.app')

@section('title', translate('update new ward'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assets/admin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('update_ward') }}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.ward.update', $ward->id) }}" method="post">
                    {{-- <form action="javascript:" method="post" id="ward_form" enctype="multipart/form-data"> --}}
                    @csrf
                    <div id="from_part_2">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="ward_name">{{ \App\CentralLogics\translate('ward_name') }}</label>
                                            <input type="text" name="ward_name" class="form-control"
                                                value="{{ $ward->ward_name }}" placeholder="{{ translate('') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="max_beds_capacity">{{ \App\CentralLogics\translate('max_beds_capacity') }}</label>
                                            <input type="number" name="max_beds_capacity"
                                                value="{{ $ward->max_beds_capacity }}" class="form-control"
                                                placeholder="{{ translate('') }}" required>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ \App\CentralLogics\translate('description') }}</label>
                                            <div class="form-group">
                                                <textarea name="description" class="ckeditor form-control" name="about_us">{{ $ward->description }}</textarea>
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


    <script>
        $('#ward_form').on('submit', function() {

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.ward.update', [$ward['id']]) }}',
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
                        toastr.success('{{ translate('ward Updated successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.ward.list') }}';
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

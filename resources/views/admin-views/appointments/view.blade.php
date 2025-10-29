@extends('layouts.admin.app')

@section('title', translate('Doctor_detail'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset(config('app.asset_path') . '/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3 d-md-flex justify-content-between">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/product.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('doctor_detail') }}
            </h2>
            <div class="d-flex justify-content-sm-end">
                <a href="{{ route('admin.appointment_schedule.add-new', $doctor->id) }}" class="btn btn-primary">
                    <i class="tio-add"></i>
                    {{ \App\CentralLogics\translate('add_appt._schedule') }}
                </a>
            </div>
        </div>


        <div class="row">
            <div class="col-12">

                <form action="{{ route('admin.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div id="from_part_2">
                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="row media">
                                    <div class="col-md-8 media-body">
                                        <h1 class="mb-1 text-dark d-flex">Dr.
                                            {{ $doctor->admin['f_name'] . ' ' . $doctor->admin['l_name'] }}</h1>
                                        <a class=" text-dark d-flex pt-1">Specialization: {{ $doctor->specialization }}</a>
                                        <a class=" text-dark d-flex pt-1">Experience: {{ $doctor->experience }}
                                            Experience</a>
                                        <a class=" text-dark d-flex pt-1">Department: {{ $doctor->department->title }}</a>
                                    </div>

                                    <div class="col-md-4 card card-body">
                                        <div class="media gap-3  align-items-center">
                                            <div class="avatar-circle mr-3" style="width: 5rem; height:5rem">
                                                <img class="img-fit rounded-circle "
                                                    onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img1.jpg') }}'"
                                                    src="{{ $doctor->getImageUrl() }}" alt="Image Description">
                                            </div>
                                            <div class="media-body text-dark">
                                                <div class="">
                                                    {{ $doctor->admin['f_name'] . ' ' . $doctor->admin['l_name'] }}</div>
                                                <a class="text-dark d-flex"
                                                    href="tel:{{ $doctor->admin['phone'] }}"><strong>{{ $doctor->admin['phone'] }}</strong></a>
                                                <a class="text-dark d-flex"
                                                    href="mailto:{{ $doctor->admin['email'] }}">{{ $doctor->admin['email'] }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        {!! $doctor->about !!}
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

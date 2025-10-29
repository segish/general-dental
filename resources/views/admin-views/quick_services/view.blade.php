@extends('layouts.admin.app')

@section('title', translate('quick_service_detail'))

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
                {{ \App\CentralLogics\translate('quick_service_detail') }}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">
                @csrf
                <div id="from_part_2">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row media">
                                <div class="media-body text-dark row">
                                    <div class="col-md-7">
                                        <dl class="row">
                                            <dt class="col-sm-3">Service Name:</dt>
                                            <dd class="col-sm-9">{{ $quick_service->service_name }}</dd>


                                            <dt class="col-sm-3">Patient:</dt>
                                            <dd class="col-sm-9">
                                                @if ($quick_service->patient)
                                                    {{ $quick_service->patient->full_name }}
                                                @elseif($quick_service->patient_name)
                                                    {{ $quick_service->patient_name }}
                                                @else
                                                    Walking Customer
                                                @endif
                                            </dd>



                                            <dt class="col-sm-3">Requested By:</dt>
                                            <dd class="col-sm-9">
                                                @if ($quick_service->requestedBy)
                                                    {{ $quick_service->requestedBy->f_name }}
                                                    {{ $quick_service->requestedBy->l_name }}
                                                @else
                                                    ----
                                                @endif
                                            </dd>

                                            <dt class="col-sm-3">Assigned To:</dt>
                                            <dd class="col-sm-9">
                                                @if ($quick_service->assignedTo)
                                                    {{ $quick_service->assignedTo->f_name }}
                                                    {{ $quick_service->assignedTo->l_name }}
                                                @else
                                                    ----
                                                @endif
                                            </dd>

                                            <dt class="col-sm-3">Status:</dt>
                                            <dd class="col-sm-9">
                                                <span
                                                    style="font-weight:bold; color:
                                                    @if ($quick_service->status == 'pending') red
                                                    @elseif($quick_service->status == 'completed') green @endif;"
                                                    data-toggle="modal" data-target="#updateStatusProgressModal"
                                                    data-quick-service-id="{{ $quick_service->id }}"
                                                    data-current-progress="{{ $quick_service->status }}">
                                                    {{ translate($quick_service->status) }}
                                            </dd>

                                        </dl>
                                    </div>
                                    <div class="col-md-5">
                                        <dt class="col-sm-3">Files</dt>
                                        <div class="media-body text-dark">
                                            <dl class="row">
                                                @if ($quick_service->image_path)
                                                    <dd class="col-sm-9">
                                                        @foreach (json_decode($quick_service->image_path) as $image)
                                                            <a href="{{ asset('/storage/' . $image) }}" download>
                                                                <img src="{{ asset('/storage/' . $image) }}" alt="Image"
                                                                    class="img-thumbnail"
                                                                    style="max-width: 100px; max-height: 100px; margin-right: 5px;">
                                                            </a>
                                                        @endforeach
                                                    </dd>
                                                @endif

                                                @if ($quick_service->doc_path)
                                                    <dd class="col-sm-9">
                                                        <a href="{{ asset('/storage/' . $quick_service->doc_path) }}"
                                                            download id="downloadDocumentBtn">
                                                            <button class="btn btn-primary">Download Document</button>
                                                        </a>
                                                    </dd>
                                                @endif
                                            </dl>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row media">
                                <div class="media-body text-dark row">
                                    @if (auth('admin')->user()->can('lab_technician_dashboard'))
                                        <div class="col-md-6">
                                            @if ($quick_service->testTypes->count() > 0)
                                                <h5>Test Types</h5>
                                                <ul>
                                                    @foreach ($quick_service->testTypes as $item)
                                                        <li>{{ $item->test_name }}</dd>
                                                    @endforeach
                                                </ul>

                                                @if ($quick_service->medicalLabResults->count() > 0)
                                                    <h4 class="underline">Lab Test Result</h4>

                                                    @foreach ($quick_service->medicalLabResults as $item)
                                                        <h5 class="pl-2">
                                                            {{ implode(', ', $item->testTypes->pluck('test_name')->toArray()) }}
                                                        </h5>
                                                        <p class="pl-2">
                                                            {{ $item->result_content }}
                                                        </p>

                                                        @if ($item->image)
                                                            <h5>Files </h5>
                                                            <div style="display: flex; gap: 10px; flex-wrap: wrap;"
                                                                class="pl-2 mb-5">
                                                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                                    @foreach ($item->image as $imageName)
                                                                        <a href="{{ asset('/storage/radiology_results/' . $imageName) }}"
                                                                            target="_blank"
                                                                            data-lightbox="radiology-results"
                                                                            data-title="Radiology Result Image">
                                                                            <img src="{{ asset('/storage/radiology_results/' . $imageName) }}"
                                                                                alt="Image"
                                                                                style="width: 70px; height: 100px; object-fit: cover;">
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <button class="btn btn-success rounded text-nowrap"
                                                    id="add_new_medical_lab_test" type="button" data-toggle="modal"
                                                    data-target="#add-medical_lab_test"
                                                    data-quick-service-id="{{ $quick_service->id }}"
                                                    title="Add quick Lab Test">
                                                    <i class="tio-add"></i>
                                                    {{ translate('test_result') }}
                                                </button>
                                            @endif
                                        </div>
                                    @endif

                                    @if (auth('admin')->user()->can('radiologist_dashboard'))
                                        <div class="col-md-6">
                                            @if ($quick_service->radiologyTypes->count() > 0)
                                                <h5>Radiology Test Types</h5>
                                                <ul>
                                                    @foreach ($quick_service->radiologyTypes as $item)
                                                        <li>{{ $item->radiology_test_name }}</dd>
                                                    @endforeach
                                                </ul>

                                                @if ($quick_service->radiologyTestResults->count() > 0)
                                                    <h4 class="underline">Radiology Result</h4>

                                                    @foreach ($quick_service->radiologyTestResults as $item)
                                                        @if ($item->radiologyTypes)
                                                            <h5 class="pl-2 ">
                                                                {{ implode(', ', $item->radiologyTypes->pluck('radiology_test_name')->toArray()) }}
                                                            </h5>
                                                            <p class="pl-2 ">
                                                                {{ $item->result_content }}
                                                            </p>

                                                            {{-- Display images --}}
                                                            @if ($item->image)
                                                                <h5 class="pl-2 ">Files </h5>
                                                                <div style="display: flex; gap: 10px; flex-wrap: wrap;"
                                                                    class="pl-2 mb-6">
                                                                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                                        @foreach ($item->image as $imageName)
                                                                            <a href="{{ asset('/storage/radiology_results/' . $imageName) }}"
                                                                                target="_blank" data-lightbox="lab-results"
                                                                                data-title="Lab Result Image">
                                                                                <img src="{{ asset('/storage/radiology_results/' . $imageName) }}"
                                                                                    alt="Image"
                                                                                    style="width: 70px; height: 100px; object-fit: cover;">
                                                                            </a>
                                                                        @endforeach
                                                                    </div>

                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <button class="btn btn-success rounded text-nowrap"
                                                    id="add_new_radiology_lab_test" type="button" data-toggle="modal"
                                                    data-target="#add-radiology_lab_test"
                                                    data-quick-service-id="{{ $quick_service->id }}"
                                                    title="Add radiology Lab Test">
                                                    <i class="tio-add"></i>
                                                    {{ translate('radiology_result') }}
                                                </button>
                                            @endif

                                        </div>
                                    @endif



                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add-radiology_lab_test" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add_New_radiology_lab_result') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="radiology_lab_test_form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="quick_service_id">
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="test_name">{{ \App\CentralLogics\translate('test_type') }}</label>
                                    <select name="radiology_type_id[]" id="radiology_name"
                                        class="form-control js-select2-custom" multiple required>

                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('test_result') }}</label>
                                    <div class="form-group">
                                        <textarea name="test_result" class="form-control" required></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="">
                            <div class="mb-2">
                                <label class="text-capitalize">{{ \App\CentralLogics\translate('Attach Photos') }}</label>
                                <small class="text-danger"> * ( {{ \App\CentralLogics\translate('ratio') }} 1:1 )</small>
                            </div>
                            <div class="row" id="coba2"></div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id=""
                                class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add-medical_lab_test" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add_New_medical_lab_test_result') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="medical_lab_test_form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="quick_service_id">
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="test_name">{{ \App\CentralLogics\translate('test_type') }}</label>
                                    <select name="test_type_id[]" id="test_name" class="form-control js-select2-custom"
                                        multiple required>

                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('test_result') }}</label>
                                    <div class="form-group">
                                        <textarea name="test_result" class="form-control" required></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="">
                            <div class="mb-2">
                                <label class="text-capitalize">{{ \App\CentralLogics\translate('Attach Photos') }}</label>
                                <small class="text-danger"> * ( {{ \App\CentralLogics\translate('ratio') }} 1:1 )</small>
                            </div>
                            <div class="row" id="coba"></div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id=""
                                class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateStatusProgressModal" tabindex="-1" role="dialog"
        aria-labelledby="updateStatusProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusProgressModalLabel">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateStatusProgressForm">
                        @csrf
                        <input type="hidden" id="quickServiceIdInput" name="quick_service_id">
                        <div class="form-group">
                            <label for="status">Status Progress</label>
                            <select class="form-control" id="status" name="status">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Progress</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush


@push('script_2')
    <script src="{{ asset(config('app.asset_path') . '/admin/js/spartan-multi-image-picker.js') }}"></script>

    <script>
        $('#updateStatusProgressForm').submit(function(e) {
            e.preventDefault();

            // Perform AJAX request to update the lab test progress
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.quick_service.status') }}', // Replace with your actual route
                data: formData,
                success: function(response) {
                    $('#updateStatusProgressModal').hide();
                    location.reload();
                    toastr.success('{{ translate('Status Updated Successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });

                },
                error: function(error) {
                    // Handle error, show an alert or update the UI as needed
                    console.error(error);
                }
            });
        });

        $('#updateStatusProgressModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var quickServiceId = button.data('quick-service-id');
            var currentProgress = button.data('current-progress');

            var modal = $(this);
            modal.find('#quickServiceIdInput').val(quickServiceId);
            modal.find('#statusProgressInput').val(currentProgress);
        });

        $(document).on('click', '#add_new_radiology_lab_test', function() {
            var quick_serviceID = $(this).data('quick-service-id');
            $('#radiology_lab_test_form input[name="quick_service_id"]').val(quick_serviceID);

            // Make an AJAX request to fetch test types
            $.ajax({
                url: '{{ route('admin.radiologyType.fetch2') }}',
                type: 'GET',
                data: {
                    id: quick_serviceID,
                },
                success: function(data) {
                    $('#radiology_name').html('');
                    data.forEach(function(test) {
                        $('#radiology_name').append('<option value="' + test.id + '">' + test
                            .radiology_test_name + '</option>');
                    });
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });


        $(function() {
            $("#coba2").spartanMultiImagePicker({
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

        $(document).on('click', '#add_new_medical_lab_test', function() {
            var quickServiceId = $(this).data('quick-service-id');
            $('#medical_lab_test_form input[name="quick_service_id"]').val(quickServiceId);

            // Make an AJAX request to fetch test types
            $.ajax({
                url: '{{ route('admin.testType.fetch2') }}',
                type: 'GET',
                data: {
                    id: quickServiceId,
                },
                success: function(data) {
                    $('#test_name').html('');
                    data.forEach(function(test) {
                        $('#test_name').append('<option value="' + test.id + '">' + test
                            .test_name + '</option>');
                    });
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });

        $('#radiology_lab_test_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.quick_service.rad_result') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('Radiology result Saved successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-radiology_lab_test').click();
                    $('#radiology_lab_test_form')[0].reset();
                    $('#slot_id').html('');
                    location.reload();

                    setTimeout(function() {
                        // location.href = '{{ route('admin.patient.list') }}';
                    }, 2000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error, {
                            closeButton: true,
                            progressBar: true
                        });
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
                            });
                    }
                }
            });
        });
        $('#medical_lab_test_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.quick_service.lab_result') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('lab result Saved successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-medical_lab_test').click();
                    $('#medical_lab_test_form')[0].reset();
                    $('#slot_id').html('');
                    location.reload();

                    setTimeout(function() {
                        // location.href = '{{ route('admin.patient.list') }}';
                    }, 2000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error, {
                            closeButton: true,
                            progressBar: true
                        });
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
                            });
                    }
                }
            });
        });

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

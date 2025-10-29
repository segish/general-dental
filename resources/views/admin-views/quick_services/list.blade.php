@extends('layouts.admin.app')

@section('title', translate('quick_services List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/quick_service.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('quick_services_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $quick_services->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4 col-sm-8 col-md-6">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Search by quick_service Name') }}"
                                            aria-label="Search" value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            {{-- @if (auth('admin')->user()->can('quick_servicer.add-new')) --}}
                            <div class="col-lg-8 col-sm-8 col-md-6">
                                <div class="d-flex justify-content-sm-end">
                                    <button class="btn btn-success rounded mx-2 text-nowrap" id="add_test_request"
                                        type="button" data-toggle="modal" data-target="#add-test_request"
                                        title="Add Test Request">
                                        <i class="tio-add"></i>
                                        {{ translate('Test Request') }}

                                    </button>

                                    <button class="btn btn-success rounded text-nowrap" id="add_test_request" type="button"
                                        data-toggle="modal" data-target="#add-other_service" title="Add Other Service">
                                        <i class="tio-add"></i>
                                        {{ translate('Other Services') }}

                                    </button>
                                </div>
                            </div>
                            {{-- @endif --}}
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                    <th>{{ \App\CentralLogics\translate('service name') }}</th>
                                    {{-- <th>{{\App\CentralLogics\translate('Requested By')}}</th> --}}
                                    <th>{{ \App\CentralLogics\translate('Assigned To') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Patient') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Date') }}</th>
                                    <th>{{ \App\CentralLogics\translate('status') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($quick_services as $key => $quick_service)
                                    <tr>
                                        <td>{{ $quick_services->firstitem() + $key }}</td>

                                        <td>{{ $quick_service->service_name }}</td>
                                        {{-- <td>{{$quick_service->requestedBy->f_name}}  {{$quick_service->requestedBy->l_name}}</td> --}}
                                        <td>
                                            @if ($quick_service->assignedTo)
                                                {{ $quick_service->assignedTo->f_name }}
                                                {{ $quick_service->assignedTo->l_name }}
                                            @elseif(!$quick_service->assignedTo)
                                                Radiology/Lab Test
                                            @endif
                                        </td>
                                        <td>
                                            @if ($quick_service->patient)
                                                {{ $quick_service->patient->full_name }}
                                            @elseif($quick_service->patient_name)
                                                {{ $quick_service->patient_name }}
                                            @else
                                                Walking Customer
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($quick_service['created_at'])->format('M d, Y') }}
                                        </td>

                                        <td>
                                            <span
                                                style="font-weight:bold; color:
                                        @if ($quick_service->status == 'pending') red
                                        @elseif($quick_service->status == 'completed') green @endif;"
                                                data-toggle="modal" data-target="#updateStatusProgressModal"
                                                data-quick-service-id="{{ $quick_service->id }}"
                                                data-current-progress="{{ $quick_service->status }}">
                                                {{ translate($quick_service->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                {{-- @if (auth('admin')->user()->can('quick_service.view')) --}}
                                                <a class="btn btn-outline-primary square-btn"
                                                    href="{{ route('admin.quick_service.view', [$quick_service['id']]) }}">
                                                    <i class="tio tio-visible"></i>
                                                </a>
                                                {{-- @endif
                                            @if (auth('admin')->user()->can('quick_service.edit')) --}}

                                                {{-- @endif --}}
                                                @if (auth('admin')->user()->can('quick_service.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('quick_service-{{ $quick_service['id'] }}','{{ \App\CentralLogics\translate('Want to delete this quick_service ?') }}')"><i
                                                            class="tio tio-delete"></i></a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.quick_service.delete', [$quick_service['id']]) }}"
                                                method="post" id="quick_service-{{ $quick_service['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->

                    <!-- Pagination -->
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $quick_services->links() !!}
                        </div>
                    </div>
                    @if (count($quick_services) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
                <!-- End Card -->
            </div>
        </div>


    </div>



    <div class="modal fade" id="add-other_service" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add_other_service') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="other_service_form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="service_name">{{ \App\CentralLogics\translate('Service Name') }}</label>
                                    <input type="text" name="service_name" class="form-control"
                                        placeholder="{{ translate('Service Name') }}" required>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="admin_id">{{ \App\CentralLogics\translate('Assign To') }}</label>
                                    <select name="admin_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>{{ \App\CentralLogics\translate('') }}
                                        </option>
                                        @foreach ($admins as $admin)
                                            <option value="{{ $admin->id }}">{{ $admin->f_name }}
                                                {{ $admin->l_name }}
                                                @foreach ($admin->getRoleNames() as $v)
                                                    ({{ translate($v) }})
                                                @endforeach
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="patient_id">{{ \App\CentralLogics\translate('Patient') }}</label>
                                    <select name="patient_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>{{ \App\CentralLogics\translate('') }}
                                        </option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->full_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="patient_name">{{ \App\CentralLogics\translate('Patient Name') }}</label>
                                    <input type="text" name="patient_name" class="form-control"
                                        placeholder="{{ translate('Patient Name') }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="text-capitalize">{{ \App\CentralLogics\translate('Attach File') }}</label>
                                <input type="file" name="doc" class="form-control">
                            </div>

                        </div>

                        <div class="row" style="display: flex; flex-direction:column;">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label
                                        class="text-capitalize">{{ \App\CentralLogics\translate('Attach Photos') }}</label>
                                    <small class="text-danger"> * ( {{ \App\CentralLogics\translate('ratio') }} 1:1
                                        )</small>
                                </div>
                                <div class="row" id="coba"></div>
                            </div>

                        </div>



                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Additional Note') }}</label>
                                    <div class="form-group">
                                        <textarea name="prescription_content" class="form-control"></textarea>
                                    </div>
                                </div>

                            </div>
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



    <div class="modal fade" id="add-test_request" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add_New_test_request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="test_request_form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="service_name">{{ \App\CentralLogics\translate('Service Name') }}</label>
                                    <input type="text" name="service_name" class="form-control"
                                        placeholder="{{ translate('Service Name') }}" required>
                                </div>
                            </div>


                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="patient_id">{{ \App\CentralLogics\translate('Patient') }}</label>
                                    <select name="patient_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>{{ \App\CentralLogics\translate('') }}
                                        </option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->full_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="patient_name">{{ \App\CentralLogics\translate('Patient Name') }}</label>
                                    <input type="text" name="patient_name" class="form-control"
                                        placeholder="{{ translate('Patient Name') }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="text-capitalize">{{ \App\CentralLogics\translate('Attach File') }}</label>
                                <input type="file" name="doc" class="form-control">
                            </div>

                        </div>

                        <div class="row ml-3">
                            <div class="col-12 ">
                                <div class="form-group">
                                    <div class=" custom-checkbox">

                                        <input type="hidden" name="lab_test_required" value="0">
                                        <!-- Hidden input for 'false' value -->
                                        <input type="checkbox" class="custom-control-input" id="lab_test_required"
                                            name="lab_test_required" value="1"
                                            onchange="toggleLabTestTypeField(this)">
                                        <label class="custom-control-label" for="lab_test_required">
                                            {{ translate('lab test is required') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12" id="labTestTypeField" style="display: none;">
                            <div class="form-group">
                                <label class="input-label"
                                    for="test_type">{{ \App\CentralLogics\translate('test_type') }}</label>
                                <select name="test_types[]" class="form-control js-select2-custom" multiple>
                                    <option value="" disabled>{{ \App\CentralLogics\translate('') }}</option>
                                    @foreach ($testTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->test_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row ml-3">
                            <div class="col-12 ">
                                <div class="form-group">
                                    <div class=" custom-checkbox">

                                        <input type="hidden" name="radiology_is_required" value="0">
                                        <!-- Hidden input for 'false' value -->
                                        <input type="checkbox" class="custom-control-input" id="radiology_is_required"
                                            name="radiology_is_required" value="1"
                                            onchange="toggleRadiologyTestTypeField(this)">
                                        <label class="custom-control-label" for="radiology_is_required">
                                            {{ translate('Radiology is required') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12" id="radiologyTestTypeField" style="display: none;">
                            <div class="form-group">
                                <label class="input-label"
                                    for="radiology_type">{{ \App\CentralLogics\translate('radiology_type') }}</label>
                                <select name="radiology_types[]" class="form-control js-select2-custom" multiple>
                                    <option value="" disabled>{{ \App\CentralLogics\translate('') }}</option>
                                    @foreach ($radiologyTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->radiology_test_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row" style="display: flex; flex-direction:column;">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label
                                        class="text-capitalize">{{ \App\CentralLogics\translate('Attach Photos') }}</label>
                                    <small class="text-danger"> * ( {{ \App\CentralLogics\translate('ratio') }} 1:1
                                        )</small>
                                </div>
                                <div class="row" id="coba2"></div>
                            </div>

                        </div>



                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Additional Note') }}</label>
                                    <div class="form-group">
                                        <textarea name="prescription_content" class="form-control"></textarea>
                                    </div>
                                </div>

                            </div>
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

        $('#test_request_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.quick_service.request_tests') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('Test Request Sent successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-test_request').click();
                    $('#test_request_form')[0].reset();
                    $('#slot_id').html('');
                    location.reload();

                    setTimeout(function() {
                        location.href = '{{ route('admin.quick_service.list') }}';
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

        $('#other_service_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.quick_service.other_services') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('Other Services Sent successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-other_service').click();
                    $('#other_service_form')[0].reset();
                    $('#slot_id').html('');
                    location.reload();

                    setTimeout(function() {
                        location.href = '{{ route('admin.quick_service.list') }}';
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

        function toggleLabTestTypeField(checkbox) {
            var labTestTypeField = document.getElementById('labTestTypeField');
            labTestTypeField.style.display = checkbox.checked ? 'block' : 'none';
        }

        function toggleRadiologyTestTypeField(checkbox) {
            var labTestTypeField = document.getElementById('radiologyTestTypeField');
            labTestTypeField.style.display = checkbox.checked ? 'block' : 'none';
        }
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

        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.quick_service.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush

@extends('layouts.admin.app')

@section('title', translate('patient_detail'))

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
                {{ \App\CentralLogics\translate('patient_detail') }}
            </h2>

            @if (auth('admin')->user()->can('laboratory_request.add-new'))
                <div class="d-flex justify-content-sm-end">
                    <button class="btn btn-success rounded text-nowrap" id="add_new_medical_history" type="button"
                        data-toggle="modal" data-target="#add-laboratory_request" title="Add Appointment">
                        <i class="tio-add"></i>
                        {{ translate('Laboratory Request') }}
                    </button>
                </div>
            @endif

        </div>


        <div class="row">
            <div class="col-12">

                @csrf
                <div id="from_part_2">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row media">
                                <div class="col-md-8 media-body ">
                                    <div class="row gy-2 align-items-center mb-3">
                                        <div class="col-12">
                                            {{-- <form action="{{ url()->current() }}" method="GET">
                                                <div class="input-group">
                                                    <input id="datatableSearch_" type="date" name="search"
                                                        class="form-control" value="{{ $search }}" required
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button type="submit"
                                                            class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}</button>
                                                    </div>
                                                </div>
                                            </form> --}}
                                        </div>
                                    </div>
                                    @foreach ($laboratoryRequests as $key => $laboratoryRequest)
                                        <div class="card mb-3">
                                            <div class="card-header d-flex justify-content-sm-end gap-4">
                                                @if (auth('admin')->user()->can('specimen.add-new'))
                                                    <button class="btn btn-light rounded text-nowrap me-2"
                                                        id="add_new_specimen" type="button" data-toggle="modal"
                                                        data-target="#add-medical_lab_test" data-medical-history-id="1"
                                                        title="Add Specimen" style="border: 1px solid #d3d3d3;">
                                                        <i class="tio-add"></i>
                                                        {{ translate('Specimen') }}
                                                    </button>
                                                @endif

                                                @if (auth('admin')->user()->can('laboratory_result.add-new'))
                                                    <button class="btn btn-light rounded text-nowrap" id="add_new_result"
                                                        type="button" data-toggle="modal" data-target="#add-result_test"
                                                        data-medical-history-id="{{ $laboratoryRequest->id }}"
                                                        title="Add Result" style="border: 1px solid #d3d3d3;">
                                                        <i class="tio-add"></i>
                                                        {{ translate('Result') }}
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="card-header d-flex flex-column align-items-start">
                                                <h5 class="card-title" style="font-weight: bolder; color: #677788;">
                                                    @foreach ($laboratoryRequest->tests as $key => $item)
                                                        <span class="pl-1">
                                                            {{ $item->test_name }}{{ $key < $laboratoryRequest->tests->count() - 1 ? ',' : '' }}
                                                        </span>
                                                    @endforeach
                                                </h5>
                                            </div>

                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <!-- Request Date -->
                                                    <div class="col-md-3 d-flex flex-column align-items-center px-3"
                                                        style="border-right: 1px solid #ddd;">
                                                        <strong>Request Date</strong>
                                                        <p class="m-0">
                                                            {{ $laboratoryRequest->created_at->format('M d, Y') }}</p>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="col-md-3 d-flex flex-column align-items-center px-3"
                                                        style="border-right: 1px solid #ddd;">
                                                        <strong>Status</strong>
                                                        @if ($laboratoryRequest->status === 'pending')
                                                            <p class="m-0" style="color: #FFC107;">Pending</p>
                                                        @elseif ($laboratoryRequest->status === 'in process')
                                                            <p class="m-0" style="color: #17A2B8;">In Process</p>
                                                        @elseif ($laboratoryRequest->status === 'completed')
                                                            <p class="m-0" style="color: #28A745;">Completed</p>
                                                        @elseif ($laboratoryRequest->status === 'rejected')
                                                            <p class="m-0" style="color: #DC3545;">Rejected</p>
                                                        @else
                                                            <p class="m-0" style="color: #6C757D;">-</p>
                                                        @endif

                                                    </div>

                                                    <!-- Collected By -->
                                                    <div class="col-md-3 d-flex flex-column align-items-center px-3"
                                                        style="border-right: 1px solid #ddd;">
                                                        <strong>Collected By</strong>
                                                        <p class="m-0">{{ $laboratoryRequest->collectedBy->f_name }}</p>
                                                    </div>
                                                    <!-- Button -->
                                                    <div class="col-md-3 d-flex justify-content-center">
                                                        <button class="btn btn-link toggle-button" type="button"
                                                            data-toggle="collapse"
                                                            data-target="#symptomsSection{{ $key }}">
                                                            <i class="toggle-icon tio-add font-weight-bold"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="collapse" id="symptomsSection{{ $key }}">

                                                    {{-- <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Investigation
                                                        </legend>
                                                        <div class="row">
                                                            <div class="col-md-12 pl-4 pl-md-12">
                                                                <li>{{ $history->investigation ?? 'No Investigation' }}
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Diagnosis</legend>
                                                        <div class="row">
                                                            <div class="col-md-12 pl-4 pl-md-12">
                                                                <li>{{ $history->diagnosis ?? 'No Diagnosis' }}</li>
                                                            </div>
                                                        </div>
                                                    </fieldset> --}}

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Additional Note
                                                        </legend>
                                                        <div class="row">
                                                            <div class="col-md-12 pl-4 pl-md-12">
                                                                <li>{{ $laboratoryRequest->additional_note ?? 'No Additional Note' }}
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Lab Test</legend>

                                                        {{-- @if (!$history->lab_test_required)
                                                            <p class="pl-2">No Lab Test</p>
                                                        @else
                                                             <div class="row">
                                                                @if (auth('admin')->user()->can('testType.view'))
                                                                    <div class="col-md-6">
                                                                        @if ($history->testTypes->count() > 0)
                                                                            <h5 class="pl-2"
                                                                                style="text-decoration: underline; font-weight:bold">
                                                                                Test Types</h5>
                                                                            <ul>
                                                                                @foreach ($history->testTypes as $item)
                                                                                    <li class="pl-2">
                                                                                        <p>
                                                                                            <span>
                                                                                                {{ $item->test_name }},
                                                                                                Status:
                                                                                            </span>
                                                                                            <span
                                                                                                style="font-weight:bold; color:
                                                                                            @if ($item->pivot->status == 'Pending') red
                                                                                            @elseif($item->pivot->status == 'accepted') blue
                                                                                            @elseif($item->pivot->status == 'Done') green @endif;">
                                                                                                {{ translate($item->pivot->status) }}
                                                                                            </span>

                                                                                            @if ($item->pivot->type || $item->pivot->shade)
                                                                                                @if ($item->pivot->type)
                                                                                                    &nbsp;&nbsp; Type:
                                                                                                    {{ $item->pivot->type }}
                                                                                                @endif

                                                                                                @if ($item->pivot->type && $item->pivot->shade)
                                                                                                    &nbsp;&nbsp;
                                                                                                    <!-- Space between Type and Shade -->
                                                                                                @endif

                                                                                                @if ($item->pivot->shade)
                                                                                                    Shade:
                                                                                                    {{ $item->pivot->shade }}
                                                                                                @endif
                                                                                            @endif
                                                                                        </p>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                                @if (auth('admin')->user()->can('radiologyType.view'))
                                                                    <div class="col-md-6">
                                                                        @if ($history->radiologyTypes->count() > 0)
                                                                            <h5 class="pl-2"
                                                                                style="text-decoration: underline; font-weight:bold">
                                                                                Radiology Test Types</h5>

                                                                            <ul>
                                                                                @foreach ($history->radiologyTypes as $item)
                                                                                    <li class="pl-2">
                                                                                        {{ $item->radiology_test_name }}</>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>





                                                             @if ($history->labResults && $history->labResults->count() > 0)
                                                                @if (auth('admin')->user()->can('lab_result.view'))
                                                                    <h4 class="underline">Lab Result</h4>
                                                                    @foreach ($history->labResults as $item)
                                                                        @if ($item->testTypes)
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
                                                                                    <div
                                                                                        style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                                                        @foreach ($item->image as $imageName)
                                                                                            <a href="{{ asset('storage/assets/lab_results/' . $imageName) }}"
                                                                                                target="_blank"
                                                                                                data-lightbox="lab-results"
                                                                                                data-title="Lab Result Image">
                                                                                                <img src="{{ asset('/storage/lab_results/' . $imageName) }}"
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
                                                            @endif

                                                        @endif --}}
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Pagination -->
                                    <div class="table-responsive mt-4 px-3">
                                        <div class="d-flex justify-content-end">
                                            {!! $laboratoryRequests->links() !!}
                                        </div>
                                    </div>
                                    @if (count($laboratoryRequests) == 0)
                                        <div class="text-center p-4">
                                            <img class="mb-3"
                                                src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                                                alt="Image Description" style="width: 7rem;">
                                            <p class="mb-0">{{ translate('No data to show') }}</p>
                                        </div>
                                    @endif

                                </div>
                                <div class="col-md-4 card card-body">
                                    <!-- Basic Info -->
                                    <div class="media gap-3 align-items-center">
                                        <!-- Avatar -->
                                        <div class="avatar-circle mr-3"
                                            style="position: relative; width: 50px; height: 50px; border-radius: 50%; overflow: hidden; background-color: #24b89b; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white;">
                                            @if ($patient->getImageUrl() && file_exists(public_path($patient->getImageUrl())))
                                                <img class="img-fit rounded-circle" src="{{ $patient->getImageUrl() }}"
                                                    alt="Image Description" style="width: 100%; height: 100%;">
                                            @else
                                                <span style="font-size: 18px;">
                                                    {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                                                    {{ strtoupper(substr(strrchr($patient->full_name, ' '), 1, 1)) }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="media-body text-dark">
                                            <!-- Full Name -->
                                            <div style="font-size: 18px; font-weight: 600; color: #000;">
                                                {{ $patient->full_name }}
                                            </div>
                                            <!-- Phone -->
                                            <a class="d-block" style="font-size: 16px; font-weight: 500; color: #555;"
                                                href="tel:{{ $patient['phone'] }}">
                                                Phone: {{ $patient['phone'] }}
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Detailed Info -->
                                    <div class="mt-4">
                                        <div class="details-container">
                                            <div class="details-row">
                                                <span class="details-label">Registration No:</span>
                                                <span class="details-value">{{ $patient->registration_no }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Gender:</span>
                                                <span
                                                    class="details-value">{{ ucfirst($patient->gender) ?? 'Not Available' }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Age:</span>
                                                <span
                                                    class="details-value">{{ \Carbon\Carbon::parse($patient->date_of_birth)->age ?? 'Not Available' }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Address:</span>
                                                <span
                                                    class="details-value">{{ $patient->address ?? 'Not Available' }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Blood Group:</span>
                                                <span
                                                    class="details-value">{{ $patient->blood_group ?? 'Not Available' }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Marital Status:</span>
                                                <span
                                                    class="details-value">{{ $patient->marital_status ?? 'Not Available' }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Email:</span>
                                                <span
                                                    class="details-value">{{ $patient->email ?? 'Not Available' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-laboratory_request" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add New Laboratory Request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="laboratory_request_form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="patient_id" value="{{ $patient->id }}">
                        <input type="text" hidden name="collected_by" value="{{ auth('admin')->user()->id }}">
                        <input type="text" hidden name="status" value="pending">

                        <div class="row pl-2">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="order_status">{{ \App\CentralLogics\translate('Order Status') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="order_status" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select Order Status') }}</option>
                                        <option value="urgent">Urgent</option>
                                        <option value="routine">routine</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="requested_by">{{ \App\CentralLogics\translate('Requested By') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="requested_by" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select Requested By') }}</option>
                                        <option value="physician">Physician (In-Clinic)</option>
                                        <option value="self">Self</option>
                                        <option value="other healthcare">Other Healthcare</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="fasting">{{ \App\CentralLogics\translate('Fasting Status') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="fasting" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select Fasting Status') }}</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="referring_dr">{{ \App\CentralLogics\translate('Referring Doctor') }}</label>
                                    <input type="text" name="referring_dr" class="form-control"
                                        placeholder="{{ translate('enter referring doctor') }}">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="referring_institution">{{ \App\CentralLogics\translate('Referring Institution') }}</label>
                                    <input type="text" name="referring_institution" class="form-control"
                                        placeholder="{{ translate('enter referring institution') }}">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="card_no">{{ \App\CentralLogics\translate('Card Number') }}</label>
                                    <input type="text" name="card_no" class="form-control"
                                        placeholder="{{ translate('enter card number') }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Test Type') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="sample_type_id" id="sample_type_id"
                                        class="form-control js-select2-custom" multiple required>
                                        @foreach ($tests as $test)
                                            <option value="{{ $test->id }}">{{ $test->test_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="relevant_clinical_data">{{ \App\CentralLogics\translate('Relevant Clinical Data') }}</label>
                                    <div class="form-group">
                                        <textarea name="relevant_clinical_data" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="current_medication">{{ \App\CentralLogics\translate('Current Medication') }}</label>
                                    <div class="form-group">
                                        <textarea name="current_medication" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Additional Note') }}</label>
                                    <div class="form-group">
                                        <textarea name="additional_note" class="form-control"></textarea>
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

    <div class="modal fade" id="add-medical_lab_test" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('add_new_specimen') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="medical_lab_test_form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="medical_history_id">
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="test_name">{{ \App\CentralLogics\translate('Specimen Type') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="specimen_type_id" id="specimen_type_id"
                                        class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>Select specimen type</option>
                                        @foreach ($specimenTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="test_name">{{ \App\CentralLogics\translate('Specimen Origin') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="specimen_origin_id" id="specimen_origin_id"
                                        class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>Select specimen origin</option>
                                        @foreach ($specimenOrigins as $origin)
                                            <option value="{{ $origin->id }}">{{ $origin->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="specimen_code">{{ \App\CentralLogics\translate('Specimen Code') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <div class="form-group">
                                        <input type="text" name="specimen_code" class="form-control"
                                            placeholder="{{ translate('Enter specimen code') }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="specimen_taken_at">{{ \App\CentralLogics\translate('Specimen Taken At') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <div class="form-group">
                                        <input type="time" name="specimen_taken_at" class="form-control">
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

    <div class="modal fade" id="add-result_test" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('add_new_result') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="radiology_lab_test_form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="medical_history_id">
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

    <div class="modal fade" id="add-prescription" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add New Prescription') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="prescriptionTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="in-clinic-tab" data-toggle="tab" href="#in-clinic"
                                role="tab" aria-controls="in-clinic"
                                aria-selected="true">{{ translate('In-Clinic') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="external-tab" data-toggle="tab" href="#external" role="tab"
                                aria-controls="external" aria-selected="false">{{ translate('External') }}</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    {{-- <div class="tab-content mt-2" id="prescriptionTabContent">
                        <!-- In-Clinic Tab -->
                        <div class="tab-pane fade show active" id="in-clinic" role="tabpanel"
                            aria-labelledby="in-clinic-tab">
                            <form action="javascript:" method="post" id="prescription_form"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" hidden name="patient_id" value="{{ $patient->id }}">
                                <input type="text" hidden name="medical_history_id">

                                <div class="form-group">
                                    <label class="input-label"
                                        for="test_type">{{ \App\CentralLogics\translate('Medicines') }}</label>
                                    <select name="medicines[]" class="form-control js-select2-custom" multiple
                                        id="medicinesSelect" required>
                                        <option value="" disabled>
                                            {{ \App\CentralLogics\translate('Select Medicine') }}</option>
                                        @foreach ($medicines as $med)
                                            @if ($med->quantity > 0 && $med->type == 'internal')
                                                <option value="{{ $med->id }}"
                                                    data-unit-cost="{{ $med->unit_price }}">{{ $med->name }}
                                                    ({{ $med->quantity }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Medicine Cart Table -->
                                <table class="table" id="medicineCartTable" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>Qty</th>
                                            <th>U/Cost</th>
                                            <th>T/Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="medicineCartBody"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" style="text-align: right;">
                                                Total: <span id="subTotal">0.00</span> |
                                                Tax
                                                ({{ \App\Models\BusinessSetting::where('key', 'tax')->first()->value ?? 0 }}
                                                %) : <span id="tax">0.00</span> |
                                                Grand Total: <span id="grandTotal">0.00</span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- Additional Note -->
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
                                    <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                                </div>
                            </form>
                        </div>

                        <!-- External Tab -->
                        <div class="tab-pane fade" id="external" role="tabpanel" aria-labelledby="external-tab">
                            <form action="javascript:" method="post" id="external_prescription_form"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" hidden name="patient_id" value="{{ $patient->id }}">
                                <input type="text" hidden name="medical_history_id">

                                <div class="form-group">
                                    <label class="input-label"
                                        for="test_type">{{ \App\CentralLogics\translate('Select Medicine') }}</label>
                                    <select name="external_medicines[]" class="form-control js-select2-custom" multiple
                                        id="externalMedicinesSelect" required>
                                        <option value="" disabled>
                                            {{ \App\CentralLogics\translate('Select Medicine') }}</option>
                                        @foreach ($medicines as $med)
                                            @if ($med->type == 'external')
                                                <option value="{{ $med->id }}">{{ $med->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Medicine Cart Table -->
                                <table class="table" id="externalMedicineCartTable" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>Medication Details</th>
                                        </tr>
                                    </thead>
                                    <tbody id="externalMedicineCartBody"></tbody>
                                </table>

                                <!-- Additional Note for External Prescription -->
                                <div class="form-group">
                                    <label class="input-label"
                                        for="externalNote">{{ \App\CentralLogics\translate('Additional Note') }}</label>
                                    <textarea name="external_prescription_content" class="form-control"></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn btn-primary">{{ translate('Submit External') }}</button>
                                </div>
                            </form>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add-laboratory" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add New Laboratory') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="prescriptionTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="lab-test-tab" data-toggle="tab" href="#lab-test"
                                role="tab" aria-controls="lab-test"
                                aria-selected="true">{{ translate('lab_test') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="radiology-tab" data-toggle="tab" href="#radiology" role="tab"
                                aria-controls="radiology" aria-selected="false">{{ translate('Radiology') }}</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-2" id="prescriptionTabContent">
                        <!-- Lab Test Tab -->
                        <div class="tab-pane fade show active" id="lab-test" role="tabpanel"
                            aria-labelledby="lab_test-tab">
                            <form action="javascript:" method="post" id="history_lab_test_form"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" hidden name="medical_history_id">

                                <!-- Radio buttons for test_category -->
                                <div class="form-group">
                                    <label class="input-label">{{ \App\CentralLogics\translate('test_category') }}</label>
                                    <div>
                                        <label>
                                            <input type="radio" name="test_category" value="standard" checked>
                                            {{ \App\CentralLogics\translate('Standard') }}
                                        </label>
                                        <label>
                                            <input type="radio" name="test_category" value="tooth">
                                            {{ \App\CentralLogics\translate('Tooth') }}
                                        </label>
                                    </div>
                                </div>

                                <!-- Select box for test types -->
                                {{-- <div class="form-group">
                                    <label class="input-label"
                                        for="test_type">{{ \App\CentralLogics\translate('test_type') }}</label>
                                    <select name="test_type_id" class="form-control js-select2-custom"
                                        id="test_type_select">
                                        <option value="" disabled>
                                            {{ \App\CentralLogics\translate('Select test type') }}</option>
                                        @foreach ($testTypes as $type)
                                            <option value="{{ $type->id }}"
                                                data-category="{{ $type->test_category }}">
                                                {{ $type->test_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <!-- Additional fields for 'tooth' -->
                                {{-- <div class="form-group" id="additional_fields" style="display: none;">
                                    <label class="input-label"
                                        for="type">{{ \App\CentralLogics\translate('Type') }}</label>
                                    <input type="text" name="type" class="form-control" id="type">

                                    <label class="input-label"
                                        for="unit">{{ \App\CentralLogics\translate('Unit') }}</label>
                                    <input type="text" name="unit" class="form-control" id="unit">

                                    <label class="input-label"
                                        for="shade">{{ \App\CentralLogics\translate('Shade') }}</label>
                                    <input type="text" name="shade" class="form-control" id="shade">
                                </div> --}}

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                                </div>
                            </form>
                        </div>
                        <!-- Radiology Tab -->
                        {{-- <div class="tab-pane fade" id="radiology" role="tabpanel" aria-labelledby="radiology-tab">
                            <form action="javascript:" method="post" id="history_radiology_form"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" hidden name="medical_history_id">

                                <!-- Select box for test types -->
                                <div class="form-group">
                                    <label class="input-label"
                                        for="radiology">{{ \App\CentralLogics\translate('radiology') }}</label>
                                    <select name="radiology_id" class="form-control js-select2-custom"
                                        id="radiology_select">
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select Radiology') }}</option>
                                        @foreach ($radiologyTypes as $type)
                                            <option value="{{ $type->id }}">
                                                {{ $type->radiology_test_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn btn-primary">{{ translate('Submit External') }}</button>
                                </div>
                            </form>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
@endpush
@push('script_2')
    <script src="{{ asset(config('app.asset_path') . '/admin/js/spartan-multi-image-picker.js') }}"></script>

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
    </script>
    <script>
        function loadPdf(pdfUrl) {
            // Load the PDF into the iframe when the modal is triggered
            document.getElementById('pdfIframe').src = pdfUrl;
        }
    </script>
    <script>
        $(document).ready(function() {
            var cart = []; // Array to store selected medicines in the cart
            var externalCart = [];

            function removeFromCart(cartId) {
                var cartItemIndex = findCartItemIndexById(cartId);
                if (cartItemIndex !== -1) {
                    cart.splice(cartItemIndex, 1);

                }
            }

            function removeFromExternalCart(cartId) {
                var cartItemIndex = findExternalCartItemIndexById(cartId);
                if (cartItemIndex !== -1) {
                    externalCart.splice(cartItemIndex, 1);
                }
            }

            function addToCart() {
                var selectedMedicines = $('#medicinesSelect option:selected');
                selectedMedicines.each(function() {
                    var medicineId = $(this).val();

                    // Check if the medicine already exists in the cart
                    var existingCartItemIndex = findCartItemIndexById2(medicineId);


                    if (existingCartItemIndex !== -1) {
                        // If the medicine is already in the cart, increment its quantity
                        // cart[existingCartItemIndex].quantity += 1;
                    } else {
                        // If the medicine is not in the cart, add it with quantity 1
                        cart.push({
                            medicineId: medicineId,
                            quantity: 1,
                            unitCost: parseFloat($(this).data('unit-cost')),
                            medicineName: $(this).text(),
                            cartId: generateCartId()
                        });

                        // Append a new row to the cart with the quantity input field
                        $('#medicineCartBody').append(`
                            <tr>
                                <td style="vertical-align:middle" class="col-4">${$(this).text()}</td>
                                <td style="vertical-align:middle" class="col-2"><input type="number" class="form-control quantity-input" value="1" min="1" data-cart-id="${cart[cart.length - 1].cartId}"></td>
                                <td style="vertical-align:middle" class="col-2">${parseFloat($(this).data('unit-cost')).toFixed(2)}</td>
                                <td style="vertical-align:middle" class="col-3">${parseFloat($(this).data('unit-cost')).toFixed(2)}</td>
                                <td style="vertical-align:middle" class="col-1"><a style="cursor:pointer"  class=" text-danger remove-btn" data-cart-id="${cart[cart.length - 1].cartId}">
                                    <i class="tio tio-delete"></i>
                                </a></td>
                            </tr>
                        `);
                    }
                });
            }

            function addToExternalCart() {
                var selectedMedicines = $('#externalMedicinesSelect option:selected');
                selectedMedicines.each(function() {
                    var medicineId = $(this).val();

                    // Check if the medicine already exists in the cart
                    var existingCartItemIndex = findExternalCartItemIndexById2(medicineId);


                    if (existingCartItemIndex !== -1) {
                        // If the medicine is already in the cart, increment its quantity
                        // cart[existingCartItemIndex].quantity += 1;
                    } else {
                        // If the medicine is not in the cart, add it with quantity 1
                        externalCart.push({
                            medicineId: medicineId,
                            medicineName: $(this).text(),
                            medicineDetail: '',
                            cartId: generateCartId()
                        });

                        // Append a new row to the cart with the quantity input field  <input type="text" class="form-control external-detail-input"></td>
                        $('#externalMedicineCartBody').append(`
                            <tr>
                                <td style="vertical-align:middle" class="col-2">${$(this).text()}</td>
                                <td style="vertical-align:middle" class="col-7"><textarea class="form-control external-detail-input" placeholder="Enter Medication Detail" data-cart-id="${externalCart[externalCart.length - 1].cartId}"></textarea></td>
                                <td style="vertical-align:middle" class="col-1"><a style="cursor:pointer"  class=" text-danger remove-external-btn" data-cart-id="${externalCart[externalCart.length - 1].cartId}">
                                    <i class="tio tio-delete"></i>
                                </a></td>
                            </tr>
                        `);
                    }
                });
            }

            function updateCartQuantity(cartId, newQuantity) {
                var cartItemIndex = findCartItemIndexById(cartId);
                if (cartItemIndex !== -1) {
                    cart[cartItemIndex].quantity = parseInt(newQuantity) || 1;
                }
            }

            function updateExternalCartDetail(cartId, newQuantity) {
                var cartItemIndex = findExternalCartItemIndexById(cartId);
                if (cartItemIndex !== -1) {
                    externalCart[cartItemIndex].medicineDetail = newQuantity;
                }
            }

            function findCartItemIndexById(cartId) {
                for (var i = 0; i < cart.length; i++) {
                    if (cart[i].cartId === cartId) {
                        return i;
                    }
                }
                return -1; // Return -1 if not found
            }

            function findExternalCartItemIndexById(cartId) {
                for (var i = 0; i < externalCart.length; i++) {
                    if (externalCart[i].cartId === cartId) {
                        return i;
                    }
                }
                return -1; // Return -1 if not found
            }

            function findCartItemIndexById2(medicineId) {
                for (var i = 0; i < cart.length; i++) {
                    if (cart[i].medicineId === medicineId) {
                        return i;
                    }
                }
                return -1; // Return -1 if not found
            }

            function findExternalCartItemIndexById2(medicineId) {
                for (var i = 0; i < externalCart.length; i++) {
                    if (externalCart[i].medicineId === medicineId) {
                        return i;
                    }
                }
                return -1; // Return -1 if not found
            }

            function renderCartTable() {
                var medicineCartTable = $('#medicineCartTable');
                var medicineCartBody = $('#medicineCartBody');
                medicineCartBody.empty(); // Clear existing rows

                var subTotal = 0;

                cart.forEach(function(cartItem) {
                    var totalCost = cartItem.quantity * cartItem.unitCost;

                    // Append a new row to the cart with the quantity input field
                    medicineCartBody.append(`
                        <tr>
                            <td style="vertical-align:middle" class="col-4" data-medicine-id="${cartItem.medicineId}">${cartItem.medicineName}</td>
                            <td style="vertical-align:middle" class="col-2"><input type="number" class="form-control quantity-input" value="${cartItem.quantity}" min="1" data-cart-id="${cartItem.cartId}"></td>
                            <td style="vertical-align:middle" class="col-2">${cartItem.unitCost.toFixed(2)}</td>
                            <td style="vertical-align:middle" class="col-3">${totalCost.toFixed(2)}</td>
                            <td style="vertical-align:middle" class="col-1"><a style="cursor:pointer"  class=" text-danger remove-btn" data-cart-id="${cartItem.cartId}">
                                <i class="tio tio-delete"></i>
                                </a></td>
                        </tr>
                    `);

                    // Accumulate sub-total
                    subTotal += totalCost;
                });

                // Display the cart table
                if (cart.length > 0) {
                    medicineCartTable.show();
                } else {
                    medicineCartTable.hide();
                }

                // Calculate tax (5% of sub-total)
                var taxRate = {{ \App\Models\BusinessSetting::where('key', 'tax')->first()->value ?? 0 }};

                var tax = (taxRate / 100) * subTotal;

                // Calculate grand total
                var grandTotal = subTotal + tax;

                // Display values in the tfoot
                $('#subTotal').text(subTotal.toFixed(2));
                $('#tax').text(tax.toFixed(2));
                $('#grandTotal').text(grandTotal.toFixed(2));
            }

            function renderExternalCartTable() {
                var medicineCartTable = $('#externalMedicineCartTable');
                var medicineCartBody = $('#externalMedicineCartBody');
                medicineCartBody.empty(); // Clear existing rows

                externalCart.forEach(function(cartItem) {
                    medicineCartBody.append(`
            <tr>
                <td style="vertical-align:middle" class="col-2" data-medicine-id="${cartItem.medicineId}">${cartItem.medicineName}</td>
                <td style="vertical-align:middle" class="col-8"><textarea class="form-control external-detail-input" placeholder="Enter Medication Detail" data-cart-id="${cartItem.cartId}">${cartItem.medicineDetail}</textarea></td>
                <td style="vertical-align:middle" class="col-1">
                    <a style="cursor:pointer" class="text-danger remove-external-btn" data-cart-id="${cartItem.cartId}">
                        <i class="tio tio-delete"></i>
                    </a>
                </td>
            </tr>
        `);
                });

                if (externalCart.length > 0) {
                    medicineCartTable.show();
                } else {
                    medicineCartTable.hide();
                }
            }

            function generateCartId() {
                return Math.random().toString(36).substr(2, 9);
            }
        });


        function toggleLabTestTypeField(checkbox) {
            var labTestTypeField = document.getElementById('labTestTypeField');
            labTestTypeField.style.display = checkbox.checked ? 'block' : 'none';
        }

        function toggleRadiologyTestTypeField(checkbox) {
            var labTestTypeField = document.getElementById('radiologyTestTypeField');
            labTestTypeField.style.display = checkbox.checked ? 'block' : 'none';
        }
    </script>
    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

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
    <!-- Your JavaScript code -->
    <script>
        $('#laboratory_request_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.laboratory_request.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('medical_history Scheduled successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-laboratory_request').click();
                    $('#laboratory_request_form')[0].reset();
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
    </script>

    <script>
        $('#history_lab_test_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.laboratory_request.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('Labbratory Record Created successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-laboratory').click();
                    $('#history_lab_test_form')[0].reset();
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
    </script>

    <script>
        $('#history_radiology_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.laboratory_request.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('Labbratory Record Created successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-laboratory').click();
                    $('#history_radiology_form')[0].reset();
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
<style>
    .details-container {
        display: grid;
        grid-template-columns: max-content auto;
        row-gap: 20px;
        column-gap: 40px;
    }

    .details-row {
        display: contents;
    }

    .details-label {
        font-size: 14px;
        font-weight: 500;
        color: #888;
        text-align: left;
    }

    .details-value {
        font-size: 14px;
        font-weight: 600;
        color: #555;
    }
</style>

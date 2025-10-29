@extends('layouts.admin.app')

@section('title', translate('patient_detail'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3 d-md-flex justify-content-between">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/assets/admin/img/icons/product.png')}}" alt="">
                {{\App\CentralLogics\translate('patient_detail')}}
            </h2>

            @if(auth('admin')->user()->hasRole('doctor'))
            <div class="d-flex justify-content-sm-end">
                <button class="btn btn-success rounded text-nowrap" id="add_new_medical_history" type="button" data-toggle="modal" data-target="#add-medical_history" title="Add Appointment">
                    <i class="tio-add"></i>
                    {{translate('medical_history')}}
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
                                                <form  action="{{ url()->current()}}" method="GET">
                                                    <div class="input-group">
                                                        <input id="datatableSearch_" type="date" name="search" class="form-control" value="{{ $search }}" required autocomplete="off">
                                                        <div class="input-group-append">
                                                            <button type="submit" class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>



                                        @foreach ($patient_medical_histories as $key => $history)
                                            <div class="card mb-3">
                                                <div class="card-header d-flex justify-content-sm-between">
                                                    <h5 class="card-title ">  {{ $history->title }}</h5>
                                                    @if(auth('admin')->user()->hasRole('lab_technician'))
                                                    <div class="d-flex justify-content-sm-end">
                                                        <button class="btn btn-success rounded text-nowrap" id="add_new_medical_lab_test" type="button" data-toggle="modal" data-target="#add-medical_lab_test" data-medical-history-id="{{ $history->id }}" title="Add Medical Lab Test">
                                                            <i class="tio-add"></i>
                                                            {{ translate('lab_test') }}
                                                        </button>
                                                    </div>
                                                    @endif

                                                    @if(auth('admin')->user()->hasRole('radiologist'))
                                                    <div class="d-flex justify-content-sm-end">
                                                        <button class="btn btn-success rounded text-nowrap" id="add_new_radiology_lab_test" type="button" data-toggle="modal" data-target="#add-radiology_lab_test" data-medical-history-id="{{ $history->id }}" title="Add radiology Lab Test">
                                                            <i class="tio-add"></i>
                                                            {{ translate('radiology_test') }}
                                                        </button>
                                                    </div>
                                                    @endif

                                                    @if(auth('admin')->user()->hasRole('doctor'))
                                                    <div class="d-flex justify-content-sm-end">
                                                        <button class="btn btn-success rounded text-nowrap" id="add_new_prescription" type="button" data-toggle="modal" data-target="#add-prescription" data-medical-history-id="{{ $history->id }}" title="Add Prescription">
                                                            <i class="tio-add"></i>
                                                            {{ translate('prescriptions') }}
                                                        </button>
                                                    </div>
                                                    @endif

                                                </div>
                                                <div class="card-body">

                                                    <div class="row d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <p>Ref By: {{ $history->doctor->admin->f_name }}  {{ $history->doctor->admin->l_name }}</p>
                                                            <p>Date : {{ $history->created_at->toDateString() }}</p>
                                                        </div>

                                                        <button class="btn btn-link toggle-button" type="button" data-toggle="collapse" data-target="#symptomsSection{{ $key }}">
                                                            <i class="toggle-icon tio-add font-weight-bold"></i>

                                                        </button>
                                                    </div>
                                                    <div  class="collapse" id="symptomsSection{{ $key }}">

                                                        <fieldset class="border p-2" style="border-color: green">
                                                            <legend  class="float-none w-auto p-2"  style="font-weight: bold; font-size:17px;">Symptoms</legend>
                                                                <div class="row">

                                                                    @foreach ($history->medicalConditions as $condition)
                                                                        <div class="col-md-6 pl-2 pl-md-5">
                                                                            <li>{{ $condition->condition_name }}</li>
                                                                        </div>
                                                                    @endforeach
                                                                    <p class="pl-2 pl-md-5 pt-1"> {{ $history->history_content }}</p>
                                                                </div>
                                                            </fieldset>

                                                            <fieldset class="border p-2" style="border-color: green">
                                                                <legend  class="float-none w-auto p-2" style="font-weight: bold; font-size:17px;">Lab Test</legend>

                                                                @if(!$history->lab_test_required)
                                                                <p class="pl-2">No Lab Test</p>
                                                                @else

                                                                <div class="row">
                                                                    @if(auth('admin')->user()->hasRole('lab_technician'))
                                                                        <div class="col-md-6">
                                                                            @if($history->testTypes->count()>0)
                                                                                <h5 class="pl-2" style="text-decoration: underline; font-weight:bold">Test Types</h5>

                                                                                <ul >
                                                                                    @foreach ($history->testTypes as $item)
                                                                                        <li class="pl-2">{{$item->test_name}}</>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                    @if(auth('admin')->user()->hasRole('radiologist'))
                                                                        <div class="col-md-6">
                                                                            @if($history->radiologyTypes->count()>0)
                                                                                <h5 class="pl-2" style="text-decoration: underline; font-weight:bold">Radiology Test Types</h5>

                                                                                <ul >
                                                                                    @foreach ($history->radiologyTypes as $item)
                                                                                        <li class="pl-2">{{$item->radiology_test_name}}</>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                @if(auth('admin')->user()->hasRole('lab_technician'))
                                                                <p class="pl-2">Lab Test Progress:
                                                                    @if(auth('admin')->user()->hasRole('lab_technician'))
                                                                        <span style="font-weight:bold; color:
                                                                            @if($history->lab_test_progress == 'pending') red
                                                                            @elseif($history->lab_test_progress == 'accepted') blue
                                                                            @elseif($history->lab_test_progress == 'done') green
                                                                            @endif;"
                                                                            data-toggle="modal" data-target="#updateLabTestProgressModal"
                                                                            data-medical-history-id="{{ $history->id }}"
                                                                            data-current-progress="{{ $history->lab_test_progress }}">
                                                                            {{ translate($history->lab_test_progress) }}
                                                                        </span>
                                                                    @else
                                                                        <span style="font-weight:bold; color:
                                                                            @if($history->lab_test_progress == 'pending') red
                                                                            @elseif($history->lab_test_progress == 'accepted') blue
                                                                            @elseif($history->lab_test_progress == 'done') green
                                                                            @endif;">
                                                                            {{ translate($history->lab_test_progress) }}
                                                                        </span>
                                                                    @endif
                                                                </p>
                                                                @endif

                                                                @if(auth('admin')->user()->hasRole('radiologist'))
                                                                <p class="pl-2">Radiology Test Progress:
                                                                    @if(auth('admin')->user()->hasRole('radiologist'))
                                                                        <span style="font-weight:bold; color:
                                                                            @if($history->radiology_test_progress == 'pending') red
                                                                            @elseif($history->radiology_test_progress == 'accepted') blue
                                                                            @elseif($history->radiology_test_progress == 'done') green
                                                                            @endif;"
                                                                            data-toggle="modal" data-target="#updateRadiologyTestProgressModal"
                                                                            data-medical-history-id="{{ $history->id }}"
                                                                            data-current-progress="{{ $history->radiology_test_progress }}">
                                                                            {{ translate($history->radiology_test_progress) }}
                                                                        </span>
                                                                    @else
                                                                        <span style="font-weight:bold; color:
                                                                            @if($history->radiology_test_progress == 'pending') red
                                                                            @elseif($history->radiology_test_progress == 'accepted') blue
                                                                            @elseif($history->radiology_test_progress == 'done') green
                                                                            @endif;">
                                                                            {{ translate($history->radiology_test_progress) }}
                                                                        </span>
                                                                    @endif
                                                                </p>
                                                                @endif

                                                                @if(auth('admin')->user()->hasRole('lab_technician'))
                                                                    @if($history->labResults)
                                                                        @foreach ($history->labResults as $item)
                                                                            @if ($item->testTypes)
                                                                                <h5 class="pl-2">
                                                                                    {{ implode(', ', $item->testTypes->pluck('test_name')->toArray()) }}
                                                                                </h5>
                                                                                <p class="pl-2">
                                                                                    {{$item->result_content}}
                                                                                </p>

                                                                                {{-- Display images --}}
                                                                                @if ($item->image)
                                                                                    <h5>Files </h5>
                                                                                    <div style="display: flex; gap: 10px; flex-wrap: wrap;" class="pl-2 mb-5">
                                                                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                                                            @foreach ($item->image as $imageName)
                                                                                                <a href="{{ asset('/storage/lab_results/' . $imageName) }}" target="_blank" data-lightbox="lab-results" data-title="Lab Result Image">
                                                                                                    <img src="{{ asset('/storage/lab_results/' . $imageName) }}" alt="Image" style="width: 70px; height: 100px; object-fit: cover;">
                                                                                                </a>
                                                                                            @endforeach
                                                                                        </div>

                                                                                    </div>

                                                                                @endif
                                                                            @endif

                                                                        @endforeach
                                                                    @endif
                                                                @endif

                                                                @if(auth('admin')->user()->hasRole('radiologist'))
                                                                    @if($history->radiologyLabResults)
                                                                        @foreach ($history->radiologyLabResults as $item)
                                                                            @if ($item->radiologyTypes)
                                                                                <h5 class="pl-2 underline">
                                                                                    {{ implode(', ', $item->radiologyTypes->pluck('radiology_test_name')->toArray()) }}
                                                                                </h5>
                                                                                <p class="pl-2 ">
                                                                                    {{$item->result_content}}
                                                                                </p>

                                                                                {{-- Display images --}}
                                                                                @if ($item->image)
                                                                                    <h5 class="pl-2 ">Files </h5>
                                                                                    <div style="display: flex; gap: 10px; flex-wrap: wrap;" class="pl-2 mb-6">
                                                                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                                                            @foreach ($item->image as $imageName)
                                                                                                <a href="{{ asset('/storage/radiology_results/' . $imageName) }}" target="_blank" data-lightbox="lab-results" data-title="Lab Result Image">
                                                                                                    <img src="{{ asset('/storage/radiology_results/' . $imageName) }}" alt="Image" style="width: 70px; height: 100px; object-fit: cover;">
                                                                                                </a>
                                                                                            @endforeach
                                                                                        </div>

                                                                                    </div>

                                                                                @endif
                                                                            @endif

                                                                        @endforeach
                                                                    @endif
                                                                @endif


                                                                @endif
                                                            </fieldset>




                                                            @if(count($history->prescriptions) > 0)
                                                            <fieldset class="border p-2" style="border-color: green">
                                                                <legend  class="float-none w-auto p-2"  style="font-weight: bold; font-size:17px;">Prescriptions</legend>
                                                                    @if($history->prescriptions)
                                                                        @foreach ($history->prescriptions as $prescription)
                                                                            {{-- Check if billing is available --}}
                                                                            @if ($prescription->billing)
                                                                                {{-- Check if billingDetails is available --}}
                                                                                @if ($prescription->billing->billingDetails)
                                                                                    {{-- Access medicine names --}}
                                                                                    <h4 class="text-decoration:underline">Medicines</h4>
                                                                                    @foreach ($prescription->billing->billingDetails as $billingDetail)
                                                                                        {{-- Check if medicine is available --}}
                                                                                        @if ($billingDetail->medicine)
                                                                                        <ul class="row">
                                                                                            <li>
                                                                                                {{ $billingDetail->medicine->name }} * {{$billingDetail->quantity}}
                                                                                            </li>
                                                                                        </ul>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                    @endif

                                                                    <h4 class="text-decoration:underline">Note</h4>

                                                                        @if($history->prescriptions)
                                                                        @foreach ($history->prescriptions as $item)
                                                                            <p class="pl-2 pl-md-5 pt-1 "> {{ $item->prescription_content }}</p>
                                                                        @endforeach
                                                                        @endif
                                                            </fieldset>
                                                            @endif
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach



                                <!-- Pagination -->
                                <div class="table-responsive mt-4 px-3">
                                    <div class="d-flex justify-content-end">
                                        {!! $patient_medical_histories->links() !!}
                                    </div>
                                </div>
                                @if(count($patient_medical_histories)==0)
                                    <div class="text-center p-4">
                                        <img class="mb-3" src="{{asset('/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                                        <p class="mb-0">{{ translate('No data to show') }}</p>
                                    </div>
                                @endif

                                    </div>

                                    <div class="col-md-4 card card-body">
                                        <div class="media gap-3  align-items-center">
                                            <div class="avatar-circle mr-3" >
                                                <img
                                                    class="img-fit rounded-circle "
                                                    onerror="this.src='{{asset('/assets/admin/img/160x160/img1.jpg')}}'"
                                                    src="{{ $patient->getImageUrl() }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="media-body text-dark">
                                                <div class="">{{$patient->full_name}}</div>
                                                <a class="text-dark d-flex" href="tel:{{$patient['phone']}}"><strong>{{$patient['phone']}}</strong></a>
                                                <a class="text-dark d-flex" href="mailto:{{$patient['email']}}">{{$patient['email']}}</a>
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

    <div class="modal fade" id="add-medical_history" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_New_medical_history')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                    {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}}
                    action="javascript:" method="post" id="medical_history_form"
                    enctype="multipart/form-data"

                    >
                        @csrf
                        <input type="text" hidden name="patient_id" value="{{$patient->id}}">
                        <input type="text" hidden name="doctor_id" value="{{auth('admin')->user()->id}}">
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="title">{{\App\CentralLogics\translate('title')}}</label>
                                    <input type="text"  name="title"
                                        class="form-control"
                                        placeholder="{{ translate('Title') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" style="font-weight: bold !important">{{translate('Do you now or have you ever had:')}}<span class="input-label-secondary text-danger">*</span></label>

                                    <div class="row">
                                    @foreach($medical_histories as $value)
                                       <div class="col-lg-6">
                                            <label>{{ Form::checkbox('medical_conditions[]', $value->id, false, array('class' => 'name')) }}
                                            {{ $value->condition_name }}</label>
                                        </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row ml-3">
                            <div class="col-12 ">
                                <div class="form-group">
                                    <div class=" custom-checkbox">

                                        <input type="hidden" name="lab_test_required" value="0"> <!-- Hidden input for 'false' value -->
                                        <input type="checkbox" class="custom-control-input" id="lab_test_required" name="lab_test_required" value="1"  onchange="toggleLabTestTypeField(this)">
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
                                    for="test_type">{{\App\CentralLogics\translate('test_type')}}</label>
                                    <select name="test_types[]" class="form-control js-select2-custom"   multiple>
                                        <option value=""  disabled>{{ \App\CentralLogics\translate('') }}</option>
                                        @foreach($testTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->test_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>

                        <div class="row ml-3">
                            <div class="col-12 ">
                                <div class="form-group">
                                    <div class=" custom-checkbox">

                                        <input type="hidden" name="radiology_is_required" value="0"> <!-- Hidden input for 'false' value -->
                                        <input type="checkbox" class="custom-control-input" id="radiology_is_required" name="radiology_is_required" value="1"  onchange="toggleRadiologyTestTypeField(this)">
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
                                    for="radiology_type">{{\App\CentralLogics\translate('radiology_type')}}</label>
                                    <select name="radiology_types[]" class="form-control js-select2-custom" multiple>
                                        <option value=""  disabled>{{ \App\CentralLogics\translate('') }}</option>
                                        @foreach($radiologyTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->radiology_test_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>

                        <div class="row ">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('other history')}}</label>
                                    <div class="form-group">
                                        <textarea name="other_history_content"  class="form-control" ></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">{{translate('Submit')}}</button>
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
                    <h5 class="modal-title">{{translate('Add_New_medical_lab_test')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                    {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}}
                    action="javascript:" method="post" id="medical_lab_test_form"
                    enctype="multipart/form-data"

                    >
                        @csrf
                        <input type="text" hidden name="medical_history_id" >
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="test_name">{{\App\CentralLogics\translate('test_type')}}</label>
                                    <select name="test_type_id[]" id="test_name" class="form-control js-select2-custom" multiple required>

                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('test_result')}}</label>
                                    <div class="form-group">
                                        <textarea name="test_result"  class="form-control" ></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="">
                            <div class="mb-2">
                                <label class="text-capitalize">{{\App\CentralLogics\translate('Attach Photos')}}</label>
                                <small class="text-danger"> * ( {{\App\CentralLogics\translate('ratio')}} 1:1 )</small>
                            </div>
                            <div class="row" id="coba"></div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">{{translate('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-radiology_lab_test" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_New_radiology_lab_test')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                    {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}}
                    action="javascript:" method="post" id="radiology_lab_test_form"
                    enctype="multipart/form-data"

                    >
                        @csrf
                        <input type="text" hidden name="medical_history_id" >
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="test_name">{{\App\CentralLogics\translate('test_type')}}</label>
                                    <select name="radiology_type_id[]" id="radiology_name" class="form-control js-select2-custom" multiple required>

                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('test_result')}}</label>
                                    <div class="form-group">
                                        <textarea name="test_result"  class="form-control" ></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="">
                            <div class="mb-2">
                                <label class="text-capitalize">{{\App\CentralLogics\translate('Attach Photos')}}</label>
                                <small class="text-danger"> * ( {{\App\CentralLogics\translate('ratio')}} 1:1 )</small>
                            </div>
                            <div class="row" id="coba2"></div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">{{translate('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add-prescription" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_New_prescription')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                    {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}}
                    action="javascript:" method="post" id="prescription_form"
                    enctype="multipart/form-data"

                    >
                        @csrf
                        <input type="text" hidden name="patient_id" value="{{$patient->id}}">
                        <input type="text" hidden  name="medical_history_id" >

                        <div class="form-group">
                            <label class="input-label" for="test_type">{{\App\CentralLogics\translate('test_type')}}</label>
                            <select name="medicines[]" class="form-control js-select2-custom" multiple id="medicinesSelect">
                                <option value="" disabled>{{ \App\CentralLogics\translate('') }}</option>
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}" data-unit-cost="{{ $med->unit_price }}">{{ $med->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
                                        Tax ({{\App\Model\BusinessSetting::where('key', 'tax')->first()->value??0}} %) : <span id="tax">0.00</span> |
                                        Grand Total: <span id="grandTotal">0.00</span>
                                    </td>
                                </tr>
                                </tfoot>

                        </table>
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('Additional Note')}}</label>
                                    <div class="form-group">
                                        <textarea name="prescription_content"  class="form-control" ></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">{{translate('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateLabTestProgressModal" tabindex="-1" role="dialog" aria-labelledby="updateLabTestProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabTestProgressModalLabel">Update Lab Test Progress</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Include a form to update the lab test progress -->
                    <form id="updateLabTestProgressForm">
                        @csrf
                        <input type="hidden" id="medicalHistoryIdInput" name="medical_history_id">
                        <div class="form-group">
                            <label for="labTestProgressInput">Lab Test Progress</label>
                            <select class="form-control" id="labTestProgressInput" name="lab_test_progress">
                                <option value="pending">Pending</option>
                                <option value="accepted">Accepted</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Progress</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="updateRadiologyTestProgressModal" tabindex="-1" role="dialog" aria-labelledby="updateRadiologyTestProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateRadiologyTestProgressModalLabel">Update Radiology Test Progress</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Include a form to update the lab test progress -->
                    <form id="updateRadiologyTestProgressForm">
                        @csrf
                        <input type="hidden" id="medicalHistoryIdInput" name="medical_history_id">
                        <div class="form-group">
                            <label for="radiologyTestProgressInput">Radiology Test Progress</label>
                            <select class="form-control" id="radiologyTestProgressInput" name="radiology_test_progress">
                                <option value="pending">Pending</option>
                                <option value="accepted">Accepted</option>
                                <option value="done">Done</option>
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
<script src="{{asset('/assets/admin/js/spartan-multi-image-picker.js')}}"></script>

<script type="text/javascript">
    $(function () {
        $("#coba").spartanMultiImagePicker({
            fieldName: 'images[]',
            maxCount: 4,
            rowHeight: '215px',
            groupClassName: 'col-auto',
            maxFileSize: '',
            placeholderImage: {
                image: '{{asset('/assets/admin/img/400x400/img2.jpg')}}',
                width: '100%'
            },
            dropFileLabel: "Drop Here",
            onAddRow: function (index, file) {

            },
            onRenderedPreview: function (index) {

            },
            onRemoveRow: function (index) {

            },
            onExtensionErr: function (index, file) {
                toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{ translate("File size too big") }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });
    });


    $(function () {
        $("#coba2").spartanMultiImagePicker({
            fieldName: 'images[]',
            maxCount: 4,
            rowHeight: '215px',
            groupClassName: 'col-auto',
            maxFileSize: '',
            placeholderImage: {
                image: '{{asset('/assets/admin/img/400x400/img2.jpg')}}',
                width: '100%'
            },
            dropFileLabel: "Drop Here",
            onAddRow: function (index, file) {

            },
            onRenderedPreview: function (index) {

            },
            onRemoveRow: function (index) {

            },
            onExtensionErr: function (index, file) {
                toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{ translate("File size too big") }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });
    });
</script>

 <script>
        $(document).ready(function () {
        var cart = []; // Array to store selected medicines in the cart

        $('#medicinesSelect').change(function () {
            addToCart();
            renderCartTable();
        });

        // Use the change event to capture changes on quantity input fields
        $(document).on('change', '.quantity-input', function () {
            updateCartQuantity($(this).data('cart-id'), $(this).val());
            renderCartTable();
        });
        $(document).on('click', '.remove-btn', function () {
            removeFromCart($(this).data('cart-id'));
            renderCartTable();
        });
        function removeFromCart(cartId) {
        var cartItemIndex = findCartItemIndexById(cartId);
        if (cartItemIndex !== -1) {
            cart.splice(cartItemIndex, 1);
        }
        }
            function addToCart() {
                var selectedMedicines = $('#medicinesSelect option:selected');

                selectedMedicines.each(function () {
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

            function updateCartQuantity(cartId, newQuantity) {
                var cartItemIndex = findCartItemIndexById(cartId);
                if (cartItemIndex !== -1) {
                    cart[cartItemIndex].quantity = parseInt(newQuantity) || 1;
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

            function findCartItemIndexById2(medicineId) {
                for (var i = 0; i < cart.length; i++) {
                    if (cart[i].medicineId === medicineId) {
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

                cart.forEach(function (cartItem) {
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
                var taxRate = {{ \App\Model\BusinessSetting::where('key', 'tax')->first()->value ?? 0 }};

                var tax = (taxRate/100) * subTotal;

                // Calculate grand total
                var grandTotal = subTotal + tax;

                // Display values in the tfoot
                $('#subTotal').text(subTotal.toFixed(2));
                $('#tax').text(tax.toFixed(2));
                $('#grandTotal').text(grandTotal.toFixed(2));
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
    // $(document).ready(function () {
    //     $('#add_new_medical_lab_test').on('click', function () {
    //         var medicalHistoryId = $(this).data('medical-history-id');
    //         $('#medical_lab_test_form input[name="medical_history_id"]').val(medicalHistoryId);
    //     });


    // });
    $(document).on('click', '#add_new_prescription', function () {
        var medicalHistoryId = $(this).data('medical-history-id');
        $('#prescription_form input[name="medical_history_id"]').val(medicalHistoryId);
    });

    $(document).on('click', '#add_new_medical_lab_test', function () {
        var medicalHistoryId = $(this).data('medical-history-id');
        $('#medical_lab_test_form input[name="medical_history_id"]').val(medicalHistoryId);

        // Make an AJAX request to fetch test types
        $.ajax({
            url: '{{ route("admin.testType.fetch") }}',
            type: 'GET',
            data: {
                medicalHistoryId: medicalHistoryId,
            },
            success: function (data) {
                    $('#test_name').html('');
                        data.forEach(function (test) {
                            $('#test_name').append('<option value="' + test.id + '">' + test.test_name + '</option>');
                        });
            },
            error: function (error) {
                console.error(error);
            }
        });
    });


    $(document).on('click', '#add_new_radiology_lab_test', function () {
        var medicalHistoryId = $(this).data('medical-history-id');
        $('#radiology_lab_test_form input[name="medical_history_id"]').val(medicalHistoryId);

        // Make an AJAX request to fetch test types
        $.ajax({
            url: '{{ route("admin.radiologyType.fetch") }}',
            type: 'GET',
            data: {
                medicalHistoryId: medicalHistoryId,
            },
            success: function (data) {
                    $('#radiology_name').html('');
                        data.forEach(function (test) {
                            $('#radiology_name').append('<option value="' + test.id + '">' + test.radiology_test_name + '</option>');
                        });
            },
            error: function (error) {
                console.error(error);
            }
        });
    });




    $('#medical_lab_test_form').on('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route('admin.lab_result.store') }}',
            method: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);

                toastr.success('{{ translate("lab result Saved successfully!") }}', {
                    closeButton: true,
                    progressBar: true
                });
                $('#add-medical_lab_test').click();
                $('#medical_lab_test_form')[0].reset();
                $('#slot_id').html('');
                location.reload();

                setTimeout(function () {
                    // location.href = '{{ route('admin.patient.list') }}';
                }, 2000);
            },
            error: function (xhr, textStatus, errorThrown) {
            if (xhr.responseJSON && xhr.responseJSON.error) {
                toastr.error(xhr.responseJSON.error, {
                    closeButton: true,
                    progressBar: true
                });
            } else {
                toastr.error('{{ translate("An error occurred while processing your request.") }}', {
                    closeButton: true,
                    progressBar: true
                });
            }
            }
        });
    });


    $('#radiology_lab_test_form').on('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route('admin.radiology_result.store') }}',
            method: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);

                toastr.success('{{ translate("Radiology result Saved successfully!") }}', {
                    closeButton: true,
                    progressBar: true
                });
                $('#add-radiology_lab_test').click();
                $('#radiology_lab_test_form')[0].reset();
                $('#slot_id').html('');
                location.reload();

                setTimeout(function () {
                    // location.href = '{{ route('admin.patient.list') }}';
                }, 2000);
            },
            error: function (xhr, textStatus, errorThrown) {
            if (xhr.responseJSON && xhr.responseJSON.error) {
                toastr.error(xhr.responseJSON.error, {
                    closeButton: true,
                    progressBar: true
                });
            } else {
                toastr.error('{{ translate("An error occurred while processing your request.") }}', {
                    closeButton: true,
                    progressBar: true
                });
            }
            }
        });
    });

        // Add a click event listener to the "Add Prescription" button


    // Submit the form when it is submitted
    $('#prescription_form').on('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Get cart data
    var cartData = getCartData();

    // Get other necessary data
    var subTotal = parseFloat($('#subTotal').text());
    var tax = parseFloat($('#tax').text());
    var grandTotal = parseFloat($('#grandTotal').text());

    // Add cart and other data to form data
    var formData = new FormData(this);
    formData.append('cart', JSON.stringify(cartData));
    formData.append('sub_total', subTotal);
    formData.append('tax', tax);
    formData.append('grand_total', grandTotal);

    // Perform the AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{{ route('admin.prescription.store') }}',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);

            toastr.success('{{ translate("Prescription Saved successfully!") }}', {
                closeButton: true,
                progressBar: true
            });

            // Close the modal
            $('#add-prescription').hide();

            // Reset the form
            $('#prescription_form')[0].reset();

            // Reload the page
            location.reload();
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.responseJSON && xhr.responseJSON.error) {
                toastr.error(xhr.responseJSON.error, {
                    closeButton: true,
                    progressBar: true
                });
            } else {
                toastr.error('{{ translate("An error occurred while processing your request.") }}', {
                    closeButton: true,
                    progressBar: true
                });
            }
        }
    });
});

function getCartData() {
    var cartData = [];
    $('#medicineCartBody tr').each(function () {
        var medicineId = $(this).find('td[data-medicine-id]').data('medicine-id');
        var medicineName = $(this).find('td:first-child').text();
        var quantity = parseInt($(this).find('.quantity-input').val()) || 1;
        var unitCost = parseFloat($(this).find('td:nth-child(3)').text());
        var totalCost = parseFloat($(this).find('td:nth-child(4)').text());

        cartData.push({
            medicineId: medicineId,
            medicineName: medicineName,
            quantity: quantity,
            unitCost: unitCost,
            totalCost: totalCost
        });
    });

    return cartData;
}


    $('#updateLabTestProgressModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var medicalHistoryId = button.data('medical-history-id');
        var currentProgress = button.data('current-progress');

        var modal = $(this);
        modal.find('#medicalHistoryIdInput').val(medicalHistoryId);
        modal.find('#labTestProgressInput').val(currentProgress);
    });


    $('#updateRadiologyTestProgressModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var medicalHistoryId = button.data('medical-history-id');
        var currentProgress = button.data('current-progress');

        var modal = $(this);
        modal.find('#medicalHistoryIdInput').val(medicalHistoryId);
        modal.find('#radiologyTestProgressInput').val(currentProgress);
    });

    $('#updateLabTestProgressForm').submit(function (e) {
        e.preventDefault();

        // Perform AJAX request to update the lab test progress
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.lab_result.status') }}', // Replace with your actual route
            data: formData,
            success: function (response) {
                $('#updateLabTestProgressModal').hide();
                location.reload();
                toastr.success('{{ translate("Progress Updated Successfully!") }}', {
                    closeButton: true,
                    progressBar: true
                });

            },
            error: function (error) {
                // Handle error, show an alert or update the UI as needed
                console.error(error);
            }
        });
    });


    $('#updateRadiologyTestProgressForm').submit(function (e) {
        e.preventDefault();

        // Perform AJAX request to update the lab test progress
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.radiology_result.status') }}', // Replace with your actual route
            data: formData,
            success: function (response) {
                $('#updateRadiologyTestProgressModal').hide();
                location.reload();
                toastr.success('{{ translate("Progress Updated Successfully!") }}', {
                    closeButton: true,
                    progressBar: true
                });

            },
            error: function (error) {
                // Handle error, show an alert or update the UI as needed
                console.error(error);
            }
        });
    });
</script>

    <script>

$(document).on('ready', function () {
        $('.js-select2-custom').each(function () {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });
    });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
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
$('#medical_history_form').on('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{{ route('admin.medical_history.store') }}',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);

            toastr.success('{{ translate("medical_history Scheduled successfully!") }}', {
                closeButton: true,
                progressBar: true
            });
            $('#add-medical_history').click();
            $('#medical_history_form')[0].reset();
            $('#slot_id').html('');
            location.reload();

            setTimeout(function () {
                // location.href = '{{ route('admin.patient.list') }}';
            }, 2000);
        },
        error: function (xhr, textStatus, errorThrown) {
        if (xhr.responseJSON && xhr.responseJSON.error) {
            toastr.error(xhr.responseJSON.error, {
                closeButton: true,
                progressBar: true
            });
        } else {
            toastr.error('{{ translate("An error occurred while processing your request.") }}', {
                closeButton: true,
                progressBar: true
            });
        }
        }
    });
});

</script>
<script>
    $('#doctor_id, #day').on('change', function () {
    var doctorId = $('#doctor_id').val();
    var day = $('#day').val();

    // Check if both doctor_id and day are selected
    if (doctorId && day) {
        var url = '{{ route("admin.doctor.appointment_schedule.doctor_list", ["doctor_id" => "doctorIdPlaceholder"]) }}'
            .replace('doctorIdPlaceholder', doctorId);
        console.log('====================================');
        console.log(url);
        console.log('====================================');

        // Make an AJAX request to fetch time slots based on the selected doctor and day
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                doctor_id: doctorId,
                day: day,
            },
            success: function (data) {
                console.log('====================================');
                console.log(data);
                console.log('====================================');

                // Clear the existing options in the slot dropdown
                $('#slot_id').html('');

                // Iterate through each time schedule
                data.time_schedules.forEach(function (timeSchedule) {
                    // Format the start and end times
                    var formattedTime = moment(timeSchedule.start, 'HH:mm:ss').format('h:mm a') + ' - ' + moment(timeSchedule.end, 'HH:mm:ss').format('h:mm a');

                    // Add a separator for each time schedule
                    $('#slot_id').append('<option disabled>' + formattedTime + '</option>');

                    // Iterate through each appointment slot for the current time schedule
                    timeSchedule.appointment_slots.forEach(function (appointmentSlot) {
                        // Format the appointment slot start and end times
                        var formattedSlotTime = moment(appointmentSlot.start_time, 'HH:mm:ss').format('h:mm a') + ' - ' + moment(appointmentSlot.end_time, 'HH:mm:ss').format('h:mm a');

                        // Add the appointment slot as an option in the dropdown
                        $('#slot_id').append('<option value="' + appointmentSlot.id + '">' + formattedSlotTime + '</option>');
                    });
                });
            },
            error: function (error) {
                console.error(error);
            }
        });
    } else {
        // Determine which option is missing and inform the user
        var missingOption = doctorId ? 'Day' : 'Doctor';
        toastr.error('Both Week Day and Doctor Id are Required to get Slots. So Select ' + missingOption );
        }
    });

</script>
<script>

    window.csrf_token = "{{ csrf_token() }}";

    $('#update_status').on('change', function () {
        var appointmentId = $(this).data('appointment-id');
        var status = $(this).val();

        var url = '{{ route("admin.appointment.status", ["appointment_id" => "appointmentIdPlaceholder"]) }}'
            .replace('appointmentIdPlaceholder', appointmentId);

        $.ajax({
            url: url,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': window.csrf_token
            },
            data: {
                status: status,
            },
            success: function (data) {
        toastr.success('Status Updated Successfully ');

                console.log(data)
            },
            error: function (error) {
                console.error(error);
            }
        });
    });
</script>

<script>
    $(document).on('ready', function () {
        $('.js-select2-custom').each(function () {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });
    });
</script>
@endpush
@push('script')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush

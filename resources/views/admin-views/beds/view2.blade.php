@extends('layouts.admin.app')

@section('title', translate('Patient Detail'))

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
                                                                <p>No Lab Test</p>
                                                                @else
                                                                <p>Lab Test Progress:
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


                                                                @if($history->labResults)
                                                                @foreach ($history->labResults as $item)
                                                                    <h5 class="d-flex ">Title : {{$item->test_name}}</h5>
                                                                    <p>
                                                                        {{$item->result_content}}
                                                                    </p>
                                                                @endforeach
                                                                @endif
                                                                @endif
                                                            </fieldset>

                                                            @if(count($history->prescriptions) > 0)
                                                            <fieldset class="border p-2" style="border-color: green">
                                                                <legend  class="float-none w-auto p-2"  style="font-weight: bold; font-size:17px;">Prescriptions</legend>
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


@endsection

@push('script')

@endpush

@push('script_2')
    <script src="{{asset('/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script src="{{asset('/assets/admin')}}/js/tags-input.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>



@endpush



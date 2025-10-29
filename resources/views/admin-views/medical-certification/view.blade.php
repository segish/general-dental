@extends('layouts.admin.app')

@section('title', translate('patient_detail'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/assetsadmin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3 d-md-flex justify-content-between">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assetsadmin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('patient_detail') }}
            </h2>

            @if (auth('admin')->user()->can('medical_history.add-new'))
                <div class="d-flex justify-content-sm-end">
                    <button class="btn btn-success rounded text-nowrap" id="add_new_medical_history" type="button"
                        data-toggle="modal" data-target="#add-medical_history" title="Add Appointment">
                        <i class="tio-add"></i>
                        {{ translate('medical_history') }}
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
                                            <form action="{{ url()->current() }}" method="GET">
                                                <div class="input-group">
                                                    <input id="datatableSearch_" type="date" name="search"
                                                        class="form-control" value="{{ $search }}" required
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button type="submit"
                                                            class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>



                                    @foreach ($patient_medical_histories as $key => $history)
                                        <div class="card mb-3">
                                            <div class="card-header d-flex justify-content-sm-between">
                                                <h5 class="card-title "> {{ $history->chief_complaint }}</h5>
                                                @if (auth('admin')->user()->can('lab_result.add-new'))
                                                    <div class="d-flex justify-content-sm-end">
                                                        <button class="btn btn-success rounded text-nowrap"
                                                            id="add_new_medical_lab_test" type="button" data-toggle="modal"
                                                            data-target="#add-medical_lab_test"
                                                            data-medical-history-id="{{ $history->id }}"
                                                            title="Add Medical Lab Test">
                                                            <i class="tio-add"></i>
                                                            {{ translate('lab_test') }}
                                                        </button>
                                                    </div>
                                                @endif

                                                @if (auth('admin')->user()->can('radiology_result.add-new'))
                                                    <div class="d-flex justify-content-sm-end">
                                                        <button class="btn btn-success rounded text-nowrap"
                                                            id="add_new_radiology_lab_test" type="button"
                                                            data-toggle="modal" data-target="#add-radiology_lab_test"
                                                            data-medical-history-id="{{ $history->id }}"
                                                            title="Add radiology Lab Test">
                                                            <i class="tio-add"></i>
                                                            {{ translate('radiology_test') }}
                                                        </button>
                                                    </div>
                                                @endif

                                                <div class="d-flex">
                                                    @if (auth('admin')->user()->can('medical_history.add-new'))
                                                        <button class="btn btn-success rounded text-nowrap mx-2"
                                                            id="add_new_laboratory" type="button" data-toggle="modal"
                                                            data-target="#add-laboratory"
                                                            data-medical-history-id="{{ $history->id }}"
                                                            title="Add Laboratory">
                                                            <i class="tio-add"></i>
                                                            {{ translate('Laboratory') }}
                                                        </button>
                                                    @endif

                                                    @if (auth('admin')->user()->can('prescription.add-new'))
                                                        <button class="btn btn-success rounded text-nowrap"
                                                            id="add_new_prescription" type="button" data-toggle="modal"
                                                            data-target="#add-prescription"
                                                            data-medical-history-id="{{ $history->id }}"
                                                            title="Add Prescription">
                                                            <i class="tio-add"></i>
                                                            {{ translate('Prescriptions') }}
                                                        </button>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="card-body">

                                                <div class="row d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <p>Ref By: {{ $history->doctor->admin->f_name }}
                                                            {{ $history->doctor->admin->l_name }}</p>
                                                        <p>Date : {{ $history->created_at->toDateString() }}</p>
                                                    </div>

                                                    <button class="btn btn-link toggle-button" type="button"
                                                        data-toggle="collapse"
                                                        data-target="#symptomsSection{{ $key }}">
                                                        <i class="toggle-icon tio-add font-weight-bold"></i>

                                                    </button>
                                                </div>
                                                <div class="collapse" id="symptomsSection{{ $key }}">

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">History of Present
                                                            Illness (HPI)</legend>
                                                        <div class="row">
                                                            <div class="col-md-12 pl-4 pl-md-12">
                                                                <li>{{ $history->hpi }}</li>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Past Medical History
                                                            (PMHx)</legend>
                                                        <div class="row">

                                                            @foreach ($history->medicalConditions->filter(function ($condition) {
                                                                return $condition->type === 'PMhx';
                                                            }) as $condition)
                                                                <div class="col-md-6 pl-2 pl-md-5">
                                                                    <li>{{ $condition->condition_name }}</li>
                                                                </div>
                                                            @endforeach
                                                            <p class="pl-2 pl-md-5 pt-1"> {{ $history->history_content }}
                                                            </p>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Past Dental History
                                                            (PDHx)</legend>
                                                        <div class="row">

                                                            @foreach ($history->medicalConditions->filter(function ($condition) {
                                                                return $condition->type === 'PDhx';
                                                            }) as $condition)
                                                                <div class="col-md-6 pl-2 pl-md-5">
                                                                    <li>{{ $condition->condition_name }}</li>
                                                                </div>
                                                            @endforeach
                                                            <p class="pl-2 pl-md-5 pt-1"> {{ $history->history_content }}
                                                            </p>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Personal History
                                                        </legend>
                                                        <div class="row">
                                                            <div class="col-md-12 pl-4 pl-md-12">
                                                                <li>{{ $history->personal_history ?? 'No personal history' }}
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">General Appearance
                                                        </legend>
                                                        <div class="row">
                                                            <div class="col-md-12 pl-4 pl-md-12">
                                                                <li>{{ $history->general_appearance ?? 'No General Appearance' }}
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Extra-Oral
                                                            Examination</legend>
                                                        <div class="row">
                                                            <div class="col-md-12 pl-4 pl-md-12">
                                                                <li>{{ $history->extra_oral_examination ?? 'No Extra-Oral Examination' }}
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Intra-Oral
                                                            Examination</legend>
                                                        <div class="row">
                                                            <div class="col-md-12 pl-4 pl-md-12">
                                                                <li>{{ $history->intra_oral_examination ?? 'No Intra-Oral Examination' }}
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Assessment</legend>
                                                        <div class="row">
                                                            <div class="col-md-12 pl-4 pl-md-12">
                                                                <li>{{ $history->assessment ?? 'No Assessment' }}</li>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
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
                                                    </fieldset>

                                                    <fieldset class="border p-2" style="border-color: green">
                                                        <legend class="float-none w-auto p-2"
                                                            style="font-weight: bold; font-size:17px;">Lab Test</legend>

                                                        @if (!$history->lab_test_required)
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
                                                                                        {{ $item->test_name }}

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

                                                            @if (auth('admin')->user()->can('lab_result.view'))
                                                                <p class="pl-2">Lab Test Progress:
                                                                    @if (auth('admin')->user()->can('lab_result.status'))
                                                                        <span
                                                                            style="font-weight:bold; color:
                                                                            @if ($history->lab_test_progress == 'pending') red
                                                                            @elseif($history->lab_test_progress == 'accepted') blue
                                                                            @elseif($history->lab_test_progress == 'done') green @endif;"
                                                                            data-toggle="modal"
                                                                            data-target="#updateLabTestProgressModal"
                                                                            data-medical-history-id="{{ $history->id }}"
                                                                            data-current-progress="{{ $history->lab_test_progress }}">
                                                                            {{ translate($history->lab_test_progress) }}
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            style="font-weight:bold; color:
                                                                            @if ($history->lab_test_progress == 'pending') red
                                                                            @elseif($history->lab_test_progress == 'accepted') blue
                                                                            @elseif($history->lab_test_progress == 'done') green @endif;">
                                                                            {{ translate($history->lab_test_progress) }}
                                                                        </span>
                                                                    @endif
                                                                </p>
                                                            @endif

                                                            @if (auth('admin')->user()->can('radiology_result.view'))
                                                                <p class="pl-2">Radiology Test Progress:
                                                                    @if (auth('admin')->user()->can('radiology_result.status'))
                                                                        <span
                                                                            style="font-weight:bold; color:
                                                                            @if ($history->radiology_test_progress == 'pending') red
                                                                            @elseif($history->radiology_test_progress == 'accepted') blue
                                                                            @elseif($history->radiology_test_progress == 'done') green @endif;"
                                                                            data-toggle="modal"
                                                                            data-target="#updateRadiologyTestProgressModal"
                                                                            data-medical-history-id="{{ $history->id }}"
                                                                            data-current-progress="{{ $history->radiology_test_progress }}">
                                                                            {{ translate($history->radiology_test_progress) }}
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            style="font-weight:bold; color:
                                                                            @if ($history->radiology_test_progress == 'pending') red
                                                                            @elseif($history->radiology_test_progress == 'accepted') blue
                                                                            @elseif($history->radiology_test_progress == 'done') green @endif;">
                                                                            {{ translate($history->radiology_test_progress) }}
                                                                        </span>
                                                                    @endif
                                                                </p>
                                                            @endif

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

                                                                            {{-- Display images --}}
                                                                            @if ($item->image)
                                                                                <h5>Files </h5>
                                                                                <div style="display: flex; gap: 10px; flex-wrap: wrap;"
                                                                                    class="pl-2 mb-5">
                                                                                    <div
                                                                                        style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                                                        @foreach ($item->image as $imageName)
                                                                                            <a href="{{ asset('/storage/lab_results/' . $imageName) }}"
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

                                                            @if ($history->radiologyLabResults && $history->radiologyLabResults->count() > 0)
                                                                @if (auth('admin')->user()->can('radiology_result.view'))
                                                                    <h4 class="underline">Radiology Result</h4>

                                                                    @foreach ($history->radiologyLabResults as $item)
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
                                                                                    <div
                                                                                        style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                                                        @foreach ($item->image as $imageName)
                                                                                            <a href="{{ asset('/storage/radiology_results/' . $imageName) }}"
                                                                                                target="_blank"
                                                                                                data-lightbox="lab-results"
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
                                                            @endif


                                                        @endif
                                                    </fieldset>




                                                    @if (count($history->prescriptions) > 0)
                                                        <fieldset class="border p-2" style="border-color: green">
                                                            <legend class="float-none w-auto p-2"
                                                                style="font-weight: bold; font-size:17px;">Prescriptions
                                                            </legend>
                                                            @if ($history->prescriptions)
                                                                @foreach ($history->prescriptions as $prescription)
                                                                    {{-- Check if billing is available --}}
                                                                    @if ($prescription->billing)
                                                                        {{-- Check if billingDetails is available --}}
                                                                        @if ($prescription->billing->billingDetails)
                                                                            {{-- Access medicine names --}}
                                                                            <h4 class="text-decoration:underline">In-Clinic
                                                                                Medicines
                                                                            </h4>
                                                                            @foreach ($prescription->billing->billingDetails as $billingDetail)
                                                                                {{-- Check if medicine is available --}}
                                                                                @if ($billingDetail->medicine)
                                                                                    <ul class="row">
                                                                                        <li>
                                                                                            {{ $billingDetail->medicine->name }}
                                                                                            *
                                                                                            {{ $billingDetail->quantity }}
                                                                                        </li>
                                                                                    </ul>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                        @if ($prescription->prescription_content)
                                                                            <h4 class="text-decoration:underline">Note
                                                                            </h4>
                                                                            <p class="pl-2 pl-md-5 pt-1 ">
                                                                                {{ $prescription->prescription_content }}
                                                                            </p>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif



                                                            @if ($history->prescriptions)
                                                                @foreach ($history->prescriptions as $externalPrescription)
                                                                    @if ($externalPrescription->medicines->isNotEmpty())
                                                                        <h4 class="text-decoration:underline">External
                                                                            Medicines</h4>

                                                                        @foreach ($externalPrescription->medicines as $prescriptionMedicine)
                                                                            <ul class="row">
                                                                                <li>{{ $prescriptionMedicine->name }}</li>
                                                                            </ul>
                                                                        @endforeach


                                                                        <!-- Button to trigger the modal -->
                                                                        @if (auth('admin')->user()->can('prescription.pdf'))
                                                                            <a href="javascript:void(0);"
                                                                                class="btn btn-primary"
                                                                                data-toggle="modal"
                                                                                data-target="#pdfModal"
                                                                                onclick="loadPdf('{{ route('admin.prescription.pdf', $externalPrescription->id) }}')">
                                                                                View Prescription PDF
                                                                            </a>

                                                                            <!-- Modal for displaying PDF -->
                                                                            <div class="modal fade" id="pdfModal"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="pdfModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog modal-lg"
                                                                                    role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="pdfModalLabel">
                                                                                                Prescription PDF</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <!-- Empty iframe that will load the PDF when the modal opens -->
                                                                                            <iframe id="pdfIframe"
                                                                                                width="100%"
                                                                                                height="500px"></iframe>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <!-- Button to download PDF -->
                                                                                            <a href="{{ route('admin.prescription.download', $externalPrescription->id) }}"
                                                                                                class="btn btn-success">Download
                                                                                                PDF</a>
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Close</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    @if ($externalPrescription->prescription_content)
                                                                        <h4 class="text-decoration:underline">Note</h4>
                                                                        <p class="pl-2 pl-md-5 pt-1">
                                                                            {{ $externalPrescription->prescription_content }}
                                                                        </p>
                                                                    @endif
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
                                    @if (count($patient_medical_histories) == 0)
                                        <div class="text-center p-4">
                                            <img class="mb-3"
                                                src="{{ asset('/assetsadmin') }}/svg/illustrations/sorry.svg"
                                                alt="Image Description" style="width: 7rem;">
                                            <p class="mb-0">{{ translate('No data to show') }}</p>
                                        </div>
                                    @endif

                                </div>

                                <div class="col-md-4 card card-body">
                                    <div class="media gap-3  align-items-center">
                                        <div class="avatar-circle mr-3">
                                            <img class="img-fit rounded-circle "
                                                onerror="this.src='{{ asset('/assetsadmin/img/160x160/img1.jpg') }}'"
                                                src="{{ $patient->getImageUrl() }}" alt="Image Description">
                                        </div>
                                        <div class="media-body text-dark">
                                            <div class="">{{ $patient->full_name }}</div>
                                            <a class="text-dark d-flex"
                                                href="tel:{{ $patient['phone'] }}"><strong>{{ $patient['phone'] }}</strong></a>
                                            <a class="text-dark d-flex"
                                                href="mailto:{{ $patient['email'] }}">{{ $patient['email'] }}</a>
                                        </div>
                                    </div>

                                    <div class="media gap-3 align-items-center pt-5"
                                        style="display: flex; flex-direction:column;">
                                        @if ($physical_test)
                                            <div class="media-body text-dark">
                                                <dl class="row">
                                                    <dt class="col-sm-7">Blood Pressure</dt>
                                                    <dd class="col-sm-5">{{ $physical_test->blood_pressure }} mmHg</dd>

                                                    <dt class="col-sm-7">Temperature</dt>
                                                    <dd class="col-sm-5">{{ $physical_test->temperature }} °C</dd>

                                                    <dt class="col-sm-7">Pulse Rate</dt>
                                                    <dd class="col-sm-5">{{ $physical_test->pulse_rate }} bpm</dd>

                                                    <dt class="col-sm-7">Oxygen Saturation</dt>
                                                    <dd class="col-sm-5">{{ $physical_test->oxygen_saturation }} %</dd>

                                                    <dt class="col-sm-7">Weight</dt>
                                                    <dd class="col-sm-5">{{ $physical_test->weight }} kg</dd>

                                                    <dt class="col-sm-7">Height</dt>
                                                    <dd class="col-sm-5">{{ $physical_test->height }} cm</dd>

                                                    <dt class="col-sm-7">Updated</dt>
                                                    <dd class="col-sm-5">
                                                        {{ \Carbon\Carbon::parse($physical_test->updated_at)->format('M d, Y') }}
                                                    </dd>
                                                </dl>
                                            </div>
                                            @if (auth('admin')->user()->can('physical_test.store'))
                                                <button class="btn btn-success rounded text-nowrap"
                                                    id="edit_physical_test" type="button" data-toggle="modal"
                                                    data-target="#edit-physical_test" onclick="populateEditForm()">
                                                    <i class="tio-edit"></i>
                                                    {{ translate('Vital Sign') }}
                                                </button>
                                            @endif
                                        @else
                                            @if (auth('admin')->user()->can('physical_test.update'))
                                                <button class="btn btn-success rounded text-nowrap"
                                                    id="add_new_physical_test" type="button" data-toggle="modal"
                                                    data-target="#add-physical_test"
                                                    data-patient-id="{{ $patient->id }}" title="Add radiology Lab Test">
                                                    <i class="tio-add"></i>
                                                    {{ translate('Vital Sign') }}
                                                </button>
                                            @endif
                                        @endif
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
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add_New_medical_history') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="medical_history_form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="patient_id" value="{{ $patient->id }}">
                        <input type="text" hidden name="doctor_id" value="{{ auth('admin')->user()->id }}">
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="chief_complaint">{{ \App\CentralLogics\translate('Chief Complaint') }}</label>
                                    <input type="text" name="chief_complaint" class="form-control"
                                        placeholder="{{ translate('Chief Complaint') }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('History of Present Illness (HPI)') }}</label>
                                    <div class="form-group">
                                        <textarea name="hpi" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        style="font-weight: bold !important">{{ translate('Past Medical History (PMHx)') }}<span
                                            class="pmhx">*</span></label>

                                    <div class="row">
                                        @foreach ($medical_histories->filter(function ($value) {
            return $value->type === 'PMhx';
        }) as $value)
                                            <div class="col-lg-6">
                                                <label>
                                                    {{ Form::checkbox('medical_conditions_pmhx[]', $value->id, false, ['class' => 'name']) }}
                                                    {{ $value->condition_name }}
                                                </label>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        style="font-weight: bold !important">{{ translate('Past Dental History (PDhx)') }}<span
                                            class="pdhx">*</span></label>

                                    <div class="row">
                                        @foreach ($medical_histories->filter(function ($value) {
            return $value->type === 'PDhx';
        }) as $value)
                                            <div class="col-lg-6">
                                                <label>{{ Form::checkbox('medical_conditions_pdhx[]', $value->id, false, ['class' => 'name']) }}
                                                    {{ $value->condition_name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Personal History') }}</label>
                                    <div class="form-group">
                                        <textarea name="phx" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('General Appearance') }}</label>
                                    <div class="form-group">
                                        <textarea name="ga" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Extra-Oral Examination') }}</label>
                                    <div class="form-group">
                                        <textarea name="eoe" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Intra-Oral Examination') }}</label>
                                    <div class="form-group">
                                        <textarea name="ioe" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Assessment') }}</label>
                                    <div class="form-group">
                                        <textarea name="asst" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Investigations') }}</label>
                                    <div class="form-group">
                                        <textarea name="inv" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('Diagnosis') }}</label>
                                    <div class="form-group">
                                        <textarea name="diag" class="form-control" required></textarea>
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
                    <h5 class="modal-title">{{ translate('Add_New_medical_lab_test') }}</h5>
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
                                <label
                                    class="text-capitalize">{{ \App\CentralLogics\translate('Attach Photos') }}</label>
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

    <div class="modal fade" id="add-radiology_lab_test" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add_New_radiology_lab_test') }}</h5>
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
                                <label
                                    class="text-capitalize">{{ \App\CentralLogics\translate('Attach Photos') }}</label>
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


    <div class="modal fade" id="add-physical_test" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add_New_Physical_Test') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="physical_test_form" enctype="multipart/form-data">
                        @csrf
                        <input type="text" value="{{ $patient->id }}" hidden name="patient_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="blood_pressure">{{ \App\CentralLogics\translate('Blood Pressure') }}
                                        (mmHg)</label>
                                    <input type="text" name="blood_pressure" class="form-control"
                                        placeholder="{{ translate('Blood Pressure') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="temperature">{{ \App\CentralLogics\translate('Temperature') }}
                                        (&deg;C)</label>
                                    <input type="number" name="temperature" class="form-control"
                                        placeholder="{{ translate('Temperature') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="pulse_rate">{{ \App\CentralLogics\translate('Pulse Rate') }} (bpm)</label>
                                    <input type="number" name="pulse_rate" class="form-control"
                                        placeholder="{{ translate('Pulse Rate') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="oxygen_saturation">{{ \App\CentralLogics\translate('Oxygen Saturation') }}
                                        (%)</label>
                                    <input type="number" name="oxygen_saturation" class="form-control"
                                        placeholder="{{ translate('Oxygen Saturation') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="weight">{{ \App\CentralLogics\translate('Weight') }} (kg)</label>
                                    <input type="number" name="weight" class="form-control"
                                        placeholder="{{ translate('Weight') }}" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="height">{{ \App\CentralLogics\translate('Height') }} (cm)</label>
                                    <input type="number" name="height" class="form-control"
                                        placeholder="{{ translate('Height') }}" step="0.01" required>
                                </div>
                            </div>

                            {{-- <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="doctor_id">{{ \App\CentralLogics\translate('Assign Doctor') }}</label>
                                    <select name="doctor_id" class="form-control js-select2-custom" multiple>
                                        <option value="" disabled>{{ \App\CentralLogics\translate('') }}</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->admin->f_name }}
                                                {{ $doctor->admin->l_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
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

    <div class="modal fade" id="edit-physical_test" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('edit_Physical_Test') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="physical_test_form_edit"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="id">
                        <input type="text" hidden name="patient_id">
                        <input type="text" hidden name="nurse_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="blood_pressure">{{ \App\CentralLogics\translate('Blood Pressure') }}
                                        (mmHg)</label>
                                    <input type="text" name="blood_pressure" class="form-control"
                                        placeholder="{{ translate('Blood Pressure') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="temperature">{{ \App\CentralLogics\translate('Temperature') }}
                                        (&deg;C)</label>
                                    <input type="number" name="temperature" class="form-control"
                                        placeholder="{{ translate('Temperature') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="pulse_rate">{{ \App\CentralLogics\translate('Pulse Rate') }} (bpm)</label>
                                    <input type="number" name="pulse_rate" class="form-control"
                                        placeholder="{{ translate('Pulse Rate') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="oxygen_saturation">{{ \App\CentralLogics\translate('Oxygen Saturation') }}
                                        (%)</label>
                                    <input type="number" name="oxygen_saturation" class="form-control"
                                        placeholder="{{ translate('Oxygen Saturation') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="weight">{{ \App\CentralLogics\translate('Weight') }} (kg)</label>
                                    <input type="number" name="weight" class="form-control"
                                        placeholder="{{ translate('Weight') }}" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="height">{{ \App\CentralLogics\translate('Height') }} (cm)</label>
                                    <input type="number" name="height" class="form-control"
                                        placeholder="{{ translate('Height') }}" step="0.01" required>
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
                    <div class="tab-content mt-2" id="prescriptionTabContent">
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
                                                ({{ \App\Model\BusinessSetting::where('key', 'tax')->first()->value ?? 0 }}
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
                    </div>
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
                                    <label
                                        class="input-label">{{ \App\CentralLogics\translate('test_category') }}</label>
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
                                <div class="form-group">
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
                                </div>

                                <!-- Additional fields for 'tooth' -->
                                <div class="form-group" id="additional_fields" style="display: none;">
                                    <label class="input-label"
                                        for="type">{{ \App\CentralLogics\translate('Type') }}</label>
                                    <input type="text" name="type" class="form-control" id="type">

                                    <label class="input-label"
                                        for="shade">{{ \App\CentralLogics\translate('Shade') }}</label>
                                    <input type="text" name="shade" class="form-control" id="shade">
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                                </div>
                            </form>
                        </div>
                        <!-- Radiology Tab -->
                        <div class="tab-pane fade" id="radiology" role="tabpanel" aria-labelledby="radiology-tab">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateLabTestProgressModal" tabindex="-1" role="dialog"
        aria-labelledby="updateLabTestProgressModalLabel" aria-hidden="true">
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



    <div class="modal fade" id="updateRadiologyTestProgressModal" tabindex="-1" role="dialog"
        aria-labelledby="updateRadiologyTestProgressModalLabel" aria-hidden="true">
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
    <script src="{{ asset('/assetsadmin/js/spartan-multi-image-picker.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 4,
                rowHeight: '215px',
                groupClassName: 'col-auto',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('/assetsadmin/img/400x400/img2.jpg') }}',
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
                    image: '{{ asset('/assetsadmin/img/400x400/img2.jpg') }}',
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
        // JavaScript to handle the selection of test_category
        document.querySelectorAll('input[name="test_category"]').forEach((radio) => {
            radio.addEventListener('change', function() {
                const selectedCategory = this.value;
                const testTypeSelect = document.getElementById('test_type_select');
                const additionalFields = document.getElementById('additional_fields');

                // Show relevant test types based on the selected category
                Array.from(testTypeSelect.options).forEach(option => {
                    if (option.value) { // Ignore the disabled option
                        if (option.dataset.category === selectedCategory) {
                            option.style.display = 'block'; // Show matching options
                        } else {
                            option.style.display = 'none'; // Hide non-matching options
                        }
                    }
                });

                // Show additional fields if 'tooth' is selected
                if (selectedCategory === 'tooth') {
                    additionalFields.style.display = 'block';
                } else {
                    additionalFields.style.display = 'none';
                }

                // Reset the selected index if no options are visible
                if (![...testTypeSelect.options].some(option => option.style.display === 'block')) {
                    testTypeSelect.selectedIndex = 0; // Reset selection if no options
                }
            });
        });

        // Trigger change event on load to set initial state
        document.querySelector('input[name="test_category"]:checked').dispatchEvent(new Event('change'));
    </script>
    <script>
        function loadPdf(pdfUrl) {
            // Load the PDF into the iframe when the modal is triggered
            document.getElementById('pdfIframe').src = pdfUrl;
        }
    </script>
    <script>
        // Optional: Add JS to handle the tab functionality (if needed)
        document.addEventListener("DOMContentLoaded", function() {
            // Example: If you want to hide the medicine cart table when switching to the external tab
            document.querySelectorAll('.nav-link').forEach(item => {
                item.addEventListener('click', event => {
                    if (event.target.id === 'external-tab') {
                        document.getElementById('externalMedicineCartTable').style.display =
                            'block';
                    } else {
                        document.getElementById('medicineCartTable').style.display = 'block';
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var cart = []; // Array to store selected medicines in the cart
            var externalCart = [];

            $('#medicinesSelect').change(function() {
                addToCart();
                renderCartTable();
            });

            $('#externalMedicinesSelect').change(function() {
                addToExternalCart();
                renderExternalCartTable();
            });

            // Use the change event to capture changes on quantity input fields
            $(document).on('change', '.quantity-input', function() {
                updateCartQuantity($(this).data('cart-id'), $(this).val());
                renderCartTable();
            });

            $(document).on('change', '.external-detail-input', function() {
                updateExternalCartDetail($(this).data('cart-id'), $(this).val());
            });

            $(document).on('click', '.remove-btn', function() {
                removeFromCart($(this).data('cart-id'));
                renderCartTable();
            });

            $(document).on('click', '.remove-external-btn', function() {
                removeFromExternalCart($(this).data('cart-id'));
                renderExternalCartTable();
            });

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
                var taxRate = {{ \App\Model\BusinessSetting::where('key', 'tax')->first()->value ?? 0 }};

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
        $(document).on('click', '#add_new_prescription', function() {
            var medicalHistoryId = $(this).data('medical-history-id');
            $('#prescription_form input[name="medical_history_id"]').val(medicalHistoryId);
            $('#external_prescription_form input[name="medical_history_id"]').val(medicalHistoryId);
        });

        $(document).on('click', '#add_new_laboratory', function() {
            var medicalHistoryId = $(this).data('medical-history-id');
            $('#history_lab_test_form input[name="medical_history_id"]').val(medicalHistoryId);
            $('#history_radiology_form input[name="medical_history_id"]').val(medicalHistoryId);
        });

        function populateEditForm() {
            // Assuming you have the $physical_test data available
            var physicalTest = @json($physical_test);

            // Populate the form fields in the edit modal
            $('#physical_test_form_edit input[name="id"]').val(physicalTest.id);
            $('#physical_test_form_edit input[name="nurse_id"]').val(physicalTest.nurse_id);
            $('#physical_test_form_edit input[name="patient_id"]').val(physicalTest.patient_id);
            $('#physical_test_form_edit input[name="blood_pressure"]').val(physicalTest.blood_pressure);
            $('#physical_test_form_edit input[name="temperature"]').val(physicalTest.temperature);
            $('#physical_test_form_edit input[name="pulse_rate"]').val(physicalTest.pulse_rate);
            $('#physical_test_form_edit input[name="oxygen_saturation"]').val(physicalTest.oxygen_saturation);
            $('#physical_test_form_edit input[name="weight"]').val(physicalTest.weight);
            $('#physical_test_form_edit input[name="height"]').val(physicalTest.height);

            // Additional fields can be populated similarly

            // Open the edit modal
            $('#edit-physical_test').modal('show');
        }

        $(document).on('click', '#add_new_physical_test', function() {
            var patientId = $(this).data('patient-id');
            $('#physical_test_form input[name="patient_id"]').val(patientId);
        });

        $(document).on('click', '#add_new_medical_lab_test', function() {
            var medicalHistoryId = $(this).data('medical-history-id');
            $('#medical_lab_test_form input[name="medical_history_id"]').val(medicalHistoryId);

            // Make an AJAX request to fetch test types
            $.ajax({
                url: '{{ route('admin.testType.fetch') }}',
                type: 'GET',
                data: {
                    medicalHistoryId: medicalHistoryId,
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


        $(document).on('click', '#add_new_radiology_lab_test', function() {
            var medicalHistoryId = $(this).data('medical-history-id');
            $('#radiology_lab_test_form input[name="medical_history_id"]').val(medicalHistoryId);

            // Make an AJAX request to fetch test types
            $.ajax({
                url: '{{ route('admin.radiologyType.fetch') }}',
                type: 'GET',
                data: {
                    medicalHistoryId: medicalHistoryId,
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




        $('#physical_test_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.physical_test.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('Physical Test Saved successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-physical_test').click();
                    $('#medical_lab_test_form')[0].reset();
                    $('#slot_id').html('');
                    location.reload();

                    setTimeout(function() {
                        // location.href = '{{ route('admin.patient.list') }}';
                    }, 2000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        console.log(xhr.responseJSON.error)
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


        $('#physical_test_form_edit').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.physical_test.update') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('Physical Test Updated successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-physical_test').click();
                    $('#medical_lab_test_form')[0].reset();
                    $('#slot_id').html('');
                    location.reload();

                    setTimeout(function() {
                        // location.href = '{{ route('admin.patient.list') }}';
                    }, 2000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        console.log(xhr.responseJSON.error)
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
                url: '{{ route('admin.lab_result.store') }}',
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


        $('#radiology_lab_test_form').on('submit', function(event) {
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

        // Add a click event listener to the "Add Prescription" button


        // Submit the form when it is submitted
        $('#prescription_form').on('submit', function(event) {
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
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('Prescription Saved successfully!') }}', {
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

        $('#external_prescription_form').on('submit', function(event) {
            event.preventDefault();

            var cartData = getExternalCartData();

            var externalMedicines = [];

            var selectedMedicines = $('#externalMedicinesSelect option:selected');

            selectedMedicines.each(function() {
                var medicineId = $(this).val();

                if (!externalMedicines.includes(medicineId)) {
                    externalMedicines.push(medicineId);
                }
            });

            if (!externalMedicines || externalMedicines.length === 0) {
                toastr.error('{{ translate('Please select at least one medicine.') }}', {
                    closeButton: true,
                    progressBar: true
                });
                return;
            }

            var externalNote = $('textarea[name="external_prescription_content"]').val();

            var formData = new FormData(this);
            formData.append('cart', JSON.stringify(cartData));
            //formData.append('external_medicines', JSON.stringify(externalMedicines));
            formData.append('external_note', externalNote);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.prescription.store_external') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.success('{{ translate('External Prescription Saved successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });

                    $('#add-prescription').hide();

                    $('#external_prescription_form')[0].reset();

                    location.reload();
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

        function getExternalCartData() {
            var cartData = [];
            $('#externalMedicineCartBody tr').each(function() {
                var medicineId = $(this).find('td[data-medicine-id]').data('medicine-id');
                var medicineName = $(this).find('td:first-child').text();
                var medicineDetail = $(this).find('.external-detail-input').val();
                cartData.push({
                    medicineId: medicineId,
                    medicineName: medicineName,
                    medicineDetail: medicineDetail,
                });
            });

            return cartData;
        }


        function getCartData() {
            var cartData = [];
            $('#medicineCartBody tr').each(function() {
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


        $('#updateLabTestProgressModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var medicalHistoryId = button.data('medical-history-id');
            var currentProgress = button.data('current-progress');

            var modal = $(this);
            modal.find('#medicalHistoryIdInput').val(medicalHistoryId);
            modal.find('#labTestProgressInput').val(currentProgress);
        });


        $('#updateRadiologyTestProgressModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var medicalHistoryId = button.data('medical-history-id');
            var currentProgress = button.data('current-progress');

            var modal = $(this);
            modal.find('#medicalHistoryIdInput').val(medicalHistoryId);
            modal.find('#radiologyTestProgressInput').val(currentProgress);
        });

        $('#updateLabTestProgressForm').submit(function(e) {
            e.preventDefault();

            // Perform AJAX request to update the lab test progress
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.lab_result.status') }}', // Replace with your actual route
                data: formData,
                success: function(response) {
                    $('#updateLabTestProgressModal').hide();
                    location.reload();
                    toastr.success('{{ translate('Progress Updated Successfully!') }}', {
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


        $('#updateRadiologyTestProgressForm').submit(function(e) {
            e.preventDefault();

            // Perform AJAX request to update the lab test progress
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.radiology_result.status') }}', // Replace with your actual route
                data: formData,
                success: function(response) {
                    $('#updateRadiologyTestProgressModal').hide();
                    location.reload();
                    toastr.success('{{ translate('Progress Updated Successfully!') }}', {
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
        $('#medical_history_form').on('submit', function(event) {
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
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('medical_history Scheduled successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-medical_history').click();
                    $('#medical_history_form')[0].reset();
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
                url: '{{ route('admin.medical_history.store-test-type') }}',
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
                url: '{{ route('admin.medical_history.store-radiology') }}',
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
        $('#doctor_id, #day').on('change', function() {
            var doctorId = $('#doctor_id').val();
            var day = $('#day').val();

            // Check if both doctor_id and day are selected
            if (doctorId && day) {
                var url =
                    '{{ route('admin.doctor.appointment_schedule.doctor_list', ['doctor_id' => 'doctorIdPlaceholder']) }}'
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
                    success: function(data) {
                        console.log('====================================');
                        console.log(data);
                        console.log('====================================');

                        // Clear the existing options in the slot dropdown
                        $('#slot_id').html('');

                        // Iterate through each time schedule
                        data.time_schedules.forEach(function(timeSchedule) {
                            // Format the start and end times
                            var formattedTime = moment(timeSchedule.start, 'HH:mm:ss').format(
                                    'h:mm a') + ' - ' + moment(timeSchedule.end, 'HH:mm:ss')
                                .format('h:mm a');

                            // Add a separator for each time schedule
                            $('#slot_id').append('<option disabled>' + formattedTime +
                                '</option>');

                            // Iterate through each appointment slot for the current time schedule
                            timeSchedule.appointment_slots.forEach(function(appointmentSlot) {
                                // Format the appointment slot start and end times
                                var formattedSlotTime = moment(appointmentSlot
                                        .start_time, 'HH:mm:ss').format('h:mm a') +
                                    ' - ' + moment(appointmentSlot.end_time, 'HH:mm:ss')
                                    .format('h:mm a');

                                // Add the appointment slot as an option in the dropdown
                                $('#slot_id').append('<option value="' + appointmentSlot
                                    .id + '">' + formattedSlotTime + '</option>');
                            });
                        });
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            } else {
                // Determine which option is missing and inform the user
                var missingOption = doctorId ? 'Day' : 'Doctor';
                toastr.error('Both Week Day and Doctor Id are Required to get Slots. So Select ' + missingOption);
            }
        });
    </script>
    <script>
        window.csrf_token = "{{ csrf_token() }}";

        $('#update_status').on('change', function() {
            var appointmentId = $(this).data('appointment-id');
            var status = $(this).val();

            var url = '{{ route('admin.appointment.status', ['appointment_id' => 'appointmentIdPlaceholder']) }}'
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
                success: function(data) {
                    toastr.success('Status Updated Successfully ');

                    console.log(data)
                },
                error: function(error) {
                    console.error(error);
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

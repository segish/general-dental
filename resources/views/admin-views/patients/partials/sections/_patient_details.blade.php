@php
    use Carbon\Carbon;
@endphp
<div class="col-md-3 card card-body">
    <!-- Basic Info -->
    <div class="media gap-3 align-items-center">
        <!-- Avatar -->
        <div class="avatar-circle mr-3"
            style="position: relative; width: 50px; height: 50px; border-radius: 50%; overflow: hidden; background-color: #24b89b; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white;">
            @if ($patient->getImageUrl() && file_exists(public_path($patient->getImageUrl())))
                <img class="img-fit rounded-circle" src="{{ $patient->getImageUrl() }}" alt="Image Description"
                    style="width: 100%; height: 100%;">
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
        <div class="container px-0">
            <div class="row mb-2">
                <div class="col-sm-4 font-weight-bold">Registration No:</div>
                <div class="col-sm-8">{{ $patient->registration_no }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 font-weight-bold">Gender:</div>
                <div class="col-sm-8">{{ ucfirst($patient->gender) ?? 'Not Available' }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 font-weight-bold">Age:</div>
                <div class="col-sm-8">{{ $patient->age_detailed }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 font-weight-bold">Address:</div>
                <div class="col-sm-8">{{ $patient->address ?? 'Not Available' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 font-weight-bold">Blood Group:</div>
                <div class="col-sm-8">{{ $patient->blood_group ?? 'Not Available' }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 font-weight-bold">Marital Status:</div>
                <div class="col-sm-8">
                    {{ $patient->marital_status ? ucwords($patient->marital_status) : 'Not Available' }}
                </div>
            </div>
            @if ($patient->mother)
                <div class="row mb-3 ml-4">
                    <div class="col-12">
                        <h4 class="font-weight-bold">Mother</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-4 font-weight-bold">Name:</div>
                    <div class="col-sm-6">
                        @if (auth('admin')->user() && auth('admin')->user()->can('patient.view'))
                            <a href="{{ route('admin.patient.view', $patient->mother->id) }}">
                                {{ ucwords($patient->mother->full_name ?? 'Unknown') }}
                            </a>
                        @else
                            {{ ucwords($patient->mother->full_name ?? 'Unknown') }}
                        @endif
                    </div>
                </div>
            @endif

            @if ($pregnancy)
                @php

                    $lmp = $pregnancy->lmp ? Carbon::parse($pregnancy->lmp) : null;
                    $edd = $pregnancy->edd ? Carbon::parse($pregnancy->edd) : null;
                    $currentWeek = $lmp ? $lmp->diffInWeeks(Carbon::now()) : null;
                @endphp
                <div class="row mb-3 ml-4">
                    <div class="col-sm-12">
                        <h4 class="font-weight-bold">Pregnancy Details</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-4 font-weight-bold">LMP:</div>
                    <div class="col-sm-8">
                        {{ $lmp ? $lmp->format('F j, Y') : 'Not Available' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 font-weight-bold">EDD:</div>
                    <div class="col-sm-8">
                        {{ $edd ? $edd->format('F j, Y') : 'Not Available' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 font-weight-bold">Current Week:</div>
                    <div class="col-sm-8">
                        {{ $currentWeek !== null ? $currentWeek . ' week' . ($currentWeek > 1 ? 's' : '') : 'Not Available' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">Status:</div>
                    <div class="col-sm-8">{{ ucfirst($pregnancy->status) }}</div>
                </div>
            @endif

            @if ($patient->children->count())
                <div class="row mb-3 ml-4">
                    <div class="col-12">
                        <h4 class="font-weight-bold">
                            {{ $patient->children->count() > 1 ? 'Children' : 'Child' }}
                        </h4>
                    </div>
                </div>

                @foreach ($patient->children as $index => $child)
                    <div class="row mb-2">
                        <div class="col-sm-4 font-weight-bold">Child
                            {{ $index + 1 }}:</div>
                        <div class="col-sm-6">
                            @if (auth('admin')->user() && auth('admin')->user()->can('patient.view'))
                                <a href="{{ route('admin.patient.view', $child->id) }}">
                                    {{ ucwords($child->full_name ?? 'Unknown') }}
                                </a>
                            @else
                                {{ ucwords($child->full_name ?? 'Unknown') }}
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="mt-4">
        <div class="details-container">


        </div>
    </div>
</div>

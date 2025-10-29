@extends('layouts.admin.app')

@section('title', translate('appointments List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/appointment.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('appointments_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $appointments->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-8 col-sm-8 col-md-8">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Search by Patient/Doctor Name') }}"
                                            aria-label="Search" value="{{ $search }}" autocomplete="off">
                                        <input type="date" name="date" class="form-control"
                                            value="{{ request('date') }}" />
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('appointment.add-new'))
                                <div class="col-lg-4 col-sm-4 col-md-4 d-flex justify-content-sm-end">
                                    <button class="btn btn-success rounded text-nowrap" id="add_new_appointment"
                                        type="button" data-toggle="modal" data-target="#add-appointment"
                                        title="Add Appointment">
                                        <i class="tio-add"></i>
                                        {{ translate('appointment') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Doctor Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Patient Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Date') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Time') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Status') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($appointments as $key => $appointment)
                                    <tr>
                                        <td>{{ $appointments->firstitem() + $key }}</td>

                                        <td>

                                            {{ $appointment->doctor->full_name }}
                                        </td>
                                        </a>
                                        <td>
                                            <a href="{{ route('admin.patient.view', [$appointment->patient['id']]) }}">
                                                {{ $appointment->patient->full_name }}
                                            </a>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->date)->format('M d, Y') }}</td>
                                        <td>
                                            @if ($appointment->timeSchedule)
                                                {{ \Carbon\Carbon::parse($appointment->timeSchedule->start)->format('g:i a') }}
                                                -
                                                {{ \Carbon\Carbon::parse($appointment->timeSchedule->end)->format('g:i a') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth('admin')->user()->can('appointment.edit') || auth('admin')->user()->can('appointment.status'))
                                                <select name="status" class="form-control" id="update_status"
                                                    data-appointment-id="{{ $appointment->id }}">
                                                    <option value="pending" style="font-weight:bold; color:  red;"
                                                        {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending
                                                    </option>
                                                    <option value="confirmed" style="font-weight:bold; color:  green;"
                                                        {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>
                                                        Confirmed</option>
                                                    <option value="done" style="font-weight:bold; color:  blue;"
                                                        {{ $appointment->status == 'done' ? 'selected' : '' }}>Done
                                                    </option>
                                                </select>
                                            @else
                                                @if ($appointment->status == 'pending')
                                                    <span
                                                        style="font-weight:bold; color:  red;">{{ translate($appointment->status) }}</span>
                                                @elseif ($appointment->status == 'confirmed')
                                                    <span
                                                        style="font-weight:bold; color:  green;">{{ translate($appointment->status) }}</span>
                                                @elseif ($appointment->status == 'done')
                                                    <span
                                                        style="font-weight:bold; color:  blue;">{{ translate($appointment->status) }}</span>
                                                @else
                                                    {{ $appointment->status }}
                                                @endif
                                            @endif

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
                            {!! $appointments->links() !!}
                        </div>
                    </div>
                    @if (count($appointments) == 0)
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


    <!-- ADD Customer Modal -->
    <div class="modal fade" id="add-appointment" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add_New_appointment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="appointment_form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row pl-2">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Date') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="date" name="date" class="form-control" value="" required="">
                                </div>
                            </div>
                            <div class="col-lg-7 ">
                                <div class="row" style="display: flex; align-items:center;">
                                    <div class="col-9">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Patient') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <select name="patient_id" id="patient-select"
                                                class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>Select a patient</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Doctor') }}<span
                                            class="input-label-secondary text-danger">*</span></label>

                                    <select name="doctor_id" id="doctor_id" class="form-control js-select2-custom"
                                        required>
                                        <option value="" selected disabled>{{ \App\CentralLogics\translate('') }}
                                        </option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="day">{{ \App\CentralLogics\translate('Week Day') }}</label>
                                    <select name="day" id="day" class="form-control js-select2-custom"
                                        required>
                                        <option value="" selected disabled>{{ \App\CentralLogics\translate('') }}
                                        </option>
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wendensday">Wendensday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                        <option value="sunday">Sunday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Time Schedule') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="slot_id" id="slot_id" class="form-control js-select2-custom"
                                        required>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('note') }}</label>
                                    <div class="form-group">
                                        <textarea name="notes" class="form-control"></textarea>
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

@endsection

<!-- Include jQuery -->

@push('script')
    <script>
        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.appointment.search') }}',
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
        $(document).ready(function() {
            $('.js-select2-custom').select2({
                placeholder: "Select a patient",
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                minimumResultsForSearch: 10;
            });
        });
    </script>
@endpush

@push('script_2')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        // Add event listeners for age and date of birth fields
        document.addEventListener('DOMContentLoaded', function() {
            const ageInput = document.querySelector('input[name="age"]');
            const dobInput = document.querySelector('input[name="date_of_birth"]');

            // When age is entered, calculate and set date of birth
            ageInput.addEventListener('input', function() {
                if (this.value) {
                    const age = parseInt(this.value);
                    const today = moment();
                    const dob = today.subtract(age, 'years').format('YYYY-MM-DD');
                    dobInput.value = dob;
                }
            });

            // When date of birth is entered, calculate and set age
            dobInput.addEventListener('change', function() {
                if (this.value) {
                    const dob = moment(this.value);
                    const today = moment();
                    const age = today.diff(dob, 'years');
                    ageInput.value = age;
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#patient-select').select2({
                ajax: {
                    url: '{{ route('admin.appointment.get-patients') }}', // Add comma here
                    dataType: 'json',
                    delay: 250, // Debounce for better performance
                    data: function(params) {
                        return {
                            search: params.term, // Search term entered by the user
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(patient => ({
                                id: patient.id,
                                text: `${patient.full_name} - ${patient.registration_no} - ${patient.phone}`,
                            })),
                        };
                    },
                    cache: true,
                },
                width: '100%',
                dropdownAutoWidth: true,
                minimumInputLength: 2, // Start searching after 2 characters
            });
        });
    </script>

    <!-- Your JavaScript code -->
    <script>
        $('#appointment_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.appointment.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('Appointment Scheduled successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-appointment').click();
                    $('#appointment_form')[0].reset();
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


        $('#patient_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.patient.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    toastr.success('{{ translate('patient saved successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-appointment').click();
                    $('#appointment_form')[0].reset();
                    $('#slot_id').html('');
                    location.reload();
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

            if (doctorId && day) {
                var url =
                    '{{ route('admin.appointment_schedule.doctor_list', ['doctor_id' => 'doctorIdPlaceholder']) }}'
                    .replace('doctorIdPlaceholder', doctorId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        doctor_id: doctorId,
                        day: day
                    },
                    success: function(data) {
                        console.log('Fetched time schedules:', data);

                        // Clear the slot dropdown
                        $('#slot_id').html('');

                        if (data.time_schedules.length === 0) {
                            $('#slot_id').append(
                                '<option disabled>No available time schedules</option>');
                            return;
                        }

                        // Add each time schedule as a selectable option
                        data.time_schedules.forEach(function(schedule) {
                            var formattedTime = moment(schedule.start, 'HH:mm:ss').format(
                                    'h:mm a') + ' - ' +
                                moment(schedule.end, 'HH:mm:ss').format('h:mm a');

                            $('#slot_id').append('<option value="' + schedule.id + '">' +
                                formattedTime + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        toastr.error('Failed to fetch time schedules.');
                    }
                });
            } else {
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
@endpush

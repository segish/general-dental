@extends('layouts.admin.app')

@section('title', translate('Add new appointment_schedule'))

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
                {{ \App\CentralLogics\translate('add_new_appointment_schedule') }}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">


                <div id="from_part_2">

                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.appointment_schedule.store', $doctor->id) }} "
                                enctype="multipart/form-data">
                                @csrf
                                @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                                @php($language = $language->value ?? null)
                                @php($default_lang = 'bn')
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="day">{{ \App\CentralLogics\translate('Week Day') }}</label>
                                            <select name="day" class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('') }}</option>
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
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="start">{{ \App\CentralLogics\translate('Start Time') }}</label>
                                            <input type="time" name="start" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="end">{{ \App\CentralLogics\translate('End Time') }}</label>
                                            <input type="time" name="end" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex justify-content-end align-items-center items gap-3">
                                        <div class="">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('submit') }}</button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="card mt-3">


                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Week Day') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Start Time / End Time') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($timeSchedules as $key => $time)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $time->day }}</td>
                                        <td>{{ \Carbon\Carbon::parse($time->start)->format('g:i a') }} -
                                            {{ \Carbon\Carbon::parse($time->end)->format('g:i a') }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">

                                                <!-- Edit button -->
                                                <a class="btn btn-outline-primary square-btn" href="#"
                                                    onclick="editSchedule('{{ route('admin.appointment_schedule.edit', ['doctor_id' => $time->doctor_id, 'id' => $time->id]) }}')"
                                                    data-toggle="modal" data-target="#edit-schedule_time"
                                                    data-day="{{ $time->day }}"
                                                    data-start="{{ \Carbon\Carbon::parse($time->start)->format('H:i') }}"
                                                    data-end="{{ \Carbon\Carbon::parse($time->end)->format('H:i') }}">
                                                    <i class="tio tio-edit"></i>
                                                </a>


                                                {{-- <a class="btn btn-outline-success square-btn" href="javascript:"
                                                    onclick="generateSchedules('{{ $time->day }}', '{{ $time->start }}', '{{ $time->end }}', '{{ $doctor->id }}')">
                                                    <i class="tio tio-map"></i>
                                                </a> --}}


                                                <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                    onclick="form_alert('time-1','{{ \App\CentralLogics\translate('Want to delete this Schedule ?') }}')"><i
                                                        class="tio tio-delete"></i></a>
                                            </div>
                                            <form
                                                action="{{ route('admin.appointment_schedule.delete', ['doctor_id' => $time->doctor_id, 'id' => $time->id]) }}"
                                                method="post" id="time-1">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->


                    @if (count($timeSchedules) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>

    <div class="row">

    </div>
    </div>
    <div class="modal fade" id="edit-schedule_time" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Edit_Appointment_Schedule') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="edit_schedule_time"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row pl-2">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="day">{{ \App\CentralLogics\translate('Week Day') }}</label>
                                    <select name="day" class="form-control js-select2-custom" required>
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
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="start">{{ \App\CentralLogics\translate('Start Time') }}</label>
                                    <input type="time" name="start" class="form-control">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="end">{{ \App\CentralLogics\translate('End Time') }}</label>
                                    <input type="time" name="end" class="form-control">
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

@push('script')
@endpush

@push('script')
    <script>
        function editSchedule(editUrl) {
            // Fetch the modal
            var modal = $('#edit-schedule_time');

            // Make an AJAX request to get the edit schedule form
            $.get(editUrl, function(data) {
                // Set the modal body content with the form
                modal.find('.modal-body').html(data);

                // Populate the form fields with schedule details
                modal.find('[name="day"]').val(modal.data('day'));
                modal.find('[name="time_type"]').val(modal.data('time-type'));
                modal.find('[name="start"]').val(modal.data('start'));
                modal.find('[name="end"]').val(modal.data('end'));
                modal.find('[name="slot_duration"]').val(modal.data('slot-duration'));

                // Show the modal
                modal.modal('show');
            });
        }
    </script>


    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function generateSchedules(day, timeType, startTime, endTime, slotDuration, doctorId) {
            // Define the weekdays
            var weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            // Initialize a cart to store the schedules
            var scheduleCart = [];

            // Iterate through each weekday and generate schedules
            weekdays.forEach(function(weekday) {
                // Create a new schedule for the current weekday
                var newSchedule = {
                    day: weekday,
                    start: formatTimeTo24Hour(startTime),
                    end: formatTimeTo24Hour(endTime),
                };


                // Add the new schedule to the cart
                scheduleCart.push(newSchedule);
            });

            // Send the cart data to the backend with the doctor's ID
            sendCartDataToBackend(scheduleCart, doctorId);
        }

        function formatTimeTo24Hour(time) {
            // Assuming 'time' is in HH:mm format
            var parts = time.split(':');
            var hours = parseInt(parts[0], 10);
            var minutes = parseInt(parts[1], 10);

            // Format as 24-hour time
            var formattedTime = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');

            return formattedTime;
        }

        function sendCartDataToBackend(cartData, doctorId) {
            // Perform an AJAX request to send the cart data to the backend
            // Modify the URL and method according to your backend endpoint
            // Include the CSRF token in the headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Your AJAX request
            $.ajax({
                url: '{{ route('admin.appointment_schedule.bulk', $doctor->id) }}',
                method: 'POST',
                data: {
                    cart: JSON.stringify(cartData)
                },
                success: function(response) {
                    location.reload();
                    toastr.success('{{ translate('Schedule Time Saved successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    // Add any additional logic here after successfully sending the data
                },
                error: function(error) {
                    toastr.success('{{ translate('Something Went Wrong!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    // Handle errors if needed
                }
            });

        }
    </script>
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
    <script>
        // JavaScript to handle "Check All" functionality
        $(document).ready(function() {
            $('.check-all-group').change(function() {
                var group = $(this).val();
                $('input[data-group="' + group + '"]').prop('checked', this.checked);
            });
        });
    </script>

    <script src="{{ asset(config('app.asset_path') . '/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        $(".lang_link").click(function(e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{ $default_lang }}') {
                $("#from_part_2").removeClass('d-none');
            } else {
                $("#from_part_2").addClass('d-none');
            }
        })
    </script>
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
    </script>

    <script>
        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }
    </script>

    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/tags-input.min.js"></script>

    <script>
        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append(
                '<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i +
                '"><input type="text" class="form-control" name="choice[]" value="' + n +
                '" placeholder="Choice Title" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' +
                i +
                '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>'
            );
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        function combination_update() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        }
    </script>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        @if ($language)
            @foreach (json_decode($language) as $lang)
                var en_quill = new Quill('#{{ $lang }}_editor', {
                    theme: 'snow'
                });
            @endforeach
        @else
            var bn_quill = new Quill('#editor', {
                theme: 'snow'
            });
        @endif
        $('#appointment_form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            console.log(e);

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.appointment_schedule.store', $doctor->id) }}',
                method: 'POST',
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
                        toastr.success('{{ translate('Role saved successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.roles.list') }}';
                        }, 2000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
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

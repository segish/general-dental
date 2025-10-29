@extends('layouts.admin.app')

@section('title', translate('Add new Patient'))

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
                {{ \App\CentralLogics\translate('add_new_patient') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="javascript:" method="post" id="patient_form" enctype="multipart/form-data">
                    @csrf
                    @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = 'bn')
                    <div id="from_part_2">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="full_name">{{ \App\CentralLogics\translate('full_name') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="text" name="full_name" class="form-control"
                                                placeholder="{{ translate('Ex : JOHN') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="gender">{{ \App\CentralLogics\translate('gender') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <select name="gender" class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Gender') }}</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="phone">{{ \App\CentralLogics\translate('phone_number') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control"
                                                placeholder="{{ translate('Ex : 09xxxxxxxx') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="date_of_birth">{{ \App\CentralLogics\translate('Date of Birth') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date_of_birth" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="age_years">{{ \App\CentralLogics\translate('Years') }}</label>
                                            <input type="number" name="age_years" class="form-control"
                                                placeholder="{{ translate('Ex : 1') }}" min="0" value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="age_months">{{ \App\CentralLogics\translate('Months') }}</label>
                                            <input type="number" name="age_months" class="form-control"
                                                placeholder="{{ translate('Ex : 3') }}" min="0" max="11"
                                                value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="age_days">{{ \App\CentralLogics\translate('Days') }}</label>
                                            <input type="number" name="age_days" class="form-control"
                                                placeholder="{{ translate('Ex : 15') }}" min="0" max="30"
                                                value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="blood_group">{{ \App\CentralLogics\translate('blood_group') }}</label>
                                            <select name="blood_group" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('blood_group') }}</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="marital_status">{{ \App\CentralLogics\translate('marital_status') }}</label>
                                            <select name="marital_status" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select_marital_status') }}</option>
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Divorced">Divorced</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="registration_date">{{ \App\CentralLogics\translate('registration_date') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="date" class="form-control" name="registration_date"
                                                id="registration_date" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="address">{{ \App\CentralLogics\translate('Address') }}</label>
                                            <input type="text" name="address" class="form-control"
                                                placeholder="Addis Ababa">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="email">{{ \App\CentralLogics\translate('email') }}</label>
                                            <input type="text" name="email" class="form-control"
                                                placeholder="{{ translate('Ex : example@gmail.com') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="is_flexible_payment">{{ \App\CentralLogics\translate('Flexible Payment Allowed') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <select name="is_flexible_payment" class="form-control js-select2-custom"
                                                required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select payment flexibility') }}
                                                </option>
                                                <option value="1" selected>Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">

                                        <div class="d-flex justify-content-end gap-3">
                                            <button type="reset"
                                                class="btn btn-secondary">{{ \App\CentralLogics\translate('reset') }}</button>
                                            <button type="submit" class="btn btn-primary"
                                                id="patient_submit_button">{{ \App\CentralLogics\translate('submit') }}</button>
                                        </div>
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
        function disableButton(button) {
            const originalText = button.html();
            button.prop('disabled', true);
            button.html('<i class="tio-sync spin"></i> Loading...');
            return originalText;
        }

        // Function to re-enable button
        function enableButton(button, originalText) {
            button.prop('disabled', false);
            button.html(originalText);
        }
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
        let now = new Date();
        let year = now.getFullYear();
        let month = (now.getMonth() + 1).toString().padStart(2, '0');
        let day = now.getDate().toString().padStart(2, '0');

        document.getElementById('registration_date').value = `${year}-${month}-${day}`;
    </script>

    <script>
        // Add event listeners for age and date of birth fields
        document.addEventListener('DOMContentLoaded', function() {
            const ageYearsInput = document.querySelector('input[name="age_years"]');
            const ageMonthsInput = document.querySelector('input[name="age_months"]');
            const ageDaysInput = document.querySelector('input[name="age_days"]');
            const dobInput = document.querySelector('input[name="date_of_birth"]');

            // When age is entered, calculate and set date of birth
            function updateDateOfBirth() {
                const years = parseInt(ageYearsInput.value) || 0;
                const months = parseInt(ageMonthsInput.value) || 0;
                const days = parseInt(ageDaysInput.value) || 0;

                if (years > 0 || months > 0 || days > 0) {
                    const today = moment();
                    const dob = today
                        .subtract(years, 'years')
                        .subtract(months, 'months')
                        .subtract(days, 'days')
                        .format('YYYY-MM-DD');
                    dobInput.value = dob;
                }
            }

            // When date of birth is entered, calculate and set age
            dobInput.addEventListener('change', function() {
                if (this.value) {
                    const dob = moment(this.value);
                    const today = moment();

                    // Calculate total days difference
                    const totalDays = today.diff(dob, 'days');

                    // Calculate years, months, and remaining days
                    const years = Math.floor(totalDays / 365);
                    const remainingDays = totalDays % 365;
                    const months = Math.floor(remainingDays / 30);
                    const days = remainingDays % 30;

                    ageYearsInput.value = years;
                    ageMonthsInput.value = months;
                    ageDaysInput.value = days;
                }
            });

            // Add event listeners for all age inputs
            ageYearsInput.addEventListener('input', updateDateOfBirth);
            ageMonthsInput.addEventListener('input', updateDateOfBirth);
            ageDaysInput.addEventListener('input', updateDateOfBirth);
        });
    </script>


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

        $('#patient_form').on('submit', function() {

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.patient.store') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('Patient Saved successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.patient.list') }}';
                        }, 2000);
                    }
                },
                error: function(xhr, status, error) {
                    let message = '';
                    // console.log(xhr)
                    // message = xhr?.responseJSON?.message || '{{ translate('An error occurred while saving the patient.') }}';
                    if (xhr?.responseJSON?.errors) {
                        message = '';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            message += value[0] + '<br>';
                        });
                        toastr.error(message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.error(
                        '{{ translate('An error occurred while saving the patient.') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
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

{{-- @push('script_2')
    <script>
        $('#patient_form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.patient.store') }}',
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
                        toastr.success('{{ translate('Patient Saved successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.patient.list') }}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush --}}

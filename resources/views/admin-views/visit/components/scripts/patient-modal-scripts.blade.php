<!-- Patient Modal JavaScript -->
<script>
    $(document).ready(function() {
        let now = new Date();
        let year = now.getFullYear();
        let month = (now.getMonth() + 1).toString().padStart(2, '0');
        let day = now.getDate().toString().padStart(2, '0');
        document.getElementById('modal_registration_date').value = `${year}-${month}-${day}`;
    });

    $(document).ready(function() {
        const ageYearsInput = document.querySelector('#modal_age_years');
        const ageMonthsInput = document.querySelector('#modal_age_months');
        const ageDaysInput = document.querySelector('#modal_age_days');
        const dobInput = document.querySelector('#modal_date_of_birth');

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

        dobInput.addEventListener('change', function() {
            if (this.value) {
                const dob = moment(this.value);
                const today = moment();
                const totalDays = today.diff(dob, 'days');
                const years = Math.floor(totalDays / 365);
                const remainingDays = totalDays % 365;
                const months = Math.floor(remainingDays / 30);
                const days = remainingDays % 30;

                ageYearsInput.value = years;
                ageMonthsInput.value = months;
                ageDaysInput.value = days;
            }
        });

        ageYearsInput.addEventListener('input', updateDateOfBirth);
        ageMonthsInput.addEventListener('input', updateDateOfBirth);
        ageDaysInput.addEventListener('input', updateDateOfBirth);
    });

    $('#modal_patient_submit_button').on('click', function() {
        const submitButton = $(this);
        const originalText = disableButton(submitButton);

        var formData = new FormData($('#patient_modal_form')[0]);

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
                    enableButton(submitButton, originalText);
                } else {
                    toastr.success('{{ translate('Patient Added successfully!') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });

                    $('#addPatientModal').modal('hide');
                    enableButton(submitButton, originalText);
                    $('#patient_modal_form')[0].reset();

                    if (data.patient) {
                        var newOption = new Option(
                            `${data.patient.full_name} - ${data.patient.registration_no} - ${data.patient.phone}`,
                            data.patient.id,
                            true,
                            true
                        );
                        $('#patient-select').append(newOption).trigger('change');
                    } else {
                        refreshPatientSelect();
                    }
                }
            },
            error: function(xhr, status, error) {
                let message = '';
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
                }, 2000);
            }
        });
    });

    function refreshPatientSelect() {
        if ($('#patient-select').hasClass('select2-hidden-accessible')) {
            $('#patient-select').select2('destroy');
        }

        $('#patient-select').empty().append('<option value="" selected disabled>Select a patient</option>');

        $.HSCore.components.HSSelect2.init($('#patient-select'), {
            ajax: {
                url: '{{ route('admin.appointment.get-patients') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
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
            minimumInputLength: 2,
        });
    }

    $('#addPatientModal').on('shown.bs.modal', function() {
        $('.js-select2-custom').each(function() {
            $.HSCore.components.HSSelect2.init($(this));
        });
    });

    $('#addPatientModal').on('hidden.bs.modal', function() {
        $('#patient_modal_form')[0].reset();
        let now = new Date();
        let year = now.getFullYear();
        let month = (now.getMonth() + 1).toString().padStart(2, '0');
        let day = now.getDate().toString().padStart(2, '0');
        document.getElementById('modal_registration_date').value = `${year}-${month}-${day}`;
    });
</script>

<!-- Visit Form JavaScript -->
<script>
    $(document).on('ready', function() {
        $('.js-select2-custom').each(function() {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });
    });
</script>
<script>
    $(document).ready(function() {
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
    });
</script>
<script>
    $('#visit_submit_button').on('click', function(event) {
        event.preventDefault();
        const submitButton = $(this);
        const originalText = disableButton(submitButton);
        $('#visit_form').submit();
    });

    function disableButton(button) {
        const originalText = button.html();
        button.prop('disabled', true);
        button.html('<i class="tio-sync spin"></i> Loading...');
        return originalText;
    }

    function enableButton(button, originalText) {
        button.prop('disabled', false);
        button.html(originalText);
    }
</script>
<script>
    $(document).ready(function() {
        $('select[name="appointment_id"]').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var patientId = selectedOption.data('patient-id');
            var doctorId = selectedOption.data('doctor-id');
            var date = selectedOption.data('date');

            $('select[name="patient_id"]').val(patientId).trigger('change');
            $('select[name="doctor_id"]').val(doctorId).trigger('change');
            $('#visit_datetime').val(date + 'T09:00');
        });
    });
</script>
<script>
    let now = new Date();
    let year = now.getFullYear();
    let month = (now.getMonth() + 1).toString().padStart(2, '0');
    let day = now.getDate().toString().padStart(2, '0');
    let hours = now.getHours().toString().padStart(2, '0');
    let minutes = now.getMinutes().toString().padStart(2, '0');

    document.getElementById('visit_datetime').value = `${year}-${month}-${day}T${hours}:${minutes}`;
    document.getElementById('admission_date').value = `${year}-${month}-${day}T${hours}:${minutes}`;
</script>
<script>
    $('#visit_type').change(function() {
        if ($(this).val() === 'IPD') {
            $('#ipd_fields').removeClass('d-none');
            $('#opd_fields').addClass('d-none');
        } else if ($(this).val() === 'OPD') {
            $('#opd_fields').removeClass('d-none');
            $('#ipd_fields').addClass('d-none');
        } else {
            $('#ipd_fields, #opd_fields').addClass('d-none');
        }
    });
</script>

@extends('layouts.admin.app')

@section('title', translate('Add new Patient'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/product.png') }}" alt="">
                {{ \App\CentralLogics\translate('add_new_patient') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="javascript:" method="post" id="patient_form" enctype="multipart/form-data">
                    @csrf
                    <div id="from_part_2">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label">{{ translate('Patient') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <select name="patient_id" class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('') }}</option>
                                                @foreach ($patients as $patient)
                                                    <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Service Date -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="service_date">{{ \App\CentralLogics\translate('Service Date') }}</label>
                                            <input type="date" name="service_date" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- MRN -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="mrn">{{ \App\CentralLogics\translate('MRN') }}</label>
                                            <input type="text" name="mrn" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="address">{{ \App\CentralLogics\translate('Address (woreda/kebele)') }}</label>
                                            <input type="text" name="address" class="form-control">
                                        </div>
                                    </div>

                                    <!-- National Classification of Disease (NCoD) -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="ncoD">{{ \App\CentralLogics\translate('National Classification of Disease (NCoD)') }}</label>
                                            <input type="text" name="ncoD" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- New / Repeat -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="patient_status">{{ \App\CentralLogics\translate('New / Repeat') }}</label>
                                            <select name="patient_status" class="form-control js-select2-custom" required>
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Status') }}</option>
                                                <option value="New">New</option>
                                                <option value="Repeat">Repeat</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Road Traffic Accident -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="rta">{{ \App\CentralLogics\translate('Road Traffic Accident') }}</label>
                                            <select name="rta" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Accident Type') }}</option>
                                                <option value="1">Pedestrian</option>
                                                <option value="2">Motorcyclist</option>
                                                <option value="3">Vehicle Occupant</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- HIV Test Offered -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="hiv_test_offered">{{ \App\CentralLogics\translate('HIV Test Offered') }}</label>
                                            <select name="hiv_test_offered" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Status') }}</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- HIV Test Performed -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="hiv_test_performed">{{ \App\CentralLogics\translate('HIV Test Performed') }}</label>
                                            <select name="hiv_test_performed" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Status') }}</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Targeted Population Category -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="targeted_population">{{ \App\CentralLogics\translate('Targeted Population Category') }}</label>
                                            <input type="text" name="targeted_population" class="form-control">
                                        </div>
                                    </div>

                                    <!-- HIV Test Result -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="hiv_result">{{ \App\CentralLogics\translate('HIV Test Result (P/N)') }}</label>
                                            <select name="hiv_result" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Result') }}</option>
                                                <option value="P">Positive</option>
                                                <option value="N">Negative</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Screening for TB -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="tb_screening">{{ \App\CentralLogics\translate('Screening for TB') }}</label>
                                            <select name="tb_screening" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Status') }}</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- TB Screening Result -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="tb_result">{{ \App\CentralLogics\translate('TB Screening Result (P/N)') }}</label>
                                            <select name="tb_result" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Result') }}</option>
                                                <option value="P">Positive</option>
                                                <option value="N">Negative</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- TB Screening Outcomes -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="tb_screening_outcome">{{ \App\CentralLogics\translate('TB Screening Outcome') }}</label>
                                            <select name="tb_screening_outcome" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Result') }}</option>
                                                <option value="TB">TB</option>
                                                <option value="No TB">No TB</option>
                                                <option value="ND">Not Decided</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Type of diagnostic_evaluation_code -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="diagnostic_evaluation_code">{{ \App\CentralLogics\translate('Diagnostic Evaluation Code') }}</label>
                                            <input type="text" name="diagnostic_evaluation_code" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Referred to -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="referred_to">{{ \App\CentralLogics\translate('Referred to') }}</label>
                                            <select name="referred_to" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Referral') }}</option>
                                                <option value="1">Hospital</option>
                                                <option value="2">Health Center</option>
                                                <option value="3">Health Post</option>
                                                <option value="4">MCH</option>
                                                <option value="5">ART</option>
                                                <option value="6">SOPD</option>
                                                <option value="7">OPGYN</option>
                                                <option value="8">TB Clinic</option>
                                                <option value="9">Another Service</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Died -->
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label class="input-label"
                                                for="died">{{ \App\CentralLogics\translate('Died') }}</label>
                                            <select name="died" class="form-control js-select2-custom">
                                                <option value="" selected disabled>
                                                    {{ \App\CentralLogics\translate('Select Status') }}</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Remark -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="remark">{{ \App\CentralLogics\translate('Remark') }}</label>
                                            <textarea name="remark" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn btn-primary">{{ \App\CentralLogics\translate('Submit') }}</button>
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
    <script type="text/javascript">
        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 4,
                rowHeight: '215px',
                groupClassName: 'col-auto',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('/public/assets/admin/img/400x400/img2.jpg') }}',
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

    use Spatie\Permission\Models\Role;


    <script src="{{ asset('/public/assets/admin') }}/js/tags-input.min.js"></script>

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
        $('#patient_form').on('submit', function() {

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.out_patient_report.store') }}',
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
                        toastr.success('{{ translate('Patient Report Saved successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.out_patient_report.list') }}';
                        }, 2000);
                    }
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

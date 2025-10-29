@extends('layouts.admin.app')

@section('title', translate('patient_detail'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset(config('app.asset_path') . '/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 100px;
        }
    </style>
@endpush
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <div class="content container-fluid">
        <div class="mb-3 d-md-flex justify-content-between">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/product.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('patient_detail') }}
            </h2>
            {{-- <pre>{{dd($visits)}}</pre> --}}

            {{-- @if (auth('admin')->user()->can('laboratory_request.add-new'))
                <div class="d-flex justify-content-sm-end">
                    <button class="btn btn-success rounded text-nowrap" id="add_new_medical_history" type="button"
                        data-toggle="modal" data-target="#add-laboratory_request" title="Add Appointment">
                        <i class="tio-add"></i>
                        {{ translate('Medical Record ') }}
                    </button>
                </div>
            @endif --}}
        </div>

        <div class="row">
            <div class="col-12">

                @csrf
                <div id="from_part_2">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row media">
                                <div class="col-md-9 media-body ">
                                    <div class="row gy-2 align-items-center mb-3">
                                        <div class="col-12">
                                            {{-- <form action="{{ url()->current() }}" method="GET">
                                                <div class="input-group">
                                                    <input id="datatableSearch_" type="date" name="search"
                                                        class="form-control" value="{{ $search }}" required
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button type="submit"
                                                            class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}</button>
                                                    </div>
                                                </div>
                                            </form> --}}
                                        </div>
                                    </div>
                                    @foreach ($visits as $key => $visit)
                                        <div class="card mb-3">

                                            @include('admin-views.patients.partials.sections._buttons')

                                            <div class="card-header d-flex flex-column align-items-start">
                                                <h5 class="card-title border-info">
                                                    @if (
                                                        $visit->laboratoryrequest &&
                                                            $visit->laboratoryrequest->tests &&
                                                            auth('admin')->user()->can('laboratory_request.list'))
                                                        @foreach ($visit->laboratoryrequest->tests as $key => $item)
                                                            <span class="pl-1">
                                                                {{ $item->test->test_name }}{{ $key < $visit->laboratoryrequest->tests->count() - 1 ? ',' : '' }}
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                    @if (auth('admin')->user()->can('radiology_request.list') &&
                                                            auth('admin')->user()->can('laboratory_request.list') &&
                                                            $visit->laboratoryrequest &&
                                                            $visit->radiologyRequest)
                                                        <span> || </span>
                                                    @endif
                                                    @if (
                                                        $visit->radiologyRequest &&
                                                            $visit->radiologyRequest->radiologies &&
                                                            auth('admin')->user()->can('radiology_request.list'))
                                                        @foreach ($visit->radiologyRequest->radiologies as $key => $item)
                                                            <span class="pl-1">
                                                                {{ $item->radiology->radiology_name }}{{ $key < $visit->radiologyRequest->radiologies->count() - 1 ? ',' : '' }}
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                </h5>
                                            </div>

                                            <div class="card-body">
                                                <div class="row align-items-center pb-3 border-bottom">
                                                    <!-- Main Content Section -->
                                                    <div
                                                        class="col-lg-11 col-md-10 col-12 d-flex flex-wrap flex-lg-nowrap px-0">
                                                        @php
                                                            $billing = $visit->billing;
                                                            $amountLeft = $billing
                                                                ? $billing->total_amount - $billing->amount_paid
                                                                : 0;
                                                        @endphp

                                                        <!-- Billing Status -->
                                                        <div class="col-12 col-sm-6 col-md-3 col-lg d-flex flex-column align-items-center px-3 mb-2 mb-lg-0 border border-sm"
                                                            style="border-right: 1px solid #ddd; border-lg:none;">
                                                            <strong>Visit Code</strong>
                                                            <p class="m-0">
                                                                {{ $visit->code }}</p>
                                                            {{-- @if ($billing)
                                                                @if ($billing->status === 'paid')
                                                                    <span class="badge"
                                                                        style="background-color: #28a745; color: #fff;">
                                                                        Paid
                                                                    </span>
                                                                @elseif ($billing->status === 'partial')
                                                                    <span class="badge"
                                                                        style="background-color: #ffc107; color: #000;">
                                                                        Partial ({{ $amountLeft }} Left)
                                                                    </span>
                                                                @elseif ($billing->status === 'unpaid')
                                                                    <span class="badge"
                                                                        style="background-color: #dc3545; color: #fff;">
                                                                        Unpaid ({{ $billing->total_amount }})
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-secondary">Unknown</span>
                                                                @endif --}}
                                                            {{-- <span class="badge text-white"
                                                                    style="background-color:
                                                                        {{ $billing->status === 'paid' ? '#28a745' : ($billing->status === 'partial' ? '#ffc107' : '#dc3545') }};
                                                                    color: {{ $billing->status === 'partial' ? '#000' : '#fff' }};">
                                                                    {{ ucwords($billing->status) }}
                                                                    @if ($billing->status === 'partial')
                                                                        ({{ $amountLeft }} Left)
                                                                    @elseif($billing->status === 'unpaid')
                                                                        ({{ $billing->total_amount }})
                                                                    @endif
                                                                </span>
                                                            @else
                                                                <span class="badge badge-secondary">N/A</span>
                                                            @endif --}}
                                                        </div>

                                                        <!-- Request Date -->
                                                        <div class="col-12 col-sm-6 col-md-3 col-lg d-flex flex-column align-items-center px-3 mb-2 mb-lg-0 border border-sm"
                                                            style="border-right: 1px solid #ddd; border-lg:none;">
                                                            <strong>Visit Date</strong>
                                                            <p class="m-0">
                                                                {{ $visit->created_at->format('M d, Y') }}<br>
                                                                {{ $visit->created_at->format('h:i A') }}</p>
                                                        </div>

                                                        <!-- Status -->
                                                        <div class="col-12 col-sm-6 col-md-3 col-lg d-flex flex-column align-items-center px-3 mb-2 mb-lg-0 border border-sm"
                                                            style="border-right: 1px solid #ddd; border-lg:none;">
                                                            <strong>Service</strong>
                                                            {{ $visit->serviceCategory->name }}</p>
                                                            {{-- <span class="badge text-white"
                                                                style="background-color:
                                                                    {{ $visit->status == 'completed' ? '#28a745' : ($visit->status == 'in process' ? '#ffc107' : ($visit->status == 'rejected' ? '#dc3545' : '#17a2b8')) }};
                                                                color: {{ $visit->status == 'in process' ? '#000' : '#fff' }};">
                                                                {{ ucwords($visit->status) }}
                                                            </span> --}}
                                                        </div>

                                                        <!-- Collected By -->
                                                        <div class="col-12 col-sm-6 col-md-3 col-lg d-flex flex-column align-items-center px-3 mb-2 mb-lg-0 border border-sm"
                                                            style="border-right: 1px solid #ddd; border-lg:none;">
                                                            <strong>Visit Type</strong>
                                                            <p class="m-0">
                                                                {{ $visit->visit_type }}</p>
                                                        </div>
                                                    </div>

                                                    <!-- Expand/Collapse Button -->
                                                    <div
                                                        class="col-lg-1 col-md-2 col-12 d-flex justify-content-lg-center justify-content-end px-0">
                                                        <button class="btn btn-link toggle-button"
                                                            id="symptomsSection{{ $visit->id }}" type="button"
                                                            data-toggle="collapse"
                                                            data-target="#symptomsSection{{ $visit->id }}"
                                                            style="display: block;">
                                                            <i
                                                                class="toggle-icon tio-{{ request()->get('active') == $visit->id ? 'remove' : 'add' }} font-weight-bold"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="collapse {{ request()->get('active') == $visit->id ? 'show' : '' }}"
                                                    id="symptomsSection{{ $visit->id }}">

                                                    @include('admin-views.patients.partials.sections._medical_record_diagnosis_and_vital_sign')

                                                    @include('admin-views.patients.partials.sections._pregnancy_section')

                                                    @include('admin-views.patients.partials.sections._laboratory_details')

                                                    @include('admin-views.patients.partials.sections._radiology_details')

                                                    @include('admin-views.patients.partials.sections._prescreptions')

                                                    @include('admin-views.patients.partials.sections._medical_documents')

                                                    @include('admin-views.patients.partials.sections._dental_chart_section')


                                                </div>
                                            </div>
                                        </div>

                                        <div class="my-4">
                                            <div class="text-center" style="margin-top: -18px;">
                                                <span
                                                    style="background: #fff; padding: 0 16px; color: #0a58ca; font-weight: bold; font-size: 1.1rem; letter-spacing: 1px;">
                                                    <i class="tio-star"></i> End of Visit of
                                                    {{ $visit->created_at->format('M d, Y h:i A') }}<i
                                                        class="tio-star"></i>
                                                </span>
                                            </div>
                                            <hr style="border-top: 2px dashed #0a58ca; margin: 0 0 1rem 0;">
                                        </div>
                                    @endforeach

                                    <!-- Pagination -->
                                    <div class="table-responsive mt-4 px-3">
                                        <div class="d-flex justify-content-end">
                                            {!! $visits->links() !!}
                                        </div>
                                    </div>
                                    @if (count($visits) == 0)
                                        <div class="text-center p-4">
                                            <img class="mb-3"
                                                src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                                                alt="Image Description" style="width: 7rem;">
                                            <p class="mb-0">{{ translate('No data to show') }}</p>
                                        </div>
                                    @endif

                                </div>
                                @include('admin-views.patients.partials.sections._patient_details')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add  Billing Service to billing Modal -->
    <div class="modal fade" id="add-service-billing-modal" tabindex="-1" role="dialog"
        aria-labelledby="serviceBillingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.service.add-service-billing') }}" method="POST">
                @csrf
                <input type="hidden" name="visit_id" id="modal_visit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Add Service to Billing') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="input-label">{{ translate('Select Billing Service') }}</label>
                            <select name="billing_service_id" class="form-control js-select2-custom" required>
                                <option value="">Choose a Service</option>
                                @foreach ($billingServices as $service)
                                    <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="input-label">{{ translate('Additional Notes') }}</label>
                            <textarea name="procedure_notes" class="form-control" rows="3"
                                placeholder="{{ translate('Enter any notes about the service (optional)') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ translate('Add to Billing') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin-views.patients.partials.modals._radiology_modals')
    @include('admin-views.patients.partials.modals._laboratory_modals')
    @include('admin-views.patients.partials.modals._specimen_modals')
    @include('admin-views.patients.partials.modals._in_and_out_prescreptions_modals')
    @include('admin-views.patients.partials.modals._pregnancy_modals')
    @include('admin-views.patients.partials.modals._medical_records_modals')
    @include('admin-views.patients.partials.modals._medical_document_modal')
    @include('admin-views.patients.partials.modals._visit_document_modal')
    @include('admin-views.patients.partials.modals._dental_chart_modal')

    <script>
        let emergencyPrescriptionStats = @json($emergencyPrescriptionStats);
    </script>
@endsection
@push('script')
@endpush
@push('script_2')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const body = document.body;

            console.log('object');
            console.log(body.classList);

            // If compact mode
            if (body.classList.contains("navbar-vertical-aside-compact-mode")) {
                body.classList.add("navbar-vertical-aside-compact-mini-mode");
            }
            // If default sidebar
            else {
                body.classList.add("navbar-vertical-aside-mini-mode");
            }
            console.log(body.classList);
        });
    </script>

    <script>
        function editDiagnosisTreatment(id) {
            const button = $(event.target);
            const originalText = disableButton(button);

            $.ajax({
                url: "{{ route('admin.diagnosis.edit', '') }}/" + id,
                type: "GET",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        const record = response.data;
                        $('#editDiagnosisTreatmentModal input[name="id"]').val(record.id);
                        $('#editDiagnosisTreatmentModal textarea[name="diagnosis"]').val(record.diagnosis);
                        $('#editDiagnosisTreatmentModal textarea[name="treatment"]').val(record.treatment);

                        // Set selected diseases
                        const diseaseIds = record.diseases.map(disease => disease.id);
                        $('#editDiagnosisTreatmentModal select[name="condition_ids[]"]').val(diseaseIds)
                            .trigger(
                                'change');

                        $('#editDiagnosisTreatmentModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                },
                complete: function() {
                    enableButton(button, originalText);
                }
            });
        }

        $('#edit_diagnosis_treatment_form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.diagnosis.update', '') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#editDiagnosisTreatmentModal').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });
    </script>
    <script src="{{ asset(config('app.asset_path') . '/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get current date and time
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Month is 0-indexed
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            // Format as YYYY-MM-DDTHH:MM:SS
            const datetime = `${year}-${month}-${day}T${hours}:${minutes}`;

            // Set the input value
            document.getElementById('specimen_taken_at').value = datetime;

            // --- LMP to EDD auto-calculation ---
            const lmpInput = document.querySelector('input[name="lmp"]');
            const eddInput = document.querySelector('input[name="edd"]');

            if (lmpInput && eddInput) {
                lmpInput.addEventListener('change', function() {
                    const lmpDate = new Date(this.value);
                    if (!isNaN(lmpDate)) {
                        const eddDate = new Date(lmpDate);
                        eddDate.setDate(eddDate.getDate() + 280);
                        const formatted = eddDate.toISOString().split('T')[0];
                        eddInput.value = formatted;
                    }
                });
            }
        });
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
                    image: '{{ asset(config('/asset') . '/admin/img/400x400/img2.jpg') }}',
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
                rowHeight: '100px',
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
        function loadPdf(pdfUrl) {
            // Load the PDF into the iframe when the modal is triggered
            document.getElementById('pdfIframe').src = pdfUrl;
        }

        function loadLabPdf(pdfUrl) {
            // Load the PDF into the iframe when the modal is triggered
            document.getElementById('LabpdfIframe').src = pdfUrl;
        }

        function loadRadPdf(pdfUrl) {
            // Load the PDF into the iframe when the modal is triggered
            document.getElementById('RadpdfIframe').src = pdfUrl;
        }
    </script>
    <script>
        function showBillingToast(event) {
            event.preventDefault();
            event.stopPropagation();

            // Use your preferred toast/alert method. Here is a simple Bootstrap example:
            toastr.error(
                "This visit has unpaid or partially paid bills. Please ask the patient to complete the payment first.");
        }
    </script>

    <script>
        // Capture visit ID when opening the modal
        // $('#add_new_nurse_assessment_test').on('click', function () {
        //     let visitId = $(this).data('visit-id');
        //     $('#visit_id').val(visitId);
        // });

        $(document).on('click', '#add_new_nurse_assessment_test', function() {
            var visitId = $(this).data('visit-id');
            $('#nurseAssessmentForm input[name="visit_id"]').val(visitId);
        });

        $(document).on('click', '#add_new_labour_followup_test', function() {
            var visitId = $(this).data('visit-id');
            $('#labourFollowupForm input[name="visit_id"]').val(visitId);
        });


        // Update unit name dynamically when selecting a category
        $('#category_id').on('change', function() {
            let unitName = $(this).find(':selected').data('unit');
            $('#unit_name').val(unitName || '');
        });

        $('#add-nurse_assessment_test').on('show.bs.modal', function(e) {
            var visitId = $(e.relatedTarget).data(
                'visit-id'); // Get the visit_id from the button that opened the modal
            $('#visit_id').val(visitId); // Set the visit_id in the hidden field

            var nurseId = $('input[name="nurse_id"]').val(); // Get the nurse_id
            // Generate the vital sign input fields dynamically
            var vitalSignsFields = '';
            vitalSigns.forEach(function(sign) {
                vitalSignsFields += `
        <div class="form-group col-md-6">
            <label for="test_value_${sign.id}">${sign.name} (${sign.unit.code})</label>
            <input type="text" class="form-control" name="test_values[${sign.id}]" id="test_value_${sign.id}" placeholder="Enter value for ${sign.name}">
            <input type="hidden" name="unit_names[${sign.id}]" value="${sign.unit.code}">
        </div>
    `;
            });


            // Append the vital signs fields to the modal
            $('#vitalSignsFields').html(vitalSignsFields);
        });

        $('#add-labour_followup_test').on('show.bs.modal', function(e) {
            var visitId = $(e.relatedTarget).data(
                'visit-id'); // Get the visit_id from the button that opened the modal
            $('#visit_id').val(visitId); // Set the visit_id in the hidden field

            var nurseId = $('input[name="nurse_id"]').val(); // Get the nurse_id
            // Generate the vital sign input fields dynamically
            var labourFollowupFields = '';
            labourFollowups.forEach(function(sign) {
                labourFollowupFields += `
        <div class="form-group col-md-6">
            <label for="test_value_${sign.id}">${sign.name} (${sign.unit.code})</label>
            <input type="text" class="form-control" name="test_values[${sign.id}]" id="test_value_${sign.id}" placeholder="Enter value for ${sign.name}">
            <input type="hidden" name="unit_names[${sign.id}]" value="${sign.unit.code}">
        </div>
    `;
            });


            // Append the vital signs fields to the modal
            $('#labourFollowupFields').html(labourFollowupFields);
        });



        // Form submission
        $('#nurseAssessmentForm').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.nurse_assessment.store') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire('Success', 'Vital signs recorded successfully!', 'success');
                    $('#add-nurse_assessment_test').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to save vital signs!', 'error');
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        $('#labourFollowupForm').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.labour_followup.store') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire('Success', 'Labour follow ups  recorded successfully!', 'success');
                    $('#add-labour_followup_test').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to save Lavbour follow ups!', 'error');
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
        $(document).on('click', '#add_new_diagnosis_treatment', function() {
            var visitId = $(this).data('visit-id');
            $('#diagnosis_treatment_form input[name="visit_id"]').val(visitId);
        });
        $('#diagnosis_treatment_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.diagnosis.store') }}', // Replace with your actual route name
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    toastr.success('{{ translate('Diagnosis & Treatment Added successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-diagnosis-treatment').modal('hide'); // Close the modal
                    $('#add-diagnosis-treatment form')[0].reset();
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

                    setTimeout(function() {
                        // Optionally redirect or perform other actions after a delay
                        // location.href = '{{ route('admin.patient.list') }}';
                    }, 2000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Handle validation errors
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0], {
                                closeButton: true,
                                progressBar: true
                            });
                        });

                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Handle generic error messages
                        toastr.error(xhr.responseJSON.message, {
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
        // Set visit_id when modal opens

        $('#add_new_Prescirption_test').on('click', function() {
            let visitId = $(this).data('visit-id');
            $('#visit_id').val(visitId);
        });
        $('#add_new_Emergency_Prescirption_test').on('click', function() {
            let visitId = $(this).data('visit-id');
            $('#emergency_visit_id').val(visitId);
        });
    </script>
    <script>
        $('#add-service-billing-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var visitId = button.data('visit-id')
            $('#modal_visit_id').val(visitId)
        })
    </script>
    <script>
        $(document).on('click', '#add_new_medical_record', function() {
            var visitId = $(this).data('visit-id');
            $('#medical_test_form input[name="visit_id"]').val(visitId);
        });

        $('#medical_test_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.medical_record.store') }}', // Use the correct route name
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    toastr.success('{{ translate('Medical Record Created successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-medical-record').modal('hide'); // Close the modal
                    $('#medical_test_form')[0].reset();
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

                    setTimeout(function() {
                        // Optionally redirect or perform other actions after a delay
                        // location.href = '{{ route('admin.patient.list') }}';
                    }, 2000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Handle validation errors
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0], {
                                closeButton: true,
                                progressBar: true
                            });
                        });

                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Handle generic error messages
                        toastr.error(xhr.responseJSON.message, {
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

                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });



        $(document).on('click', '#add_new_radiology_result', function() {
            var radiologyRequestId = $(this).data('radiology-request-id');
            $('#radiology_test_form input[name="radiology_request_id"]').val(radiologyRequestId);
            $.ajax({
                url: '{{ route('admin.radiology_request.fetchTestType') }}',
                type: 'GET',
                data: {
                    radiologyRequestId: radiologyRequestId,
                },
                success: function(data) {
                    $('#radiology_name_result').html('');
                    $('#radiology_name_result').append(
                        '<option value="" selected disabled>Select Radiology Type</option>'
                    );
                    data.forEach(function(radiology) {
                        $('#radiology_name_result').append('<option value="' + radiology
                            .radiology_id +
                            '" data-radiology-request-test-id="' + radiology
                            .radiology_request_test_id + '">' + radiology.radiology_name +
                            '</option>');
                    });
                },
                error: function(error) {
                    console.error('Error fetching tests:', error);
                }
            });
        });
        $(document).on('change', '#radiology_name_result', function() {
            var selectedOption = $(this).find('option:selected');
            var radiologyId = selectedOption.val(); // Get the selected radiology ID
            var radiologyRequestTestId = selectedOption.data(
                'radiology-request-test-id'); // Get the data attribute

            // Update the hidden input with the selected test's radiology_request_test_id
            $('input[name="radiology_request_test_id"]').val(radiologyRequestTestId);

            // Make an AJAX request to fetch the test attributes
            $.ajax({
                url: '{{ route('admin.radiology_attribute.fetchRadiologyAttributes') }}',
                type: 'GET',
                data: {
                    radiologyId: radiologyId,
                },
                success: function(data) {
                    console.log(data);
                    $('#radiology_attributes_container').html(''); // Clear any existing fields
                    const radiology = data.radiology;
                    data.attributes.forEach(function(attribute) {
                        const labelText = attribute.attribute_name.replace(/_/g, ' ');

                        let inputField = '';

                        if (attribute.result_type === 'paragraph') {
                            inputField = '<div class="row pl-2">' +
                                '<div class="col-12">' +
                                '<div class="form-group">' +
                                '<label class="input-label" for="attribute_' + attribute.id +
                                '">' +
                                labelText +
                                (attribute.default_required ?
                                    '<span class="input-label-secondary text-danger">*</span>' :
                                    '') +
                                '</label>' +
                                '<textarea name="attribute_' + attribute.id +
                                '" id="attribute_' + attribute.id +
                                '" class="ckeditor form-control" rows="3" ' +
                                (attribute.default_required ? '' : '') +
                                '>' + attribute.template + '</textarea>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        } else {
                            inputField = '<div class="row pl-2">' +
                                '<div class="col-12">' +
                                '<div class="form-group">' +
                                '<label class="input-label" for="attribute_' + attribute.id +
                                '">' +
                                labelText +
                                (attribute.default_required ?
                                    '<span class="input-label-secondary text-danger">*</span>' :
                                    '') +
                                '</label>' +
                                '<input type="text" name="attribute_' + attribute.id +
                                '" class="form-control" ' +
                                (attribute.default_required ? 'required' : '') + ' />' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        }

                        $('#radiology_attributes_container').append(inputField);
                    });

                    // ✅ After the loop, check if description is from_text and show additional note
                    if (radiology.description === 'from_text' && radiology.additional_notes) {
                        const noteField = '<div class="row pl-2">' +
                            '<div class="col-12">' +
                            '<div class="form-group">' +
                            '<label class="input-label">Radiology Additional Notes</label>' +
                            '<div class="form-control" style="min-height: 300px; overflow-y: auto; background: #f9f9f9;">' +
                            radiology.additional_notes +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';


                        $('#radiology_attributes_container').append(noteField);
                    }

                    // ✅ Reinitialize CKEditor 5 for dynamically added textareas
                    $('#radiology_attributes_container textarea.ckeditor').each(function() {
                        let id = $(this).attr('id');

                        if (!window.editors) {
                            window.editors = {}; // create global storage if missing
                        }

                        if (!window.editors[id]) {
                            ClassicEditor
                                .create(this)
                                .then(editor => {
                                    window.editors[id] = editor;

                                    // ✅ Live syncing: keep textarea updated on every change
                                    editor.model.document.on('change:data', () => {
                                        $('#' + id).val(editor.getData());
                                    });
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        }
                    });

                },
                error: function(error) {
                    console.error('Error fetching test attributes:', error);
                }
            });
        });
        $('#radiology_test_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
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

                    toastr.success('{{ translate('Test result stored successfully.') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-result_test').click();
                    $('#lab_test_form')[0].reset();
                    $('#slot_id').html('');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

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
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });




        $(document).on('click', '#add_new_result', function() {
            var laboratoryRequestId = $(this).data('laboratory-request-id');
            $('#lab_test_form input[name="laboratory_request_id"]').val(laboratoryRequestId);

            $.ajax({
                url: '{{ route('admin.laboratory_request.fetchTestType') }}',
                type: 'GET',
                data: {
                    laboratoryRequestId: laboratoryRequestId,
                },
                success: function(data) {
                    $('#test_name_result').html('');
                    $('#test_name_result').append(
                        '<option value="" selected disabled>Select Test Type</option>'
                    );
                    data.forEach(function(test) {
                        $('#test_name_result').append('<option value="' + test.test_id +
                            '" data-laboratory-request-test-id="' + test
                            .laboratory_request_test_id + '">' + test.test_name + '/ ' +
                            test.category +
                            '</option>');
                    });
                },
                error: function(error) {
                    console.error('Error fetching tests:', error);
                }
            });
        });
        $(document).on('change', '#test_name_result', function() {
            var selectedOption = $(this).find('option:selected');
            var testId = selectedOption.val(); // Get the selected test ID
            var laboratoryRequestTestId = selectedOption.data(
                'laboratory-request-test-id'); // Get the data attribute

            // Update the hidden input with the selected test's laboratory_request_test_id
            $('input[name="laboratory_request_test_id"]').val(laboratoryRequestTestId);

            // Make an AJAX request to fetch the test attributes
            $.ajax({
                url: '{{ route('admin.test_attribute.fetchTestAttributes') }}',
                type: 'GET',
                data: {
                    testId: testId,
                },
                success: function(data) {
                    $('#attributes_container').html(''); // Clear any existing fields
                    data.forEach(function(attribute) {
                        var inputField = '';

                        // If the attribute has options, create a select dropdown
                        if (attribute.has_options == 1) {
                            inputField = '<div class="row pl-2">' +
                                '<div class="col-12">' +
                                '<div class="form-group">' +
                                '<label class="input-label" for="attribute_' + attribute.id +
                                '">' +
                                attribute.attribute_name + (attribute.default_required ?
                                    '<span class="input-label-secondary text-danger">*</span>' :
                                    '') +
                                '</label>' +

                                '<select name="attribute_' + attribute.id +
                                '" class="form-control js-select2-custom attribute-select" ' +
                                (attribute.default_required ? 'required' : '') + '>' +

                                '<option value="" disabled selected>select ' + attribute
                                .attribute_name + ' result</option>';
                            // Add the options to the select field
                            attribute.options.forEach(function(option) {
                                inputField += '<option value="' + option.option_value +
                                    '">' +
                                    option.option_value + '</option>';
                            });

                            inputField += '</select>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        } else {
                            // If the attribute does not have options, create a text input
                            inputField = '<div class="row pl-2">' +
                                '<div class="col-12">' +
                                '<div class="form-group">' +
                                '<label class="input-label" for="attribute_' + attribute.id +
                                '">' +
                                attribute.attribute_name +
                                (attribute.default_required ?
                                    '<span class="input-label-secondary text-danger">*</span>' :
                                    '') +
                                '</label>' +
                                '<input type="text" name="attribute_' + attribute.id +
                                '" class="form-control" ' +
                                (attribute.default_required ? 'required' : '') + ' />' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        }
                        $('#attributes_container').append(inputField);
                    });
                },
                error: function(error) {
                    console.error('Error fetching test attributes:', error);
                }
            });
        });
        $('#lab_test_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.laboratory_result.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    toastr.success('{{ translate('Test result stored successfully.') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-result_test').click();
                    $('#lab_test_form')[0].reset();
                    $('#slot_id').html('');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

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
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        function ucwords(str) {
            if (!str) return str; // Check if the string is empty or undefined
            return str.replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
        }

        function viewRadiologyResult(radiologyResults) {
            const radiologyResult = radiologyResults;
            // Populate the overall test result section
            $('#viewRadiologyResultModalLabel').html(`
            ${radiologyResult.radiology_request_test.radiology.radiology_name} Radiology Result`);
            $('#radiologyResultStatus').html(`
                <strong>Status:</strong> ${ucwords(radiologyResult.process_status || '')}<br>
                <strong>Verification Status:</strong> ${ucwords(radiologyResult.verify_status || '')}<br>
                <strong>Comments:</strong> ${radiologyResult.comments || 'No comments available.'}
            `);

            // Display additional notes if available
            $('#radiologyResultAdditionalNote').html(`
                <strong>Additional Note:</strong> ${radiologyResult.additional_note || 'No additional notes.'}
            `);

            $('#radiologyResultProcessedBy').html(`
                <strong>Processed By:</strong> ${radiologyResult.processed_by ? radiologyResult.processed_by.f_name + ' ' + radiologyResult.processed_by.l_name : 'Not Processed Yet'}
            `);

            $('#radiologyResultVerifiedBy').html(`
                <strong>Verified By:</strong> ${radiologyResult.verified_by ? radiologyResult.verified_by.f_name + ' ' + radiologyResult.verified_by.l_name : 'Not Verified Yet'}
            `);

            // Handle images
            let images = radiologyResult.image || []; // Expecting `image` as an array

            if (typeof images === 'string') {
                try {
                    images = JSON.parse(images);
                } catch (e) {
                    console.error('Failed to parse image field:', e);
                    images = [];
                }
            }
            if (images.length > 0) {
                let imageHtml = '';
                images.forEach((image) => {
                    // Wrap the image in an anchor tag for clickable functionality
                    imageHtml +=
                        `<a href="/storage/app/public/assets/${image}" target="_blank">
                            <img src="/storage/app/public/assets/${image}"
                            alt="Test Image"
                            class="img-fluid"
                            style="max-width: 50px; margin-top: 10px; cursor: pointer;">
                        </a>`;
                });
                $('#radiologyResultImage').html(imageHtml);
            } else {
                $('#radiologyResultImage').html('<strong>No images available.</strong>');
            }

            // Populate attributes
            if (radiologyResult.attributes && Array.isArray(radiologyResult.attributes)) {
                let attributesHtml = '';
                radiologyResult.attributes.forEach((attribute) => {
                    attributesHtml += `
                <tr>
                    <td>${attribute.attribute.attribute_name}</td>
                    <td>${attribute.result_value || 'No value provided'}</td>
                </tr>
            `;
                });
                $('#radiologyResultAttributes').html(attributesHtml);
            } else {
                $('#radiologyResultAttributes').html(
                    '<tr><td colspan="3" class="text-center">No attributes available.</td></tr>');
            }

            // Show the modal
            $('#viewRadiologyResultModal').modal('show');
        }
    </script>
    <script>
        $(document).ready(function() {
            // Handle quick add medicine form submit
            $('#quickAddMedicineForm').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                const submitButton = $(this).find('button[type="submit"]');
                const originalText = disableButton(submitButton);
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        if (response.success && response.medicine) {
                            // Append new medicine to select and select it
                            var med = response.medicine;
                            var option = new Option(med.name, med.id, true, true);
                            $('#medicine_id').append(option).trigger('change');
                            $('#addMedicineModal').modal('hide');
                            $form[0].reset();
                            toastr.success('new medicine added and selected successfully!', {
                                closeButton: true,
                                progressBar: true
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Error occurred please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        toastr.error(msg, {
                            closeButton: true,
                            progressBar: true
                        });
                    },
                    complete: function() {
                        setTimeout(function() {
                            enableButton(submitButton, originalText);
                        }, 2000);
                    }
                });
            });

            // Reset form when modal closes
            $('#addMedicineModal').on('hidden.bs.modal', function() {
                $('#quickAddMedicineForm')[0].reset();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        function handleRadiologyResultApprovalStatusClick(resultId, currentApprovalStatus) {
            @if (auth('admin')->user()->can('radiology_result.verify-status.update'))
                // If the user has permission, show the update modal
                showUpdateRadiologyResultApprovalStatusModal(resultId, currentApprovalStatus);
            @else
                // If the user does not have permission, show an interactive popup
                toastr.warning('You do not have permission to update result approval status.', 'Access Denied', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
            @endif
        }

        function showUpdateRadiologyResultApprovalStatusModal(resultId, currentApprovalStatus) {
            // Set up modal content dynamically
            document.getElementById('approvalRadiologyResultIdInput').value = resultId;
            document.getElementById('approvalRadiologyStatusInput').value = currentApprovalStatus;

            // Show the modal
            $('#updateRadiologyResultApprovalStatusModal').modal('show');
        }
        $('#updateRadiologyResultApprovalStatusForm').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.radiology_result.verify-status.update') }}',
                data: formData,
                success: function(response) {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                    $('#updateRadiologyResultApprovalStatusModal').modal('hide');
                    toastr.success('Radiology Result Approval status updated successfully!', {
                        closeButton: true,
                        progressBar: true,
                    });
                },
                error: function(error) {
                    console.error(error);
                    toastr.error('Failed to update result approval status. Please try again.', {
                        closeButton: true,
                        progressBar: true,
                    });
                },
            });
        });



        function handleIssuedStatusClick(paymentTiming, billingStatus, resultId, currentStatus, quantity, issude,
            cancelled) {
            @if (auth('admin')->user()->can('emergency_prescriptions.issued-status.update'))
                if (currentStatus == 'pending' || (quantity - issude - cancelled) > 0) {
                    showUpdateIssuedStatusModal(resultId, currentStatus, paymentTiming, billingStatus, quantity, issude,
                        cancelled);
                } else {
                    toastr.warning('You can update only pending status.', 'Access Denied', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3000,
                    });
                }
            @else
                // If the user does not have permission, show an interactive popup
                toastr.warning('You do not have permission to update this issued status.', 'Access Denied', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
            @endif
        }

        function handleRadiologyResultStatusClick(resultId, currentStatus) {
            @if (auth('admin')->user()->can('radiology_result.process-status.update'))
                // If the user has permission, show the update modal
                showUpdateRadiologyResultStatusModal(resultId, currentStatus);
            @else
                // If the user does not have permission, show an interactive popup
                toastr.warning('You do not have permission to update this result process status.', 'Access Denied', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
            @endif
        }

        function showUpdateStatusModal(specimenId, currentStatus) {
            // Pre-fill modal with specimen details
            $('#specimenIdInput').val(specimenId);
            $('#specimenStatusInput').val(currentStatus);

            // Show modal
            $('#updateSpecimenStatusModal').modal('show');
        }

        function showUpdateResultStatusModal(resultId, currentStatus) {
            // Pre-fill modal with specimen details
            $('#resultIdInput').val(resultId);
            $('#resultStatusInput').val(currentStatus);

            // Show modal
            $('#updateResultStatusModal').modal('show');
        }

        function showUpdateIssuedStatusModal(resultId, currentStatus, paymentTiming, billingStatus, quantity, issude,
            cancelled) {
            // Pre-fill modal with specimen details
            $('#issuedResultIdInput').val(resultId);
            $('#paymentTimingInput').val(paymentTiming);
            $('#billingStatusInput').val(billingStatus);

            $('#isued_quantity').attr('max', quantity - issude - cancelled);

            $('#stat-prescribed').text(quantity);
            $('#stat-issued').text(issude);
            $('#stat-cancelled').text(cancelled);
            $('#stat-pending').text(quantity - issude - cancelled);
            // Show modal
            $('#updateIssuedStatusModal').modal('show');
        }

        function showUpdateRadiologyResultStatusModal(resultId, currentStatus) {
            // Pre-fill modal with specimen details
            $('#radiologyResultIdInput').val(resultId);
            $('#radiologyResultStatusInput').val(currentStatus);

            // Show modal
            $('#updateRadiologyResultStatusModal').modal('show');
        }

        $('#updateRadiologyResultStatusForm').submit(function(e) {
            e.preventDefault();

            // Perform AJAX request to update the specimen status
            const formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.radiology_result.process-status.update') }}', // Replace with your actual route
                data: formData,
                success: function(response) {
                    $('#updateRadiologyResultStatusModal').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                    toastr.success('Radiology Result Process Status Updated Successfully!', {
                        closeButton: true,
                        progressBar: true
                    });
                },
                error: function(error) {
                    console.error(error);
                    toastr.error('Failed to update status. Please try again.', {
                        closeButton: true,
                        progressBar: true
                    });
                }
            });
        });

        $('#updateSpecimenStatusForm').submit(function(e) {
            e.preventDefault();

            // Perform AJAX request to update the specimen status
            const formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.specimen.status.update') }}', // Replace with your actual route
                data: formData,
                success: function(response) {
                    $('#updateSpecimenStatusModal').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                    toastr.success('Specimen Status Updated Successfully!', {
                        closeButton: true,
                        progressBar: true
                    });
                },
                error: function(error) {
                    console.error(error);
                    toastr.error('Failed to update status. Please try again.', {
                        closeButton: true,
                        progressBar: true
                    });
                }
            });
        });

        $('#updateResultStatusForm').submit(function(e) {
            e.preventDefault();

            // Perform AJAX request to update the specimen status
            const formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.laboratory_result.process-status.update') }}', // Replace with your actual route
                data: formData,
                success: function(response) {
                    $('#updateResultStatusModal').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                    toastr.success('Result Process Status Updated Successfully!', {
                        closeButton: true,
                        progressBar: true
                    });
                },
                error: function(error) {
                    console.error(error);
                    toastr.error('Failed to update status. Please try again.', {
                        closeButton: true,
                        progressBar: true
                    });
                }
            });
        });

        $('#updateIssuedStatusForm').submit(function(e) {
            e.preventDefault();

            const selectedStatus = $('#issuedResultStatusInput').val();
            const paymentTiming = $('#paymentTimingInput').val();
            const billingStatus = $('#billingStatusInput').val();

            // Check the logic
            if (selectedStatus === 'issued' && paymentTiming === 'prepaid' && billingStatus !== 'paid') {
                toastr.error('Patient must pay first before issuing prepaid medicine.', 'Billing Required', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 3000,
                });
                return; // Stop form submission
            }
            // Perform AJAX request to update the specimen status
            const formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.emergency_prescriptions.issued-status.update') }}', // Replace with your actual route
                data: formData,
                success: function(response) {
                    $('#updateIssuedStatusModal').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                    toastr.success('Issued Status Updated Successfully!', {
                        closeButton: true,
                        progressBar: true
                    });
                },
                error: function(error) {
                    console.error(error);
                    toastr.error(error.responseJSON.message ? error.responseJSON.message :
                        'Failed to update status. Please try again.', {
                            closeButton: true,
                            progressBar: true
                        });
                }
            });
        });

        function isValidJson(jsonString) {
            try {
                const parsed = JSON.parse(jsonString);
                return parsed;
            } catch (e) {
                console.error("Invalid JSON:", e.message);
                return null;
            }
        }

        function viewSpecimen(specimen) {
            const specimenData = specimen;
            // Helper function for null or undefined handling
            const getValue = (value, defaultValue = 'Not Specified') => value ? value : defaultValue;

            // Helper function to determine badge class based on status
            const getBadgeClass = (status) => {
                switch (status) {
                    case 'approved':
                        return 'badge-success';
                    case 'checking':
                        return 'badge-warning';
                    case 'rejected':
                        return 'badge-danger';
                    default:
                        return 'badge-info';
                }
            };

            if (!specimen) {
                document.getElementById('specimenDetailsContent').innerHTML = `
            <p class="text-danger">No specimen data available.</p>`;
                $('#viewSpecimenModal').modal('show');
                return;
            }

            const formatDate = (dateString) => {
                if (!dateString) return 'Not Specified';
                const date = new Date(dateString);
                return new Intl.DateTimeFormat('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                }).format(date);
            };
            // Define fields dynamically
            const fields = [{
                    label: 'Specimen Code',
                    value: getValue(specimenData.specimen_code)
                },
                {
                    label: 'Status',
                    value: `<span class="badge ${getBadgeClass(specimenData.status)}">${getValue(specimenData.status)}</span>`
                },
                {
                    label: 'Origin Type',
                    value: getValue(specimenData.origin_type)
                },
                {
                    label: 'Notes',
                    value: getValue(specimenData.notes)
                },
                {
                    label: 'Specimen Taken At',
                    value: formatDate(specimenData.specimen_taken_at)
                },
                {
                    label: 'Checking Start Time',
                    value: formatDate(specimenData.checking_start_time)
                },
                {
                    label: 'Checking End Time',
                    value: formatDate(specimenData.checking_end_time)
                },
                // {
                //     label: 'Approval Start Time',
                //     value: formatDate(specimenData.approval_start_time)
                // },
                // {
                //     label: 'Approval End Time',
                //     value: formatDate(specimenData.approval_end_time)
                // },
            ];

            // Build the modal content dynamically
            let contentHtml = '<div class="row">';
            fields.forEach(field => {
                contentHtml += `
            <div class="col-md-6 mb-3">
                <strong>${field.label}:</strong>
                <p class="mb-0">${field.value}</p>
            </div>`;
            });
            contentHtml += '</div>';

            // Inject the content and show the modal
            document.getElementById('specimenDetailsContent').innerHTML = contentHtml;
            $('#viewSpecimenModal').modal('show');
        }

        function editSpecimen(specimen) {
            $('#editSpecimenModal').modal('show');

            $('#edit_specimen_form input[name="id"]').val(specimen.id);
            $('#edit_specimen_form input[name="specimen_code"]').val(specimen.specimen_code);
            $('#edit_specimen_form select[name="specimen_type_id"]').val(specimen.specimen_type_id).trigger('change');
            $('#edit_specimen_form select[name="specimen_origin_id"]').val(specimen.specimen_origin_id).trigger('change');
            $('#edit_specimen_form input[name="specimen_taken_at"]').val(specimen.specimen_taken_at);

            // Populate selected tests
            if (specimen.laboratory_request_tests && Array.isArray(specimen.laboratory_request_tests)) {
                const testIds = specimen.laboratory_request_tests.map(test => test.id);
                $('#edit_specimen_form select[name="laboratory_request_test_ids[]"]').val(testIds).trigger('change');
            }
        }

        $('#edit_specimen_form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: '{{ route('admin.specimen.update') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    toastr.success('{{ translate('Specimen updated successfully!') }}');
                    $('#editSpecimenModal').modal('hide');

                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                },
                error: function(xhr) {
                    if (xhr.responseJSON?.error) {
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        toastr.error('{{ translate('Something went wrong!') }}');
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
        $('#medical_lab_test_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.specimen.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    toastr.success('{{ translate('Specimen Saved successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-medical_lab_test').click();
                    $('#medical_lab_test_form')[0].reset();
                    $('#slot_id').html('');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

                    setTimeout(function() {
                        // location.href = '{{ route('admin.patient.list') }}';
                    }, 2000);
                },
                error: function(xhr, textStatus, errorThrown, error) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message, {
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
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        $(document).on('click', '#add_new_medical_lab_test', function() {
            var laboratoryRequestId = $(this).data('laboratory-request-id');
            $('#medical_lab_test_form input[name="laboratory_request_id"]').val(laboratoryRequestId);

            // Initialize Select2 with AJAX
            // $('#test_name').select2({
            $.HSCore.components.HSSelect2.init($('#test_name'), {
                placeholder: "Select Test Type",
                ajax: {
                    url: "{{ route('admin.specimen.get.tests') }}", // Use Laravel route
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            laboratory_request_id: laboratoryRequestId, // Send ID to backend
                            q: params.term, // Search term
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {

                        params.page = params.page || 1;

                        var formattedResults = data.tests.map(function(test) {
                            return {
                                id: test.test_id,
                                text: test.test_name + '/ ' + test.category
                            };
                        });

                        return {
                            results: formattedResults,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
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
            $('.collapse').on('show.bs.collapse', function() {
                var targetId = $(this).attr('id');
                var button = $('[data-target="#' + targetId + '"]');
                button.find('i.toggle-icon').removeClass('tio-add').addClass('tio-remove');
            });

            $('.collapse').on('hide.bs.collapse', function() {
                var targetId = $(this).attr('id');
                var button = $('[data-target="#' + targetId + '"]');
                button.find('i.toggle-icon').removeClass('tio-remove').addClass('tio-add');
            });
        });

        // $(document).ready(function() {
        //     // Add an event listener for the Bootstrap collapse event
        //     $('.toggle-button').on('click', function() {
        //         console.log($(this).attr('id'));
        //         var icon = $('i.toggle-icon', this);

        //         if ($(this).attr('aria-expanded') === 'false') {
        //             icon.removeClass('tio-add').addClass('tio-remove');
        //         } else {
        //             icon.removeClass('tio-remove').addClass('tio-add');
        //         }
        //     });
        // });
    </script>
    <!-- Your JavaScript code -->

    <script>
        $(document).on('click', '#add_new_medical_history', function() {
            var visitId = $(this).data('visit-id');
            $('#laboratory_request_form input[name="visit_id"]').val(visitId);
        });
        $('#laboratory_request_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            var formData = new FormData(this);

            // // Ensure the selected test IDs are included
            // $('#test_ids option:selected').each(function() {
            //     formData.append('test_ids[]', $(this).val());
            // });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.laboratory_request.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    toastr.success('{{ translate('Laboratory Request Scheduled successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-laboratory_request').click();
                    $('#laboratory_request_form')[0].reset();
                    $('#slot_id').html('');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

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
        $(document).on('click', '#add_new_radiology_request', function() {
            var visitId = $(this).data('visit-id');
            $('#radiology_request_form input[name="visit_id"]').val(visitId);
        });
        $('#radiology_request_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.radiology_request.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    toastr.success('{{ translate('Radiology Request Scheduled successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-radiology_request').click();
                    $('#radiology_request_form')[0].reset();
                    $('#slot_id').html('');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

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
        $('#history_lab_test_form').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.laboratory_request.store') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    toastr.success('{{ translate('Labbratory Record Created successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-laboratory').click();
                    $('#history_lab_test_form')[0].reset();
                    $('#slot_id').html('');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

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
        $('#newbornForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let id = $('#newborn_id').val();
            let url = id ? '/admin/newborns/' + id : '/admin/newborns';
            let method = id ? 'POST' : 'POST';

            if (id) {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                url: url,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#newbornModal').modal('hide');
                    toastr.success('Saved successfully!');
                    // Optionally refresh the page or datatable
                },
                error: function(err) {
                    toastr.error('Error saving newborn data', err);
                    console.log(err.responseJSON);
                }
            });
        });
    </script>
    <script>
        $('#dischargeForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let id = $('#discharge_id').val();
            let url = id ?
                `/admin/discharge/${id}` :
                "{{ route('admin.discharge.store') }}";

            let method = id ? 'POST' : 'POST';

            if (id) {
                formData.append('_method', 'PUT');
            }
            for (let [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#dischargeModal').modal('hide');
                    $('#dischargeForm')[0].reset();

                    toastr.success(res.message || 'Saved successfully!');
                },
                error: function(xhr) {
                    let errorMsg = 'Something went wrong!';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        } else if (typeof xhr.responseJSON === 'string') {
                            errorMsg = xhr.responseJSON;
                        } else if (xhr.status === 422) {
                            // Laravel validation errors
                            const errors = xhr.responseJSON.errors;
                            errorMsg = '';
                            for (const key in errors) {
                                errorMsg += errors[key][0] + '<br>';
                            }
                        }
                    }

                    toastr.error(errorMsg);
                }
            });

        });

        $('#btnCreateDischarge').on('click', function() {
            $('#dischargeForm')[0].reset(); // Reset all fields
            $('#discharge_id').val(''); // Clear hidden ID (so it performs "create")
            $('#dischargeModal').modal('show'); // Show modal
            var visitId = $(this).data('visit-id');
            $('#dischargeForm input[name="visit_id"]').val(visitId);
        });


        // Function to populate the modal for editing
        function editDischarge(data) {
            $('#discharge_id').val(data.id);
            $('#visit_id').val(data.visit_id);
            $('#ward_id').val(data.ward_id);
            $('#bed_id').val(data.bed_id);
            $('#admission_date').val(data.admission_date);
            $('#discharge_date').val(data.discharge_date);
            $('#discharge_type').val(data.discharge_type);
            $('#discharge_notes').val(data.discharge_notes);
            $('#remarks').val(data.remarks);
            $('#attending_physician').val(data.attending_physician);
            $('#dischargeModal').modal('show');
        }

        // Example: attach this to edit buttons
        $(document).on('click', '.btn-edit-discharge', function() {
            let discharge = $(this).data('discharge');
            editDischarge(discharge);
        });
    </script>
    <script>
        // Open modal for creating new history sheet
        $('#btnAddPrenatalVisitHistory').on('click', function() {
            $('#prenatalVisitHistoryForm')[0].reset();
            var visitId = $(this).data('visit-id');
            $('#prenatalVisitHistoryForm input[name="visit_id"]').val(visitId);

            $('#history_sheet_id').val('');
            $('#prenatalVisitHistoryModal').modal('show');
        });

        // Open modal for editing existing history sheet
        $('#btn-edit-prenatal-visit-history').on('click', function() {
            const id = $(this).data('id');

            $.get(`/admin/prenatal-visit-history/edit/${id}`, function(record) {
                $('#history_sheet_id').val(record.id);
                $('#visit_id').val(record.visit_id);
                $('[name="history"]').val(record.history);
                $('[name="physical_findings"]').val(record.physical_findings);
                $('[name="progress_notes"]').val(record.progress_notes);
                $('[name="remarks"]').val(record.remarks);

                $('#prenatalVisitHistoryModal').modal('show');
            });
        });

        // Submit form (store or update)
        $('#prenatalVisitHistoryForm').on('submit', function(e) {
            e.preventDefault();

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            const formData = new FormData(this);
            const id = $('#history_sheet_id').val();
            const url = id ?
                `/admin/prenatal-visit-history/update/${id}` :
                `{{ route('admin.prenatal_visit_history.store') }}`;

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    toastr.success(data.message, {
                        closeButton: true,
                        progressBar: true
                    });

                    $('#prenatalVisitHistoryModal').modal('hide');
                    // Optionally reload the page or table
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            // Show validation errors
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    toastr.error(errors[field][0], {
                                        closeButton: true,
                                        progressBar: true
                                    });
                                }
                            }
                        } else if (xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message, {
                                closeButton: true,
                                progressBar: true
                            });
                        }
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
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
        // Open for create
        $('#btnAddPrenatalVisit').on('click', function() {
            $('#prenatalVisitForm')[0].reset();
            var visitId = $(this).data('visit-id');
            $('#prenatalVisitForm input[name="visit_id"]').val(visitId);

            $('#prenatal_visit_id').val('');
            $('#prenatalVisitModal').modal('show');
        });

        // Open for edit
        $('#btn-edit-prenatal-visit').on('click', function() {
            const id = $(this).data('id');

            $.get('/admin/prenatal-visit/view/' + id, function(visit) {
                $('#prenatal_visit_id').val(visit.id);
                $('#pregnancy_id').val(visit.pregnancy_id);

                for (const key in visit) {
                    const field = $('[name="' + key + '"]');
                    if (field.attr('type') === 'checkbox') {
                        field.prop('checked', visit[key]);
                    } else {
                        field.val(visit[key]);
                    }
                }

                $('#prenatalVisitModal').modal('show');
            });
        });

        // Submit form
        $('#prenatalVisitForm').on('submit', function(e) {
            e.preventDefault();

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            const formData = new FormData(this);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'))
            const id = $('#prenatal_visit_id').val();
            if (id) {
                formData.append('_method', 'PUT'); // <---- important
            }
            const url = id ? `/admin/prenatal-visit/update/${id}` : "{{ route('admin.prenatal_visit.store') }}";

            $.ajax({
                url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {

                    toastr.success('{{ translate('Pregnancy Folllow Up Created Successfully.!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#prenatalVisitModal').modal('hide');
                    $('#slot_id').html('');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            // Loop through validation errors
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    toastr.error(errors[field][0], {
                                        closeButton: true,
                                        progressBar: true
                                    });
                                }
                            }
                        } else if (xhr.responseJSON.message) {
                            // General error message
                            toastr.error(xhr.responseJSON.message, {
                                closeButton: true,
                                progressBar: true
                            });
                        }
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
                            }
                        );
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
        // Open modal for CREATE
        $('#addPregnancyBtn').on('click', function() {
            const visitId = $(this).data('visit-id');

            $('#pregnancyForm')[0].reset(); // this clears visit_id too
            $('#pregnancyForm input[name="visit_id"]').val(visitId);
            $('#visit_id').val(visitId); // set it AFTER reset

            $('#formMethod').val('POST');
            $('#pregnancyModalLabel').text('Add Pregnancy Record');
            $('#pregnancyModal').modal('show');
            $('#saveBtn').text('Save');
            $('#pregnancyForm').attr('action', "{{ route('admin.pregnancy.store') }}");
        });


        // Open modal for EDIT
        $('#editPregnancyBtn').on('click', function() {
            const pregnancy = $(this).data('pregnancy'); // this should be a JS object

            $('#pregnancyForm')[0].reset();
            $('#formMethod').val('PUT');
            $('#pregnancyModalLabel').text('Edit Pregnancy Record');
            $('#pregnancyModal').modal('show');
            $('#saveBtn').text('Update');
            $('#pregnancyForm').attr('action', `/admin/pregnancy/update/${pregnancy.id}`);

            // Fill fields
            $('#visit_id').val(pregnancy.visit_id);
            $('#lmp').val(pregnancy.lmp);
            $('#edd').val(pregnancy.edd);
            $('#marital_status').val(pregnancy.marital_status);
            $('#remark').val(pregnancy.remark);
        });

        // AJAX Submit
        $('#pregnancyForm').on('submit', function(e) {
            e.preventDefault();
            const url = $(this).attr('action');
            const method = $('#formMethod').val();
            const formData = $(this).serialize();
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function(response) {
                    toastr.success('Pregnancy record saved successfully');
                    $('#pregnancyModal').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    toastr.error(xhr.responseJSON?.error || 'Failed to save pregnancy record');
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
        $('#newborn-form').submit(function(e) {
            e.preventDefault();
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            let formData = new FormData(this);
            let id = $('#newborn-id').val();
            let url = id ? `/admin/newborn/update/${id}` : `{{ route('admin.newborn.store') }}`;

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.success('{{ translate('New Born Saved Successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#newborn-modal').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

                    setTimeout(function() {}, 2000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            // Loop through validation errors
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    toastr.error(errors[field][0], {
                                        closeButton: true,
                                        progressBar: true
                                    });
                                }
                            }
                        } else if (xhr.responseJSON.message) {
                            // General error message
                            toastr.error(xhr.responseJSON.message, {
                                closeButton: true,
                                progressBar: true
                            });
                        }
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
                            }
                        );
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        // For edit
        $(document).on('click', '#edit-newborn-btn', function() {
            let data = $(this).data('newborn'); // data-id, data-name, etc.
            console.log(data)
            $('#newborn-form')[0].reset();
            $('#newborn-id').val(data.id);
            for (let key in data) {
                const el = $(`[name="${key}"]`);
                if (el.attr('type') === 'checkbox') {
                    el.prop('checked', data[key]);
                } else {
                    el.val(data[key]);
                }
            }
            $('#newborn-modal').modal('show');
        });

        // For add new
        $(document).on('click', '#add-newborn-btn', function() {
            $('#newborn-form')[0].reset();
            const delivery_summary_id = $(this).data('delivery-summary-id');
            $('#newborn-form input[name="delivery_summary_id"]').val(delivery_summary_id);
            $('#newborn-id').val('');
            $('#delivery_summary_id').val($(this).data('delivery_summary_id'));
            $('#newborn-modal').modal('show');
        });
    </script>
    <script>
        function resetDeliverySummaryForm() {
            $('#delivery-summary-form')[0].reset();
            $('#delivery_summary_id').val('');
            $('input[name="_method"]').val('POST');
            $('#delivery-summary-modal-title').text('Add Delivery Summary');
            $('#delivery-summary-submit-btn').text('Save');
        }

        // 🟢 Open for Create
        $(document).on('click', '#open-create-delivery-summary', function() {
            resetDeliverySummaryForm();
            const visitId = $(this).data('visit-id');
            $('#delivery-summary-form input[name="visit_id"]').val(visitId);
            $('#delivery-summary-modal').modal('show');
        });

        // 🟡 Open for Edit
        $(document).on('click', '#edit-delivery-summary-btn', function() {
            let data = $(this).data('summary');

            resetDeliverySummaryForm();
            $('#delivery_summary_id').val(data.id);
            $('input[name="_method"]').val('PUT');
            $('#delivery-summary-modal-title').text('Edit Delivery Summary');
            $('#delivery-summary-submit-btn').text('Update');

            // Fill form fields
            for (let key in data) {
                const field = $(`[name="${key}"]`);
                if (field.attr('type') === 'checkbox') {
                    field.prop('checked', data[key] === 1 || data[key] === true);
                } else {
                    field.val(data[key]);
                }
            }

            $('#delivery-summary-modal').modal('show');
        });

        // ✅ Submit Create or Update
        $('#delivery-summary-form').on('submit', function(e) {
            e.preventDefault();

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);
            let form = $(this);
            let formData = form.serialize();
            let id = $('#delivery_summary_id').val();
            let url = id ?
                `/admin/delivery-summary/update/${id}` :
                "{{ route('admin.delivery_summary.store') }}";

            $.ajax({
                url: url,
                method: id ? 'POST' : 'POST', // Laravel handles PUT via _method
                data: formData,
                success: function(data) {

                    toastr.success('{{ translate('Delivery summary saved successfully.!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#delivery-summary-modal').modal('hide');
                    $('#slot_id').html('');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', data.visit_id);
                    location.href = currentUrl.toString();

                    setTimeout(function() {}, 1000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            // Loop through validation errors
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    toastr.error(errors[field][0], {
                                        closeButton: true,
                                        progressBar: true
                                    });
                                }
                            }
                        } else if (xhr.responseJSON.message) {
                            // General error message
                            toastr.error(xhr.responseJSON.message, {
                                closeButton: true,
                                progressBar: true
                            });
                        }
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
                            }
                        );
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
        $('#prescriptionForm').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.prescriptions.store') }}', // Use the correct route name
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {

                    toastr.success('{{ translate('Prescription Added Successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-Prescription_test').modal('hide'); // Close the modal
                    $('#prescriptionForm')[0].reset(); // Reset the form
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();

                    setTimeout(function() {
                        // Optionally redirect or perform other actions after a delay
                        // location.href = '{{ route('admin.patient.list') }}';
                    }, 2000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Handle validation errors
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0], {
                                closeButton: true,
                                progressBar: true
                            });
                        });

                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Handle generic error messages
                        toastr.error(xhr.responseJSON.message, {
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
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        $('#emergencyPrescriptionForm').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.emergency_prescriptions.store') }}', // Use the correct route name
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    toastr.success('{{ translate('Emergency Prescription Added Successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    $('#add-Emergency-Prescirption_test').modal('hide'); // Close the modal
                    $('#emergencyPrescriptionForm')[0].reset(); // Reset the form
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();

                    setTimeout(function() {
                        // Optionally redirect or perform other actions after a delay
                        // location.href = '{{ route('admin.patient.list') }}';
                    }, 2000);
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Handle validation errors
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0], {
                                closeButton: true,
                                progressBar: true
                            });
                        });

                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Handle generic error messages
                        toastr.error(xhr.responseJSON.message, {
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
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        // ... existing code ...
        function editTestResult(testResultId) {
            // Fetch test result data
            $.get(`/admin/laboratory_result/test-results/${testResultId}`, function(response) {
                if (response.error) {
                    toastr.error(response.error);
                    return;
                }

                const testResult = response.testResult;
                const attributes = response.attributes;
                // Show the edit modal
                $('#editTestResultModal').modal('show');

                // Populate form fields
                $('#edit_test_result_form input[name="id"]').val(testResult.id);
                $('#edit_test_result_form textarea[name="additional_note"]').val(testResult.additional_note);
                $('#edit_test_result_form textarea[name="comments"]').val(testResult.comments);
                $('#edit_test_result_form select[name="result_status"]').val(testResult.result_status).trigger(
                    'change');

                // Clear and populate attributes container
                const attributesContainer = $('#attributes_container_edit');
                attributesContainer.empty();

                // Add attribute fields
                attributes.forEach(attribute => {
                    const attributeHtml = `
                        <div class="form-group">
                            <label>
                                ${attribute.name} ${attribute.default_required ?
                                    '<span class="input-label-secondary text-danger">*</span>':''}
                            </label>
                            ${getAttributeInputField(attribute)}
                        </div>
                    `;
                    attributesContainer.append(attributeHtml);
                });
            });
        }

        function getAttributeInputField(attribute) {
            const testAttribute = attribute;

            if (testAttribute.has_options) {
                const options = testAttribute.options;
                let optionsHtml = '';
                options.forEach(option => {
                    const selected = attribute.result_value == option.option_value ? 'selected' : '';
                    optionsHtml +=
                        `<option value="${option.option_value}" ${selected}>${option.option_value}</option>`;
                });

                return `
                    <select name="attribute_${testAttribute.id}" class="form-control js-select2-custom" ${testAttribute.default_required ? 'required' : ''}>
                        <option value="" disabled>select ${testAttribute.name} result</option>
                        ${optionsHtml}
                    </select>
                `;
            } else {
                return `
                    <input type="text" name="attribute_${testAttribute.id}" class="form-control"  ${testAttribute.default_required ? 'required' : ''}
                           value="${testAttribute.result_value || ''}">
                `;
            }
        }



        // Handle edit form submission
        $('#edit_test_result_form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            // Add CSRF token
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: '/admin/laboratory_result/update/' + formData.get('id'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Test result updated successfully');
                    $('#editTestResultModal').modal('hide');
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    toastr.error(xhr.responseJSON?.error || 'Failed to update test result');
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });



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

        function editMedicalRecord(id) {
            const button = $(event.target);
            const originalText = disableButton(button);

            $.ajax({
                url: "{{ route('admin.medical_record.edit', '') }}/" + id,
                type: "GET",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        const record = response.data;
                        $('#editMedicalRecordModal input[name="id"]').val(record.id);
                        $('#editMedicalRecordModal textarea[name="chief_complaint"]').val(record
                            .chief_complaint);
                        $('#editMedicalRecordModal textarea[name="symptoms"]').val(record.symptoms);
                        $('#editMedicalRecordModal textarea[name="medical_history"]').val(record
                            .medical_history);
                        $('#editMedicalRecordModal textarea[name="additional_notes"]').val(record
                            .additional_notes);
                        $('#editMedicalRecordModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                },
                complete: function() {
                    enableButton(button, originalText);
                }
            });
        }

        $('#edit_medical_record_form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.medical_record.update', '') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#editMedicalRecordModal').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        // Add click handlers for all buttons on the page
        // $(document).ready(function() {
        //     $('button').on('click', function() {
        //         console.log('clicked')
        //         if ($(this).attr('type') === 'submit' && !$(this).hasClass('close') && !$(this).hasClass('dropdown-toggle')) {
        //         console.log('clicked11')
        //             const button = $(this);
        //             const originalText = disableButton(button);

        //             // Re-enable button after 5 seconds if not already re-enabled
        //             setTimeout(function() {
        //                 enableButton(button, originalText);
        //             }, 5000);
        //         }
        //     });
        // });
    </script>

    <script>
        // ... existing code ...
        function editNurseAssessment(id, testName, testValue, unitName, notes) {
            $('#edit_test_name').val(testName);
            $('#edit_test_value').val(testValue);
            $('#edit_unit_name').val(unitName);
            $('#edit_notes').val(notes);
            $('#edit_assessment_id').val(id);
            // $('#edit_visit_id').val(visitId);
            $('#edit-nurse_assessment_test').modal('show');
        }

        $('#editNurseAssessmentForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('assessment_id');
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.nurse_assessment.update', '') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success');
                        $('#edit-nurse_assessment_test').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to update assessment!', 'error');
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 5000);
                }
            });
        });

        function editLabourFollowup(id, testName, testValue, unitName, notes) {
            $('#edit_test_name').val(testName);
            $('#edit_test_value').val(testValue);
            $('#edit_unit_name').val(unitName);
            $('#edit_notes').val(notes);
            $('#edit_assessment_id').val(id);
            // $('#edit_visit_id').val(visitId);
            $('#edit-labour_follwup_test').modal('show');
        }

        $('#editLabourFollowupForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('assessment_id');
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.labour_followup.update', '') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success');
                        $('#edit-labour_follwup_test').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to update assessment!', 'error');
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
        // ... existing code ...
        function editPrescription(id, medicine, dosage, duration, time, interval, quantity, comment) {
            $('#edit_prescription_detail_id').val(id);
            $('#edit_medicine').val(JSON.parse(medicine).id);
            $('#edit_dosage').val(dosage);
            $('#edit_dose_duration').val(duration);
            $('#edit_dose_time').val(time);
            $('#edit_dose_interval').val(interval);
            $('#edit_quantity').val(quantity);
            $('#edit_comment').val(comment);
            $('#edit-prescription_test').modal('show');
        }

        $('#editPrescriptionForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('prescription_detail_id');
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.prescriptions.update', '') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#edit-prescription_test').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
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
        // ... existing code ...
        function editInclinicPrescription(id, medicine_id, dosage, duration, time, interval, quantity, comment, item_type) {
            $('#edit_inclinic_prescription_detail_id').val(id);
            $('#edit_inclinic_inventory').val(medicine_id);
            const dosageFieldsEdit = `
                    <div class="form-group">
                        <label for="edit_inclinic_dosage">Dosage</label>
                        <input type="text" class="form-control" id="edit_inclinic_dosage" name="dosage"
                            placeholder="e.g., 500mg">
                    </div>

                    <div class="form-group">
                        <label for="edit_inclinic_dose_duration">Duration (Days)</label>
                        <input type="number" class="form-control" id="edit_inclinic_dose_duration" name="dose_duration">
                    </div>

                    <div class="form-group">
                        <label for="edit_inclinic_dose_time">Dose Time</label>
                        <select class="form-control" id="edit_inclinic_dose_time" name="dose_time">
                            <option value="">Dose Time(optional)</option>
                            <option value="Before Meal">Before Meal</option>
                            <option value="After Meal">After Meal</option>
                            <option value="With Meal">With Meal</option>
                            <option value="Anytime">Anytime</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_inclinic_dose_interval">Dose Interval</label>
                        <select class="form-control" id="edit_inclinic_dose_interval" name="dose_interval">
                            <option value="">Dose interval(optional)</option>
                            @foreach ($doseIntervals as $doseInterval)
                                <option value="{{ $doseInterval->name }}">{{ $doseInterval->name }}</option>
                            @endforeach
                        </select>
                    </div>
                `;

            if (item_type === 'medication') {
                $('#dosageFieldsEdit').html(dosageFieldsEdit);
            } else {
                $('#dosageFieldsEdit').empty();
            }
            $('#edit_inclinic_dosage').val(dosage);
            $('#edit_inclinic_dose_duration').val(duration);
            $('#edit_inclinic_dose_time').val(time);
            $('#edit_inclinic_dose_interval').val(interval);
            $('#edit_inclinic_quantity').val(quantity);
            $('#edit_inclinic_comment').val(comment);
            $('#edit-inclinic_prescription_test').modal('show');
        }

        $('#editInclinicPrescriptionForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('detail_id');
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.emergency_prescriptions.update', '') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#edit-inclinic_prescription_test').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
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
        // ... existing code ...
        function deleteSpecimen(specimenId) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: '{{ translate('You want to delete this specimen?') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('No') }}',
                confirmButtonText: '{{ translate('Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/admin/specimen/delete/' + specimenId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            toastr.success('{{ translate('Specimen deleted successfully') }}');
                            const currentUrl = new URL(window.location.href);
                            location.href = currentUrl.toString();
                        },
                        error: function(xhr) {
                            toastr.error('{{ translate('Failed to delete specimen') }}');
                        }
                    });
                }
            });
        }

        function deleteTestResult(testResultId) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: '{{ translate('You want to delete this test result?') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('No') }}',
                confirmButtonText: '{{ translate('Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/admin/laboratory_result/delete/' + testResultId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            toastr.success('{{ translate('Test result deleted successfully') }}');
                            const currentUrl = new URL(window.location.href);
                            location.href = currentUrl.toString();
                        },
                        error: function(xhr) {
                            toastr.error('{{ translate('Failed to delete test result') }}');
                        }
                    });
                }
            });
        }

        function deleteRadiologyResult(testResultId) {
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: '{{ translate('You want to delete this Radiology result?') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('No') }}',
                confirmButtonText: '{{ translate('Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/admin/radiology_result/delete/' + testResultId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            toastr.success(
                                '{{ translate('Radiology result deleted successfully') }}');
                            const currentUrl = new URL(window.location.href);
                            location.href = currentUrl.toString();
                        },
                        error: function(xhr) {
                            toastr.error('{{ translate('Failed to delete test result') }}');
                        }
                    });
                }
            });
        }
    </script>

    <script>
        // ... existing code ...
        function editRadiologyResult(resultId) {
            // Fetch the radiology result data
            $.ajax({
                url: '{{ route('admin.radiology_result.edit', '') }}/' + resultId,
                type: 'GET',
                success: function(response) {
                    const result = response;
                    // Set the form values
                    $('#editRadiologyResultId').val(resultId);
                    $('#radiology_request_test_id').val(result.radiology_request_test_id);
                    $('#resultStatus').val(result.result_status);
                    $('#additionalNote').val(result.additional_note);
                    $('#comments').val(result.comments);

                    // Handle current images
                    let images = result.image || [];
                    if (typeof images === 'string') {
                        try {
                            images = JSON.parse(images);
                        } catch (e) {
                            console.error('Failed to parse image field:', e);
                            images = [];
                        }
                    }

                    let imagesHtml = '';
                    if (images.length > 0) {
                        images.forEach((image) => {
                            imagesHtml += `
                                <div class="position-relative">
                                    <img src="/storage/app/public/radiology_results/${image}"
                                         alt="Result Image"
                                         class="img-thumbnail"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <button type="button"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                            onclick="removeImage('${image}')">
                                        <i class="tio-delete"></i>
                                    </button>
                                </div>`;
                        });
                    } else {
                        imagesHtml = '<p>No images available</p>';
                    }
                    $('#currentImagesContainer').html(imagesHtml);

                    // Handle attributes
                    let attributesHtml = '';
                    if (response.attributes && response.attributes.length > 0) {
                        response.attributes.forEach((attribute) => {
                            attributesHtml += attribute.attribute.result_type == 'paragraph' ?
                                `<div class="form-group">
                                    <label>${attribute.attribute.attribute_name} ${attribute.attribute.default_required ?
                                    '<span class="input-label-secondary text-danger">*</span>' :
                                    ''}</label>
                                    <textarea  name="attribute_${attribute.attribute.id}" id="attribute_${attribute.attribute.id}"
                                    value="${attribute.result_value || ''}"
                                           placeholder="Enter value"
                                        class="form-control ckeditor" ${attribute.attribute.default_required ? '' : ''} >${attribute.result_value || attribute.attribute.template}</textarea>
                                </div>` :
                                `<div class="form-group">
                                    <label>${attribute.attribute.attribute_name} ${attribute.attribute.default_required ?
                                    '<span class="input-label-secondary text-danger">*</span>' :
                                    ''}</label>
                                    <input type="text" name="attribute_${attribute.attribute.id}"
                                    value="${attribute.result_value || ''}"
                                           placeholder="Enter value"
                                        class="form-control" ${attribute.attribute.default_required ? 'required' : ''} />
                                </div>`;
                        });
                    } else {
                        attributesHtml = '<p>No attributes available</p>';
                    }
                    $('#radiologyAttributesContainer').html(attributesHtml);

                    // Initialize CKEditor for dynamically added textareas
                    $('#radiologyAttributesContainer textarea.ckeditor').each(function() {
                        let id = $(this).attr('id');

                        if (!window.editors) {
                            window.editors = {}; // create global storage if missing
                        }

                        if (!window.editors[id]) {
                            ClassicEditor
                                .create(this)
                                .then(editor => {
                                    window.editors[id] = editor;

                                    // ✅ Live syncing: keep textarea updated on every change
                                    editor.model.document.on('change:data', () => {
                                        $('#' + id).val(editor.getData());
                                    });
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        }
                    });
                    // Show the modal
                    $('#editRadiologyResultModal').modal('show');
                },
                error: function(error) {
                    console.error('Error fetching radiology result:', error);
                    toastr.error('Failed to fetch radiology result data. Please try again.', {
                        closeButton: true,
                        progressBar: true
                    });
                }
            });
        }

        function removeImage(imageName) {
            // Add the image name to a hidden input for tracking images to remove
            if (!$('#imagesToRemove').length) {
                $('#editRadiologyResultForm').append('<input type="hidden" id="imagesToRemove" name="images_to_remove[]">');
            }
            $('#imagesToRemove').append(`<input type="hidden" name="images_to_remove[]" value="${imageName}">`);

            // Remove the image element from the UI
            $(event.target).closest('.position-relative').remove();
        }

        $('#editRadiologyResultForm').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const resultId = $('#editRadiologyResultId').val();

            $.ajax({
                url: '{{ route('admin.radiology_result.update', '') }}/' + resultId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#editRadiologyResultModal').modal('hide');
                    toastr.success('Radiology result updated successfully!', {
                        closeButton: true,
                        progressBar: true
                    });

                    // Refresh the page to show updated data
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('active', response.visit_id);
                    location.href = currentUrl.toString();
                },
                error: function(error) {
                    console.error('Error updating radiology result:', error);
                    toastr.error('Failed to update radiology result. Please try again.', {
                        closeButton: true,
                        progressBar: true
                    });
                }
            });
        });

        // ... existing code ...
    </script>

    <script>
        function editRadiologyRequest(id) {
            const button = $(event.target);
            const originalText = disableButton(button);

            $.ajax({
                url: "{{ route('admin.radiology_request.edit', '') }}/" + id,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        const request = response.data.radiologyRequest;
                        const radiologies = response.data.radiologies;

                        // Set form values
                        $('#edit_radiology_request_form input[name="id"]').val(request.id);
                        $('#edit_radiology_request_form input[name="visit_id"]').val(request.visit_id);
                        $('#edit_radiology_request_form select[name="requested_by"]').val(request.requested_by)
                            .trigger('change');
                        $('#edit_radiology_request_form select[name="order_status"]').val(request.order_status)
                            .trigger('change');
                        $('#edit_radiology_request_form select[name="fasting"]').val(request.fasting).trigger(
                            'change');
                        $('#edit_radiology_request_form input[name="referring_dr"]').val(request.referring_dr);
                        $('#edit_radiology_request_form input[name="referring_institution"]').val(request
                            .referring_institution);
                        $('#edit_radiology_request_form input[name="card_no"]').val(request.card_no);
                        $('#edit_radiology_request_form input[name="hospital_ward"]').val(request
                            .hospital_ward);
                        $('#edit_radiology_request_form textarea[name="relevant_clinical_data"]').val(request
                            .relevant_clinical_data);
                        $('#edit_radiology_request_form textarea[name="current_medication"]').val(request
                            .current_medication);
                        $('#edit_radiology_request_form textarea[name="additional_note"]').val(request
                            .additional_note);

                        // Get radiology IDs from the radiologies array
                        const radiologyIds = request.radiologies.map(radiology => radiology.radiology_id);
                        $('#edit_radiology_request_form select[name="radiology_ids[]"]').val(radiologyIds)
                            .trigger(
                                'change');

                        $('#editRadiologyRequestModal').modal('show');
                    } else {
                        toastr.error(response.message || 'Error retrieving radiology request');
                    }
                    enableButton(button, originalText);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error retrieving radiology request');
                    enableButton(button, originalText);
                }
            });
        }

        $('#edit_radiology_request_form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajax({
                url: "{{ route('admin.radiology_request.update', '') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#editRadiologyRequestModal').modal('hide');
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('active', response.visit_id);
                        location.href = currentUrl.toString();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while updating the radiology request.');
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
        // ... existing code ...
    </script>
@endpush
@push('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <!-- Fabric.js for Dental Charting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.ckeditor').forEach((el) => {
                ClassicEditor
                    .create(el)
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endpush
<style>
    .details-container {
        display: grid;
        grid-template-columns: max-content auto;
        row-gap: 20px;
        column-gap: 40px;
    }

    .details-row {
        display: contents;
    }

    .details-label {
        font-size: 14px;
        font-weight: 500;
        color: #888;
        text-align: left;
    }

    .details-value {
        font-size: 14px;
        font-weight: 600;
        color: #555;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-selection {
        width: 100% !important;
    }
</style>

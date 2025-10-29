@extends('layouts.admin.app')

@section('title', translate('Emergency Prescreption List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">

                {{ \App\CentralLogics\translate('Emergency Prescreption_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $prescreptions->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">

                <!-- Table -->
                <div class="table-responsive datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                <th>{{ \App\CentralLogics\translate('patient') }}</th>
                                <th>{{ \App\CentralLogics\translate('doctor') }}</th>
                                <th>{{ \App\CentralLogics\translate('prescribed date') }}</th>
                                <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                            @foreach ($prescreptions as $key => $prescreption)
                                <tr>
                                    <td>{{ 1 + $key }}</td>

                                    <td>{{ $prescreption->visit->patient->full_name }}</td>
                                    <td>{{ $prescreption->doctor->f_name . ' ' . $prescreption->doctor->l_name }}</td>
                                    <td>{{ $prescreption->created_at }}</td>

                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button class="btn btn-sm btn-outline-info mr-2 px-1 py-1"
                                                onclick='viewPrescreption(@json($prescreption))'>
                                                <i class="tio tio-visible"></i>
                                            </button>
                                            @if (auth('admin')->user()->can('emergency_prescriptions.delete'))
                                                <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                    onclick="form_alert('patient-{{ $prescreption['id'] }}','{{ \App\CentralLogics\translate('Want to delete this prescreption ?') }}')"><i
                                                        class="tio tio-delete">
                                                    </i>
                                                </a>
                                            @endif
                                        </div>
                                        <form
                                            action="{{ route('admin.emergency_prescriptions.delete', [$prescreption['id']]) }}"
                                            method="post" id="patient-{{ $prescreption['id'] }}">
                                            @csrf @method('delete')
                                        </form>
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
                        {!! $prescreptions->links() !!}
                    </div>
                </div>
                @if (count($prescreptions) == 0)
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

    <!-- Modal -->
    <div class="modal fade" id="viewItemModal" tabindex="-1" aria-labelledby="viewItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-wide modal-xl"> <!-- Adjusted modal size -->
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewItemModalLabel">Item Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="ItemDetailsContent" class="">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="updateIssuedStatusModal" tabindex="-1" role="dialog" style="z-index: 999;"
        aria-labelledby="updateIssuedStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background: lightgray;">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateIssuedStatusModalLabel">Update Issued Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-2" id="prescription-stats-row">
                        <strong>prescribed:</strong> <span id="stat-prescribed">0</span>
                        <strong>Issued:</strong> <span id="stat-issued">0</span>
                        <strong>canceled:</strong> <span id="stat-cancelled">0</span>
                        <strong>pending:</strong> <span id="stat-pending">0</span>
                    </div>
                    <form id="updateIssuedStatusForm">
                        <input type="hidden" id="paymentTimingInput" name="payment_timing">
                        <input type="hidden" id="billingStatusInput" name="billing_status">
                        @csrf
                        <input type="hidden" id="issuedResultIdInput" name="result_id">
                        <div class="form-group">
                            <label for="issue_status">Issued Status</label>
                            <select class="form-control js-select2-custom" id="issuedResultStatusInput" name="process_status" id="issue_status">
                                <option value="" disabled selected>select Issue status</option>
                                <option value="issued">Issued</option>
                                <option value="cancelled">Reject</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="isued_quantity">Quantity(Issued/Rejected)
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" name="quantity" id="isued_quantity" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Issued Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Emergency Prescription Modal -->
    <div class="modal fade" id="edit-inclinic_prescription_test" tabindex="-1" role="dialog"
        aria-labelledby="editPrescriptionLabel" aria-hidden="true" style="z-index: 999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background: lightgray;">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPrescriptionLabel">Edit Inclinic Prescription</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editInclinicPrescriptionForm" action='javascript:void(0)'>
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="detail_id" id="edit_inclinic_prescription_detail_id">
                        <input type="text" hidden name="doctor_id" value="{{ auth('admin')->user()->id }}">

                        <div class="form-group">
                            <label for="edit_inclinic_inventory">Select Prescription</label>
                            <select class="form-control " id="edit_inclinic_inventory" name="emergency_inventory_id"
                                required>
                                <option value="" disabled>Choose a Prescription</option>
                                @foreach ($emergencyPrescreptions as $medicine)
                                    <option value="{{ $medicine->id }}">
                                        {{ $medicine->medicine->name }}-({{ $medicine->batch_number }})-({{ $medicine->quantity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="dosageFieldsEdit">
                        </div>

                        <div class="form-group">
                            <label for="edit_inclinic_quantity">Quantity</label>
                            <input type="number" class="form-control" id="edit_inclinic_quantity" name="quantity"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="edit_inclinic_comment">Comment</label>
                            <textarea class="form-control" id="edit_inclinic_comment" name="comment" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script_2')
    <script>
        $(document).ready(function() {
            $('#edit_inclinic_inventory').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const itemType = selectedOption.data('item-type');
                const dosageFieldsEdit = `
                    <div class="form-group">
                        <label for="dosage">Dosage</label>
                        <input type="text" class="form-control" id="dosage" name="dosage"
                            placeholder="e.g., 500mg">
                    </div>

                    <div class="form-group">
                        <label for="dose_duration">Duration (Days)</label>
                        <input type="number" class="form-control" id="dose_duration" name="dose_duration">
                    </div>

                    <div class="form-group">
                        <label for="dose_time">Dose Time</label>
                        <select class="form-control" id="dose_time" name="dose_time">
                            <option value="">Dose Time(optional)</option>
                            <option value="Before Meal">Before Meal</option>
                            <option value="After Meal">After Meal</option>
                            <option value="With Meal">With Meal</option>
                            <option value="Anytime">Anytime</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dose_interval">Dose Interval</label>
                        <select class="form-control" id="dose_interval" name="dose_interval">
                            <option value="">Dose interval(optional)</option>
                            <option value="Once Daily">Once Daily</option>
                            <option value="Twice Daily">Twice Daily</option>
                            <option value="Three Times Daily">Three Times Daily</option>
                            <option value="Every 6 Hours">Every 6 Hours</option>
                            <option value="Every 8 Hours">Every 8 Hours</option>
                        </select>
                    </div>
                `;

                if (itemType === 'medication') {
                    $('#dosageFieldsEdit').html(dosageFieldsEdit);
                } else {
                    $('#dosageFieldsEdit').empty();
                }
            });
        });

        function viewPrescreption(prescription) {
            const prescriptionData = prescription;
            if (!prescriptionData) {
                document.getElementById('ItemDetailsContent').innerHTML = `
                <p class="text-danger">No prescription data available.</p>`;
                $('#viewItemModal').modal('show');
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

            // Create HTML content for the modal
            const contentHtml = `
                <div class="">

                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Dosage</th>
                                        <th>Quantity</th>
                                        <th>Duration (Days)</th>
                                        <th>Dose Interval</th>
                                        <th>Dose Time</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        @if (auth('admin')->user()->can('emergency_prescriptions.edit'))
                                            ${prescriptionData.details.every(detail => detail.status === 'pending')
                                            &&prescriptionData.billing.status=='unpaid' ?
                                                `<th>Action</th>`
                                                :``
                                            }
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    ${prescriptionData.details.map(detail => `
                                                                                                <tr>
                                                                                                    <td>${detail.medicine.medicine.name}(${detail.medicine.batch_number})</td>
                                                                                                    <td>${detail.dosage || 'N/A'}</td>
                                                                                                    <td>${detail.quantity} - ${detail.issued_quantity+detail.cancelled_quantity} =${detail.quantity-(detail.issued_quantity+detail.cancelled_quantity)}</td>
                                                                                                    <td>${detail.dose_duration || 'N/A'}</td>
                                                                                                    <td>${detail.dose_interval || 'N/A'}</td>
                                                                                                    <td>${detail.dose_time || 'N/A'}</td>
                                                                                                    <td>${detail.comment || 'N/A'}</td>
                                                                                                    <td>
                                                                                                        <a href="javascript:void(0)"
                                                                                                            class="badge badge-success)}"
                                                                                                            onclick="handleIssuedStatusClick('${detail.medicine.medicine.payment_timing}', '${prescriptionData.billing.status}', '${detail.id}', '${detail.status}', '${detail.quantity}', '${detail.issued_quantity}', '${detail.cancelled_quantity}')">
                                                                                                            ${detail.cancelled_quantity == detail.quantity ?'cancelled':(detail.issued_quantity == detail.quantity ?'Issued':(detail.quantity == (detail.issued_quantity+detail.cancelled_quantity)?'completed':'pending'))}
                                                                                                        </a>
                                                                                                    </td>
                                                                                                    @if (auth('admin')->user()->can('emergency_prescriptions.edit'))
                                                                                                    ${
                                                                                                        detail.status=='pending'&&prescriptionData.billing.status=='unpaid' ?
                                                                                                        `<td>
                                                    <button class="btn btn-sm btn-primary"
                                                        onclick="editInclinicPrescription('${detail.id}', '${detail.medicine.id}',
                                                        '${detail.dosage}', '${detail.dose_duration}', '${detail.dose_time}',
                                                        '${detail.dose_interval}', '${detail.quantity}', '${detail.comment}',
                                                        '${detail.medicine.medicine.item_type}')">
                                                        <i class="tio tio-edit"></i>
                                                    </button>
                                                </td>`
                                                                                                        :``
                                                                                                    }
                                                                                                    @endif
                                                                                                </tr>
                                                                                            `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('ItemDetailsContent').innerHTML = contentHtml;
            $('#viewItemModal').modal('show');
        }

        function handleIssuedStatusClick(paymentTiming, billingStatus, resultId, currentStatus,quantity, issude, cancelled) {
            @if (auth('admin')->user()->can('emergency_prescriptions.issued-status.update'))
                if (currentStatus == 'pending'||(quantity-issude-cancelled)>0) {
                    showUpdateIssuedStatusModal(resultId, currentStatus, paymentTiming, billingStatus, quantity, issude, cancelled );
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



          function showUpdateIssuedStatusModal(resultId, currentStatus, paymentTiming, billingStatus, quantity, issude, cancelled) {
            // Pre-fill modal with specimen details
            $('#issuedResultIdInput').val(resultId);
            $('#paymentTimingInput').val(paymentTiming);
            $('#billingStatusInput').val(billingStatus);

            $('#isued_quantity').attr('max', quantity - issude - cancelled);

            $('#stat-prescribed').text(quantity);
            $('#stat-issued').text(issude);
            $('#stat-cancelled').text(cancelled);
            $('#stat-pending').text(quantity-issude-cancelled);
            // Show modal
            $('#updateIssuedStatusModal').modal('show');
        }

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

            const formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.emergency_prescriptions.issued-status.update') }}',
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
                    console.error(error.responseJSON.message);
                    toastr.error(error.responseJSON.message ? error.responseJSON.message :
                        'Failed to update status. Please try again.', {
                            closeButton: true,
                            progressBar: true
                        });
                }
            });
        });
    </script>

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

        // ... existing code ...
        function editInclinicPrescription(id, medicine_id, dosage, duration, time, interval, quantity, comment, item_type) {

            $('#edit_inclinic_prescription_detail_id').val(id);
            $('#edit_inclinic_inventory').val(medicine_id);
            const dosageFields = `
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
                        <option value="Once Daily">Once Daily</option>
                        <option value="Twice Daily">Twice Daily</option>
                        <option value="Three Times Daily">Three Times Daily</option>
                        <option value="Every 6 Hours">Every 6 Hours</option>
                        <option value="Every 8 Hours">Every 8 Hours</option>
                    </select>
                </div>
            `;
            if (item_type === 'medication') {
                $('#dosageFieldsEdit').html(dosageFields);
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
@endpush

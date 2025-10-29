<!-- Add Prescription Modal -->
<div class="modal fade" id="add-Prescirption_test" tabindex="-1" role="dialog" aria-labelledby="addPrescriptionLabel"
    aria-hidden="true" style="scrollbar-width: none;-ms-overflow-style: none;overflow: scroll;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPrescriptionLabel">Add Prescription</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="prescriptionForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="visit_id" id="visit_id">
                    <input type="text" hidden name="doctor_id" value="{{ auth('admin')->user()->id }}">

                    <div class="form-group">
                        <label for="medicine_id">Select Medicine</label>
                        <div class="row">
                            <div class="col-10">
                                <select class="form-control js-select2-custom" id="medicine_id" name="medicine_id"
                                    required>
                                    <option value="">Choose a Medicine</option>
                                    @foreach ($medications as $medicine)
                                        <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-outline-primary" id="addMedicineBtn"
                                data-toggle="modal" data-target="#addMedicineModal" title="Add New Medicine">
                                <i class="tio-add"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dosage">Dosage</label>
                        <input type="text" class="form-control" id="dosage" name="dosage"
                            placeholder="e.g., 500mg">
                    </div>

                    <div class="form-group">
                        <label for="dose_duration">Duration (Days)</label>
                        <input type="number" class="form-control" id="dose_duration" name="dose_duration" required>
                    </div>

                    <div class="form-group">
                        <label for="dose_time">Dose Time</label>
                        <select class="form-control" id="dose_time" name="dose_time">
                            <option value="=" disabled selected>Select Dose Time</option>
                            <option value="Before Meal">Before Meal</option>
                            <option value="After Meal">After Meal</option>
                            <option value="With Meal">With Meal</option>
                            <option value="Anytime">Anytime</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dose_interval">Dose Interval</label>
                        <select class="form-control" id="dose_interval" name="dose_interval">
                            <option disabled selected>Select Dose Interval</option>
                            @foreach ($doseIntervals as $doseInterval)
                                <option value="{{ $doseInterval->name }}">{{ $doseInterval->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>

                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Medicine Modal -->
<div class="modal fade" id="addMedicineModal" tabindex="-1" role="dialog" aria-labelledby="addMedicineModalLabel"
    aria-hidden="true" style="
            backdrop-filter: blur(4px);
            background-color: rgba(0,0,0,0.3);">
    <div class="modal-dialog" role="document">
        <form id="quickAddMedicineForm" action="{{ route('admin.medicines.quick-store') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMedicineModalLabel">{{ translate('Add New Medicine') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="{{ translate('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="input-label">{{ translate('Medicine Name') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">{{ translate('Category') }}</label>
                            <select name="category_id" class="form-control js-select2-custom">
                                <option value="" selected disabled>Select category</option>
                                @foreach ($medicineCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">{{ translate('Status') }}</label>
                            <select name="status" class="form-control js-select2-custom" value="active">
                                <option value="" selected disabled>Select status</option>
                                <option value="active" selected>Active</option>
                                <option value="inactive">InActive</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="input-label">{{ translate('Description') }}</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Emergency Prescription Modal -->
<div class="modal fade" id="add-Emergency-Prescirption_test" tabindex="-1" role="dialog"
    aria-labelledby="addPrescriptionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPrescriptionLabel">Add Inclinic Prescription</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="emergencyPrescriptionForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="visit_id" id="emergency_visit_id">
                    <input type="text" hidden name="doctor_id" value="{{ auth('admin')->user()->id }}">

                    <div class="form-group">
                        <label for="emergency_inventory_id">Select Item</label>
                        <select class="form-control js-select2-custom" id="emergency_inventory_id"
                            name="emergency_inventory_id" required>
                            <option value="">Choose an Item</option>
                            @foreach ($emergencyPrescreptions as $medicine)
                                <option value="{{ $medicine->id }}"
                                    data-item-type="{{ $medicine->medicine->item_type }}">
                                    {{ $medicine->medicine->name }}-({{ $medicine->batch_number }})-({{ $medicine->quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="dosageFields">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>

                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Edit Prescription Modal -->
<div class="modal fade" id="edit-prescription_test" tabindex="-1" role="dialog"
    aria-labelledby="editPrescriptionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPrescriptionLabel">{{ translate('Edit Prescription') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editPrescriptionForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="prescription_detail_id" id="edit_prescription_detail_id">

                    <div class="form-group">
                        <label for="edit_medicine">{{ translate('Medicine') }}</label>
                        <select class="form-control" id="edit_medicine" name="medicine_id" required>
                            <option value="">Choose a Medicine</option>
                            @foreach ($medications as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_dosage">{{ translate('Dosage') }}</label>
                        <input type="text" class="form-control" name="dosage" id="edit_dosage"
                            placeholder="e.g., 500mg">
                    </div>

                    <div class="form-group">
                        <label for="edit_dose_duration">{{ translate('Duration (days)') }}</label>
                        <input type="number" class="form-control" name="dose_duration" id="edit_dose_duration"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="edit_dose_time">{{ translate('Time') }}</label>
                        <select class="form-control" name="dose_time" id="edit_dose_time" required>
                            <option value="Before Meal">Before Meal</option>
                            <option value="After Meal">After Meal</option>
                            <option value="With Meal">With Meal</option>
                            <option value="Anytime">Anytime</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_dose_interval">{{ translate('Interval') }}</label>
                        <select class="form-control" name="dose_interval" id="edit_dose_interval">
                            <option value="">Select Interval</option>
                            @foreach ($doseIntervals as $doseInterval)
                                <option value="{{ $doseInterval->name }}">{{ $doseInterval->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_quantity">{{ translate('Quantity') }}<span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="quantity" id="edit_quantity">
                    </div>

                    <div class="form-group">
                        <label for="edit_comment">{{ translate('Comment') }}</label>
                        <textarea class="form-control" name="comment" id="edit_comment" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Update Prescription') }}</button>
                </div>
            </form>
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
                                <option value="{{ $medicine->id }}"
                                    data-item-type="{{ $medicine->medicine->item_type }}">
                                    {{ $medicine->medicine->name }}-({{ $medicine->batch_number }})-({{ $medicine->quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="dosageFieldsEdit">
                    </div>

                    <div class="form-group">
                        <label for="edit_inclinic_quantity">Quantity<span class="text-danger">*</span></label>
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

<div class="modal fade" id="viewItemModal" tabindex="-1" aria-labelledby="viewItemModalLabel" aria-hidden="true"
    style="scrollbar-width: none;-ms-overflow-style: none;overflow: scroll;">
    <div class="modal-dialog modal-dialog-wide  modal-xl"> <!-- Adjusted modal size -->
        <div class="modal-content">
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
                        <select class="form-control js-select2-custom" id="issuedResultStatusInput"
                            name="process_status" id="issue_status">
                            <option value="" disabled selected>select Issue status</option>
                            <option value="issued">Issued</option>
                            <option value="cancelled">Reject</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="isued_quantity">Quantity(Issued/Rejected)
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" name="quantity" id="isued_quantity"
                            min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Issued Status</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script_2')
    <script>
        $(document).ready(function() {
            $('#emergency_inventory_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const itemType = selectedOption.data('item-type');
                const dosageFields = `
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
                            @foreach ($doseIntervals as $doseInterval)
                                <option value="{{ $doseInterval->name }}">{{ $doseInterval->name }}</option>
                            @endforeach
                        </select>
                    </div>
                `;

                if (itemType === 'medication') {
                    $('#dosageFields').html(dosageFields);
                } else {
                    $('#dosageFields').empty();
                }
            });

            $('#edit_inclinic_inventory').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const itemType = selectedOption.data('item-type');
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
                                        <th>Item-batch</th>
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
    </script>
@endpush

@if (
    (auth('admin')->user()->can('prescriptions.list') && $visit->prescription->count() > 0) ||
        (auth('admin')->user()->can('emergency_prescriptions.list') && $visit->emergencyPrescriptions->count() > 0) ||
        (auth('admin')->user()->can('service.add-service-billing') && $visit->procedures->count() > 0))

    <!-- Prescriptions Tabs -->
    <fieldset class="border border-primary mt-3 p-3 rounded">
        <legend class="float-none w-auto px-3 py-1 bg-light border border-primary rounded-sm"
            style="font-weight: bold; font-size: 18px; color:white; background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%)">
            <div class="pr-1">
                Prescriptions
            </div>
        </legend>

        <div class="p-3">
            <ul class="nav nav-tabs" id="prescriptionTabs" role="tablist">
                @if (auth('admin')->user()->can('prescriptions.list') && $visit->prescription->count() > 0)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="regular-prescriptions-tab" data-toggle="tab"
                            href="#regular-prescriptions" role="tab" aria-controls="regular-prescriptions"
                            aria-selected="true">
                            <i class="tio-receipt mr-1"></i>Out Prescriptions
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('emergency_prescriptions.list') && $visit->emergencyPrescriptions->count() > 0)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('prescriptions.list') && $visit->prescription->count() > 0) ? 'active' : '' }}"
                            id="inclinic-prescriptions-tab" data-toggle="tab" href="#inclinic-prescriptions"
                            role="tab" aria-controls="inclinic-prescriptions"
                            aria-selected="{{ !(auth('admin')->user()->can('prescriptions.list') && $visit->prescription->count() > 0) ? 'true' : 'false' }}">
                            <i class="tio-download-to mr-1"></i>Inclinic Prescriptions
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('service.add-service-billing') && $visit->procedures->count() > 0)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !(auth('admin')->user()->can('prescriptions.list') && $visit->prescription->count() > 0) && !(auth('admin')->user()->can('emergency_prescriptions.list') && $visit->emergencyPrescriptions->count() > 0) ? 'active' : '' }}"
                            id="billing-services-tab" data-toggle="tab" href="#billing-services" role="tab"
                            aria-controls="billing-services"
                            aria-selected="{{ !(auth('admin')->user()->can('prescriptions.list') && $visit->prescription->count() > 0) && !(auth('admin')->user()->can('emergency_prescriptions.list') && $visit->emergencyPrescriptions->count() > 0) ? 'true' : 'false' }}">
                            <i class="tio-protection mr-1"></i>Services
                        </a>
                    </li>
                @endif
            </ul>

            <div class="tab-content border border-primary rounded-bottom" id="prescriptionTabsContent">
                <!-- Regular Prescriptions Tab -->
                @if (auth('admin')->user()->can('prescriptions.list') && $visit->prescription->count() > 0)
                    <div class="tab-pane fade show active p-3" id="regular-prescriptions" role="tabpanel"
                        aria-labelledby="regular-prescriptions-tab">

                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Prescribed By') }}</th>
                                        <th>{{ \App\CentralLogics\translate('medicine') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Desc.') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Action') }}</th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">
                                    @foreach ($visit->prescription as $prescription)
                                        @foreach ($prescription->details as $key => $detail)
                                            <tr>
                                                <td>{{ 1 + $key }}</td>
                                                <td>{{ $prescription->doctor->full_name }}</td>
                                                <td>{{ $detail->medicine->name }}</td>
                                                <td>
                                                    <p class="mb-0 text-muted">
                                                        {{ $detail->dosage ?? 'N/A' }},
                                                        {{ $detail->dose_duration }}
                                                        Days,
                                                        {{ $detail->dose_time }}
                                                    </p>
                                                </td>
                                                <td>
                                                    @if (auth('admin')->user()->can('prescriptions.edit'))
                                                        <div>
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-outline-primary square-btn"
                                                                data-toggle="modal" data-target="#editPrescriptionModal"
                                                                onclick="editPrescription({{ $detail->id }}, '{{ $detail->medicine }}', '{{ $detail->dosage }}', '{{ $detail->dose_duration }}', '{{ $detail->dose_time }}', '{{ $detail->dose_interval }}', '{{ $detail->quantity }}', '{{ $detail->comment }}')">
                                                                <i class="tio-edit"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if (auth('admin')->user()->can('prescriptions.pdf'))
                            <div class="text-end mt-3">
                                <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal"
                                    data-target="#pdfModal"
                                    onclick="loadPdf('{{ route('admin.prescriptions.pdf', $visit->id) }}')">
                                    View Prescription PDF
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Inclinic Prescriptions Tab -->
                @if (auth('admin')->user()->can('emergency_prescriptions.list') && $visit->emergencyPrescriptions->count() > 0)
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('prescriptions.list') && $visit->prescription->count() > 0) ? 'show active' : '' }} p-3"
                        id="inclinic-prescriptions" role="tabpanel" aria-labelledby="inclinic-prescriptions-tab">

                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Prescribed By') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Prescribed Date') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Items') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Action') }}</th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">
                                    @foreach ($visit->emergencyPrescriptions as $key => $prescription)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $prescription->doctor->f_name . ' ' . $prescription->doctor->l_name }}
                                            </td>
                                            <td>{{ $prescription->created_at }}</td>
                                            <td>
                                                {{ $prescription->details->count() }}
                                                {{ $prescription->details->count() > 1 ? 'items' : 'item' }},
                                                {{ $prescription->details->sum('quantity') }} in total
                                            </td>
                                            <td>
                                                @if (auth('admin')->user()->can('emergency_prescriptions.view'))
                                                    <button class="btn btn-sm btn-primary mr-2 px-2 py-2"
                                                        onclick='viewPrescreption(@json($prescription))'>
                                                        <i class="tio tio-visible"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif


                <!-- procedures Tab -->
                @if (auth('admin')->user()->can('service.add-service-billing') && $visit->procedures->count() > 0)
                    <div class="tab-pane fade {{ !(auth('admin')->user()->can('prescriptions.list') && $visit->prescription->count() > 0) && !(auth('admin')->user()->can('emergency_prescriptions.list') && $visit->emergencyPrescriptions->count() > 0) ? 'show active' : '' }} p-3"
                        id="billing-services" role="tabpanel" aria-labelledby="billing-services-tab">

                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Prescribed By') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Service') }}</th>
                                        <th>{{ \App\CentralLogics\translate('Action') }}</th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">
                                    @foreach ($visit->procedures as $key => $procedure)
                                        <tr>
                                            <td>{{ 1 + $key }}</td>
                                            <td>{{ $procedure->doctor->full_name }}</td>
                                            <td>{{ $procedure->billingService->service_name }}</td>

                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-outline-primary square-btn" data-toggle="modal"
                                                        data-target="#BillServiceModal">
                                                        <i class="tio tio-visible"></i>
                                                    </a>

                                                    <!-- Modal for displaying PDF -->
                                                    <div class="modal fade" id="BillServiceModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="BillServiceModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="BillServiceModalLabel">
                                                                        Service Details</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p><strong>Service Name:</strong>
                                                                        {{ $procedure->billingService->service_name }}
                                                                    </p>
                                                                    <p><strong>Note:</strong>
                                                                        {{ $procedure->procedure_notes ?? 'N/A' }}</p>
                                                                    <p><strong>Prescribed By:</strong>
                                                                        {{ $procedure->doctor->full_name }}</p>
                                                                    <p><strong>Prescribed At:</strong>
                                                                        {{ $procedure->created_at }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </fieldset>

    <!-- Modal for displaying PDF -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">
                        Prescription PDF
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Empty iframe that will load the PDF when the modal opens -->
                    <iframe id="pdfIframe" width="100%" height="500px"></iframe>
                </div>
                <div class="modal-footer">
                    <!-- Button to download PDF -->
                    <a href="{{ route('admin.prescriptions.download', $visit->id) }}"
                        class="btn btn-success">Download
                        PDF</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif

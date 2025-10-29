@extends('layouts.admin.app')
@section('title', translate('POS'))
@section('content')
    @php
        $currency_code = \App\Models\BusinessSetting::where('key', 'currency')->first();
        $currency_symbol = \App\Models\Currency::where('currency_code', $currency_code->value)->first();
    @endphp

    <div class="content container-fluid">
        <div class="row gy-3 gx-2">
            <div class="col-lg-6">
                <div class="card overflow-hidden">
                    <!-- POS Title -->
                    <div class="pos-title">
                        <h4 class="mb-0">{{ translate('Product_Section') }}</h4>
                    </div>
                    <!-- End POS Title -->

                    {{-- POS Filter --}}
                    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between gap-3 gap-xl-4 px-4 py-4">
                        <div class="w-100 mr-xl-2">
                            <select name="category" id="category" class="form-control js-select2-custom mx-1"
                                title="{{ translate('select category') }}" onchange="set_category_filter(this.value)">
                                <option value="">{{ translate('All Categories') }}</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" {{ $category == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-100 mr-xl-2">
                            <select name="medicine" id="medicine" class="form-control js-select2-custom mx-1"
                                title="{{ translate('select medicine') }}" onchange="set_medicine_filter(this.value)">
                                <option value="">{{ translate('All Medicines') }}</option>
                                @foreach ($medicines as $item)
                                    <option value="{{ $item->id }}" {{ $medicine == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100 mr-xl-2">
                            <form id="search-form" class="header-item">
                                <!-- Search -->
                                <div class="input-group input-group-merge input-group-flush border rounded">
                                    <div class="input-group-prepend pl-1">
                                        <div class="input-group-text">
                                            <button style="background: none; border: none;" type="submit"
                                                class="tio-search"></button>
                                        </div>
                                    </div>
                                    <input id="datatableSearch" type="search" value="{{ $keyword ? $keyword : '' }}"
                                        name="search" class="form-control border-0 pr-2"
                                        placeholder="{{ \App\CentralLogics\translate('Search here') }}"
                                        aria-label="Search here">
                                </div>
                                <!-- End Search -->
                            </form>
                        </div>
                    </div>
                    {{-- POS Filter --}}

                    <div class="card-body pt-0" id="items">
                        <div class="pos-item-wrap justify-content-center">
                            @foreach ($products as $product)
                                @include('admin-views.pos._single_product', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                    <div class="px-3 d-flex justify-content-end">
                        {!! $products->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card billing-section-wrap">
                    <!-- POS Title -->
                    <div class="pos-title">
                        <h4 class="mb-0">{{ translate('Billing_Section') }}</h4>
                    </div>
                    <!-- End POS Title -->
                    <div class="p-2 p-sm-4">
                        <div class="form-group d-flex gap-2">
                            <select onchange="store_key('customer_id',this.value)" id="customer" name="customer_id"
                                data-placeholder="{{ \App\CentralLogics\translate('Walk In Customer') }}"
                                class="js-data-example-ajax form-control m-1">
                                <option value="" selected disabled>{{ translate('Walking Customer') }}</option>
                                @foreach (\App\Models\Customer::select('id', 'fullname')->get() as $customer)
                                    <option value="{{ $customer['id'] }}"
                                        {{ session()->get('customer_id') == $customer['id'] ? 'selected' : '' }}>
                                        {{ $customer['fullname'] }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button"
                                data-toggle="modal" data-target="#add-customer" title="Add Customer">
                                <i class="tio-add"></i>
                                {{ translate('Customer') }}
                            </button>
                        </div>
                        <div class="form-group d-flex gap-2">
                            <label for="prescriptionSelect">Select Prescription</label>
                            <select id="prescriptionSelect" onchange="showPrescriptionDetails(this)"
                                class="form-control js-select2-custom">
                                <option value="" selected disabled>Select Prescription</option>
                                @foreach ($prescriptions as $prescription)
                                    <option value="{{ $prescription->id }}" data-notes="{{ e($prescription->notes) }}"
                                        data-details='@json($prescription->details)'
                                        data-patient-id="{{ $prescription->visit->patient->id }}"
                                        data-patient-name="{{ $prescription->visit->patient->full_name }}"
                                        data-patient-age="{{ $prescription->visit->patient->age_detailed }}"
                                        data-patient-sex="{{ $prescription->visit->patient->gender }}">Patient
                                        {{ $prescription->visit->patient->full_name }}
                                        {{ $prescription->prescribed_date }} - Dr. {{ $prescription->doctor->f_name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div id="cart">
                            @include('admin-views.pos._cart')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="prescriptionDetailsModal" tabindex="-1" aria-labelledby="prescriptionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header prescription-header">
                    <div class="d-flex align-items-center">
                        <div class="prescription-icon me-3">
                            <i class="tio-medical-bag text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="patient-info">
                            <h4 class="modal-title mb-1 text-primary">
                                <i class="tio-user me-2"></i>Prescription Details
                            </h4>
                            <div class="patient-details">
                                <span class="badge badge-light-info me-2">
                                    <i class="tio-user me-1"></i><span id="patient-name"></span>
                                </span>
                                <span class="badge badge-light-secondary">
                                    <i class="tio-id-card me-1"></i>Sex: <span id="patient-sex"></span>
                                </span>
                                <span class="badge badge-light-secondary">
                                    <i class="tio-id-card me-1"></i>Age: <span id="patient-age"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="add-to-cart-form-prescription" class="mb-2">
                    @csrf
                    <div class="modal-body" id="prescription-details-body">
                        {{-- JS will populate here --}}
                    </div>
                    <div class="d-flex justify-content-end mt-3 mr-3">
                        <button class="btn btn-primary" type="submit" style="height: 45px">
                            <i class="tio-shopping-cart"></i>
                            {{ \App\CentralLogics\translate('add') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <style>
            .select2-container {
                margin-bottom: 0.2rem !important;
            }

            /* Prescription Modal Styles */
            .prescription-modal {
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                border: none;
            }

            .prescription-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 12px 12px 0 0;
                border-bottom: none;
            }

            .prescription-header .modal-title {
                color: white !important;
                font-weight: 600;
                font-size: 1.5rem;
            }

            .patient-details {
                margin-top: 0.5rem;
                display: flex;
                align-items: center;
            }

            .patient-details .badge {
                font-size: 0.85rem;
                border-radius: 20px;
            }

            .prescription-icon {
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                width: 60px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

        </style>
    </div>


    <!-- Quick View Modal -->
    <div class="modal fade" id="quick-view" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" id="quick-view-modal">

            </div>
        </div>
    </div>

    <!-- ADD Customer Modal -->
    <div class="modal fade" id="add-customer" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add_New_Customer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.pos.customer-store') }}" method="post" id="customer-form">
                        @csrf
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('First_Name') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" name="f_name" class="form-control" value=""
                                        placeholder="First name" required="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Last_Name') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" name="l_name" class="form-control" value=""
                                        placeholder="Last name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Email') }}</label>
                                    <input type="email" name="email" class="form-control" value=""
                                        placeholder="Ex : ex@example.com">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Phone') }}</label>
                                    <input type="text" name="phone" class="form-control" value=""
                                        placeholder="Phone">
                                </div>
                            </div>
                        </div>

                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('TIN') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" name="tin" class="form-control" value=""
                                        placeholder="TIN" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('A/C Number') }}</label>
                                    <input type="text" name="ac_number" class="form-control" value=""
                                        placeholder="A/C Number">
                                </div>
                            </div>
                        </div>

                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Address') }}</label>
                                    <input type="text" name="address" class="form-control" value=""
                                        placeholder="Address">
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


    @php($order = \App\Models\Order::find(session('last_order')))
    @if ($order)
        @php(session(['last_order' => false]))
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfModalLabel">
                            Invoice PDF</h5>
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
                        <a href="{{ route('admin.pos.download', $order->id) }}" class="btn btn-success">Download</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- @php($order = \App\Models\Order::find(session('last_order')))
    @if ($order)
        @php(session(['last_order' => false]))
        <div class="modal fade" id="print-invoice" tabindex="-1">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ \App\CentralLogics\translate('Print Invoice') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row" style="font-family: emoji;">
                        <div class="col-md-12">
                            <center>
                                <input type="button" class="btn btn-primary non-printable"
                                    onclick="printDiv('printableArea')"
                                    value="{{ translate('Proceed, If thermal printer is ready.') }}" />
                                <a href="{{ url()->previous() }}"
                                    class="btn btn-danger non-printable">{{ \App\CentralLogics\translate('Back') }}</a>
                            </center>
                            <hr class="non-printable">
                        </div>
                        <div class="row" id="printableArea" style="margin: auto;">
                            @include('admin-views.pos.order.invoice')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif --}}
@endsection

@push('script_2')
    <script>
        function setSymbol(amount) {
            const setting = @json(\App\Models\BusinessSetting::where('key', 'currency_symbol_position')->first());
            const currency_code = @json($currency_code);
            const currency_symbol = @json($currency_symbol);

            const position = setting && setting.value ? setting.value : 'right';
            const formattedAmount = Number(amount).toFixed(2);
            const symbol = currency_symbol.currency_symbol;

            if (position === 'left') {
                return symbol + formattedAmount;
            } else {
                return formattedAmount + symbol;
            }
        }

        function showPrescriptionDetails(selectElement) {
            $('#prescriptionSelect').select2('close');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const notes = selectedOption.getAttribute('data-notes') || 'N/A';
            const detailsJson = selectedOption.getAttribute('data-details');
            const patient_id = selectedOption.getAttribute('data-patient-id');
            const patient_name = selectedOption.getAttribute('data-patient-name');
            const patient_age = selectedOption.getAttribute('data-patient-age');
            const patient_sex = selectedOption.getAttribute('data-patient-sex');
            if (!detailsJson) return;

            const details = JSON.parse(detailsJson);
            let html = `<p><strong>Notes:</strong> ${notes}</p>`;
            html += `<div class="table-responsive">
                <input type="hidden" id="patient_id_prescription" value="${patient_id}">
                <table class="table table-bordered"><thead><tr>
                    <th>Medicine Type</th><th>Dosage</th><th>Duration</th><th>Interval</th>
                    <th>Time</th><th>Qty</th>
                    <th>Comment</th><th>select product</th>
                    <th>quantity</th>
                    </tr></thead><tbody>`;

            details.forEach((detail, index) => {
                html += `<tr>
                            <td>${detail.medicine.name}</td>
                            <td>${detail.dosage ?? '-'}</td>
                            <td>${detail.dose_duration ?? '-'}</td>
                            <td>${detail.dose_interval}</td>
                            <td>${detail.dose_time}</td>
                            <td>${detail.quantity}</td>
                            <td>${detail.comment ?? '-'}</td>
                            <td>
                                <select class="form-control mb-2 js-select2-custom prescription-product"
                                        data-index="${index}"
                                        data-medicine-id="${detail.medicine.id}"
                                        name="prescription[${index}][product]"
                                        required>
                                    <option value="">Select Product</option>
                                    ${detail.medicine.products.map(product => `
                                                        <option value="${product.id}"
                                                                data-inventories='${JSON.stringify(product.pharmacy_inventories)}'>
                                                            ${product.name}
                                                        </option>
                                                    `).join('')}
                                </select>
                                <select class="form-control mb-2 js-select2-custom prescription-batch mt-2"
                                        data-index="${index}"
                                        name="prescription[${index}][batch]"
                                        disabled
                                        required>
                                    <option value="">Select Batch</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control prescription-quantity"
                                       data-index="${index}"
                                       name="prescription[${index}][quantity]"
                                       value="1"
                                       min="1" max="1" required>
                            </td>
                        </tr>`;
            });

            html += '</tbody></table></div>';

            document.getElementById('prescription-details-body').innerHTML = html;
            document.getElementById('patient-name').innerHTML = patient_name;
            document.getElementById('patient-age').innerHTML = patient_age;
            document.getElementById('patient-sex').innerHTML = patient_sex;
            $('#prescriptionDetailsModal').modal('show');

            // Initialize select2 for new elements
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            // Add change event handler for medicine selection
            $(document).on('change', '.prescription-product', function() {
                const index = $(this).data('index');
                const productId = $(this).val();
                const batchSelect = $(`.prescription-batch[data-index="${index}"]`);

                if (productId) {
                    // Enable batch select
                    batchSelect.prop('disabled', false);

                    // Find the selected product's inventories
                    const selectedOption = $(this).find('option:selected');
                    const inventories = selectedOption.data('inventories') || [];
                    console.log(inventories);
                    // Populate batch options
                    let batchOptions = '<option value="">Select Batch</option>';
                    inventories.forEach(inventory => {
                        batchOptions += `
                            <option value="${inventory.id}"
                                    data-price="${inventory.selling_price}"
                                    data-quantity="${inventory.quantity}">
                                ${inventory.batch_number} - ${inventory.quantity} - ${setSymbol(inventory.selling_price)}
                            </option>
                        `;
                    });

                    batchSelect.html(batchOptions);

                    // Initialize select2 for the batch dropdown
                    $.HSCore.components.HSSelect2.init(batchSelect);
                } else {
                    // Disable and clear batch select
                    batchSelect.prop('disabled', true);
                    batchSelect.html('<option value="">Select Batch</option>');
                }
            });

            // Add change event handler for batch selection
            $(document).on('change', '.prescription-batch', function() {
                const index = $(this).data('index');
                const selectedOption = $(this).find('option:selected');
                const quantityInput = $(`.prescription-quantity[data-index="${index}"]`);

                if (selectedOption.val()) {
                    const maxQuantity = selectedOption.data('quantity');
                    quantityInput.attr('max', maxQuantity);
                    // Reset quantity if it exceeds new max
                    if (parseFloat(quantityInput.val()) > maxQuantity) {
                        quantityInput.val(maxQuantity);
                    }
                } else {
                    quantityInput.attr('max', 1);
                    quantityInput.val('');
                }
            });
        }

        // Modify the form submission handler
        $(document).on('submit', '#add-to-cart-form-prescription', function(e) {
            e.preventDefault();

            const formData = [];
            const rows = $('.prescription-product').length;
            const customer_id = $(`#patient_id_prescription`).val();

            for (let i = 0; i < rows; i++) {
                const productSelect = $(`.prescription-product[data-index="${i}"]`);
                const batchSelect = $(`.prescription-batch[data-index="${i}"]`);
                const quantityInput = $(`.prescription-quantity[data-index="${i}"]`);

                if (productSelect.val() && batchSelect.val() && quantityInput.val()) {
                    const selectedBatchOption = batchSelect.find('option:selected');
                    formData.push({
                        product_id: productSelect.val(),
                        batch: batchSelect.val(),
                        quantity: quantityInput.val(),
                        unit_price: selectedBatchOption.data('price')
                    });
                }
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.add-prescription-to-cart') }}',
                data: {
                    items: formData,
                    customer_id: customer_id
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    if (data.data == 1) {
                        Swal.fire({
                            icon: 'info',
                            title: '{{ translate('Cart') }}',
                            confirmButtonText: '{{ translate('Ok') }}',
                            text: "{{ \App\CentralLogics\translate('Batch Number not selected for some of medicines') }}"
                        });
                        return false;
                    }
                    $('#prescriptionDetailsModal').modal('hide');
                    $('.call-when-done').click();
                    toastr.success(
                        '{{ \App\CentralLogics\translate('Item has been added in your cart') }}!', {
                            CloseButton: true,
                            ProgressBar: true
                        });

                    updateCart();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        });

        function changePaymentType(paymentType) {
            // if (paymentType === 'cash') {
            //     $('#creditDate').hide();
            //     $('#amount_recieved').hide();
            //     // $('#bank_id').hide();
            //     $('#creditEndDate').prop('required', false);
            //     $('#creditEndDate').val(null);
            //     console.log("cash selected");

            // } else {
            //     console.log("credit selected");

            //     $('#creditDate').show();
            //     $('#amount_recieved').show();
            //     //$('#bank_id').show();
            //     $('#creditEndDate').prop('required', true);
            // }
        }

        $(document).ready(function() {
            @if ($order)
                // Load the PDF in iframe
                let pdfUrl = '{{ route('admin.pos.pdf', $order->id) }}';
                $('#pdfIframe').attr('src', pdfUrl);

                // Open the modal
                $('#pdfModal').modal('show');
            @endif
        });
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        function set_category_filter(id) {
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('category_id', id);
            location.href = nurl;
        }

        function set_medicine_filter(id) {
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('medicine_id', id);
            location.href = nurl;
        }

        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var keyword = $('#datatableSearch').val();
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('keyword', keyword);
            location.href = nurl;
        });

        function quickView(product_id) {
            $.ajax({
                url: '{{ route('admin.pos.quick-view') }}',
                type: 'GET',
                data: {
                    product_id: product_id
                },
                dataType: 'json', // added data type
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log("success...");
                    console.log(data);

                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function checkAddToCartValidity() {
            return true;
        }

        function cartQuantityInitialize() {
            $('.btn-number').click(function(e) {
                e.preventDefault();

                var fieldName = $(this).attr('data-field');
                var type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseFloat(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseFloat(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }
                        updateTotalPrice();

                    } else if (type == 'plus') {

                        // if (currentVal <  parseInt(input.attr('max')) ) {
                        input.val(currentVal + 1).change();
                        updateTotalPrice();
                        // }
                        // if (currentVal >=  parseInt(input.attr('max')) ) {
                        //     console.log(input.val(currentVal))
                        //     Swal.fire({
                        //         icon: 'error',
                        //         title: '{{ translate('Cart') }}',
                        //         confirmButtonText:'{{ translate('Ok') }}',
                        //         text: '{{ \App\CentralLogics\translate('stock limit exceeded ') }}.'
                        //     });
                        //     input.val(currentVal).change();
                        // }
                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function() {

                minValue = parseFloat($(this).attr('min'));
                maxValue = parseFloat($(this).attr('max'));
                valueCurrent = parseFloat($(this).val());

                var name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ translate('Cart') }}',
                        confirmButtonText: '{{ translate('Ok') }}',
                        text: '{{ \App\CentralLogics\translate('Sorry, the minimum value was reached') }}'
                    });
                    $(this).val($(this).data('oldValue'));
                }
                // if (valueCurrent <= maxValue) {
                //     $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                // } else {
                //     Swal.fire({
                //         icon: 'error',
                //         title: '{{ translate('Cart') }}',
                //         confirmButtonText:'{{ translate('Ok') }}',
                //         text: '{{ \App\CentralLogics\translate('Sorry, stock limit exceeded ') }}.'
                //     });
                //     $(this).val($(this).data('oldValue'));
                // }
            });
            $(".input-number").keydown(function(e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }

        // function getVariantPrice() {
        //     if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
        //         $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        //             }
        //         });
        //         $.ajax({
        //             type: "POST",
        //             url: '{{ route('admin.pos.variant_price') }}',
        //             data: $('#add-to-cart-form').serializeArray(),
        //             success: function (data) {
        //                 $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
        //                 $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
        //                 $('#add-to-cart-form #quantity_div #quantity').attr({"max" : data.stock});
        //             }
        //         });
        //     }
        // }

        function addToCart(form_id = 'add-to-cart-form') {
            if (checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('admin.pos.add-to-cart') }}',
                    data: $('#' + form_id).serializeArray(),
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {
                        if (data.data == 1) {
                            Swal.fire({
                                icon: 'info',
                                title: '{{ translate('Cart') }}',
                                confirmButtonText: '{{ translate('Ok') }}',
                                text: "{{ \App\CentralLogics\translate('Product already added in cart') }}"
                            });
                            return false;
                        } else if (data.data == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ translate('Cart') }}',
                                confirmButtonText: '{{ translate('Ok') }}',
                                text: '{{ \App\CentralLogics\translate('Sorry, product out of stock') }}.'
                            });
                            return false;
                        }
                        $('.call-when-done').click();

                        toastr.success(
                            '{{ \App\CentralLogics\translate('Item has been added in your cart') }}!', {
                                CloseButton: true,
                                ProgressBar: true
                            });

                        updateCart();
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            } else {
                Swal.fire({
                    type: 'info',
                    title: '{{ translate('Cart') }}',
                    confirmButtonText: '{{ translate('Ok') }}',
                    text: '{{ \App\CentralLogics\translate('Please choose all the options') }}'
                });
            }
        }

        function removeFromCart(key) {
            $.post('{{ route('admin.pos.remove-from-cart') }}', {
                _token: '{{ csrf_token() }}',
                key: key
            }, function(data) {
                if (data.errors) {
                    for (var i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    updateCart();
                    toastr.info('{{ \App\CentralLogics\translate('Item has been removed from cart') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }

            });
        }

        function emptyCart() {
            $.post('{{ route('admin.pos.emptyCart') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                updateCart();
                toastr.info('{{ \App\CentralLogics\translate('Item has been removed from cart') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
                location.reload();
            });
        }

        function updateCart() {
            $.post('<?php echo e(route('admin.pos.cart_items')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>'
            }, function(data) {
                $('#cart').empty().html(data);
            });
        }

        function store_key(key, value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            $.post({
                url: '{{ route('admin.pos.store-keys') }}',
                data: {
                    key: key,
                    value: value,
                },
                success: function(data) {
                    var selected_field_text = key;
                    var selected_field = selected_field_text.replace("_", " ");
                    var selected_field = selected_field.replace("id", " ");
                    var message = selected_field + ' ' + 'selected!';
                    var new_message = message.charAt(0).toUpperCase() + message.slice(1);
                    toastr.success((new_message), {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
            });
        }

        $(function() {
            $(document).on('click', 'input[type=number]', function() {
                this.select();
            });
        });


        function updateQuantity(e) {
            var element = $(e.target);
            var minValue = parseFloat(element.attr('min'));
            var valueCurrent = parseFloat(element.val());

            var key = element.data('key');
            if (valueCurrent >= minValue) {
                $.post('{{ route('admin.pos.updateQuantity') }}', {
                    _token: '{{ csrf_token() }}',
                    key: key,
                    quantity: valueCurrent
                }, function(data) {
                    updateCart();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ translate('Cart') }}',
                    confirmButtonText: '{{ translate('Ok') }}',
                    text: '{{ \App\CentralLogics\translate('Sorry, the minimum value was reached') }}'
                });
                element.val(element.data('oldValue'));
            }


            // Allow: backspace, delete, tab, escape, enter and .
            if (e.type == 'keydown') {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            }

        };

        // INITIALIZATION OF SELECT2
        // =======================================================
        $('.js-select2-custom').each(function() {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });

        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{ route('admin.pos.customers') }}',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });


        $('.js-data-example-ajax-2').select2()

        $('#order_place').submit(function(eventObj) {
            var customerId = $('#customer').val();
            var buyerType = customerId ? 'registered' : 'walk-in';
            console.log(customerId, buyerType)
            if (customerId) {
                $(this).append('<input type="hidden" name="customer_id" value="' + customerId + '" />');
            }
            $(this).append('<input type="hidden" name="buyer_type" value="' + buyerType + '" />');
            return true;
        });
    </script>
    <!-- IE Support -->
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write(
            '<script src="{{ asset(config('app.asset_path') . '/admin') }}/vendor/babel-polyfill/polyfill.min.js"><\/script>'
        );
    </script>
@endpush

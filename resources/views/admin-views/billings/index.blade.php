@extends('layouts.admin.app')

@section('title', translate('Add new billing'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset(config('app.asset_path') . '/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ \App\CentralLogics\translate('add_new_billing') }}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.invoice.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="patient_id">{{ \App\CentralLogics\translate('patient') }}</label>
                                        <select name="patient_id" id="patient-select" class="form-control js-select2-custom"
                                            required>
                                            <option value="" selected disabled>Select a patient</option>
                                        </select>
                                        {{-- <select name="patient_id" class="form-control js-select2-custom"  required>
                                                <option value="" selected disabled>{{ \App\CentralLogics\translate('select_patient') }}</option>
                                                @foreach ($patients as $patient)
                                                    <option value="{{$patient->id}}">{{$patient->full_name}}</option>
                                                @endforeach
                                            </select> --}}
                                    </div>
                                </div>


                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="nurse_id">{{\App\CentralLogics\translate('nurse')}}</label>
                                            <select name="nurse_id" class="form-control js-select2-custom" >
                                                <option value="" selected disabled>{{ \App\CentralLogics\translate('select_nurse') }}</option>
                                                @foreach ($nurses as $nurse)
                                                    <option value="{{$nurse->id}}">{{$nurse->admin->f_name}}  {{$nurse->admin->l_name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div> --}}



                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="date">{{ \App\CentralLogics\translate('date') }}</label>
                                        <input type="date" class="form-control" name="date"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="day">{{ \App\CentralLogics\translate('Service') }}</label>
                                        <select name="service_id" class="form-control js-select2-custom custom_field_select"
                                            required>
                                            <option value="" selected disabled>{{ \App\CentralLogics\translate('') }}
                                            </option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}" data-unit-cost="{{ $service->cost }}">
                                                    {{ $service->service_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="payment_method">{{ \App\CentralLogics\translate('payment_method') }}</label>
                                        <select name="payment_method" class="form-control js-select2-custom" required>
                                            <option value="" selected disabled>
                                                {{ \App\CentralLogics\translate('select_payment_method') }}</option>
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="telebirr">Telebirr</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 px-3" style=" padding:20px 0px">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label"
                                                    for="start">{{ \App\CentralLogics\translate('Quantity') }}</label>
                                                <input type="number" name="quantity" min="1" class="form-control">
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label"
                                                    for="end">{{ \App\CentralLogics\translate('Unit Cost') }}</label>
                                                <input type="number" name="unit_cost" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="input-label"
                                                    for="description">{{ \App\CentralLogics\translate('Note') }}</label>
                                                <textarea name="description" class="form-control" id="" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end align-items-center items gap-3">
                                            <div class="">
                                                <button type="button" class="btn btn-primary" id="addBillingDetailsForm">
                                                    <i class="tio-add"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="table-responsive datatable-custom mt-6" id="billingDetailsSection">
                                    <table
                                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="col-2">Service</th>
                                                <th class="col-3">Note</th>
                                                <th class="col-2">Quantity</th>
                                                <th class="col-2">Unit Cost</th>
                                                <th class="col-2">Price</th>
                                                <th class="col-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="billingDetailsTableBody">
                                            <!-- Billing details will be dynamically added here -->
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td colspan="3"></td>
                                                <!-- Adjust the colspan based on your table structure -->
                                                <td colspan="1"
                                                    style="
                                                vertical-align: middle;
                                                ">
                                                    Sub Total :</td>
                                                <td colspan="4" id="subTotal">0.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <!-- Adjust the colspan based on your table structure -->
                                                <td colspan="1"
                                                    style="
                                                vertical-align: middle;
                                                ">
                                                    Tax
                                                    ({{ \App\Models\BusinessSetting::where('key', 'tax')->first()->value ?? 0 }}
                                                    %) : </td>
                                                <td colspan="4" id="tax"> 0.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <!-- Adjust the colspan based on your table structure -->
                                                <td style="
                                                vertical-align: middle;
                                                "
                                                    colspan="1">Discount : </td>
                                                <td colspan="4">
                                                    <input type="decimalNumber" name="discount" id="discount"
                                                        class="form-control" style="width: 130px" placeholder="Discount"
                                                        value="0" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <!-- Adjust the colspan based on your table structure -->
                                                <td style="
                                                vertical-align: middle;
                                                "
                                                    colspan="1">Total :</td>
                                                <td colspan="4" id="total">0.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <!-- Adjust the colspan based on your table structure -->
                                                <td style="
                                                vertical-align: middle;
                                                "
                                                    colspan="1">Recieved Amount :</td>
                                                <td colspan="4">
                                                    <input type="decimalNumber" name="amount_paid" id="paid"
                                                        class="form-control" style="width: 130px" placeholder="Amount"
                                                        required />
                                                </td>
                                            </tr>
                                            {{-- <tr>
                                                <td colspan="3"></td> <!-- Adjust the colspan based on your table structure -->
                                                <td  colspan="1">Due  :</td>
                                                <td colspan="4" id="due">0.00</td>
                                            </tr> --}}
                                        </tfoot>
                                    </table>

                                </div>
                                <input type="hidden" name="billing_details" id="billingDetailsInput" value="">

                                <div class="col-12">

                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="reset"
                                            class="btn btn-secondary">{{ \App\CentralLogics\translate('reset') }}</button>
                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CentralLogics\translate('submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* CSS to remove cell borders in the table footer */
        /* CSS to remove cell borders only in the tfoot of a specific table */
        .table tfoot tr td {
            border: none;
        }

        /* Reset border for thead and tbody cells (if needed) */
        .table thead tr td,
        .table tbody tr td {
            border: 1px solid #ddd;
            /* Adjust as needed */
        }

        .d-none {
            display: none !important;
        }
    </style>

    @php($billing = \App\Models\Billing::find(session('last_billing')))

    @if ($billing)
        @php(session()->pull('last_billing'))
        <div class="modal fade" id="print-invoice" tabindex="-1">
            <div class="modal-dialog">
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
                            <div class="col-md-12 d-flex justify-content-center">
                                @include('admin-views.billings.invoices.invoice1')
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script_2')
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

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

    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#patient-select').select2({
                ajax: {
                    url: '{{ route('admin.appointment.get-patients') }}', // Add comma here
                    dataType: 'json',
                    delay: 250, // Debounce for better performance
                    data: function(params) {
                        return {
                            search: params.term, // Search term entered by the user
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
                minimumInputLength: 2, // Start searching after 2 characters
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            document.getElementById('discount').addEventListener('input', function() {
                var discountInput = this;
                if (discountInput.value === '') {
                    discountInput.value = '0';
                }
            });

            var selectedOption;

            // When the service selection changes
            $('select[name="service_id"]').on('change', function() {
                selectedOption = $(this).find(':selected');
                var unitCost = selectedOption.data('unit-cost');
                $('input[name="unit_cost"]').val(unitCost); // Populate unit cost field
                $('input[name="quantity"]').val(1); // Set quantity to 1

                console.log(unitCost);
            });

            // When the quantity changes
            $('input[name="quantity"]').on('input', function() {
                var unitCost = selectedOption.data('unit-cost');
                var quantity = parseFloat($(this).val());

                // Check if quantity is less than 1
                if (quantity < 1) {
                    // Update input field value to 1
                    $(this).val(1);
                    // Update quantity variable with corrected value
                    quantity = 1;
                }

                if (!isNaN(unitCost) && !isNaN(quantity)) {
                    var totalCost = unitCost * quantity;
                    if (!isNaN(totalCost)) {
                        $('input[name="unit_cost"]').val(totalCost.toFixed(2)); // Update unit cost field
                    }
                }
            });

        });
    </script>
@endpush
@push('script')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            @if ($billing)
                $('#print-invoice').modal('show');
            @endif
            var billingDetails = [];

            var taxRate = {{ \App\Models\BusinessSetting::where('key', 'tax')->first()->value ?? 0 }};

            // Handle the click event on the "Add" button
            $("#addBillingDetailsForm").click(function() {
                var serviceId = $("select[name='service_id']").val();
                var description = $("textarea[name='description']").val() || " ";
                var quantity = parseFloat($("input[name='quantity']").val()) || 0;
                var unitCost = parseFloat($("input[name='unit_cost']").val()) || 0;

                // Check for duplication
                var existingDetailIndex = findBillingDetailIndex(serviceId);
                if (existingDetailIndex !== -1) {
                    // If the detail already exists, increase the quantity and update the price
                    billingDetails[existingDetailIndex].quantity += quantity;
                    billingDetails[existingDetailIndex].price = billingDetails[existingDetailIndex]
                        .quantity * unitCost;
                } else {
                    // If the detail doesn't exist, add a new one
                    var formData = {
                        service_id: serviceId,
                        description: description,
                        quantity: quantity,
                        unit_cost: unitCost,
                        price: calculatePrice(),
                    };

                    if (formData.service_id && formData.quantity && formData.unit_cost) {
                        // Only add to billingDetails if all fields are non-empty
                        billingDetails.push(formData);
                    }
                }

                // Update the billing details table
                updateBillingDetailsTable();
            });

            function findBillingDetailIndex(serviceId) {
                for (var i = 0; i < billingDetails.length; i++) {
                    if (billingDetails[i].service_id == serviceId) {
                        return i;
                    }
                }
                return -1;
            }

            function updateBillingDetailsTable() {
                // Clear the table body
                $("#billingDetailsTableBody").empty();

                // Populate the table with billing details
                billingDetails.forEach(function(billingDetail, index) {
                    var maxLength = 20; // Adjust the maximum length as needed

                    var description = billingDetail.description;
                    var truncatedDescription = description.length > maxLength ? description.slice(0,
                        maxLength) + '...' : description;

                    var rowHtml =
                        '<tr>' +
                        '<td class="col-2">' + getServiceName(billingDetail.service_id) + '</td>' +
                        '<td class="col-3 copyable" title="' + description + '">' + truncatedDescription +
                        '</td>' +
                        '<td class="col-2">' + billingDetail.quantity + '</td>' +
                        '<td class="col-2">' + billingDetail.unit_cost + '</td>' +
                        '<td class="col-2">' + billingDetail.price + '</td>' +
                        '<td class="col-1"><a class="cursor-pointer" onclick="removeBillingDetail(' +
                        index + ')"><i class="tio-delete text-danger"></i></a></td>' +
                        '</tr>';

                    $("#billingDetailsTableBody").append(rowHtml);

                    // Add click event listener for copying
                    $('.copyable').on('click', function() {
                        copyToClipboard($(this).attr('title'));
                    });

                    // Function to copy text to clipboard
                    function copyToClipboard(text) {
                        var tempInput = document.createElement('input');
                        tempInput.value = text;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempInput);
                        alert('Text copied to clipboard: ' + text);
                    }
                });

                // Update the hidden input with the JSON data
                $("#billingDetailsInput").val(JSON.stringify(billingDetails));

                // Automatically populate the tfoot based on billing details
                populateTfoot();
            }

            function getServiceName(serviceId) {
                // Replace this with your actual logic to fetch medicine name
                var service = {!! \App\Models\Service::all()->toJson() !!}.find(function(service) {
                    return service.id == serviceId;
                });

                return service ? service.service_name : '';
            }
            // Function to calculate and populate tfoot based on billing details
            function populateTfoot() {
                var subTotal = calculateSubTotal();
                var tax = calculateTax(subTotal);
                var total = subTotal + tax;

                // Get discount percentage from input
                var discount = parseFloat($("#discount").val()) || 0;

                // Calculate discount amount
                var discountAmount = discount

                // Apply discount
                total -= discountAmount;

                // Display values in the tfoot
                $("#subTotal").text(subTotal.toFixed(2));
                $("#tax").text(tax.toFixed(2));
                $("#total").text(total.toFixed(2));

                // Get paid amount from input
                var paid = parseFloat($("#paid").val()) || 0;

                // Calculate due amount
                var due = total - paid;

                // Display due amount
                $("#due").text(due.toFixed(2));
            }

            // Function to calculate Sub Total based on billing details
            function calculateSubTotal() {
                var subTotal = 0;
                billingDetails.forEach(function(billingDetail) {
                    subTotal += billingDetail.quantity * billingDetail.unit_cost;
                });
                return subTotal;
            }

            // Function to calculate Tax based on Sub Total
            function calculateTax(subTotal) {
                // You can implement your own tax calculation logic here
                // For example, apply a fixed tax rate
                return (taxRate / 100) *
                    subTotal; // This is just a placeholder, replace with your actual calculation
            }

            // Function to calculate the price based on quantity and unit cost
            function calculatePrice() {
                var quantity = parseFloat($("input[name='quantity']").val()) || 0;
                var unitCost = parseFloat($("input[name='unit_cost']").val()) || 0;
                return quantity * unitCost;
            }


            // Function to remove a billing detail by index
            window.removeBillingDetail = function(index) {
                billingDetails.splice(index, 1);
                updateBillingDetailsTable();
            };


            // Event listeners to trigger updates when the user interacts with the form
            $("#discount, #paid").on("input", function() {
                populateTfoot();
            });

        });
    </script>



    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush

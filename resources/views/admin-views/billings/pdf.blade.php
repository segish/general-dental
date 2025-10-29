<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        /* General Styles */
        body {
            background-color: #f8f9fa;
            padding: 2rem 0;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .invoice-container {
            background-color: #ffffff;
            padding: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .logo img {
            max-height: 60px;
            width: auto;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 0.75rem;
        }

        .invoice-header h2 {
            font-weight: bold;
            color: #212529;
            margin: 0;
            font-size: 1.25rem;
        }

        .invoice-details {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: #6c757d;
        }

        .invoice-details p {
            margin: 0;
        }

        .fiscal-notice {
            text-align: center;
            color: #dc3545;
            font-size: 0.5rem;
        }

        .bill-details {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: #6c757d;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.75rem;
        }

        .info-table td {
            padding: 0.25rem 0;
            font-size: 0.75rem;
            border: none;
        }

        .info-table td:first-child {
            font-weight: 600;
            color: #6c757d;
            text-align: left;
            width: 40%;
        }

        .info-table td:last-child {
            text-align: right;
            width: 60%;
        }

        .test-details,
        .payment-history {
            margin-top: 1rem;
        }

        .test-details h5,
        .payment-history h5 {
            font-weight: 600;
            color: #6c757d;
            margin: 0 0 0.5rem 0;
            font-size: 0.875rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5rem;
        }

        th,
        td {
            font-size: 0.75rem;
        }

        th {
            background-color: #EEEEEE;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .footer-text {
            text-align: center;
            color: #6c757d;
            font-size: 0.75rem;
            margin: 0.5rem 0 0 0;
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;
        $currency_code = \App\Models\BusinessSetting::where('key', 'currency')->first()->value;
        $pdf_company_name_en = \App\CentralLogics\Helpers::get_business_settings('pdf_company_name_en') ?? '';
        $slogan = \App\CentralLogics\Helpers::get_business_settings('slogan') ?? '';
        $currency_position = \App\CentralLogics\Helpers::get_business_settings('currency_symbol_position') ?? 'right';
        $tin_no = \App\CentralLogics\Helpers::get_business_settings('tin_no') ?? '-';
        $address = \App\CentralLogics\Helpers::get_business_settings('address') ?? '-';
    @endphp

    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')
    <div class="invoice-container">

        <div class="invoice-header">
            <h2>Clinical Invoice</h2>
            <div class="invoice-details">
                <p>Date & Time: {{ Carbon::parse($billing->created_at)->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        <table width="100%" style="border: none !important; border-spacing: 0;">
            <tr>
                <td width="48%" style="border: none; vertical-align: top;">
                    <table border="0" width="100%" cellpadding="2"
                        style="background-color: #F8F8F8; border-collapse: collapse; padding: 0.25rem; border-radius: 0.5rem; height: 6rem; overflow: visible;">
                        <tr>
                            <td>Name:</td>
                            <td class="bill-details text-right">{{ $billing->visit->patient->full_name }}</td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td class="bill-details text-right">{{ $billing->visit->patient->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>TIN No:</td>
                            <td class="bill-details text-right">{{ $billing->visit->patient->tin_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Date of Registration:</td>
                            <td class="bill-details text-right">
                                {{ $billing->visit->patient->registration_date ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td width="48%" style="border: none; vertical-align: top; padding-left: 10px;">
                    <table border="0" width="100%" cellpadding="2"
                        style="background-color: #F8F8F8; border-collapse: collapse; padding: 0.25rem; border-radius: 0.5rem; height: 6rem; overflow: visible;">
                        <tr>
                            <td>From:</td>
                            <td class="bill-details text-right">{{ $pdf_company_name_en }}</td>
                        </tr>
                        <tr>
                            <td>TIN No:</td>
                            <td class="bill-details text-right">{{ $tin_no }}</td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td class="bill-details text-right">{{ $address }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        @php
            $total = 0;
            $discount = 0;
        @endphp
        @foreach ($billing->visit->billings as $billing)
            @php
                $discount += $billing->discounted_amount;
            @endphp
            @if ($billing->emergency_medicine_issuance_id)
                {{-- <h5>Inclinic Items</h5> --}}
                <table class="table mt-2" style="border: 1px solid #dee2e6;">
                    <thead class="table-light">
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th class="text-right">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billing->billingDetail as $billingDetail)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td>{{ $billingDetail->prescreption->medicine->medicine->name }}</td>
                                <td>{{ $billingDetail->prescreption->quantity }}</td>
                                <td class="text-right">
                                    {{ $currency_position == 'right' ? $billingDetail->unit_cost . ' ' . $currency_code : $currency_code . ' ' . $billingDetail->unit_cost }}
                                </td>
                                @php
                                    $total += $billingDetail->unit_cost;
                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($billing->laboratory_request_id)
                {{-- <h5>Lab Tests</h5> --}}
                <table class="table mt-2" style="border: 1px solid #dee2e6;">
                    <thead class="table-light">
                        <tr>
                            <th>Test</th>
                            <th class="text-right">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billing->billingDetail as $billingDetail)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td>{{ $billingDetail->test->test_name }}</td>
                                <td class="text-right">
                                    {{ $currency_position == 'right' ? $billingDetail->unit_cost . ' ' . $currency_code : $currency_code . ' ' . $billingDetail->unit_cost }}
                                </td>
                            </tr>
                            @php
                                $total += $billingDetail->unit_cost;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($billing->radiology_request_id)
                {{-- <h5>Radiologies</h5> --}}
                <table class="table mt-2" style="border: 1px solid #dee2e6;">
                    <thead class="table-light">
                        <tr>
                            <th>Radiology</th>
                            <th class="text-right">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billing->billingDetail as $billingDetail)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td>{{ $billingDetail->radiology->radiology_name }}</td>
                                <td class="text-right">
                                    {{ $currency_position == 'right' ? $billingDetail->unit_cost . ' ' . $currency_code : $currency_code . ' ' . $billingDetail->unit_cost }}
                                </td>
                            </tr>
                            @php
                                $total += $billingDetail->unit_cost;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($billing->billingService)
                {{-- <h5>Service Fees</h5> --}}
                <table class="table mt-2" style="border: 1px solid #dee2e6;">
                    <thead class="table-light">
                        <tr>
                            <th>Services</th>
                            <th class="text-right">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billing->billingDetail as $billingDetail)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td>{{ $billingDetail->billingService->service_name }}</td>
                                <td class="text-right">
                                    {{ $currency_position == 'right' ? $billingDetail->unit_cost . ' ' . $currency_code : $currency_code . ' ' . $billingDetail->unit_cost }}
                                </td>
                            </tr>
                            @php
                                $total += $billingDetail->unit_cost;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($billing->emergencyMedicineIssuance)
                {{-- <h5>Emergency Medicine Issuance</h5> --}}
                <table class="table mt-2" style="border: 1px solid #dee2e6;">
                    <thead class="table-light">
                        <tr>
                            <th>Emergency Items</th>
                            <th class="text-right">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billing->billingDetail as $billingDetail)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td>{{ $billingDetail->emergencyMedicineIssuance->medicine_name }}</td>
                                <td class="text-right">
                                    {{ $currency_position == 'right' ? $billingDetail->unit_cost . ' ' . $currency_code : $currency_code . ' ' . $billingDetail->unit_cost }}
                                </td>
                            </tr>
                            @php
                                $total += $billingDetail->unit_cost;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($billing->dischargeService)
                {{-- <h5 class="mt-4">Inpatient Fees</h5> --}}
                <table class="table table-bordered mt-2" style="border: 1px solid #dee2e6;">
                    <thead class="table-light">
                        <tr>
                            <th>Bed</th>
                            <th class="text-center">Stay Days</th>
                            <th class="text-center">Price per Day</th>
                            <th class="text-right">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billing->billingDetail as $billingDetail)
                            @php
                                $discharge = $billingDetail->dischargeService;
                                $bedNumber = $discharge->visit->ipdRecord->bed->bed_number ?? 'N/A';
                                $stayDays = $discharge->stay_days ?? 1;
                                $unitCost = $billingDetail->unit_cost;
                                $total = $stayDays * $unitCost;
                            @endphp
                            <tr>
                                <td>{{ $bedNumber }}</td>
                                <td class="text-center">{{ $stayDays }}</td>
                                <td class="text-center">
                                    {{ $currency_position == 'right' ? $unitCost . ' ' . $currency_code : $currency_code . ' ' . $unitCost }}
                                </td>
                                <td class="text-right">
                                    {{ $currency_position == 'right' ? number_format($total, 2) . ' ' . $currency_code : $currency_code . ' ' . number_format($total, 2) }}
                                </td>
                            </tr>
                            @php
                                $total += $billingDetail->unit_cost;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            @endif


            @if ($billing->patientProcedures)
                <h5>Patient Procedures</h5>
                <p>{{ $billing->patientProcedures->procedure_name }} -
                    <strong>{{ $currency_position == 'right' ? $billing->patientProcedures->cost . ' ' . $currency_code : $currency_code . ' ' . $billing->patientProcedures->cost }}</strong>
                </p>
                @php
                    $total += $billing->patientProcedures->cost;
                @endphp
            @endif
        @endforeach
        <hr>
        <table class="table mt-2" style="border: 1px solid #dee2e6;">
            <tbody>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td>Sub Total</td>
                    <td class="text-right">
                    <strong>{{ $currency_position == 'right' ? $total . ' ' . $currency_code : $currency_code . ' ' . $total }}</strong>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td>Discount</td>
                    <td class="text-right">
                    <strong>{{ $currency_position == 'right' ? $discount . ' ' . $currency_code : $currency_code . ' ' . $discount }}</strong>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td>Total</td>
                    <td class="text-right">
                    <strong>{{ $currency_position == 'right' ? $total-$discount . ' ' . $currency_code : $currency_code . ' ' . $total-$discount }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <h5>Payment History</h5>
        <table class="table mt-2" style="border: 1px solid #dee2e6;">
            <thead>
                <tr>
                    <th>Method</th>
                    <th>FS No</th>
                    <th>Date</th>
                    <th class="text-right">Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($billing->visit->billings as $billing)
                    @foreach ($billing->payments as $payment)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td>{{ $payment->payment_method }}</td>
                            <td>{{ $payment->fn_no ?? '---' }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}</td>
                            <td class="text-right">
                                {{ $currency_position == 'right' ? number_format($payment->amount_paid, 2) . ' ' . $currency_code : $currency_code . ' ' . number_format($payment->amount_paid, 2) }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <p>{{ $slogan }}</p>
        {{-- <p>Thank you for choosing our laboratory services.</p> --}}
        <p class="fiscal-notice">INVALID WITHOUT FISCAL RECEIPT ATTACHED</p>
    </div>

    @include('admin-views.pdf-components.footer')
</body>

</html>

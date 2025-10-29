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
            margin-top: 100px !important;
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
        $pdf_company_name_en = \App\Models\PharmacyCompanySetting::where('key', 'company_name')->first()->value;
        $slogan = \App\CentralLogics\Helpers::get_business_settings('slogan') ?? '';
        $currency_position = \App\CentralLogics\Helpers::get_business_settings('currency_symbol_position') ?? 'right';
        $tin_no = \App\Models\PharmacyCompanySetting::where('key', 'tin_no')->first()->value;
        $vat_reg_no = \App\Models\PharmacyCompanySetting::where('key', 'vat_reg_no')->first()->value;
        $address = \App\Models\PharmacyCompanySetting::where('key', 'address')->first()->value;
    @endphp

    @php
        $pdf_header_logo = \App\Models\PharmacyCompanySetting::where('key', 'logo')->first()->value;
        $header_logo_path = public_path('storage/app/public/assets/' . $pdf_header_logo);
        $header_logo_path = $pdf_header_logo ? storage_path('app/public/assets/' . $pdf_header_logo) : null;

    @endphp
    <style>
        @page {
            margin: 0;
        }

        #header {
            position: fixed;
            left: 0;
            top: 0;
            right: 0;
            height: 100px;
            text-align: center;
            background-color: white;
        }

        #header img {
            width: 100%;
            height: 100px;
            display: block;
            margin: 0;
        }
    </style>
    @if ($header_logo_path && file_exists($header_logo_path))
        <div id="header">
            <img src="{{ 'file://' . $header_logo_path }}">
        </div>
    @endif

    <div class="invoice-container">
        <div class="invoice-header">
            <h2>Pharmacy Invoice</h2>
            <div class="invoice-details">
                <p>Invoice #: {{ $order->invoice_no }}</p>
                <p>FS No.: {{ $order->fs_no ?? 'N/A' }}</p>
                <p>Date & Time: {{ Carbon::parse($order->created_at)->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        <table width="100%" style="border: none !important; border-spacing: 0;">
            <tr>
                <td width="48%" style="border: none; vertical-align: top;">
                    <table border="0" width="100%" cellpadding="2"
                        style="background-color: #F8F8F8; border-collapse: collapse; padding: 0.25rem; border-radius: 0.5rem; height: 6rem; overflow: visible;">
                        @php
                            $buyerType = $order->buyer_type ?? 'walk-in';
                            $patient = $order->patient ?? null;
                            $customer = $order->customer ?? null;
                        @endphp

                        {{-- Name --}}
                        @if ($buyerType === 'walk-in' || $patient || $customer)
                            <tr>
                                <td>Name:</td>
                                <td class="bill-details text-right">
                                    @if ($buyerType === 'walk-in')
                                        Walk-in Customer
                                    @elseif($patient)
                                        {{ $patient->full_name }}
                                    @elseif($customer)
                                        {{ $customer->fullname }}
                                    @endif
                                </td>
                            </tr>
                        @endif

                        {{-- Address --}}
                        @if ($buyerType !== 'walk-in' && ($patient?->address || $customer?->address))
                            <tr>
                                <td>Address:</td>
                                <td class="bill-details text-right">
                                    {{ $patient?->address ?? $customer?->address }}
                                </td>
                            </tr>
                        @endif

                        {{-- TIN No --}}
                        @if ($buyerType !== 'walk-in' && $patient?->tin_no)
                            <tr>
                                <td>TIN No:</td>
                                <td class="bill-details text-right">{{ $patient->tin_no }}</td>
                            </tr>
                        @endif
                    </table>
                </td>
                <td width="48%" style="border: none; vertical-align: top; padding-left: 10px;">
                    <table border="0" width="100%" cellpadding="2"
                        style="background-color: #F8F8F8; border-collapse: collapse; padding: 0.25rem; border-radius: 0.5rem; height: 6rem; overflow: visible;">

                        {{-- From --}}
                        @if (!empty($pdf_company_name_en))
                            <tr>
                                <td>From:</td>
                                <td class="bill-details text-right">{{ $pdf_company_name_en }}</td>
                            </tr>
                        @endif

                        {{-- TIN No --}}
                        @if (!empty($tin_no))
                            <tr>
                                <td>TIN No:</td>
                                <td class="bill-details text-right">{{ $tin_no }}</td>
                            </tr>
                        @endif

                        {{-- VAT Reg. No --}}
                        @if (!empty($vat_reg_no))
                            <tr>
                                <td>VAT Reg. No:</td>
                                <td class="bill-details text-right">{{ $vat_reg_no }}</td>
                            </tr>
                        @endif

                        {{-- Address --}}
                        @if (!empty($address))
                            <tr>
                                <td>Address:</td>
                                <td class="bill-details text-right">{{ $address }}</td>
                            </tr>
                        @endif

                    </table>
                </td>
            </tr>
        </table>

        @if ($order->details->isNotEmpty())
            <h5>Details</h5>
            <table class="table mt-2" style="border: 1px solid #dee2e6;">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_discount = 0;
                    @endphp
                    @foreach ($order->details as $detail)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td>{{ $detail->inventory->product->name }}</td>
                            <td>{{ $detail->unit }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>
                                @php
                                    $total = $detail->price;
                                @endphp
                                {{ \App\CentralLogics\Helpers::set_symbol($total) }}
                            </td>
                            <td>
                                @php
                                    $discount = $detail->discount_on_product ?? 0;
                                @endphp
                                {{ \App\CentralLogics\Helpers::set_symbol($discount) }}
                            </td>
                            <td>
                                @php
                                    $tax = $detail->tax_amount ?? 0;
                                @endphp
                                {{ \App\CentralLogics\Helpers::set_symbol($tax) }}
                            </td>
                            <td class="text-right">
                                @php
                                    $subtotal = $detail->price * $detail->quantity;
                                    $total =
                                        $subtotal - ($detail->discount_on_product ?? 0) + ($detail->tax_amount ?? 0);
                                    $total_discount += $detail->discount_on_product ?? 0;
                                @endphp
                                {{ $currency_position == 'right'
                                    ? \App\CentralLogics\Helpers::set_symbol($total) . ' ' . $currency_code
                                    : $currency_code . ' ' . \App\CentralLogics\Helpers::set_symbol($total) }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div style="margin-top: 2rem; text-align: right;">
            <table style="width: 300px; margin-left: auto; border-collapse: collapse;">
                <tr style="border-top: 1px solid #dee2e6;">
                    <td style="padding: 0.25rem; font-size: 1rem; font-weight: bold; text-align: left;">Total discount:
                    </td>
                    <td style="padding: 0.25rem; font-size: 1rem; font-weight: bold; text-align: right;">
                        {{ \App\CentralLogics\Helpers::set_symbol($total_discount) }}
                    </td>
                </tr>
                <tr style="border-top: 1px solid #dee2e6;">
                    <td style="padding: 0.25rem; font-size: 1rem; font-weight: bold; text-align: left;">Total tax:
                    </td>
                    <td style="padding: 0.25rem; font-size: 1rem; font-weight: bold; text-align: right;">
                        {{ \App\CentralLogics\Helpers::set_symbol($order->total_tax_amount) }}
                    </td>
                </tr>
                <tr style="border-top: 1px solid #dee2e6;">
                    <td style="padding: 0.25rem; font-size: 1rem; font-weight: bold; text-align: left;">Grand Total:
                    </td>
                    <td style="padding: 0.25rem; font-size: 1rem; font-weight: bold; text-align: right;">
                        {{ \App\CentralLogics\Helpers::set_symbol($order->subtotal + $order->total_tax_amount + $order->extra_discount) }}
                    </td>
                </tr>
            </table>
        </div>

        <p style="text-align: center;">{{ $slogan }}</p>
        {{-- <p>Thank you for choosing our laboratory services.</p> --}}
        <p style="text-align: center;">INVALID WITHOUT FISCAL RECEIPT ATTACHED</p>
    </div>

    @include('admin-views.pdf-components.footer')
</body>

</html>

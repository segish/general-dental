<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pharmacy Revenue Report</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            margin-top: 180px;
            margin-left: 40px;
            margin-right: 40px;
            margin-bottom: 50px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .date-range {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }

        th {
            background-color: #f5f5f5;
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')
    <div class="header">
        <h2>Pharmacy Revenue Report</h2>
    </div>

    <div class="date-range">
        <strong>Period:</strong> {{ $startDate->format('Y-m-d') }} to {{ $endDate->format('Y-m-d') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Revenue</th>
                <th>Total Paid</th>
                <th>Outstanding</th>
                <th>Total Tax</th>
                <th>Total Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($revenues as $revenue)
                <tr>
                    <td>{{ $revenue->date }}</td>
                    <td>{{ number_format($revenue->total_revenue, 2) }}</td>
                    <td>{{ number_format($revenue->total_paid, 2) }}</td>
                    <td>{{ number_format($revenue->outstanding, 2) }}</td>
                    <td>{{ number_format($revenue->total_tax, 2) }}</td>
                    <td>{{ number_format($revenue->total_profit, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>Total</td>
                <td>{{ number_format($totalRevenue, 2) }}</td>
                <td>{{ number_format($totalPaid, 2) }}</td>
                <td>{{ number_format($totalOutstanding, 2) }}</td>
                <td>{{ number_format($totalTax, 2) }}</td>
                <td>{{ number_format($totalProfit, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Revenue:</strong> {{ number_format($totalRevenue, 2) }}</p>
        <p><strong>Total Paid:</strong> {{ number_format($totalPaid, 2) }}</p>
        <p><strong>Total Outstanding:</strong> {{ number_format($totalOutstanding, 2) }}</p>
        <p><strong>Total Tax:</strong> {{ number_format($totalTax, 2) }}</p>
        <p><strong>Total Profit:</strong> {{ number_format($totalProfit, 2) }}</p>
    </div>
</body>

</html>

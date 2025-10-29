<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Product Performance Report</title>
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

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
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
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
            color: #333;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')
    <div class="header">
        <h1>Product Performance Report</h1>
        <p>Period: {{ $startDate->format('Y-m-d') }} to {{ $endDate->format('Y-m-d') }}</p>
    </div>

    <div class="section-title">Top Selling Products</div>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topSellingProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->total_quantity }}</td>
                    <td>{{ number_format($product->total_revenue, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Low Selling Products</div>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lowSellingProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->total_quantity }}</td>
                    <td>{{ number_format($product->total_revenue, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

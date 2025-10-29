<!DOCTYPE html>
<html>

<head>
    <title>Test Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
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
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .summary-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .summary-box h3 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Test Report</h1>
        <div class="date-range">
            Period: {{ \Carbon\Carbon::parse($data['fromDate'])->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($data['toDate'])->format('d M Y') }}
        </div>
    </div>

    <div class="summary-box">
        <h3>Summary</h3>
        <p>Total Tests: {{ $data['totalTests'] }}</p>
        <p>Completed Tests: {{ $data['statusCompleted'] }}</p>
        <p>Rejected Tests: {{ $data['statusRejected'] }}</p>
        <p>Pending Tests: {{ $data['statusPending'] }}</p>
    </div>

    <h3>Tests by Type</h3>
    <table>
        <thead>
            <tr>
                <th>Test Type</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['testsByType'] as $test)
                <tr>
                    <td>{{ $test->test_type }}</td>
                    <td>{{ $test->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

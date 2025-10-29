<!DOCTYPE html>
<html>

<head>
    <title>Lab Test Result - Stool Examination</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin-top: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid black;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')
    @if ($testResult->laboratoryRequestTest->laboratoryRequest->requested_by == 'physician')
        @include('admin-views.pdf-components.physican-patient-detail', [
            'patient' => $testResult->laboratoryRequestTest->laboratoryRequest->patient,
            'laboratoryRequest' => $testResult->laboratoryRequestTest->laboratoryRequest,
            'specimens' => $testResult->laboratoryRequestTest->laboratoryRequest->specimens,
        ])
    @else
        @include('admin-views.pdf-components.self-patient-detail', [
            'patient' => $testResult->laboratoryRequestTest->laboratoryRequest->patient,
        ])
    @endif
    <!-- Combined Test Result Table -->
    <table>
        <!-- Macroscopic Test Section -->
        <tr>
            <th class="section-title" colspan="2">MACROSCOPIC TEST</th>
        </tr>
        @foreach ($testResult->attributes as $attribute)
            @if ($attribute->attribute->test_category == 'Macroscopic')
                <tr>
                    <td><strong>{{ $attribute->attribute->attribute_name }}</strong></td>
                    <td>{{ $attribute->result_value ?? 'N/A' }}</td>
                </tr>
            @endif
        @endforeach

        <!-- Microscopic Test Section -->
        <tr>
            <th class="section-title" colspan="2">MICROSCOPIC TEST</th>
        </tr>
        @foreach ($testResult->attributes as $attribute)
            @if ($attribute->attribute->test_category == 'Microscopic')
                <tr>
                    <td><strong>{{ $attribute->attribute->attribute_name }}</strong></td>
                    <td>{{ $attribute->result_value ?? 'N/A' }}</td>
                </tr>
            @endif
        @endforeach

        <!-- Chemical Test Section -->
        <tr>
            <th class="section-title" colspan="2">CHEMICAL TEST</th>
        </tr>
        @foreach ($testResult->attributes as $attribute)
            @if ($attribute->attribute->test_category == 'Chemical')
                <tr>
                    <td><strong>{{ $attribute->attribute->attribute_name }}</strong></td>
                    <td>{{ $attribute->result_value ?? 'N/A' }}</td>
                </tr>
            @endif
        @endforeach
    </table>

    @include('admin-views.pdf-components.footer', [
        'patient' => $testResult->laboratoryRequestTest->laboratoryRequest->patient,
        'test' => $testResult->laboratoryRequestTest->test,
        'testResult' => $testResult,
        'specimens' => $testResult->laboratoryRequestTest->laboratoryRequest->specimens,
    ])

</body>

</html>

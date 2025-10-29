<!DOCTYPE html>
<html>

<head>
    <title>Lab Test Result </title>
    <style>
        @page {
            margin: 0;
            /* No margin for the entire page */
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            margin-top: 150px;
            margin-left: 40px;
            margin-right: 40px;
            margin-bottom: 50px;
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
    <h3 style="text-align: center; font-weight: bold; text-transform: uppercase;">{{$testResult->laboratoryRequestTest->test->test_name}} TEST RESULT</h3>
    @if ($testResult->laboratoryRequestTest->laboratoryRequest->requested_by == 'physician')
        @include('admin-views.pdf-components.physican-patient-detail', [
            'patient' => $testResult->laboratoryRequestTest->laboratoryRequest->visit->patient,
            'laboratoryRequest' => $testResult->laboratoryRequestTest->laboratoryRequest,
            'specimens' => $testResult->laboratoryRequestTest->laboratoryRequest->specimens,
        ])
    @else
        @include('admin-views.pdf-components.self-patient-detail', [
            'patient' => $testResult->laboratoryRequestTest->laboratoryRequest->visit->patient,
        ])
    @endif

    @if ($testResult->laboratoryRequestTest->test->result_type == 'multi-type')
        @include('admin-views.pdf-components.multi-type-results', [
            'testResult' => $testResult,
        ])
    @elseif ($testResult->laboratoryRequestTest->test->result_type == 'numeric')
        @include('admin-views.pdf-components.numeric-results', [
            'patient' => $testResult->laboratoryRequestTest->laboratoryRequest->patient,
            'attributes' => $attributes,
        ])
    @elseif ($testResult->laboratoryRequestTest->test->result_type == 'text')
        @include('admin-views.pdf-components.text-results', [
            'patient' => $testResult->laboratoryRequestTest->laboratoryRequest->patient,
        ])
    @endif

    <div>
        {!! $testResult->laboratoryRequestTest->test->additional_notes !!}
    </div>

    @include('admin-views.pdf-components.result-interpretation', [
        'patient' => $testResult->laboratoryRequestTest->laboratoryRequest->patient,
        'test' => $testResult->laboratoryRequestTest->test,
        'testResult' => $testResult,
        'specimens' => $testResult->laboratoryRequestTest->laboratoryRequest->specimens,
    ])

    @include('admin-views.pdf-components.footer')

</body>

</html>

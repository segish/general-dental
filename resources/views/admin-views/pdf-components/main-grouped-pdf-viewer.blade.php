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

    @php
        $testResult = $testResults->first(); // ✅ define it manually
        $labTest = optional($testResult->laboratoryRequestTest);
        $labRequest = optional($labTest)->laboratoryRequest;
        $test = $labTest->test;
        $patient =
            $labRequest && $labRequest->requested_by === 'physician'
                ? optional($labRequest->visit)->patient
                : $labRequest->patient;
        $specimens = $labRequest->specimens ?? [];
    @endphp

    @php
        $numericResults = $testResults->filter(function ($result) {
            return optional($result->laboratoryRequestTest->test)->result_type === 'numeric';
        });

        $nonNumericResults = $testResults->reject(function ($result) {
            return optional($result->laboratoryRequestTest->test)->result_type === 'numeric';
        });
    @endphp



    {{-- Patient Inxo --}}
    @if ($labRequest && $labRequest->requested_by === 'physician')
        @include('admin-views.pdf-components.physican-patient-detail', [
            'patient' => $labRequest->visit->patient,
            'laboratoryRequest' => $labRequest,
            'specimens' => $specimens,
        ])
    @else
        @include('admin-views.pdf-components.self-patient-detail', [
            'patient' => $labRequest->patient,
        ])
    @endif

    @if ($numericResults->isNotEmpty())
        @include('admin-views.pdf-components.numeric-results-grouped', [
            'numericResults' => $numericResults,
            'patient' => $patient,
        ])
    @endif

    {{-- ✅ Loop only result section --}}
    @foreach ($nonNumericResults as $loopedTestResult)
        @php
            $test = optional($loopedTestResult->laboratoryRequestTest)->test;
            $attributes = collect($loopedTestResult->attributes)->sortBy(function ($item) {
                return $item->attribute->index ?? PHP_INT_MAX;
            });
        @endphp

        {{-- Results --}}
        @if ($test && $test->result_type === 'multi-type')
            @include('admin-views.pdf-components.multi-type-results', [
                'testResult' => $loopedTestResult,
            ])
        @elseif ($test && $test->result_type === 'text')
            @include('admin-views.pdf-components.text-results', [
                'patient' => $patient,
            ])
        @endif
    @endforeach

    {{-- Additional Notes for Each Test --}}
    @foreach ($testResults as $result)
        @php
            $test = optional($result->laboratoryRequestTest)->test;
            $notes = $test->additional_notes ?? null;
        @endphp

        @if ($notes)
            <div style="margin-top: 10px;">
                <strong>{{ $test->test_name ?? 'Test' }} Notes:</strong><br>
                {!! $notes !!}
            </div>
        @endif
    @endforeach


    {{-- Interpretation --}}
    @include('admin-views.pdf-components.result-interpretation', [
        'patient' => $patient,
        'test' => $test,
        'testResult' => $testResult,
        'specimens' => $specimens,
    ])

    @include('admin-views.pdf-components.footer')
</body>

</html>

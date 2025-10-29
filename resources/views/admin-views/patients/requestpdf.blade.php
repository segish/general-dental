<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Laboratory Request Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@100..900&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Noto Sans Ethiopic';
            src: url('{{ public_path('fonts/NotoSansEthiopic-VariableFont_wdth,wght.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Noto Sans Ethiopic', Arial, sans-serif;
            margin: 10px;
            line-height: 1.2;
            color: #000;
        }

        h1,
        h2,
        h3 {
            text-align: center;
            margin: 5px 0;
            color: #000;
        }

        .horizontal-line {
            border-top: 1px solid #000;
            margin: 10px 0;
        }

        .patient-info {
            margin: 20px 0;
        }

        .info-row {
            margin: 8px 0;
            width: 100%;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            /* width: 120px; */
            vertical-align: top;
        }

        .value {
            display: inline-block;
            border-bottom: 1px solid #000;
            /* padding: 0 10px;
            margin: 0 10px; */
            /* min-width: 150px; */
        }

        .checkbox-container {
            margin: 5px 0;
        }

        .checkbox {
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin-right: 5px;
            display: inline-block;
            vertical-align: middle;
        }

        .checked {
            background-color: #000;
        }

        .tests-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .tests-table th,
        .tests-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .tests-table th {
            background-color: #f0f0f0;
        }

        .signature-section {
            margin-top: 40px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')
    <div style="margin-top: 150px;">
        <div class="horizontal-line"></div>

        <h2>Laboratory Request Form</h2>
        @php
            use Carbon\Carbon;
            $age = $visit->patient->date_of_birth ? Carbon::parse($visit->patient->date_of_birth)->age : 'N/A';
        @endphp
        <div class="patient-info">
            <div class="info-row">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 40%;">
                            <span class="label">Patient Name:</span>
                            <span class="value">{{ $visit->patient->full_name }}</span>
                        </td>
                        <td style="width: 15%;">
                            <span class="label">Age:</span>
                            <span class="value">{{ $age ?? '' }}</span>
                        </td>
                        <td style="width: 15%;">
                            <span class="label">Sex:</span>
                            <span class="value">{{ $visit->patient->gender ?? '' }}</span>
                        </td>
                        <td style="width: 30%;">
                            <span class="label">Phone:</span>
                            <span class="value">{{ $visit->patient->phone }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="info-row">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 30%;">
                            <span class="label">Card No:</span>
                            <span class="value">{{ $visit->code }}</span>
                        </td>
                        <td style="width: 30%;">
                            <span class="label">Address:</span>
                            <span class="value" style="width: 60%;">{{ $visit->patient->address ?? '' }}</span>
                        </td>
                        <td style="width: 20%;">
                            <div class="checkbox {!! $visit->visit_type === 'IPD' ? 'checked' : '' !!}"></div>
                            <span>In-Patient</span>
                        </td>
                        <td style="width: 20%;">
                            <div class="checkbox {!! $visit->visit_type === 'OPD' ? 'checked' : '' !!}" style="margin-left: 20px;"></div>
                            <span>Out-Patient</span>
                        </td>
                    </tr>
                </table>
            </div>
            @if ($visit->diagnosis)
                <div class="info-row">
                    <span class="label">Clinical Diagnosis:</span>
                    <span class="value" style="flex: 2;">{{ $visit->diagnosis ?? '' }}</span>
                </div>
            @endif
        </div>

        <table class="tests-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 30%;">Test Name</th>
                    <th style="width: 10%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach ($visit->laboratoryRequest->tests as $laboratoryRequestTest)
                    @if (!$laboratoryRequestTest->test->is_active)
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $laboratoryRequestTest->test->test_name }}</td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line">
                        Requested By: {{ $visit->laboratoryRequest->collectedBy->full_name }}<br>
                        Qualification: {{ $visit->laboratoryRequest->collectedBy->department->name }}<br>
                        <p><span>Signature:</span>
                            @if ($visit->laboratoryRequest->collectedBy->signature)
                                <img src="{{ $visit->laboratoryRequest->collectedBy->signature_url }}"
                                    alt="Collector Signature"
                                    style="max-width: 120px; max-height: 30px; vertical-align: middle;">
                            @else
                                <span class="prescriber-dispenser-underline"></span>
                            @endif
                        </p>
                        Date: {{ $visit->created_at->format('M d, Y') }}
                    </div>
                </td>
                <td>
                    <div class="signature-line">
                        Received By: <br>
                        Date: <br>
                        Time:
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Note: Please bring this form when collecting your results</p>
        </div>
    </div>
    @include('admin-views.pdf-components.footer')
</body>

</html>

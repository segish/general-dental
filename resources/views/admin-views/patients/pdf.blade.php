<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Prescription PDF</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@100..900&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A5;
            margin: 0;
        }

        @font-face {
            font-family: 'Noto Sans Ethiopic';
            src: url('{{ public_path('fonts/NotoSansEthiopic-VariableFont_wdth,wght.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Noto Sans Ethiopic', Arial, sans-serif;
            margin: 10px;
            margin-top: 150px;
            line-height: 1.2;
            color: #0077b6;
            font-size: 12px;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 14px;
        }

        h3 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 15px;
            font-size: 12px;
        }

        h4 {
            text-align: center;
            font-size: 11px;
            margin: 5px 0;
        }

        .horizontal-line {
            border-top: 1px solid #0077b6;
            margin: 10px 0;
        }

        .name {
            display: inline-block;
            width: 300px;
            text-align: left;
            border-bottom: 1px groove #0077b6;
            padding-left: 10px;
            padding-bottom: 2px;
        }

        .checked {
            background-color: #0077b6;
        }

        .inline-details {
            margin-top: 10px;
        }

        .inline-field-new {
            display: inline-block;
            margin-right: 5px;
            font-size: 9px;
        }

        .underline-field-new {
            display: inline-block;
            flex: 1;
            width: 60px;
            text-align: left;
            border-bottom: 1px groove #0077b6;
            padding-left: 10px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
        }

        .underline-field-new-age {
            display: inline-block;
            flex: 1;
            width: 100px;
            text-align: left;
            border-bottom: 1px groove #0077b6;
            padding-left: 10px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
        }

        .underline-field-new-address {
            display: inline-block;
            width: 80px;
            border-bottom: 1px solid #0077b6;
        }

        .underline-field-new-phone {
            display: inline-block;
            width: 90px;
            padding-left: 5px;
            border-bottom: 1px solid #0077b6;
        }

        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 9px;
        }

        .prescription-table th,
        .prescription-table td {
            border: 1px solid #0077b6;
            padding: 4px;
            text-align: left;
            line-height: 1.1;
        }

        .prescriber-dispenser-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .prescriber-dispenser-table td {
            width: 50%;
            vertical-align: top;
            padding: 5px;
            border: 0px solid #ccc;
        }

        .prescriber-dispenser-underline {
            display: inline-block;
            flex: 1;
            width: 100px;
            text-align: left;
            border-bottom: 1px groove #0077b6;
            padding-left: 10px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
        }

        .custom-height td {
            height: 20px;
        }

        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #0077b6;
            margin-right: 3px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')

    <div class="horizontal-line"></div>

    <h3>Prescription Paper</h3>

    <div class="patient-info">
        <div class="line-container">
            <span class="field-label">Patient's Full Name:</span>
            <span class="name">{{ $visit->patient->full_name }}</span>
            <!-- Name is underlined and centered -->
        </div>

        @php
            use Carbon\Carbon;
            $age = $visit->patient->date_of_birth ? Carbon::parse($visit->patient->date_of_birth)->age : 'N/A';
        @endphp
        <!-- Sex, Age, Weight, and Card No. on one line with underlines -->
        <div class="inline-details">
            <span class="inline-field-new" style="width: fit-content;">Sex</span>
            <span class="underline-field-new"
                style="width: 20%; text-align: left;">{{ $visit->patient->gender ?? '' }}</span>
            <span class="inline-field-new" style="width: fit-content;">Age</span>
            <span class="underline-field-new-age"
                style="width: 20%; text-align: left;">{{ $visit->patient->age_detailed ?? '' }}</span>
            <span class="inline-field-new" style="width: fit-content;">Card No. </span>
            <span class="underline-field-new" style="width: 20%; text-align: left;">{{ $visit->code }}</span>
        </div>

        {{-- <div class="inline-details">
            <span class="inline-field-new">Address:- Town </span>
            <span class="underline-field-new-address"></span>
            <span class="inline-field-new">Woreda </span>
            <span class="underline-field-new-address"></span>
            <span class="inline-field-new">Kebele </span>
            <span class="underline-field-new-address"></span>
        </div> --}}

        <div class="inline-details">
            {{-- <span class="inline-field-new">House No </span> --}}
            {{-- <span class="underline-field-new-phone"></span> --}}
            <span class="inline-field-new">Tel No: </span>
            <span class="underline-field-new-phone">{{ $visit->patient->phone }}</span>
            <span class="inline-field-new checkbox {!! $visit->visit_type === 'IPD' ? 'checked' : '' !!}">
                {!! $visit->visit_type === 'IPD' ? '&#10003;' : '' !!}
            </span> In-Patient

            <span class="inline-field-new checkbox {!! $visit->visit_type === 'OPD' ? 'checked' : '' !!}">
                {!! $visit->visit_type === 'OPD' ? '&#10003;' : '' !!}
            </span> Out-Patient <br>
            <span class="field-label">Diagnosis (if not ICD): </span>
            <span class="name"></span> <!-- Name is underlined and centered -->

        </div>
        {{-- <p> <strong>{{ $visit->house_no }}, {{ $visit->tel_no }}</strong></p> --}}
        {{-- <p>Diagnosis (if not ICD): <strong>{{ $visit->diagnosis }}</strong></p> --}}
        {{-- <div class="inline-details">
        </div> --}}
    </div>

    <table class="prescription-table">
        <thead>
            <tr>
                <th style="width: 70%;">Drug Name Strength Dosage From Frequency<br>
                    Duration, Quantity; How to use & other information</th>
                <th style="width: 30%;">Price (dispensers<br> use only)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($visit->prescription as $prescription)
                @foreach ($prescription->details as $detail)
                    <tr>
                        <td>
                            {{ $detail->medicine->name }}
                            ({{ $detail->dosage ?? 'N/A' }},
                            {{ $detail->dose_duration }} Days,
                            {{ $detail->dose_time }},
                            {{ $detail->dose_interval }},
                            Qty: {{ $detail->quantity }})
                        </td>
                        <td></td>
                    </tr>
                @endforeach
            @endforeach

            <tr class="custom-height">
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: right; padding-right: 20px;">Total Price</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="prescriber-dispenser-table">
        <tr>
            <td style="width: 75%;">
                <div class="prescriber">
                    <h4>Prescriber's</h4>
                    <P><span>Full Name:</span><span
                            class="prescriber-dispenser-underline">{{ $visit->prescription->first()->doctor->full_name }}</span>
                    </P>
                    <P><span>Qualification:</span><span
                            class="prescriber-dispenser-underline">{{ $visit->prescription->first()->doctor->department->name }}</span>
                    </P>
                    {{-- <P><span>Registration:</span><span class="prescriber-dispenser-underline"></span></P> --}}
                    <P><span>Signature:</span>
                        @if ($visit->prescription->first()->doctor->signature)
                            <img src="{{ $visit->prescription->first()->doctor->signature_url }}"
                                alt="Doctor Signature"
                                style="max-width: 150px; max-height: 30px; vertical-align: middle;">
                        @else
                            <span class="prescriber-dispenser-underline"></span>
                        @endif
                    </P>
                    <P><span>Date:</span><span
                            class="prescriber-dispenser-underline">{{ $visit->created_at->format('M d, Y') }}</span>
                    </P>
                </div>
            </td>

            <td style="width: 25%;">
                <div class="dispenser">
                    <h4>Dispenser's</h4>
                    <P><span class="prescriber-dispenser-underline"></span>
                    </P>
                    <P><span class="prescriber-dispenser-underline"></span>
                    </P>
                    <P><span class="prescriber-dispenser-underline"></span></P>
                    {{-- <P><span class="prescriber-dispenser-underline"></span></P> --}}
                    <P><span class="prescriber-dispenser-underline"></span></P>
                </div>
            </td>
        </tr>
    </table>
    @include('admin-views.pdf-components.footer')

</body>

</html>

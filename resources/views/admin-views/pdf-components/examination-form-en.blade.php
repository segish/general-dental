<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Comprehensive Medical Certificate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@100..900&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Noto Sans Ethiopic';
            src: url('{{ public_path('fonts/NotoSansEthiopic-VariableFont_wdth,wght.ttf') }}') format('truetype');
        }

        @page {
            margin: 0;
        }

        body {
            font-family: 'Noto Sans Ethiopic', sans-serif;
            font-size: 12px;
            margin: 110px 50px 65px 50px;
            line-height: 1.3;
            color: #000;
        }

        h3 {
            text-align: center;
            font-weight: 700;
            font-size: 12px;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .section-title {
            font-weight: 600;
            margin-top: 5px;
            margin-bottom: 2px;
            font-size: 10px;
            text-decoration: underline;
        }

        .field-group {
            margin-bottom: 5px;
        }

        .label {
            font-weight: 600;
            display: inline-block;
            margin-right: 2px;
            line-height: 1;
        }

        .input-line {
            border-bottom: 1px solid #000;
            min-height: 12px;
            display: inline-block;
            width: 350px;
            margin-left: 2px;
        }

        .small-input-line {
            width: 60px !important;
        }

        .mid-input-line {
            width: 200px !important;
        }

        .multi-line-input {
            min-height: 12px;
            width: 100%;
            margin-left: 20px;
            display: block;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1;
            padding: 1px 0;
            text-decoration: underline;
        }

        .inline {
            display: inline-block;
            vertical-align: baseline;
        }

        .small-field {
            width: 25%;
        }

        .long-field {
            width: 75%;
        }

        .section {
            margin-top: 3px;
            margin-left: 50px;
        }

        .sub-sub {
            margin-left: 70px;
        }

        .footer-section {
            margin-top: 8px;
        }

        .signature-block {
            width: 100%;
            display: table;
            margin-top: 5px;
        }

        .signature-column {
            width: 58%;
            display: table-cell;
        }

        .signature-column2 {
            width: 38%;
            display: table-cell;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            min-height: 12px;
            margin-top: 2px;
            display: inline-block;
            width: 100px;
            margin-left: 2px;
            vertical-align: baseline;
        }

        .small-text {
            font-size: 8px;
            color: #333;
        }

        .double {
            width: 100%;
            display: table;
            margin-bottom: 5px;
        }

        .double .field {
            display: table-cell;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .right {
            text-align: right;
        }

        .multi-line-field {
            margin-bottom: 5px;
        }

        .multi-line-spacer {
            height: 12px;
            border-bottom: 1px solid #000;
            margin-bottom: 2px;
        }

        .field-row {
            display: block;
            margin-bottom: 3px;
        }

        .lab-table {
            width: 80%;
            border-collapse: collapse;
            margin: 3px 0;
            font-size: 8px;
            margin-left: 50px;

        }

        .lab-table th,
        .lab-table td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: left;
            vertical-align: top;
        }

        .lab-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .lab-table td {
            min-height: 10px;
        }

        .subsection {
            margin-left: 3px;
            margin-top: 1px;
        }

        .final-statement {
            background-color: #f8f9fa;
            padding: 3px;
            border: 1px solid #dee2e6;
            margin: 3px 0;
            border-radius: 1px;
        }

        .tight-lines {
            line-height: 0.8;
        }

        .label-line {
            line-height: 0.1;
            margin: 0;
            padding: 0;
        }

        .underline {
            display: inline;
            text-decoration: underline;
            text-decoration-thickness: 1px;
            text-underline-offset: 3px;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')

    <h3>COMPREHENSIVE MEDICAL CERTIFICATE</h3>

    <!-- Patient Information -->
    <div class="section" style="margin-left: 0px;">
        <div class="field-row">
            <span class="label">Name:</span>
            <span class="input-line mid-input-line">{{ $medicalDocument->visit->patient->full_name ?? '' }}</span>
            <span class="label" style="margin-left: 20px;">Address:</span>
            <span class="input-line small-input-line">{{ $medicalDocument->visit->patient->address ?? '' }}</span>
            <span class="label" style="margin-left: 20px;">Age:</span>
            <span class="small-input-line input-line">{{ $medicalDocument->visit->patient->age ?? '' }}</span>
            <span class="label" style="margin-left: 10px;">Sex:</span>
            <span class="small-input-line input-line">{{ $medicalDocument->visit->patient->gender ?? '' }}</span>
        </div>
    </div>

    <!-- Section I: THE APPLICANT SELF DECLARATION -->
    <div class="section-title">I. THE APPLICANT SELF DECLARATION</div>

    <div class="section">
        <div class="field-row">
            <span class="label">1. Disease in the past (if any):
                <span class="underline"
                    style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->past_diseases ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
            @if ($medicalDocument->past_diseases == '')
                <div class="multi-line-input"></div>
            @endif
        </div>

        <div class="field-row">
            <span class="label">2. If hospitalized indicate period, place and reason for
                hospitalization:<span class="underline"
                    style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->hospitalization_history ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
            @if ($medicalDocument->hospitalization_history == '')
                <div class="multi-line-input"></div>
            @endif
        </div>

        <div class="field-row">
            <span class="label">3. I certify that the above statements are complete & true:</span>
            <div class="input-line" style="width: 200px;"></div>
        </div>

        <div class="double">
            <div class="field">
                <span class="label">Signature:</span>
                <span class="input-line mid-input-line"></span>
            </div>
            <div class="field">
                <span class="label">Date:</span>
                <span class="small-input-line input-line"></span>
            </div>
        </div>
    </div>

    <!-- Section II: DOCTOR'S EXAMINATION -->
    <div class="section-title">II. DOCTOR'S EXAMINATION</div>

    <div class="section">
        <div class="field-row">
            <span class="label">1. General appearance:<span class="underline"
                    style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->general_appearance ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
            @if ($medicalDocument->general_appearance == '')
                <div class="multi-line-input"></div>
            @endif
            </span>
        </div>

        <div class="field-row">
            <span class="label">2. HEENT (Head, Eyes, Ears, Nose, Throat):</span>
        </div>
        <div class="subsection">
            <div class="double">
                <div class="field">
                    <span class="label sub-sub">a. Visual acuity OD:<span class="underline"
                            style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->visual_acuity_od ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
                    @if ($medicalDocument->visual_acuity_od == '')
                        <div class="small-input-line input-line"></div>
                    @endif
                </div>
                <div class="field">
                    <span class="label">OS:<span class="underline"
                            style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->visual_acuity_os ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
                    @if ($medicalDocument->visual_acuity_os == '')
                        <div class="small-input-line input-line"></div>
                    @endif
                </div>
            </div>
            <div class="field-row">
                <span class="label sub-sub">b. Ear (able to hear normal voice at 4 meters):<span class="underline"
                        style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->hearing_test ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
                @if ($medicalDocument->hearing_test == '')
                    <div class="input-line mid-input-line"></div>
                @endif
            </div>
        </div>

        <div class="field-row">
            <span class="label">3. Lung:<span class="underline"
                    style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->lung_examination ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
            @if ($medicalDocument->lung_examination == '')
                <div class="input-line mid-input-line"></div>
            @endif
            <span class="label" style="margin-left: 20px;">X-ray of lungs:<span class="underline"
                    style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->lung_xray ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
            @if ($medicalDocument->lung_xray == '')
                <div class="small-input-line input-line"></div>
            @endif
        </div>

        <div class="field-row">
            <span class="label">4. CVS (Cardiovascular System):</span>
        </div>
        <div class="subsection">
            <div class="field-row">
                <span class="label sub-sub">a. Heart condition:<span class="underline"
                        style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->heart_condition ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
                @if ($medicalDocument->heart_condition == '')
                    <div class="input-line"></div>
                @endif
            </div>
            <div class="double">
                <div class="field">
                    <span class="label sub-sub">b. BP:<span class="underline"
                            style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->blood_pressure ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
                    @if ($medicalDocument->blood_pressure == '')
                        <div class="small-input-line input-line"></div>
                    @endif
                </div>


                <div class="field">
                    <span class="label">c. Pulse:<span class="underline"
                            style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->pulse ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
                    @if ($medicalDocument->pulse == '')
                        <div class="small-input-line input-line"></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="field-row">
            <span class="label">5. Abdomen:<span class="underline"
                    style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->abdomen_examination ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
            @if ($medicalDocument->abdomen_examination == '')
                <div class="input-line"></div>
            @endif
        </div>

        <div class="field-row">
            <span class="label">6. GUT (Gastrointestinal/Urinary Tract):<span class="underline"
                    style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->gut_examination ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
            @if ($medicalDocument->gut_examination == '')
                <div class="input-line"></div>
            @endif
        </div>

        <div class="field-row">
            <span class="label">7. Musculoskeletal system:<span class="underline"
                    style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->musculoskeletal_examination ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
            @if ($medicalDocument->musculoskeletal_examination == '')
                <div class="input-line"></div>
            @endif
        </div>

        <div class="field-row">
            <span class="label">8. Neurological examination:</span>
        </div>
        <div class="subsection">
            <div class="field-row">
                <span class="label sub-sub">a. Mental status:<span class="underline"
                        style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->mental_status ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
                @if ($medicalDocument->mental_status == '')
                    <div class="input-line"></div>
                @endif
            </div>
            <div class="field-row">
                <span class="label sub-sub">b. Symptom of disturbance of Nervous system:<span class="underline"
                        style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->nervous_system_symptoms ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
                @if ($medicalDocument->nervous_system_symptoms == '')
                    <div class="input-line"></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Section III: LABORATORY EXAMINATION -->
    <div class="section-title">III. LABORATORY EXAMINATION</div>

    <table class="lab-table">
        <thead>
            <tr>
                <th style="width: 25%;">BLOOD</th>
                <th style="width: 25%;">RESULT</th>
                <th style="width: 25%;">BLOOD</th>
                <th style="width: 25%;">RESULT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1. HIV</td>
                <td>{{ $medicalDocument->hiv_result ?? '' }}</td>
                <td>5. HCV</td>
                <td>{{ $medicalDocument->hcv_result ?? '' }}</td>
            </tr>
            <tr>
                <td>2. Syphilis</td>
                <td>{{ $medicalDocument->syphilis_result ?? '' }}</td>
                <td>6. ESR</td>
                <td>{{ $medicalDocument->esr_result ?? '' }}</td>
            </tr>
            <tr>
                <td>3. HBsAg</td>
                <td>{{ $medicalDocument->hbsag_result ?? '' }}</td>
                <td>7. Blood group</td>
                <td>{{ $medicalDocument->blood_group ?? '' }}</td>
            </tr>
            <tr>
                <td>4. WBC</td>
                <td>{{ $medicalDocument->wbc_result ?? '' }}</td>
                <td>8. Pregnancy Test</td>
                <td>{{ $medicalDocument->pregnancy_test ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Section IV: FINAL STATEMENT OF THE DOCTOR -->
    <div class="section-title">IV. FINAL STATEMENT OF THE DOCTOR</div>

    <div class="final-statement section">
        <div class="field-row">
            <span class="label">Ato/w/t:</span>
            <span class="input-line mid-input-line">{{ $medicalDocument->visit->patient->full_name ?? '' }}</span>
            <span style="margin: 0 5px;">has been medically examined in accordance with the above outlined and he/she
                is medically <span class="underline"
                    style="font-weight: normal; margin-left: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->final_medical_status ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
            @if ($medicalDocument->final_medical_status == '')
                <div class="input-line"></div>
            @endif
        </div>
    </div>

    <!-- Doctor's Signature Section -->
    <div class="footer-section">
        <div class="field-row">
            <span class="label">Doctor's Name:</span>
            <span class="input-line mid-input-line">{{ $medicalDocument->doctor->f_name ?? '' }}
                {{ $medicalDocument->doctor->l_name ?? '' }}</span>
            <span class="label" style="margin-left: 20px;">Date:</span>
            <span
                class="underline">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->date ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="label" style="margin-left: 20px;">Signature:</span>
            @if ($medicalDocument->doctor->signature)
                <img src="{{ $medicalDocument->doctor->signature_url }}" alt="Doctor Signature"
                    style="max-width: 150px; max-height: 30px; vertical-align: middle;">
            @else
                <span class="signature-line"></span>
            @endif
        </div>
    </div>

    @include('admin-views.pdf-components.footer')
</body>

</html>

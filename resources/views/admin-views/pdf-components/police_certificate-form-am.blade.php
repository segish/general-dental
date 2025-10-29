<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Medical Certificate for Police Office</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@400;600&display=swap" rel="stylesheet">

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
            margin: 100px 40px 40px 40px;
            line-height: 1.4;
            color: #000;
        }

        h3 {
            text-align: center;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 25px;
            text-decoration: underline;
        }

        .field-group {
            margin-bottom: 20px;
        }

        .label {
            font-weight: 600;
            display: inline-block;
            margin-right: 5px;
            line-height: 1;
        }


        .input-line {
            border-bottom: 1px solid #000;
            min-height: 18px;
            display: inline-block;
            width: 400px;
            margin-left: 5px;
        }

        .small-input-line {
            width: 100px !important;
        }

        .mid-input-line {
            width: 200px !important;
        }

        .multi-line-input {
            min-height: 18px;
            width: 100%;
            margin-left: 5px;
            display: block;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1;
            padding: 2px 0;
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
            margin-top: 10px;
        }

        .footer-section {
            margin-top: 30px;
        }

        .signature-block {
            width: 100%;
            display: table;
            margin-top: 20px;
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
            min-height: 18px;
            margin-top: 5px;
            display: inline-block;
            width: 150px;
            margin-left: 5px;
            vertical-align: baseline;
        }

        .small-text {
            font-size: 11px;
            color: #333;
        }

        .double {
            width: 100%;
            display: table;
            margin-bottom: 20px;
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
            margin-bottom: 20px;
        }

        .multi-line-spacer {
            height: 18px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }

        .field-row {
            display: block;
            margin-bottom: 15px;
        }

        /* Specific styles to reduce space between Amharic and English */
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

    <h3>የክሊኒክ ምርመራ ወረቀት ለፖሊስ ቢሮ<br><span style="font-size:15px; line-height: 0.8;">Medical Certificate for Police
            Office</span></h3>

    <!-- Name, Age, Sex -->
    <div class="section">
        <div class="double">
            <div class="field">
                <span class="label" style="line-height: 1.8;">ስም: <br><span
                        style="line-height: 0.5;">Name:</span></span>
                <span class="input-line mid-input-line">{{ $medicalDocument->visit->patient->full_name ?? '' }}</span>
            </div>
            <div class="field">
                <span class="label" style="line-height: 1.8;">ዕድሜ: <br><span
                        style="line-height: 0.5;">Age:</span></span>
                <span class="small-input-line input-line">{{ $medicalDocument->visit->patient->age ?? '' }}</span>
            </div>
            <div class="field">
                <span class="label" style="line-height: 1.8;">ፆታ: <br><span
                        style="line-height: 0.5;">Sex:</span></span>
                <span class="small-input-line input-line">{{ $medicalDocument->visit->patient->gender ?? '' }}</span>
            </div>
        </div>
    </div>

    <!-- Police request info -->
    <div class="section">
        <div class="double">
            <div class="field">
                <span class="label" style="line-height: 1.8;">ለፖሊስ ጣቢያ የተጠየቅንበት ደብዳቤ ቁጥር: <br><span
                        style="line-height: 0.5;">The registration number of letter of police office and
                        date:</span></span>
                <span class="small-input-line input-line">{{ $medicalDocument->letter_number ?? '' }}</span>
            </div>
            <div class="field">
                <span class="label" style="line-height: 1.8;">ቀን: <br><span
                        style="line-height: 0.5;">Date:</span></span>
                <span class="small-input-line input-line">{{ $medicalDocument->date ?? '' }}</span>
            </div>
        </div>
    </div>

    <!-- Issued Idea -->
    <div class="field-row">
        <span class="label" style="line-height: 1.8;">የተጠየቅነዉ ጉዳይ: <br><span style="line-height: 0.5;">Issued
                idea:</span></span>
        @if ($medicalDocument->issued_idea == '')
            <div class="input-line"></div>
        @else
            <span
                class="underline">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->issued_idea ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
        @endif
    </div>

    <!-- Date of Examination -->
    <div class="field-row">
        <span class="label" style="line-height: 1.8;">የተመረመረበት/ችበት ቀን: <br><span style="line-height: 0.5;">Date of
                Examination:</span></span>
        <span
            class="underline">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->examination_date ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </div>

    <!-- History of victim -->
    <div class="multi-line-field">
        <div class="field-row">
            <span class="label" style="line-height: 1.8;">የተጎዳው ተገልጋይ የተናገረው የጉዳት ታሪክ: <br><span
                    style="line-height: 0.5;">The history of the victim Client:</span></span>
            @if ($medicalDocument->victim_history == '')
                <div class="input-line"></div>
            @else
                <span
                    class="underline">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->victim_history ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
            @endif
        </div>
    </div>

    <!-- Finding -->
    <div class="multi-line-field">
        <div class="field-row">
            <span class="label" style="line-height: 1.8;">መርማሪዉ የደረሰበት የተገልጋይ ጉዳት: <br><span
                    style="line-height: 0.5;">Finding of the injury by examining of the victim client:</span></span>
            @if ($medicalDocument->injury_finding == '')
                <div class="multi-line-spacer"></div>
            @else
                <span
                    class="underline">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->injury_finding ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
            @endif
        </div>
    </div>

    <!-- Recommendation -->
    <div class="multi-line-field">
        <div class="field-row">
            <span class="label" style="line-height: 1.8;">የሃኪሙ/ ያየው የጤና ባለሙያ/ አስተያት ህክምና ካገኘ በኋላ: <br><span
                    style="line-height: 0.5;">The recommendation of the Dr's or Health Professional Person after the end:</span></span>
            @if ($medicalDocument->doctor_recommendation == '')
                <div class="multi-line-spacer"></div>
            @else
                <span
                    class="underline">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->doctor_recommendation ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
            @endif
        </div>
    </div>

    <!-- Doctor info -->
    <div class="footer-section">
        <div class="field-row">
            <span class="label" style="line-height: 1.8;">የመረመረዉ ሀኪም /ጤና ባለሙያ/: <br><span
                    style="line-height: 0.5;">Seen by Dr./Health professional person:</span></span>
        </div>

        <div class="signature-block">
            <div class="signature-column">
                <div class="field-row">
                    <span class="label" style="line-height: 1.8;">ስም: <br><span
                            style="line-height: 0.5;">Name:</span></span>
                    <span
                        class="underline">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->doctor ? $medicalDocument->doctor->f_name . ' ' . $medicalDocument->doctor->l_name : '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </div>
                <div class="field-row">
                    <span class="label" style="line-height: 1.8;">የሙያ ስያሜ: <br><span
                            style="line-height: 0.5;">Qualification:</span></span>
                    <span class="underline">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->doctor->department->name ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </div>
            </div>

            <div class="signature-column2">
                <div class="field-row">
                    <span class="label" style="line-height: 1.8;">ፊርማ: <br><span
                            style="line-height: 0.5;">Signature:</span></span>
                    @if ($medicalDocument->doctor->signature)
                        <img class="underline" src="{{ $medicalDocument->doctor->signature_url }}" alt="Doctor Signature"
                            style="max-width: 150px; max-height: 30px; vertical-align: middle;">
                    @else
                        <span class="signature-line"></span>
                    @endif
                </div>
                <div class="field-row">
                    <span class="label" style="line-height: 1.8;">ቀን: <br><span
                            style="line-height: 0.5;">Date:</span></span>
                    <span class="underline">&nbsp;&nbsp;&nbsp;&nbsp;{{ $medicalDocument->date ?? '' }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </div>
            </div>
        </div>
    </div>

    @include('admin-views.pdf-components.footer')
</body>

</html>

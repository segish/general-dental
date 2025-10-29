<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> Prescription PDF</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@100..900 &display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Noto Sans Ethiopic';
            src: url('{{ public_path('fonts/NotoSansEthiopic-VariableFont_wdth,wght.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Noto Sans Ethiopic', Arial, sans-serif;
            line-height: 0.1;
            color: #000;
        }

        h3 {
            text-align: center;
            margin-top: 0px;
        }

        .horizontal-line {
            border-top: 1px solid #000;
        }

        .name {
            display: inline-block;
            width: 300px;
            text-align: left;
            border-bottom: 1px groove #000;
            padding-left: 20px;
            padding-bottom: 2px;
        }

        .inline-details {
            margin-top: 20px;
            margin-left: 270px;
        }

        .inline-details-straight {
            margin-top: 20px;
        }

        .inline-field-new {
            display: inline-block;
            margin-right: 10px;
            font-size: 12px;
        }

        .underline-field-new {
            display: inline-block;
            flex: 1;
            width: 90px;
            text-align: left;
            border-bottom: 1px groove #000;
            padding-left: 20px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
        }

        .underline-field-new-address {
            display: inline-block;
            flex: 1;
            width: 130px;
            padding-left: 20px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
            border-bottom: 1px solid #000;
        }

        .underline-field-new-phone {
            display: inline-block;
            width: 140px;
            padding-left: 10px;
            border-bottom: 2px solid #000;
        }

        .top-margin {
            margin-top: 40px;
        }

        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
        }

        .ft-14px {
            font-size: 14px;
            color: #444
        }

        .prescription-table th,
        .prescription-table td {
            border-bottom: 1px solid #000;
            border-top: none;
            border-left: none;
            border-right: none;
            padding: 0;
            margin: 0;
            text-align: left;
            line-height: 1.0;
            vertical-align: top;
            /* Align text to the top of the cell */
        }
    </style>
</head>

<body>
    <div class="horizontal-line"></div>
    <div class="inline-details">
        <span class="inline-field-new">Card No </span>
        <span class="underline-field-new">{{ $medicalCertification->visit->code }}</span>
    </div>
    <div class="inline-details">
        <span class="inline-field-new">ቀን/Date </span>
        <span class="underline-field-new">{{ $medicalCertification->date }}</span>
    </div>
    <div style="text-decoration: underline; margin-top: 25px;">
        <h3>የህክምና የምስክር ወረቀት</h3>
        <h3>Medical Certification</h3>
    </div>
    <div class="top-margin">
        <span class="ft-14px">የህመምተኛ ስም</span>
        <span class="name ft-14px">{{ $medicalCertification->visit->patient->full_name }}</span>
        <!-- Name is underlined and centered -->
    </div>
    <div style="margin-top: 10px;">
        <span class="ft-14px">Patient Name</span>
    </div>
    <div class="inline-details-straight ft-14px" style="margin-top: 20px;">
        <span class="inline-field-new">ዕድሜ</span>
        <span class="underline-field-new">{{ $medicalCertification->patient->age ?? '' }}</span>
        <span class="inline-field-new">ፆታ</span>
        <span class="underline-field-new">{{ $medicalCertification->patient->gender ?? '' }}</span>
    </div>

    <div class="ft-14px" style="margin-bottom:20px; margin-top: 10px;">
        <span style="margin-right: 120px;">Age</span>
        <span>Sex</span>
    </div>

    <table class="prescription-table ft-14px">
        <tbody>
            <tr>
                <td style="border: none; margin: 0; padding: 0; font-size:10px;">የምርመራ ውጤት</td>
            </tr>
            <tr>
                <td style="border: none; margin: 0; padding: 0;">Diagnosis&nbsp;&nbsp;&nbsp;&nbsp;<span
                        style="text-decoration: underline;">{{ $medicalCertification->diagnosis }}</span></td>
            </tr>
        </tbody>
    </table>

    <div class="top-margin">
        <span class="inline-field-new">ክ</span>
        <span class="underline-field-new-address">{{ $medicalCertification->treated_from ?? '' }}</span>
        <span class="inline-field-new">እስከ</span>
        <span class="underline-field-new-address">{{ $medicalCertification->treated_to ?? '' }}</span>
        <span class="inline-field-new">ታክሟል፡፡</span>
    </div>
    <div class="ft-14px" style="margin-top: 10px;">
        <span style="margin-right: 90px;">Treated From</span>
        <span>To</span>
    </div>

    <div class="top-margin ft-14px">
        <span>የሐኪም እረፍት</span>
        <span class="name">{{ $medicalCertification->rest_required }}</span>
        <!-- Name is underlined and centered -->
    </div>
    <div class="ft-14px" style="margin-top: 10px;">
        <span>Rest Required</span>
    </div>

    <table class="prescription-table ft-14px" style="margin-top: 40px;">
        <tbody>
            <tr>
                <td style="border: none;">አስተያየት</td>
            </tr>
            <tr>
                <td style="border: none;">Remark&nbsp;&nbsp;&nbsp;&nbsp;<span style="text-decoration: underline;">{{ $medicalCertification->remark }}</span></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-left: 200px; margin-top: 50px;">
        <div class="ft-14px">
            <span>የሐኪም ፊርማ</span>
        </div>
        <div class="ft-14px" style="margin-top: 10px;">
            <span class="inline-field-new">Doctor's Signature </span>
            <span class="underline-field-new"></span>
        </div>
    </div>
</body>

</html>

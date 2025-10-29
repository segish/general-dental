<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Referral Form</title>
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
            font-family: 'Noto Sans Ethiopic', Arial, sans-serif;
            font-size: 14px;
            margin-top: 150px;
            margin-left: 40px;
            margin-right: 40px;
            margin-bottom: 50px;
        }

        h3 {
            text-align: center;
            margin-top: 0px;
        }

        .line-divider {
            border-top: 1px solid #000;
        }

        .field-name-underlined {
            display: inline-block;
            width: 300px;
            text-align: left;
            border-bottom: 1px groove #000;
            padding-left: 20px;
            padding-bottom: 2px;
        }

        .group-right-offset {
            margin-left: 250px;
        }

        .group-no-offset {
            margin-top: 20px;
        }

        .label-inline-small {
            display: inline-block;
            margin-right: 10px;
            font-size: 12px;
        }

        .field-inline-underlined {
            display: inline-block;
            flex: 1;
            width: 100px;
            text-align: left;
            border-bottom: 1px groove #000;
            padding-left: 20px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
        }

        .field-address-underlined {
            display: inline-block;
            flex: 1;
            width: 130px;
            padding-left: 20px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
            border-bottom: 1px solid #000;
        }

        .field-phone-underlined {
            display: inline-block;
            width: 140px;
            padding-left: 10px;
            border-bottom: 2px solid #000;
        }

        .section-top-margin {
            margin-top: 2px;
        }

        .table-borderless {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
        }

        . {
            font-size: 14px;
            color: #444
        }

        .table-borderless th,
        .table-borderless td {
            border-bottom: 1px solid #000;
            border-top: none;
            border-left: none;
            border-right: none;
            padding: 0;
            margin: 0;
            text-align: left;
            line-height: 1.0;
            vertical-align: top;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')

    <table style="float: right; border-collapse: collapse; font-size: 14px; margin: 0; padding: 0;">
        <tr style="border: none; margin: 0; padding: 0; line-height: 60%;">
            <td style="margin: 0; padding: 0;">ካርድ ቁ.<br>Card No</td>
            <td style="margin: 0; padding: 0; border-bottom: 1px solid #000;">
                {{ $medicalDocument->visit->code }}
            </td>
        </tr>
        <tr style="border: none; margin: 0; padding: 0; line-height: 60%;">
            <td style="margin: 0; padding: 0;">ቀን<br>Date</td>
            <td style="margin: 0; padding: 0; border-bottom: 1px solid #000;">
                {{ $medicalDocument->date }}
            </td>
        </tr>
    </table>

    <div style="margin-top: 25px;">
        <h3 style="margin-bottom: 0; text-decoration: underline;">PATIENT REFERRAL SLIP</h3>
    </div>

    <!-- Referral To -->
    <div style="margin-top: 25px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 30px; vertical-align: bottom; padding: 0;">
                    To
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->to_hospital }}
                </td>
                <td style="width: 100px; vertical-align: bottom; padding-left: 10px;">
                    Hospital
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->to_department }}
                </td>
                <td style="width: 100px; vertical-align: bottom; padding-left: 10px;">
                    Department
                </td>
            </tr>
        </table>
    </div>

    <!-- Referral From -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 50px; vertical-align: bottom; padding: 0;">
                    From
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->from_hospital }}
                </td>
                <td style="width: 120px; vertical-align: bottom; padding-left: 10px;">
                    Hospital/H.center
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->from_department }}
                </td>
                <td style="width: 100px; vertical-align: bottom; padding-left: 10px;">
                    Department
                </td>
            </tr>
        </table>
    </div>

    <!-- Time -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 50px; vertical-align: bottom; padding: 0;">
                    Time
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->date }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Patient Name -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 120px; vertical-align: bottom; padding: 0;">
                    Name of Patient
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->patient->full_name ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Age, Sex, Occupation -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 40px; vertical-align: bottom; padding: 0;">Age</td>
                <td style="width: 60px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->patient->age ?? '&nbsp;' }}
                </td>
                <td style="width: 40px; vertical-align: bottom; padding-left: 10px;">Sex</td>
                <td style="width: 60px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->patient->gender ?? '&nbsp;' }}
                </td>
                <td style="width: 80px; vertical-align: bottom; padding-left: 10px;">Occupation</td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->house_no ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Address -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 70px; vertical-align: bottom; padding: 0;">Address:</td>
                <td style="width: 70px; vertical-align: bottom; padding-left: 5px;">Woreda</td>
                <td style="width: 60px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->Woreda ?? '' }}
                </td>
                <td style="width: 60px; vertical-align: bottom; padding-left: 10px;">Kebele</td>
                <td style="width: 60px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->Kebele ?? '' }}
                </td>
                <td style="width: 70px; vertical-align: bottom; padding-left: 10px;">House No</td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->house_no }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Clinical finding -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 120px; vertical-align: bottom; padding: 0;">
                    Clinical finding
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->clinical_findings }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Investigation results -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 150px; vertical-align: bottom; padding: 0;">
                    Investigation results
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->diagnosisTreatment->diagnosis ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- diagnosis -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 80px; vertical-align: bottom; padding: 0;">
                    diagnosis
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->dignosis ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Rx given -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 70px; vertical-align: bottom; padding: 0;">
                    Rx given
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->rx_given }}
                </td>
            </tr>
        </table>
    </div>

    <!-- reasons for referral -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 120px; vertical-align: bottom; padding: 0;">
                    reasons for referral
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->reason }}
                </td>
            </tr>
        </table>
    </div>

    <!-- referred by -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 90px; vertical-align: bottom; padding: 0;">
                    referred by
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->doctor->full_name ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- signature -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 90px; vertical-align: bottom; padding: 0;">
                    signature
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    &nbsp;
                </td>
            </tr>
        </table>
    </div>

    <!-- Second Page - Feedback Slip -->
    <div style="margin-top: 50px;">
        <table style="float: right; border-collapse: collapse; font-size: 14px; margin: 0; padding: 0;">
            <tr style="border: none; margin: 0; padding: 0; line-height: 60%;">
                <td style="margin: 0; padding: 0;">ካርድ ቁ.<br>Card No</td>
                <td style="margin: 0; padding: 0; border-bottom: 1px solid #000;">
                    &nbsp;
                </td>
            </tr>
            <tr style="border: none; margin: 0; padding: 0; line-height: 60%;">
                <td style="margin: 0; padding: 0;">ቀን<br>Date</td>
                <td style="margin: 0; padding: 0; border-bottom: 1px solid #000;">
                    &nbsp;
                </td>
            </tr>
        </table>

        <div style="margin-top: 25px;">
            <h3 style="margin-bottom: 0; text-decoration: underline;">PATIENT REFERRAL FEEDBACK SLIP</h3>
        </div>

        <!-- To Hospital -->
        <div style="margin-top: 25px; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                <tr style="line-height: 60%;">
                    <td style="width: 30px; vertical-align: bottom; padding: 0;">
                        to
                    </td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                    <td style="width: 120px; vertical-align: bottom; padding-left: 10px;">
                        Hospital/H.center
                    </td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                    <td style="width: 100px; vertical-align: bottom; padding-left: 10px;">
                        Department
                    </td>
                </tr>
            </table>
        </div>

        <!-- Patient Name -->
        <div style="margin-top: 10px; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                <tr style="line-height: 60%;">
                    <td style="width: 120px; vertical-align: bottom; padding: 0;">
                        Name of patient
                    </td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>

        <!-- Age, Sex, Occupation -->
        <div style="margin-top: 10px; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                <tr style="line-height: 60%;">
                    <td style="width: 40px; vertical-align: bottom; padding: 0;">Age</td>
                    <td
                        style="width: 60px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                    <td style="width: 40px; vertical-align: bottom; padding-left: 10px;">Sex</td>
                    <td
                        style="width: 60px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                    <td style="width: 80px; vertical-align: bottom; padding-left: 10px;">Occupation</td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>

        <!-- Address -->
        <div style="margin-top: 10px; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                <tr style="line-height: 60%;">
                    <td style="width: 70px; vertical-align: bottom; padding: 0;">Address:</td>
                    <td style="width: 70px; vertical-align: bottom; padding-left: 5px;">Woreda</td>
                    <td
                        style="width: 60px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                    <td style="width: 60px; vertical-align: bottom; padding-left: 10px;">Kebele</td>
                    <td
                        style="width: 60px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                    <td style="width: 70px; vertical-align: bottom; padding-left: 10px;">House No</td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>

        <!-- Buildings -->
        <div style="margin-top: 10px; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                <tr style="line-height: 60%;">
                    <td style="width: 80px; vertical-align: bottom; padding: 0;">
                        Buildings
                    </td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>

        <!-- Treatment given -->
        <div style="margin-top: 10px; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                <tr style="line-height: 60%;">
                    <td style="width: 120px; vertical-align: bottom; padding: 0;">
                        Treatment given
                    </td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>

        <!-- recommendation -->
        <div style="margin-top: 10px; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                <tr style="line-height: 60%;">
                    <td style="width: 120px; vertical-align: bottom; padding: 0;">
                        recommendation
                    </td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>

        <!-- Consulted Physician -->
        <div style="margin-top: 10px; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                <tr style="line-height: 60%;">
                    <td style="width: 150px; vertical-align: bottom; padding: 0;">
                        Consulted Physician
                    </td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>

        <!-- signature -->
        <div style="margin-top: 10px; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                <tr style="line-height: 60%;">
                    <td style="width: 90px; vertical-align: bottom; padding: 0;">
                        signature
                    </td>
                    <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>
    </div>

    @include('admin-views.pdf-components.footer')
</body>

</html>

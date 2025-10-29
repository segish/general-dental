<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Medical Certificate</title>
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
            <td style="margin: 0; padding: 0; border-bottom: 1px solid #000;">{{ $medicalDocument->visit->code }}

            </td>
        </tr>
        <tr style="border: none; margin: 0; padding: 0; line-height: 60%;">
            <td style="margin: 0; padding: 0;">ቀን<br>Date</td>
            <td style="margin: 0; padding: 0; border-bottom: 1px solid #000;">{{ $medicalDocument->date }}</td>
        </tr>
    </table>


    <div style="margin-top: 25px;">
        <h3 style="margin-bottom: 0">የህክምና የምስክር ወረቀት</h3>
        <h3 style="text-decoration: underline;">MEDICAL CERTIFICATE</h3>
    </div>

    <!-- Full Name -->
    <div style="margin-top: 25px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 50px; vertical-align: bottom; padding: 0;">
                    ስም<br>Name
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->patient->full_name ?? '&nbsp;' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Age and Sex -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 50px; vertical-align: bottom; padding: 0;">ዕድሜ<br>Age</td>
                <td style="width: 150px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->patient->age ?? '&nbsp;' }}
                </td>
                <td style="width: 50px; vertical-align: bottom; padding-left: 20px;">ፆታ<br>Sex</td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->patient->gender ?? '&nbsp;' }}
                </td>
            </tr>
        </table>
    </div>


    <!-- Address -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%">
                <td style="width: 60px; vertical-align: bottom; padding: 0;">አድራሻ <br>Address</td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->patient->address ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Date of Examination -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 150px; vertical-align: bottom; padding: 0;">
                    የተመረመረበት ቀን<br>Date of Examination
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->visit_datetime ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Diagnosis -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 100px; vertical-align: bottom; padding: 0;">
                    የምርመራ ውጤት<br>Diagnosis
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->visit->diagnosisTreatment->diagnosis ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Doctor's Recommendation -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 200px; vertical-align: bottom; padding: 0;">
                    የሐኪሙ ትዕዛዝ<br>Doctor's Recommendation
                </td>
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->diagnosis ?? '' }}
                </td>
            </tr>
            <tr style="line-height: 60%;">
                <td></td>
                <td style="width: 100%; padding-left: 5px; border-bottom: 1px solid black;">&nbsp;</td>
            </tr>
            <tr style="line-height: 60%;">
                <td style="width: 100%; padding-left: 5px; border-bottom: 1px solid black;">&nbsp;</td>
            </tr>
        </table>
    </div>



    <!-- Doctor's Recommended -->
    <div style="margin-top: 10px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="width: 170px; vertical-align: bottom; padding: 0;">
                    የተፈቀደላቸው እረፍት<br>Doctors Recommended
                </td>
                <td style="width: 270px; vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                    {{ $medicalDocument->date_of_rest ?? '' }}
                </td>
                <td style="width: 100px; vertical-align: bottom; padding: 0;">
                    ቀኖች<br>Days
                </td>
            </tr>
        </table>
    </div>

    <!-- Doctor's Signature -->
    <div style="margin-top: 50px; width: 100%;">
        <table style="width: 60%; border-collapse: collapse; margin: 0 auto; padding: 0;">
            <tr style="line-height: 60%;">
                <td style="vertical-align: bottom; padding-left: 5px; border-bottom: 1px solid black;">
                </td>
            </tr>
            <tr style="line-height: 200%;">
                <td style="width: 150px; vertical-align: bottom; padding: 0; text-align: center;">
                    የሐኪሙ ፊርማና ማህተም
                </td>
            </tr>
        </table>
    </div>

    @include('admin-views.pdf-components.footer')
</body>

</html>

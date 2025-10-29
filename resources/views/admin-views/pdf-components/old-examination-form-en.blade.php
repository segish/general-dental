<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Medical Examination</title>
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
            <td style="margin: 0; padding: 0;">ቀን/Date</td>
            <td style="margin: 0; padding: 0; border-bottom: 1px solid #000;">
                {{ now()->format('Y-m-d H:i') }}
            </td>
        </tr>
        <tr style="border: none; margin: 0; padding: 0; line-height: 60%;">
            <td style="margin: 0; padding: 0;">ቁጥር/Ref.No</td>
            <td style="margin: 0; padding: 0; border-bottom: 1px solid #000;"></td>
        </tr>
    </table>

    <p style="margin-top: 80px;">
        ለ <span style="border-bottom: 1px solid black; display: inline-block; width: 200px; text-align: center; padding-bottom: 2px;">
            {{ $medicalDocument->to }}
        </span>
    </p>


    <p style="margin-top: 50px; text-align: center;">
        ጉዳዩ፦ <span style="text-decoration: underline;">የሜዲካል ምርመራ ውጤትን ይመለኪታ።</span>
    </p>

    <p>
        ከላይ በርዕሱ ለመግልፅ እንደሞከረው መስሪያ ቤታችሁ በቀን <span
            style="border-bottom: 1px solid black; display: inline-block; width: 200px; text-align: center; padding-bottom: 2px;">
            {{ $medicalDocument->date }} </span> ዓ.ም. በኮትር ዓ.ም. በተጻፈ ደብዳቤ ወ/ሮ አቶ/ወ/ሪት <span
            style="border-bottom: 1px solid black; display: inline-block; width: 200px; text-align: center; padding-bottom: 2px;">
            {{ $medicalDocument->visit->patient->full_name }}
        </span>
        በቀን <span style="border-bottom: 1px solid black; display: inline-block; width: 200px; text-align: center; padding-bottom: 2px;">
            {{ $medicalDocument->visit->visit_datetime }}
        </span> ዓ.ም. በክሊኒካችን በመምጣት የሜዲካል ምርመራ
        ያደረገ ስለሆነ የተመሩበትን ውጤት 1ገጽ ከዚህ መሸኛ ጋር አባሪ በማድረግ የላክን መሆኑን እንገልጻለን።
    </p>

    <p style="margin-top: 50px; text-align: right;">
        ከሰላምታ ጋር
    </p>

    @include('admin-views.pdf-components.footer')
</body>

</html>

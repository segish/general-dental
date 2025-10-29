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
            margin-top: 2px;
            margin-bottom: 30px;
        }

        p {
            display: flex;
            justify-content: space-between;
        }

        .horizontal-line {
            border-top: 2px solid #000;
            margin: 20px 0;
        }

        .contact-info {
            font-weight: bold;
            text-align: center;
            margin: 1px 0;
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
            border-bottom: 1px solid #000;
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
        <span class="inline-field-new" style="margin-left: 30rem;">Ref.No </span>
        <span class="underline-field-new">{{ $referralSlip->visit->code }}</span>
    </div>
    <div class="inline-details">
        <span class="inline-field-new" style="margin-left: 30rem;">Date </span>
        <span class="underline-field-new">{{ $referralSlip->date }}</span>
    </div>

    <div style="text-decoration: underline; margin-top: 25px;">
        <h3>PATIENT REFERRAL SLIP</h3>
    </div>


    <div class="inline-details">
        <span class="inline-field-new">To </span>
        <span class="underline-field-new-phone" style="width: 30rem;">{{ $referralSlip->to_department }}</span>
        <span class="inline-field-new">Department </span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">From </span>
        <span class="underline-field-new-phone" style="width: 11rem">{{ $referralSlip->from_department}}</span>
        <span class="inline-field-new">Higher Dental Clinic </span>
        <span class="underline-field-new-phone" style="width: 11rem">{{ $referralSlip->from_department }}</span>
        <span class="inline-field-new">Department </span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Time </span>
        <span class="underline-field-new-phone" style="width: 30rem">{{ $referralSlip->time }}</span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Name of Patient </span>
        <span class="underline-field-new-phone"
            style="width: 30rem">{{ $referralSlip->visit->patient->full_name }}</span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Age </span>
        <span class="underline-field-new-phone">{{ $referralSlip->patient->age ?? '' }}</span>
        <span class="inline-field-new">Sex </span>
        <span class="underline-field-new-phone">{{ $referralSlip->patient->gender ?? '' }}</span>
        <span class="inline-field-new">Occupation </span>
        <span class="underline-field-new-phone">{{ $referralSlip->patient->occupation ?? '' }}</span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Address: Werda </span>
        <span class="underline-field-new-phone">{{ $referralSlip->patient->age ?? '' }}</span>
        <span class="inline-field-new">Kebele </span>
        <span class="underline-field-new-phone">{{ $referralSlip->patient->gender ?? '' }}</span>
        <span class="inline-field-new">House No </span>
        <span class="underline-field-new-phone">{{ $referralSlip->patient->occupation ?? '' }}</span>
    </div>

    <div class="inline-details">
        <table class="prescription-table ft-14px">
            <tbody>
                <tr>
                    <td style="border: none; margin: 0; padding: 0;">clinical Finding<span
                            style="text-decoration: underline;">{{ $referralSlip->clinical_finding ?? '' }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="inline-details">
        <span class="inline-field-new">Diagnosis </span>
        <span class="underline-field-new-phone">{{ $referralSlip->diagnosis ?? '' }}</span>
    </div>
    <div class="inline-details">
        <table class="prescription-table ft-14px">
            <tbody>
                <tr>
                    <td style="border: none; margin: 0; padding: 0;">Investigation Result<span
                            style="text-decoration: underline;">{{ $referralSlip->investigation_result ?? '' }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="inline-details">
        <table class="prescription-table ft-14px">
            <tbody>
                <tr>
                    <td style="border: none; margin: 0; padding: 0;">RX Given<span
                            style="text-decoration: underline;">{{ $referralSlip->rx_given ?? '' }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Reason for Referral </span>
        <span class="underline-field-new-phone"
            style="width: 30rem">{{ $referralSlip->reasons_for_referral ?? '' }}</span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Referred by </span>
        <span class="underline-field-new-phone" style="width: 30rem">{{ $referralSlip->referred_by ?? '' }}</span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Name of Physician </span>
        <span class="underline-field-new-phone"
            style="width: 30rem">{{ $referralSlip->visit->doctor->full_name }}</span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Signature </span>
        <span class="underline-field-new-phone" style="width: 30rem"></span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new" style="margin-left: 30rem;">Ref.No </span>
        <span class="underline-field-new">{{ $referralSlip->card_no }}</span>
    </div>
    <div class="inline-details">
        <span class="inline-field-new" style="margin-left: 30rem;">Date </span>
        <span class="underline-field-new">{{ $referralSlip->date }}</span>
    </div>

    <div style="text-decoration: underline; margin-top: 25px;">
        <h3>FEED BACK</h3>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">From </span>
        <span class="underline-field-new-phone" style="width: 11rem"></span>
        <span class="inline-field-new">Hospital H.Center </span>
        <span class="underline-field-new-phone" style="width: 11rem">{{ $referralSlip->from_department }}</span>
        <span class="inline-field-new">Department </span>
    </div>

    <div class="inline-details">
        <table class="prescription-table ft-14px">
            <tbody>
                <tr>
                    <td style="border: none; margin: 0; padding: 0;">clinical Finding<span
                            style="text-decoration: underline;">{{ $referralSlip->clinical_finding ?? '' }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="inline-details">
        <span class="inline-field-new">Diagnosis </span>
        <span class="underline-field-new-phone" style="width:30rem">{{ $referralSlip->diagnosis ?? '' }}</span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Treatment Given </span>
        <span class="underline-field-new-phone" style="width:30rem">{{ $referralSlip->treatment_given ?? '' }}</span>
    </div>

    <div class="inline-details">
        <span class="inline-field-new">Followed by </span>
        <span class="underline-field-new-phone"
            style="width:30rem">{{ $referralSlip->visit->doctor->full_name }}</span>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>consentForm PDF</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* Add your CSS styles here for the PDF layout */
        @font-face {
            font-family: 'Noto Sans Ethiopic';
            src: url('{{ public_path('fonts/NotoSansEthiopic-VariableFont_wdth,wght.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Noto Sans Ethiopic', Arial, sans-serif;
            margin: 2px;
            line-height: 0.1;
            color: #000000;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 1px;
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

        .contact-info {
            font-weight: bold;
            text-align: center;
            margin: 1px 0;
        }

        .horizontal-line {
            border-top: 2px solid #000000;
            margin: 20px 0;
        }

        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #000000;
            margin-right: 5px;
            vertical-align: middle;
        }

        .custom-height td {
            height: 30px;
        }

        .name {
            display: inline-block;
            width: 500px;
            text-align: left;
            border-bottom: 2px groove #000000;
            padding-left: 20px;
            padding-bottom: 2px;
        }

        .inline-details {
            margin-top: 20px;
        }

        .inline-field-new {
            display: inline-block;
            margin-right: 10px;
        }

        .underline-field-new {
            display: inline-block;
            flex: 1;
            width: 90px;
            text-align: left;
            border-bottom: 2px groove #000000;
            padding-left: 20px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
        }

        .underline-field-new-address {
            display: inline-block;
            width: 130px;
            border-bottom: 2px solid #000000;
        }

        .underline-field-new-phone {
            display: inline-block;
            width: 13rem;
            padding-left: 10px;
            border-bottom: 2px solid #000000;
        }
    </style>
</head>

<body>
    <div class="horizontal-line"></div>

    <h3>Patient Consent Form for Special Procedures</h3>

    <div class="patient-info">

        <div class="inline-details">
            <span class="inline-field-new">I </span>
            <span class="underline-field-new-phone">{{ $consentForm->visit->patient->full_name }}</span>
            <span class="inline-field-new">as my physican Dr </span>
            <span class="underline-field-new-phone">{{ $consentForm->doctor->full_name }}</span>
        </div>

        <!-- Sex, Age, Weight, and Card No. on one line with underlines -->
        <div class="inline-details">
            <span class="inline-field-new">explained to me I need to have </span>
            <span class="underline-field-new-phone"></span>
            <span class="inline-field-new">and I have accepted</span>
        </div>

        <div class="inline-details">
            <span class="inline-field-new">to undergo </span>
            <span class="underline-field-new"></span>
            <span class="inline-field-new">the following points were explained to</span>
        </div>

        <div class="inline-details">
            <span class="inline-field-new">me and I have agreed to accept the terms.</span>
        </div>
        <div style="margin-top: 4px;">
            <p>1. Explanation of procedure, Risks, benefits and alternatives:</p>
        </div>
        <div>
            <p
                style="
            display: block;
            white-space: normal;
            line-height: 1.0;
            margin-bottom: 1rem;
            word-wrap: break-word;">
                The nature and purpose of the procedure, possible alternative methods of treatment, the expected
                benefits complications and the risks involved have been fully explained to me. I have been given an
                opportunity to ask question and all my question have been answered fully and satisfactorily.
            </p>
        </div>
        <div>
            <p
                style="
            display: block;
            white-space: normal;
            line-height: 1.0;
            margin-bottom: 1rem;
            word-wrap: break-word;">
                2. I am informed that during an operation under local anesthesia, unexpected complication may sometimes
                happen such as bleeding, infection, cardiac failure, which might cause death.
            </p>
        </div>
        <div style="margin-top: 1rem;">
            <p>3. Unforeseen Condition
            </p>
        </div>
        <div>
            <p
                style="
            display: block;
            white-space: normal;
            line-height: 1.0;
            margin-bottom: 1rem;
            word-wrap: break-word;">
                If any unforeseen condition arises in the course of the procedure for which other procedures, in
                addition to or different from those above contemplated, are necessary or appropriate in the judgment of
                the said physician or his designee(s), I further request and authorize the conduct of such procedures.
            </p>
        </div>

        <div>
            <p
                style="
            display: block;
            white-space: normal;
            line-height: 1.0;
            margin-bottom: 1rem;
            word-wrap: break-word;">
                I certify that I have read and fully understood the above and consented to have the procedure; all the blank spaces above have been completed prior to my signing.
            </p>
        </div>

        <div class="inline-details" style="margin-top: 10px;">
            <span class="inline-field-new">Name </span>
            <span class="underline-field-new-address"></span>
            <span class="inline-field-new">Signature </span>
            <span class="underline-field-new-address"></span>
        </div>

        <div class="inline-details">
            <span class="inline-field-new">Address </span>
            <span class="underline-field-new-address"></span>
            <span class="inline-field-new">Tel No </span>
            <span class="underline-field-new-address"></span>
        </div>

        <div style="margin-top: 10px">
            <span class="field-label">Witness: 1</span>
            <span class="name">{{ $consentForm->witness_1_name }}</span>
        </div>
        <div style="margin-top: 10px;">
            <span class="field-label">Witness: 2</span>
            <span class="name">{{ $consentForm->witness_2_name ?? '' }}</span>
        </div>

        div style="margin-top: 10px;">
            <span class="field-label">Witness: 1</span>
            <span class="name">{{ $consentForm->witness_1_name }}</span>
            @if($consentForm->witness_1_relationship)
                <span class="relationship">({{ $consentForm->witness_1_relationship }})</span>
            @endif
        </div>

        <div style="margin-top: 10px;">
            <span class="field-label">Witness: 2</span>
            <span class="name">{{ $consentForm->witness_2_name ?? '' }}</span>
            @if($consentForm->witness_2_relationship)
                <span class="relationship">({{ $consentForm->witness_2_relationship }})</span>
            @endif
        </div>
    </div>
</body>
</html>

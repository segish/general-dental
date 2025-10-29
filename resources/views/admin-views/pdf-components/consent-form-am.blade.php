<!DOCTYPE html>
<html lang="am">

<head>
    <title>Consent Form</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Noto Sans Ethiopic', "DejaVu Sans", sans-serif;
            font-size: 14px;
            margin-top: 150px;
            margin-left: 40px;
            margin-right: 40px;
            margin-bottom: 50px;
            color: #000000;
        }

        h3 {
            text-align: center;
            margin-top: 2px;
            margin-bottom: 30px;
        }

        .horizontal-line {
            border-top: 2px solid #000000;
            margin: 20px 0;
        }

        .inline-details {
            margin-top: 20px;
        }

        .inline-field-new {
            display: inline-block;
            margin-right: 10px;
        }

        .underline-field-new,
        .underline-field-new-phone {
            display: inline-block;
            width: 200px;
            border-bottom: 2px groove #000000;
            padding-left: 10px;
            padding-bottom: 2px;
            white-space: nowrap;
        }

        .underline-field-new-address {
            display: inline-block;
            width: 130px;
            border-bottom: 2px solid #000000;
        }

        p {
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .name {
            display: inline-block;
            width: 500px;
            text-align: left;
            border-bottom: 2px groove #000000;
            padding-left: 20px;
            padding-bottom: 2px;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')

    <div class="horizontal-line"></div>

    <h3>Patient Consent Form for Special Procedures</h3>

    <div class="patient-info">

        <div class="inline-details">
            <span class="inline-field-new">I</span>
            <span class="underline-field-new-phone">{{ $medicalDocument->visit->patient->full_name }}</span>
            <span class="inline-field-new">as my physican Dr</span>
            <span class="underline-field-new-phone">{{ $medicalDocument->visit->doctor->full_name }}</span>
        </div>

        <div class="inline-details">
            <span class="inline-field-new">explained to me I need to have</span>
            <span class="underline-field-new-phone"></span>
            <span class="inline-field-new">and I have accepted</span>
        </div>

        <div class="inline-details">
            <span class="inline-field-new">to undergo</span>
            <span class="underline-field-new"></span>
            <span class="inline-field-new">the following points were explained to</span>
        </div>

        <div class="inline-details">
            <span class="inline-field-new">me and I have agreed to accept the terms.</span>
        </div>

        <p>1. Explanation of procedure, Risks, benefits and alternatives:</p>
        <p>
            The nature and purpose of the procedure, possible alternative methods of treatment, the expected
            benefits, complications, and the risks involved have been fully explained to me. I have been given an
            opportunity to ask questions and all my questions have been answered fully and satisfactorily.
        </p>

        <p>
            2. I am informed that during an operation under local anesthesia, unexpected complications may sometimes
            happen such as bleeding, infection, cardiac failure, which might cause death.
        </p>

        <p>3. Unforeseen Condition</p>
        <p>
            If any unforeseen condition arises in the course of the procedure for which other procedures,
            in addition to or different from those above contemplated, are necessary or appropriate in the
            judgment of the said physician or his designee(s), I further request and authorize the conduct
            of such procedures.
        </p>

        <p>
            I certify that I have read and fully understood the above and consented to have the procedure;
            all the blank spaces above have been completed prior to my signing.
        </p>

        <div class="inline-details" style="margin-top: 10px;">
            <span class="inline-field-new">Name</span>
            <span class="underline-field-new-address"></span>
            <span class="inline-field-new">Signature</span>
            <span class="underline-field-new-address"></span>
        </div>

        <div class="inline-details">
            <span class="inline-field-new">Address</span>
            <span class="underline-field-new-address"></span>
            <span class="inline-field-new">Tel No</span>
            <span class="underline-field-new-address"></span>
        </div>

        <div style="margin-top: 10px;">
            <span class="field-label">Witness: 1</span>
            <span class="name">
                {{ $medicalDocument->witness_1_name }}{{ $medicalDocument->witness_1_relationship ? ' (' . $medicalDocument->witness_1_relationship . ')' : '' }}
            </span>
        </div>

        <div style="margin-top: 10px;">
            <span class="field-label">Witness: 2</span>
            <span class="name">
                {{ $medicalDocument->witness_2_name }}{{ $medicalDocument->witness_2_relationship ? ' (' . $medicalDocument->witness_2_relationship . ')' : '' }}
            </span>
        </div>
    </div>

    @include('admin-views.pdf-components.footer')
</body>

</html>

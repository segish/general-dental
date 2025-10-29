<!DOCTYPE html>
<html>

<head>
    <title>Abortion Form</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            margin-top: 150px;
            margin-left: 40px;
            margin-right: 40px;
            margin-bottom: 50px;
        }

        ol {
            padding-left: 20px;
        }

        ol li {
            margin-bottom: 12px;
        }

        .signature-section {
            margin-top: 40px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .underline-strong {
            border-bottom: 2px solid black;
            padding: 2px 10px;
            display: inline-block;
            min-width: 150px;
        }

        .line {
            overflow: hidden;
            /* ensures floated items don't break layout */
            margin-bottom: 10px;
        }

        .right-align {
            float: right;
        }

        .long-line {
            display: inline-block;
            width: 60%;
            padding-left: 20px;
            border-bottom: 2px solid black;
            margin-left: 5px;
        }

        .short-line {
            display: inline-block;
            width: 60%;
            padding-left: 5px;
            border-bottom: 2px solid black;
        }

        .flex-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')

    <div class="title">
        CONSENT FORM FOR CLIENTS REQUESTING MEDICAL ABORTION (MA)
    </div>

    <div class="flex-line">
        <span>I</span>
        <div class="long-line">{{ $medicalDocument->visit->patient->full_name }}</div>
        <span class="right-align">Card No<span class="short-line">{{ $medicalDocument->visit->code }}</span></span>
    </div>


    <p>
        I have consulted with a healthcare professional regarding my health condition related to pregnancy and I consent
        to terminate the pregnancy using medical abortion (pills).
    </p>

    <ol>
        <li>I have understood what medical abortion is and how the pills should be taken.</li>

        <li>Even though I have made the decision to terminate the pregnancy, I understand that I can change my mind at
            any time before the procedure begins.</li>

        <li>I understand that medical abortion has both benefits and risks, and that unexpected complications, although
            rare, may occur, including severe bleeding, cramping, vomiting, or infections.</li>

        <li>I understand that common side effects of the pills may include cramping, bleeding, nausea, vomiting, fever,
            and chills.</li>

        <li>I understand that if the pills fail to terminate the pregnancy or if I do not take the pills correctly, the
            pregnancy may continue or may be incomplete, in which case a vacuum aspiration (VA) procedure may be
            required.</li>

        <li>I am aware that I must return to the facility as per the given appointment and in case of any problems, I
            know the phone number to call for advice and the nearest health facility to go to.</li>

        <li>I understand that bleeding alone does not confirm the end of the pregnancy. I must attend the follow-up
            appointment to ensure the pregnancy has been successfully terminated and that there is no retained tissue.
        </li>

        <li>I understand that for pregnancies less than 9 weeks, a vacuum aspiration (VA) may also be used along with
            the pills.</li>
    </ol>

    <p>
        Therefore, I give permission to the healthcare professionals to do whatever is necessary to manage any
        complications that may arise and to protect my life and health during the abortion process.
    </p>

    <p>
        I confirm by my signature that the above information I provided to the healthcare provider is correct.
    </p>

    <div class="signature-section">
        <div class="line">
            <span>Client’s Name & Signature <span
                    class="underline-strong">{{ $medicalDocument->visit->patient->full_name }}</span></span>
            <span class="right-align">Date <span
                    class="underline-strong">{{ \Carbon\Carbon::parse($medicalDocument->created_at)->format('Y-m-d') }}</span></span>
        </div>
        <div class="line">
            <span>Service Provider’s Name & Signature <span
                    class="underline-strong">{{ $medicalDocument->visit->doctor->fullname }}</span></span>
            <span class="right-align">Date <span
                    class="underline-strong">{{ \Carbon\Carbon::parse($medicalDocument->created_at)->format('Y-m-d') }}</span></span>
        </div>
    </div>

    @include('admin-views.pdf-components.footer')
</body>

</html>

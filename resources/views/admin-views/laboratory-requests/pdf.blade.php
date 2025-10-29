<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Prescription PDF</title>
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
            color: #0077b6;
            ;
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
            border-top: 2px solid #0077b6;
            margin: 20px 0;
        }

        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #0077b6;
            margin-right: 5px;
            vertical-align: middle;
        }

        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .prescription-table th,
        .prescription-table td {
            border: 1px solid #0077b6;
            padding: 8px;
            text-align: left;
            line-height: 1.1;
        }

        .prescriber-dispenser-table {
            width: 100%;
            border-collapse: collapse;
        }

        .prescriber-dispenser-table td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
            border: 0px solid #ccc;
            /* Optional: border for the cells */
        }

        .prescriber-dispenser-underline {
            display: inline-block;
            flex: 1;
            width: 140px;
            text-align: left;
            border-bottom: 2px groove #0077b6;
            padding-left: 20px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
        }

        h4 {
            text-align: center;
        }

        .custom-height td {
            height: 30px;
        }

        .name {
            display: inline-block;
            width: 500px;
            text-align: left;
            border-bottom: 2px groove #0077b6;
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
            border-bottom: 2px groove #0077b6;
            padding-left: 20px;
            padding-bottom: 2px;
            word-wrap: break-word;
            white-space: nowrap;
        }

        .underline-field-new-address {
            display: inline-block;
            width: 130px;
            border-bottom: 2px solid #0077b6;
        }

        .underline-field-new-phone {
            display: inline-block;
            width: 140px;
            padding-left: 10px;
            border-bottom: 2px solid #0077b6;
        }
    </style>
</head>

<body>
    <h1>ቃል ስፔሻሊቲ ልዩ የጥርስ ህክምና ኃ.የተ.የግ.ማህበር</h1>
    <h1>KAL SPECIALITY DENTAL CLINIC PLC</h1>

    <div class="contact-info">
        <p>We Provide Quality Health Care</p>
        <p>Tel. 0900 77 75 76 / 0930 07 24 09 / 0913 67 97 53</p>
        <p>አድራሻ፡- ኢኢ ሲኤሚሲ፤ቃሊቲ፤ ጀሞ፤እንቁላል ፋብሪካ፤ አየር ጤና፤አያት ዞን 3</p>
    </div>

    <div class="horizontal-line"></div>

    <h3>Prescription Paper</h3>

    <div class="patient-info">
        <div class="line-container">
            <span class="field-label">Patient's Full Name:</span>
            <span class="name">{{ $prescription->medicalHistory->patient->full_name }}</span>
            <!-- Name is underlined and centered -->
        </div>

        <!-- Sex, Age, Weight, and Card No. on one line with underlines -->
        <div class="inline-details">
            <span class="inline-field-new">Sex</span>
            <span class="underline-field-new">{{ $prescription->medicalHistory->patient->gender ?? '' }}</span>
            <span class="inline-field-new">Age</span>
            <span class="underline-field-new">{{ $prescription->medicalHistory->patient->age ?? '' }}</span>
            <span class="inline-field-new">Weight </span>
            <span class="underline-field-new">{{ $prescription->medicalHistory->patient->physicalTest->weight ?? '' }}</span>
            <span class="inline-field-new">Card No. </span>
            <span class="underline-field-new">{{ $prescription->medicalHistory->id }}</span>
        </div>

        <div class="inline-details">
            <span class="inline-field-new">Address:- Town </span>
            <span class="underline-field-new-address"></span>
            <span class="inline-field-new">Woreda </span>
            <span class="underline-field-new-address"></span>
            <span class="inline-field-new">Kebele </span>
            <span class="underline-field-new-address"></span>
        </div>

        <div class="inline-details">
            <span class="inline-field-new">House No </span>
            <span class="underline-field-new-phone"></span>
            <span class="inline-field-new">Tel No: </span>
            <span class="underline-field-new-phone">{{ $prescription->medicalHistory->patient->phone }}</span>
            <span class="inline-field-new checkbox"></span> In-Patient
            <span class="inline-field-new checkbox"></span> Out-Patient
        </div>
        {{-- <p> <strong>{{ $prescription->house_no }}, {{ $prescription->tel_no }}</strong></p> --}}
        {{-- <p>Diagnosis (if not ICD): <strong>{{ $prescription->diagnosis }}</strong></p> --}}
        <div class="inline-details">
            <span class="field-label">Diagnosis (if not ICD): </span>
            <span class="name"></span> <!-- Name is underlined and centered -->
        </div>
    </div>

    <table class="prescription-table">
        <thead>
            <tr>
                <th style="width: 70%;">Drug Name Strength Dosage From Frequency<br>
                    Duration, Quantity; How to use & other information</th>
                <th style="width: 30%;">Price (dispensers<br> use only)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prescription->medicines as $medicine)
                <tr>
                    <td>
                        {{ $medicine->name }} ({{ $medicine->pivot->medication_details ?? '' }})<br>
                    </td>
                    <td></td>
                </tr>
            @endforeach
            <tr class="custom-height">
                <td></td>
                <td></td>
            </tr>
            <tr class="custom-height">
                <td>
                    Note: {{ $prescription->prescription_content }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: right; padding-right: 20px;">Total Price</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="prescriber-dispenser-table">
        <tr>
            <td style="width: 75%;">
                <div class="prescriber">
                    <h4>Prescriber's</h4>
                    <P><span>Full Name:</span><span
                            class="prescriber-dispenser-underline">{{ $prescription->medicalHistory->doctor->admin->f_name }}</span>
                    </P>
                    <P><span>Qualification:</span><span
                            class="prescriber-dispenser-underline">{{ $prescription->medicalHistory->doctor->specialization ?? '' }}</span>
                    </P>
                    <P><span>Registration:</span><span class="prescriber-dispenser-underline"></span></P>
                    <P><span>Signature:</span>
                        @if ($prescription->medicalHistory->doctor->admin->signature)
                            <img src="{{ $prescription->medicalHistory->doctor->admin->signature_url }}"
                                alt="Doctor Signature"
                                style="max-width: 150px; max-height: 30px; vertical-align: middle;">
                        @else
                            <span class="prescriber-dispenser-underline"></span>
                        @endif
                    </P>
                    <P><span>Date:</span><span
                            class="prescriber-dispenser-underline">{{ $prescription->created_at->format('M d, Y') }}</span>
                    </P>
                </div>
            </td>

            <td style="width: 25%;">
                <div class="dispenser">
                    <h4>Dispenser's</h4>
                    <P><span class="prescriber-dispenser-underline"></span>
                    </P>
                    <P><span class="prescriber-dispenser-underline"></span>
                    </P>
                    <P><span class="prescriber-dispenser-underline"></span></P>
                    <P><span class="prescriber-dispenser-underline"></span></P>
                    <P><span class="prescriber-dispenser-underline"></span></P>
                </div>
            </td>
        </tr>
    </table>

</body>

</html>

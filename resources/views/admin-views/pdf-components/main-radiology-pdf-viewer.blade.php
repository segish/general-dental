<!DOCTYPE html>
<html>

<head>
    <title>Radiology Result</title>
    <style>
        @page {
            margin: 0;
            /* No margin for the entire page */
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            margin-top: 100px;
            margin-left: 40px;
            margin-right: 40px;
            margin-bottom: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid black;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        .header h2 {
            font-size: 14px;
            margin: 10px 0 0;
            padding: 0;
            text-decoration: underline;
            font-weight: bold;
        }

        .patient-info {
            width: 100%;
            margin-bottom: 15px;
        }

        .patient-info p {
            margin: 5px 0;
        }

        .section {
            margin-bottom: 15px;
        }

        .section h3 {
            font-size: 12px;
            margin: 0 0 5px 0;
            font-weight: bold;
            text-decoration: underline;
        }

        .findings-list {
            margin: 5px 0;
            padding-left: 20px;
        }

        .findings-list li {
            margin-bottom: 3px;
        }

        .dashed-line {
            border-top: 1px dashed #000;
            margin: 15px 0;
        }

        .signature {
            margin-top: 30px;
            text-align: right
        }

        .signature p {
            margin: 5px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')
    @include('admin-views.pdf-components.ultrasound-obstetrics', [
        'patient' => $radiologyResult->radiologyRequestTest->request->visit->patient,
        'radiologyResult' => $radiologyResult,
    ])
    @include('admin-views.pdf-components.footer')

</body>

</html>

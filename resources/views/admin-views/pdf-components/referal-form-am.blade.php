<!DOCTYPE html>
<html>

<head>
    <title>Lab Test Result </title>
    <style>
        @page {
            margin: 0;
            /* No margin for the entire page */
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            margin-top: 150px;
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
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')


    @include('admin-views.pdf-components.footer')

</body>

</html>

<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Prescription PDF</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@100..900&display=swap" rel="stylesheet">
    <style>
        @page {
            margin-top: 150px;
            margin-bottom: 150px;
        }

        #header {
            position: fixed;
            left: 0px;
            top: -150px;
            right: 0px;
            height: 180px;
            text-align: center;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 0px;
        }

        .logo-container {
            text-align: right;
            position: relative;
        }

        .logo-container img {
            width: 80px;
            /* Adjust as needed */
            height: auto;
        }

        .logo-title {
            font-size: 12px;
            text-align: right;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: bottom;
            font-size: 8px;
            /* Font size for table cells */
        }

        th {
            background-color: #f2f2f2;
            font-weight: normal;
            /* Not bold */
        }

        /* Updated vertical header styling */
        .vertical-header {
            height: 100px;
            /* Height for vertical headers */
            position: relative;
            /* Relative positioning */
            overflow: hidden;
            /* Prevent overflow */
        }

        .vertical-text {
            position: absolute;
            /* Positioning inside the header cell */
            left: 50%;
            /* Center horizontally */
            top: 50%;
            /* Center vertically */
            transform: translate(-50%, -50%) rotate(-90deg);
            /* Rotate text */
            white-space: nowrap;
            /* Prevent text wrapping */
        }

        /* Specific widths for each header */
        .header-sn {
            width: 30px;
        }

        .header-service-date {
            width: 50px;
        }

        .header-mrn {
            width: 40px;
        }

        .header-age {
            width: 25px;
        }

        .header-sex {
            width: 20px;
        }

        .header-address {
            width: 50px;
        }

        .header-ncod {
            width: 120px;
        }

        .header-new {
            width: 25px;
        }

        .header-repeat {
            width: 25px;
        }

        .header-traffic {
            width: 25px;
        }

        .header-hiv-offered {
            width: 25px;
        }

        .header-hiv-performed {
            width: 25px;
        }

        .header-targeted-category {
            width: 25px;
        }

        .header-hiv-result {
            width: 25px;
        }

        .header-screened-tb {
            width: 25px;
        }

        .header-tb-result {
            width: 25px;
        }

        .header-diagnostic {
            width: 25px;
        }

        .header-result {
            width: 30px;
        }

        .header-referred {
            width: 25px;
        }

        .header-died {
            width: 25px;
        }

        .header-remark {
            width: 50px;
        }

        #footer {
            position: fixed;
            left: 0;
            bottom: 0;
            right: 0;
            height: 100px;
            /* Adjust height as needed */
            padding: 10px;
        }

        .footer-layout {
            width: 100%;
            /* Full width of the footer */
            table-layout: fixed;
            border: 0;
            /* Fixed table layout for even spacing */
        }

        .footer-table-cell {
            width: 33%;
            /* Each cell takes one-third of the footer */
            vertical-align: top;
            border: 0;
            /* Align content at the top */
        }

        .footer-inner-table {
            width: 100%;
            /* Inner table width */
            margin: 0 auto;
            /* Center the inner table */

        }

        .footer-inner-table th {
            text-align: center;
            /* Center text in table header */
            font-size: 8px;
            /* Font size for header */
        }

        .footer-text {
            font-size: 8px;
            /* Font size for footer text */
            line-height: 1.2;
            /* Line height for readability */
        }

        .right-text {
            font-size: 8px;
            /* Font size for right side text */
        }
    </style>
</head>

<body>
    <div id="header">
        <h1>Outpatient Department (OPD) Register</h1>
        <div class="logo-container">
            <img style="margin-right: 100px" src="assets/admin/img/FDRE-Logo.png" alt="FDRE Logo">
            <div class="logo-title">Federal Democratic Republic of Ethiopia Ministry of Health</div>
        </div>
    </div>

    <div id="footer">
        <table style="margin: auto; width: 50%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Pedestrian</th>
                    <th>Motorcycle</th>
                    <th>Vehicle Occupants</th>
                </tr>
            </thead>
        </table>
        <table class="footer-layout">
            <tr>
                <td class="footer-table-cell" style="text-align: left; width: 70%;">
                    <div class="footer-text">
                        <strong>Target:</strong><br>
                        Female commercial A. Sex workers, B. Long distance drivers, C. Mobile/laborers, D. Prisoners,<br>
                        E. OVC, F. Children of PLHIV, G. Partners of PLHIV, H. Others MARPs, I. General population Type of diagnostic:<br>
                        Col-17:1. Sputum smear microscopy, 2. Sputum GeneXpert, 3. X-ray/other imaging,<br>
                        4. Histopathology test, 5. Other (specify), 6. Not done
                    </div>
                </td>
                <td class="footer-table-cell" style="text-align: left; width: 30%;">
                    <div class="right-text">
                        <strong>**referral Codes for CP:19</strong><br>
                        1. Hospital&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 6. SOPD<br>
                        2. Health center &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7. OPGYN<br>
                        3. Health post&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8. TB clinic<br>
                        4. MCH&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 9. To another service/HI<br>
                        5. ART<br>
                    </div>
                </td>
            </tr>
        </table>
    </div>


    <table>
        <thead>
            <tr>
                <th colspan="5">Identification</th>
                <th colspan="4">Diagnosis</th>
                <th></th>
                <th colspan="8">HIV and TB Screening</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th class="header-sn">S.N</th>
                <th class="header-service-date">Service Date<br>(DD/MM/YY)</th>
                <th class="header-mrn">MRN</th>
                <th class="header-age">Age</th>
                <th class="header-sex">SEX<br>(M/F)</th>
                <th class="header-address">Address<br>(Woreda, Kebele)</th>
                <th class="header-ncod">National classification of disease (NCoD) (If patient admitted, do not write
                    diagnosis, write admitted)</th>
                <th class="vertical-header header-new">
                    <div class="vertical-text">New (✓)</div>
                </th>
                <th class="vertical-header header-repeat">
                    <div class="vertical-text">Repeat (✓)</div>
                </th>
                <th class="vertical-header header-traffic">
                    <div class="vertical-text">Road Traffic Accident<br>1. Pediatric 2. Motorcycle<br>3. Vehicle
                        Occupant</div>
                </th>
                <th class="vertical-header header-hiv-offered">
                    <div class="vertical-text">HIV Test Offered (✓)</div>
                </th>
                <th class="vertical-header header-hiv-performed">
                    <div class="vertical-text">HIV Test Performed (✓)</div>
                </th>
                <th class="vertical-header header-targeted-category">
                    <div class="vertical-text">Targeted Population<br>Category(code)</div>
                </th>
                <th class="vertical-header header-hiv-result">
                    <div class="vertical-text">HIV Test Result (P or N)</div>
                </th>
                <th class="vertical-header header-screened-tb">
                    <div class="vertical-text">Screened for TB (✓)</div>
                </th>
                <th class="vertical-header header-tb-result">
                    <div class="vertical-text">TB Screening Result (P/N)</div>
                </th>
                <th class="vertical-header header-diagnostic">
                    <div class="vertical-text">Type of Diagnostic<br>Evaluation</div>
                </th>
                <th class="vertical-header header-result">
                    <div class="vertical-text">Result of TB Screening<br>(Code TB, NO TB, <br>Not decided (ND))</div>
                </th>
                <th class="header-referred">Referred<br>to**</th>
                <th class="header-died">Died<br>(✓)</th>
                <th class="vertical-header header-remark">
                    <div class="vertical-text">Remark</div>
                </th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
                <th>8</th>
                <th>9</th>
                <th>10</th>
                <th>11</th>
                <th>12</th>
                <th>13</th>
                <th>14</th>
                <th>15</th>
                <th>16</th>
                <th>17</th>
                <th>18</th>
                <th>19</th>
                <th>20</th>
                <th>21</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($patientsReport as $key => $patientReport)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($patientReport->service_date)->format('d/m/y') }}</td>
                    <td>{{ $patientReport->mrn }}</td>
                    <td>{{ $patientReport->patient->age }}</td>
                    <td>{{ $patientReport->patient->gender === 'Male' ? 'M' : ($patientReport->patient->gender === 'Female' ? 'F' : '') }}
                    </td>
                    <td>{{ $patientReport->address ?? '' }}</td>
                    <td>{{ $patientReport->ncod }}</td>
                    <td>{{ $patientReport->status === 'New' ? '✓' : '' }}</td>
                    <td>{{ $patientReport->status === 'Repeat' ? '✓' : '' }}</td>
                    <td>{{ $patientReport->road_traffic_accident ?? '' }}</td>
                    <td>{{ $patientReport->hiv_test_offered === 1 ? '✓' : '' }}</td>
                    <td>{{ $patientReport->hiv_test_performed === 1 ? '✓' : '' }}</td>
                    <td>{{ $patientReport->targeted_population_category ?? '' }}</td>
                    <td>{{ $patientReport->hiv_test_result ?? '' }}</td>
                    <td>{{ $patientReport->tb_screening === 1 ? '✓' : '' }}</td>
                    <td>{{ $patientReport->tb_screening_result ?? '' }}</td>
                    <td>{{ $patientReport->diagnostic_evaluation_code ?? '' }}</td>
                    <td>{{ $patientReport->tb_screening_outcome ?? '' }}</td>
                    <td>{{ $patientReport->referred_to ?? '' }}</td>
                    <td>{{ $patientReport->died === 1 ? '✓' : '' }}</td>
                    <td>{{ $patientReport->remark ?? '' }}</td>
                </tr>
            @endforeach
            <!-- Repeat above row structure for more entries -->
        </tbody>
    </table>
</body>

</html>

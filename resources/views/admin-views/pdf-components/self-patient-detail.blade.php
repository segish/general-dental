@php
    use Carbon\Carbon;
    $age = $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A';
@endphp

<style>
    td {
        padding: 2px;
    }
</style>
<table width="100%" style="border: none; margin-bottom: 10px;">
    <tr>
        <!-- Patient Details Table -->
        <td width="48%" style="border: none; vertical-align: top; padding-right: 10px;">
            <table border="1" width="100%" cellpadding="5" style="border-collapse: collapse;">
                <tr>
                    <th colspan="2" style="background-color: #f2f2f2;">Patient Details</th>
                </tr>
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>{{ $patient->full_name }}</td>
                </tr>
                <tr>
                    <td><strong>Address:</strong></td>
                    <td>{{ $patient->address }}</td>
                </tr>
                <tr>
                    <td><strong>Age:</strong></td>
                    <td>{{ $age }} years</td>
                </tr>
                <tr>
                    <td><strong>Gender:</strong></td>
                    <td>{{ ucfirst($patient->gender) }}</td>
                </tr>
            </table>
        </td>

        <!-- Requester Details Table -->
        <td width="48%" style="border: none; vertical-align: top; padding-left: 10px;">
            <table border="1" width="100%" cellpadding="5" style="border-collapse: collapse;">
                <tr>
                    <th colspan="2" style="background-color: #f2f2f2;">Requester Details</th>
                </tr>
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>{{ $laboratoryRequest->referring_dr ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Organization:</strong></td>
                    <td>{{ $laboratoryRequest->referring_institution ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Telephone:</strong></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

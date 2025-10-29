@php
    use Carbon\Carbon;
    $age = $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A';
@endphp

<style>
    td {
        padding: 2px;
    }
</style>
@php
    $clinicName = \App\Models\BusinessSetting::where(['key'=>'pdf_company_name_en'])->first()? \App\Models\BusinessSetting::where(['key'=>'pdf_company_name_en'])->first()->value:'';
    $tell = \App\Models\BusinessSetting::where(['key'=>'pdf_tel_num'])->first()? \App\Models\BusinessSetting::where(['key'=>'pdf_tel_num'])->first()->value:'';
@endphp
<table width="100%" style="border: none !important; font-size: 13px; border-spacing: 0;">
    <tr>
        <!-- Patient Details Table -->
        <td width="48%" style="border: none; vertical-align: top;">
            <table border="1" width="100%" cellpadding="5" style="border-collapse: collapse;">
                <tr>
                    <th colspan="2" style="background-color: #f2f2f2; font-size: 15px;">Patient Details</th>
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
                    <th colspan="2" style="background-color: #f2f2f2; font-size: 15px;">Requester Details</th>
                </tr>
                <tr>
                    <td><strong>Sample Coll Time:</strong></td>
                    <td>{{ optional($specimens->first())->specimen_taken_at ? \Carbon\Carbon::parse($specimens->first()->specimen_taken_at)->format('d M, Y') : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>{{ $laboratoryRequest->billing && $laboratoryRequest->billing->admin ? $laboratoryRequest->billing->admin->fullname : '---' }}</td>
                </tr>
                <tr>
                    <td><strong>Organization:</strong></td>
                    <td>{{ $laboratoryRequest->referring_institution ?? $clinicName }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Telephone:</strong></td>
                    <td>{{$tell}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

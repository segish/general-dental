<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>#</th>
            <th>Patient Name</th>
            <th>Visit Date</th>
            <th>Reason</th>
            <th>Doctor</th>
            <th>Diagnosed Diseases</th> <!-- New Column -->
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $visit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $visit->patient->full_name ?? 'N/A' }}</td>
                <td>{{ $visit->created_at->toDateString() }}</td>
                <td>{{ $visit->medicalRecord->chief_complaint ?? 'N/A' }}</td>
                <td>{{ $visit->doctor->full_name ?? 'N/A' }}</td>
                <td>
                    @if ($visit->diagnosisTreatment && $visit->diagnosisTreatment->diseases->isNotEmpty())
                        <ul style="padding-left: 15px; margin: 0;">
                            @foreach ($visit->diagnosisTreatment->diseases as $disease)
                                <li>{{ $disease->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>#</th>
            <th>Patient Name</th>
            <th>Total Visits</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->patient->full_name ?? 'N/A' }}</td>
                <td>{{ $row->visit_count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

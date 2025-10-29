<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>#</th>
            <th>Patient Name</th>
            <th>Visit Date</th>
            <th>Tests Requested</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $visit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $visit->patient->full_name ?? 'N/A' }}</td>
                <td>{{ $visit->created_at->toDateString() }}</td>
                <td>{{ $visit->test_names ?? 'N/A' }}</td> <!-- Display the combined test names -->
            </tr>
        @endforeach
    </tbody>
</table>

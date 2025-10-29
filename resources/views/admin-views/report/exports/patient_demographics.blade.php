<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Sex</th>
            <th>Age</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $patient)
        <tr>
            <td>{{ $patient->full_name }}</td>
            <td>{{ $patient->sex }}</td>
            <td>{{ $patient->age }}</td>
            <td>{{ $patient->phone }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Total Revenue</th>
            <th>Total Paid</th>
            <th>Outstanding</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($revenues as $revenue)
        <tr>
            <td>{{ $revenue->date }}</td>
            <td>{{ number_format($revenue->total_revenue, 2) }}</td>
            <td>{{ number_format($revenue->total_paid, 2) }}</td>
            <td>{{ number_format($revenue->outstanding, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

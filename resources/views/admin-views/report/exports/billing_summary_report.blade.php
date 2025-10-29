<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>#</th>
            <th>Patient Name</th>
            <th>Invoice Number</th>
            <th>Total Amount</th>
            <th>Total Amount Paid</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $bill)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $bill->patient->full_name ?? 'N/A' }}</td>
                <td>{{ $bill->visit_id }}</td>
                <td>{{ number_format($bill->total_amount, 2) }}</td>
                <td>{{ number_format($bill->amount_paid, 2) }}</td>
                <td>{{ ucfirst($bill->status) }}</td>
                <td>{{ $bill->created_at->toDateString() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

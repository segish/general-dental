<table>
    <thead>
        <tr>
            <th>{{ \App\CentralLogics\translate('SL') }}</th>
            <th>{{ \App\CentralLogics\translate('Patient') }}</th>
            <th>{{ \App\CentralLogics\translate('Service Name') }}</th>
            <th>{{ \App\CentralLogics\translate('Received By') }}</th>
            <th>{{ \App\CentralLogics\translate('Total Amount') }}</th>
            <th>{{ \App\CentralLogics\translate('Received Amount') }}</th>
            <th>{{ \App\CentralLogics\translate('Status') }}</th>
            <th>{{ \App\CentralLogics\translate('Date') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($billings as $billing)
            <tr>
                <td>{{ $loop->iteration }}</td> <!-- Using $loop->iteration -->
                <td>{{ $billing->patient->full_name }}</td>
                <td>
                    {{-- Check service, then testType, then radiologyType --}}
                    @foreach ($billing->billingDetails as $billingDetail)
                        @if ($billingDetail->service)
                            {{-- If service exists, show service name --}}
                            {{ $billingDetail->service->service_name ?? 'N' }}
                        @elseif ($billingDetail->testType)
                            {{-- If service is null, check for testType and show test_name --}}
                            {{ $billingDetail->testType->test_name ?? 'N' }}
                        @elseif ($billingDetail->radiologyType)
                            {{-- If service and testType are null, check for radiologyType and show radiology_test_name --}}
                            {{ $billingDetail->radiologyType->radiology_test_name ?? 'N' }}
                        @else
                            {{-- If all are null, show 'N' --}}
                            N
                        @endif

                        @if (!$loop->last)
                            <!-- Don't add a comma for the last item -->
                            ,
                        @endif
                    @endforeach
                </td>
                <td>{{ $billing->admin->f_name }}</td>
                <td>{{ $billing->total_amount }}</td>
                <td>{{ $billing->amount_paid }}</td>
                <td>
                    @if ($billing->amount_paid >= $billing->total_amount)
                        <span style="font-weight: bold" class="text-success">Paid</span>
                    @elseif ($billing->amount_paid == 0)
                        <span style="font-weight: bold" class="text-danger">Unpaid</span>
                    @elseif ($billing->amount_paid > 0 && $billing->amount_paid < $billing->total_amount)
                        <span style="font-weight: bold; color:rgba(255, 138, 14, 0.776)">
                            Partial <br> ({{ $billing->total_amount - $billing->amount_paid }})
                        </span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($billing->created_at)->format('M d, Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

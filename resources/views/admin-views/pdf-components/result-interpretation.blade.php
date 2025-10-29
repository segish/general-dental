<hr>

<h5 style="text-align: center; font-size: 12px;">Comment / Result Interpretation</h5>

<!-- Specimen Type (ST) Information Table -->
@if ($test->specimenType || $test->specimenType != null)
    <table width="100%" border="1" cellspacing="0" cellpadding="5"
        style="font-size: 10px; margin-top: 10px; border-collapse: collapse;">
        <tr>
            <td colspan="4" style="text-align: center;"><strong>Specimen Type (ST) Information</strong></td>
        </tr>
        <tr>
            <th width="20%">Code</th>
            <th width="26%">Description</th>
            <th width="27%">Collection Date</th>
            <th width="27%">Received Date</th>
        </tr>
        {{-- @foreach ($specimens as $specimen) --}}
            <tr>
                <td>{{ $specimens->first()->specimen_code }}</td>
                <td>{{ $test->specimenType->name }}</td>
                <td>{{ \Carbon\Carbon::parse($specimens->first()->specimen_taken_at)->format('d M, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($specimens->first()->created_at)->format('d M, Y') }}</td>
            </tr>
        {{-- @endforeach --}}
    </table>
@endif

<!-- Sample Verification Information Table -->
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="font-size: 10px; margin-top: 20px;">
    <tr>
        <td colspan="5" style="text-align: center;"><strong>Sample Verification Information</strong></td>
    </tr>
    <tr>
        <th>S.No</th>
        <th>Department</th>
        <th>Name of Verifier</th>
        <th>Sign</th>
        <th>Verification Date</th>
    </tr>
    {{-- @foreach ($departments as $index => $department) --}}
    <tr>
        <td>1</td>
        <td>{{ $test->testCategory->name }}</td>
        <td>{{ $testResult->verifiedBy->f_name }}</td>
        <td>________</td>
        <td>{{ \Carbon\Carbon::parse($testResult->process_end_time)->format('d M, Y') }}</td>
    </tr>
    {{-- @endforeach --}}
</table>

<!-- Laboratory Machine (LM) & Testing Method (TM) Information Table -->
@if ($test->laboratory_machine_id || $test->testing_method_id)
    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="font-size: 10px; margin-top: 20px;">
        <tr>
            <th colspan="2" style="text-align: center;">Laboratory Machine (LM) Information</th>
            <th colspan="2" style="text-align: center;">Testing Method (TM) Information</th>
        </tr>
        <tr>
            <th>Code</th>
            <th>Description</th>
            <th>Code</th>
            <th>Description</th>
        </tr>
        @foreach ($tests as $test)
            @if ($test->laboratory_machine || $test->testing_method)
                <tr>
                    <td>{{ $test->laboratory_machine->code ?? 'N/A' }}</td>
                    <td>{{ $test->laboratory_machine->description ?? 'N/A' }}</td>
                    <td>{{ $test->testing_method->code ?? 'N/A' }}</td>
                    <td>{{ $test->testing_method->description ?? 'N/A' }}</td>
                </tr>
            @endif
        @endforeach
    </table>
@endif

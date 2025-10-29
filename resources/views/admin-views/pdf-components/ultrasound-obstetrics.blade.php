    <div class="header">
        <h2>ULTRASOUND REPORT</h2>
    </div>

    <div class="patient-info">
        <p style="text-align: right">
            Date: <span class="underline">{{ $radiologyResult->created_at->format('d/m/Y') ?? '__________' }}</span>
        </p>
        <table width="100%" style="margin-top: 10px; border: none; border-collapse: collapse;" cellpadding="0"
            cellspacing="0" style="margin-top: 10px;">
            <tr>
                <td style="border: none;">
                    Name:
                    <span class="underline">
                        {{ $radiologyResult->radiologyRequestTest->request->visit->patient->full_name ?? '__________' }}
                    </span>
                </td>
                <td style="border: none;">
                    Sex:
                    <span class="underline">
                        {{ $radiologyResult->radiologyRequestTest->request->visit->patient->gender ?? '___' }}
                    </span>
                </td>
                <td style="border: none;">
                    Age:
                    <span class="underline">
                        {{ $radiologyResult->radiologyRequestTest->request->visit->patient->age ?? '______' }}
                    </span>
                </td>
            </tr>
        </table>


    </div>

    <div class="section">
        <h3>Examination name</h3>
        <ul class="findings-list">
            <li>{{ $radiologyResult->requestTest->radiology->radiology_name }}</li>
        </ul>

        <ul class="findings-list">
            @foreach ($radiologyResult->attributes as $attribute)
                @php
                    $count = count($radiologyResult->attributes);
                    $middleIndex = floor($count / 2);
                @endphp
                <p>
                    <li>
                        <strong>{{ $attribute->attribute->attribute_name ? $attribute->attribute->attribute_name . ': ' : '__________ : ' }}</strong>
                        <span>{!! $attribute->result_value ?? '__________' !!}</span>
                    </li>
                </p>
            @endforeach
        </ul>
    </div>

    <div class="dashed-line"></div>


    <div class="dashed-line"></div>

    <div class="signature">
        <p>Reported by <span class="underline">__________</span></p>
        <p>Signature <span class="underline">__________</span></p>
    </div>

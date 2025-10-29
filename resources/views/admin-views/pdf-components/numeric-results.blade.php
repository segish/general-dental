<table>
    @php
        if (!function_exists('formatOperator')) {
            function formatOperator($op)
            {
                return match ($op) {
                    '>=' => '≥',
                    '<=' => '≤',
                    '>' => '>',
                    '<' => '<',
                    '=' => '=',
                    default => '',
                };
            }
        }
        if (!function_exists('formatRange')) {
            function formatRange($lower, $upper, $lowerOp, $upperOp)
            {
                if (!is_null($lower) && !is_null($upper)) {
                    return "{$lower} – {$upper}";
                } elseif (!is_null($lower)) {
                    return formatOperator($lowerOp) . $lower;
                } elseif (!is_null($upper)) {
                    return formatOperator($upperOp) . $upper;
                }
                return '';
            }
        }
        if (!function_exists('formatReferenceText')) {
            function formatReferenceText($ref)
            {
                if ($ref->reference_text) {
                    return $ref->reference_text;
                }

                $parts = [];
                if (!is_null($ref->min_age) && !is_null($ref->max_age)) {
                    $parts[] = "Age {$ref->min_age} – {$ref->max_age}";
                }

                $range = formatRange($ref->lower_limit, $ref->upper_limit, $ref->lower_operator, $ref->upper_operator);
                if ($range) {
                    $parts[] = $range;
                }

                return implode(', ', $parts);
            }
        }
        if (!function_exists('buildReferenceDisplay')) {
            function buildReferenceDisplay($references)
            {
                if (count($references) == 2 && collect($references)->every(fn($r) => $r->gender)) {
                    $maleRef = collect($references)->firstWhere('gender', 'male');
                    $femaleRef = collect($references)->firstWhere('gender', 'female');

                    if ($maleRef && $femaleRef) {
                        return 'Male ' .
                            formatReferenceText($maleRef) .
                            '&emsp;&emsp; <br> Female ' .
                            formatReferenceText($femaleRef);
                    }
                }

                return implode(
                    '<br>',
                    array_map(function ($ref) {
                        $gender = $ref->gender ? ucfirst($ref->gender) . ' ' : '';
                        return $gender . formatReferenceText($ref);
                    }, $references->all()),
                );
            }
        }
    @endphp
    <tr>
        <th>Test Name</th>
        <th>Result</th>
        <th>ABN</th>
        <th>Unit</th>
        <th>Reference</th>
    </tr>
    @foreach ($attributes as $attribute)
        <tr>
            <!-- Test Name -->
            <td><strong>{{ $attribute->attribute->attribute_name }}</strong></td>

            <!-- Result -->
            <td>{{ $attribute->result_value ?? 'N/A' }}</td>
            <td>{{ $attribute->comments ?? 'N/A' }}</td>

            <!-- Unit -->
            <td>{{ $attribute->attribute->unit->code ?? 'N/A' }}</td>

            <td>
                {!! buildReferenceDisplay($attribute->attribute->attributeReferences ?? collect()) !!}
            </td>
        </tr>
    @endforeach
</table>

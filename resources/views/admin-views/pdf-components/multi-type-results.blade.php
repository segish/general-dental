<!-- Combined Test Result Table -->
@php
    // Group attributes by test_category
    $groupedAttributes = $testResult->attributes->groupBy(fn($attr) => $attr->attribute->test_category);

    // Define the desired order
    $desiredOrder = ['Macroscopic', 'Chemical', 'Microscopic', 'Result', 'Text'];

    // Reorder categories based on $desiredOrder
    $categories = collect($desiredOrder)->filter(fn($cat) => $groupedAttributes->has($cat));

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
            if ($ref->reference_text !== null) {
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
                ',',
                array_map(function ($ref) {
                    $gender = $ref->gender ? ucfirst($ref->gender) . ' ' : '';
                    return $gender . formatReferenceText($ref);
                }, $references->all()),
            );
        }
    }
@endphp

<table>
    @foreach ($categories as $category)
        @php

            $categoryTitle = '';
            if ($category == 'Macroscopic') {
                $categoryTitle = 'PHYSICAL EXAMINATION';
            } elseif ($category == 'Microscopic') {
                $categoryTitle = 'MICROSCOPIC EXAMINATION';
            } elseif ($category == 'Chemical') {
                $categoryTitle = 'CHEMICAL EXAMINATION';
            } elseif ($category == 'Text') {
                $categoryTitle = 'TEXT EXAMINATION';
            } elseif ($category == 'Result') {
                $categoryTitle = 'RESULT EXAMINATION';
            }
        @endphp
        <tr>
            <th class="section-title" colspan="4">{{ strtoupper($categoryTitle) }}</th>
        <tr>
            <th>Test Name</th>
            <th>Result</th>
            <th>ABN</th>
            <th>Reference</th>
        </tr>
        </tr>
        @foreach ($groupedAttributes[$category] as $attribute)
            <tr>
                <td width="40%"><strong>{{ $attribute->attribute->attribute_name }}</strong></td>
                <td style="font-weight: normal;">{{ $attribute->result_value ?? 'N/A' }}</td>
                <td>{{ $attribute->comments ?? 'N/A' }}</td>
                <td>
                    {!! buildReferenceDisplay($attribute->attribute->attributeReferences ?? collect()) !!}
                </td>
            </tr>
        @endforeach
    @endforeach
</table>

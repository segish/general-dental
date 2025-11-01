@php
    // This partial displays medical record field values
    // $values should be an array with field short_code as key
@endphp

@foreach ($medicalRecordFields as $field)
    @php
        $fieldValue = isset($values) && isset($values[$field->short_code]) ? $values[$field->short_code] : null;
    @endphp
    @if ($fieldValue !== null && $fieldValue !== '')
        <div class="col-md-6 mb-3">
            <strong>{{ $field->name }}:</strong>
            <p class="mb-0 text-muted">
                @if ($fieldValue !== null && $fieldValue !== '')
                    @if (in_array($field->field_type, ['multiselect', 'checkbox']) && is_array($fieldValue))
                        @php
                            $selectedOptions = [];
                            foreach ($field->options as $option) {
                                if (in_array($option->option_value, $fieldValue)) {
                                    $selectedOptions[] = $option->option_label;
                                }
                            }
                        @endphp
                        {{ !empty($selectedOptions) ? implode(', ', $selectedOptions) : 'Not Specified' }}
                    @elseif($field->field_type == 'select')
                        @php
                            $selectedOption = $field->options->firstWhere('option_value', $fieldValue);
                        @endphp
                        {{ $selectedOption ? $selectedOption->option_label : $fieldValue }}
                    @else
                        {{ $fieldValue }}
                    @endif
                @else
                    Not Specified
                @endif
            </p>
        </div>
    @endif
@endforeach

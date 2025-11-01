@php
    // This partial renders medical record fields dynamically
@endphp

@foreach ($medicalRecordFields as $field)
    @php
        $fieldName = 'field_' . $field->short_code;
        $fieldValue = isset($values) && isset($values[$field->short_code]) ? $values[$field->short_code] : null;
    @endphp

    <div class="form-group">
        <label class="input-label">
            {{ $field->name }}
            @if ($field->is_required)
                <span class="text-danger">*</span>
            @endif
        </label>

        @if ($field->field_type == 'text')
            <input type="text" name="{{ $fieldName }}" class="form-control" value="{{ old($fieldName, $fieldValue) }}"
                @if ($field->is_required) required @endif>
        @elseif($field->field_type == 'textarea')
            <textarea name="{{ $fieldName }}" class="form-control" rows="3" @if ($field->is_required) required @endif>{{ old($fieldName, $fieldValue) }}</textarea>
        @elseif($field->field_type == 'select')
            <select name="{{ $fieldName }}" class="form-control" @if ($field->is_required) required @endif>
                <option value="">{{ translate('Select') }} {{ $field->name }}</option>
                @foreach ($field->options as $option)
                    <option value="{{ $option->option_value }}"
                        {{ old($fieldName, $fieldValue) == $option->option_value ? 'selected' : '' }}>
                        {{ $option->option_label }}
                    </option>
                @endforeach
            </select>
        @elseif($field->field_type == 'multiselect')
            <select name="{{ $fieldName }}[]" class="form-control js-select2-custom" multiple
                @if ($field->is_required) required @endif>
                @foreach ($field->options as $option)
                    @php
                        $selected = false;
                        if ($fieldValue) {
                            if (is_array($fieldValue)) {
                                $selected = in_array($option->option_value, $fieldValue);
                            } else {
                                $selected = $fieldValue == $option->option_value;
                            }
                        }
                        $oldValue = old($fieldName);
                        if ($oldValue && is_array($oldValue)) {
                            $selected = in_array($option->option_value, $oldValue);
                        }
                    @endphp
                    <option value="{{ $option->option_value }}" {{ $selected ? 'selected' : '' }}>
                        {{ $option->option_label }}
                    </option>
                @endforeach
            </select>
        @elseif($field->field_type == 'checkbox')
            <div class="checkbox-group border rounded p-3"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;">
                @foreach ($field->options as $option)
                    @php
                        $checked = false;
                        if ($fieldValue) {
                            if (is_array($fieldValue)) {
                                $checked = in_array($option->option_value, $fieldValue);
                            } else {
                                $checked = $fieldValue == $option->option_value;
                            }
                        }
                        $oldValue = old($fieldName);
                        if ($oldValue && is_array($oldValue)) {
                            $checked = in_array($option->option_value, $oldValue);
                        }
                    @endphp
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="{{ $fieldName }}[]"
                            value="{{ $option->option_value }}" id="{{ $fieldName }}_{{ $option->id }}"
                            {{ $checked ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $fieldName }}_{{ $option->id }}">
                            {{ $option->option_label }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endforeach

@props(['field'])

<div class="form-field">
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-300') }} mb-2">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @if($field->isBulkToggleable())
        <div class="mb-2 flex gap-2">
            <button type="button" class="text-sm text-primary-600 hover:text-primary-800" onclick="document.querySelectorAll('[name=\'{{ $field->getName() }}[]\']').forEach(cb => cb.checked = true)">
                Select All
            </button>
            <span class="text-gray-400">|</span>
            <button type="button" class="text-sm text-primary-600 hover:text-primary-800" onclick="document.querySelectorAll('[name=\'{{ $field->getName() }}[]\']').forEach(cb => cb.checked = false)">
                Deselect All
            </button>
        </div>
    @endif

    <div class="grid grid-cols-{{ $field->getColumns() }} gap-2">
        @foreach($field->getOptions() as $value => $label)
            <div class="flex items-start gap-2">
                <input
                    type="checkbox"
                    name="{{ $field->getName() }}[]"
                    id="{{ $field->getId() }}-{{ $value }}"
                    value="{{ $value }}"
                    @if(is_array($field->getDefault()) && in_array($value, $field->getDefault())) checked @endif
                    @if($field->isDisabled()) disabled @endif
                    class="{{ config('forms.styles.checkbox', 'rounded border-gray-300 dark:border-gray-600 text-primary-600 shadow-sm focus:ring-primary-500') }}"
                    {!! $field->getExtraAttributesString() !!}
                >
                <div>
                    <label for="{{ $field->getId() }}-{{ $value }}" class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $label }}
                    </label>
                    @if($field->getDescription($value))
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $field->getDescription($value) }}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

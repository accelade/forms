@props(['field'])

<div class="form-field">
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-300') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="mt-1">
        <select
            name="{{ $field->getName() }}{{ $field->isMultiple() ? '[]' : '' }}"
            id="{{ $field->getId() }}"
            @if($field->isRequired()) required @endif
            @if($field->isDisabled()) disabled @endif
            @if($field->isMultiple()) multiple @endif
            @if($field->hasAutofocus()) autofocus @endif
            class="{{ config('forms.styles.select', 'block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm') }}"
            {!! $field->getExtraAttributesString() !!}
        >
            @if($field->getEmptyOptionLabel() && !$field->isMultiple())
                <option value="">{{ $field->getEmptyOptionLabel() }}</option>
            @endif

            @foreach($field->getOptions() as $value => $label)
                <option
                    value="{{ $value }}"
                    @if($field->getDefault() !== null && (
                        ($field->isMultiple() && is_array($field->getDefault()) && in_array($value, $field->getDefault())) ||
                        (!$field->isMultiple() && $field->getDefault() == $value)
                    )) selected @endif
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>
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

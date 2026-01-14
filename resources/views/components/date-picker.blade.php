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
        <input
            type="date"
            name="{{ $field->getName() }}"
            id="{{ $field->getId() }}"
            @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @endif
            @if($field->getDefault()) value="{{ $field->getDefault() }}" @endif
            @if($field->isRequired()) required @endif
            @if($field->isDisabled()) disabled @endif
            @if($field->isReadonly()) readonly @endif
            @if($field->hasAutofocus()) autofocus @endif
            @if($field->getMinDate()) min="{{ $field->getMinDate() }}" @endif
            @if($field->getMaxDate()) max="{{ $field->getMaxDate() }}" @endif
            class="{{ config('forms.styles.input', 'block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm') }}"
            {!! $field->getExtraAttributesString() !!}
        >
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

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

    <div class="mt-1 {{ $field->getPrefix() || $field->getSuffix() ? 'flex rounded-lg shadow-sm' : '' }}">
        @if($field->getPrefix())
            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 sm:text-sm">
                {{ $field->getPrefix() }}
            </span>
        @endif

        <input
            type="{{ $field->getType() }}"
            name="{{ $field->getName() }}"
            id="{{ $field->getId() }}"
            @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @endif
            @if($field->getDefault() !== null) value="{{ $field->getDefault() }}" @endif
            @if($field->isRequired()) required @endif
            @if($field->isDisabled()) disabled @endif
            @if($field->isReadonly()) readonly @endif
            @if($field->hasAutofocus()) autofocus @endif
            @if($field->getMinValue() !== null) min="{{ $field->getMinValue() }}" @endif
            @if($field->getMaxValue() !== null) max="{{ $field->getMaxValue() }}" @endif
            @if($field->getMinLength() !== null) minlength="{{ $field->getMinLength() }}" @endif
            @if($field->getMaxLength() !== null) maxlength="{{ $field->getMaxLength() }}" @endif
            @if($field->getStep() !== null) step="{{ $field->getStep() }}" @endif
            @if($field->getInputMode()) inputmode="{{ $field->getInputMode() }}" @endif
            @if($field->getAutocomplete()) autocomplete="{{ $field->getAutocomplete() }}" @endif
            @if($field->getDatalist()) list="{{ $field->getDatalist() }}" @endif
            class="{{ config('forms.styles.input', 'block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm') }} {{ $field->getPrefix() ? 'rounded-l-none' : '' }} {{ $field->getSuffix() ? 'rounded-r-none' : '' }}"
            {!! $field->getExtraAttributesString() !!}
        >

        @if($field->getSuffix())
            <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 sm:text-sm">
                {{ $field->getSuffix() }}
            </span>
        @endif
    </div>

    @if($field->getDatalist() && count($field->getDatalistOptions()) > 0)
        <datalist id="{{ $field->getDatalist() }}">
            @foreach($field->getDatalistOptions() as $option)
                <option value="{{ $option }}">
            @endforeach
        </datalist>
    @endif

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

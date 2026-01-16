@props(['field'])

@php
    $hasPrefix = $field->getPrefix();
    $hasSuffix = $field->getSuffix();
    $hasAddons = $hasPrefix || $hasSuffix;
    $isDisabled = $field->isDisabled();

    // Container classes
    $containerClasses = $hasAddons
        ? config('forms.styles.input_wrapper', 'flex rounded-lg border border-gray-300 bg-white shadow-sm overflow-hidden focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800')
        : config('forms.styles.input_container', 'relative rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800');

    if ($isDisabled) {
        $containerClasses .= ' ' . config('forms.styles.input_container_disabled', 'bg-gray-50 dark:bg-gray-900 cursor-not-allowed');
    }

    // Input classes - transparent background
    $inputClasses = config('forms.styles.input', 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 placeholder-gray-400 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed dark:text-gray-100 dark:placeholder-gray-500 dark:disabled:text-gray-600');
@endphp

<div class="form-field text-input-field {{ config('forms.styles.field', 'mb-4') }}">
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $containerClasses }}">
        @if($hasPrefix)
            <span class="{{ config('forms.styles.prefix', 'inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border-e border-gray-300 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600') }}">
                {!! $field->getPrefix() !!}
            </span>
        @endif

        <input
            type="{{ $field->getType() }}"
            name="{{ $field->getName() }}"
            id="{{ $field->getId() }}"
            @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @endif
            @if($field->getDefault() !== null) value="{{ $field->getDefault() }}" @endif
            @if($field->isRequired()) required @endif
            @if($isDisabled) disabled @endif
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
            @if($field->getMask()) data-mask="{{ $field->getMask() }}" @endif
            @if($field->hasDate()) data-date="{{ json_encode($field->getDateOptions()) }}" @endif
            @if($field->hasTime()) data-time="{{ json_encode($field->getTimeOptions()) }}" @endif
            @if($field->isRange()) data-range @endif
            class="{{ $inputClasses }}"
            {!! $field->getExtraAttributesString() !!}
        >

        @if($hasSuffix)
            <span class="{{ config('forms.styles.suffix', 'inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border-s border-gray-300 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600') }}">
                {!! $field->getSuffix() !!}
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
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

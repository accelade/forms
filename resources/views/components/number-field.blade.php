@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $placeholder = $field->getPlaceholder();
    $min = $field->getMinValue();
    $max = $field->getMaxValue();
    $step = $field->getStep();
    $prefix = $field->getPrefix();
    $suffix = $field->getSuffix();

    // Container classes with proper background
    $containerClasses = 'flex rounded-lg border border-gray-300 bg-white shadow-sm overflow-hidden transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800';

    if ($isDisabled) {
        $containerClasses .= ' bg-gray-50 dark:bg-gray-900 opacity-50 cursor-not-allowed';
    }
@endphp

<div {{ $attributes->class(['form-field number-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $containerClasses }} number-input-wrapper">
        @if($prefix)
            <span class="number-input-prefix inline-flex items-center px-3 border-e border-gray-300 bg-gray-50 text-gray-500 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400">{{ $prefix }}</span>
        @endif

        <button
            type="button"
            class="number-decrement inline-flex items-center justify-center w-10 h-10 border-e border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition-colors duration-150 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 dark:focus:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            @if($isDisabled || $isReadOnly) disabled @endif
            aria-label="Decrease value"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
        </button>

        <input
            type="number"
            id="{{ $id }}"
            name="{{ $statePath }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($isDisabled) disabled @endif
            @if($isReadOnly) readonly @endif
            @if($isRequired) required @endif
            @if($min !== null) min="{{ $min }}" @endif
            @if($max !== null) max="{{ $max }}" @endif
            @if($step !== null) step="{{ $step }}" @endif
            {{ $attributes->whereStartsWith('wire:') }}
            class="number-input flex-1 min-w-[60px] text-center px-2 py-2 text-sm bg-transparent text-gray-900 placeholder-gray-400 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed dark:text-gray-100 dark:placeholder-gray-500"
        />

        <button
            type="button"
            class="number-increment inline-flex items-center justify-center w-10 h-10 border-s border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition-colors duration-150 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 dark:focus:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            @if($isDisabled || $isReadOnly) disabled @endif
            aria-label="Increase value"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>

        @if($suffix)
            <span class="number-input-suffix inline-flex items-center px-3 border-s border-gray-300 bg-gray-50 text-gray-500 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400">{{ $suffix }}</span>
        @endif
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">{{ $field->getHint() }}</p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $placeholder = $field->getPlaceholder() ?? 'Select date range';
    $isNative = $field->isNative();
    $isInline = $field->isInline();
    $flatpickrOptions = $field->getFlatpickrOptions();
    $minDate = $field->getMinDate();
    $maxDate = $field->getMaxDate();
    $startPlaceholder = $field->getStartDatePlaceholder() ?? 'Start date';
    $endPlaceholder = $field->getEndDatePlaceholder() ?? 'End date';

    // Container classes
    $containerClasses = config('forms.styles.input_container', 'relative rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800');

    if ($isDisabled) {
        $containerClasses .= ' ' . config('forms.styles.input_container_disabled', 'bg-gray-50 dark:bg-gray-900 cursor-not-allowed');
    }

    // Input classes
    $inputClasses = config('forms.styles.input', 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 placeholder-gray-400 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed dark:text-gray-100 dark:placeholder-gray-500 dark:disabled:text-gray-600');
@endphp

<div {{ $attributes->class(['form-field date-range-picker-field', config('forms.styles.field', 'mb-4')]) }}
    @if(!$isNative)
        data-flatpickr="{{ json_encode($flatpickrOptions) }}"
    @endif
>
    @if($field->getLabel())
        <label for="{{ $id }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="date-range-picker-wrapper {{ $isInline ? 'inline-block' : '' }}">
        @if($isNative)
            {{-- Native browser inputs (two separate date inputs) --}}
            <div class="flex items-center gap-2">
                <div class="{{ $containerClasses }} flex-1">
                    <input
                        type="date"
                        id="{{ $id }}-start"
                        name="{{ $statePath }}[start]"
                        placeholder="{{ $startPlaceholder }}"
                        @if($isDisabled) disabled @endif
                        @if($isReadOnly) readonly @endif
                        @if($isRequired) required @endif
                        @if($minDate) min="{{ $minDate }}" @endif
                        @if($maxDate) max="{{ $maxDate }}" @endif
                        {{ $attributes->whereStartsWith('wire:') }}
                        class="{{ $inputClasses }}"
                    />
                </div>
                <span class="date-range-separator text-gray-500 dark:text-gray-400 shrink-0">{{ $field->getSeparator() }}</span>
                <div class="{{ $containerClasses }} flex-1">
                    <input
                        type="date"
                        id="{{ $id }}-end"
                        name="{{ $statePath }}[end]"
                        placeholder="{{ $endPlaceholder }}"
                        @if($isDisabled) disabled @endif
                        @if($isReadOnly) readonly @endif
                        @if($minDate) min="{{ $minDate }}" @endif
                        @if($maxDate) max="{{ $maxDate }}" @endif
                        {{ $attributes->whereStartsWith('wire:') }}
                        class="{{ $inputClasses }}"
                    />
                </div>
            </div>
        @else
            {{-- Flatpickr enhanced input (single input for range mode) --}}
            <div class="{{ $containerClasses }} flex items-center">
                <input
                    type="text"
                    id="{{ $id }}"
                    name="{{ $statePath }}"
                    @if($field->getDefault()) value="{{ $field->getDefault() }}" @endif
                    placeholder="{{ $placeholder }}"
                    @if($isDisabled) disabled @endif
                    @if($isReadOnly) readonly @endif
                    @if($isRequired) required @endif
                    {{ $attributes->whereStartsWith('wire:') }}
                    class="date-range-picker-input flatpickr-input {{ $inputClasses }} pe-10"
                />
                @if(!$isInline)
                    <button type="button" class="date-range-picker-toggle absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300" tabindex="-1">
                        {{-- Calendar range icon --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                @endif
            </div>
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

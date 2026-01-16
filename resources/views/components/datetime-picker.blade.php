@props([
    'field',
])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $placeholder = $field->getPlaceholder();
    $format = $field->getFormat();
    $minDate = $field->getMinDate();
    $maxDate = $field->getMaxDate();
    $withSeconds = $field->hasSeconds();
    $isNative = $field->isNative();
    $isInline = $field->isInline();
    $flatpickrOptions = $field->getFlatpickrOptions();
@endphp

<div {{ $attributes->class(['form-field datetime-picker-field', config('forms.styles.field', 'mb-4')]) }}
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

    <div class="datetime-picker-wrapper relative {{ $isInline ? 'inline-block' : '' }}">
        @if($isNative)
            {{-- Native browser input --}}
            <input
                type="{{ $field->getNativeType() }}"
                id="{{ $id }}"
                name="{{ $statePath }}"
                @if($field->getDefault()) value="{{ $field->getDefault() }}" @endif
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($isDisabled) disabled @endif
                @if($isReadOnly) readonly @endif
                @if($isRequired) required @endif
                @if($minDate) min="{{ $minDate }}" @endif
                @if($maxDate) max="{{ $maxDate }}" @endif
                @if($withSeconds) step="1" @endif
                {{ $attributes->whereStartsWith('wire:') }}
                class="datetime-picker-input {{ config('forms.styles.input', 'block w-full px-3 py-2 text-sm rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 focus:outline-none disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:disabled:bg-gray-900 dark:disabled:text-gray-600') }}"
            />
        @else
            {{-- Flatpickr enhanced input --}}
            <div class="relative">
                <input
                    type="text"
                    id="{{ $id }}"
                    name="{{ $statePath }}"
                    @if($field->getDefault()) value="{{ $field->getDefault() }}" @endif
                    @if($placeholder) placeholder="{{ $placeholder }}" @endif
                    @if($isDisabled) disabled @endif
                    @if($isReadOnly) readonly @endif
                    @if($isRequired) required @endif
                    {{ $attributes->whereStartsWith('wire:') }}
                    class="datetime-picker-input flatpickr-input {{ config('forms.styles.input', 'block w-full px-3 py-2 text-sm rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 focus:outline-none disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:disabled:bg-gray-900 dark:disabled:text-gray-600') }} pe-10"
                />
                @if(!$isInline)
                    <button type="button" class="datetime-picker-toggle absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300" tabindex="-1">
                        @if($field->hasTime() && !$field->hasDate())
                            {{-- Clock icon for time-only --}}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            {{-- Calendar icon for date/datetime --}}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        @endif
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

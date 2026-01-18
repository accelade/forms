@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $placeholder = $field->getPlaceholder();
    $isNative = $field->isNative();
    $isInline = $field->isInline();
    $flatpickrOptions = $field->getFlatpickrOptions();

    // Container classes
    $containerClasses = config('forms.styles.input_container', 'relative rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800');

    if ($isDisabled) {
        $containerClasses .= ' ' . config('forms.styles.input_container_disabled', 'bg-gray-50 dark:bg-gray-900 cursor-not-allowed');
    }

    // Input classes
    $inputClasses = config('forms.styles.input', 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 placeholder-gray-400 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed dark:text-gray-100 dark:placeholder-gray-500 dark:disabled:text-gray-600');
@endphp

<div {{ $attributes->class(['form-field date-picker-field', config('forms.styles.field', 'mb-4')]) }}
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

    <div class="date-picker-wrapper {{ $isInline ? 'inline-block' : '' }}">
        @if($isNative)
            {{-- Native browser input --}}
            <div class="{{ $containerClasses }}">
                <input
                    type="date"
                    id="{{ $id }}"
                    name="{{ $statePath }}"
                    @if($field->getDefault()) value="{{ $field->getDefault() }}" @endif
                    @if($placeholder) placeholder="{{ $placeholder }}" @endif
                    @if($isDisabled) disabled @endif
                    @if($isReadOnly) readonly @endif
                    @if($isRequired) required @endif
                    @if($field->getMinDate()) min="{{ $field->getMinDate() }}" @endif
                    @if($field->getMaxDate()) max="{{ $field->getMaxDate() }}" @endif
                    {{ $attributes->whereStartsWith('wire:') }}
                    class="{{ $inputClasses }}"
                    {!! $field->getExtraAttributesString() !!}
                />
            </div>
        @else
            {{-- Flatpickr enhanced input --}}
            <div class="{{ $containerClasses }} flex items-center">
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
                    class="date-picker-input flatpickr-input {{ $inputClasses }} pe-10"
                    {!! $field->getExtraAttributesString() !!}
                />
                @if(!$isInline)
                    <button type="button" class="date-picker-toggle absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300" tabindex="-1">
                        <x-accelade::icon name="heroicon-o-calendar" size="md" :showFallback="false" />
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

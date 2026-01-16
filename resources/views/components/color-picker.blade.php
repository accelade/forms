@props(['field'])

@php
    $id = $field->getId();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $swatches = $field->getSwatches();
@endphp

<div {{ $attributes->class(['form-field color-picker-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="color-picker-wrapper flex items-center gap-3">
        <div class="relative rounded-lg border border-gray-300 bg-white shadow-sm overflow-hidden dark:border-gray-600 dark:bg-gray-800 {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}">
            <input
                type="color"
                id="{{ $id }}"
                name="{{ $statePath }}"
                @if($isDisabled) disabled @endif
                @if($isReadOnly) readonly @endif
                @if($isRequired) required @endif
                {{ $attributes->whereStartsWith('wire:') }}
                class="color-input h-10 w-14 cursor-pointer border-0 p-0 bg-transparent"
            />
        </div>

        @if(count($swatches) > 0)
            <div class="color-swatches flex flex-wrap gap-1.5">
                @foreach($swatches as $swatch)
                    <button
                        type="button"
                        class="color-swatch w-7 h-7 rounded-md border border-gray-300 shadow-sm cursor-pointer transition-transform duration-150 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:border-gray-600 dark:focus:ring-offset-gray-900"
                        style="background-color: {{ $swatch }};"
                        data-color="{{ $swatch }}"
                        title="{{ $swatch }}"
                    ></button>
                @endforeach
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

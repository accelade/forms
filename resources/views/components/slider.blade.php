@props(['field'])

@php
    $id = $field->getId();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $min = $field->getMinValue() ?? 0;
    $max = $field->getMaxValue() ?? 100;
    $step = $field->getStep() ?? 1;
    $marks = $field->getMarks();
    $showValue = $field->getShowValue();
    $color = $field->getSliderColor();
@endphp

<div {{ $attributes->class(['form-field slider-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div
        class="slider-wrapper flex items-center gap-3"
        @if($color) style="--slider-color: {{ $color }};" @endif
    >
        @if($showValue)
            <span class="slider-value slider-value-min text-sm text-gray-500 dark:text-gray-400 tabular-nums">{{ $min }}</span>
        @endif

        <div class="slider-track flex-1">
            <input
                type="range"
                id="{{ $id }}"
                name="{{ $statePath }}"
                min="{{ $min }}"
                max="{{ $max }}"
                step="{{ $step }}"
                @if($isDisabled) disabled @endif
                @if($isReadOnly) readonly @endif
                @if($isRequired) required @endif
                {{ $attributes->whereStartsWith('wire:') }}
                class="slider-input w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-600 dark:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
            />

            @if(count($marks) > 0)
                <datalist id="{{ $id }}-marks">
                    @foreach($marks as $value => $label)
                        <option value="{{ $value }}" label="{{ $label }}"></option>
                    @endforeach
                </datalist>
            @endif
        </div>

        @if($showValue)
            <span class="slider-value slider-value-current text-sm font-medium text-gray-700 dark:text-gray-300 tabular-nums min-w-[2rem] text-end">{{ $min }}</span>
        @endif
    </div>

    @if(count($marks) > 0)
        <div class="slider-marks relative mt-1 text-xs text-gray-500 dark:text-gray-400">
            @foreach($marks as $value => $label)
                <span class="slider-mark absolute transform -translate-x-1/2" style="left: {{ (($value - $min) / ($max - $min)) * 100 }}%;">
                    {{ $label }}
                </span>
            @endforeach
        </div>
    @endif

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

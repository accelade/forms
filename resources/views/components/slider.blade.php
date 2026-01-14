@props([
    'field',
])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadOnly();
    $isRequired = $field->isRequired();
    $min = $field->getMin() ?? 0;
    $max = $field->getMax() ?? 100;
    $step = $field->getStep() ?? 1;
    $marks = $field->getMarks();
    $showValue = $field->getShowValue();
    $isRange = $field->isRange();
    $color = $field->getSliderColor();
@endphp

<div {{ $attributes->class(['form-field slider-field']) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div
        class="slider-wrapper"
        @if($color) style="--slider-color: {{ $color }};" @endif
    >
        @if($showValue)
            <span class="slider-value slider-value-min">{{ $min }}</span>
        @endif

        <div class="slider-track">
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
                class="slider-input"
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
            <span class="slider-value slider-value-current">{{ $min }}</span>
        @endif
    </div>

    @if(count($marks) > 0)
        <div class="slider-marks">
            @foreach($marks as $value => $label)
                <span class="slider-mark" style="left: {{ (($value - $min) / ($max - $min)) * 100 }}%;">
                    {{ $label }}
                </span>
            @endforeach
        </div>
    @endif

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

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
    $placeholder = $field->getPlaceholder();
    $min = $field->getMin();
    $max = $field->getMax();
    $step = $field->getStep();
    $prefix = $field->getPrefix();
    $suffix = $field->getSuffix();
@endphp

<div {{ $attributes->class(['form-field number-field']) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="number-input-wrapper">
        @if($prefix)
            <span class="number-input-prefix">{{ $prefix }}</span>
        @endif

        <button type="button" class="number-decrement" @if($isDisabled || $isReadOnly) disabled @endif>−</button>

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
            class="form-input number-input"
        />

        <button type="button" class="number-increment" @if($isDisabled || $isReadOnly) disabled @endif>+</button>

        @if($suffix)
            <span class="number-input-suffix">{{ $suffix }}</span>
        @endif
    </div>

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

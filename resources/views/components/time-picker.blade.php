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
    $format = $field->getFormat();
    $withSeconds = $field->hasSeconds();
@endphp

<div {{ $attributes->class(['form-field time-picker-field']) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <input
        type="time"
        id="{{ $id }}"
        name="{{ $statePath }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($isDisabled) disabled @endif
        @if($isReadOnly) readonly @endif
        @if($isRequired) required @endif
        @if($withSeconds) step="1" @endif
        {{ $attributes->whereStartsWith('wire:') }}
        class="form-input time-input"
    />

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

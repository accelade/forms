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
    $minDate = $field->getMinDate();
    $maxDate = $field->getMaxDate();
    $withSeconds = $field->hasSeconds();
@endphp

<div {{ $attributes->class(['form-field datetime-picker-field']) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <input
        type="datetime-local"
        id="{{ $id }}"
        name="{{ $statePath }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($isDisabled) disabled @endif
        @if($isReadOnly) readonly @endif
        @if($isRequired) required @endif
        @if($minDate) min="{{ $minDate }}" @endif
        @if($maxDate) max="{{ $maxDate }}" @endif
        @if($withSeconds) step="1" @endif
        {{ $attributes->whereStartsWith('wire:') }}
        class="form-input datetime-input"
    />

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

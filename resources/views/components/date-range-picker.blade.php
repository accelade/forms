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
    $minDate = $field->getMinDate();
    $maxDate = $field->getMaxDate();
    $numberOfMonths = $field->getNumberOfMonths();
    $startPlaceholder = $field->getStartDatePlaceholder() ?? 'Start date';
    $endPlaceholder = $field->getEndDatePlaceholder() ?? 'End date';
@endphp

<div {{ $attributes->class(['form-field date-range-picker-field']) }}>
    @if($field->getLabel())
        <label for="{{ $id }}-start" class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="date-range-inputs">
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
            class="form-input date-input date-start"
        />
        <span class="date-range-separator">to</span>
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
            class="form-input date-input date-end"
        />
    </div>

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

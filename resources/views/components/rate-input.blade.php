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
    $maxRating = $field->getMaxRating();
    $allowHalf = $field->getAllowHalf();
    $icon = $field->getRateIcon();
    $color = $field->getRateColor();
    $showValue = $field->getShowValue();
    $clearable = $field->isClearable();
@endphp

<div {{ $attributes->class(['form-field rate-input-field']) }}>
    @if($field->getLabel())
        <label class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div
        class="rate-input-wrapper"
        data-max="{{ $maxRating }}"
        data-allow-half="{{ $allowHalf ? 'true' : 'false' }}"
        data-clearable="{{ $clearable ? 'true' : 'false' }}"
        @if($color) style="--rate-color: {{ $color }};" @endif
        role="group"
        aria-label="Rating"
    >
        @for($i = 1; $i <= $maxRating; $i++)
            <button
                type="button"
                class="rate-item"
                data-value="{{ $i }}"
                @if($isDisabled || $isReadOnly) disabled @endif
                aria-label="Rate {{ $i }} out of {{ $maxRating }}"
            >
                <span class="rate-icon rate-icon-empty">☆</span>
                <span class="rate-icon rate-icon-filled">★</span>
                @if($allowHalf)
                    <span class="rate-icon rate-icon-half">★</span>
                @endif
            </button>
        @endfor

        @if($showValue)
            <span class="rate-value">0</span>
        @endif

        <input type="hidden" name="{{ $statePath }}" class="rate-input-value" @if($isRequired) required @endif />
    </div>

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

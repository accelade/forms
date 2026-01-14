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
    $swatches = $field->getSwatches();
@endphp

<div {{ $attributes->class(['form-field color-picker-field']) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="color-picker-wrapper">
        <input
            type="color"
            id="{{ $id }}"
            name="{{ $statePath }}"
            @if($isDisabled) disabled @endif
            @if($isReadOnly) readonly @endif
            @if($isRequired) required @endif
            {{ $attributes->whereStartsWith('wire:') }}
            class="form-input color-input"
        />

        @if(count($swatches) > 0)
            <div class="color-swatches">
                @foreach($swatches as $swatch)
                    <button
                        type="button"
                        class="color-swatch"
                        style="background-color: {{ $swatch }};"
                        data-color="{{ $swatch }}"
                        title="{{ $swatch }}"
                    ></button>
                @endforeach
            </div>
        @endif
    </div>

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

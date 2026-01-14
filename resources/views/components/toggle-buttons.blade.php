@props([
    'field',
])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isRequired = $field->isRequired();
    $options = $field->getOptions();
    $isGrouped = $field->isGrouped();
@endphp

<div {{ $attributes->class(['form-field toggle-buttons-field']) }}>
    @if($field->getLabel())
        <label class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="toggle-buttons-wrapper @if($isGrouped) is-grouped @endif" role="group">
        @foreach($options as $value => $label)
            @php
                $optionId = $id . '-' . $value;
                $icon = $field->getIcon($value);
                $color = $field->getColor($value);
            @endphp
            <label
                for="{{ $optionId }}"
                class="toggle-button"
                @if($color) data-color="{{ $color }}" style="--toggle-color: {{ $color }};" @endif
            >
                <input
                    type="radio"
                    id="{{ $optionId }}"
                    name="{{ $statePath }}"
                    value="{{ $value }}"
                    @if($isDisabled) disabled @endif
                    @if($isRequired) required @endif
                    {{ $attributes->whereStartsWith('wire:') }}
                    class="toggle-button-input"
                />
                <span class="toggle-button-content">
                    @if($icon)
                        <span class="toggle-button-icon">{!! $icon !!}</span>
                    @endif
                    <span class="toggle-button-label">{{ $label }}</span>
                </span>
            </label>
        @endforeach
    </div>

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

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
    $length = $field->getLength();
    $mask = $field->getMask();
    $otp = $field->getOtp();
    $type = $field->getPinType();
    $align = $field->getAlign();

    $inputType = $mask ? 'password' : 'text';
    $inputMode = $type === 'numeric' ? 'numeric' : 'text';
    $pattern = match($type) {
        'numeric' => '[0-9]',
        'alpha' => '[a-zA-Z]',
        default => '[a-zA-Z0-9]',
    };
@endphp

<div {{ $attributes->class(['form-field pin-input-field', "align-{$align}"]) }}>
    @if($field->getLabel())
        <label class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="pin-input-wrapper" data-length="{{ $length }}">
        @for($i = 0; $i < $length; $i++)
            <input
                type="{{ $inputType }}"
                inputmode="{{ $inputMode }}"
                pattern="{{ $pattern }}"
                maxlength="1"
                class="pin-input-digit"
                data-index="{{ $i }}"
                @if($isDisabled) disabled @endif
                @if($isReadOnly) readonly @endif
                @if($otp) autocomplete="one-time-code" @endif
                aria-label="Digit {{ $i + 1 }}"
            />
        @endfor
        <input type="hidden" name="{{ $statePath }}" class="pin-input-value" @if($isRequired) required @endif />
    </div>

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

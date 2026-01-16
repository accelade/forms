@props(['field'])

@php
    $id = $field->getId();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
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

    $alignClass = match($align) {
        'center' => 'justify-center',
        'end', 'right' => 'justify-end',
        default => 'justify-start',
    };
@endphp

<div {{ $attributes->class(['form-field pin-input-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="pin-input-wrapper flex gap-2 {{ $alignClass }}" data-length="{{ $length }}">
        @for($i = 0; $i < $length; $i++)
            <input
                type="{{ $inputType }}"
                inputmode="{{ $inputMode }}"
                pattern="{{ $pattern }}"
                maxlength="1"
                class="pin-input-digit w-12 h-12 text-center text-lg font-semibold rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 focus:outline-none disabled:bg-gray-50 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:disabled:bg-gray-900"
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

@props([
    'field',
])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
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
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-300') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div
        class="mt-1 rate-input-wrapper flex items-center gap-1"
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
                class="rate-item text-2xl text-gray-300 dark:text-gray-600 hover:text-yellow-400 transition-colors"
                data-value="{{ $i }}"
                @if($isDisabled || $isReadOnly) disabled @endif
                aria-label="Rate {{ $i }} out of {{ $maxRating }}"
            >
                <span class="rate-icon rate-icon-empty">☆</span>
                <span class="rate-icon rate-icon-filled hidden">★</span>
                @if($allowHalf)
                    <span class="rate-icon rate-icon-half hidden">★</span>
                @endif
            </button>
        @endfor

        @if($showValue)
            <span class="rate-value ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">0</span>
        @endif

        <input type="hidden" name="{{ $statePath }}" class="rate-input-value" @if($isRequired) required @endif />
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1') }}">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1') }}">{{ $field->getHint() }}</p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

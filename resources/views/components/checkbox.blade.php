@props(['field'])

@php
    $isDisabled = $field->isDisabled();
    $isInline = $field->isInline();
    $isChecked = (bool) $field->getDefault();
@endphp

<div class="form-field checkbox-field {{ config('forms.styles.field', 'mb-4') }}">
    <div class="flex items-{{ $isInline ? 'center' : 'start' }} gap-3">
        @if(!$isInline && $field->getLabel())
            <label for="{{ $field->getId() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
                {{ $field->getLabel() }}
                @if($field->isRequired())
                    <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
                @endif
            </label>
        @endif

        {{-- Custom checkbox wrapper --}}
        <div class="checkbox-wrapper relative inline-flex items-center">
            {{-- Hidden input for unchecked value --}}
            @if($field->getUncheckedValue() !== false)
                <input type="hidden" name="{{ $field->getName() }}" value="{{ $field->getUncheckedValue() }}">
            @endif

            {{-- Actual hidden checkbox --}}
            <input
                type="checkbox"
                name="{{ $field->getName() }}"
                id="{{ $field->getId() }}"
                value="{{ $field->getCheckedValue() }}"
                @if($isChecked) checked @endif
                @if($field->isRequired()) required @endif
                @if($isDisabled) disabled @endif
                class="checkbox-input peer sr-only"
                {!! $field->getExtraAttributesString() !!}
            >

            {{-- Custom checkbox visual --}}
            <label
                for="{{ $field->getId() }}"
                class="checkbox-box flex h-5 w-5 items-center justify-center rounded border-2 border-gray-300 bg-white shadow-sm transition-all duration-150 cursor-pointer
                       peer-checked:border-primary-600 peer-checked:bg-primary-600
                       peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500/20 peer-focus-visible:ring-offset-2
                       peer-disabled:cursor-not-allowed peer-disabled:bg-gray-100 peer-disabled:border-gray-200
                       dark:border-gray-600 dark:bg-gray-800
                       dark:peer-checked:border-primary-500 dark:peer-checked:bg-primary-500
                       dark:peer-disabled:bg-gray-900 dark:peer-disabled:border-gray-700
                       dark:peer-focus-visible:ring-offset-gray-900"
            >
                {{-- Checkmark icon - hidden by default, shown when checked via CSS --}}
                <svg
                    class="checkbox-icon h-3 w-3 text-white"
                    viewBox="0 0 12 12"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <polyline points="2 6 5 9 10 3"></polyline>
                </svg>
            </label>
        </div>

        @if($isInline && $field->getLabel())
            <label for="{{ $field->getId() }}" class="text-sm text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                {{ $field->getLabel() }}
                @if($field->isRequired())
                    <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
                @endif
            </label>
        @endif
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }} {{ $isInline ? 'ms-8' : '' }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }} {{ $isInline ? 'ms-8' : '' }}">
            {{ $message }}
        </p>
    @enderror
</div>

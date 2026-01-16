@props(['field'])

@php
    $isDisabled = $field->isDisabled();

    // Container classes
    $containerClasses = config('forms.styles.textarea_container', 'relative rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800');

    if ($isDisabled) {
        $containerClasses .= ' ' . config('forms.styles.input_container_disabled', 'bg-gray-50 dark:bg-gray-900 cursor-not-allowed');
    }

    // Textarea classes - transparent background
    $textareaClasses = config('forms.styles.textarea', 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 placeholder-gray-400 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed resize-y dark:text-gray-100 dark:placeholder-gray-500 dark:disabled:text-gray-600');
@endphp

<div class="form-field textarea-field {{ config('forms.styles.field', 'mb-4') }}">
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $containerClasses }}">
        <textarea
            name="{{ $field->getName() }}"
            id="{{ $field->getId() }}"
            rows="{{ $field->getRows() }}"
            cols="{{ $field->getCols() }}"
            @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @endif
            @if($field->isRequired()) required @endif
            @if($isDisabled) disabled @endif
            @if($field->isReadonly()) readonly @endif
            @if($field->hasAutofocus()) autofocus @endif
            @if($field->getMinLength() !== null) minlength="{{ $field->getMinLength() }}" @endif
            @if($field->getMaxLength() !== null) maxlength="{{ $field->getMaxLength() }}" @endif
            @if($field->hasAutosize()) data-autosize @endif
            class="{{ $textareaClasses }}"
            {!! $field->getExtraAttributesString() !!}
        >{{ $field->getDefault() }}</textarea>
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

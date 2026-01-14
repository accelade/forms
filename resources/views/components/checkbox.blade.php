@props(['field'])

<div class="form-field">
    <div class="flex items-{{ $field->isInline() ? 'center' : 'start' }} gap-3">
        @if(!$field->isInline() && $field->getLabel())
            <label for="{{ $field->getId() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-300') }}">
                {{ $field->getLabel() }}
                @if($field->isRequired())
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif

        <input
            type="checkbox"
            name="{{ $field->getName() }}"
            id="{{ $field->getId() }}"
            value="{{ $field->getCheckedValue() }}"
            @if($field->getDefault()) checked @endif
            @if($field->isRequired()) required @endif
            @if($field->isDisabled()) disabled @endif
            class="{{ config('forms.styles.checkbox', 'rounded border-gray-300 dark:border-gray-600 text-primary-600 shadow-sm focus:ring-primary-500') }}"
            {!! $field->getExtraAttributesString() !!}
        >

        @if($field->isInline() && $field->getLabel())
            <label for="{{ $field->getId() }}" class="text-sm text-gray-700 dark:text-gray-300">
                {{ $field->getLabel() }}
                @if($field->isRequired())
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1') }} {{ $field->isInline() ? 'ml-7' : '' }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }} {{ $field->isInline() ? 'ml-7' : '' }}">
            {{ $message }}
        </p>
    @enderror
</div>

@props(['field'])

@php
    $id = $field->getId();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $placeholder = $field->getPlaceholder();
    $suggestions = $field->getSuggestions();
    $separator = $field->getSeparator();
    $maxTags = $field->getMaxTags();
    $color = $field->getColor();
    $isReorderable = $field->isReorderable();
    $tagPrefix = $field->getTagPrefix();
    $tagSuffix = $field->getTagSuffix();

    // Determine color class
    $colorClass = 'tags-color-primary';
    if ($color && in_array($color, ['danger', 'success', 'warning', 'info', 'primary', 'gray'])) {
        $colorClass = 'tags-color-' . $color;
    }
@endphp

<div {{ $attributes->class(['form-field tags-input-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div
        class="tags-input-wrapper {{ $colorClass }} {{ $isDisabled ? 'is-disabled' : '' }} {{ $isReorderable ? 'is-reorderable' : '' }}"
        data-separator="{{ $separator }}"
        @if($maxTags) data-max-tags="{{ $maxTags }}" @endif
        @if($tagPrefix) data-tag-prefix="{{ $tagPrefix }}" @endif
        @if($tagSuffix) data-tag-suffix="{{ $tagSuffix }}" @endif
        @if($isReorderable) data-reorderable="true" @endif
    >
        <div class="tags-container"></div>
        <input
            type="text"
            id="{{ $id }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($isDisabled) disabled @endif
            @if($isReadOnly) readonly @endif
            class="tags-text-input"
            autocomplete="off"
            @if(count($suggestions) > 0) list="{{ $id }}-suggestions" @endif
        />
        <input type="hidden" name="{{ $statePath }}" class="tags-value" />
    </div>

    @if(count($suggestions) > 0)
        <datalist id="{{ $id }}-suggestions">
            @foreach($suggestions as $suggestion)
                <option value="{{ $suggestion }}">
            @endforeach
        </datalist>
    @endif

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

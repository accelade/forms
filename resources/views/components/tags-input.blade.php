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
    $placeholder = $field->getPlaceholder();
    $suggestions = $field->getSuggestions();
    $separator = $field->getSeparator();
    $maxTags = $field->getMaxTags();
@endphp

<div {{ $attributes->class(['form-field tags-input-field']) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="tags-input-wrapper" data-separator="{{ $separator }}" @if($maxTags) data-max-tags="{{ $maxTags }}" @endif>
        <div class="tags-container"></div>
        <input
            type="text"
            id="{{ $id }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($isDisabled) disabled @endif
            @if($isReadOnly) readonly @endif
            class="tags-input"
            list="{{ $id }}-suggestions"
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
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

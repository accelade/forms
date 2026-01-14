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
    $toolbarButtons = $field->getToolbarButtons();
    $hasMedia = $field->hasMedia();
    $hasTables = $field->hasTables();
    $maxLength = $field->getMaxLength();
@endphp

<div {{ $attributes->class(['form-field rich-editor-field']) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="rich-editor-wrapper">
        <div class="rich-editor-toolbar">
            @foreach($toolbarButtons as $button)
                <button type="button" class="toolbar-button" data-action="{{ $button }}" title="{{ ucfirst($button) }}">
                    {{ $button }}
                </button>
            @endforeach
            @if($hasMedia)
                <button type="button" class="toolbar-button" data-action="media" title="Insert Media">
                    media
                </button>
            @endif
            @if($hasTables)
                <button type="button" class="toolbar-button" data-action="table" title="Insert Table">
                    table
                </button>
            @endif
        </div>

        <div
            class="rich-editor-content"
            id="{{ $id }}"
            contenteditable="{{ $isDisabled || $isReadOnly ? 'false' : 'true' }}"
            @if($placeholder) data-placeholder="{{ $placeholder }}" @endif
            @if($maxLength) data-max-length="{{ $maxLength }}" @endif
        ></div>

        <input type="hidden" name="{{ $statePath }}" class="rich-editor-value" />
    </div>

    @if($maxLength)
        <div class="rich-editor-counter">
            <span class="current-length">0</span> / {{ $maxLength }}
        </div>
    @endif

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

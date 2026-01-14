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
    $hasPreview = $field->hasPreview();
    $maxLength = $field->getMaxLength();
@endphp

<div {{ $attributes->class(['form-field markdown-editor-field']) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="markdown-editor-wrapper">
        <div class="markdown-editor-toolbar">
            @foreach($toolbarButtons as $button)
                <button type="button" class="toolbar-button" data-action="{{ $button }}" title="{{ ucfirst($button) }}">
                    {{ $button }}
                </button>
            @endforeach
            @if($hasPreview)
                <button type="button" class="toolbar-button toolbar-preview-toggle" data-action="preview" title="Toggle Preview">
                    preview
                </button>
            @endif
        </div>

        <div class="markdown-editor-panels">
            <textarea
                id="{{ $id }}"
                name="{{ $statePath }}"
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($isDisabled) disabled @endif
                @if($isReadOnly) readonly @endif
                @if($isRequired) required @endif
                @if($maxLength) maxlength="{{ $maxLength }}" @endif
                {{ $attributes->whereStartsWith('wire:') }}
                class="markdown-editor-input"
            ></textarea>

            @if($hasPreview)
                <div class="markdown-editor-preview" style="display: none;"></div>
            @endif
        </div>
    </div>

    @if($maxLength)
        <div class="markdown-editor-counter">
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

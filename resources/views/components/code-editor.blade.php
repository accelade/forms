@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $config = $field->getConfig();
    $languageLabel = $field->getLanguageLabel();
    $defaultValue = $field->getDefault() ?? '';
@endphp

<div {{ $attributes->class(['form-field code-editor-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-danger-600 dark:text-danger-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    {{-- Editor Header with Language Badge --}}
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <polyline points="16 18 22 12 16 6" />
                <polyline points="8 6 2 12 8 18" />
            </svg>
            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $languageLabel }}</span>
        </div>
        @if($field->hasLineNumbers())
            <span class="text-xs text-gray-400 dark:text-gray-500">Line numbers enabled</span>
        @endif
    </div>

    {{-- Code Editor Container --}}
    <div
        class="code-editor-container relative rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
        data-code-editor
        data-code-editor-id="{{ $id }}"
        data-code-editor-config='@json($config)'
        style="min-height: {{ $config['minHeight'] }}px;{{ $config['maxHeight'] ? ' max-height: ' . $config['maxHeight'] . 'px;' : '' }}"
    >
        {{-- Editor Mount Point --}}
        <div class="code-editor-mount" id="{{ $id }}-editor"></div>

        {{-- Fallback Textarea (shown before JS initializes or if JS fails) --}}
        <textarea
            name="{{ $name }}"
            id="{{ $id }}"
            class="code-editor-fallback w-full h-full p-3 font-mono text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-0 focus:ring-0 focus:outline-none resize-none"
            style="min-height: {{ $config['minHeight'] }}px;{{ $config['maxHeight'] ? ' max-height: ' . $config['maxHeight'] . 'px;' : '' }}"
            @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @endif
            @if($isRequired) required @endif
            @if($isDisabled) disabled @endif
            @if($isReadOnly) readonly @endif
            @if($field->hasAutofocus()) autofocus @endif
            {!! $field->getExtraAttributesString() !!}
        >{{ $defaultValue }}</textarea>
    </div>

    {{-- Helper Text --}}
    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'mt-2 text-sm text-gray-500 dark:text-gray-400') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @if($field->getHint())
        <p class="{{ config('forms.styles.hint', 'mt-2 text-sm text-gray-500 dark:text-gray-400') }}">
            {{ $field->getHint() }}
        </p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'mt-2 text-sm text-danger-600 dark:text-danger-400') }}">
            {{ $message }}
        </p>
    @enderror
</div>

<style>
/* CodeMirror Editor Styles */
.code-editor-container .cm-editor {
    height: auto;
    font-size: 14px;
    background: transparent;
}

.code-editor-container .cm-scroller {
    overflow: auto;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
}

.code-editor-container .cm-content {
    padding: 12px 0;
}

.code-editor-container .cm-line {
    padding: 0 12px;
}

/* Light theme styling */
.code-editor-container:not(.dark-theme) .cm-editor {
    background-color: #ffffff;
}

.code-editor-container:not(.dark-theme) .cm-gutters {
    background-color: #f3f4f6;
    border-right: 1px solid #e5e7eb;
    color: #9ca3af;
}

.code-editor-container:not(.dark-theme) .cm-activeLineGutter {
    background-color: #e5e7eb;
}

.code-editor-container:not(.dark-theme) .cm-activeLine {
    background-color: rgba(0, 0, 0, 0.04);
}

/* Dark theme styling */
.code-editor-container.dark-theme .cm-editor {
    background-color: #1f2937;
}

.code-editor-container.dark-theme .cm-gutters {
    background-color: #111827;
    border-right: 1px solid #374151;
    color: #6b7280;
}

.code-editor-container.dark-theme .cm-activeLineGutter {
    background-color: #1f2937;
}

.code-editor-container.dark-theme .cm-activeLine {
    background-color: rgba(255, 255, 255, 0.04);
}

/* Hide fallback when CodeMirror is initialized */
.code-editor-container[data-initialized="true"] .code-editor-fallback {
    display: none;
}

/* Focus styles */
.code-editor-container .cm-editor.cm-focused {
    outline: none;
}
</style>

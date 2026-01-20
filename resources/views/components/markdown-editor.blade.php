@props([
    'field' => null,
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => 'Write your markdown here...',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'direction' => 'ltr',
    'maxLength' => null,
    'showCharacterCount' => null,
    'toolbarButtons' => null,
    'preview' => true,
])

@php
    // Support both Field object mode and direct props mode
    if ($field) {
        $id = $field->getId();
        $name = $field->getName();
        $label = $field->getLabel();
        $hint = $field->getHint();
        $required = $field->isRequired();
        $disabled = $field->isDisabled();
        $readonly = $field->isReadOnly();
        $direction = $field->getDirection();
        $maxLength = $field->getMaxLength();
        $showCharacterCount = $field->shouldShowCharacterCount();
        $groupedTools = $field->getGroupedTools();
        $config = $field->getConfig();
        $placeholder = $field->getPlaceholder() ?? 'Write your markdown here...';
    } else {
        $id = $id ?? $name;

        // Default toolbar buttons (Filament-style grouped)
        $defaultToolbarButtons = [
            ['bold', 'italic', 'strike', 'link'],
            ['heading'],
            ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
            ['table', 'attachFiles'],
            ['undo', 'redo'],
        ];

        $groupedTools = $toolbarButtons ?? $defaultToolbarButtons;

        if ($showCharacterCount === null) {
            $showCharacterCount = $maxLength !== null;
        }

        $config = [
            'preview' => $preview,
            'maxLength' => $maxLength,
            'showCharacterCount' => $showCharacterCount,
            'direction' => $direction,
            'fileAttachments' => [
                'enabled' => true,
                'disk' => 'public',
                'directory' => 'attachments',
                'visibility' => 'public',
                'maxSize' => 12288,
                'acceptedTypes' => ['image/png', 'image/jpeg', 'image/gif', 'image/webp'],
            ],
        ];
    }

    $isRtl = $direction === 'rtl';
    $hasPreview = $config['preview'] ?? true;

    // Tool icons mapping
    $toolIcons = [
        'bold' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg>',
        'italic' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>',
        'strike' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4H9a3 3 0 0 0-2.83 4"/><path d="M14 12a4 4 0 0 1 0 8H6"/><line x1="4" y1="12" x2="20" y2="12"/></svg>',
        'link' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
        'heading' => '<span class="text-[11px] font-bold tracking-tight">H</span>',
        'h1' => '<span class="text-[11px] font-bold tracking-tight">H1</span>',
        'h2' => '<span class="text-[11px] font-bold tracking-tight">H2</span>',
        'h3' => '<span class="text-[11px] font-bold tracking-tight">H3</span>',
        'blockquote' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>',
        'codeBlock' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
        'bulletList' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="4" cy="6" r="1" fill="currentColor"/><circle cx="4" cy="12" r="1" fill="currentColor"/><circle cx="4" cy="18" r="1" fill="currentColor"/></svg>',
        'orderedList' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>',
        'table' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>',
        'attachFiles' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
        'undo' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>',
        'redo' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7v6h-6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3l3 2.7"/></svg>',
    ];
@endphp

<div class="markdown-editor-field space-y-1.5" dir="{{ $direction }}">
    {{-- Label --}}
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">
            {{ $label }}
            @if($required)
                <span class="text-red-500 {{ $isRtl ? 'me-0.5' : 'ms-0.5' }}">*</span>
            @endif
        </label>
    @endif

    {{-- Editor Container --}}
    <div
        class="markdown-editor-wrapper group relative overflow-hidden rounded-xl border border-neutral-200 bg-white transition-all duration-200
               focus-within:border-neutral-400 focus-within:ring-2 focus-within:ring-neutral-100
               dark:border-neutral-700 dark:bg-neutral-900 dark:focus-within:border-neutral-500 dark:focus-within:ring-neutral-800
               @if($disabled) opacity-60 pointer-events-none @endif"
        data-config="{{ json_encode($config) }}"
        dir="{{ $direction }}"
    >
        {{-- Header with Tabs and Toolbar --}}
        <div class="flex flex-col border-b border-neutral-100 dark:border-neutral-800">
            {{-- Edit/Preview Tabs --}}
            @if($hasPreview)
                <div class="flex items-center border-b border-neutral-100 dark:border-neutral-800" dir="ltr">
                    <button
                        type="button"
                        data-tab="edit"
                        class="is-active relative px-4 py-2.5 text-sm font-medium text-neutral-600 transition-colors
                               hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100
                               [&.is-active]:text-neutral-900 [&.is-active]:dark:text-white
                               [&.is-active]:after:absolute [&.is-active]:after:bottom-0 [&.is-active]:after:left-0 [&.is-active]:after:right-0
                               [&.is-active]:after:h-0.5 [&.is-active]:after:bg-neutral-900 [&.is-active]:after:dark:bg-white"
                    >
                        <span class="flex items-center gap-1.5">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit
                        </span>
                    </button>
                    <button
                        type="button"
                        data-tab="preview"
                        class="relative px-4 py-2.5 text-sm font-medium text-neutral-600 transition-colors
                               hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100
                               [&.is-active]:text-neutral-900 [&.is-active]:dark:text-white
                               [&.is-active]:after:absolute [&.is-active]:after:bottom-0 [&.is-active]:after:left-0 [&.is-active]:after:right-0
                               [&.is-active]:after:h-0.5 [&.is-active]:after:bg-neutral-900 [&.is-active]:after:dark:bg-white"
                    >
                        <span class="flex items-center gap-1.5">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Preview
                        </span>
                    </button>
                </div>
            @endif

            {{-- Toolbar --}}
            @if(count($groupedTools) > 0)
                <div class="markdown-editor-toolbar flex flex-wrap items-center gap-0.5 bg-neutral-50/80 px-2 py-1.5 backdrop-blur-sm transition-opacity dark:bg-neutral-800/50" dir="ltr">
                    @foreach($groupedTools as $groupIndex => $group)
                        @if($groupIndex > 0)
                            <div class="mx-1.5 h-5 w-px bg-neutral-200 dark:bg-neutral-700"></div>
                        @endif

                        <div class="flex items-center gap-0.5">
                            @foreach($group as $tool)
                                <button
                                    type="button"
                                    class="toolbar-button relative flex h-8 w-8 items-center justify-center rounded-lg text-neutral-500 transition-all duration-150
                                           hover:bg-neutral-100 hover:text-neutral-900
                                           focus:outline-none focus-visible:ring-2 focus-visible:ring-neutral-400 focus-visible:ring-offset-1
                                           dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-100
                                           [&.is-active]:bg-neutral-900 [&.is-active]:text-white
                                           dark:[&.is-active]:bg-white dark:[&.is-active]:text-neutral-900"
                                    data-action="{{ $tool }}"
                                    title="{{ ucfirst(preg_replace('/([A-Z])/', ' $1', $tool)) }}"
                                    @if($disabled || $readonly) disabled @endif
                                >
                                    {!! $toolIcons[$tool] ?? '<span class="text-[10px] font-semibold uppercase">' . substr($tool, 0, 2) . '</span>' !!}
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Editor Panels --}}
        <div class="markdown-panels">
            {{-- Edit Panel --}}
            <div class="markdown-edit-panel">
                <textarea
                    id="{{ $id }}"
                    name="{{ $name }}"
                    placeholder="{{ $placeholder }}"
                    @if($disabled) disabled @endif
                    @if($readonly) readonly @endif
                    @if($required) required @endif
                    @if($maxLength) maxlength="{{ $maxLength }}" @endif
                    class="markdown-editor-input w-full min-h-[250px] resize-y border-0 bg-transparent px-4 py-3
                           font-mono text-sm text-neutral-900 placeholder-neutral-400
                           focus:outline-none focus:ring-0
                           dark:text-neutral-100 dark:placeholder-neutral-500"
                    style="direction: {{ $direction }}; text-align: {{ $isRtl ? 'right' : 'left' }};"
                ></textarea>
            </div>

            {{-- Preview Panel --}}
            @if($hasPreview)
                <div class="markdown-preview-panel hidden min-h-[250px] px-4 py-3">
                    <div class="preview-content markdown-body"
                         style="direction: {{ $direction }}; text-align: {{ $isRtl ? 'right' : 'left' }};">
                        <p class="text-neutral-400 dark:text-neutral-500 italic">Nothing to preview</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Hidden Input for form submission --}}
        <input type="hidden" name="{{ $name }}" class="markdown-value" />

        {{-- Character Counter --}}
        @if($showCharacterCount)
            <div class="markdown-counter flex items-center justify-{{ $isRtl ? 'start' : 'end' }} gap-1.5 border-t border-neutral-100 bg-neutral-50/50 px-3 py-2 text-xs font-medium text-neutral-400 dark:border-neutral-800 dark:bg-neutral-800/30 dark:text-neutral-500">
                <span class="current-length tabular-nums">0</span>
                @if($maxLength)
                    <span class="text-neutral-300 dark:text-neutral-600">/</span>
                    <span class="tabular-nums">{{ number_format($maxLength) }}</span>
                @endif
            </div>
        @endif
    </div>

    {{-- Hint --}}
    @if($hint)
        <p class="text-sm text-neutral-500 dark:text-neutral-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $hint }}</p>
    @endif

    {{-- Error --}}
    @error($name)
        <p class="text-sm text-red-600 dark:text-red-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $message }}</p>
    @enderror
</div>

{{-- Markdown Preview CSS (matching TipTap editor style) --}}
<style>
    /* Markdown Preview - Light Mode */
    .markdown-body {
        color: #171717;
        font-size: 1rem;
        line-height: 1.75;
        word-wrap: break-word;
    }

    /* Headings */
    .markdown-body h1 {
        font-size: 1.875rem;
        font-weight: 700;
        line-height: 1.25;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #171717;
    }
    .markdown-body h1:first-child { margin-top: 0; }

    .markdown-body h2 {
        font-size: 1.5rem;
        font-weight: 600;
        line-height: 1.3;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
        color: #171717;
    }
    .markdown-body h2:first-child { margin-top: 0; }

    .markdown-body h3 {
        font-size: 1.25rem;
        font-weight: 600;
        line-height: 1.4;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: #171717;
    }
    .markdown-body h3:first-child { margin-top: 0; }

    .markdown-body h4 { font-size: 1rem; font-weight: 600; margin-top: 1rem; margin-bottom: 0.5rem; }
    .markdown-body h5 { font-size: 0.875rem; font-weight: 600; margin-top: 1rem; margin-bottom: 0.5rem; }
    .markdown-body h6 { font-size: 0.85rem; font-weight: 600; margin-top: 1rem; margin-bottom: 0.5rem; color: #6b7280; }

    /* Paragraphs and text */
    .markdown-body p {
        margin-bottom: 0.75rem;
    }
    .markdown-body p:last-child {
        margin-bottom: 0;
    }

    .markdown-body strong { font-weight: 600; }
    .markdown-body em { font-style: italic; }
    .markdown-body del { text-decoration: line-through; }
    .markdown-body u { text-decoration: underline; }

    /* Links */
    .markdown-body a {
        color: #2563eb;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .markdown-body a:hover {
        color: #1d4ed8;
    }

    /* Lists */
    .markdown-body ul,
    .markdown-body ol {
        margin-bottom: 0.75rem;
        padding-{{ $isRtl ? 'right' : 'left' }}: 1.5rem;
    }

    .markdown-body ul { list-style-type: disc; }
    .markdown-body ol { list-style-type: decimal; }

    .markdown-body li {
        margin-bottom: 0.25rem;
    }

    .markdown-body li p {
        margin-bottom: 0;
    }

    .markdown-body ul ul,
    .markdown-body ul ol,
    .markdown-body ol ol,
    .markdown-body ol ul {
        margin-top: 0.25rem;
        margin-bottom: 0;
    }

    /* Task lists */
    .markdown-body .task-list-item {
        list-style-type: none;
        margin-{{ $isRtl ? 'right' : 'left' }}: -1.5em;
    }

    .markdown-body .task-checkbox {
        appearance: none;
        width: 1em;
        height: 1em;
        border: 1px solid #d1d5db;
        border-radius: 3px;
        margin-{{ $isRtl ? 'left' : 'right' }}: 0.35em;
        vertical-align: -0.15em;
        position: relative;
        background-color: #fff;
    }

    .markdown-body .task-checkbox:checked {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .markdown-body .task-checkbox:checked::after {
        content: '';
        position: absolute;
        left: 3px;
        top: 0px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Blockquotes */
    .markdown-body blockquote {
        border-{{ $isRtl ? 'right' : 'left' }}: 3px solid #e5e7eb;
        padding-{{ $isRtl ? 'right' : 'left' }}: 1rem;
        margin: 1rem 0;
        font-style: italic;
        color: #6b7280;
    }

    .markdown-body blockquote > :first-child { margin-top: 0; }
    .markdown-body blockquote > :last-child { margin-bottom: 0; }

    /* Code - inline */
    .markdown-body code {
        background: #f3f4f6;
        border-radius: 0.25rem;
        padding: 0.125rem 0.375rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.875em;
    }

    /* Code blocks */
    .markdown-body pre {
        background: #1f2937;
        color: #f9fafb;
        border-radius: 0.5rem;
        padding: 1rem;
        overflow-x: auto;
        margin: 1rem 0;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .markdown-body pre code {
        background: transparent;
        padding: 0;
        color: inherit;
        font-size: 100%;
    }

    /* Tables */
    .markdown-body table {
        border-collapse: collapse;
        width: 100%;
        margin: 1rem 0;
    }

    .markdown-body table th,
    .markdown-body table td {
        border: 1px solid #e5e7eb;
        padding: 0.5rem 0.75rem;
        text-align: {{ $isRtl ? 'right' : 'left' }};
    }

    .markdown-body table th {
        background: #f9fafb;
        font-weight: 600;
    }

    /* Horizontal rule */
    .markdown-body hr {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 1.5rem 0;
    }

    /* Images */
    .markdown-body img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
    }

    /* Highlight */
    .markdown-body mark {
        background: #fef08a;
        border-radius: 0.125rem;
        padding: 0 0.125rem;
    }

    /* First/last child margins */
    .markdown-body > *:first-child { margin-top: 0 !important; }
    .markdown-body > *:last-child { margin-bottom: 0 !important; }

    /* ===== DARK MODE ===== */
    .dark .markdown-body {
        color: #f5f5f5;
    }

    .dark .markdown-body h1,
    .dark .markdown-body h2,
    .dark .markdown-body h3,
    .dark .markdown-body h4,
    .dark .markdown-body h5 {
        color: #f5f5f5;
    }

    .dark .markdown-body h6 {
        color: #9ca3af;
    }

    .dark .markdown-body a {
        color: #60a5fa;
    }

    .dark .markdown-body a:hover {
        color: #93c5fd;
    }

    .dark .markdown-body blockquote {
        border-{{ $isRtl ? 'right' : 'left' }}-color: #4b5563;
        color: #9ca3af;
    }

    .dark .markdown-body code {
        background: #374151;
    }

    .dark .markdown-body pre {
        background: #1f2937;
        color: #f9fafb;
    }

    .dark .markdown-body table th,
    .dark .markdown-body table td {
        border-color: #4b5563;
    }

    .dark .markdown-body table th {
        background: #374151;
    }

    .dark .markdown-body hr {
        border-top-color: #4b5563;
    }

    .dark .markdown-body mark {
        background: #854d0e;
        color: #fef08a;
    }

    .dark .markdown-body .task-checkbox {
        border-color: #4b5563;
        background-color: #1f2937;
    }

    .dark .markdown-body .task-checkbox:checked {
        background-color: #60a5fa;
        border-color: #60a5fa;
    }

    /* Drag over state */
    .markdown-edit-panel.drag-over {
        background-color: rgba(59, 130, 246, 0.05);
        outline: 2px dashed #3b82f6;
        outline-offset: -4px;
    }
</style>

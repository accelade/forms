@props([
    'field' => null,
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'direction' => 'ltr',
    'maxLength' => null,
    'showCharacterCount' => null,
    'toolbarButtons' => null,
    'extraInputAttributes' => [],
])

@php
    // Support both Field object mode and direct props mode
    if ($field) {
        $id = $field->getId();
        $name = $field->getName();
        $label = $field->getLabel();
        $placeholder = $field->getPlaceholder();
        $hint = $field->getHint() ?? $field->getHelperText();
        $required = $field->isRequired();
        $disabled = $field->isDisabled();
        $readonly = $field->isReadonly();
        $direction = method_exists($field, 'getDirection') ? ($field->getDirection() ?? 'ltr') : 'ltr';
        $maxLength = $field->getMaxLength();
        $showCharacterCount = method_exists($field, 'shouldShowCharacterCount')
            ? ($field->shouldShowCharacterCount() ?? ($maxLength !== null))
            : ($maxLength !== null);
        $groupedTools = $field->hasGroupedToolbarButtons() ? $field->getToolbarButtons() : [$field->getFlatToolbarButtons()];
        $extraInputAttributes = method_exists($field, 'getExtraInputAttributes')
            ? ($field->getExtraInputAttributes() ?? [])
            : [];
    } else {
        $id = $id ?? $name;

        // Default toolbar buttons (grouped)
        $defaultToolbarButtons = [
            ['bold', 'italic', 'underline', 'strike'],
            ['h1', 'h2', 'h3'],
            ['bulletList', 'orderedList', 'blockquote'],
            ['link', 'image', 'table'],
            ['alignStart', 'alignCenter', 'alignEnd'],
            ['undo', 'redo'],
        ];

        $groupedTools = $toolbarButtons ?? $defaultToolbarButtons;

        if ($showCharacterCount === null) {
            $showCharacterCount = $maxLength !== null;
        }
    }

    $isRtl = $direction === 'rtl';

    // Tool icons mapping (matching TipTap style)
    $toolIcons = [
        'bold' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg>',
        'italic' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>',
        'underline' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4v6a6 6 0 0 0 12 0V4"/><line x1="4" y1="20" x2="20" y2="20"/></svg>',
        'strike' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4H9a3 3 0 0 0-2.83 4"/><path d="M14 12a4 4 0 0 1 0 8H6"/><line x1="4" y1="12" x2="20" y2="12"/></svg>',
        'subscript' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m4 5 8 8"/><path d="m12 5-8 8"/><path d="M20 19h-4c0-1.5.44-2 1.5-2.5S20 15.33 20 14c0-.47-.17-.93-.48-1.29a2.11 2.11 0 0 0-2.62-.44c-.42.24-.74.62-.9 1.07"/></svg>',
        'superscript' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m4 19 8-8"/><path d="m12 19-8-8"/><path d="M20 12h-4c0-1.5.442-2 1.5-2.5S20 8.334 20 7c0-.472-.17-.93-.484-1.29a2.105 2.105 0 0 0-2.617-.436c-.42.239-.738.614-.899 1.06"/></svg>',
        'h1' => '<span class="text-[11px] font-bold tracking-tight">H1</span>',
        'h2' => '<span class="text-[11px] font-bold tracking-tight">H2</span>',
        'h3' => '<span class="text-[11px] font-bold tracking-tight">H3</span>',
        'bulletList' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="4" cy="6" r="1" fill="currentColor"/><circle cx="4" cy="12" r="1" fill="currentColor"/><circle cx="4" cy="18" r="1" fill="currentColor"/></svg>',
        'orderedList' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>',
        'blockquote' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>',
        'codeBlock' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
        'horizontalRule' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>',
        'link' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
        'attachFiles' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>',
        'image' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
        'table' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>',
        'alignStart' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>',
        'alignLeft' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>',
        'alignCenter' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="10" x2="6" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="18" y1="18" x2="6" y2="18"/></svg>',
        'alignEnd' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="7" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="7" y2="18"/></svg>',
        'alignRight' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="7" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="7" y2="18"/></svg>',
        'alignJustify' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
        'undo' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>',
        'redo' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7v6h-6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3l3 2.7"/></svg>',
        'clearFormatting' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/><path d="m5 19 14-14"/></svg>',
        'highlight' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11-6 6v3h9l3-3"/><path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4"/></svg>',
    ];
@endphp

<div class="rich-editor-field space-y-1.5" dir="{{ $direction }}">
    {{-- Label --}}
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">
            {{ $label }}
            @if($required)
                <span class="text-red-500 {{ $isRtl ? 'me-0.5' : 'ms-0.5' }}">*</span>
            @endif
        </label>
    @endif

    {{-- Rich Editor Container --}}
    <div
        class="rich-editor-wrapper group relative overflow-hidden rounded-xl border border-neutral-200 bg-white transition-all duration-200
               focus-within:border-neutral-400 focus-within:ring-2 focus-within:ring-neutral-100
               dark:border-neutral-700 dark:bg-neutral-900 dark:focus-within:border-neutral-500 dark:focus-within:ring-neutral-800
               @if($disabled) opacity-60 pointer-events-none @endif"
        dir="{{ $direction }}"
        @if(!empty($extraInputAttributes))
            @foreach($extraInputAttributes as $attrName => $attrValue)
                {{ $attrName }}="{{ is_array($attrValue) ? implode(' ', $attrValue) : $attrValue }}"
            @endforeach
        @endif
    >
        {{-- Toolbar --}}
        @if(count($groupedTools) > 0)
            <div class="rich-editor-toolbar flex flex-wrap items-center gap-0.5 border-b border-neutral-100 bg-neutral-50/80 px-2 py-1.5 backdrop-blur-sm dark:border-neutral-800 dark:bg-neutral-800/50" dir="ltr">
                @foreach($groupedTools as $groupIndex => $group)
                    @if(is_array($group) && !empty($group))
                        @if($groupIndex > 0)
                            <div class="mx-1.5 h-5 w-px bg-neutral-200 dark:bg-neutral-700"></div>
                        @endif

                        <div class="flex items-center gap-0.5">
                            @foreach($group as $button)
                                <button
                                    type="button"
                                    class="toolbar-button relative flex h-8 w-8 items-center justify-center rounded-lg text-neutral-500 transition-all duration-150
                                           hover:bg-neutral-100 hover:text-neutral-900
                                           focus:outline-none focus-visible:ring-2 focus-visible:ring-neutral-400 focus-visible:ring-offset-1
                                           dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-100
                                           [&.is-active]:bg-neutral-900 [&.is-active]:text-white
                                           dark:[&.is-active]:bg-white dark:[&.is-active]:text-neutral-900"
                                    data-action="{{ $button }}"
                                    title="{{ ucfirst(preg_replace('/([A-Z])/', ' $1', $button)) }}"
                                    @if($disabled || $readonly) disabled @endif
                                >
                                    {!! $toolIcons[$button] ?? '<span class="text-[10px] font-semibold uppercase">' . substr($button, 0, 2) . '</span>' !!}
                                </button>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Editor Content Area --}}
        <div
            class="rich-editor-content min-h-[200px] px-4 py-3"
            id="{{ $id }}"
            contenteditable="{{ $disabled || $readonly ? 'false' : 'true' }}"
            @if($placeholder) data-placeholder="{{ $placeholder }}" @endif
            @if($maxLength) data-max-length="{{ $maxLength }}" @endif
        >
            {{-- ProseMirror/ContentEditable styles --}}
            <style>
                [id="{{ $id }}"] {
                    outline: none;
                    min-height: 176px;
                    font-size: 1rem;
                    line-height: 1.75;
                    color: #171717;
                }
                .dark [id="{{ $id }}"] {
                    color: #f5f5f5;
                }

                /* Placeholder */
                [id="{{ $id }}"]:empty::before {
                    content: attr(data-placeholder);
                    color: #9ca3af;
                    pointer-events: none;
                }
                .dark [id="{{ $id }}"]:empty::before {
                    color: #6b7280;
                }

                /* Headings */
                [id="{{ $id }}"] h1 {
                    font-size: 1.875rem;
                    font-weight: 700;
                    line-height: 1.25;
                    margin-top: 1.5rem;
                    margin-bottom: 0.75rem;
                }
                [id="{{ $id }}"] h1:first-child { margin-top: 0; }
                [id="{{ $id }}"] h2 {
                    font-size: 1.5rem;
                    font-weight: 600;
                    line-height: 1.3;
                    margin-top: 1.25rem;
                    margin-bottom: 0.5rem;
                }
                [id="{{ $id }}"] h2:first-child { margin-top: 0; }
                [id="{{ $id }}"] h3 {
                    font-size: 1.25rem;
                    font-weight: 600;
                    line-height: 1.4;
                    margin-top: 1rem;
                    margin-bottom: 0.5rem;
                }
                [id="{{ $id }}"] h3:first-child { margin-top: 0; }

                /* Paragraphs */
                [id="{{ $id }}"] p {
                    margin-bottom: 0.75rem;
                }
                [id="{{ $id }}"] p:last-child {
                    margin-bottom: 0;
                }

                /* Lists */
                [id="{{ $id }}"] ul {
                    list-style-type: disc;
                    padding-{{ $isRtl ? 'right' : 'left' }}: 1.5rem;
                    margin-bottom: 0.75rem;
                }
                [id="{{ $id }}"] ol {
                    list-style-type: decimal;
                    padding-{{ $isRtl ? 'right' : 'left' }}: 1.5rem;
                    margin-bottom: 0.75rem;
                }
                [id="{{ $id }}"] li {
                    margin-bottom: 0.25rem;
                }
                [id="{{ $id }}"] li p {
                    margin-bottom: 0;
                }

                /* Blockquote */
                [id="{{ $id }}"] blockquote {
                    border-{{ $isRtl ? 'right' : 'left' }}: 3px solid #e5e7eb;
                    padding-{{ $isRtl ? 'right' : 'left' }}: 1rem;
                    margin: 1rem 0;
                    font-style: italic;
                    color: #6b7280;
                }
                .dark [id="{{ $id }}"] blockquote {
                    border-{{ $isRtl ? 'right' : 'left' }}-color: #4b5563;
                    color: #9ca3af;
                }

                /* Code */
                [id="{{ $id }}"] code {
                    background: #f3f4f6;
                    border-radius: 0.25rem;
                    padding: 0.125rem 0.375rem;
                    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
                    font-size: 0.875em;
                }
                .dark [id="{{ $id }}"] code {
                    background: #374151;
                }
                [id="{{ $id }}"] pre {
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
                [id="{{ $id }}"] pre code {
                    background: transparent;
                    padding: 0;
                    color: inherit;
                }

                /* Links */
                [id="{{ $id }}"] a {
                    color: #2563eb;
                    text-decoration: underline;
                    text-underline-offset: 2px;
                }
                .dark [id="{{ $id }}"] a {
                    color: #60a5fa;
                }

                /* Strong, Em, etc. */
                [id="{{ $id }}"] strong { font-weight: 600; }
                [id="{{ $id }}"] em { font-style: italic; }
                [id="{{ $id }}"] u { text-decoration: underline; }
                [id="{{ $id }}"] s { text-decoration: line-through; }

                /* Highlight */
                [id="{{ $id }}"] mark {
                    background: #fef08a;
                    border-radius: 0.125rem;
                    padding: 0 0.125rem;
                }
                .dark [id="{{ $id }}"] mark {
                    background: #854d0e;
                    color: #fef08a;
                }

                /* Horizontal Rule */
                [id="{{ $id }}"] hr {
                    border: none;
                    border-top: 1px solid #e5e7eb;
                    margin: 1.5rem 0;
                }
                .dark [id="{{ $id }}"] hr {
                    border-top-color: #4b5563;
                }

                /* Images */
                [id="{{ $id }}"] img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 0.5rem;
                }

                /* Tables */
                [id="{{ $id }}"] table {
                    border-collapse: collapse;
                    width: 100%;
                    margin: 1rem 0;
                }
                [id="{{ $id }}"] td,
                [id="{{ $id }}"] th {
                    border: 1px solid #e5e7eb;
                    padding: 0.5rem 0.75rem;
                    text-align: {{ $isRtl ? 'right' : 'left' }};
                }
                [id="{{ $id }}"] th {
                    background: #f9fafb;
                    font-weight: 600;
                }
                .dark [id="{{ $id }}"] td,
                .dark [id="{{ $id }}"] th {
                    border-color: #4b5563;
                }
                .dark [id="{{ $id }}"] th {
                    background: #374151;
                }

                /* Text Alignment */
                [id="{{ $id }}"] [style*="text-align: left"] { text-align: {{ $isRtl ? 'right' : 'left' }}; }
                [id="{{ $id }}"] [style*="text-align: center"] { text-align: center; }
                [id="{{ $id }}"] [style*="text-align: right"] { text-align: {{ $isRtl ? 'left' : 'right' }}; }
                [id="{{ $id }}"] [style*="text-align: justify"] { text-align: justify; }
            </style>
        </div>

        {{-- Hidden Input --}}
        <input type="hidden" name="{{ $name }}" class="rich-editor-value" />

        {{-- Character Counter --}}
        @if($showCharacterCount)
            <div class="rich-editor-counter flex items-center justify-{{ $isRtl ? 'start' : 'end' }} gap-1.5 border-t border-neutral-100 bg-neutral-50/50 px-3 py-2 text-xs font-medium text-neutral-400 dark:border-neutral-800 dark:bg-neutral-800/30 dark:text-neutral-500">
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

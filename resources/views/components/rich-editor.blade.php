@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $placeholder = $field->getPlaceholder();
    $toolbarButtons = $field->getToolbarButtons();
    $flatToolbarButtons = $field->getFlatToolbarButtons();
    $hasGroupedToolbar = $field->hasGroupedToolbarButtons();
    $maxLength = $field->getMaxLength();

    // Toolbar button icons (Filament-compatible)
    $toolbarIcons = [
        // Text formatting
        'bold' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg>',
        'italic' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>',
        'underline' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 4v6a6 6 0 0 0 12 0V4"/><line x1="4" y1="20" x2="20" y2="20"/></svg>',
        'strike' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 4H9a3 3 0 0 0-2.83 4"/><path d="M14 12a4 4 0 0 1 0 8H6"/><line x1="4" y1="12" x2="20" y2="12"/></svg>',
        'subscript' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m4 5 8 8"/><path d="m12 5-8 8"/><path d="M20 19h-4c0-1.5.44-2 1.5-2.5S20 15.33 20 14c0-.47-.17-.93-.48-1.29a2.11 2.11 0 0 0-2.62-.44c-.42.24-.74.62-.9 1.07"/></svg>',
        'superscript' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m4 19 8-8"/><path d="m12 19-8-8"/><path d="M20 12h-4c0-1.5.442-2 1.5-2.5S20 8.334 20 7c0-.472-.17-.93-.484-1.29a2.105 2.105 0 0 0-2.617-.436c-.42.239-.738.614-.899 1.06"/></svg>',

        // Headings
        'h1' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 12h8"/><path d="M4 18V6"/><path d="M12 18V6"/><path d="m17 12 3-2v8"/></svg>',
        'h2' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 12h8"/><path d="M4 18V6"/><path d="M12 18V6"/><path d="M21 18h-4c0-4 4-3 4-6 0-1.5-2-2.5-4-1"/></svg>',
        'h3' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 12h8"/><path d="M4 18V6"/><path d="M12 18V6"/><path d="M17.5 10.5c1.7-1 3.5 0 3.5 1.5a2 2 0 0 1-2 2"/><path d="M17 17.5c2 1.5 4 .3 4-1.5a2 2 0 0 0-2-2"/></svg>',

        // Lists
        'bulletList' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>',
        'orderedList' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>',

        // Block elements
        'blockquote' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V21z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3z"/></svg>',
        'codeBlock' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
        'horizontalRule' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>',

        // Links & Media
        'link' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
        'attachFiles' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>',
        'image' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>',
        'table' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 3v18"/><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/></svg>',

        // Alignment
        'alignStart' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="21" y1="6" x2="3" y2="6"/><line x1="15" y1="12" x2="3" y2="12"/><line x1="17" y1="18" x2="3" y2="18"/></svg>',
        'alignCenter' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="21" y1="6" x2="3" y2="6"/><line x1="17" y1="12" x2="7" y2="12"/><line x1="19" y1="18" x2="5" y2="18"/></svg>',
        'alignEnd' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="12" x2="9" y2="12"/><line x1="21" y1="18" x2="7" y2="18"/></svg>',
        'alignJustify' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',

        // History
        'undo' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>',
        'redo' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 7v6h-6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3l3 2.7"/></svg>',

        // Additional tools
        'clearFormatting' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/></svg>',
        'highlight' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 11-6 6v3h9l3-3"/><path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4"/></svg>',
    ];
@endphp

<div {{ $attributes->class(['form-field rich-editor-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label for="{{ $id }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-danger-600 dark:text-danger-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="rich-editor-wrapper ring-1 ring-gray-950/10 rounded-lg bg-white overflow-hidden transition duration-75 focus-within:ring-2 focus-within:ring-primary-600 dark:ring-white/20 dark:bg-white/5 dark:focus-within:ring-primary-500 {{ $isDisabled ? 'opacity-70 pointer-events-none' : '' }}">
        {{-- Toolbar --}}
        <div class="rich-editor-toolbar flex flex-wrap items-center gap-1 border-b border-gray-200 bg-gray-50 px-3 py-2 dark:border-white/10 dark:bg-gray-900/50">
            @if($hasGroupedToolbar)
                @foreach($toolbarButtons as $groupIndex => $group)
                    @if(is_array($group) && !empty($group))
                        @if($groupIndex > 0)
                            <div class="h-5 w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>
                        @endif
                        <div class="flex items-center gap-0.5">
                            @foreach($group as $button)
                                <button
                                    type="button"
                                    class="toolbar-button inline-flex items-center justify-center rounded p-1.5 text-gray-400 transition duration-75 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-1 dark:text-gray-500 dark:hover:bg-white/5 dark:hover:text-gray-400 dark:focus:ring-offset-gray-900 [&.is-active]:bg-gray-100 [&.is-active]:text-gray-700 dark:[&.is-active]:bg-white/10 dark:[&.is-active]:text-gray-300"
                                    data-action="{{ $button }}"
                                    title="{{ ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $button)) }}"
                                    @if($isDisabled || $isReadOnly) disabled @endif
                                >
                                    {!! $toolbarIcons[$button] ?? '<span class="text-xs font-semibold">' . strtoupper(substr($button, 0, 2)) . '</span>' !!}
                                </button>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            @else
                @foreach($toolbarButtons as $button)
                    <button
                        type="button"
                        class="toolbar-button inline-flex items-center justify-center rounded p-1.5 text-gray-400 transition duration-75 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-1 dark:text-gray-500 dark:hover:bg-white/5 dark:hover:text-gray-400 dark:focus:ring-offset-gray-900 [&.is-active]:bg-gray-100 [&.is-active]:text-gray-700 dark:[&.is-active]:bg-white/10 dark:[&.is-active]:text-gray-300"
                        data-action="{{ $button }}"
                        title="{{ ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $button)) }}"
                        @if($isDisabled || $isReadOnly) disabled @endif
                    >
                        {!! $toolbarIcons[$button] ?? '<span class="text-xs font-semibold">' . strtoupper(substr($button, 0, 2)) . '</span>' !!}
                    </button>
                @endforeach
            @endif
        </div>

        {{-- Editor content area --}}
        <div
            class="rich-editor-content block w-full min-h-[12rem] px-3 py-3 text-base text-gray-950 bg-transparent outline-none focus:outline-none sm:text-sm sm:leading-6 dark:text-white [&:empty]:before:content-[attr(data-placeholder)] [&:empty]:before:text-gray-400 dark:[&:empty]:before:text-gray-500 [&:empty]:before:cursor-text
                [&_h1]:text-2xl [&_h1]:font-bold [&_h1]:mb-2 [&_h1]:mt-4 [&_h1]:first:mt-0
                [&_h2]:text-xl [&_h2]:font-bold [&_h2]:mb-2 [&_h2]:mt-3 [&_h2]:first:mt-0
                [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:mb-2 [&_h3]:mt-3 [&_h3]:first:mt-0
                [&_h4]:text-base [&_h4]:font-semibold [&_h4]:mb-1 [&_h4]:mt-2
                [&_h5]:text-sm [&_h5]:font-semibold [&_h5]:mb-1 [&_h5]:mt-2
                [&_h6]:text-xs [&_h6]:font-semibold [&_h6]:mb-1 [&_h6]:mt-2
                [&_p]:mb-2 [&_p]:last:mb-0
                [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:mb-2
                [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:mb-2
                [&_li]:mb-1
                [&_blockquote]:border-l-4 [&_blockquote]:border-gray-300 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:my-2 dark:[&_blockquote]:border-gray-600
                [&_pre]:bg-gray-100 [&_pre]:p-3 [&_pre]:rounded [&_pre]:overflow-x-auto [&_pre]:my-2 [&_pre]:font-mono [&_pre]:text-sm dark:[&_pre]:bg-gray-800
                [&_code]:bg-gray-100 [&_code]:px-1 [&_code]:rounded [&_code]:font-mono [&_code]:text-sm dark:[&_code]:bg-gray-800
                [&_a]:text-primary-600 [&_a]:underline dark:[&_a]:text-primary-400
                [&_strong]:font-bold
                [&_em]:italic
                [&_u]:underline
                [&_s]:line-through
                [&_mark]:bg-yellow-200 dark:[&_mark]:bg-yellow-800
                [&_hr]:border-gray-300 [&_hr]:my-4 dark:[&_hr]:border-gray-600
                [&_table]:w-full [&_table]:border-collapse [&_table]:my-2
                [&_td]:border [&_td]:border-gray-300 [&_td]:p-2 dark:[&_td]:border-gray-600
                [&_th]:border [&_th]:border-gray-300 [&_th]:p-2 [&_th]:bg-gray-50 [&_th]:font-semibold dark:[&_th]:border-gray-600 dark:[&_th]:bg-gray-800"
            id="{{ $id }}"
            contenteditable="{{ $isDisabled || $isReadOnly ? 'false' : 'true' }}"
            @if($placeholder) data-placeholder="{{ $placeholder }}" @endif
            @if($maxLength) data-max-length="{{ $maxLength }}" @endif
        ></div>

        {{-- Hidden input for form submission --}}
        <input type="hidden" name="{{ $name }}" class="rich-editor-value" />
    </div>

    {{-- Character counter --}}
    @if($maxLength)
        <div class="flex items-center justify-end mt-1.5">
            <span class="rich-editor-counter text-xs text-gray-500 dark:text-gray-400">
                <span class="current-length">0</span> / {{ $maxLength }}
            </span>
        </div>
    @endif

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'mt-2 text-sm text-gray-500 dark:text-gray-400') }}">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="{{ config('forms.styles.hint', 'mt-2 text-sm text-gray-500 dark:text-gray-400') }}">{{ $field->getHint() }}</p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'mt-2 text-sm text-danger-600 dark:text-danger-400') }}">
            {{ $message }}
        </p>
    @enderror
</div>

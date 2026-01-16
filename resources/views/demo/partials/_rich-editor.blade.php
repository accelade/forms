{{-- Rich Editor Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\RichEditor;

    // Full-featured editor with Filament-style grouped toolbar
    $fullEditor = RichEditor::make('content')
        ->label('Full-Featured Editor')
        ->toolbarButtons([
            ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
            ['h1', 'h2', 'h3'],
            ['bulletList', 'orderedList', 'blockquote', 'codeBlock'],
            ['alignStart', 'alignCenter', 'alignEnd'],
            ['link', 'image', 'table'],
            ['undo', 'redo'],
        ])
        ->placeholder('Start writing your content...')
        ->helperText('Full toolbar with all formatting options');

    // Minimal editor for comments
    $minimalEditor = RichEditor::make('comment')
        ->label('Comment Editor')
        ->toolbarButtons(['bold', 'italic', 'link'])
        ->placeholder('Write a comment...')
        ->helperText('Minimal toolbar for simple text');

    // Editor with disabled buttons
    $limitedEditor = RichEditor::make('description')
        ->label('Limited Editor')
        ->toolbarButtons([
            ['bold', 'italic', 'underline'],
            ['bulletList', 'orderedList'],
        ])
        ->disableToolbarButtons(['underline'])
        ->placeholder('Some buttons are disabled...')
        ->helperText('Underline button is disabled');

    // Editor with max length
    $maxLengthEditor = RichEditor::make('bio')
        ->label('Bio (Max 500 characters)')
        ->toolbarButtons(['bold', 'italic', 'link'])
        ->maxLength(500)
        ->placeholder('Write your bio...');

    // Editor with file attachments config
    $attachmentsEditor = RichEditor::make('article')
        ->label('Article with Attachments')
        ->toolbarButtons([
            ['bold', 'italic', 'underline'],
            ['h2', 'h3'],
            ['bulletList', 'orderedList', 'blockquote'],
            ['link', 'attachFiles'],
        ])
        ->fileAttachmentsDisk('public')
        ->fileAttachmentsDirectory('uploads/articles')
        ->fileAttachmentsVisibility('public')
        ->placeholder('Write your article...')
        ->helperText('File attachments configured for public storage');

    // Editor with extra formatting tools
    $formattingEditor = RichEditor::make('formatted_content')
        ->label('Advanced Formatting')
        ->toolbarButtons([
            ['bold', 'italic', 'underline', 'strike'],
            ['h1', 'h2', 'h3'],
            ['bulletList', 'orderedList'],
            ['blockquote', 'codeBlock', 'horizontalRule'],
            ['highlight', 'clearFormatting'],
            ['undo', 'redo'],
        ])
        ->placeholder('Try all formatting options...')
        ->helperText('Includes highlight, code blocks, and horizontal rules');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Rich Editor</h3>
    </div>
    <p class="text-sm mb-6" style="color: var(--docs-text-muted);">
        WYSIWYG rich text editor with Filament-compatible API. Supports grouped toolbars, file attachments, and customizable formatting options.
    </p>

    <div class="space-y-6 mb-6">
        {{-- Full-Featured Editor --}}
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Full</span>
                Full-Featured Editor
            </h4>
            {!! $fullEditor !!}
        </div>

        {{-- Minimal Editor --}}
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Minimal</span>
                Minimal Toolbar
            </h4>
            {!! $minimalEditor !!}
        </div>

        {{-- Limited Editor --}}
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Limited</span>
                Disabled Buttons
            </h4>
            {!! $limitedEditor !!}
        </div>

        {{-- Max Length Editor --}}
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Limit</span>
                Character Limit
            </h4>
            {!! $maxLengthEditor !!}
        </div>

        {{-- File Attachments Editor --}}
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">Files</span>
                File Attachments
            </h4>
            {!! $attachmentsEditor !!}
        </div>

        {{-- Advanced Formatting Editor --}}
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Format</span>
                Advanced Formatting
            </h4>
            {!! $formattingEditor !!}
        </div>
    </div>

    {{-- API Reference --}}
    <div class="mt-8">
        <h4 class="text-md font-semibold mb-4" style="color: var(--docs-text);">API Reference</h4>

        <x-accelade::code-block language="php" filename="rich-editor-examples.php">
use Accelade\Forms\Components\RichEditor;

// Full-featured editor with grouped toolbar (Filament style)
RichEditor::make('content')
    ->label('Article Content')
    ->toolbarButtons([
        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
        ['h1', 'h2', 'h3'],
        ['bulletList', 'orderedList', 'blockquote', 'codeBlock'],
        ['alignStart', 'alignCenter', 'alignEnd'],
        ['link', 'image', 'table'],
        ['undo', 'redo'],
    ])
    ->placeholder('Start writing...');

// Minimal editor
RichEditor::make('comment')
    ->toolbarButtons(['bold', 'italic', 'link']);

// With disabled buttons
RichEditor::make('description')
    ->disableToolbarButtons(['underline', 'strike']);

// Disable all toolbar buttons
RichEditor::make('plain')
    ->disableAllToolbarButtons();

// Character limit with counter
RichEditor::make('bio')
    ->maxLength(500);

// File attachments configuration
RichEditor::make('article')
    ->fileAttachmentsDisk('public')
    ->fileAttachmentsDirectory('uploads')
    ->fileAttachmentsVisibility('public');

// Readonly mode
RichEditor::make('preview')
    ->readonly();

// Disabled state
RichEditor::make('locked')
    ->disabled();
        </x-accelade::code-block>
    </div>

    {{-- Available Toolbar Buttons Reference --}}
    <div class="mt-8">
        <h4 class="text-md font-semibold mb-4" style="color: var(--docs-text);">Available Toolbar Buttons</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="font-medium text-sm mb-2" style="color: var(--docs-text);">Text Formatting</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'] as $btn)
                        <span class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">{{ $btn }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="font-medium text-sm mb-2" style="color: var(--docs-text);">Headings</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'paragraph'] as $btn)
                        <span class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">{{ $btn }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="font-medium text-sm mb-2" style="color: var(--docs-text);">Lists & Blocks</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['bulletList', 'orderedList', 'blockquote', 'codeBlock', 'horizontalRule'] as $btn)
                        <span class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">{{ $btn }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="font-medium text-sm mb-2" style="color: var(--docs-text);">Alignment</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'] as $btn)
                        <span class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">{{ $btn }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="font-medium text-sm mb-2" style="color: var(--docs-text);">Links & Media</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['link', 'unlink', 'image', 'attachFiles', 'table'] as $btn)
                        <span class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">{{ $btn }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="font-medium text-sm mb-2" style="color: var(--docs-text);">History & Misc</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['undo', 'redo', 'clearFormatting', 'highlight'] as $btn)
                        <span class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded font-mono">{{ $btn }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

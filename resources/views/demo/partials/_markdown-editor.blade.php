{{-- Markdown Editor Component Section --}}
@props(['prefix' => 'a'])

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Markdown Editor</h3>
    </div>
    <p class="text-sm mb-6" style="color: var(--docs-text-muted);">
        A powerful markdown editor with live preview, toolbar customization, and file attachment support. Compatible with Filament's MarkdownEditor API.
    </p>

    <div class="space-y-6 mb-6">
        {{-- Default Editor --}}
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Default</span>
                Full-Featured Editor
            </h4>
            <x-forms::markdown-editor
                name="markdown_content"
                placeholder="Write your markdown here... Use **bold**, *italic*, [links](url), and more!"
            />
        </div>

        {{-- Simple Toolbar --}}
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Simple</span>
                Minimal Toolbar
            </h4>
            <x-forms::markdown-editor
                name="markdown_simple"
                :toolbarButtons="[
                    ['bold', 'italic', 'link'],
                    ['bulletList', 'orderedList'],
                ]"
                placeholder="A simpler editor with fewer options..."
            />
        </div>

        {{-- With Character Count --}}
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Limited</span>
                With Character Count
            </h4>
            <x-forms::markdown-editor
                name="markdown_limited"
                :maxLength="500"
                :showCharacterCount="true"
                :toolbarButtons="[
                    ['bold', 'italic', 'strike'],
                    ['link'],
                ]"
                placeholder="Maximum 500 characters..."
            />
        </div>

        {{-- RTL Support --}}
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">RTL</span>
                Right-to-Left Support
            </h4>
            <x-forms::markdown-editor
                name="markdown_rtl"
                direction="rtl"
                :toolbarButtons="[
                    ['bold', 'italic', 'link'],
                    ['bulletList', 'orderedList'],
                ]"
                placeholder="اكتب محتوى الماركداون هنا..."
            />
        </div>
    </div>

    {{-- Code Examples --}}
    <div class="space-y-4">
        <h4 class="font-medium" style="color: var(--docs-text);">Usage Examples</h4>

        <x-accelade::code-block language="php" title="Markdown Editor Examples">
use Accelade\Forms\Components\MarkdownEditor;

// Default with all features
MarkdownEditor::make('content')
    ->preview();

// Custom toolbar buttons (grouped)
MarkdownEditor::make('content')
    ->toolbarButtons([
        ['bold', 'italic', 'strike', 'link'],
        ['heading'],
        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
        ['table', 'attachFiles'],
        ['undo', 'redo'],
    ]);

// Disable specific buttons
MarkdownEditor::make('content')
    ->disableToolbarButtons(['attachFiles', 'table']);

// With character limit
MarkdownEditor::make('summary')
    ->maxLength(500)
    ->characterCount();

// RTL support
MarkdownEditor::make('content')
    ->rtl();

// File attachments configuration
MarkdownEditor::make('content')
    ->fileAttachmentsDisk('s3')
    ->fileAttachmentsDirectory('attachments')
    ->fileAttachmentsVisibility('private')
    ->fileAttachmentsMaxSize(5120) // 5MB
    ->fileAttachmentsAcceptedFileTypes(['image/png', 'image/jpeg']);
        </x-accelade::code-block>
    </div>

    {{-- Available Tools Reference --}}
    <div class="mt-6 pt-6 border-t border-[var(--docs-border)]">
        <h4 class="font-medium mb-4" style="color: var(--docs-text);">Available Toolbar Buttons</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2" style="color: var(--docs-text);">Text Formatting</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['bold', 'italic', 'strike', 'link'] as $tool)
                        <span class="text-xs px-2 py-1 rounded font-mono" style="background: var(--docs-bg-alt); color: var(--docs-text-muted);">{{ $tool }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2" style="color: var(--docs-text);">Headings</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['heading', 'h1', 'h2', 'h3'] as $tool)
                        <span class="text-xs px-2 py-1 rounded font-mono" style="background: var(--docs-bg-alt); color: var(--docs-text-muted);">{{ $tool }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2" style="color: var(--docs-text);">Lists & Blocks</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['bulletList', 'orderedList', 'blockquote', 'codeBlock'] as $tool)
                        <span class="text-xs px-2 py-1 rounded font-mono" style="background: var(--docs-bg-alt); color: var(--docs-text-muted);">{{ $tool }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2" style="color: var(--docs-text);">Media & Tables</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['table', 'attachFiles'] as $tool)
                        <span class="text-xs px-2 py-1 rounded font-mono" style="background: var(--docs-bg-alt); color: var(--docs-text-muted);">{{ $tool }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2" style="color: var(--docs-text);">History</h5>
                <div class="flex flex-wrap gap-1">
                    @foreach(['undo', 'redo'] as $tool)
                        <span class="text-xs px-2 py-1 rounded font-mono" style="background: var(--docs-bg-alt); color: var(--docs-text-muted);">{{ $tool }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

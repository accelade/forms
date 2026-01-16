{{-- Textarea Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\Textarea;

    // Basic textarea
    $descriptionTextarea = Textarea::make('description')
        ->label('Description')
        ->placeholder('Enter a description...')
        ->rows(4);

    // With character limit
    $bioTextarea = Textarea::make('bio')
        ->label('Bio')
        ->placeholder('Write a short bio...')
        ->rows(3)
        ->maxLength(200)
        ->helperText('Maximum 200 characters');

    // Autosize textarea
    $autosizeTextarea = Textarea::make('autosize_content')
        ->label('Auto-resize Textarea')
        ->placeholder('Start typing and watch me grow...')
        ->rows(2)
        ->autosize()
        ->helperText('This textarea automatically grows as you type');

    // Autosize with default content
    $autosizeWithContent = Textarea::make('autosize_prefilled')
        ->label('Auto-resize with Content')
        ->rows(2)
        ->autosize()
        ->default("This textarea has some initial content.\n\nIt automatically adjusts its height based on the content.\n\nTry adding or removing lines to see it resize!");

    // Different sizes
    $smallTextarea = Textarea::make('small_content')
        ->label('Small (2 rows)')
        ->placeholder('Short text...')
        ->rows(2);

    $largeTextarea = Textarea::make('large_content')
        ->label('Large (6 rows)')
        ->placeholder('Longer content...')
        ->rows(6);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Textarea</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Multi-line text input with optional auto-resize functionality.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Textarea -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Simple Textarea
            </h4>

            {!! $descriptionTextarea !!}
        </div>

        <!-- Autosize Textarea -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Autosize</span>
                Auto-resize Textarea
            </h4>

            <div class="space-y-4">
                {!! $autosizeTextarea !!}
                {!! $autosizeWithContent !!}
            </div>
            <p class="text-xs mt-2" style="color: var(--docs-text-muted);">
                Use <code class="px-1 py-0.5 bg-gray-800 rounded">->autosize()</code> to make the textarea grow automatically as content is added.
            </p>
        </div>

        <!-- With Character Count -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Counter</span>
                Character Limit
            </h4>

            {!! $bioTextarea !!}
        </div>

        <!-- Different Sizes -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Sizes</span>
                Different Sizes
            </h4>

            <div class="space-y-4">
                {!! $smallTextarea !!}
                {!! $largeTextarea !!}
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="textarea-examples.php">
use Accelade\Forms\Components\Textarea;

// Basic textarea
Textarea::make('description')
    ->label('Description')
    ->placeholder('Enter a description')
    ->rows(4);

// Auto-resize textarea (grows with content)
Textarea::make('content')
    ->label('Content')
    ->autosize()
    ->rows(2)  // Minimum starting rows
    ->placeholder('Start typing...');

// With character limit
Textarea::make('bio')
    ->label('Bio')
    ->maxLength(200)
    ->helperText('Maximum 200 characters');

// Auto-resize with default content
Textarea::make('notes')
    ->label('Notes')
    ->autosize()
    ->default('Initial content here...');

// Different sizes
Textarea::make('small')
    ->rows(2);

Textarea::make('large')
    ->rows(6);
    </x-accelade::code-block>
</section>

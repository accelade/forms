{{-- Emoji Input Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\EmojiInput;

    // Basic emoji picker
    $basicEmojiPicker = EmojiInput::make('emoji')
        ->label('Select an Emoji');

    // With specific categories
    $reactionsEmojiPicker = EmojiInput::make('reaction')
        ->label('Reaction')
        ->categories(['smileys', 'people']);

    // Without search
    $quickEmojiPicker = EmojiInput::make('quick_emoji')
        ->label('Quick Select')
        ->searchable(false)
        ->gridColumns(6);

    // Multiple selection
    $multipleEmojiPicker = EmojiInput::make('tags')
        ->label('Emoji Tags')
        ->multiple();

    // Without preview
    $compactEmojiPicker = EmojiInput::make('compact_emoji')
        ->label('Compact Picker')
        ->showPreview(false)
        ->gridColumns(10);

    // Custom emojis
    $customEmojiPicker = EmojiInput::make('status')
        ->label('Status')
        ->customEmojis([
            'status' => [
                '✅' => 'Done',
                '🔄' => 'In Progress',
                '⏸️' => 'Paused',
                '❌' => 'Cancelled',
                '🚀' => 'Launched',
            ],
        ]);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-amber-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Emoji Input</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Emoji picker with categories, search, and multiple selection support.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Emoji Picker -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Basic</span>
                Emoji Picker
            </h4>

            {!! $basicEmojiPicker !!}
        </div>

        <!-- Specific Categories -->
        <div class="rounded-xl p-4 border border-pink-500/30" style="background: rgba(236, 72, 153, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-pink-500/20 text-pink-500 rounded">Categories</span>
                Filtered Categories
            </h4>

            {!! $reactionsEmojiPicker !!}
        </div>

        <!-- Without Search -->
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">Quick</span>
                Without Search
            </h4>

            {!! $quickEmojiPicker !!}
        </div>

        <!-- Multiple Selection -->
        <div class="rounded-xl p-4 border border-violet-500/30" style="background: rgba(139, 92, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-violet-500/20 text-violet-500 rounded">Multiple</span>
                Multiple Selection
            </h4>

            {!! $multipleEmojiPicker !!}
        </div>

        <!-- Compact Mode -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Compact</span>
                No Preview
            </h4>

            {!! $compactEmojiPicker !!}
        </div>

        <!-- Custom Emojis -->
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Custom</span>
                Custom Emoji Set
            </h4>

            {!! $customEmojiPicker !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="emoji-input-examples.php">
use Accelade\Forms\Components\EmojiInput;

// Basic emoji picker
EmojiInput::make('emoji')
    ->label('Select an Emoji');

// Filter to specific categories
EmojiInput::make('reaction')
    ->label('Reaction')
    ->categories(['smileys', 'people']);

// Multiple selection
EmojiInput::make('tags')
    ->label('Emoji Tags')
    ->multiple();

// Custom emojis
EmojiInput::make('status')
    ->customEmojis([
        'status' => [
            '✅' => 'Done',
            '🔄' => 'In Progress',
            '⏸️' => 'Paused',
        ],
    ]);

// Compact without search and preview
EmojiInput::make('quick')
    ->searchable(false)
    ->showPreview(false)
    ->gridColumns(10);
    </x-accelade::code-block>
</section>

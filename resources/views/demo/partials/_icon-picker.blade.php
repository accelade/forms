{{-- Icon Picker Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\IconPicker;
    use Accelade\Forms\Enums\IconSet;

    // Emoji only picker (default)
    $emojiPicker = IconPicker::make('emoji_icon')
        ->label('Emoji Icon')
        ->sets([IconSet::Emoji])
        ->searchable()
        ->showIconName()
        ->gridColumns(8);

    // All icon sets
    $allSetsPicker = IconPicker::make('all_icons')
        ->label('Choose Icon')
        ->sets([IconSet::Emoji, IconSet::Boxicons, IconSet::Heroicons, IconSet::Lucide])
        ->defaultSet(IconSet::Emoji)
        ->searchable()
        ->showIconName()
        ->gridColumns(8);

    // Boxicons only
    $boxiconsPicker = IconPicker::make('boxicon')
        ->label('Boxicons')
        ->sets([IconSet::Boxicons])
        ->searchable()
        ->gridColumns(8);

    // Heroicons only
    $heroiconsPicker = IconPicker::make('heroicon')
        ->label('Heroicons')
        ->sets([IconSet::Heroicons])
        ->searchable()
        ->gridColumns(8);

    // Lucide only
    $lucidePicker = IconPicker::make('lucide_icon')
        ->label('Lucide Icons')
        ->sets([IconSet::Lucide])
        ->searchable()
        ->gridColumns(8);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Icon Picker</h3>
    </div>
    <p class="text-sm mb-6" style="color: var(--docs-text-muted);">
        Select icons from multiple icon libraries including Emoji, Boxicons, Heroicons, and Lucide. Supports searching, categories, and keyboard navigation.
    </p>

    <div class="space-y-6 mb-6">
        {{-- All Icon Sets --}}
        <div class="rounded-xl p-4 border border-violet-500/30" style="background: rgba(139, 92, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-violet-500/20 text-violet-500 rounded">All Sets</span>
                Multiple Icon Libraries
            </h4>
            <p class="text-xs mb-3" style="color: var(--docs-text-muted);">
                Switch between Emoji, Boxicons, Heroicons, and Lucide icon sets using tabs.
            </p>

            {!! $allSetsPicker !!}
        </div>

        {{-- Emoji Only --}}
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Emoji</span>
                Native Emoji Icons
            </h4>
            <p class="text-xs mb-3" style="color: var(--docs-text-muted);">
                Unicode emoji organized by category: Smileys, Animals, Food, Activities, Travel, Objects, Symbols.
            </p>

            {!! $emojiPicker !!}
        </div>

        {{-- Boxicons --}}
        <div class="rounded-xl p-4 border border-blue-500/30" style="background: rgba(59, 130, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-blue-500/20 text-blue-500 rounded">Boxicons</span>
                Boxicons Library
            </h4>
            <p class="text-xs mb-3" style="color: var(--docs-text-muted);">
                Premium vector icons with regular and solid variants. Requires Boxicons CSS.
            </p>

            {!! $boxiconsPicker !!}
        </div>

        {{-- Heroicons --}}
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Heroicons</span>
                Heroicons by Tailwind
            </h4>
            <p class="text-xs mb-3" style="color: var(--docs-text-muted);">
                Beautiful hand-crafted SVG icons by the makers of Tailwind CSS. Outline and solid styles.
            </p>

            {!! $heroiconsPicker !!}
        </div>

        {{-- Lucide --}}
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Lucide</span>
                Lucide Icons
            </h4>
            <p class="text-xs mb-3" style="color: var(--docs-text-muted);">
                Community-driven fork of Feather icons with 1000+ icons. Clean and consistent.
            </p>

            {!! $lucidePicker !!}
        </div>
    </div>

    {{-- Code Examples --}}
    <div class="space-y-4">
        <h4 class="font-medium" style="color: var(--docs-text);">Usage Examples</h4>

        <x-accelade::code-block language="php" title="Icon Picker Examples">
use Accelade\Forms\Components\IconPicker;
use Accelade\Forms\Enums\IconSet;

// Emoji only (using IconSet enum)
IconPicker::make('icon')
    ->label('Choose Emoji')
    ->sets([IconSet::Emoji])
    ->searchable();

// All icon sets with tabs
IconPicker::make('icon')
    ->label('Choose Icon')
    ->sets([IconSet::Emoji, IconSet::Boxicons, IconSet::Heroicons, IconSet::Lucide])
    ->defaultSet(IconSet::Emoji)
    ->searchable()
    ->showIconName();

// Single icon library
IconPicker::make('icon')
    ->label('Heroicons')
    ->sets([IconSet::Heroicons])
    ->searchable()
    ->gridColumns(10);

// Multiple libraries (no emoji)
IconPicker::make('icon')
    ->label('Icon Libraries')
    ->sets([IconSet::Boxicons, IconSet::Heroicons, IconSet::Lucide])
    ->defaultSet(IconSet::Heroicons);

// String values still work for backward compatibility
IconPicker::make('icon')
    ->label('Lucide Icons')
    ->sets(['lucide'])
    ->disabled();
        </x-accelade::code-block>
    </div>

    {{-- Available Icon Sets --}}
    <div class="mt-6 pt-6 border-t border-[var(--docs-border)]">
        <h4 class="font-medium mb-4" style="color: var(--docs-text);">Available Icon Sets</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2 flex items-center gap-2" style="color: var(--docs-text);">
                    <span class="text-lg">😀</span> Emoji
                </h5>
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    Native Unicode emoji. No external dependencies. Categories: Smileys, Animals, Food, Activities, Travel, Objects, Symbols.
                </p>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2 flex items-center gap-2" style="color: var(--docs-text);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h16v16H4z"/></svg>
                    Boxicons
                </h5>
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    700+ premium vector icons. Regular and solid styles. Inline SVG - no external dependencies required.
                </p>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2 flex items-center gap-2" style="color: var(--docs-text);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Heroicons
                </h5>
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    By Tailwind CSS creators. Outline variants. Inline SVG - no external dependencies required.
                </p>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2 flex items-center gap-2" style="color: var(--docs-text);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    Lucide
                </h5>
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    1000+ community icons. Fork of Feather. Inline SVG - no external dependencies required.
                </p>
            </div>
        </div>
    </div>
</section>

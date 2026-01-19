{{-- Icon Picker Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\IconPicker;
    use Accelade\Forms\Enums\IconSet;

    // Blade Icons mode - lazy loading from any installed Blade Icons package
    $bladeIconsPicker = IconPicker::make('blade_icon')
        ->label('Blade Icons (Lazy Loading)')
        ->bladeIcons()
        ->perPage(48)
        ->searchable()
        ->gridColumns(8);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Icon Picker</h3>
    </div>
    <p class="text-sm mb-6" style="color: var(--docs-text-muted);">
        Select icons from any installed Blade Icons package. Supports searching, categories, and keyboard navigation.
    </p>

    <div class="space-y-6 mb-6">
        {{-- Blade Icons Mode --}}
        <div class="rounded-xl p-4 border border-pink-500/30" style="background: rgba(236, 72, 153, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-pink-500/20 text-pink-500 rounded">Blade Icons</span>
                Lazy Loading with Infinite Scroll
            </h4>
            <p class="text-xs mb-3" style="color: var(--docs-text-muted);">
                Automatically detects all installed Blade Icons packages. Icons load on-demand (48 at a time) as you scroll. Search icons by name across the selected set.
            </p>

            {!! $bladeIconsPicker !!}

            <div class="mt-4 p-3 rounded-lg border border-neutral-200 dark:border-neutral-700" style="background: var(--docs-bg);">
                <h5 class="text-xs font-semibold mb-2" style="color: var(--docs-text);">Install Icon Packages</h5>
                <p class="text-xs mb-2" style="color: var(--docs-text-muted);">
                    Install any Blade Icons package to use with the icon picker:
                </p>
                <div class="space-y-1 text-xs font-mono" style="color: var(--docs-text-muted);">
                    <div><code class="px-1.5 py-0.5 rounded bg-neutral-100 dark:bg-neutral-800">composer require blade-ui-kit/blade-heroicons</code></div>
                    <div><code class="px-1.5 py-0.5 rounded bg-neutral-100 dark:bg-neutral-800">composer require mallardduck/blade-boxicons</code></div>
                    <div><code class="px-1.5 py-0.5 rounded bg-neutral-100 dark:bg-neutral-800">composer require owenvoke/blade-fontawesome</code></div>
                </div>
            </div>
        </div>

        {{-- Note about Emoji --}}
        <div class="rounded-lg p-3 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <p class="text-xs" style="color: var(--docs-text-muted);">
                <strong class="text-amber-600 dark:text-amber-400">Note:</strong> For emoji selection, use the dedicated <code class="px-1 py-0.5 rounded bg-neutral-100 dark:bg-neutral-800">EmojiInput</code> component which provides a full emoji picker with categories and search.
            </p>
        </div>
    </div>

    {{-- Code Examples --}}
    <div class="space-y-4">
        <h4 class="font-medium" style="color: var(--docs-text);">Usage Examples</h4>

        <x-accelade::code-block language="php" title="Icon Picker Examples">
use Accelade\Forms\Components\IconPicker;
use Accelade\Forms\Enums\IconSet;

// Blade Icons mode - lazy loading with infinite scroll
// Automatically detects all installed Blade Icons packages
IconPicker::make('icon')
    ->label('Select Icon')
    ->bladeIcons()          // Enable Blade Icons mode
    ->perPage(48)           // Icons per page (default: 50)
    ->searchable();

// Custom icons
IconPicker::make('icon')
    ->label('Custom Icons')
    ->icons([
        'home', 'user', 'settings', 'search',
        'heart', 'star', 'mail', 'phone',
    ])
    ->searchable()
    ->gridColumns(4);

// Multiple selection
IconPicker::make('icons')
    ->label('Select Multiple Icons')
    ->bladeIcons()
    ->multiple()
    ->maxItems(5);
        </x-accelade::code-block>
    </div>

    {{-- Available Icon Sets --}}
    <div class="mt-6 pt-6 border-t border-[var(--docs-border)]">
        <h4 class="font-medium mb-4" style="color: var(--docs-text);">Available Icon Sets</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2 flex items-center gap-2" style="color: var(--docs-text);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    Blade Icons
                </h5>
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    Any installed Blade Icons package (Heroicons, Feather, Font Awesome, Tabler, etc.). Lazy loaded with infinite scroll.
                </p>
            </div>

            <div class="rounded-lg p-3" style="background: var(--docs-bg);">
                <h5 class="text-sm font-medium mb-2 flex items-center gap-2" style="color: var(--docs-text);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Custom Icons
                </h5>
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    Provide your own array of icon classes or names for project-specific icon sets.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Color Picker Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\ColorPicker;

    // Basic color picker
    $brandColorPicker = ColorPicker::make('brand_color')
        ->label('Brand Color')
        ->default('#6366f1');

    // With swatches
    $themeColorPicker = ColorPicker::make('theme_color')
        ->label('Theme Color')
        ->swatches([
            '#ef4444', '#f97316', '#eab308',
            '#22c55e', '#3b82f6', '#8b5cf6',
            '#ec4899', '#64748b',
        ])
        ->default('#3b82f6');

    // With preview
    $buttonColorPicker = ColorPicker::make('button_color')
        ->label('Button Color')
        ->default('#8b5cf6');

    // Tailwind palette
    $accentColorPicker = ColorPicker::make('accent_color')
        ->label('Accent Color')
        ->swatches([
            '#ef4444', '#f97316', '#f59e0b', '#eab308',
            '#84cc16', '#22c55e', '#10b981', '#14b8a6',
            '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1',
            '#8b5cf6', '#a855f7', '#d946ef', '#ec4899',
            '#f43f5e', '#64748b',
        ])
        ->default('#6366f1');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Color Picker</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Color selection with preset swatches and custom color input.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Color Picker -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Color Input
            </h4>

            {!! $brandColorPicker !!}
        </div>

        <!-- With Swatches -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Swatches</span>
                Preset Colors
            </h4>

            {!! $themeColorPicker !!}
        </div>

        <!-- With Preview -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Preview</span>
                With Live Preview
            </h4>

            {!! $buttonColorPicker !!}
        </div>

        <!-- Tailwind Colors Grid -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Palette</span>
                Color Palette
            </h4>

            {!! $accentColorPicker !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="color-picker-examples.php">
use Accelade\Forms\Components\ColorPicker;

// Basic color picker
ColorPicker::make('brand_color')
    ->label('Brand Color')
    ->default('#6366f1');

// With preset swatches
ColorPicker::make('theme_color')
    ->label('Theme Color')
    ->swatches([
        '#ef4444', '#f97316', '#eab308',
        '#22c55e', '#3b82f6', '#8b5cf6',
    ]);

// Different formats
ColorPicker::make('accent')
    ->hex();     // Default
ColorPicker::make('background')
    ->rgb();
ColorPicker::make('text_color')
    ->rgba();
    </x-accelade::code-block>
</section>

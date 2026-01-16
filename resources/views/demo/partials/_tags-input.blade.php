{{-- Tags Input Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\TagsInput;

    // Basic tags input (primary color - default)
    $skillsInput = TagsInput::make('skills')
        ->label('Skills')
        ->placeholder('Add a skill...')
        ->helperText('Press Enter or comma to add a tag');

    // With suggestions
    $technologiesInput = TagsInput::make('technologies')
        ->label('Technologies')
        ->placeholder('Type to search...')
        ->suggestions(['React', 'Angular', 'Svelte', 'Node.js', 'Python', 'Go'])
        ->color('info');

    // With max limit and warning color
    $categoriesInput = TagsInput::make('categories')
        ->label('Categories (max 3)')
        ->placeholder('Add a category...')
        ->maxTags(3)
        ->color('warning');

    // Danger color for important tags
    $alertsInput = TagsInput::make('alerts')
        ->label('Alert Keywords')
        ->placeholder('Add alert keyword...')
        ->color('danger');

    // Success color
    $tagsInput = TagsInput::make('tags')
        ->label('Approved Tags')
        ->placeholder('Add tag...')
        ->color('success');

    // Gray color
    $labelsInput = TagsInput::make('labels')
        ->label('Labels')
        ->placeholder('Add label...')
        ->color('gray');

    // With prefix/suffix
    $pricesInput = TagsInput::make('prices')
        ->label('Price Points')
        ->placeholder('Add price...')
        ->tagPrefix('$')
        ->color('success');

    // Reorderable
    $priorityInput = TagsInput::make('priority_items')
        ->label('Priority Items (drag to reorder)')
        ->placeholder('Add item...')
        ->reorderable()
        ->color('primary');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Tags Input</h3>
    </div>
    <p class="text-sm mb-6" style="color: var(--docs-text-muted);">
        Tag/chip input with Filament-style colors, prefix/suffix support, and reorderable functionality.
    </p>

    <div class="space-y-6 mb-6">
        <!-- Basic Tags Input -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Primary</span>
                Basic Tags (Default Color)
            </h4>
            <x-forms::tags-input :field="$skillsInput" />
        </div>

        <!-- With Suggestions -->
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">Info</span>
                With Autocomplete Suggestions
            </h4>
            <x-forms::tags-input :field="$technologiesInput" />
        </div>

        <!-- With Limit -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Warning</span>
                Maximum Tags Limit
            </h4>
            <x-forms::tags-input :field="$categoriesInput" />
        </div>

        <!-- Danger Color -->
        <div class="rounded-xl p-4 border border-red-500/30" style="background: rgba(239, 68, 68, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-red-500/20 text-red-500 rounded">Danger</span>
                Alert Keywords
            </h4>
            <x-forms::tags-input :field="$alertsInput" />
        </div>

        <!-- Success Color -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Success</span>
                Approved Tags
            </h4>
            <x-forms::tags-input :field="$tagsInput" />
        </div>

        <!-- Gray Color -->
        <div class="rounded-xl p-4 border border-gray-500/30" style="background: rgba(107, 114, 128, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-gray-500/20 text-gray-500 rounded">Gray</span>
                Labels
            </h4>
            <x-forms::tags-input :field="$labelsInput" />
        </div>

        <!-- With Prefix -->
        <div class="rounded-xl p-4 border border-green-500/30" style="background: rgba(34, 197, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-green-500/20 text-green-500 rounded">Prefix</span>
                With Tag Prefix
            </h4>
            <x-forms::tags-input :field="$pricesInput" />
        </div>

        <!-- Reorderable -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Reorderable</span>
                Drag to Reorder
            </h4>
            <x-forms::tags-input :field="$priorityInput" />
        </div>
    </div>

    <x-accelade::code-block language="php" filename="tags-input-examples.php">
use Accelade\Forms\Components\TagsInput;

// Basic tags input (primary color - default)
TagsInput::make('skills')
    ->label('Skills')
    ->placeholder('Add a skill...');

// With color presets
TagsInput::make('alerts')
    ->label('Alert Keywords')
    ->color('danger'); // danger, success, warning, info, primary, gray

// With suggestions
TagsInput::make('technologies')
    ->suggestions(['React', 'Vue.js', 'Angular', 'Svelte'])
    ->color('info');

// With max limit
TagsInput::make('categories')
    ->maxTags(3)
    ->color('warning');

// With prefix/suffix
TagsInput::make('prices')
    ->tagPrefix('$')
    ->tagSuffix('.00')
    ->color('success');

// Reorderable (drag to sort)
TagsInput::make('priority')
    ->reorderable();
    </x-accelade::code-block>
</section>

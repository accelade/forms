{{-- Toggle Buttons Component Section --}}
@props(['prefix' => 'a'])

@php
    $textAttr = match($prefix) {
        'v' => 'v-text',
        'data-state' => 'data-state-text',
        's' => 's-text',
        'ng' => 'ng-text',
        default => 'a-text',
    };

    $showAttr = match($prefix) {
        'v' => 'v-show',
        'data-state' => 'data-state-show',
        's' => 's-show',
        'ng' => 'ng-show',
        default => 'a-show',
    };

    use Accelade\Forms\Components\ToggleButtons;

    // Basic grouped toggle buttons
    $sizeField = ToggleButtons::make('size')
        ->label('Size Selection')
        ->options([
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
        ])
        ->default('medium')
        ->grouped();

    // With icons
    $viewField = ToggleButtons::make('view')
        ->label('View Mode')
        ->options([
            'list' => 'List',
            'grid' => 'Grid',
            'columns' => 'Columns',
        ])
        ->icons([
            'list' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>',
            'grid' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>',
            'columns' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>',
        ])
        ->default('grid')
        ->grouped();

    // Status with colors (separate buttons)
    $statusField = ToggleButtons::make('status')
        ->label('Status')
        ->options([
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ])
        ->colors([
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ])
        ->default('pending')
        ->grouped(false);

    // Boolean toggle
    $activeField = ToggleButtons::make('active')
        ->label('Active Status')
        ->boolean('Active', 'Inactive')
        ->colors([
            '1' => 'success',
            '0' => 'danger',
        ])
        ->default('1')
        ->grouped();

    // Priority with all color presets (separate)
    $priorityField = ToggleButtons::make('priority')
        ->label('Priority Level')
        ->options([
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ])
        ->colors([
            'low' => 'gray',
            'medium' => 'info',
            'high' => 'warning',
            'critical' => 'danger',
        ])
        ->default('medium')
        ->grouped(false);

    // Category with grid columns
    $categoryField = ToggleButtons::make('category')
        ->label('Category (Grid Layout)')
        ->options([
            'tech' => 'Technology',
            'design' => 'Design',
            'marketing' => 'Marketing',
            'sales' => 'Sales',
            'support' => 'Support',
            'other' => 'Other',
        ])
        ->colors([
            'tech' => 'primary',
            'design' => 'info',
            'marketing' => 'success',
            'sales' => 'warning',
            'support' => 'gray',
            'other' => 'gray',
        ])
        ->default('tech')
        ->grouped(false)
        ->columns(3);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Toggle Buttons</h3>
    </div>
    <p class="text-sm mb-6" style="color: var(--docs-text-muted);">
        Button group for selecting single options with Filament-style colors and layouts.
    </p>

    <div class="space-y-6 mb-6">
        <!-- Basic Grouped Toggle Buttons -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Grouped</span>
                Basic Selection
            </h4>
            <x-forms::toggle-buttons :field="$sizeField" />
        </div>

        <!-- With Icons -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Icons</span>
                View Selector with Icons
            </h4>
            <x-forms::toggle-buttons :field="$viewField" />
        </div>

        <!-- Status with Colors (Separate Buttons) -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Colors</span>
                Status with Semantic Colors
            </h4>
            <x-forms::toggle-buttons :field="$statusField" />
        </div>

        <!-- Boolean Toggle -->
        <div class="rounded-xl p-4 border border-blue-500/30" style="background: rgba(59, 130, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-blue-500/20 text-blue-500 rounded">Boolean</span>
                Yes/No Toggle
            </h4>
            <x-forms::toggle-buttons :field="$activeField" />
        </div>

        <!-- Priority with All Colors -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Separate</span>
                Priority with Multiple Colors
            </h4>
            <x-forms::toggle-buttons :field="$priorityField" />
        </div>

        <!-- Grid Layout -->
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">Grid</span>
                Grid Layout (3 Columns)
            </h4>
            <x-forms::toggle-buttons :field="$categoryField" />
        </div>
    </div>

    <x-accelade::code-block language="php" filename="toggle-buttons-examples.php">
use Accelade\Forms\Components\ToggleButtons;

// Basic grouped toggle buttons (Filament style)
ToggleButtons::make('size')
    ->label('Size')
    ->options([
        'small' => 'Small',
        'medium' => 'Medium',
        'large' => 'Large',
    ])
    ->grouped(); // Connected buttons (default)

// With icons
ToggleButtons::make('view')
    ->options([
        'list' => 'List',
        'grid' => 'Grid',
    ])
    ->icons([
        'list' => '&lt;svg&gt;...&lt;/svg&gt;',
        'grid' => '&lt;svg&gt;...&lt;/svg&gt;',
    ])
    ->grouped();

// Separate buttons with semantic colors
ToggleButtons::make('status')
    ->options([
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ])
    ->colors([
        'pending' => 'warning',  // Yellow
        'approved' => 'success', // Green
        'rejected' => 'danger',  // Red
    ])
    ->grouped(false); // Separate buttons with gap

// Boolean toggle (Yes/No)
ToggleButtons::make('active')
    ->boolean('Active', 'Inactive')
    ->colors([
        '1' => 'success',
        '0' => 'danger',
    ]);

// Grid layout
ToggleButtons::make('category')
    ->options([...])
    ->grouped(false)
    ->columns(3); // 3-column grid

// Available color presets:
// 'primary', 'danger', 'success', 'warning', 'info', 'gray'
// Or use custom hex: '#ff6b6b'
    </x-accelade::code-block>
</section>

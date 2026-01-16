{{-- Checkbox List Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\CheckboxList;

    // Basic checkbox list
    $basicCheckboxList = CheckboxList::make('basic_interests')
        ->label('Select your interests')
        ->options([
            'tech' => 'Technology',
            'sports' => 'Sports',
            'music' => 'Music',
            'travel' => 'Travel',
            'food' => 'Food & Cooking',
            'art' => 'Art & Design',
        ]);

    // Multi-column layout
    $columnsCheckboxList = CheckboxList::make('skills')
        ->label('Select your skills')
        ->options([
            'php' => 'PHP',
            'javascript' => 'JavaScript',
            'python' => 'Python',
            'ruby' => 'Ruby',
            'go' => 'Go',
            'rust' => 'Rust',
            'java' => 'Java',
            'csharp' => 'C#',
            'swift' => 'Swift',
        ])
        ->columns(3);

    // With descriptions
    $descriptionsCheckboxList = CheckboxList::make('plans')
        ->label('Choose your subscription plans')
        ->options([
            'basic' => 'Basic Plan',
            'pro' => 'Pro Plan',
            'enterprise' => 'Enterprise Plan',
        ])
        ->descriptions([
            'basic' => 'Perfect for individuals, includes basic features',
            'pro' => 'For professionals, includes advanced analytics',
            'enterprise' => 'For teams, includes priority support and API access',
        ]);

    // Searchable
    $searchableCheckboxList = CheckboxList::make('countries')
        ->label('Select countries')
        ->options([
            'us' => 'United States',
            'uk' => 'United Kingdom',
            'ca' => 'Canada',
            'au' => 'Australia',
            'de' => 'Germany',
            'fr' => 'France',
            'jp' => 'Japan',
            'br' => 'Brazil',
            'in' => 'India',
            'mx' => 'Mexico',
        ])
        ->searchable()
        ->searchPrompt('Search countries...')
        ->noSearchResultsMessage('No countries found');

    // Bulk toggleable
    $bulkCheckboxList = CheckboxList::make('permissions')
        ->label('User Permissions')
        ->options([
            'create' => 'Create',
            'read' => 'Read',
            'update' => 'Update',
            'delete' => 'Delete',
            'export' => 'Export',
            'import' => 'Import',
        ])
        ->columns(3)
        ->bulkToggleable()
        ->selectAllActionLabel('Grant all')
        ->deselectAllActionLabel('Revoke all');

    // With disabled options
    $disabledOptionsCheckboxList = CheckboxList::make('features')
        ->label('Available Features')
        ->options([
            'dashboard' => 'Dashboard',
            'reports' => 'Reports',
            'analytics' => 'Analytics (Pro)',
            'api' => 'API Access (Enterprise)',
        ])
        ->disableOptionWhen(fn (string $value): bool => in_array($value, ['analytics', 'api']))
        ->helperText('Upgrade your plan to unlock disabled features');

    // Combined: searchable + bulk + columns + descriptions
    $fullFeaturedCheckboxList = CheckboxList::make('technologies')
        ->label('Technology Stack')
        ->options([
            'laravel' => 'Laravel',
            'vue' => 'Vue.js',
            'react' => 'React',
            'angular' => 'Angular',
            'svelte' => 'Svelte',
            'tailwind' => 'Tailwind CSS',
            'bootstrap' => 'Bootstrap',
            'mysql' => 'MySQL',
            'postgres' => 'PostgreSQL',
            'redis' => 'Redis',
            'docker' => 'Docker',
            'kubernetes' => 'Kubernetes',
        ])
        ->descriptions([
            'laravel' => 'PHP web framework',
            'vue' => 'Progressive JavaScript framework',
            'react' => 'JavaScript library for building UIs',
            'angular' => 'TypeScript-based web framework',
            'svelte' => 'Compiler-based JavaScript framework',
            'tailwind' => 'Utility-first CSS framework',
            'bootstrap' => 'Popular CSS framework',
            'mysql' => 'Relational database',
            'postgres' => 'Advanced relational database',
            'redis' => 'In-memory data store',
            'docker' => 'Container platform',
            'kubernetes' => 'Container orchestration',
        ])
        ->columns(2)
        ->searchable()
        ->bulkToggleable()
        ->default(['laravel', 'vue', 'tailwind']);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Checkbox List</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Multi-select checkbox component with grid layouts, search, bulk toggle, descriptions, and more.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Basic</span>
                Basic Checkbox List
            </h4>

            {!! $basicCheckboxList !!}
        </div>

        <!-- Multi-column -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Columns</span>
                Multi-Column Layout
            </h4>

            {!! $columnsCheckboxList !!}
        </div>

        <!-- With Descriptions -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Descriptions</span>
                With Descriptions
            </h4>

            {!! $descriptionsCheckboxList !!}
        </div>

        <!-- Searchable -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Searchable</span>
                Searchable Options
            </h4>

            {!! $searchableCheckboxList !!}
        </div>

        <!-- Bulk Toggle -->
        <div class="rounded-xl p-4 border border-violet-500/30" style="background: rgba(139, 92, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-violet-500/20 text-violet-500 rounded">Bulk</span>
                Bulk Toggle (Select All / Deselect All)
            </h4>

            {!! $bulkCheckboxList !!}
        </div>

        <!-- Disabled Options -->
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Disabled</span>
                Disabled Options
            </h4>

            {!! $disabledOptionsCheckboxList !!}
        </div>

        <!-- Full Featured -->
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">Full</span>
                Full Featured Example
            </h4>

            {!! $fullFeaturedCheckboxList !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="checkbox-list-examples.php">
use Accelade\Forms\Components\CheckboxList;

// Basic checkbox list
CheckboxList::make('interests')
    ->label('Select your interests')
    ->options([
        'tech' => 'Technology',
        'sports' => 'Sports',
        'music' => 'Music',
    ]);

// Multi-column layout
CheckboxList::make('skills')
    ->label('Select your skills')
    ->options([...])
    ->columns(3);

// With descriptions
CheckboxList::make('plans')
    ->label('Choose your subscription')
    ->options([
        'basic' => 'Basic Plan',
        'pro' => 'Pro Plan',
    ])
    ->descriptions([
        'basic' => 'Perfect for individuals',
        'pro' => 'For professionals',
    ]);

// Searchable
CheckboxList::make('countries')
    ->label('Select countries')
    ->options([...])
    ->searchable()
    ->searchPrompt('Search countries...')
    ->noSearchResultsMessage('No countries found');

// Bulk toggle
CheckboxList::make('permissions')
    ->label('User Permissions')
    ->options([...])
    ->bulkToggleable()
    ->selectAllActionLabel('Grant all')
    ->deselectAllActionLabel('Revoke all');

// Disable specific options
CheckboxList::make('features')
    ->label('Available Features')
    ->options([...])
    ->disableOptionWhen(fn ($value) => in_array($value, ['premium', 'enterprise']));

// Grid direction (column or row)
CheckboxList::make('items')
    ->options([...])
    ->columns(3)
    ->gridDirection('row'); // Options flow left-to-right, then wrap

// Full featured
CheckboxList::make('technologies')
    ->label('Technology Stack')
    ->options([...])
    ->descriptions([...])
    ->columns(2)
    ->searchable()
    ->bulkToggleable()
    ->default(['laravel', 'vue']);
    </x-accelade::code-block>
</section>

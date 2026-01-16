{{-- Repeater Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\Repeater;
    use Accelade\Forms\Components\TextInput;

    // Basic repeater - Team Members
    $teamRepeater = Repeater::make('team')
        ->label('Team')
        ->schema([
            TextInput::make('name')->label('Name')->required(),
            TextInput::make('email')->label('Email')->email()->required(),
        ])
        ->default([
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
        ])
        ->minItems(1)
        ->maxItems(5)
        ->cloneable()
        ->collapsible()
        ->reorderable();

    // Address Repeater
    $addressRepeater = Repeater::make('addresses')
        ->label('Addresses')
        ->schema([
            TextInput::make('street')->label('Street')->required(),
            TextInput::make('city')->label('City')->required(),
            TextInput::make('zip')->label('ZIP')->required(),
        ])
        ->default([
            ['street' => '123 Main St', 'city' => 'New York', 'zip' => '10001'],
        ])
        ->maxItems(3)
        ->collapsible()
        ->reorderable()
        ->helperText('Maximum 3 addresses');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Repeater</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Repeatable field groups for dynamic form sections.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Repeater -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Team Members
            </h4>

            {!! $teamRepeater !!}
        </div>

        <!-- Address Repeater -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Complex</span>
                Shipping Addresses
            </h4>

            {!! $addressRepeater !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="repeater-examples.php">
use Accelade\Forms\Components\Repeater;
use Accelade\Forms\Components\TextInput;

// Basic repeater
Repeater::make('team')
    ->label('Team Members')
    ->schema([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),
    ])
    ->minItems(1)
    ->maxItems(5)
    ->cloneable()
    ->collapsible()
    ->reorderable();

// With default items
Repeater::make('addresses')
    ->label('Addresses')
    ->schema([
        TextInput::make('street')->required(),
        TextInput::make('city')->required(),
        TextInput::make('zip')->required(),
    ])
    ->default([
        ['street' => '123 Main St', 'city' => 'New York', 'zip' => '10001'],
    ]);
    </x-accelade::code-block>
</section>

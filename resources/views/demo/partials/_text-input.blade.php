{{-- Text Input Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\TextInput;

    // Basic text inputs
    $nameInput = TextInput::make('name')
        ->label('Name')
        ->placeholder('Enter your name');

    $emailInput = TextInput::make('email')
        ->label('Email')
        ->placeholder('Enter your email')
        ->required()
        ->helperText("We'll never share your email with anyone.");

    // Input types
    $passwordInput = TextInput::make('password')
        ->password()
        ->label('Password')
        ->placeholder('Enter password');

    $urlInput = TextInput::make('website_url')
        ->url()
        ->label('URL')
        ->placeholder('https://example.com');

    $phoneInput = TextInput::make('phone')
        ->tel()
        ->label('Phone')
        ->placeholder('+1 (555) 000-0000');

    $numberInput = TextInput::make('quantity')
        ->numeric()
        ->label('Number')
        ->placeholder('0');

    // With prefix/suffix
    $websiteInput = TextInput::make('website')
        ->label('Website')
        ->prefix('https://')
        ->placeholder('example.com');

    $priceInput = TextInput::make('price')
        ->numeric()
        ->label('Price')
        ->prefix('$')
        ->suffix('USD')
        ->placeholder('0.00');

    // States
    $disabledInput = TextInput::make('disabled_field')
        ->label('Disabled')
        ->default('Disabled input')
        ->disabled();

    $readonlyInput = TextInput::make('readonly_field')
        ->label('Readonly')
        ->default('Readonly input')
        ->readonly();
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Text Input</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        A versatile text input component supporting various input types.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Text Input -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Simple Text Input
            </h4>

            <div class="space-y-4">
                {!! $nameInput !!}
                {!! $emailInput !!}
            </div>
        </div>

        <!-- Input Types -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Types</span>
                Input Types
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $passwordInput !!}
                {!! $urlInput !!}
                {!! $phoneInput !!}
                {!! $numberInput !!}
            </div>
        </div>

        <!-- With Prefix/Suffix -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Addons</span>
                Prefix &amp; Suffix
            </h4>

            <div class="space-y-4">
                {!! $websiteInput !!}
                {!! $priceInput !!}
            </div>
        </div>

        <!-- States -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">States</span>
                Input States
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $disabledInput !!}
                {!! $readonlyInput !!}
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="text-input-examples.php">
use Accelade\Forms\Components\TextInput;

// Basic text input
TextInput::make('name')
    ->label('Full Name')
    ->placeholder('Enter your name')
    ->required();

// Email input
TextInput::make('email')
    ->email()
    ->required()
    ->hint('We\'ll never share your email');

// Password input
TextInput::make('password')
    ->password()
    ->minLength(8);

// With prefix/suffix
TextInput::make('price')
    ->numeric()
    ->prefix('$')
    ->suffix('USD');

// URL input
TextInput::make('website')
    ->url()
    ->prefix('https://');
    </x-accelade::code-block>
</section>

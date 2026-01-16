{{-- Radio Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\Radio;

    // Basic radio group
    $paymentRadio = Radio::make('payment')
        ->label('Payment Method')
        ->options([
            'card' => 'Credit Card',
            'paypal' => 'PayPal',
            'bank' => 'Bank Transfer',
        ])
        ->default('card');

    // Inline radio
    $sizeRadio = Radio::make('size')
        ->label('Size')
        ->options([
            's' => 'Small',
            'm' => 'Medium',
            'l' => 'Large',
            'xl' => 'X-Large',
        ])
        ->inline()
        ->default('m');

    // With descriptions
    $planRadio = Radio::make('plan')
        ->label('Subscription Plan')
        ->options([
            'free' => 'Free Plan',
            'pro' => 'Pro Plan - $9/mo',
            'enterprise' => 'Enterprise - $29/mo',
        ])
        ->descriptions([
            'free' => 'Basic features for personal use',
            'pro' => 'Advanced features for professionals',
            'enterprise' => 'Full features for teams',
        ])
        ->default('pro');

    // Multi-column
    $colorRadio = Radio::make('color')
        ->label('Favorite Color')
        ->options([
            'red' => 'Red',
            'blue' => 'Blue',
            'green' => 'Green',
            'yellow' => 'Yellow',
            'purple' => 'Purple',
            'orange' => 'Orange',
        ])
        ->columns(3)
        ->default('blue');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Radio</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Radio button group for single selection from multiple options.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Radio -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Radio Group
            </h4>

            {!! $paymentRadio !!}
        </div>

        <!-- Inline Radio -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Inline</span>
                Inline Layout
            </h4>

            {!! $sizeRadio !!}
        </div>

        <!-- With Descriptions -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Rich</span>
                With Descriptions
            </h4>

            {!! $planRadio !!}
        </div>

        <!-- Columns Layout -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Columns</span>
                Multi-Column Layout
            </h4>

            {!! $colorRadio !!}

            <div class="mt-4 space-y-2">
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">columns(2)</span>
                    <span style="color: var(--docs-text);">2-column grid layout</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">columns(3)</span>
                    <span style="color: var(--docs-text);">3-column grid layout</span>
                </div>
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="radio-examples.php">
use Accelade\Forms\Components\Radio;

// Basic radio group
Radio::make('payment')
    ->label('Payment Method')
    ->options([
        'card' => 'Credit Card',
        'paypal' => 'PayPal',
        'bank' => 'Bank Transfer',
    ])
    ->default('card');

// Inline layout
Radio::make('size')
    ->label('Size')
    ->options(['s' => 'Small', 'm' => 'Medium', 'l' => 'Large'])
    ->inline()
    ->default('m');

// With descriptions
Radio::make('plan')
    ->label('Subscription Plan')
    ->options([
        'free' => 'Free Plan',
        'pro' => 'Pro Plan - $9/mo',
        'enterprise' => 'Enterprise - $29/mo',
    ])
    ->descriptions([
        'free' => 'Basic features for personal use',
        'pro' => 'Advanced features for professionals',
        'enterprise' => 'Full features for teams',
    ]);

// Multi-column layout
Radio::make('color')
    ->label('Favorite Color')
    ->options([
        'red' => 'Red',
        'blue' => 'Blue',
        'green' => 'Green',
    ])
    ->columns(3);
    </x-accelade::code-block>
</section>

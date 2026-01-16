{{-- Rate Input Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\RateInput;

    // Basic 5-star rating
    $productRating = RateInput::make('rating')
        ->label('Rate this product')
        ->maxRating(5)
        ->default(4);

    // With value display
    $scoreRating = RateInput::make('score')
        ->label('Your rating')
        ->maxRating(5)
        ->default(3)
        ->showValue()
        ->color('success');

    // Custom icon (heart)
    $loveRating = RateInput::make('love')
        ->label('How much do you love it?')
        ->maxRating(5)
        ->default(5)
        ->icon('heart')
        ->color('danger');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Rate Input</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Star rating input for reviews and feedback.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Rating -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                5-Star Rating
            </h4>

            {!! $productRating !!}
        </div>

        <!-- With Value Display -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Display</span>
                With Value
            </h4>

            {!! $scoreRating !!}
        </div>

        <!-- Custom Icons -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Custom</span>
                Heart Rating
            </h4>

            {!! $loveRating !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="rate-input-examples.php">
use Accelade\Forms\Components\RateInput;

// Basic 5-star rating
RateInput::make('rating')
    ->label('Rate this product')
    ->maxRating(5);

// With value display
RateInput::make('score')
    ->label('Your rating')
    ->showValue()
    ->color('success');

// Custom icon
RateInput::make('love')
    ->label('How much do you love it?')
    ->icon('heart')
    ->color('danger');

// 10-point scale
RateInput::make('score')
    ->label('Score')
    ->maxRating(10)
    ->showValue();
    </x-accelade::code-block>
</section>

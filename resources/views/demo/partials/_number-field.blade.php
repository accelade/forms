{{-- Number Field Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\NumberField;

    // Basic number field
    $quantityField = NumberField::make('quantity')
        ->label('Quantity')
        ->default(1)
        ->minValue(1)
        ->maxValue(99);

    // With min/max constraints
    $ageField = NumberField::make('age')
        ->label('Age (18-100)')
        ->default(25)
        ->minValue(18)
        ->maxValue(100);

    $ratingField = NumberField::make('rating')
        ->label('Rating (0-5)')
        ->default(3)
        ->minValue(0)
        ->maxValue(5)
        ->step(0.5);

    // Currency formatting
    $priceField = NumberField::make('price')
        ->label('Price')
        ->prefix('$')
        ->suffix('USD')
        ->default(99.99)
        ->step(0.01)
        ->minValue(0);

    $percentageField = NumberField::make('percentage')
        ->label('Percentage')
        ->suffix('%')
        ->default(50)
        ->minValue(0)
        ->maxValue(100);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Number Field</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Number input with increment/decrement buttons and formatting options.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Number Field -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Number Input with Stepper
            </h4>

            {!! $quantityField !!}
        </div>

        <!-- With Min/Max -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Range</span>
                With Min/Max Constraints
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $ageField !!}
                {!! $ratingField !!}
            </div>
        </div>

        <!-- With Prefix/Suffix -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Format</span>
                Currency Formatting
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $priceField !!}
                {!! $percentageField !!}
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="number-field-examples.php">
use Accelade\Forms\Components\NumberField;

// Basic number input
NumberField::make('quantity')
    ->label('Quantity')
    ->default(1)
    ->minValue(1)
    ->maxValue(99);

// With step
NumberField::make('rating')
    ->label('Rating')
    ->minValue(0)
    ->maxValue(5)
    ->step(0.5);

// Currency format
NumberField::make('price')
    ->label('Price')
    ->prefix('$')
    ->suffix('USD')
    ->step(0.01);

// Percentage
NumberField::make('discount')
    ->label('Discount')
    ->suffix('%')
    ->minValue(0)
    ->maxValue(100);
    </x-accelade::code-block>
</section>

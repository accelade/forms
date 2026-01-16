{{-- Slider Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\Slider;

    // Basic slider
    $volumeSlider = Slider::make('volume')
        ->label('Volume')
        ->minValue(0)
        ->maxValue(100)
        ->default(50);

    // With marks
    $temperatureSlider = Slider::make('temperature')
        ->label('Temperature')
        ->minValue(0)
        ->maxValue(100)
        ->step(25)
        ->default(25);

    // Step values
    $brightnessSlider = Slider::make('brightness')
        ->label('Brightness (step: 10)')
        ->minValue(0)
        ->maxValue(100)
        ->step(10)
        ->default(70);

    $priceSlider = Slider::make('price')
        ->label('Price (step: 5)')
        ->minValue(0)
        ->maxValue(100)
        ->step(5)
        ->default(45);

    // Custom colors
    $successSlider = Slider::make('success_slider')
        ->label('Success')
        ->minValue(0)
        ->maxValue(100)
        ->default(80);

    $warningSlider = Slider::make('warning_slider')
        ->label('Warning')
        ->minValue(0)
        ->maxValue(100)
        ->default(60);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Slider</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Range slider for selecting numeric values visually.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Slider -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Simple Slider
            </h4>

            {!! $volumeSlider !!}
        </div>

        <!-- With Marks -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Marks</span>
                With Tick Marks
            </h4>

            {!! $temperatureSlider !!}
        </div>

        <!-- Step Values -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Step</span>
                Step Values
            </h4>

            <div class="space-y-4">
                {!! $brightnessSlider !!}
                {!! $priceSlider !!}
            </div>
        </div>

        <!-- Custom Colors -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Colors</span>
                Custom Colors
            </h4>

            <div class="space-y-4">
                {!! $successSlider !!}
                {!! $warningSlider !!}
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="slider-examples.php">
use Accelade\Forms\Components\Slider;

// Basic slider
Slider::make('volume')
    ->label('Volume')
    ->minValue(0)
    ->maxValue(100)
    ->default(50);

// With step
Slider::make('brightness')
    ->label('Brightness')
    ->minValue(0)
    ->maxValue(100)
    ->step(10);

// With marks
Slider::make('temperature')
    ->label('Temperature')
    ->minValue(0)
    ->maxValue(100)
    ->step(25)
    ->marks([0, 25, 50, 75, 100]);

// Custom color
Slider::make('rating')
    ->label('Rating')
    ->color('success');
    </x-accelade::code-block>
</section>

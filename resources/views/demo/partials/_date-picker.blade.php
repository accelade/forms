{{-- Date Picker Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\DatePicker;

    // Flatpickr date picker (default)
    $basicDatePicker = DatePicker::make('appointment_date')
        ->label('Appointment Date')
        ->placeholder('Select a date...')
        ->displayFormat('F j, Y');

    // Date picker with default value
    $defaultDatePicker = DatePicker::make('event_date')
        ->label('Event Date')
        ->default(now()->format('Y-m-d'))
        ->displayFormat('l, F j, Y');

    // With constraints
    $futureDatePicker = DatePicker::make('future_date')
        ->label('Future Date Only')
        ->minDate('today')
        ->displayFormat('M j, Y')
        ->helperText('Cannot select past dates');

    // This year only
    $thisYearPicker = DatePicker::make('this_year_date')
        ->label('This Year Only')
        ->minDate(now()->startOfYear()->format('Y-m-d'))
        ->maxDate(now()->endOfYear()->format('Y-m-d'))
        ->displayFormat('M j, Y')
        ->helperText('Limited to ' . date('Y'));

    // Date range selection
    $rangePicker = DatePicker::make('date_range')
        ->label('Date Range')
        ->range()
        ->displayFormat('M j, Y')
        ->placeholder('Select date range...');

    // Week numbers
    $weekNumbersPicker = DatePicker::make('week_date')
        ->label('With Week Numbers')
        ->weekNumbers()
        ->displayFormat('M j, Y');

    // Native browser input
    $nativePicker = DatePicker::make('native_date')
        ->label('Native Date Input')
        ->native()
        ->helperText('Uses browser native date picker');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Date Picker</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Date selection with Flatpickr. Use <code class="text-xs bg-gray-800 px-1 py-0.5 rounded">->native()</code> for browser inputs.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Flatpickr Date Picker -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Flatpickr</span>
                Date Picker
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $basicDatePicker !!}
                {!! $defaultDatePicker !!}
            </div>
        </div>

        <!-- With Constraints -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Constrained</span>
                Min/Max Dates
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $futureDatePicker !!}
                {!! $thisYearPicker !!}
            </div>
        </div>

        <!-- Date Range -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Range</span>
                Date Range Selection
            </h4>

            {!! $rangePicker !!}
        </div>

        <!-- Week Numbers -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Options</span>
                With Week Numbers
            </h4>

            {!! $weekNumbersPicker !!}
        </div>

        <!-- Native Input -->
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Native</span>
                Browser Native Input
            </h4>

            {!! $nativePicker !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="date-picker-examples.php">
use Accelade\Forms\Components\DatePicker;

// Flatpickr date picker (default)
DatePicker::make('appointment_date')
    ->label('Appointment Date')
    ->displayFormat('F j, Y');

// With min/max constraints
DatePicker::make('future_date')
    ->label('Future Date Only')
    ->minDate('today')
    ->maxDate(now()->addDays(30));

// Date range selection
DatePicker::make('date_range')
    ->label('Date Range')
    ->range()
    ->displayFormat('M j, Y');

// Native browser input
DatePicker::make('native_date')
    ->label('Native')
    ->native();

// With custom Flatpickr options
DatePicker::make('custom')
    ->label('Custom')
    ->weekNumbers()
    ->flatpickr([
        'locale' => ['firstDayOfWeek' => 0], // Sunday
    ]);
    </x-accelade::code-block>
</section>

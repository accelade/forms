{{-- Date Range Picker Component Section --}}
@props(['prefix' => 'a'])

@php
    $textAttr = match($prefix) {
        'v' => 'v-text',
        'data-state' => 'data-state-text',
        's' => 's-text',
        'ng' => 'ng-text',
        default => 'a-text',
    };

    // Create date range picker instances
    $basicRange = \Accelade\Forms\Components\DateRangePicker::make('basic_range')
        ->label('Select Date Range')
        ->placeholder('Choose dates...');

    $multiMonthRange = \Accelade\Forms\Components\DateRangePicker::make('multi_month_range')
        ->label('Project Duration')
        ->numberOfMonths(2)
        ->weekNumbers();

    $constrainedRange = \Accelade\Forms\Components\DateRangePicker::make('constrained_range')
        ->label('Booking Period')
        ->minDate(now())
        ->maxDate(now()->addMonths(6))
        ->placeholder('Select booking dates...');

    $nativeRange = \Accelade\Forms\Components\DateRangePicker::make('native_range')
        ->label('Native Date Range')
        ->native()
        ->startDatePlaceholder('Check-in')
        ->endDatePlaceholder('Check-out');

    $customSeparator = \Accelade\Forms\Components\DateRangePicker::make('custom_separator')
        ->label('Custom Separator')
        ->separator(' - ')
        ->displayFormat('M j, Y');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Date Range Picker</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Select a date range with Flatpickr integration. Supports range mode, multi-month display, and native browser fallback.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Flatpickr Range -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Flatpickr</span>
                Basic Range Selection
            </h4>

            <div class="space-y-3">
                <x-forms::date-range-picker :field="$basicRange" />
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    Click to open the Flatpickr calendar in range mode. Select start and end dates.
                </p>
            </div>
        </div>

        <!-- Multi-Month Display -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Multi-Month</span>
                Two-Month Calendar View
            </h4>

            <div class="space-y-3">
                <x-forms::date-range-picker :field="$multiMonthRange" />
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    Shows two months side by side with week numbers enabled.
                </p>
            </div>
        </div>

        <!-- Constrained Range -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Constrained</span>
                Date Limits Applied
            </h4>

            <div class="space-y-3">
                <x-forms::date-range-picker :field="$constrainedRange" />
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    Dates are limited from today to 6 months ahead. Past dates are disabled.
                </p>
            </div>
        </div>

        <!-- Custom Display Format -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Custom</span>
                Custom Separator & Format
            </h4>

            <div class="space-y-3">
                <x-forms::date-range-picker :field="$customSeparator" />
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    Uses " - " separator and "M j, Y" display format (e.g., Jan 15, 2024 - Jan 20, 2024).
                </p>
            </div>
        </div>

        <!-- Native Browser Fallback -->
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Native</span>
                Browser Native Inputs
            </h4>

            <div class="space-y-3">
                <x-forms::date-range-picker :field="$nativeRange" />
                <p class="text-xs" style="color: var(--docs-text-muted);">
                    Uses native browser date inputs (two separate inputs). Good for mobile.
                </p>
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="date-range-picker-examples.php">
use Accelade\Forms\Components\DateRangePicker;

// Basic Flatpickr range picker
DateRangePicker::make('dates')
    ->label('Select Date Range');

// Multi-month with week numbers
DateRangePicker::make('project')
    ->label('Project Duration')
    ->numberOfMonths(2)
    ->weekNumbers();

// With date constraints
DateRangePicker::make('booking')
    ->label('Booking Period')
    ->minDate(now())
    ->maxDate(now()->addMonths(6));

// Custom display format and separator
DateRangePicker::make('vacation')
    ->label('Vacation Dates')
    ->separator(' - ')
    ->displayFormat('M j, Y');

// Native browser inputs (fallback)
DateRangePicker::make('dates')
    ->label('Date Range')
    ->native()
    ->startDatePlaceholder('Start')
    ->endDatePlaceholder('End');

// Inline calendar display
DateRangePicker::make('calendar')
    ->label('Select Range')
    ->inline();
    </x-accelade::code-block>
</section>

{{-- DateTime Picker Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\DateTimePicker;

    // Flatpickr DateTime picker
    $eventPicker = DateTimePicker::make('event_at')
        ->label('Event Date & Time')
        ->placeholder('Select date and time...')
        ->displayFormat('F j, Y h:i K');

    // Date only picker
    $datePicker = DateTimePicker::make('birth_date')
        ->label('Birth Date')
        ->dateOnly()
        ->displayFormat('F j, Y')
        ->placeholder('Select date...');

    // Time only picker
    $timePicker = DateTimePicker::make('meeting_time')
        ->label('Meeting Time')
        ->timeOnly()
        ->minuteIncrement(15)
        ->placeholder('Select time...');

    // Date range picker
    $rangePicker = DateTimePicker::make('vacation')
        ->label('Vacation Period')
        ->dateOnly()
        ->range()
        ->displayFormat('M j, Y')
        ->placeholder('Select date range...');

    // Native datetime input
    $nativePicker = DateTimePicker::make('appointment')
        ->label('Appointment (Native)')
        ->native()
        ->helperText('Uses browser native datetime input');

    // Constrained picker
    $constrainedPicker = DateTimePicker::make('booking')
        ->label('Booking (Next 30 days)')
        ->dateOnly()
        ->minDate('today')
        ->maxDate(now()->addDays(30)->format('Y-m-d'))
        ->displayFormat('l, F j, Y');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">DateTime Picker</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Date and time selection with Flatpickr. Use <code class="text-xs bg-gray-800 px-1 py-0.5 rounded">->native()</code> for browser inputs.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Flatpickr DateTime Picker -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Flatpickr</span>
                DateTime Picker
            </h4>

            {!! $eventPicker !!}
        </div>

        <!-- Date Only -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Date</span>
                Date Only
            </h4>

            {!! $datePicker !!}
        </div>

        <!-- Time Only -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Time</span>
                Time Only
            </h4>

            {!! $timePicker !!}
        </div>

        <!-- Date Range -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Range</span>
                Date Range
            </h4>

            {!! $rangePicker !!}
        </div>

        <!-- Constrained -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Constrained</span>
                With Min/Max Date
            </h4>

            {!! $constrainedPicker !!}
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

    <x-accelade::code-block language="php" filename="datetime-picker-examples.php">
use Accelade\Forms\Components\DateTimePicker;

// Flatpickr datetime picker (default)
DateTimePicker::make('event_at')
    ->label('Event Date & Time')
    ->displayFormat('F j, Y h:i K');

// Date only picker
DateTimePicker::make('birth_date')
    ->label('Birth Date')
    ->dateOnly()
    ->displayFormat('F j, Y');

// Time only picker
DateTimePicker::make('meeting_time')
    ->label('Meeting Time')
    ->timeOnly()
    ->minuteIncrement(15);

// Date range picker
DateTimePicker::make('vacation')
    ->label('Vacation Period')
    ->dateOnly()
    ->range();

// Native browser input
DateTimePicker::make('appointment')
    ->label('Appointment')
    ->native();

// With constraints
DateTimePicker::make('booking')
    ->label('Booking')
    ->minDate('today')
    ->maxDate(now()->addDays(30));

// With custom Flatpickr options
DateTimePicker::make('custom')
    ->label('Custom')
    ->flatpickr([
        'weekNumbers' => true,
        'locale' => ['firstDayOfWeek' => 1],
    ]);
    </x-accelade::code-block>
</section>

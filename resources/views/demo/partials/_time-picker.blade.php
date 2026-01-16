{{-- Time Picker Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\TimePicker;
    use Accelade\Forms\Components\DateTimePicker;

    // 12-hour format with AM/PM (default)
    $ampmTimePicker = TimePicker::make('ampm_time')
        ->label('12-Hour (AM/PM)')
        ->time24hr(false)
        ->placeholder('Select time...')
        ->helperText('Shows time with AM/PM indicator');

    $startTimePicker = TimePicker::make('start_time')
        ->label('Start Time')
        ->time24hr(false)
        ->placeholder('Select time...');

    $endTimePicker = TimePicker::make('end_time')
        ->label('End Time')
        ->time24hr(false)
        ->default('17:00');

    // 24-hour format
    $time24hrPicker = TimePicker::make('time_24hr')
        ->label('24-Hour Format')
        ->time24hr()
        ->helperText('Uses 24-hour format (e.g., 14:30)');

    // With minute increment
    $meetingTimePicker = TimePicker::make('meeting_time')
        ->label('Meeting Time')
        ->minuteIncrement(15)
        ->time24hr(false)
        ->helperText('15-minute intervals');

    // With seconds (12-hour)
    $preciseTimePicker = TimePicker::make('precise_time')
        ->label('With Seconds (12h)')
        ->withSeconds()
        ->time24hr(false)
        ->default('14:30:45')
        ->helperText('Shows seconds in AM/PM format');

    // With seconds (24-hour)
    $precise24hrPicker = TimePicker::make('precise_time_24hr')
        ->label('With Seconds (24h)')
        ->withSeconds()
        ->time24hr()
        ->default('14:30:45')
        ->helperText('Shows seconds in 24-hour format');

    // With constraints
    $constrainedTimePicker = TimePicker::make('office_time')
        ->label('Office Hours')
        ->minTime('09:00')
        ->maxTime('17:00')
        ->time24hr(false)
        ->helperText('9 AM - 5 PM only');

    // DateTime combined
    $appointmentPicker = DateTimePicker::make('appointment')
        ->label('Appointment')
        ->displayFormat('F j, Y h:i K')
        ->placeholder('Select date and time...');

    // Native browser input
    $nativeTimePicker = TimePicker::make('native_time')
        ->label('Native Time Input')
        ->native()
        ->helperText('Uses browser native time picker');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Time Picker</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Time selection with Flatpickr. Supports 12-hour (AM/PM) and 24-hour formats. Use <code class="text-xs bg-gray-800 px-1 py-0.5 rounded">->native()</code> for browser inputs.
    </p>

    <div class="space-y-4 mb-4">
        <!-- AM/PM Format -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">AM/PM</span>
                12-Hour Format (Default)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $startTimePicker !!}
                {!! $endTimePicker !!}
            </div>
            <p class="text-xs mt-2" style="color: var(--docs-text-muted);">
                Uses <code class="px-1 py-0.5 bg-gray-800 rounded">->time24hr(false)</code> to display AM/PM format.
            </p>
        </div>

        <!-- 24-Hour Format -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">24hr</span>
                24-Hour Format
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $time24hrPicker !!}
                {!! $meetingTimePicker !!}
            </div>
        </div>

        <!-- With Seconds -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Seconds</span>
                With Seconds Display
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $preciseTimePicker !!}
                {!! $precise24hrPicker !!}
            </div>
            <p class="text-xs mt-2" style="color: var(--docs-text-muted);">
                Use <code class="px-1 py-0.5 bg-gray-800 rounded">->withSeconds()</code> to enable seconds selection and display.
            </p>
        </div>

        <!-- Constrained Time -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Constrained</span>
                Min/Max Time
            </h4>

            {!! $constrainedTimePicker !!}
        </div>

        <!-- DateTime Combined -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Combined</span>
                Date + Time
            </h4>

            {!! $appointmentPicker !!}
        </div>

        <!-- Native Input -->
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Native</span>
                Browser Native Input
            </h4>

            {!! $nativeTimePicker !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="time-picker-examples.php">
use Accelade\Forms\Components\TimePicker;
use Accelade\Forms\Components\DateTimePicker;

// 12-hour format with AM/PM (default for time24hr=false)
TimePicker::make('start_time')
    ->label('Start Time')
    ->time24hr(false);  // Shows time as "2:30 PM"

// 24-hour format
TimePicker::make('time')
    ->label('Time')
    ->time24hr();  // Shows time as "14:30"

// With seconds (12-hour AM/PM)
TimePicker::make('precise_time')
    ->label('Precise Time')
    ->withSeconds()
    ->time24hr(false);  // Shows as "2:30:45 PM"

// With seconds (24-hour)
TimePicker::make('precise_time')
    ->label('Precise Time')
    ->withSeconds()
    ->time24hr();  // Shows as "14:30:45"

// With minute increment
TimePicker::make('meeting_time')
    ->label('Meeting Time')
    ->minuteIncrement(15);

// With constraints
TimePicker::make('office_time')
    ->label('Office Hours')
    ->minTime('09:00')
    ->maxTime('17:00');

// DateTime combined
DateTimePicker::make('appointment')
    ->label('Appointment')
    ->displayFormat('F j, Y h:i K');

// Native browser input
TimePicker::make('native_time')
    ->label('Native')
    ->native();
    </x-accelade::code-block>
</section>

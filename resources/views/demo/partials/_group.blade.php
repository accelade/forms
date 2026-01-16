{{-- Group Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\Group;
    use Accelade\Forms\Components\TextInput;
    use Accelade\Forms\Components\Select;

    // Basic address group
    $addressGroup = Group::make('address')
        ->label('Address')
        ->schema([
            TextInput::make('street')->placeholder('Street address'),
            TextInput::make('city')->placeholder('City'),
            TextInput::make('zip')->placeholder('ZIP Code'),
        ]);

    // Inline name group
    $nameGroup = Group::make('full_name')
        ->label('Full Name')
        ->inline()
        ->schema([
            TextInput::make('first_name')->placeholder('First name'),
            TextInput::make('last_name')->placeholder('Last name'),
        ]);

    // Phone number group
    $phoneGroup = Group::make('phone')
        ->label('Phone Number')
        ->helperText('Enter your phone number with country and area code.')
        ->inline()
        ->schema([
            Select::make('country_code')
                ->options(['+1' => '+1', '+44' => '+44', '+61' => '+61']),
            TextInput::make('area')->placeholder('Area'),
            TextInput::make('number')->placeholder('Number'),
        ]);

    // Date range group
    $dateGroup = Group::make('trip_dates')
        ->label('Trip Dates')
        ->inline()
        ->schema([
            TextInput::make('start_date')->type('date'),
            TextInput::make('end_date')->type('date'),
        ]);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Group</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Group multiple form fields together with a shared label and error display.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Group -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Grouped Address Fields
            </h4>

            {!! $addressGroup->render() !!}
        </div>

        <!-- Inline Group -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Inline</span>
                Inline Fields
            </h4>

            {!! $nameGroup->render() !!}
        </div>

        <!-- Phone Group -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Complex</span>
                Phone Number Group
            </h4>

            {!! $phoneGroup->render() !!}
        </div>

        <!-- Date Range Group -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Range</span>
                Date Range
            </h4>

            {!! $dateGroup->render() !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="group-examples.php">
use Accelade\Forms\Components\Group;
use Accelade\Forms\Components\TextInput;
use Accelade\Forms\Components\Select;

// Basic group with address fields
Group::make('address')
    ->label('Address')
    ->schema([
        TextInput::make('street')->placeholder('Street address'),
        TextInput::make('city')->placeholder('City'),
        TextInput::make('zip')->placeholder('ZIP Code'),
    ]);

// Inline name fields
Group::make('name')
    ->label('Full Name')
    ->inline()
    ->schema([
        TextInput::make('first_name')->placeholder('First name'),
        TextInput::make('last_name')->placeholder('Last name'),
    ]);

// Phone number with country code
Group::make('phone')
    ->label('Phone Number')
    ->helperText('Enter your phone number with country code.')
    ->inline()
    ->schema([
        Select::make('country_code')
            ->options(['+1' => '+1', '+44' => '+44', '+61' => '+61']),
        TextInput::make('area')->placeholder('Area'),
        TextInput::make('number')->placeholder('Number'),
    ]);

// Date range group
Group::make('trip_dates')
    ->label('Trip Dates')
    ->inline()
    ->schema([
        DatePicker::make('start_date'),
        DatePicker::make('end_date'),
    ]);
    </x-accelade::code-block>
</section>

{{-- Telephone Input Component Section --}}
@props(['prefix' => 'a'])

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Telephone Input</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        A telephone input component with integrated country code selector for international phone numbers.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Tel Input -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Basic</span>
                Basic Telephone Input
            </h4>

            <div class="space-y-4">
                <x-accelade::tel-input
                    name="phone_basic"
                    label="Phone Number"
                    placeholder="(555) 000-0000"
                />

                <x-accelade::tel-input
                    name="phone_uk"
                    label="UK Phone"
                    default-country="GB"
                    placeholder="7700 900000"
                />
            </div>
        </div>

        <!-- Country Options -->
        <div class="rounded-xl p-4 border border-blue-500/30" style="background: rgba(59, 130, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-blue-500/20 text-blue-500 rounded">Countries</span>
                Country Options
            </h4>

            <div class="space-y-4">
                <x-accelade::tel-input
                    name="phone_preferred"
                    label="Phone (Preferred Countries)"
                    :preferred-countries="['US', 'CA', 'GB', 'AU']"
                    placeholder="Enter phone number"
                />

                <x-accelade::tel-input
                    name="phone_limited"
                    label="North American Phone"
                    :countries="['US', 'CA', 'MX']"
                    default-country="US"
                    placeholder="(555) 000-0000"
                />
            </div>
        </div>

        <!-- Display Options -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(147, 51, 234, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Display</span>
                Display Options
            </h4>

            <div class="space-y-4">
                <x-accelade::tel-input
                    name="phone_no_flags"
                    label="Phone (No Flags)"
                    :show-flags="false"
                    placeholder="Enter phone number"
                />

                <x-accelade::tel-input
                    name="phone_non_searchable"
                    label="Phone (Non-searchable)"
                    :searchable="false"
                    :countries="['US', 'CA', 'GB', 'AU', 'DE', 'FR']"
                    placeholder="Select country"
                />
            </div>
        </div>

        <!-- Advanced Features -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Advanced</span>
                Advanced Features
            </h4>

            <div class="space-y-4">
                <x-accelade::tel-input
                    name="phone_separate"
                    label="Phone (Separate Dial Code)"
                    :separate-dial-code="true"
                    hint="Dial code stored separately for processing"
                />

                <x-accelade::tel-input
                    name="phone_required"
                    label="Contact Phone"
                    :required="true"
                    :min-length="10"
                    :max-length="15"
                    hint="10-15 digits required"
                />
            </div>
        </div>

        <!-- States -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">States</span>
                Input States
            </h4>

            <div class="space-y-4">
                <x-accelade::tel-input
                    name="phone_disabled"
                    label="Disabled Phone"
                    value="+1 555-123-4567"
                    :disabled="true"
                />
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="tel-input-examples.php">
use Accelade\Forms\Components\TelInput;

// Basic telephone input
TelInput::make('phone')
    ->label('Phone Number')
    ->placeholder('(555) 000-0000')
    ->required();

// With default country
TelInput::make('phone')
    ->label('UK Phone')
    ->defaultCountry('GB');

// Preferred countries at top of list
TelInput::make('phone')
    ->preferredCountries(['US', 'CA', 'GB', 'AU']);

// Limit to specific countries
TelInput::make('phone')
    ->countries(['US', 'CA', 'MX']);

// Store dial code separately
TelInput::make('phone')
    ->separateDialCode(true)
    ->hint('Creates phone_country and phone_dial_code fields');

// Display options
TelInput::make('phone')
    ->showFlags(false)
    ->showDialCode(false)
    ->searchable(false);

// With validation
TelInput::make('phone')
    ->required()
    ->minLength(10)
    ->maxLength(15);
    </x-accelade::code-block>
</section>

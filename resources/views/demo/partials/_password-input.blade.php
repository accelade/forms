{{-- Password Input Component Section --}}
@props(['prefix' => 'a'])

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-violet-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Password Input</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        A secure password input with show/hide toggle, strength indicator, and validation.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Password Input -->
        <div class="rounded-xl p-4 border border-violet-500/30" style="background: rgba(139, 92, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-violet-500/20 text-violet-500 rounded">Basic</span>
                Basic Password Input
            </h4>

            <div class="space-y-4">
                <x-accelade::password-input
                    name="password_basic"
                    label="Password"
                    placeholder="Enter your password"
                />

                <x-accelade::password-input
                    name="password_no_toggle"
                    label="Password (No Toggle)"
                    placeholder="Password without toggle"
                    :show-toggle="false"
                />
            </div>
        </div>

        <!-- Strength Indicator -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Strength</span>
                Strength Indicator
            </h4>

            <div class="space-y-4">
                <x-accelade::password-input
                    name="password_strength"
                    label="Password with Strength Indicator"
                    placeholder="Type to see strength"
                    :show-strength-indicator="true"
                />
            </div>
        </div>

        <!-- Requirements -->
        <div class="rounded-xl p-4 border border-blue-500/30" style="background: rgba(59, 130, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-blue-500/20 text-blue-500 rounded">Validation</span>
                Password Requirements
            </h4>

            <div class="space-y-4">
                <x-accelade::password-input
                    name="password_requirements"
                    label="Strong Password"
                    placeholder="Must meet all requirements"
                    :min-length="12"
                    :require-uppercase="true"
                    :require-lowercase="true"
                    :require-numbers="true"
                    :require-symbols="true"
                    :show-strength-indicator="true"
                    hint="Requires 12+ characters with uppercase, lowercase, numbers, and symbols"
                />
            </div>
        </div>

        <!-- Generate Button -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Generate</span>
                Password Generator
            </h4>

            <div class="space-y-4">
                <x-accelade::password-input
                    name="password_generate"
                    label="Generated Password"
                    placeholder="Click generate to create password"
                    :generate-button="true"
                    :generated-length="20"
                    :show-strength-indicator="true"
                    hint="Click the refresh icon to generate a strong password"
                />
            </div>
        </div>

        <!-- Confirmation -->
        <div class="rounded-xl p-4 border border-pink-500/30" style="background: rgba(236, 72, 153, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-pink-500/20 text-pink-500 rounded">Confirm</span>
                Password Confirmation
            </h4>

            <div class="space-y-4">
                <x-accelade::password-input
                    name="password_confirm"
                    label="New Password"
                    placeholder="Enter your new password"
                    :require-confirmation="true"
                    :min-length="8"
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
                <x-accelade::password-input
                    name="password_required"
                    label="Required Password"
                    placeholder="This field is required"
                    :required="true"
                />

                <x-accelade::password-input
                    name="password_disabled"
                    label="Disabled Password"
                    value="secretpassword"
                    :disabled="true"
                />
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="password-input-examples.php">
use Accelade\Forms\Components\PasswordInput;

// Basic password input
PasswordInput::make('password')
    ->label('Password')
    ->placeholder('Enter your password')
    ->required();

// With strength indicator
PasswordInput::make('password')
    ->showStrengthIndicator();

// Strong password with all requirements
PasswordInput::make('password')
    ->strong()  // Requires uppercase, lowercase, numbers, symbols, 12+ chars
    ->showStrengthIndicator();

// With generate button
PasswordInput::make('password')
    ->generateButton(true, 20)
    ->showStrengthIndicator();

// With confirmation field
PasswordInput::make('password')
    ->confirmed()
    ->minLength(8);

// Enable bcrypt hashing before save
PasswordInput::make('password')
    ->bcrypt();

// Hide the show/hide toggle
PasswordInput::make('password')
    ->hideToggle();

// For login forms (current password)
PasswordInput::make('password')
    ->currentPassword();
    </x-accelade::code-block>
</section>

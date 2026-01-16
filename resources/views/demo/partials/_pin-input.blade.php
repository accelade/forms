{{-- PIN Input Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\PinInput;

    // 4-digit verification code
    $verificationPin = PinInput::make('code')
        ->label('Enter Code')
        ->length(4)
        ->helperText('Enter the 4-digit code sent to your phone');

    // 6-digit OTP
    $otpPin = PinInput::make('otp')
        ->label('Authentication Code')
        ->length(6)
        ->otp()
        ->align('center');

    // Masked/password type
    $secretPin = PinInput::make('pin')
        ->label('Enter PIN')
        ->length(4)
        ->mask()
        ->helperText('Digits are hidden for security');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">PIN Input</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        PIN/OTP verification code input with auto-focus navigation.
    </p>

    <div class="space-y-4 mb-4">
        <!-- 4-Digit PIN -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">4 Digits</span>
                Verification Code
            </h4>

            {!! $verificationPin !!}
        </div>

        <!-- 6-Digit PIN -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">6 Digits</span>
                Two-Factor Authentication
            </h4>

            {!! $otpPin !!}
        </div>

        <!-- Password Type -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Masked</span>
                Secret PIN
            </h4>

            {!! $secretPin !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="pin-input-examples.php">
use Accelade\Forms\Components\PinInput;

// 4-digit verification code
PinInput::make('code')
    ->label('Verification Code')
    ->length(4);

// 6-digit OTP
PinInput::make('otp')
    ->label('Authentication Code')
    ->length(6)
    ->otp()
    ->align('center');

// Masked/password type
PinInput::make('pin')
    ->label('Enter PIN')
    ->length(4)
    ->mask();
    </x-accelade::code-block>
</section>

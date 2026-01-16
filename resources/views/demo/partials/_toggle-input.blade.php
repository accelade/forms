{{-- Toggle (Form) Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\Toggle;

    // Basic toggles
    $enableToggle = Toggle::make('enabled')
        ->label('Enable feature');

    $notificationsToggle = Toggle::make('notifications')
        ->label('Notifications')
        ->default(true);

    $disabledToggle = Toggle::make('auto_save')
        ->label('Auto-save')
        ->disabled();

    // Theme toggle
    $themeToggle = Toggle::make('dark_mode')
        ->label('Dark Mode');

    // Color variants
    $successToggle = Toggle::make('success_toggle')
        ->label('Success')
        ->onColor('success')
        ->default(true);

    $warningToggle = Toggle::make('warning_toggle')
        ->label('Warning')
        ->onColor('warning');

    $dangerToggle = Toggle::make('danger_toggle')
        ->label('Danger')
        ->onColor('danger')
        ->default(true);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Toggle Switch</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        On/off toggle switch for boolean form fields with smooth animations.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Toggle -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Toggle Switches
            </h4>

            <div class="space-y-4">
                {!! $enableToggle !!}
                {!! $notificationsToggle !!}
                {!! $disabledToggle !!}
            </div>
        </div>

        <!-- With Labels -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Labels</span>
                With On/Off Labels
            </h4>

            {!! $themeToggle !!}
        </div>

        <!-- Colored Toggles -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Colors</span>
                Custom Colors
            </h4>

            <div class="space-y-4">
                {!! $successToggle !!}
                {!! $warningToggle !!}
                {!! $dangerToggle !!}
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="toggle-examples.php">
use Accelade\Forms\Components\Toggle;

// Basic toggle
Toggle::make('enabled')
    ->label('Enable feature');

// With default value
Toggle::make('notifications')
    ->label('Notifications')
    ->default(true);

// With on/off labels
Toggle::make('dark_mode')
    ->label('Theme')
    ->onLabel('Dark')
    ->offLabel('Light');

// Custom colors
Toggle::make('active')
    ->label('Active')
    ->onColor('success')   // emerald
    ->offColor('gray');

// Disabled state
Toggle::make('locked')
    ->disabled();
    </x-accelade::code-block>
</section>

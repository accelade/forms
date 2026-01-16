{{-- Checkbox Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\Checkbox;
    use Accelade\Forms\Components\CheckboxList;

    // Single checkboxes
    $termsCheckbox = Checkbox::make('terms')
        ->label('I agree to the terms and conditions');

    $newsletterCheckbox = Checkbox::make('newsletter')
        ->label('Subscribe to newsletter')
        ->default(true);

    $disabledCheckbox = Checkbox::make('disabled_option')
        ->label('Disabled checkbox')
        ->disabled();

    // Checkbox list
    $interestsCheckboxList = CheckboxList::make('interests')
        ->label('Select your interests')
        ->options([
            'tech' => 'Technology',
            'sports' => 'Sports',
            'music' => 'Music',
            'travel' => 'Travel',
        ]);

    // Horizontal checkbox list (3 columns)
    $notificationsCheckboxList = CheckboxList::make('notifications')
        ->label('Notifications')
        ->options([
            'email' => 'Email',
            'sms' => 'SMS',
            'push' => 'Push',
        ])
        ->columns(3);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Checkbox</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Single checkbox and checkbox list components for boolean and multiple selection.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Single Checkbox -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Single</span>
                Single Checkbox
            </h4>

            <div class="space-y-3">
                {!! $termsCheckbox !!}
                {!! $newsletterCheckbox !!}
                {!! $disabledCheckbox !!}
            </div>
        </div>

        <!-- Checkbox List -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">List</span>
                Checkbox List
            </h4>

            {!! $interestsCheckboxList !!}
        </div>

        <!-- Inline Checkboxes -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Inline</span>
                Inline Layout
            </h4>

            {!! $notificationsCheckboxList !!}
        </div>

        <!-- Custom Values (Splade) -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Custom</span>
                Custom Values (Splade)
            </h4>

            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">value('yes')</span>
                    <span style="color: var(--docs-text);">Value when checked</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">falseValue('no')</span>
                    <span style="color: var(--docs-text);">Value when unchecked</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">checkedValue()</span>
                    <span style="color: var(--docs-text);">Alias for value()</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">uncheckedValue()</span>
                    <span style="color: var(--docs-text);">Alias for falseValue()</span>
                </div>
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="checkbox-examples.php">
use Accelade\Forms\Components\Checkbox;
use Accelade\Forms\Components\CheckboxList;

// Single checkbox
Checkbox::make('terms')
    ->label('I agree to the terms and conditions')
    ->required();

// Checkbox list
CheckboxList::make('interests')
    ->label('Select your interests')
    ->options([
        'tech' => 'Technology',
        'sports' => 'Sports',
        'music' => 'Music',
        'travel' => 'Travel',
    ]);

// Inline layout
CheckboxList::make('notifications')
    ->label('Notifications')
    ->options([
        'email' => 'Email',
        'sms' => 'SMS',
        'push' => 'Push',
    ])
    ->inline();

// Custom checked/unchecked values (Splade)
Checkbox::make('is_active')
    ->label('Active Status')
    ->value('yes')      // or checkedValue('yes')
    ->falseValue('no'); // or uncheckedValue('no')
    </x-accelade::code-block>
</section>

{{-- Submit Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\Submit;

    // Primary buttons
    $submitBasic = Submit::make();
    $submitSave = Submit::make('Save Changes');
    $submitProcessing = Submit::make('Processing...')->spinner();

    // Secondary buttons
    $submitDraft = Submit::make('Save Draft')->secondary();
    $submitPreview = Submit::make('Preview')->secondary();

    // Danger buttons
    $submitDelete = Submit::make('Delete')->danger();
    $submitRemove = Submit::make('Remove Account')->danger();

    // Disabled buttons
    $submitDisabled = Submit::make('Disabled')->disabled();
    $submitDisabledSecondary = Submit::make('Disabled Secondary')->secondary()->disabled();
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Submit Button</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Form submit button with loading spinner and styling variants.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Primary Buttons -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Primary</span>
                Primary Buttons
            </h4>

            <div class="flex flex-wrap gap-3">
                {!! $submitBasic->render() !!}
                {!! $submitSave->render() !!}
                {!! $submitProcessing->render() !!}
            </div>
        </div>

        <!-- Secondary Buttons -->
        <div class="rounded-xl p-4 border border-gray-500/30" style="background: rgba(107, 114, 128, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-gray-500/20 text-gray-500 rounded">Secondary</span>
                Secondary Buttons
            </h4>

            <div class="flex flex-wrap gap-3">
                {!! $submitDraft->render() !!}
                {!! $submitPreview->render() !!}
            </div>
        </div>

        <!-- Danger Buttons -->
        <div class="rounded-xl p-4 border border-red-500/30" style="background: rgba(239, 68, 68, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-red-500/20 text-red-500 rounded">Danger</span>
                Danger Buttons
            </h4>

            <div class="flex flex-wrap gap-3">
                {!! $submitDelete->render() !!}
                {!! $submitRemove->render() !!}
            </div>
        </div>

        <!-- Disabled State -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">State</span>
                Disabled State
            </h4>

            <div class="flex flex-wrap gap-3">
                {!! $submitDisabled->render() !!}
                {!! $submitDisabledSecondary->render() !!}
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="submit-examples.php">
use Accelade\Forms\Components\Submit;

// Basic submit button
Submit::make();

// Custom label
Submit::make('Save Changes');

// With loading spinner (default)
Submit::make('Processing...')
    ->spinner();

// Without spinner
Submit::make('Quick Submit')
    ->spinner(false);

// Secondary style
Submit::make('Save Draft')
    ->secondary();

// Danger style for destructive actions
Submit::make('Delete')
    ->danger();

Submit::make('Remove Account')
    ->danger();

// Disabled state
Submit::make('Submit')
    ->disabled();

// With extra attributes
Submit::make('Delete')
    ->danger()
    ->extraAttributes([
        'data-confirm' => 'Are you sure?',
        'onclick' => 'return confirm(this.dataset.confirm)',
    ]);
    </x-accelade::code-block>
</section>

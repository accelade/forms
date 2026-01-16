{{-- Key Value Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\KeyValue;

    // Basic key-value
    $metaKeyValue = KeyValue::make('meta')
        ->label('Meta Tags')
        ->keyLabel('Key')
        ->valueLabel('Value')
        ->default([
            'author' => 'John Doe',
            'keywords' => 'laravel, php, forms',
        ]);

    // Environment variables example
    $envKeyValue = KeyValue::make('env')
        ->label('Custom Environment')
        ->keyLabel('Variable')
        ->valueLabel('Value')
        ->default([
            'APP_DEBUG' => 'true',
            'CACHE_DRIVER' => 'redis',
            'QUEUE_CONNECTION' => 'database',
        ]);
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Key Value</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Key-value pair editor for dynamic data entry.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Key Value -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Metadata Editor
            </h4>

            {!! $metaKeyValue !!}
        </div>

        <!-- Environment Variables -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Example</span>
                Environment Variables
            </h4>

            {!! $envKeyValue !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="key-value-examples.php">
use Accelade\Forms\Components\KeyValue;

// Basic key-value
KeyValue::make('meta')
    ->label('Meta Tags')
    ->keyLabel('Key')
    ->valueLabel('Value');

// With default values
KeyValue::make('env')
    ->label('Environment Variables')
    ->default([
        'APP_DEBUG' => 'true',
        'CACHE_DRIVER' => 'redis',
    ]);

// With validation
KeyValue::make('headers')
    ->label('HTTP Headers')
    ->keyValidation('required|alpha_dash')
    ->valueValidation('required');
    </x-accelade::code-block>
</section>

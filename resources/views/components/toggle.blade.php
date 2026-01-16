@props(['field'])

@php
    $toggleId = $field->getId();
    $isEnabled = (bool) $field->getDefault();
    $isDisabled = $field->isDisabled();
    $isInline = $field->isInline();
    $onColor = $field->getOnColor() ?? 'primary';
    $offColor = $field->getOffColor() ?? 'gray';

    // Color mapping to actual CSS colors (Tailwind-600 values)
    $colorMap = [
        'primary' => '#7c3aed',   // violet-600
        'secondary' => '#64748b', // slate-500
        'success' => '#16a34a',   // green-600
        'danger' => '#dc2626',    // red-600
        'warning' => '#d97706',   // amber-600
        'info' => '#0891b2',      // cyan-600
        'gray' => '#9ca3af',      // gray-400
        'slate' => '#64748b',     // slate-500
        'zinc' => '#71717a',      // zinc-500
        'red' => '#dc2626',       // red-600
        'orange' => '#ea580c',    // orange-600
        'amber' => '#d97706',     // amber-600
        'yellow' => '#ca8a04',    // yellow-600
        'lime' => '#65a30d',      // lime-600
        'green' => '#16a34a',     // green-600
        'emerald' => '#059669',   // emerald-600
        'teal' => '#0d9488',      // teal-600
        'cyan' => '#0891b2',      // cyan-600
        'sky' => '#0284c7',       // sky-600
        'blue' => '#2563eb',      // blue-600
        'indigo' => '#4f46e5',    // indigo-600
        'violet' => '#7c3aed',    // violet-600
        'purple' => '#9333ea',    // purple-600
        'fuchsia' => '#c026d3',   // fuchsia-600
        'pink' => '#db2777',      // pink-600
        'rose' => '#e11d48',      // rose-600
    ];

    $onColorHex = $colorMap[$onColor] ?? $colorMap['primary'];
    $offColorHex = $colorMap[$offColor] ?? '#d1d5db'; // gray-300

    // Toggle button classes from config
    $toggleClasses = config('forms.styles.toggle', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900');
@endphp

<div class="form-field toggle-field {{ config('forms.styles.field', 'mb-4') }}">
    <div class="flex items-{{ $isInline ? 'center' : 'start' }} gap-3">
        @if(!$isInline && $field->getLabel())
            <label for="{{ $toggleId }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
                {{ $field->getLabel() }}
                @if($field->isRequired())
                    <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
                @endif
            </label>
        @endif

        <div
            class="toggle-wrapper"
            data-on-value="{{ $field->getOnValue() }}"
            data-off-value="{{ $field->getOffValue() }}"
            data-on-color="{{ $onColorHex }}"
            data-off-color="{{ $offColorHex }}"
        >
            <input
                type="hidden"
                name="{{ $field->getName() }}"
                class="toggle-hidden-input"
                value="{{ $isEnabled ? $field->getOnValue() : $field->getOffValue() }}"
            >

            <button
                type="button"
                id="{{ $toggleId }}"
                role="switch"
                aria-checked="{{ $isEnabled ? 'true' : 'false' }}"
                @if($isDisabled) disabled @endif
                class="{{ $toggleClasses }} {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                style="background-color: {{ $isEnabled ? $onColorHex : $offColorHex }}; --toggle-on-color: {{ $onColorHex }}; --toggle-off-color: {{ $offColorHex }}; --tw-ring-color: {{ $onColorHex }};"
            >
                <span
                    aria-hidden="true"
                    class="toggle-knob pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $isEnabled ? 'translate-x-5' : 'translate-x-0' }}"
                >
                    @if($field->getOnIcon() || $field->getOffIcon())
                        <span class="toggle-off-icon absolute inset-0 flex h-full w-full items-center justify-center transition-opacity {{ $isEnabled ? 'opacity-0' : 'opacity-100' }}">
                            @if($field->getOffIcon())
                                {!! $field->getOffIcon() !!}
                            @endif
                        </span>
                        <span class="toggle-on-icon absolute inset-0 flex h-full w-full items-center justify-center transition-opacity {{ $isEnabled ? 'opacity-100' : 'opacity-0' }}">
                            @if($field->getOnIcon())
                                {!! $field->getOnIcon() !!}
                            @endif
                        </span>
                    @endif
                </span>
            </button>
        </div>

        @if($isInline && $field->getLabel())
            <label for="{{ $toggleId }}" class="text-sm text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                {{ $field->getLabel() }}
                @if($field->isRequired())
                    <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
                @endif
            </label>
        @endif
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

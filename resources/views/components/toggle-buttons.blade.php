@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isRequired = $field->isRequired();
    $options = $field->getOptions();
    $isGrouped = $field->isGrouped();
    $isInline = $field->isInline();
    $columns = $field->getColumns();
    $gridDirection = $field->getGridDirection();
    $default = $field->getDefault();

    // Build wrapper classes based on layout mode
    $wrapperClasses = 'toggle-buttons-wrapper';

    if ($isGrouped) {
        $wrapperClasses .= ' is-grouped inline-flex rounded-lg overflow-hidden ring-1 ring-gray-950/10 dark:ring-white/20';
    } elseif ($columns) {
        $wrapperClasses .= ' grid gap-3';
        if (is_numeric($columns)) {
            $wrapperClasses .= ' grid-cols-' . $columns;
        }
    } elseif ($isInline) {
        $wrapperClasses .= ' flex flex-wrap gap-3';
    } else {
        $wrapperClasses .= ' flex flex-col gap-3';
    }
@endphp

<div {{ $attributes->class(['form-field toggle-buttons-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $wrapperClasses }}" role="group">
        @foreach($options as $value => $label)
            @php
                $optionId = $id . '-' . $value;
                $icon = $field->getIcon($value);
                $color = $field->getColor($value);
                $isSelected = $default == $value;
                $isOptionDisabled = $isDisabled || $field->isOptionDisabled((string) $value, $label);

                // Determine color classes based on preset or custom
                $colorClass = '';
                if ($color) {
                    if (in_array($color, ['danger', 'success', 'warning', 'info', 'primary', 'gray'])) {
                        $colorClass = 'toggle-button-' . $color;
                    }
                }
            @endphp

            @if($isGrouped)
                {{-- Grouped button style - connected buttons like Filament --}}
                <label
                    for="{{ $optionId }}"
                    @class([
                        'toggle-button toggle-button-grouped relative flex-1 flex items-center justify-center gap-x-2 px-4 py-2 text-sm font-semibold outline-none transition duration-75',
                        'cursor-pointer' => !$isOptionDisabled,
                        'opacity-50 cursor-not-allowed' => $isOptionDisabled,
                        'border-s border-gray-200 dark:border-white/10' => !$loop->first,
                        $colorClass,
                    ])
                    @if($color && !in_array($color, ['danger', 'success', 'warning', 'info', 'primary', 'gray']))
                        style="--toggle-color: {{ $color }};"
                    @endif
                >
                    <input
                        type="radio"
                        id="{{ $optionId }}"
                        name="{{ $statePath }}"
                        value="{{ $value }}"
                        @if($isSelected) checked @endif
                        @if($isOptionDisabled) disabled @endif
                        @if($isRequired) required @endif
                        {{ $attributes->whereStartsWith('wire:') }}
                        class="sr-only peer"
                    />
                    <span class="toggle-button-content flex items-center justify-center gap-x-2">
                        @if($icon)
                            <span class="toggle-button-icon flex-shrink-0 w-5 h-5">{!! $icon !!}</span>
                        @endif
                        <span class="toggle-button-label whitespace-nowrap">{{ $label }}</span>
                    </span>
                </label>
            @else
                {{-- Separate button style - individual buttons with gap --}}
                <label
                    for="{{ $optionId }}"
                    @class([
                        'toggle-button toggle-button-separate relative flex items-center justify-center gap-x-2 rounded-lg px-4 py-2 text-sm font-semibold outline-none ring-1 ring-gray-950/10 transition duration-75',
                        'dark:ring-white/20',
                        'cursor-pointer hover:bg-gray-50 dark:hover:bg-white/5' => !$isOptionDisabled,
                        'opacity-50 cursor-not-allowed' => $isOptionDisabled,
                        $colorClass,
                    ])
                    @if($color && !in_array($color, ['danger', 'success', 'warning', 'info', 'primary', 'gray']))
                        style="--toggle-color: {{ $color }};"
                    @endif
                >
                    <input
                        type="radio"
                        id="{{ $optionId }}"
                        name="{{ $statePath }}"
                        value="{{ $value }}"
                        @if($isSelected) checked @endif
                        @if($isOptionDisabled) disabled @endif
                        @if($isRequired) required @endif
                        {{ $attributes->whereStartsWith('wire:') }}
                        class="sr-only peer"
                    />
                    <span class="toggle-button-content flex items-center justify-center gap-x-2">
                        @if($icon)
                            <span class="toggle-button-icon flex-shrink-0 w-5 h-5">{!! $icon !!}</span>
                        @endif
                        <span class="toggle-button-label whitespace-nowrap">{{ $label }}</span>
                    </span>
                </label>
            @endif
        @endforeach
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">{{ $field->getHint() }}</p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

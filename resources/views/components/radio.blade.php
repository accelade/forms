@props(['field'])

@php
    $isDisabled = $field->isDisabled();
    $isInline = $field->isInline();
    $hasColumns = $field->hasColumns();
    $columns = $field->getColumns();

    // Grid classes for columns
    $gridClass = $hasColumns ? 'grid gap-2' : ($isInline ? 'flex flex-wrap gap-4' : 'space-y-2');
    if ($hasColumns) {
        $gridClass .= ' grid-cols-' . $columns;
    }
@endphp

<div class="form-field radio-field {{ config('forms.styles.field', 'mb-4') }}">
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $gridClass }}">
        @foreach($field->getOptions() as $value => $label)
            <div class="flex items-start gap-2">
                {{-- Custom radio wrapper --}}
                <div class="radio-wrapper relative inline-flex items-center">
                    {{-- Actual hidden radio --}}
                    <input
                        type="radio"
                        name="{{ $field->getName() }}"
                        id="{{ $field->getId() }}-{{ $value }}"
                        value="{{ $value }}"
                        @if($field->getDefault() == $value) checked @endif
                        @if($field->isRequired()) required @endif
                        @if($isDisabled) disabled @endif
                        class="radio-input peer sr-only"
                        {!! $field->getExtraAttributesString() !!}
                    >

                    {{-- Custom radio visual --}}
                    <label
                        for="{{ $field->getId() }}-{{ $value }}"
                        class="radio-box flex h-5 w-5 items-center justify-center rounded-full border-2 border-gray-300 bg-white shadow-sm transition-all duration-150 cursor-pointer
                               peer-checked:border-primary-600
                               peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500/20 peer-focus-visible:ring-offset-2
                               peer-disabled:cursor-not-allowed peer-disabled:bg-gray-100 peer-disabled:border-gray-200
                               dark:border-gray-600 dark:bg-gray-800
                               dark:peer-checked:border-primary-500
                               dark:peer-disabled:bg-gray-900 dark:peer-disabled:border-gray-700
                               dark:peer-focus-visible:ring-offset-gray-900"
                    >
                        {{-- Inner dot - hidden by default, shown when checked via CSS --}}
                        <span class="radio-dot h-2.5 w-2.5 rounded-full bg-primary-600 dark:bg-primary-500"></span>
                    </label>
                </div>

                <div>
                    <label for="{{ $field->getId() }}-{{ $value }}" class="text-sm text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                        {{ $label }}
                    </label>
                    @if($field->getDescription($value))
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $field->getDescription($value) }}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
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

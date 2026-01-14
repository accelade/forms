@props(['field'])

<div class="form-field">
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-300') }} mb-2">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $field->isInline() ? 'flex flex-wrap gap-4' : 'space-y-2' }}">
        @foreach($field->getOptions() as $value => $label)
            <div class="flex items-start gap-2">
                <input
                    type="radio"
                    name="{{ $field->getName() }}"
                    id="{{ $field->getId() }}-{{ $value }}"
                    value="{{ $value }}"
                    @if($field->getDefault() == $value) checked @endif
                    @if($field->isRequired()) required @endif
                    @if($field->isDisabled()) disabled @endif
                    class="{{ config('forms.styles.radio', 'border-gray-300 dark:border-gray-600 text-primary-600 shadow-sm focus:ring-primary-500') }}"
                    {!! $field->getExtraAttributesString() !!}
                >
                <div>
                    <label for="{{ $field->getId() }}-{{ $value }}" class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $label }}
                    </label>
                    @if($field->getDescription($value))
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $field->getDescription($value) }}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

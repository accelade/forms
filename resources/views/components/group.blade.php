@props(['field'])

<div class="form-field form-group">
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-300') }} mb-2">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $field->isInline() ? 'flex flex-wrap gap-4' : 'space-y-2' }}">
        @if($field->hasSchema())
            @foreach($field->getSchema() as $childField)
                {!! $childField->render() !!}
            @endforeach
        @else
            {{ $slot ?? '' }}
        @endif
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @if($field->shouldShowErrors())
        @error($field->getName())
            <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
                {{ $message }}
            </p>
        @enderror

        {{-- Also check for array-style errors (e.g., tags.*) --}}
        @error($field->getName() . '.*')
            <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
                {{ $message }}
            </p>
        @enderror
    @endif
</div>

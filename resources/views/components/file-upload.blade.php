@props(['field'])

<div class="form-field">
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-300') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="mt-1">
        <div class="flex justify-center rounded-lg border border-dashed border-gray-300 dark:border-gray-600 px-6 py-10">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="mt-4 flex text-sm leading-6 text-gray-600 dark:text-gray-400">
                    <label for="{{ $field->getId() }}" class="relative cursor-pointer rounded-md font-semibold text-primary-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-primary-600 focus-within:ring-offset-2 hover:text-primary-500">
                        <span>Upload a file</span>
                        <input
                            type="file"
                            name="{{ $field->getName() }}{{ $field->isMultiple() ? '[]' : '' }}"
                            id="{{ $field->getId() }}"
                            @if($field->isMultiple()) multiple @endif
                            @if($field->isRequired()) required @endif
                            @if($field->isDisabled()) disabled @endif
                            @if($field->getAcceptedFileTypes()) accept="{{ $field->getAcceptedFileTypes() }}" @endif
                            class="sr-only"
                            {!! $field->getExtraAttributesString() !!}
                        >
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs leading-5 text-gray-500 dark:text-gray-400">
                    @if($field->isImage())
                        PNG, JPG, GIF
                    @endif
                    @if($field->getMaxSize())
                        up to {{ round($field->getMaxSize() / 1024, 1) }}MB
                    @endif
                </p>
            </div>
        </div>
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

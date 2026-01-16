@props(['field'])

@php
    $fieldId = $field->getId();
    $fieldName = $field->getName();
    $options = $field->getOptions();
    $selectedValues = (array) $field->getDefault();
    $isDisabled = $field->isDisabled();
    $isSearchable = $field->isSearchable();
    $isBulkToggleable = $field->isBulkToggleable();
    $columns = $field->getColumns();
    $gridDirection = $field->getGridDirection();
    $descriptions = $field->getDescriptions();
    $allowHtml = $field->isHtmlAllowed();

    // Grid classes based on columns
    $gridCols = match($columns) {
        1 => 'grid-cols-1',
        2 => 'sm:grid-cols-2',
        3 => 'sm:grid-cols-2 lg:grid-cols-3',
        4 => 'sm:grid-cols-2 lg:grid-cols-4',
        default => 'sm:grid-cols-2 lg:grid-cols-' . min($columns, 6),
    };

    // Grid flow based on direction
    $gridFlow = $gridDirection === 'row' ? 'grid-flow-row' : 'grid-flow-col';
@endphp

<div class="form-field checkbox-list-field {{ config('forms.styles.field', 'mb-4') }}">
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div
        class="checkbox-list-wrapper"
        data-checkbox-list
        data-field-name="{{ $fieldName }}"
        data-searchable="{{ $isSearchable ? 'true' : 'false' }}"
        data-bulk-toggleable="{{ $isBulkToggleable ? 'true' : 'false' }}"
        data-search-debounce="{{ $field->getSearchDebounce() }}"
    >
        {{-- Search input --}}
        @if($isSearchable)
            <div class="mb-3">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        class="checkbox-list-search block w-full rounded-lg border border-gray-300 bg-white py-2 ps-10 pe-3 text-sm placeholder-gray-400 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                        placeholder="{{ $field->getSearchPrompt() }}"
                    >
                </div>
            </div>
        @endif

        {{-- Bulk toggle actions --}}
        @if($isBulkToggleable)
            <div class="mb-3 flex gap-3 text-sm">
                <button
                    type="button"
                    class="checkbox-list-select-all text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                >
                    {{ $field->getSelectAllActionLabel() }}
                </button>
                <span class="text-gray-300 dark:text-gray-600">|</span>
                <button
                    type="button"
                    class="checkbox-list-deselect-all text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                >
                    {{ $field->getDeselectAllActionLabel() }}
                </button>
            </div>
        @endif

        {{-- Options grid --}}
        <div class="checkbox-list-options grid gap-3 {{ $gridCols }} {{ $columns > 1 && $gridDirection === 'column' ? 'grid-flow-dense' : '' }}">
            @forelse($options as $value => $label)
                @php
                    $optionId = $fieldId . '_' . $value;
                    $isChecked = in_array($value, $selectedValues);
                    $isOptionDisabled = $isDisabled || $field->isOptionDisabled($value, $label);
                    $description = $field->getDescription($value);
                @endphp

                <div class="checkbox-list-option" data-value="{{ $value }}">
                    <div class="flex items-start gap-3">
                        {{-- Custom checkbox wrapper --}}
                        <div class="checkbox-wrapper relative inline-flex items-center shrink-0 mt-0.5">
                            <input
                                type="checkbox"
                                name="{{ $fieldName }}[]"
                                id="{{ $optionId }}"
                                value="{{ $value }}"
                                @if($isChecked) checked @endif
                                @if($isOptionDisabled) disabled @endif
                                class="checkbox-input peer sr-only"
                            >

                            {{-- Custom checkbox visual --}}
                            <label
                                for="{{ $optionId }}"
                                class="checkbox-box flex h-5 w-5 items-center justify-center rounded border-2 border-gray-300 bg-white shadow-sm transition-all duration-150 cursor-pointer
                                       peer-checked:border-primary-600 peer-checked:bg-primary-600
                                       peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500/20 peer-focus-visible:ring-offset-2
                                       peer-disabled:cursor-not-allowed peer-disabled:bg-gray-100 peer-disabled:border-gray-200
                                       dark:border-gray-600 dark:bg-gray-800
                                       dark:peer-checked:border-primary-500 dark:peer-checked:bg-primary-500
                                       dark:peer-disabled:bg-gray-900 dark:peer-disabled:border-gray-700
                                       dark:peer-focus-visible:ring-offset-gray-900"
                            >
                                <svg
                                    class="checkbox-icon h-3 w-3 text-white"
                                    viewBox="0 0 12 12"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <polyline points="2 6 5 9 10 3"></polyline>
                                </svg>
                            </label>
                        </div>

                        {{-- Label and description --}}
                        <div class="flex-1 min-w-0">
                            <label
                                for="{{ $optionId }}"
                                class="block text-sm text-gray-700 dark:text-gray-300 select-none cursor-pointer {{ $isOptionDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                            >
                                @if($allowHtml)
                                    {!! $label !!}
                                @else
                                    {{ $label }}
                                @endif
                            </label>

                            @if($description)
                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                    @if($allowHtml)
                                        {!! $description !!}
                                    @else
                                        {{ $description }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="checkbox-list-empty text-sm text-gray-500 dark:text-gray-400 py-2">
                    {{ __('No options available') }}
                </p>
            @endforelse
        </div>

        {{-- No search results message --}}
        <p class="checkbox-list-no-results hidden text-sm text-gray-500 dark:text-gray-400 py-2">
            {{ $field->getNoSearchResultsMessage() }}
        </p>
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($fieldName)
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror

    @error($fieldName . '.*')
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

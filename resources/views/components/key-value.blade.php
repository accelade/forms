@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadonly();
    $isRequired = $field->isRequired();
    $keyLabel = $field->getKeyLabel();
    $valueLabel = $field->getValueLabel();
    $keyPlaceholder = $field->getKeyPlaceholder();
    $valuePlaceholder = $field->getValuePlaceholder();
    $isAddable = $field->isAddable();
    $isDeletable = $field->isDeletable();
    $isReorderable = $field->isReorderable();
    $editableKeys = $field->hasEditableKeys();
    $editableValues = $field->hasEditableValues();
    $addActionLabel = $field->getAddActionLabel();
    $default = $field->getDefault() ?? [];
@endphp

<div {{ $attributes->class(['form-field key-value-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="key-value-wrapper rounded-lg border border-gray-300 bg-white shadow-sm overflow-hidden dark:border-gray-600 dark:bg-gray-800 {{ $isDisabled ? 'opacity-50' : '' }}"
         data-template-id="{{ $id }}-template"
         data-state-path="{{ $statePath }}">

        {{-- Header --}}
        <div class="key-value-header flex bg-gray-50 border-b border-gray-300 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
            @if($isReorderable && !$isDisabled && !$isReadOnly)
                <span class="key-value-header-handle w-8"></span>
            @endif
            <span class="key-value-header-key flex-1 px-3 py-2">{{ $keyLabel }}</span>
            <span class="key-value-header-value flex-1 px-3 py-2">{{ $valueLabel }}</span>
            @if($isDeletable && !$isDisabled && !$isReadOnly)
                <span class="key-value-header-actions w-10"></span>
            @endif
        </div>

        {{-- Rows container --}}
        <div class="key-value-rows divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($default as $key => $value)
                <div class="key-value-row flex items-center bg-white dark:bg-gray-800" @if($isReorderable && !$isDisabled && !$isReadOnly) draggable="true" @endif>
                    @if($isReorderable && !$isDisabled && !$isReadOnly)
                        <button type="button" class="key-value-reorder-handle w-8 flex items-center justify-center text-gray-400 hover:text-gray-600 cursor-grab active:cursor-grabbing dark:hover:text-gray-300" title="Drag to reorder">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                            </svg>
                        </button>
                    @endif
                    <input
                        type="text"
                        class="key-value-key flex-1 px-3 py-2.5 border-0 border-e border-gray-200 bg-transparent text-sm text-gray-900 placeholder-gray-400 focus:ring-0 focus:outline-none dark:border-gray-700 dark:text-gray-100 dark:placeholder-gray-500"
                        value="{{ $key }}"
                        @if($keyPlaceholder) placeholder="{{ $keyPlaceholder }}" @endif
                        @if($isDisabled || !$editableKeys) disabled @endif
                        @if($isReadOnly || !$editableKeys) readonly @endif
                    />
                    <input
                        type="text"
                        class="key-value-value flex-1 px-3 py-2.5 border-0 bg-transparent text-sm text-gray-900 placeholder-gray-400 focus:ring-0 focus:outline-none dark:text-gray-100 dark:placeholder-gray-500"
                        value="{{ $value }}"
                        @if($valuePlaceholder) placeholder="{{ $valuePlaceholder }}" @endif
                        @if($isDisabled || !$editableValues) disabled @endif
                        @if($isReadOnly || !$editableValues) readonly @endif
                    />
                    @if($isDeletable && !$isDisabled && !$isReadOnly)
                        <button type="button" class="key-value-delete w-10 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors duration-150 dark:hover:text-red-400" title="Remove row">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            @empty
                {{-- Empty state - will be populated by JS --}}
            @endforelse
        </div>

        {{-- Template for new rows --}}
        <template id="{{ $id }}-template">
            <div class="key-value-row flex items-center bg-white dark:bg-gray-800" @if($isReorderable && !$isDisabled && !$isReadOnly) draggable="true" @endif>
                @if($isReorderable && !$isDisabled && !$isReadOnly)
                    <button type="button" class="key-value-reorder-handle w-8 flex items-center justify-center text-gray-400 hover:text-gray-600 cursor-grab active:cursor-grabbing dark:hover:text-gray-300" title="Drag to reorder">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                        </svg>
                    </button>
                @endif
                <input
                    type="text"
                    class="key-value-key flex-1 px-3 py-2.5 border-0 border-e border-gray-200 bg-transparent text-sm text-gray-900 placeholder-gray-400 focus:ring-0 focus:outline-none dark:border-gray-700 dark:text-gray-100 dark:placeholder-gray-500"
                    @if($keyPlaceholder) placeholder="{{ $keyPlaceholder }}" @endif
                    @if($isDisabled || !$editableKeys) disabled @endif
                    @if($isReadOnly || !$editableKeys) readonly @endif
                />
                <input
                    type="text"
                    class="key-value-value flex-1 px-3 py-2.5 border-0 bg-transparent text-sm text-gray-900 placeholder-gray-400 focus:ring-0 focus:outline-none dark:text-gray-100 dark:placeholder-gray-500"
                    @if($valuePlaceholder) placeholder="{{ $valuePlaceholder }}" @endif
                    @if($isDisabled || !$editableValues) disabled @endif
                    @if($isReadOnly || !$editableValues) readonly @endif
                />
                @if($isDeletable && !$isDisabled && !$isReadOnly)
                    <button type="button" class="key-value-delete w-10 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors duration-150 dark:hover:text-red-400" title="Remove row">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </template>

        {{-- Add button --}}
        @if($isAddable && !$isDisabled && !$isReadOnly)
            <button type="button" class="key-value-add w-full flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-primary-600 bg-gray-50 border-t border-gray-200 hover:bg-gray-100 transition-colors duration-150 dark:text-primary-400 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ $addActionLabel }}
            </button>
        @endif

        {{-- Hidden input for form submission --}}
        <input type="hidden" name="{{ $statePath }}" class="key-value-data" value="{{ json_encode($default) }}" />
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

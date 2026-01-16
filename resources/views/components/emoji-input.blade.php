@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isRequired = $field->isRequired();
    $placeholder = $field->getPlaceholder() ?? 'Select an emoji...';
    $emojis = $field->getEmojis();
    $categories = $field->getCategories();
    $categoryLabels = $field->getCategoryLabels();
    $categoryIcons = $field->getCategoryIcons();
    $searchable = $field->isSearchable();
    $gridColumns = $field->getGridColumns();
    $showPreview = $field->getShowPreview();
    $multiple = $field->isMultiple();
    $hasMultipleCategories = count($emojis) > 1;
@endphp

<div {{ $attributes->class(['form-field emoji-input-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div
        class="emoji-input-wrapper relative"
        data-multiple="{{ $multiple ? 'true' : 'false' }}"
    >
        {{-- Trigger button --}}
        <button
            type="button"
            class="emoji-input-trigger w-full flex items-center justify-between px-3 py-2.5 text-left rounded-lg border border-gray-300 bg-white shadow-sm text-sm transition-colors duration-150 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-100 {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
            @if($isDisabled) disabled @endif
        >
            <span class="flex items-center gap-2">
                <span class="emoji-input-selected text-2xl leading-none"></span>
                <span class="emoji-input-selected-name text-gray-700 dark:text-gray-300"></span>
            </span>
            <span class="emoji-input-placeholder text-gray-400 dark:text-gray-500">{{ $placeholder }}</span>
            <svg class="emoji-input-arrow w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        {{-- Dropdown --}}
        <div class="emoji-input-dropdown absolute z-50 w-full mt-1 bg-white rounded-lg border border-gray-300 shadow-lg overflow-hidden dark:bg-gray-800 dark:border-gray-600" hidden>
            {{-- Search --}}
            @if($searchable)
                <div class="emoji-input-search p-2 border-b border-gray-200 dark:border-gray-700">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input
                            type="text"
                            class="emoji-input-search-input w-full ps-9 pe-3 py-2 text-sm rounded-lg border border-gray-300 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-500"
                            placeholder="Search emojis..."
                        />
                    </div>
                </div>
            @endif

            {{-- Category tabs (if multiple categories) --}}
            @if($hasMultipleCategories)
                <div class="emoji-input-tabs flex border-b border-gray-200 bg-gray-50 overflow-x-auto dark:bg-gray-700 dark:border-gray-600">
                    @foreach($emojis as $category => $categoryEmojis)
                        <button
                            type="button"
                            class="emoji-input-tab flex items-center justify-center px-3 py-2 text-lg transition-colors duration-150 {{ $loop->first ? 'bg-white border-b-2 border-primary-600 dark:bg-gray-800 dark:border-primary-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}"
                            data-category="{{ $category }}"
                            title="{{ $categoryLabels[$category] ?? ucfirst($category) }}"
                        >
                            {{ $categoryIcons[$category] ?? '📦' }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Preview area --}}
            @if($showPreview)
                <div class="emoji-input-preview hidden flex items-center gap-3 p-3 border-b border-gray-200 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <span class="emoji-preview-icon text-4xl"></span>
                    <span class="emoji-preview-name text-sm text-gray-600 dark:text-gray-300"></span>
                </div>
            @endif

            {{-- Emoji grids --}}
            <div class="emoji-input-panels">
                @foreach($emojis as $category => $categoryEmojis)
                    <div
                        class="emoji-input-panel {{ $loop->first ? '' : 'hidden' }}"
                        data-category="{{ $category }}"
                    >
                        <div class="emoji-input-grid grid gap-0.5 p-2 max-h-60 overflow-y-auto" style="grid-template-columns: repeat({{ $gridColumns }}, minmax(0, 1fr));">
                            @foreach($categoryEmojis as $emoji => $emojiName)
                                <button
                                    type="button"
                                    class="emoji-input-item flex items-center justify-center p-1.5 rounded text-2xl hover:bg-gray-100 transition-colors duration-150 dark:hover:bg-gray-700"
                                    data-emoji="{{ $emoji }}"
                                    data-name="{{ $emojiName }}"
                                    data-category="{{ $category }}"
                                    title="{{ $emojiName }}"
                                >
                                    {{ $emoji }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Empty state --}}
            <div class="emoji-input-empty hidden p-8 text-center text-gray-500 dark:text-gray-400">
                <span class="text-4xl mb-2 block opacity-50">🔍</span>
                <p class="text-sm">No emojis found</p>
            </div>
        </div>

        <input type="hidden" name="{{ $statePath }}" class="emoji-input-value" @if($isRequired) required @endif />
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

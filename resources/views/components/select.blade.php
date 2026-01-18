@props(['field'])

@php
    $isDisabled = $field->isDisabled();
    $isNative = $field->isNative();
    $isSearchable = $field->isSearchable();
    $isMultiple = $field->isMultiple();
    $hasGroupedOptions = $field->hasGroupedOptions();
    $hasDescriptions = $field->hasOptionDescriptions();
    $hasCreateOptionForm = $field->hasCreateOptionForm();
    $hasEditOptionForm = $field->hasEditOptionForm();
    $hasCreateAction = $field->hasCreateAction();
    $hasEditAction = $field->hasEditAction();
    $createAction = $field->getCreateAction();
    $editAction = $field->getEditAction();

    // Container classes
    $containerClasses = config('forms.styles.select_container', 'relative rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800');

    if ($isDisabled) {
        $containerClasses .= ' ' . config('forms.styles.input_container_disabled', 'bg-gray-50 dark:bg-gray-900 cursor-not-allowed');
    }

    // Select classes - transparent background
    $selectClasses = config('forms.styles.select', 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed dark:text-gray-100 dark:disabled:text-gray-600 appearance-none');

    // Prefix/Suffix support
    $hasPrefix = $field->hasPrefix();
    $hasSuffix = $field->hasSuffix();
@endphp

<div class="form-field select-field {{ config('forms.styles.field', 'mb-4') }}"
    @if(!$isNative)
        data-searchable-select="{{ json_encode($field->getSearchableOptions()) }}"
    @endif
>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    @if($isNative)
        {{-- Native Select --}}
        <div class="{{ $containerClasses }} flex items-center">
            {{-- Prefix --}}
            @if($hasPrefix)
                <div class="flex items-center ps-3 text-gray-500 dark:text-gray-400">
                    @if($field->getPrefixIcon())
                        <x-dynamic-component :component="'heroicon-o-' . $field->getPrefixIcon()" class="w-5 h-5 {{ $field->getPrefixIconColor() ? 'text-' . $field->getPrefixIconColor() . '-500' : '' }}" />
                    @endif
                    @if($field->getPrefix())
                        <span class="text-sm">{{ $field->getPrefix() }}</span>
                    @endif
                </div>
            @endif

            {{-- Dropdown arrow icon --}}
            <div class="pointer-events-none absolute inset-y-0 end-0 flex items-center pe-3">
                <x-accelade::icon name="heroicon-m-chevron-down" size="sm" class="text-gray-400 dark:text-gray-500" :showFallback="false" />
            </div>

            <select
                name="{{ $field->getName() }}{{ $isMultiple ? '[]' : '' }}"
                id="{{ $field->getId() }}"
                @if($field->isRequired()) required @endif
                @if($isDisabled) disabled @endif
                @if($isMultiple) multiple @endif
                @if($field->hasAutofocus()) autofocus @endif
                class="{{ $selectClasses }} pe-10 {{ $hasPrefix ? 'ps-0' : '' }}"
                {!! $field->getExtraAttributesString() !!}
            >
                @if($field->getEmptyOptionLabel() && !$isMultiple)
                    <option value="" @if(!$field->isSelectablePlaceholder()) disabled @endif>{{ $field->getEmptyOptionLabel() }}</option>
                @endif

                @if($hasGroupedOptions)
                    @foreach($field->getOptions() as $groupLabel => $groupOptions)
                        <optgroup label="{{ $groupLabel }}">
                            @foreach($groupOptions as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @if($field->getDefault() !== null && (
                                        ($isMultiple && is_array($field->getDefault()) && in_array($value, $field->getDefault())) ||
                                        (!$isMultiple && $field->getDefault() == $value)
                                    )) selected @endif
                                    @if($field->isOptionDisabled($value, $label)) disabled @endif
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @else
                    @foreach($field->getOptions() as $value => $label)
                        <option
                            value="{{ $value }}"
                            @if($field->getDefault() !== null && (
                                ($isMultiple && is_array($field->getDefault()) && in_array($value, $field->getDefault())) ||
                                (!$isMultiple && $field->getDefault() == $value)
                            )) selected @endif
                            @if($field->isOptionDisabled($value, $label)) disabled @endif
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    @else
        {{-- Searchable Select (Select2-style) --}}
        <div class="searchable-select-wrapper relative">
            {{-- Hidden select for form submission --}}
            <select
                name="{{ $field->getName() }}{{ $isMultiple ? '[]' : '' }}"
                id="{{ $field->getId() }}"
                @if($field->isRequired()) required @endif
                @if($isDisabled) disabled @endif
                @if($isMultiple) multiple @endif
                class="searchable-select-hidden sr-only"
                {!! $field->getExtraAttributesString() !!}
            >
                @if($field->getEmptyOptionLabel() && !$isMultiple)
                    <option value="">{{ $field->getEmptyOptionLabel() }}</option>
                @endif

                @if($hasGroupedOptions)
                    @foreach($field->getOptions() as $groupLabel => $groupOptions)
                        <optgroup label="{{ $groupLabel }}" data-group="{{ $groupLabel }}">
                            @foreach($groupOptions as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    data-group="{{ $groupLabel }}"
                                    @if($field->getDefault() !== null && (
                                        ($isMultiple && is_array($field->getDefault()) && in_array($value, $field->getDefault())) ||
                                        (!$isMultiple && $field->getDefault() == $value)
                                    )) selected @endif
                                    @if($field->isOptionDisabled($value, $label)) data-disabled="true" @endif
                                    @if($hasDescriptions && $field->getOptionDescription($value)) data-description="{{ $field->getOptionDescription($value) }}" @endif
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @else
                    @foreach($field->getOptions() as $value => $label)
                        <option
                            value="{{ $value }}"
                            @if($field->getDefault() !== null && (
                                ($isMultiple && is_array($field->getDefault()) && in_array($value, $field->getDefault())) ||
                                (!$isMultiple && $field->getDefault() == $value)
                            )) selected @endif
                            @if($field->isOptionDisabled($value, $label)) data-disabled="true" @endif
                            @if($hasDescriptions && $field->getOptionDescription($value)) data-description="{{ $field->getOptionDescription($value) }}" @endif
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                @endif
            </select>

            {{-- Custom dropdown trigger --}}
            <button
                type="button"
                class="searchable-select-trigger {{ $containerClasses }} flex items-center justify-between w-full text-left {{ $hasPrefix ? 'ps-0' : 'px-3' }} {{ $hasSuffix ? 'pe-0' : '' }} py-2"
                @if($isDisabled) disabled @endif
                aria-haspopup="listbox"
                aria-expanded="false"
            >
                {{-- Prefix --}}
                @if($hasPrefix)
                    <div class="flex items-center ps-3 pe-2 text-gray-500 dark:text-gray-400 shrink-0">
                        @if($field->getPrefixIcon())
                            <svg class="w-5 h-5 {{ $field->getPrefixIconColor() ? 'text-' . $field->getPrefixIconColor() . '-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        @endif
                        @if($field->getPrefix())
                            <span class="text-sm">{{ $field->getPrefix() }}</span>
                        @endif
                    </div>
                @endif

                <span class="searchable-select-display text-sm text-gray-900 dark:text-gray-100 truncate flex-1 {{ !$hasPrefix ? '' : '' }}">
                    @if($field->getEmptyOptionLabel())
                        {{ $field->getEmptyOptionLabel() }}
                    @else
                        &nbsp;
                    @endif
                </span>

                <span class="searchable-select-icons flex items-center gap-1 {{ $hasSuffix ? 'pe-3' : '' }}">
                    {{-- Suffix --}}
                    @if($hasSuffix && $field->getSuffix())
                        <span class="text-sm text-gray-500 dark:text-gray-400 me-2">{{ $field->getSuffix() }}</span>
                    @endif

                    @if($field->hasAllowClear())
                        <span class="searchable-select-clear hidden text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-pointer" title="Clear">
                            <x-accelade::icon name="heroicon-o-x-mark" size="sm" :showFallback="false" />
                        </span>
                    @endif

                    {{-- Edit option button (using Actions component if available) --}}
                    @if($hasEditAction)
                        <span class="searchable-select-edit-btn hidden" data-select-edit-action>
                            <x-actions::action :action="$editAction" :record="$field->getDefault()" />
                        </span>
                    @elseif($hasEditOptionForm)
                        <span class="searchable-select-edit-btn hidden text-gray-400 hover:text-primary-600 dark:text-gray-500 dark:hover:text-primary-400 cursor-pointer" title="{{ __('Edit') }}">
                            <x-accelade::icon name="heroicon-o-pencil-square" size="sm" :showFallback="false" />
                        </span>
                    @endif

                    {{-- Create option button (using Actions component if available) --}}
                    @if($hasCreateAction)
                        <span class="searchable-select-create-btn" data-select-create-action>
                            <x-actions::action :action="$createAction" />
                        </span>
                    @elseif($hasCreateOptionForm)
                        <span class="searchable-select-create-btn text-gray-400 hover:text-primary-600 dark:text-gray-500 dark:hover:text-primary-400 cursor-pointer" title="{{ __('Create new') }}">
                            <x-accelade::icon name="heroicon-o-plus" size="sm" :showFallback="false" />
                        </span>
                    @endif

                    <span class="searchable-select-arrow text-gray-400 dark:text-gray-500 transition-transform">
                        <x-accelade::icon name="heroicon-m-chevron-down" size="sm" :showFallback="false" />
                    </span>
                </span>
            </button>

            {{-- Dropdown panel --}}
            <div class="searchable-select-dropdown hidden absolute left-0 right-0 z-50 mt-1 rounded-lg border border-gray-300 bg-white shadow-lg dark:border-gray-600 dark:bg-gray-800">
                {{-- Search input --}}
                @if($isSearchable)
                    <div class="searchable-select-search-wrapper p-2 border-b border-gray-200 dark:border-gray-700">
                        <input
                            type="text"
                            class="searchable-select-search w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                            placeholder="{{ $field->getSearchPlaceholder() }}"
                            autocomplete="off"
                        />
                    </div>
                @endif

                {{-- Search prompt --}}
                @if($field->getSearchPrompt())
                    <div class="searchable-select-search-prompt px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hidden">
                        {{ $field->getSearchPrompt() }}
                    </div>
                @endif

                {{-- Loading message --}}
                <div class="searchable-select-loading hidden px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center gap-2">
                        <x-accelade::icon name="heroicon-o-arrow-path" size="sm" class="animate-spin" :showFallback="false" />
                        {{ $field->getLoadingMessage() }}
                    </span>
                </div>

                {{-- Options list --}}
                <ul class="searchable-select-options max-h-60 overflow-auto py-1" role="listbox" data-page="1">
                    @if($hasGroupedOptions)
                        @foreach($field->getOptions() as $groupLabel => $groupOptions)
                            <li class="searchable-select-group">
                                <div class="searchable-select-group-label px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-900">
                                    {{ $groupLabel }}
                                </div>
                                <ul class="searchable-select-group-options">
                                    @foreach($groupOptions as $value => $label)
                                        <li
                                            class="searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 {{ $field->isOptionDisabled($value, $label) ? 'opacity-50 cursor-not-allowed' : '' }} {{ !$field->shouldWrapOptionLabels() ? 'truncate' : '' }}"
                                            data-value="{{ $value }}"
                                            data-group="{{ $groupLabel }}"
                                            @if($field->isOptionDisabled($value, $label)) data-disabled="true" @endif
                                            role="option"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1 {{ !$field->shouldWrapOptionLabels() ? 'truncate' : '' }}">
                                                    <span class="searchable-select-option-label">
                                                        @if($field->hasAllowHtml())
                                                            {!! $label !!}
                                                        @else
                                                            {{ $label }}
                                                        @endif
                                                    </span>
                                                    @if($hasDescriptions && $field->getOptionDescription($value))
                                                        <p class="searchable-select-option-description text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                            {{ $field->getOptionDescription($value) }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <span class="searchable-select-option-check hidden ms-2 text-primary-600 shrink-0">
                                                    <x-accelade::icon name="heroicon-s-check" size="sm" :showFallback="false" />
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    @else
                        @foreach($field->getOptions() as $value => $label)
                            <li
                                class="searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 {{ $field->isOptionDisabled($value, $label) ? 'opacity-50 cursor-not-allowed' : '' }} {{ !$field->shouldWrapOptionLabels() ? 'truncate' : '' }}"
                                data-value="{{ $value }}"
                                @if($field->isOptionDisabled($value, $label)) data-disabled="true" @endif
                                role="option"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 {{ !$field->shouldWrapOptionLabels() ? 'truncate' : '' }}">
                                        <span class="searchable-select-option-label">
                                            @if($field->hasAllowHtml())
                                                {!! $label !!}
                                            @else
                                                {{ $label }}
                                            @endif
                                        </span>
                                        @if($hasDescriptions && $field->getOptionDescription($value))
                                            <p class="searchable-select-option-description text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $field->getOptionDescription($value) }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="searchable-select-option-check hidden ms-2 text-primary-600 shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>

                {{-- Load more indicator (infinite scroll) --}}
                <div class="searchable-select-load-more hidden px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center border-t border-gray-200 dark:border-gray-700">
                    <span class="inline-flex items-center gap-2">
                        <x-accelade::icon name="heroicon-o-arrow-path" size="sm" class="animate-spin" :showFallback="false" />
                        {{ __('Loading more...') }}
                    </span>
                </div>

                {{-- Scroll to load more hint --}}
                <div class="searchable-select-scroll-hint hidden px-3 py-1 text-xs text-gray-400 dark:text-gray-500 text-center bg-gray-50 dark:bg-gray-900/50">
                    {{ __('Scroll for more options') }}
                </div>

                {{-- No results message --}}
                <div class="searchable-select-no-results hidden px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ $field->getNoResultsText() }}
                </div>

                {{-- No options message --}}
                <div class="searchable-select-no-options hidden px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ $field->getNoOptionsMessage() }}
                </div>

                {{-- Create option (for taggable) --}}
                @if($field->isTaggable())
                    <div class="searchable-select-create hidden px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-primary-600 dark:text-primary-400 border-t border-gray-200 dark:border-gray-700">
                        {!! str_replace('{value}', '<span class="searchable-select-create-value font-medium"></span>', e($field->getCreateOptionText())) !!}
                    </div>
                @endif
            </div>
        </div>

        {{-- Multiple selection tags display --}}
        @if($isMultiple)
            <div class="searchable-select-tags flex flex-wrap gap-1 mt-2 hidden">
                {{-- Tags will be dynamically added here --}}
            </div>
        @endif

        {{-- Create Option Modal --}}
        @if($hasCreateOptionForm)
            <div class="searchable-select-create-modal fixed inset-0 z-[100] hidden" aria-labelledby="create-modal-title-{{ $field->getId() }}" role="dialog" aria-modal="true">
                {{-- Backdrop --}}
                <div class="searchable-select-modal-backdrop fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity"></div>

                {{-- Modal --}}
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="searchable-select-modal-panel relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            {{-- Header --}}
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" id="create-modal-title-{{ $field->getId() }}">
                                        {{ $field->getCreateOptionModalHeading() }}
                                    </h3>
                                    <button type="button" class="searchable-select-modal-close text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
                                        <x-accelade::icon name="heroicon-o-x-mark" size="md" :showFallback="false" />
                                    </button>
                                </div>
                            </div>

                            {{-- Form with Content and Footer --}}
                            <form class="searchable-select-create-form" novalidate>
                                {{-- Form Content --}}
                                <div class="px-4 py-4">
                                    <div class="searchable-select-create-form-content space-y-4">
                                        @foreach($field->getCreateOptionForm() ?? [] as $formField)
                                            {!! $formField !!}
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                                    <button type="button" class="searchable-select-modal-cancel px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="searchable-select-modal-submit px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        {{ $field->getCreateOptionModalSubmitButtonLabel() }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Edit Option Modal --}}
        @if($hasEditOptionForm)
            <div class="searchable-select-edit-modal fixed inset-0 z-[100] hidden" aria-labelledby="edit-modal-title-{{ $field->getId() }}" role="dialog" aria-modal="true">
                {{-- Backdrop --}}
                <div class="searchable-select-modal-backdrop fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity"></div>

                {{-- Modal --}}
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="searchable-select-modal-panel relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            {{-- Header --}}
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" id="edit-modal-title-{{ $field->getId() }}">
                                        {{ $field->getEditOptionModalHeading() }}
                                    </h3>
                                    <button type="button" class="searchable-select-modal-close text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
                                        <x-accelade::icon name="heroicon-o-x-mark" size="md" :showFallback="false" />
                                    </button>
                                </div>
                            </div>

                            {{-- Form with Content and Footer --}}
                            <form class="searchable-select-edit-form" novalidate>
                                {{-- Form Content --}}
                                <div class="px-4 py-4">
                                    <div class="searchable-select-edit-form-content space-y-4">
                                        @foreach($field->getEditOptionForm() ?? [] as $formField)
                                            {!! $formField !!}
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                                    <button type="button" class="searchable-select-modal-cancel px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="searchable-select-modal-submit px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        {{ $field->getEditOptionModalSubmitButtonLabel() }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

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

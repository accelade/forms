@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isRequired = $field->isRequired();
    $placeholder = $field->getPlaceholder() ?? 'Select an icon...';
    $icons = $field->getIcons();
    $sets = $field->getSets();
    $defaultSet = $field->getDefaultSet();
    $setLabels = $field->getSetLabels();
    $categoryLabels = $field->getCategoryLabels();
    $searchable = $field->isSearchable();
    $gridColumns = $field->getGridColumns();
    $showIconName = $field->getShowIconName();
    $multiple = $field->isMultiple();
    $maxItems = $field->getMaxItems();
    $minItems = $field->getMinItems();
    $hasMultipleSets = count($icons) > 1;
@endphp

<div {{ $attributes->class(['form-field icon-picker-field', config('forms.styles.field', 'mb-4')]) }}>
    @if($field->getLabel())
        <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div
        class="icon-picker-wrapper relative"
        data-multiple="{{ $multiple ? 'true' : 'false' }}"
        data-default-set="{{ $defaultSet }}"
        @if($maxItems) data-max-items="{{ $maxItems }}" @endif
        @if($minItems) data-min-items="{{ $minItems }}" @endif
    >
        {{-- Trigger button --}}
        <button
            type="button"
            class="icon-picker-trigger w-full flex items-center justify-between px-3 py-2.5 text-left rounded-xl border border-neutral-200 bg-white shadow-sm text-sm transition-all duration-200
                   hover:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10 focus:border-neutral-400
                   dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600 dark:text-neutral-100 dark:focus:ring-neutral-100/10
                   {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
            @if($isDisabled) disabled @endif
        >
            <span class="flex items-center gap-2.5">
                <span class="icon-picker-selected text-xl leading-none"></span>
                <span class="icon-picker-selected-name text-neutral-700 dark:text-neutral-300"></span>
            </span>
            <span class="icon-picker-placeholder text-neutral-400 dark:text-neutral-500">{{ $placeholder }}</span>
            <svg class="icon-picker-arrow w-4 h-4 text-neutral-400 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        {{-- Dropdown --}}
        <div class="icon-picker-dropdown absolute z-50 w-full mt-2 bg-white rounded-xl border border-neutral-200 shadow-xl overflow-hidden dark:bg-neutral-900 dark:border-neutral-700" hidden>
            {{-- Search --}}
            @if($searchable)
                <div class="icon-picker-search p-3 border-b border-neutral-100 dark:border-neutral-800">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input
                            type="text"
                            class="icon-picker-search-input w-full ps-9 pe-3 py-2 text-sm rounded-lg border border-neutral-200 bg-neutral-50 placeholder-neutral-400
                                   focus:outline-none focus:ring-2 focus:ring-neutral-900/10 focus:border-neutral-400 focus:bg-white
                                   dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder-neutral-500 dark:focus:ring-neutral-100/10 dark:focus:bg-neutral-800"
                            placeholder="Search icons..."
                        />
                    </div>
                </div>
            @endif

            {{-- Icon Set Tabs (Emoji, Boxicons, Heroicons, Lucide) --}}
            @if($hasMultipleSets)
                <div class="icon-picker-set-tabs flex border-b border-neutral-100 bg-neutral-50 dark:bg-neutral-800/50 dark:border-neutral-800">
                    @foreach($icons as $setName => $setCategories)
                        <button
                            type="button"
                            class="icon-picker-set-tab flex items-center gap-1.5 px-4 py-2.5 text-xs font-medium whitespace-nowrap transition-all duration-200
                                   {{ $setName === $defaultSet ? 'text-neutral-900 bg-white border-b-2 border-neutral-900 -mb-px dark:text-white dark:bg-neutral-900 dark:border-white' : 'text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}"
                            data-set="{{ $setName }}"
                        >
                            @if($setName === 'emoji')
                                <span class="text-base">😀</span>
                            @elseif($setName === 'boxicons')
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h16v16H4z"/></svg>
                            @elseif($setName === 'heroicons')
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            @elseif($setName === 'lucide')
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            @endif
                            {{ $setLabels[$setName] ?? ucfirst($setName) }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Icon Set Panels --}}
            <div class="icon-picker-set-panels">
                @foreach($icons as $setName => $setCategories)
                    <div
                        class="icon-picker-set-panel {{ $setName === $defaultSet ? '' : 'hidden' }}"
                        data-set="{{ $setName }}"
                    >
                        {{-- Category Tabs within each set --}}
                        @if(count($setCategories) > 1)
                            <div class="icon-picker-category-tabs flex border-b border-neutral-100 overflow-x-auto dark:border-neutral-800">
                                @foreach($setCategories as $category => $categoryIcons)
                                    <button
                                        type="button"
                                        class="icon-picker-category-tab px-3 py-2 text-xs font-medium whitespace-nowrap transition-colors duration-150
                                               {{ $loop->first ? 'text-neutral-900 border-b-2 border-neutral-400 dark:text-white dark:border-neutral-500' : 'text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300' }}"
                                        data-category="{{ $category }}"
                                        data-set="{{ $setName }}"
                                    >
                                        {{ $categoryLabels[$category] ?? ucfirst($category) }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        {{-- Category Panels --}}
                        <div class="icon-picker-category-panels">
                            @foreach($setCategories as $category => $categoryIcons)
                                <div
                                    class="icon-picker-category-panel {{ $loop->first ? '' : 'hidden' }}"
                                    data-category="{{ $category }}"
                                    data-set="{{ $setName }}"
                                >
                                    <div class="icon-picker-grid grid gap-1 p-3 max-h-64 overflow-y-auto" style="grid-template-columns: repeat({{ $gridColumns }}, minmax(0, 1fr));">
                                        @foreach($categoryIcons as $iconValue => $iconName)
                                            @php
                                                $isEmoji = $setName === 'emoji';
                                                $isSvg = str_starts_with($iconValue, 'svg:');
                                                $svgPaths = $isSvg ? substr($iconValue, 4) : '';
                                            @endphp
                                            <button
                                                type="button"
                                                class="icon-picker-item group relative flex flex-col items-center justify-center p-2 rounded-lg text-neutral-600 transition-all duration-150
                                                       hover:bg-neutral-100 hover:text-neutral-900 hover:scale-105
                                                       focus:outline-none focus:ring-2 focus:ring-neutral-400 focus:ring-offset-1
                                                       dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white
                                                       [&.is-selected]:bg-neutral-900 [&.is-selected]:text-white dark:[&.is-selected]:bg-white dark:[&.is-selected]:text-neutral-900"
                                                data-icon="{{ $iconValue }}"
                                                data-name="{{ $iconName }}"
                                                data-category="{{ $category }}"
                                                data-set="{{ $setName }}"
                                                data-type="{{ $isEmoji ? 'emoji' : ($isSvg ? 'svg' : 'icon') }}"
                                                title="{{ $iconName }}"
                                            >
                                                @if($isEmoji)
                                                    <span class="icon-picker-icon text-2xl leading-none">{{ $iconValue }}</span>
                                                @elseif($isSvg)
                                                    @php
                                                        // Boxicons use filled paths, Heroicons/Lucide use stroked paths
                                                        $isFilledIcon = $setName === 'boxicons';
                                                    @endphp
                                                    <span class="icon-picker-icon w-6 h-6">
                                                        @if($isFilledIcon)
                                                            <svg class="w-full h-full" viewBox="0 0 24 24" fill="currentColor">
                                                                @foreach(explode('|', $svgPaths) as $pathData)
                                                                    @if(str_starts_with($pathData, 'circle:'))
                                                                        @php
                                                                            $circleParams = explode(',', substr($pathData, 7));
                                                                            $cx = $circleParams[0] ?? '12';
                                                                            $cy = $circleParams[1] ?? '12';
                                                                            $r = $circleParams[2] ?? '10';
                                                                        @endphp
                                                                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"/>
                                                                    @elseif(str_starts_with($pathData, 'rect:'))
                                                                        @php
                                                                            $rectParams = explode(',', substr($pathData, 5));
                                                                            $x = $rectParams[0] ?? '0';
                                                                            $y = $rectParams[1] ?? '0';
                                                                            $width = $rectParams[2] ?? '10';
                                                                            $height = $rectParams[3] ?? '10';
                                                                            $rx = '0';
                                                                            $ry = '0';
                                                                            foreach ($rectParams as $param) {
                                                                                if (str_starts_with($param, 'rx:')) $rx = substr($param, 3);
                                                                                if (str_starts_with($param, 'ry:')) $ry = substr($param, 3);
                                                                            }
                                                                        @endphp
                                                                        <rect x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" rx="{{ $rx }}" ry="{{ $ry }}"/>
                                                                    @elseif(str_starts_with($pathData, 'M') || str_starts_with($pathData, 'm'))
                                                                        <path d="{{ $pathData }}"/>
                                                                    @endif
                                                                @endforeach
                                                            </svg>
                                                        @else
                                                            <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                @foreach(explode('|', $svgPaths) as $pathData)
                                                                    @if(str_starts_with($pathData, 'circle:'))
                                                                        @php
                                                                            $circleParams = explode(',', substr($pathData, 7));
                                                                            $cx = $circleParams[0] ?? '12';
                                                                            $cy = $circleParams[1] ?? '12';
                                                                            $r = $circleParams[2] ?? '10';
                                                                        @endphp
                                                                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"/>
                                                                    @elseif(str_starts_with($pathData, 'rect:'))
                                                                        @php
                                                                            $rectParams = explode(',', substr($pathData, 5));
                                                                            $x = $rectParams[0] ?? '0';
                                                                            $y = $rectParams[1] ?? '0';
                                                                            $width = $rectParams[2] ?? '10';
                                                                            $height = $rectParams[3] ?? '10';
                                                                            $rx = '0';
                                                                            $ry = '0';
                                                                            foreach ($rectParams as $param) {
                                                                                if (str_starts_with($param, 'rx:')) $rx = substr($param, 3);
                                                                                if (str_starts_with($param, 'ry:')) $ry = substr($param, 3);
                                                                            }
                                                                        @endphp
                                                                        <rect x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" rx="{{ $rx }}" ry="{{ $ry }}"/>
                                                                    @elseif(str_starts_with($pathData, 'ellipse:'))
                                                                        @php
                                                                            $ellipseParams = explode(',', substr($pathData, 8));
                                                                            $cx = $ellipseParams[0] ?? '12';
                                                                            $cy = $ellipseParams[1] ?? '12';
                                                                            $rx = $ellipseParams[2] ?? '10';
                                                                            $ry = $ellipseParams[3] ?? '5';
                                                                        @endphp
                                                                        <ellipse cx="{{ $cx }}" cy="{{ $cy }}" rx="{{ $rx }}" ry="{{ $ry }}"/>
                                                                    @elseif(str_starts_with($pathData, 'M') || str_starts_with($pathData, 'm'))
                                                                        <path d="{{ $pathData }}"/>
                                                                    @endif
                                                                @endforeach
                                                            </svg>
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="icon-picker-icon text-lg" data-icon-class="{{ $iconValue }}">
                                                        <i class="{{ $iconValue }}"></i>
                                                    </span>
                                                @endif
                                                @if($showIconName)
                                                    <span class="icon-picker-name text-[10px] mt-1 truncate w-full text-center opacity-60 group-hover:opacity-100">{{ $iconName }}</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Empty state --}}
            <div class="icon-picker-empty hidden p-8 text-center text-neutral-500 dark:text-neutral-400">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium">No icons found</p>
                <p class="text-xs mt-1 opacity-60">Try a different search term</p>
            </div>
        </div>

        <input type="hidden" name="{{ $statePath }}" class="icon-picker-value" @if($isRequired) required @endif />
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-neutral-500 dark:text-neutral-400 mt-1.5') }}">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="{{ config('forms.styles.hint', 'text-sm text-neutral-500 dark:text-neutral-400 mt-1.5') }}">{{ $field->getHint() }}</p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

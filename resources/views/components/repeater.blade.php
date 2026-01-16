@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isRequired = $field->isRequired();
    $schema = $field->getSchema();
    $minItems = $field->getMinItems();
    $maxItems = $field->getMaxItems();
    $defaultItems = $field->getDefaultItems();
    $isAddable = $field->isAddable();
    $isDeletable = $field->isDeletable();
    $isReorderable = $field->isReorderable();
    $isCollapsible = $field->isCollapsible();
    $isCollapsed = $field->isCollapsed();
    $isCloneable = $field->isCloneable();
    $isSimple = $field->isSimple();
    $addActionLabel = $field->getAddActionLabel();
    $deleteActionLabel = $field->getDeleteActionLabel();
    $cloneActionLabel = $field->getCloneActionLabel();
    $reorderActionLabel = $field->getReorderActionLabel();
    $collapseActionLabel = $field->getCollapseActionLabel();
    $expandActionLabel = $field->getExpandActionLabel();
    $collapseAllActionLabel = $field->getCollapseAllActionLabel();
    $expandAllActionLabel = $field->getExpandAllActionLabel();
    $grid = $field->getGrid();
    $columns = $field->getColumns();
    $defaultValue = $field->getDefault() ?? [];

    // Grid classes
    $gridClasses = '';
    if ($grid) {
        if (is_int($grid)) {
            $gridClasses = match($grid) {
                1 => 'grid-cols-1',
                2 => 'sm:grid-cols-2',
                3 => 'sm:grid-cols-2 lg:grid-cols-3',
                4 => 'sm:grid-cols-2 lg:grid-cols-4',
                default => 'sm:grid-cols-2 lg:grid-cols-' . $grid,
            };
        }
    }

    // Column classes for schema inside items
    $columnClasses = '';
    if ($columns) {
        $columnClasses = match($columns) {
            1 => 'grid-cols-1',
            2 => 'sm:grid-cols-2',
            3 => 'sm:grid-cols-2 lg:grid-cols-3',
            4 => 'sm:grid-cols-2 lg:grid-cols-4',
            default => 'sm:grid-cols-2',
        };
    }
@endphp

<div {{ $attributes->class(['form-field repeater-field', config('forms.styles.field', 'mb-4')]) }}
    id="{{ $id }}"
    data-min-items="{{ $minItems }}"
    data-max-items="{{ $maxItems }}"
    data-default-items="{{ $defaultItems }}"
    data-name="{{ $name }}"
    data-collapsed="{{ $isCollapsed ? 'true' : 'false' }}"
    data-simple="{{ $isSimple ? 'true' : 'false' }}"
>
    @if($field->getLabel())
        <div class="flex items-center justify-between mb-1.5">
            <label class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200') }}">
                {{ $field->getLabel() }}
                @if($isRequired)
                    <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
                @endif
            </label>
            @if($isCollapsible && count($defaultValue) > 1)
                <div class="flex items-center gap-1">
                    <button type="button" class="repeater-expand-all text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" title="{{ $expandAllActionLabel }}">
                        {{ $expandAllActionLabel }}
                    </button>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <button type="button" class="repeater-collapse-all text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" title="{{ $collapseAllActionLabel }}">
                        {{ $collapseAllActionLabel }}
                    </button>
                </div>
            @endif
        </div>
    @endif

    <div class="repeater-wrapper {{ $isDisabled ? 'opacity-50' : '' }}">
        <div class="repeater-items {{ $grid ? 'grid gap-3 ' . $gridClasses : 'space-y-3' }}" id="{{ $id }}-items">
            {{-- Render default items with their data --}}
            @if(count($defaultValue) > 0)
                @foreach($defaultValue as $index => $itemData)
                    @if($isSimple)
                        {{-- Simple mode: minimal design for single field --}}
                        <div class="repeater-item repeater-item-simple flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 shadow-sm dark:border-gray-700 dark:bg-gray-800" data-index="{{ $index }}">
                            @if($isReorderable && !$isDisabled)
                                <button type="button" class="repeater-reorder-handle flex-shrink-0 flex items-center justify-center text-gray-400 hover:text-gray-600 cursor-grab active:cursor-grabbing dark:hover:text-gray-300" title="{{ $reorderActionLabel }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                                    </svg>
                                </button>
                            @endif
                            <div class="flex-1 min-w-0">
                                @foreach($schema as $schemaField)
                                    @php
                                        $clonedField = clone $schemaField;
                                        $fieldName = $clonedField->getName();
                                        $clonedField->name($name . '[' . $index . '][' . $fieldName . ']');
                                        $clonedField->id($name . '_' . $index . '_' . $fieldName);
                                        $clonedField->label(null);
                                        if (isset($itemData[$fieldName])) {
                                            $clonedField->default($itemData[$fieldName]);
                                        } elseif (!is_array($itemData)) {
                                            $clonedField->default($itemData);
                                        }
                                    @endphp
                                    {!! $clonedField->render() !!}
                                @endforeach
                            </div>
                            @if($isDeletable && !$isDisabled)
                                <button type="button" class="repeater-delete flex-shrink-0 p-1 text-gray-400 hover:text-red-500 transition-colors duration-150 dark:hover:text-red-400" title="{{ $deleteActionLabel }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @else
                        {{-- Standard mode: card-based design --}}
                        <div class="repeater-item rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-gray-700 dark:bg-gray-800 {{ $isCollapsed ? 'is-collapsed' : '' }}" data-index="{{ $index }}" @if($isReorderable && !$isDisabled) draggable="true" @endif>
                            {{-- Item header --}}
                            <div class="repeater-item-header flex items-center justify-between px-4 py-2.5 bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    @if($isReorderable && !$isDisabled)
                                        <button type="button" class="repeater-reorder-handle flex items-center justify-center text-gray-400 hover:text-gray-600 cursor-grab active:cursor-grabbing dark:hover:text-gray-300" title="{{ $reorderActionLabel }}">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    <span class="repeater-item-label text-sm font-medium text-gray-700 dark:text-gray-300">{{ $field->getItemLabel($index, $itemData) }}</span>
                                </div>
                                <div class="repeater-item-actions flex items-center gap-1">
                                    @if($isCollapsible)
                                        <button type="button" class="repeater-collapse p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition-colors duration-150 dark:hover:text-gray-300 dark:hover:bg-gray-600" title="{{ $isCollapsed ? $expandActionLabel : $collapseActionLabel }}">
                                            <svg class="w-4 h-4 collapse-icon transition-transform duration-200 {{ $isCollapsed ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    @if($isCloneable && !$isDisabled)
                                        <button type="button" class="repeater-clone p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition-colors duration-150 dark:hover:text-gray-300 dark:hover:bg-gray-600" title="{{ $cloneActionLabel }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    @if($isDeletable && !$isDisabled)
                                        <button type="button" class="repeater-delete p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors duration-150 dark:hover:text-red-400 dark:hover:bg-red-900/20" title="{{ $deleteActionLabel }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            {{-- Item content with schema fields --}}
                            <div class="repeater-item-content p-4 {{ $columns ? 'grid gap-4 ' . $columnClasses : 'space-y-4' }}" @if($isCollapsed) hidden @endif>
                                @foreach($schema as $schemaField)
                                    @php
                                        // Clone the field to avoid mutation
                                        $clonedField = clone $schemaField;
                                        $fieldName = $clonedField->getName();
                                        $clonedField->name($name . '[' . $index . '][' . $fieldName . ']');
                                        $clonedField->id($name . '_' . $index . '_' . $fieldName);
                                        if (isset($itemData[$fieldName])) {
                                            $clonedField->default($itemData[$fieldName]);
                                        }
                                    @endphp
                                    {!! $clonedField->render() !!}
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        @if($isAddable && !$isDisabled)
            <button type="button" class="repeater-add mt-3 inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-primary-600 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:border-gray-400 transition-colors duration-150 dark:text-primary-400 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-500 disabled:opacity-50 disabled:cursor-not-allowed" id="{{ $id }}-add">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ $addActionLabel }}
            </button>
        @endif
    </div>

    {{-- Template for new repeater items (used by JavaScript) --}}
    @if($isSimple)
        <template id="{{ $id }}-template">
            <div class="repeater-item repeater-item-simple flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                @if($isReorderable && !$isDisabled)
                    <button type="button" class="repeater-reorder-handle flex-shrink-0 flex items-center justify-center text-gray-400 hover:text-gray-600 cursor-grab active:cursor-grabbing dark:hover:text-gray-300" title="{{ $reorderActionLabel }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                        </svg>
                    </button>
                @endif
                <div class="flex-1 min-w-0">
                    @foreach($schema as $schemaField)
                        @php
                            $clonedField = clone $schemaField;
                            $fieldName = $clonedField->getName();
                            $clonedField->name($name . '[__INDEX__][' . $fieldName . ']');
                            $clonedField->id($name . '___INDEX___' . $fieldName);
                            $clonedField->label(null);
                            $clonedField->default(null);
                        @endphp
                        {!! $clonedField->render() !!}
                    @endforeach
                </div>
                @if($isDeletable && !$isDisabled)
                    <button type="button" class="repeater-delete flex-shrink-0 p-1 text-gray-400 hover:text-red-500 transition-colors duration-150 dark:hover:text-red-400" title="{{ $deleteActionLabel }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </template>
    @else
        <template id="{{ $id }}-template">
            <div class="repeater-item rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-gray-700 dark:bg-gray-800" @if($isReorderable && !$isDisabled) draggable="true" @endif>
                {{-- Item header --}}
                <div class="repeater-item-header flex items-center justify-between px-4 py-2.5 bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        @if($isReorderable && !$isDisabled)
                            <button type="button" class="repeater-reorder-handle flex items-center justify-center text-gray-400 hover:text-gray-600 cursor-grab active:cursor-grabbing dark:hover:text-gray-300" title="{{ $reorderActionLabel }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                                </svg>
                            </button>
                        @endif
                        <span class="repeater-item-label text-sm font-medium text-gray-700 dark:text-gray-300">Item</span>
                    </div>
                    <div class="repeater-item-actions flex items-center gap-1">
                        @if($isCollapsible)
                            <button type="button" class="repeater-collapse p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition-colors duration-150 dark:hover:text-gray-300 dark:hover:bg-gray-600" title="{{ $collapseActionLabel }}">
                                <svg class="w-4 h-4 collapse-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        @endif
                        @if($isCloneable && !$isDisabled)
                            <button type="button" class="repeater-clone p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition-colors duration-150 dark:hover:text-gray-300 dark:hover:bg-gray-600" title="{{ $cloneActionLabel }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        @endif
                        @if($isDeletable && !$isDisabled)
                            <button type="button" class="repeater-delete p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors duration-150 dark:hover:text-red-400 dark:hover:bg-red-900/20" title="{{ $deleteActionLabel }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
                {{-- Item content with schema fields --}}
                <div class="repeater-item-content p-4 {{ $columns ? 'grid gap-4 ' . $columnClasses : 'space-y-4' }}">
                    @foreach($schema as $schemaField)
                        @php
                            // Clone the field with template placeholders
                            $clonedField = clone $schemaField;
                            $fieldName = $clonedField->getName();
                            $clonedField->name($name . '[__INDEX__][' . $fieldName . ']');
                            $clonedField->id($name . '___INDEX___' . $fieldName);
                            $clonedField->default(null);
                        @endphp
                        {!! $clonedField->render() !!}
                    @endforeach
                </div>
            </div>
        </template>
    @endif

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

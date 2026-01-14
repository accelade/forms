@props([
    'field',
])

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
    $isCloneable = $field->isCloneable();
    $addActionLabel = $field->getAddActionLabel();
@endphp

<div {{ $attributes->class(['form-field repeater-field']) }}
    data-min-items="{{ $minItems }}"
    data-max-items="{{ $maxItems }}"
    data-default-items="{{ $defaultItems }}"
>
    @if($field->getLabel())
        <label class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="repeater-wrapper">
        <div class="repeater-items" id="{{ $id }}-items">
            {{-- Repeater items will be rendered here --}}
        </div>

        @if($isAddable && !$isDisabled)
            <button type="button" class="repeater-add" id="{{ $id }}-add">
                {{ $addActionLabel }}
            </button>
        @endif
    </div>

    {{-- Template for repeater item --}}
    <template id="{{ $id }}-template">
        <div class="repeater-item" @if($isReorderable) draggable="true" @endif>
            <div class="repeater-item-header">
                @if($isReorderable)
                    <button type="button" class="repeater-reorder-handle" title="Drag to reorder">⋮⋮</button>
                @endif
                <span class="repeater-item-label"></span>
                <div class="repeater-item-actions">
                    @if($isCollapsible)
                        <button type="button" class="repeater-collapse" title="Collapse">−</button>
                    @endif
                    @if($isCloneable && !$isDisabled)
                        <button type="button" class="repeater-clone" title="Clone">⎘</button>
                    @endif
                    @if($isDeletable && !$isDisabled)
                        <button type="button" class="repeater-delete" title="Delete">&times;</button>
                    @endif
                </div>
            </div>
            <div class="repeater-item-content">
                {{-- Schema fields will be rendered here --}}
            </div>
        </div>
    </template>

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

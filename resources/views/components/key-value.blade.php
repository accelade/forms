@props([
    'field',
])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isReadOnly = $field->isReadOnly();
    $isRequired = $field->isRequired();
    $keyLabel = $field->getKeyLabel();
    $valueLabel = $field->getValueLabel();
    $keyPlaceholder = $field->getKeyPlaceholder();
    $valuePlaceholder = $field->getValuePlaceholder();
    $isAddable = $field->isAddable();
    $isDeletable = $field->isDeletable();
    $isReorderable = $field->isReorderable();
@endphp

<div {{ $attributes->class(['form-field key-value-field']) }}>
    @if($field->getLabel())
        <label class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div class="key-value-wrapper">
        <div class="key-value-header">
            <span class="key-value-header-key">{{ $keyLabel }}</span>
            <span class="key-value-header-value">{{ $valueLabel }}</span>
            <span class="key-value-header-actions"></span>
        </div>

        <div class="key-value-rows" data-template-id="{{ $id }}-template">
            {{-- Rows will be rendered here --}}
        </div>

        <template id="{{ $id }}-template">
            <div class="key-value-row" @if($isReorderable) draggable="true" @endif>
                @if($isReorderable)
                    <button type="button" class="key-value-reorder-handle" title="Drag to reorder">⋮⋮</button>
                @endif
                <input
                    type="text"
                    class="key-value-key"
                    @if($keyPlaceholder) placeholder="{{ $keyPlaceholder }}" @endif
                    @if($isDisabled) disabled @endif
                    @if($isReadOnly) readonly @endif
                />
                <input
                    type="text"
                    class="key-value-value"
                    @if($valuePlaceholder) placeholder="{{ $valuePlaceholder }}" @endif
                    @if($isDisabled) disabled @endif
                    @if($isReadOnly) readonly @endif
                />
                @if($isDeletable && !$isDisabled && !$isReadOnly)
                    <button type="button" class="key-value-delete" title="Remove row">&times;</button>
                @endif
            </div>
        </template>

        @if($isAddable && !$isDisabled && !$isReadOnly)
            <button type="button" class="key-value-add">
                Add Row
            </button>
        @endif

        <input type="hidden" name="{{ $statePath }}" class="key-value-data" />
    </div>

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

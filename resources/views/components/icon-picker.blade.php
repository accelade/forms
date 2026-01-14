@props([
    'field',
])

@php
    $id = $field->getId();
    $name = $field->getName();
    $statePath = $field->getStatePath();
    $isDisabled = $field->isDisabled();
    $isRequired = $field->isRequired();
    $placeholder = $field->getPlaceholder() ?? 'Select an icon...';
    $icons = $field->getIcons();
    $searchable = $field->isSearchable();
    $gridColumns = $field->getGridColumns();
    $showIconName = $field->getShowIconName();
    $multiple = $field->isMultiple();
    $maxItems = $field->getMaxItems();
    $minItems = $field->getMinItems();
@endphp

<div {{ $attributes->class(['form-field icon-picker-field']) }}>
    @if($field->getLabel())
        <label class="form-label">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="required-indicator">*</span>
            @endif
        </label>
    @endif

    <div
        class="icon-picker-wrapper"
        data-multiple="{{ $multiple ? 'true' : 'false' }}"
        @if($maxItems) data-max-items="{{ $maxItems }}" @endif
        @if($minItems) data-min-items="{{ $minItems }}" @endif
    >
        <button
            type="button"
            class="icon-picker-trigger"
            @if($isDisabled) disabled @endif
        >
            <span class="icon-picker-selected"></span>
            <span class="icon-picker-placeholder">{{ $placeholder }}</span>
            <span class="icon-picker-arrow">▼</span>
        </button>

        <div class="icon-picker-dropdown" hidden>
            @if($searchable)
                <div class="icon-picker-search">
                    <input
                        type="text"
                        class="icon-picker-search-input"
                        placeholder="Search icons..."
                    />
                </div>
            @endif

            <div class="icon-picker-grid" style="--grid-columns: {{ $gridColumns }};">
                @foreach($icons as $icon)
                    <button
                        type="button"
                        class="icon-picker-item"
                        data-icon="{{ $icon }}"
                        title="{{ $icon }}"
                    >
                        <span class="icon-picker-icon">{{ $icon }}</span>
                        @if($showIconName)
                            <span class="icon-picker-name">{{ $icon }}</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <input type="hidden" name="{{ $statePath }}" class="icon-picker-value" @if($isRequired) required @endif />
    </div>

    @if($field->getHelperText())
        <p class="form-helper-text">{{ $field->getHelperText() }}</p>
    @endif

    @if($field->getHint())
        <p class="form-hint">{{ $field->getHint() }}</p>
    @endif
</div>

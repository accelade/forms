<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Icon Picker Component
 *
 * A picker for selecting icons from a grid.
 */
class IconPicker extends Field
{
    /** @var array<int, string>|Closure */
    protected array|Closure $icons = [];

    protected bool|Closure $searchable = true;

    protected int|Closure $gridColumns = 8;

    protected bool|Closure $showIconName = true;

    protected bool|Closure $multiple = false;

    protected int|Closure|null $maxItems = null;

    protected int|Closure|null $minItems = null;

    /**
     * Set the available icons.
     *
     * @param  array<int, string>|Closure  $icons
     */
    public function icons(array|Closure $icons): static
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * Get the icons array.
     *
     * @return array<int, string>
     */
    public function getIcons(): array
    {
        $icons = $this->evaluate($this->icons);

        if (empty($icons)) {
            return $this->getDefaultIcons();
        }

        return is_array($icons) ? $icons : [];
    }

    /**
     * Get default popular icons.
     *
     * @return array<int, string>
     */
    protected function getDefaultIcons(): array
    {
        return [
            'home', 'user', 'users', 'settings', 'search', 'heart', 'star',
            'mail', 'phone', 'message-square', 'bell', 'calendar', 'clock',
            'map-pin', 'tag', 'folder', 'file', 'image', 'video', 'music',
            'download', 'upload', 'trash', 'edit', 'check', 'x', 'plus', 'minus',
            'chevron-right', 'chevron-left', 'chevron-up', 'chevron-down',
            'arrow-right', 'arrow-left', 'arrow-up', 'arrow-down',
            'external-link', 'link', 'copy', 'share', 'bookmark', 'flag',
            'shield', 'lock', 'unlock', 'eye', 'eye-off', 'help-circle',
            'info', 'alert-circle', 'alert-triangle', 'check-circle', 'x-circle',
            'sun', 'moon', 'cloud', 'zap', 'droplet', 'flame',
            'shopping-cart', 'shopping-bag', 'credit-card', 'dollar-sign',
            'briefcase', 'layers', 'grid', 'list', 'menu', 'more-horizontal',
        ];
    }

    /**
     * Enable/disable search functionality.
     */
    public function searchable(bool|Closure $condition = true): static
    {
        $this->searchable = $condition;

        return $this;
    }

    /**
     * Get searchable state.
     */
    public function isSearchable(): bool
    {
        return $this->evaluate($this->searchable);
    }

    /**
     * Set the number of grid columns.
     */
    public function gridColumns(int|Closure $columns): static
    {
        $this->gridColumns = $columns;

        return $this;
    }

    /**
     * Get grid columns.
     */
    public function getGridColumns(): int
    {
        return $this->evaluate($this->gridColumns);
    }

    /**
     * Show/hide icon name below each icon.
     */
    public function showIconName(bool|Closure $condition = true): static
    {
        $this->showIconName = $condition;

        return $this;
    }

    /**
     * Get show icon name state.
     */
    public function getShowIconName(): bool
    {
        return $this->evaluate($this->showIconName);
    }

    /**
     * Enable multiple icon selection.
     */
    public function multiple(bool|Closure $condition = true): static
    {
        $this->multiple = $condition;

        return $this;
    }

    /**
     * Check if multiple selection is enabled.
     */
    public function isMultiple(): bool
    {
        return $this->evaluate($this->multiple);
    }

    /**
     * Set the maximum number of icons.
     */
    public function maxItems(int|Closure|null $count): static
    {
        $this->maxItems = $count;

        return $this;
    }

    /**
     * Get the maximum number of icons.
     */
    public function getMaxItems(): ?int
    {
        return $this->evaluate($this->maxItems);
    }

    /**
     * Set the minimum number of icons.
     */
    public function minItems(int|Closure|null $count): static
    {
        $this->minItems = $count;

        return $this;
    }

    /**
     * Get the minimum number of icons.
     */
    public function getMinItems(): ?int
    {
        return $this->evaluate($this->minItems);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.icon-picker';
    }
}

<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasOptions;
use Accelade\Forms\Field;
use Closure;

/**
 * Toggle buttons field component.
 */
class ToggleButtons extends Field
{
    use HasOptions;

    protected array|Closure $icons = [];

    protected array|Closure $colors = [];

    protected bool $isGrouped = true;

    /**
     * Set icons for each option.
     */
    public function icons(array|Closure $icons): static
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * Get the icons.
     */
    public function getIcons(): array
    {
        return $this->evaluate($this->icons);
    }

    /**
     * Get icon for a specific option.
     */
    public function getIcon(string $value): ?string
    {
        $icons = $this->getIcons();

        return $icons[$value] ?? null;
    }

    /**
     * Set colors for each option.
     */
    public function colors(array|Closure $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Get the colors.
     */
    public function getColors(): array
    {
        return $this->evaluate($this->colors);
    }

    /**
     * Get color for a specific option.
     */
    public function getColor(string $value): ?string
    {
        $colors = $this->getColors();

        return $colors[$value] ?? null;
    }

    /**
     * Display as grouped buttons.
     */
    public function grouped(bool $condition = true): static
    {
        $this->isGrouped = $condition;

        return $this;
    }

    /**
     * Check if grouped.
     */
    public function isGrouped(): bool
    {
        return $this->isGrouped;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.toggle-buttons';
    }
}

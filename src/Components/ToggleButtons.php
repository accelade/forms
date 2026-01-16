<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasOptions;
use Accelade\Forms\Field;
use Closure;

/**
 * Toggle buttons field component.
 *
 * Filament-compatible API with support for colors, icons, inline layout, and more.
 */
class ToggleButtons extends Field
{
    use HasOptions;

    protected array|Closure $icons = [];

    protected array|Closure $colors = [];

    protected bool $isGrouped = true;

    protected bool $isInline = true;

    protected int|string|Closure|null $columns = null;

    protected string $gridDirection = 'row';

    protected ?Closure $disableOptionWhen = null;

    /**
     * Color presets mapping.
     *
     * @var array<string, string>
     */
    public const COLOR_PRESETS = [
        'danger' => 'danger',
        'gray' => 'gray',
        'info' => 'info',
        'primary' => 'primary',
        'success' => 'success',
        'warning' => 'warning',
    ];

    /**
     * Set icons for each option.
     *
     * @param  array<string, string>|Closure  $icons
     */
    public function icons(array|Closure $icons): static
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * Get the icons.
     *
     * @return array<string, string>
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
     * Accepts color presets (danger, success, warning, info, primary, gray) or hex values.
     *
     * @param  array<string, string>|Closure  $colors
     */
    public function colors(array|Closure $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Get the colors.
     *
     * @return array<string, string>
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
     * Check if a color is a preset name.
     */
    public function isColorPreset(string $value): bool
    {
        $color = $this->getColor($value);

        return $color !== null && array_key_exists($color, self::COLOR_PRESETS);
    }

    /**
     * Display as grouped buttons (connected, no gap).
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
     * Display inline (horizontal layout).
     */
    public function inline(bool $condition = true): static
    {
        $this->isInline = $condition;

        return $this;
    }

    /**
     * Check if inline.
     */
    public function isInline(): bool
    {
        return $this->isInline;
    }

    /**
     * Set number of columns for grid layout.
     */
    public function columns(int|string|Closure|null $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get columns.
     */
    public function getColumns(): int|string|null
    {
        return $this->evaluate($this->columns);
    }

    /**
     * Set grid direction (row or column).
     */
    public function gridDirection(string $direction): static
    {
        $this->gridDirection = $direction;

        return $this;
    }

    /**
     * Get grid direction.
     */
    public function getGridDirection(): string
    {
        return $this->gridDirection;
    }

    /**
     * Configure as boolean toggle (Yes/No).
     */
    public function boolean(?string $trueLabel = null, ?string $falseLabel = null): static
    {
        $this->options([
            '1' => $trueLabel ?? __('Yes'),
            '0' => $falseLabel ?? __('No'),
        ]);

        return $this;
    }

    /**
     * Set a callback to disable specific options.
     */
    public function disableOptionWhen(?Closure $callback): static
    {
        $this->disableOptionWhen = $callback;

        return $this;
    }

    /**
     * Check if an option is disabled.
     */
    public function isOptionDisabled(string $value, string $label): bool
    {
        if ($this->disableOptionWhen === null) {
            return false;
        }

        return (bool) $this->evaluate($this->disableOptionWhen, [
            'value' => $value,
            'label' => $label,
        ]);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.toggle-buttons';
    }
}

<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Rate Input Component
 *
 * A rating input field with customizable stars/icons.
 */
class RateInput extends Field
{
    protected int|Closure $maxRating = 5;

    protected bool|Closure $allowHalf = false;

    protected string|Closure $icon = 'star';

    protected string|Closure|null $color = null;

    protected bool|Closure $showValue = false;

    protected bool|Closure $clearable = false;

    /**
     * Set the maximum rating value.
     */
    public function maxRating(int|Closure $max): static
    {
        $this->maxRating = $max;

        return $this;
    }

    /**
     * Get the maximum rating value.
     */
    public function getMaxRating(): int
    {
        return $this->evaluate($this->maxRating);
    }

    /**
     * Allow half-star ratings.
     */
    public function allowHalf(bool|Closure $allowHalf = true): static
    {
        $this->allowHalf = $allowHalf;

        return $this;
    }

    /**
     * Get allow half state.
     */
    public function getAllowHalf(): bool
    {
        return $this->evaluate($this->allowHalf);
    }

    /**
     * Set custom icon for rating.
     */
    public function icon(string|Closure $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon name.
     */
    public function getRateIcon(): string
    {
        return $this->evaluate($this->icon);
    }

    /**
     * Set custom color for filled icons.
     */
    public function color(string|Closure $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     */
    public function getRateColor(): ?string
    {
        return $this->evaluate($this->color);
    }

    /**
     * Show numeric value next to stars.
     */
    public function showValue(bool|Closure $showValue = true): static
    {
        $this->showValue = $showValue;

        return $this;
    }

    /**
     * Get show value state.
     */
    public function getShowValue(): bool
    {
        return $this->evaluate($this->showValue);
    }

    /**
     * Allow clearing the rating.
     */
    public function clearable(bool|Closure $clearable = true): static
    {
        $this->clearable = $clearable;

        return $this;
    }

    /**
     * Get clearable state.
     */
    public function isClearable(): bool
    {
        return $this->evaluate($this->clearable);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.rate-input';
    }
}

<?php

declare(strict_types=1);

namespace Accelade\Forms\Concerns;

use Closure;

/**
 * Trait for fields that have min/max constraints.
 */
trait HasMinMax
{
    protected int|float|Closure|null $minValue = null;

    protected int|float|Closure|null $maxValue = null;

    protected int|Closure|null $minLength = null;

    protected int|Closure|null $maxLength = null;

    /**
     * Set the minimum value.
     */
    public function minValue(int|float|Closure $value): static
    {
        $this->minValue = $value;

        return $this;
    }

    /**
     * Get the minimum value.
     */
    public function getMinValue(): int|float|null
    {
        return $this->evaluate($this->minValue);
    }

    /**
     * Set the maximum value.
     */
    public function maxValue(int|float|Closure $value): static
    {
        $this->maxValue = $value;

        return $this;
    }

    /**
     * Get the maximum value.
     */
    public function getMaxValue(): int|float|null
    {
        return $this->evaluate($this->maxValue);
    }

    /**
     * Set the minimum length.
     */
    public function minLength(int|Closure $length): static
    {
        $this->minLength = $length;

        return $this;
    }

    /**
     * Get the minimum length.
     */
    public function getMinLength(): ?int
    {
        return $this->evaluate($this->minLength);
    }

    /**
     * Set the maximum length.
     */
    public function maxLength(int|Closure $length): static
    {
        $this->maxLength = $length;

        return $this;
    }

    /**
     * Get the maximum length.
     */
    public function getMaxLength(): ?int
    {
        return $this->evaluate($this->maxLength);
    }
}

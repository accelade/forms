<?php

declare(strict_types=1);

namespace Accelade\Forms\Concerns;

use Closure;

/**
 * Trait for fields that have selectable options.
 */
trait HasOptions
{
    protected array|Closure $options = [];

    protected bool $isSearchable = false;

    protected bool $isMultiple = false;

    /**
     * Set the available options.
     */
    public function options(array|Closure $options): static
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get the options.
     */
    public function getOptions(): array
    {
        return $this->evaluate($this->options);
    }

    /**
     * Make the field searchable.
     */
    public function searchable(bool $condition = true): static
    {
        $this->isSearchable = $condition;

        return $this;
    }

    /**
     * Check if searchable.
     */
    public function isSearchable(): bool
    {
        return $this->isSearchable;
    }

    /**
     * Allow multiple selections.
     */
    public function multiple(bool $condition = true): static
    {
        $this->isMultiple = $condition;

        return $this;
    }

    /**
     * Check if multiple selection is allowed.
     */
    public function isMultiple(): bool
    {
        return $this->isMultiple;
    }
}

<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasOptions;
use Accelade\Forms\Field;
use Closure;

/**
 * Checkbox list field component for multiple selections.
 */
class CheckboxList extends Field
{
    use HasOptions;

    protected int $columns = 1;

    protected array|Closure $descriptions = [];

    protected bool $isBulkToggleable = false;

    /**
     * Set the number of columns.
     */
    public function columns(int $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get the number of columns.
     */
    public function getColumns(): int
    {
        return $this->columns;
    }

    /**
     * Set descriptions for each option.
     */
    public function descriptions(array|Closure $descriptions): static
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    /**
     * Get the descriptions.
     */
    public function getDescriptions(): array
    {
        return $this->evaluate($this->descriptions);
    }

    /**
     * Get description for a specific option.
     */
    public function getDescription(string $value): ?string
    {
        $descriptions = $this->getDescriptions();

        return $descriptions[$value] ?? null;
    }

    /**
     * Enable bulk toggle (select all / deselect all).
     */
    public function bulkToggleable(bool $condition = true): static
    {
        $this->isBulkToggleable = $condition;

        return $this;
    }

    /**
     * Check if bulk toggleable.
     */
    public function isBulkToggleable(): bool
    {
        return $this->isBulkToggleable;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.checkbox-list';
    }
}

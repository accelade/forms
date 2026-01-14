<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Repeater field component for repeatable field groups.
 */
class Repeater extends Field
{
    protected array $schema = [];

    protected ?int $minItems = null;

    protected ?int $maxItems = null;

    protected int $defaultItems = 1;

    protected bool $isAddable = true;

    protected bool $isDeletable = true;

    protected bool $isReorderable = true;

    protected bool $isCollapsible = false;

    protected bool $isCloneable = false;

    protected ?string $addActionLabel = null;

    protected ?string $itemLabel = null;

    protected ?Closure $itemLabelUsing = null;

    /**
     * Set the repeater schema (fields to repeat).
     */
    public function schema(array $schema): static
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get the schema.
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * Set minimum number of items.
     */
    public function minItems(int $count): static
    {
        $this->minItems = $count;

        return $this;
    }

    /**
     * Get minimum items.
     */
    public function getMinItems(): ?int
    {
        return $this->minItems;
    }

    /**
     * Set maximum number of items.
     */
    public function maxItems(int $count): static
    {
        $this->maxItems = $count;

        return $this;
    }

    /**
     * Get maximum items.
     */
    public function getMaxItems(): ?int
    {
        return $this->maxItems;
    }

    /**
     * Set default number of items.
     */
    public function defaultItems(int $count): static
    {
        $this->defaultItems = $count;

        return $this;
    }

    /**
     * Get default items.
     */
    public function getDefaultItems(): int
    {
        return $this->defaultItems;
    }

    /**
     * Allow adding new items.
     */
    public function addable(bool $condition = true): static
    {
        $this->isAddable = $condition;

        return $this;
    }

    /**
     * Check if addable.
     */
    public function isAddable(): bool
    {
        return $this->isAddable;
    }

    /**
     * Allow deleting items.
     */
    public function deletable(bool $condition = true): static
    {
        $this->isDeletable = $condition;

        return $this;
    }

    /**
     * Check if deletable.
     */
    public function isDeletable(): bool
    {
        return $this->isDeletable;
    }

    /**
     * Allow reordering items.
     */
    public function reorderable(bool $condition = true): static
    {
        $this->isReorderable = $condition;

        return $this;
    }

    /**
     * Check if reorderable.
     */
    public function isReorderable(): bool
    {
        return $this->isReorderable;
    }

    /**
     * Make items collapsible.
     */
    public function collapsible(bool $condition = true): static
    {
        $this->isCollapsible = $condition;

        return $this;
    }

    /**
     * Check if collapsible.
     */
    public function isCollapsible(): bool
    {
        return $this->isCollapsible;
    }

    /**
     * Allow cloning items.
     */
    public function cloneable(bool $condition = true): static
    {
        $this->isCloneable = $condition;

        return $this;
    }

    /**
     * Check if cloneable.
     */
    public function isCloneable(): bool
    {
        return $this->isCloneable;
    }

    /**
     * Set the add action label.
     */
    public function addActionLabel(string $label): static
    {
        $this->addActionLabel = $label;

        return $this;
    }

    /**
     * Get the add action label.
     */
    public function getAddActionLabel(): string
    {
        return $this->addActionLabel ?? __('Add item');
    }

    /**
     * Set a static item label.
     */
    public function itemLabel(string $label): static
    {
        $this->itemLabel = $label;

        return $this;
    }

    /**
     * Set a dynamic item label.
     */
    public function itemLabelUsing(Closure $callback): static
    {
        $this->itemLabelUsing = $callback;

        return $this;
    }

    /**
     * Get the item label for an index.
     */
    public function getItemLabel(int $index, array $state = []): string
    {
        if ($this->itemLabelUsing !== null) {
            return ($this->itemLabelUsing)($state, $index);
        }

        if ($this->itemLabel !== null) {
            return $this->itemLabel.' '.($index + 1);
        }

        return __('Item').' '.($index + 1);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.repeater';
    }
}

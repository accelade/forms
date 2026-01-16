<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Repeater field component for repeatable field groups.
 *
 * Filament-compatible API for managing repeatable form sections.
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

    protected bool $isCollapsed = false;

    protected bool $isCloneable = false;

    protected bool $isSimple = false;

    protected ?Field $simpleField = null;

    protected ?string $addActionLabel = null;

    protected ?string $deleteActionLabel = null;

    protected ?string $cloneActionLabel = null;

    protected ?string $reorderActionLabel = null;

    protected ?string $collapseActionLabel = null;

    protected ?string $expandActionLabel = null;

    protected ?string $collapseAllActionLabel = null;

    protected ?string $expandAllActionLabel = null;

    protected ?string $itemLabel = null;

    protected ?Closure $itemLabelUsing = null;

    protected int|array|null $grid = null;

    protected int|string|Closure|null $columns = null;

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
     * Collapse all items by default.
     */
    public function collapsed(bool $condition = true): static
    {
        $this->isCollapsed = $condition;

        if ($condition) {
            $this->isCollapsible = true;
        }

        return $this;
    }

    /**
     * Check if collapsed by default.
     */
    public function isCollapsed(): bool
    {
        return $this->isCollapsed;
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
     * Create a simple repeater with a single field.
     */
    public function simple(?Field $field): static
    {
        $this->isSimple = $field !== null;
        $this->simpleField = $field;

        if ($field !== null) {
            $this->schema([$field]);
        }

        return $this;
    }

    /**
     * Check if simple mode.
     */
    public function isSimple(): bool
    {
        return $this->isSimple;
    }

    /**
     * Get the simple field.
     */
    public function getSimpleField(): ?Field
    {
        return $this->simpleField;
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
     * Set the delete action label.
     */
    public function deleteActionLabel(string $label): static
    {
        $this->deleteActionLabel = $label;

        return $this;
    }

    /**
     * Get the delete action label.
     */
    public function getDeleteActionLabel(): string
    {
        return $this->deleteActionLabel ?? __('Delete');
    }

    /**
     * Set the clone action label.
     */
    public function cloneActionLabel(string $label): static
    {
        $this->cloneActionLabel = $label;

        return $this;
    }

    /**
     * Get the clone action label.
     */
    public function getCloneActionLabel(): string
    {
        return $this->cloneActionLabel ?? __('Clone');
    }

    /**
     * Set the reorder action label.
     */
    public function reorderActionLabel(string $label): static
    {
        $this->reorderActionLabel = $label;

        return $this;
    }

    /**
     * Get the reorder action label.
     */
    public function getReorderActionLabel(): string
    {
        return $this->reorderActionLabel ?? __('Drag to reorder');
    }

    /**
     * Set the collapse action label.
     */
    public function collapseActionLabel(string $label): static
    {
        $this->collapseActionLabel = $label;

        return $this;
    }

    /**
     * Get the collapse action label.
     */
    public function getCollapseActionLabel(): string
    {
        return $this->collapseActionLabel ?? __('Collapse');
    }

    /**
     * Set the expand action label.
     */
    public function expandActionLabel(string $label): static
    {
        $this->expandActionLabel = $label;

        return $this;
    }

    /**
     * Get the expand action label.
     */
    public function getExpandActionLabel(): string
    {
        return $this->expandActionLabel ?? __('Expand');
    }

    /**
     * Set the collapse all action label.
     */
    public function collapseAllActionLabel(string $label): static
    {
        $this->collapseAllActionLabel = $label;

        return $this;
    }

    /**
     * Get the collapse all action label.
     */
    public function getCollapseAllActionLabel(): string
    {
        return $this->collapseAllActionLabel ?? __('Collapse all');
    }

    /**
     * Set the expand all action label.
     */
    public function expandAllActionLabel(string $label): static
    {
        $this->expandAllActionLabel = $label;

        return $this;
    }

    /**
     * Get the expand all action label.
     */
    public function getExpandAllActionLabel(): string
    {
        return $this->expandAllActionLabel ?? __('Expand all');
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
     * Set the grid layout columns.
     */
    public function grid(int|array|null $columns): static
    {
        $this->grid = $columns;

        return $this;
    }

    /**
     * Get the grid columns.
     */
    public function getGrid(): int|array|null
    {
        return $this->grid;
    }

    /**
     * Set the number of columns for the schema inside each item.
     */
    public function columns(int|string|Closure|null $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get the columns for the schema.
     */
    public function getColumns(): int|string|null
    {
        return $this->evaluate($this->columns);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.repeater';
    }
}

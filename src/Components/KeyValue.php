<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;

/**
 * Key-value pairs field component.
 *
 * Filament-compatible API for managing key-value pair data.
 */
class KeyValue extends Field
{
    protected string $keyLabel = 'Key';

    protected string $valueLabel = 'Value';

    protected ?string $keyPlaceholder = null;

    protected ?string $valuePlaceholder = null;

    protected bool $isAddable = true;

    protected bool $isDeletable = true;

    protected bool $isReorderable = false;

    protected bool $editableKeys = true;

    protected bool $editableValues = true;

    protected ?string $addActionLabel = null;

    /**
     * Set the key column label.
     */
    public function keyLabel(string $label): static
    {
        $this->keyLabel = $label;

        return $this;
    }

    /**
     * Get the key label.
     */
    public function getKeyLabel(): string
    {
        return $this->keyLabel;
    }

    /**
     * Set the value column label.
     */
    public function valueLabel(string $label): static
    {
        $this->valueLabel = $label;

        return $this;
    }

    /**
     * Get the value label.
     */
    public function getValueLabel(): string
    {
        return $this->valueLabel;
    }

    /**
     * Set the key placeholder.
     */
    public function keyPlaceholder(string $placeholder): static
    {
        $this->keyPlaceholder = $placeholder;

        return $this;
    }

    /**
     * Get the key placeholder.
     */
    public function getKeyPlaceholder(): ?string
    {
        return $this->keyPlaceholder;
    }

    /**
     * Set the value placeholder.
     */
    public function valuePlaceholder(string $placeholder): static
    {
        $this->valuePlaceholder = $placeholder;

        return $this;
    }

    /**
     * Get the value placeholder.
     */
    public function getValuePlaceholder(): ?string
    {
        return $this->valuePlaceholder;
    }

    /**
     * Allow adding new rows.
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
     * Allow deleting rows.
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
     * Allow reordering rows.
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
     * Set whether keys are editable.
     */
    public function editableKeys(bool $condition = true): static
    {
        $this->editableKeys = $condition;

        return $this;
    }

    /**
     * Check if keys are editable.
     */
    public function hasEditableKeys(): bool
    {
        return $this->editableKeys;
    }

    /**
     * Set whether values are editable.
     */
    public function editableValues(bool $condition = true): static
    {
        $this->editableValues = $condition;

        return $this;
    }

    /**
     * Check if values are editable.
     */
    public function hasEditableValues(): bool
    {
        return $this->editableValues;
    }

    /**
     * Set custom label for add button.
     */
    public function addActionLabel(?string $label): static
    {
        $this->addActionLabel = $label;

        return $this;
    }

    /**
     * Get the add action label.
     */
    public function getAddActionLabel(): string
    {
        return $this->addActionLabel ?? __('Add Row');
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.key-value';
    }
}

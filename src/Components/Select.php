<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasOptions;
use Accelade\Forms\Field;
use Closure;

/**
 * Select dropdown field component.
 */
class Select extends Field
{
    use HasOptions;

    protected ?string $emptyOptionLabel = null;

    protected bool $isNative = true;

    protected ?Closure $getOptionLabelUsing = null;

    protected ?Closure $getOptionsUsing = null;

    /**
     * Set up the field.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->emptyOptionLabel = __('Select an option');
    }

    /**
     * Set the empty option label.
     */
    public function emptyOptionLabel(?string $label): static
    {
        $this->emptyOptionLabel = $label;

        return $this;
    }

    /**
     * Get the empty option label.
     */
    public function getEmptyOptionLabel(): ?string
    {
        return $this->emptyOptionLabel;
    }

    /**
     * Disable the empty option.
     */
    public function disableEmptyOption(): static
    {
        $this->emptyOptionLabel = null;

        return $this;
    }

    /**
     * Use native select element.
     */
    public function native(bool $condition = true): static
    {
        $this->isNative = $condition;

        return $this;
    }

    /**
     * Check if using native select.
     */
    public function isNative(): bool
    {
        return $this->isNative;
    }

    /**
     * Set a callback for getting the option label.
     */
    public function getOptionLabelUsing(Closure $callback): static
    {
        $this->getOptionLabelUsing = $callback;

        return $this;
    }

    /**
     * Get the option label for a value.
     */
    public function getOptionLabel(mixed $value): ?string
    {
        if ($this->getOptionLabelUsing !== null) {
            return ($this->getOptionLabelUsing)($value);
        }

        $options = $this->getOptions();

        return $options[$value] ?? null;
    }

    /**
     * Set a callback for dynamically loading options.
     */
    public function getOptionsUsing(Closure $callback): static
    {
        $this->getOptionsUsing = $callback;

        return $this;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.select';
    }
}

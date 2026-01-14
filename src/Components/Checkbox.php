<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\CanBeInline;
use Accelade\Forms\Field;

/**
 * Checkbox field component.
 */
class Checkbox extends Field
{
    use CanBeInline;

    protected mixed $checkedValue = true;

    protected mixed $uncheckedValue = false;

    /**
     * Set the value when checked.
     */
    public function checkedValue(mixed $value): static
    {
        $this->checkedValue = $value;

        return $this;
    }

    /**
     * Get the checked value.
     */
    public function getCheckedValue(): mixed
    {
        return $this->checkedValue;
    }

    /**
     * Set the value when unchecked.
     */
    public function uncheckedValue(mixed $value): static
    {
        $this->uncheckedValue = $value;

        return $this;
    }

    /**
     * Get the unchecked value.
     */
    public function getUncheckedValue(): mixed
    {
        return $this->uncheckedValue;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.checkbox';
    }
}

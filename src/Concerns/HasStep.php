<?php

declare(strict_types=1);

namespace Accelade\Forms\Concerns;

use Closure;

/**
 * Trait for numeric fields that support stepping.
 */
trait HasStep
{
    protected int|float|Closure|null $step = null;

    /**
     * Set the step value.
     */
    public function step(int|float|Closure $step): static
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get the step value.
     */
    public function getStep(): int|float|null
    {
        return $this->evaluate($this->step);
    }
}

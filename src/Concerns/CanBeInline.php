<?php

declare(strict_types=1);

namespace Accelade\Forms\Concerns;

/**
 * Trait for fields that can be displayed inline.
 */
trait CanBeInline
{
    protected bool $isInline = true;

    /**
     * Display the field inline.
     */
    public function inline(bool $condition = true): static
    {
        $this->isInline = $condition;

        return $this;
    }

    /**
     * Check if inline.
     */
    public function isInline(): bool
    {
        return $this->isInline;
    }
}

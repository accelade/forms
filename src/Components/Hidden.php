<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;

/**
 * Hidden input field component.
 */
class Hidden extends Field
{
    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.hidden';
    }

    /**
     * Hidden fields are never visually hidden (they are always HTML hidden).
     */
    public function isHidden(): bool
    {
        return false;
    }
}

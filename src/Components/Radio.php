<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\CanBeInline;
use Accelade\Forms\Concerns\HasOptions;
use Accelade\Forms\Field;
use Closure;

/**
 * Radio button field component.
 */
class Radio extends Field
{
    use CanBeInline;
    use HasOptions;

    protected array|Closure $descriptions = [];

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
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.radio';
    }
}

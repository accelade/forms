<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Tags input field component.
 */
class TagsInput extends Field
{
    protected array|Closure $suggestions = [];

    protected ?string $separator = ',';

    protected ?int $maxTags = null;

    protected ?int $minTags = null;

    protected bool $allowDuplicates = false;

    /**
     * Set tag suggestions for autocomplete.
     */
    public function suggestions(array|Closure $suggestions): static
    {
        $this->suggestions = $suggestions;

        return $this;
    }

    /**
     * Get the suggestions.
     */
    public function getSuggestions(): array
    {
        return $this->evaluate($this->suggestions);
    }

    /**
     * Set the tag separator.
     */
    public function separator(string $separator): static
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Get the separator.
     */
    public function getSeparator(): string
    {
        return $this->separator ?? ',';
    }

    /**
     * Set maximum number of tags.
     */
    public function maxTags(int $max): static
    {
        $this->maxTags = $max;

        return $this;
    }

    /**
     * Get the maximum tags.
     */
    public function getMaxTags(): ?int
    {
        return $this->maxTags;
    }

    /**
     * Set minimum number of tags.
     */
    public function minTags(int $min): static
    {
        $this->minTags = $min;

        return $this;
    }

    /**
     * Get the minimum tags.
     */
    public function getMinTags(): ?int
    {
        return $this->minTags;
    }

    /**
     * Allow duplicate tags.
     */
    public function allowDuplicates(bool $condition = true): static
    {
        $this->allowDuplicates = $condition;

        return $this;
    }

    /**
     * Check if duplicates are allowed.
     */
    public function allowsDuplicates(): bool
    {
        return $this->allowDuplicates;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.tags-input';
    }
}

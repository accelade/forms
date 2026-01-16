<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Tags input field component.
 *
 * Filament-compatible API with color support, reorderable tags, and prefix/suffix.
 */
class TagsInput extends Field
{
    protected array|Closure $suggestions = [];

    protected ?string $separator = ',';

    protected ?int $maxTags = null;

    protected ?int $minTags = null;

    protected bool $allowDuplicates = false;

    protected ?string $color = 'primary';

    protected bool $isReorderable = false;

    protected ?string $tagPrefix = null;

    protected ?string $tagSuffix = null;

    protected ?string $splitKeys = null;

    /**
     * Color presets mapping.
     *
     * @var array<string, string>
     */
    public const COLOR_PRESETS = [
        'danger' => 'danger',
        'gray' => 'gray',
        'info' => 'info',
        'primary' => 'primary',
        'success' => 'success',
        'warning' => 'warning',
    ];

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
     * Set the tag color.
     * Accepts color presets (danger, success, warning, info, primary, gray).
     */
    public function color(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the tag color.
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Check if color is a preset.
     */
    public function isColorPreset(): bool
    {
        return $this->color !== null && array_key_exists($this->color, self::COLOR_PRESETS);
    }

    /**
     * Enable drag-to-reorder functionality.
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
     * Set a prefix for tag display (visual only).
     */
    public function tagPrefix(?string $prefix): static
    {
        $this->tagPrefix = $prefix;

        return $this;
    }

    /**
     * Get the tag prefix.
     */
    public function getTagPrefix(): ?string
    {
        return $this->tagPrefix;
    }

    /**
     * Set a suffix for tag display (visual only).
     */
    public function tagSuffix(?string $suffix): static
    {
        $this->tagSuffix = $suffix;

        return $this;
    }

    /**
     * Get the tag suffix.
     */
    public function getTagSuffix(): ?string
    {
        return $this->tagSuffix;
    }

    /**
     * Set keys that will split tags (e.g., ',', 'Tab').
     */
    public function splitKeys(?string $keys): static
    {
        $this->splitKeys = $keys;

        return $this;
    }

    /**
     * Get split keys.
     */
    public function getSplitKeys(): ?string
    {
        return $this->splitKeys;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.tags-input';
    }
}

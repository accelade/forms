<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Data\EmojiData;
use Accelade\Forms\Field;
use Closure;

/**
 * Emoji Input Component
 *
 * A picker for selecting emojis with category support.
 */
class EmojiInput extends Field
{
    protected bool|Closure $searchable = true;

    protected int|Closure $gridColumns = 8;

    protected bool|Closure $showPreview = true;

    protected bool|Closure $multiple = false;

    /** @var array<int, string> */
    protected array $categories = ['smileys', 'people', 'animals', 'food', 'travel', 'activities', 'objects', 'symbols', 'flags'];

    /** @var array<string, array<string, string>>|Closure */
    protected array|Closure $customEmojis = [];

    /**
     * Set the emoji categories to display.
     *
     * @param  array<int, string>  $categories
     */
    public function categories(array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get the emoji categories.
     *
     * @return array<int, string>
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Set custom emojis.
     *
     * @param  array<string, array<string, string>>|Closure  $emojis
     */
    public function customEmojis(array|Closure $emojis): static
    {
        $this->customEmojis = $emojis;

        return $this;
    }

    /**
     * Get custom emojis.
     *
     * @return array<string, array<string, string>>
     */
    public function getCustomEmojis(): array
    {
        return $this->evaluate($this->customEmojis);
    }

    /**
     * Enable/disable search functionality.
     */
    public function searchable(bool|Closure $condition = true): static
    {
        $this->searchable = $condition;

        return $this;
    }

    /**
     * Get searchable state.
     */
    public function isSearchable(): bool
    {
        return $this->evaluate($this->searchable);
    }

    /**
     * Set the number of grid columns.
     */
    public function gridColumns(int|Closure $columns): static
    {
        $this->gridColumns = $columns;

        return $this;
    }

    /**
     * Get grid columns.
     */
    public function getGridColumns(): int
    {
        return $this->evaluate($this->gridColumns);
    }

    /**
     * Show/hide emoji preview.
     */
    public function showPreview(bool|Closure $condition = true): static
    {
        $this->showPreview = $condition;

        return $this;
    }

    /**
     * Get show preview state.
     */
    public function getShowPreview(): bool
    {
        return $this->evaluate($this->showPreview);
    }

    /**
     * Enable multiple emoji selection.
     */
    public function multiple(bool|Closure $condition = true): static
    {
        $this->multiple = $condition;

        return $this;
    }

    /**
     * Check if multiple selection is enabled.
     */
    public function isMultiple(): bool
    {
        return $this->evaluate($this->multiple);
    }

    /**
     * Get all emojis organized by category.
     *
     * @return array<string, array<string, string>>
     */
    public function getEmojis(): array
    {
        $customEmojis = $this->getCustomEmojis();

        if (! empty($customEmojis)) {
            return $customEmojis;
        }

        return $this->getDefaultEmojis();
    }

    /**
     * Get default emojis by category.
     *
     * @return array<string, array<string, string>>
     */
    protected function getDefaultEmojis(): array
    {
        return EmojiData::all($this->categories);
    }

    /**
     * Get the category labels.
     *
     * @return array<string, string>
     */
    public function getCategoryLabels(): array
    {
        return [
            'smileys' => 'Smileys & Emotion',
            'people' => 'People & Body',
            'animals' => 'Animals & Nature',
            'food' => 'Food & Drink',
            'travel' => 'Travel & Places',
            'activities' => 'Activities',
            'objects' => 'Objects',
            'symbols' => 'Symbols',
            'flags' => 'Flags',
        ];
    }

    /**
     * Get the category icons (using emojis as icons).
     *
     * @return array<string, string>
     */
    public function getCategoryIcons(): array
    {
        return [
            'smileys' => '😀',
            'people' => '👋',
            'animals' => '🐶',
            'food' => '🍎',
            'travel' => '🚗',
            'activities' => '⚽',
            'objects' => '💡',
            'symbols' => '❤️',
            'flags' => '🏁',
        ];
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.emoji-input';
    }
}

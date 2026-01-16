<?php

declare(strict_types=1);

namespace Accelade\Forms\Concerns;

/**
 * Provides prefix and suffix support for form fields.
 */
trait HasAffixes
{
    protected ?string $prefix = null;

    protected ?string $suffix = null;

    protected ?string $prefixIcon = null;

    protected ?string $suffixIcon = null;

    protected ?string $prefixIconColor = null;

    protected ?string $suffixIconColor = null;

    /**
     * Set the prefix text.
     */
    public function prefix(?string $text): static
    {
        $this->prefix = $text;

        return $this;
    }

    /**
     * Get the prefix text.
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * Check if has prefix.
     */
    public function hasPrefix(): bool
    {
        return $this->prefix !== null || $this->prefixIcon !== null;
    }

    /**
     * Set the suffix text.
     */
    public function suffix(?string $text): static
    {
        $this->suffix = $text;

        return $this;
    }

    /**
     * Get the suffix text.
     */
    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    /**
     * Check if has suffix.
     */
    public function hasSuffix(): bool
    {
        return $this->suffix !== null || $this->suffixIcon !== null;
    }

    /**
     * Set the prefix icon (Heroicon name).
     */
    public function prefixIcon(?string $icon): static
    {
        $this->prefixIcon = $icon;

        return $this;
    }

    /**
     * Get the prefix icon.
     */
    public function getPrefixIcon(): ?string
    {
        return $this->prefixIcon;
    }

    /**
     * Set the suffix icon (Heroicon name).
     */
    public function suffixIcon(?string $icon): static
    {
        $this->suffixIcon = $icon;

        return $this;
    }

    /**
     * Get the suffix icon.
     */
    public function getSuffixIcon(): ?string
    {
        return $this->suffixIcon;
    }

    /**
     * Set the prefix icon color.
     */
    public function prefixIconColor(?string $color): static
    {
        $this->prefixIconColor = $color;

        return $this;
    }

    /**
     * Get the prefix icon color.
     */
    public function getPrefixIconColor(): ?string
    {
        return $this->prefixIconColor;
    }

    /**
     * Set the suffix icon color.
     */
    public function suffixIconColor(?string $color): static
    {
        $this->suffixIconColor = $color;

        return $this;
    }

    /**
     * Get the suffix icon color.
     */
    public function getSuffixIconColor(): ?string
    {
        return $this->suffixIconColor;
    }
}

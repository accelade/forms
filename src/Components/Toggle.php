<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\CanBeInline;
use Accelade\Forms\Field;

/**
 * Toggle switch field component.
 */
class Toggle extends Field
{
    use CanBeInline;

    protected mixed $onValue = true;

    protected mixed $offValue = false;

    protected ?string $onColor = 'primary';

    protected ?string $offColor = 'gray';

    protected ?string $onIcon = null;

    protected ?string $offIcon = null;

    /**
     * Set the value when toggle is on.
     */
    public function onValue(mixed $value): static
    {
        $this->onValue = $value;

        return $this;
    }

    /**
     * Get the on value.
     */
    public function getOnValue(): mixed
    {
        return $this->onValue;
    }

    /**
     * Set the value when toggle is off.
     */
    public function offValue(mixed $value): static
    {
        $this->offValue = $value;

        return $this;
    }

    /**
     * Get the off value.
     */
    public function getOffValue(): mixed
    {
        return $this->offValue;
    }

    /**
     * Set the color when toggle is on.
     */
    public function onColor(string $color): static
    {
        $this->onColor = $color;

        return $this;
    }

    /**
     * Get the on color.
     */
    public function getOnColor(): ?string
    {
        return $this->onColor;
    }

    /**
     * Set the color when toggle is off.
     */
    public function offColor(string $color): static
    {
        $this->offColor = $color;

        return $this;
    }

    /**
     * Get the off color.
     */
    public function getOffColor(): ?string
    {
        return $this->offColor;
    }

    /**
     * Set the icon when toggle is on.
     */
    public function onIcon(string $icon): static
    {
        $this->onIcon = $icon;

        return $this;
    }

    /**
     * Get the on icon.
     */
    public function getOnIcon(): ?string
    {
        return $this->onIcon;
    }

    /**
     * Set the icon when toggle is off.
     */
    public function offIcon(string $icon): static
    {
        $this->offIcon = $icon;

        return $this;
    }

    /**
     * Get the off icon.
     */
    public function getOffIcon(): ?string
    {
        return $this->offIcon;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.toggle';
    }
}

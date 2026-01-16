<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Closure;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Submit button component (Splade compatibility).
 *
 * Provides a submit button with loading spinner, danger/secondary variants,
 * and customizable label.
 */
class Submit implements Htmlable
{
    protected string $label = 'Submit';

    protected bool $hasSpinner = true;

    protected bool $isDanger = false;

    protected bool $isSecondary = false;

    protected bool $isDisabled = false;

    protected ?string $type = 'submit';

    protected array $extraAttributes = [];

    /**
     * Create a new submit button instance.
     */
    public static function make(?string $label = null): static
    {
        $static = new static;

        if ($label !== null) {
            $static->label = $label;
        }

        return $static;
    }

    /**
     * Set the button label.
     */
    public function label(string|Closure $label): static
    {
        $this->label = $label instanceof Closure ? $label() : $label;

        return $this;
    }

    /**
     * Get the button label.
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Enable or disable the loading spinner.
     */
    public function spinner(bool $condition = true): static
    {
        $this->hasSpinner = $condition;

        return $this;
    }

    /**
     * Check if spinner is enabled.
     */
    public function hasSpinner(): bool
    {
        return $this->hasSpinner;
    }

    /**
     * Apply danger styling.
     */
    public function danger(bool $condition = true): static
    {
        $this->isDanger = $condition;

        if ($condition) {
            $this->isSecondary = false;
        }

        return $this;
    }

    /**
     * Check if danger style.
     */
    public function isDanger(): bool
    {
        return $this->isDanger;
    }

    /**
     * Apply secondary styling.
     */
    public function secondary(bool $condition = true): static
    {
        $this->isSecondary = $condition;

        if ($condition) {
            $this->isDanger = false;
        }

        return $this;
    }

    /**
     * Check if secondary style.
     */
    public function isSecondary(): bool
    {
        return $this->isSecondary;
    }

    /**
     * Check if primary style (not danger or secondary).
     */
    public function isPrimary(): bool
    {
        return ! $this->isDanger && ! $this->isSecondary;
    }

    /**
     * Disable the button.
     */
    public function disabled(bool $condition = true): static
    {
        $this->isDisabled = $condition;

        return $this;
    }

    /**
     * Check if disabled.
     */
    public function isDisabled(): bool
    {
        return $this->isDisabled;
    }

    /**
     * Set the button type.
     */
    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the button type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Add extra HTML attributes.
     */
    public function extraAttributes(array $attributes): static
    {
        $this->extraAttributes = array_merge($this->extraAttributes, $attributes);

        return $this;
    }

    /**
     * Get extra attributes.
     */
    public function getExtraAttributes(): array
    {
        return $this->extraAttributes;
    }

    /**
     * Get extra attributes as HTML string.
     */
    public function getExtraAttributesString(): string
    {
        $html = [];

        foreach ($this->extraAttributes as $key => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html[] = e($key);
                }
            } else {
                $html[] = e($key).'="'.e($value).'"';
            }
        }

        return implode(' ', $html);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.submit';
    }

    /**
     * Get the view data for rendering.
     */
    protected function getViewData(): array
    {
        return [
            'submit' => $this,
        ];
    }

    /**
     * Render the button to HTML.
     */
    public function render(): string
    {
        return view($this->getView(), $this->getViewData())->render();
    }

    /**
     * Get content as a string of HTML.
     */
    public function toHtml(): string
    {
        return $this->render();
    }

    /**
     * Convert to string.
     */
    public function __toString(): string
    {
        return $this->render();
    }
}

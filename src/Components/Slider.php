<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasMinMax;
use Accelade\Forms\Concerns\HasStep;
use Accelade\Forms\Field;
use Closure;

/**
 * Slider Component
 *
 * A slider input for selecting numeric values within a range.
 */
class Slider extends Field
{
    use HasMinMax;
    use HasStep;

    /** @var array<int|float, string>|Closure */
    protected array|Closure $marks = [];

    protected bool|Closure $showValue = true;

    protected bool|Closure $range = false;

    protected string|Closure|null $color = null;

    /**
     * Set marks on the slider.
     *
     * @param  array<int|float, string>|Closure  $marks
     */
    public function marks(array|Closure $marks): static
    {
        $this->marks = $marks;

        return $this;
    }

    /**
     * Get the marks array.
     *
     * @return array<int|float, string>
     */
    public function getMarks(): array
    {
        return $this->evaluate($this->marks);
    }

    /**
     * Show/hide the current value.
     */
    public function showValue(bool|Closure $condition = true): static
    {
        $this->showValue = $condition;

        return $this;
    }

    /**
     * Get show value state.
     */
    public function getShowValue(): bool
    {
        return $this->evaluate($this->showValue);
    }

    /**
     * Enable range mode (two handles).
     */
    public function range(bool|Closure $range = true): static
    {
        $this->range = $range;

        return $this;
    }

    /**
     * Check if range mode is enabled.
     */
    public function isRange(): bool
    {
        return $this->evaluate($this->range);
    }

    /**
     * Set the slider color.
     */
    public function color(string|Closure $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the color.
     */
    public function getSliderColor(): ?string
    {
        return $this->evaluate($this->color);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.slider';
    }
}

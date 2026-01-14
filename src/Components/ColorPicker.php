<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;

/**
 * Color picker field component.
 */
class ColorPicker extends Field
{
    protected ?string $format = 'hex';

    protected array $swatches = [];

    /**
     * Set the color format.
     */
    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get the color format.
     */
    public function getFormat(): string
    {
        return $this->format ?? 'hex';
    }

    /**
     * Set as hex format.
     */
    public function hex(): static
    {
        $this->format = 'hex';

        return $this;
    }

    /**
     * Set as rgb format.
     */
    public function rgb(): static
    {
        $this->format = 'rgb';

        return $this;
    }

    /**
     * Set as rgba format.
     */
    public function rgba(): static
    {
        $this->format = 'rgba';

        return $this;
    }

    /**
     * Set as hsl format.
     */
    public function hsl(): static
    {
        $this->format = 'hsl';

        return $this;
    }

    /**
     * Set preset color swatches.
     */
    public function swatches(array $swatches): static
    {
        $this->swatches = $swatches;

        return $this;
    }

    /**
     * Get the swatches.
     */
    public function getSwatches(): array
    {
        return $this->swatches;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.color-picker';
    }
}

<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasMinMax;
use Accelade\Forms\Concerns\HasStep;
use Accelade\Forms\Field;
use Closure;

/**
 * Text input field component.
 */
class TextInput extends Field
{
    use HasMinMax;
    use HasStep;

    protected string $type = 'text';

    protected ?string $inputMode = null;

    protected ?string $autocomplete = null;

    protected ?string $datalist = null;

    protected array $datalistOptions = [];

    protected ?string $mask = null;

    /**
     * Set the input type.
     */
    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the input type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set as email input.
     */
    public function email(): static
    {
        $this->type = 'email';
        $this->inputMode = 'email';

        return $this;
    }

    /**
     * Set as password input.
     */
    public function password(): static
    {
        $this->type = 'password';

        return $this;
    }

    /**
     * Set as numeric input.
     */
    public function numeric(): static
    {
        $this->type = 'number';
        $this->inputMode = 'numeric';

        return $this;
    }

    /**
     * Set as integer input.
     */
    public function integer(): static
    {
        $this->type = 'number';
        $this->inputMode = 'numeric';
        $this->step = 1;

        return $this;
    }

    /**
     * Set as telephone input.
     */
    public function tel(): static
    {
        $this->type = 'tel';
        $this->inputMode = 'tel';

        return $this;
    }

    /**
     * Set as URL input.
     */
    public function url(): static
    {
        $this->type = 'url';
        $this->inputMode = 'url';

        return $this;
    }

    /**
     * Set the input mode.
     */
    public function inputMode(string $mode): static
    {
        $this->inputMode = $mode;

        return $this;
    }

    /**
     * Get the input mode.
     */
    public function getInputMode(): ?string
    {
        return $this->inputMode;
    }

    /**
     * Set the autocomplete attribute.
     */
    public function autocomplete(string $autocomplete): static
    {
        $this->autocomplete = $autocomplete;

        return $this;
    }

    /**
     * Get the autocomplete attribute.
     */
    public function getAutocomplete(): ?string
    {
        return $this->autocomplete;
    }

    /**
     * Set datalist options for autocomplete suggestions.
     */
    public function datalist(array|Closure $options): static
    {
        $this->datalistOptions = is_array($options) ? $options : $this->evaluate($options);
        $this->datalist = $this->getId().'-datalist';

        return $this;
    }

    /**
     * Get the datalist ID.
     */
    public function getDatalist(): ?string
    {
        return $this->datalist;
    }

    /**
     * Get the datalist options.
     */
    public function getDatalistOptions(): array
    {
        return $this->datalistOptions;
    }

    /**
     * Set an input mask pattern.
     */
    public function mask(string $mask): static
    {
        $this->mask = $mask;

        return $this;
    }

    /**
     * Get the input mask.
     */
    public function getMask(): ?string
    {
        return $this->mask;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.text-input';
    }
}

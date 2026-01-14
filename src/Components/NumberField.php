<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasMinMax;
use Accelade\Forms\Concerns\HasStep;
use Accelade\Forms\Field;
use Closure;

/**
 * Number Field Component
 *
 * A number input field with stepper buttons for increment/decrement.
 */
class NumberField extends Field
{
    use HasMinMax;
    use HasStep;

    /** @var array<string, mixed>|Closure */
    protected array|Closure $formatOptions = [];

    protected string|Closure|null $locale = null;

    protected string|Closure|null $prefix = null;

    protected string|Closure|null $suffix = null;

    /**
     * Set format options for number formatting.
     *
     * @param  array<string, mixed>|Closure  $options
     */
    public function formatOptions(array|Closure $options): static
    {
        $this->formatOptions = $options;

        return $this;
    }

    /**
     * Get format options.
     *
     * @return array<string, mixed>
     */
    public function getFormatOptions(): array
    {
        return $this->evaluate($this->formatOptions);
    }

    /**
     * Format as currency.
     */
    public function currency(string $currency = 'USD'): static
    {
        $this->formatOptions = [
            'style' => 'currency',
            'currency' => $currency,
        ];

        return $this;
    }

    /**
     * Format as percentage.
     */
    public function percentage(): static
    {
        $this->formatOptions = [
            'style' => 'percent',
        ];

        if ($this->step === 1) {
            $this->step = 0.01;
        }

        return $this;
    }

    /**
     * Set locale for formatting.
     */
    public function locale(string|Closure $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get number format locale.
     */
    public function getNumberLocale(): ?string
    {
        return $this->evaluate($this->locale);
    }

    /**
     * Set prefix text.
     */
    public function prefix(string|Closure $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix text.
     */
    public function getPrefix(): ?string
    {
        return $this->evaluate($this->prefix);
    }

    /**
     * Set suffix text.
     */
    public function suffix(string|Closure $suffix): static
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Get suffix text.
     */
    public function getSuffix(): ?string
    {
        return $this->evaluate($this->suffix);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.number-field';
    }
}

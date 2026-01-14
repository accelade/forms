<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Time picker field component.
 */
class TimePicker extends Field
{
    protected ?string $format = null;

    protected ?string $minTime = null;

    protected ?string $maxTime = null;

    protected bool $withSeconds = false;

    protected bool $isNative = true;

    /**
     * Set up the field.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->format = config('forms.datetime.time_format', 'H:i');
    }

    /**
     * Set the time format.
     */
    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get the time format.
     */
    public function getFormat(): string
    {
        return $this->format ?? 'H:i';
    }

    /**
     * Set the minimum time.
     */
    public function minTime(string|Closure $time): static
    {
        $this->minTime = $this->evaluate($time);

        return $this;
    }

    /**
     * Get the minimum time.
     */
    public function getMinTime(): ?string
    {
        return $this->minTime;
    }

    /**
     * Set the maximum time.
     */
    public function maxTime(string|Closure $time): static
    {
        $this->maxTime = $this->evaluate($time);

        return $this;
    }

    /**
     * Get the maximum time.
     */
    public function getMaxTime(): ?string
    {
        return $this->maxTime;
    }

    /**
     * Include seconds in the time picker.
     */
    public function withSeconds(bool $condition = true): static
    {
        $this->withSeconds = $condition;

        return $this;
    }

    /**
     * Check if seconds are included.
     */
    public function hasSeconds(): bool
    {
        return $this->withSeconds;
    }

    /**
     * Use native time input.
     */
    public function native(bool $condition = true): static
    {
        $this->isNative = $condition;

        return $this;
    }

    /**
     * Check if using native input.
     */
    public function isNative(): bool
    {
        return $this->isNative;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.time-picker';
    }
}

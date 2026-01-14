<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Carbon\Carbon;
use Closure;

/**
 * DateTime picker field component.
 */
class DateTimePicker extends Field
{
    protected ?string $format = null;

    protected ?string $displayFormat = null;

    protected Carbon|string|Closure|null $minDate = null;

    protected Carbon|string|Closure|null $maxDate = null;

    protected bool $withSeconds = false;

    protected bool $isNative = true;

    protected int $firstDayOfWeek = 1;

    /**
     * Set up the field.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->format = config('forms.datetime.datetime_format', 'Y-m-d H:i');
    }

    /**
     * Set the datetime format.
     */
    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get the datetime format.
     */
    public function getFormat(): string
    {
        return $this->format ?? 'Y-m-d H:i';
    }

    /**
     * Set the display format.
     */
    public function displayFormat(string $format): static
    {
        $this->displayFormat = $format;

        return $this;
    }

    /**
     * Get the display format.
     */
    public function getDisplayFormat(): string
    {
        return $this->displayFormat ?? $this->getFormat();
    }

    /**
     * Set the minimum datetime.
     */
    public function minDate(Carbon|string|Closure $date): static
    {
        $this->minDate = $date;

        return $this;
    }

    /**
     * Get the minimum datetime.
     */
    public function getMinDate(): ?string
    {
        $date = $this->evaluate($this->minDate);

        if ($date instanceof Carbon) {
            return $date->format('Y-m-d\TH:i');
        }

        return $date;
    }

    /**
     * Set the maximum datetime.
     */
    public function maxDate(Carbon|string|Closure $date): static
    {
        $this->maxDate = $date;

        return $this;
    }

    /**
     * Get the maximum datetime.
     */
    public function getMaxDate(): ?string
    {
        $date = $this->evaluate($this->maxDate);

        if ($date instanceof Carbon) {
            return $date->format('Y-m-d\TH:i');
        }

        return $date;
    }

    /**
     * Include seconds.
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
     * Use native datetime input.
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
        return 'forms::components.datetime-picker';
    }
}

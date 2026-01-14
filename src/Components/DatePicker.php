<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Carbon\Carbon;
use Closure;

/**
 * Date picker field component.
 */
class DatePicker extends Field
{
    protected ?string $format = null;

    protected ?string $displayFormat = null;

    protected Carbon|string|Closure|null $minDate = null;

    protected Carbon|string|Closure|null $maxDate = null;

    protected array|Closure $disabledDates = [];

    protected bool $isNative = true;

    protected int $firstDayOfWeek = 1;

    /**
     * Set up the field.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->format = config('forms.datetime.date_format', 'Y-m-d');
    }

    /**
     * Set the date format for storing.
     */
    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get the date format.
     */
    public function getFormat(): string
    {
        return $this->format ?? 'Y-m-d';
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
     * Set the minimum selectable date.
     */
    public function minDate(Carbon|string|Closure $date): static
    {
        $this->minDate = $date;

        return $this;
    }

    /**
     * Get the minimum date.
     */
    public function getMinDate(): ?string
    {
        $date = $this->evaluate($this->minDate);

        if ($date instanceof Carbon) {
            return $date->format('Y-m-d');
        }

        return $date;
    }

    /**
     * Set the maximum selectable date.
     */
    public function maxDate(Carbon|string|Closure $date): static
    {
        $this->maxDate = $date;

        return $this;
    }

    /**
     * Get the maximum date.
     */
    public function getMaxDate(): ?string
    {
        $date = $this->evaluate($this->maxDate);

        if ($date instanceof Carbon) {
            return $date->format('Y-m-d');
        }

        return $date;
    }

    /**
     * Set disabled dates.
     */
    public function disabledDates(array|Closure $dates): static
    {
        $this->disabledDates = $dates;

        return $this;
    }

    /**
     * Get disabled dates.
     */
    public function getDisabledDates(): array
    {
        return $this->evaluate($this->disabledDates);
    }

    /**
     * Use native date input.
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
     * Set the first day of the week.
     */
    public function firstDayOfWeek(int $day): static
    {
        $this->firstDayOfWeek = $day;

        return $this;
    }

    /**
     * Get the first day of the week.
     */
    public function getFirstDayOfWeek(): int
    {
        return $this->firstDayOfWeek;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.date-picker';
    }
}

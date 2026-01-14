<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Date Range Picker Component
 *
 * A date range picker that allows selecting start and end dates.
 */
class DateRangePicker extends Field
{
    protected string|Closure|null $minDate = null;

    protected string|Closure|null $maxDate = null;

    protected string|Closure $locale = 'en';

    protected int|Closure $numberOfMonths = 2;

    protected bool|Closure $closeOnSelect = false;

    protected string|Closure|null $startDatePlaceholder = null;

    protected string|Closure|null $endDatePlaceholder = null;

    /**
     * Set minimum selectable date.
     */
    public function minDate(string|Closure $minDate): static
    {
        $this->minDate = $minDate;

        return $this;
    }

    /**
     * Get minimum date.
     */
    public function getMinDate(): ?string
    {
        return $this->evaluate($this->minDate);
    }

    /**
     * Set maximum selectable date.
     */
    public function maxDate(string|Closure $maxDate): static
    {
        $this->maxDate = $maxDate;

        return $this;
    }

    /**
     * Get maximum date.
     */
    public function getMaxDate(): ?string
    {
        return $this->evaluate($this->maxDate);
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
     * Get locale.
     */
    public function getRangeLocale(): string
    {
        return $this->evaluate($this->locale);
    }

    /**
     * Set number of months to display.
     */
    public function numberOfMonths(int|Closure $numberOfMonths): static
    {
        $this->numberOfMonths = $numberOfMonths;

        return $this;
    }

    /**
     * Get number of months.
     */
    public function getNumberOfMonths(): int
    {
        return $this->evaluate($this->numberOfMonths);
    }

    /**
     * Close picker on selection.
     */
    public function closeOnSelect(bool|Closure $closeOnSelect = true): static
    {
        $this->closeOnSelect = $closeOnSelect;

        return $this;
    }

    /**
     * Get close on select state.
     */
    public function getCloseOnSelect(): bool
    {
        return $this->evaluate($this->closeOnSelect);
    }

    /**
     * Set start date placeholder.
     */
    public function startDatePlaceholder(string|Closure $placeholder): static
    {
        $this->startDatePlaceholder = $placeholder;

        return $this;
    }

    /**
     * Get start date placeholder.
     */
    public function getStartDatePlaceholder(): ?string
    {
        return $this->evaluate($this->startDatePlaceholder);
    }

    /**
     * Set end date placeholder.
     */
    public function endDatePlaceholder(string|Closure $placeholder): static
    {
        $this->endDatePlaceholder = $placeholder;

        return $this;
    }

    /**
     * Get end date placeholder.
     */
    public function getEndDatePlaceholder(): ?string
    {
        return $this->evaluate($this->endDatePlaceholder);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.date-range-picker';
    }
}

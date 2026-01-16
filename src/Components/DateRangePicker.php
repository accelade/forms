<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Carbon\Carbon;
use Closure;

/**
 * Date Range Picker Component with Flatpickr support.
 *
 * A date range picker that allows selecting start and end dates.
 */
class DateRangePicker extends Field
{
    protected ?string $format = null;

    protected ?string $displayFormat = null;

    protected Carbon|string|Closure|null $minDate = null;

    protected Carbon|string|Closure|null $maxDate = null;

    protected string|Closure $locale = 'en';

    protected int|Closure $numberOfMonths = 2;

    protected bool|Closure $closeOnSelect = false;

    protected string|Closure|null $startDatePlaceholder = null;

    protected string|Closure|null $endDatePlaceholder = null;

    protected bool $isNative = false;

    protected int $firstDayOfWeek = 1;

    protected bool $weekNumbers = false;

    protected bool $inline = false;

    protected string $separator = ' to ';

    protected array $flatpickrOptions = [];

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
     * Set the display format (altFormat in Flatpickr).
     */
    public function displayFormat(string $format): static
    {
        $this->displayFormat = $format;

        return $this;
    }

    /**
     * Get the display format.
     */
    public function getDisplayFormat(): ?string
    {
        return $this->displayFormat;
    }

    /**
     * Set minimum selectable date.
     */
    public function minDate(Carbon|string|Closure $minDate): static
    {
        $this->minDate = $minDate;

        return $this;
    }

    /**
     * Get minimum date.
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
     * Set maximum selectable date.
     */
    public function maxDate(Carbon|string|Closure $maxDate): static
    {
        $this->maxDate = $maxDate;

        return $this;
    }

    /**
     * Get maximum date.
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
     * Use native date input (disables Flatpickr).
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
     * Set the first day of the week (0 = Sunday, 1 = Monday).
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
     * Show week numbers.
     */
    public function weekNumbers(bool $show = true): static
    {
        $this->weekNumbers = $show;

        return $this;
    }

    /**
     * Check if week numbers are shown.
     */
    public function hasWeekNumbers(): bool
    {
        return $this->weekNumbers;
    }

    /**
     * Display inline calendar.
     */
    public function inline(bool $inline = true): static
    {
        $this->inline = $inline;

        return $this;
    }

    /**
     * Check if inline mode is enabled.
     */
    public function isInline(): bool
    {
        return $this->inline;
    }

    /**
     * Set the range separator string.
     */
    public function separator(string $separator): static
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Get the range separator.
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * Set additional Flatpickr options.
     */
    public function flatpickr(array $options): static
    {
        $this->flatpickrOptions = array_merge($this->flatpickrOptions, $options);

        return $this;
    }

    /**
     * Get all Flatpickr options as array.
     */
    public function getFlatpickrOptions(): array
    {
        $options = [
            'mode' => 'range',
            'enableTime' => false,
            'dateFormat' => $this->format,
            'inline' => $this->inline,
            'weekNumbers' => $this->weekNumbers,
            'showMonths' => $this->getNumberOfMonths(),
            'locale' => [
                'firstDayOfWeek' => $this->firstDayOfWeek,
                'rangeSeparator' => $this->separator,
            ],
        ];

        if ($this->displayFormat) {
            $options['altInput'] = true;
            $options['altFormat'] = $this->displayFormat;
        }

        if ($this->getMinDate()) {
            $options['minDate'] = $this->getMinDate();
        }

        if ($this->getMaxDate()) {
            $options['maxDate'] = $this->getMaxDate();
        }

        if ($this->getCloseOnSelect()) {
            $options['closeOnSelect'] = true;
        }

        return array_merge($options, $this->flatpickrOptions);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.date-range-picker';
    }
}

<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Carbon\Carbon;
use Closure;

/**
 * Date picker field component with Flatpickr support.
 */
class DatePicker extends Field
{
    protected ?string $format = null;

    protected ?string $displayFormat = null;

    protected Carbon|string|Closure|null $minDate = null;

    protected Carbon|string|Closure|null $maxDate = null;

    protected array|Closure $disabledDates = [];

    protected bool $isNative = false;

    protected int $firstDayOfWeek = 1;

    protected bool $weekNumbers = false;

    protected bool $inline = false;

    protected bool $enableRange = false;

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
     * Enable date range selection.
     */
    public function range(bool $range = true): static
    {
        $this->enableRange = $range;

        return $this;
    }

    /**
     * Check if range mode is enabled.
     */
    public function isRange(): bool
    {
        return $this->enableRange;
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
            'enableTime' => false,
            'dateFormat' => $this->format,
            'inline' => $this->inline,
            'weekNumbers' => $this->weekNumbers,
            'locale' => [
                'firstDayOfWeek' => $this->firstDayOfWeek,
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

        $disabledDates = $this->getDisabledDates();
        if (! empty($disabledDates)) {
            $options['disable'] = $disabledDates;
        }

        if ($this->enableRange) {
            $options['mode'] = 'range';
        }

        return array_merge($options, $this->flatpickrOptions);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.date-picker';
    }
}

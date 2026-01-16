<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Carbon\Carbon;
use Closure;

/**
 * DateTime picker field component with Flatpickr support.
 */
class DateTimePicker extends Field
{
    protected ?string $format = null;

    protected ?string $displayFormat = null;

    protected Carbon|string|Closure|null $minDate = null;

    protected Carbon|string|Closure|null $maxDate = null;

    protected ?string $minTime = null;

    protected ?string $maxTime = null;

    protected bool $withSeconds = false;

    protected bool $isNative = false;

    protected bool $enableTime = true;

    protected bool $enableDate = true;

    protected bool $time24hr = true;

    protected bool $enableRange = false;

    protected bool $inline = false;

    protected bool $weekNumbers = false;

    protected ?string $mode = null;

    protected ?int $minuteIncrement = null;

    protected ?int $hourIncrement = null;

    protected array $disable = [];

    protected array $enable = [];

    protected int $firstDayOfWeek = 1;

    protected array $flatpickrOptions = [];

    /**
     * Set up the field.
     */
    protected function setUp(): void
    {
        $this->format = config('forms.datetime.datetime_format', 'Y-m-d H:i');
    }

    /**
     * Set the datetime format (for Flatpickr dateFormat).
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
     * Set minimum selectable time.
     */
    public function minTime(string $time): static
    {
        $this->minTime = $time;

        return $this;
    }

    /**
     * Get minimum time.
     */
    public function getMinTime(): ?string
    {
        return $this->minTime;
    }

    /**
     * Set maximum selectable time.
     */
    public function maxTime(string $time): static
    {
        $this->maxTime = $time;

        return $this;
    }

    /**
     * Get maximum time.
     */
    public function getMaxTime(): ?string
    {
        return $this->maxTime;
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
     * Use native datetime input (disables Flatpickr).
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
     * Enable time selection.
     */
    public function time(bool $enable = true): static
    {
        $this->enableTime = $enable;

        return $this;
    }

    /**
     * Check if time is enabled.
     */
    public function hasTime(): bool
    {
        return $this->enableTime;
    }

    /**
     * Enable date selection.
     */
    public function date(bool $enable = true): static
    {
        $this->enableDate = $enable;

        return $this;
    }

    /**
     * Check if date is enabled.
     */
    public function hasDate(): bool
    {
        return $this->enableDate;
    }

    /**
     * Configure as time-only picker.
     */
    public function timeOnly(): static
    {
        $this->enableDate = false;
        $this->enableTime = true;
        $this->format = config('forms.datetime.time_format', 'H:i');

        return $this;
    }

    /**
     * Configure as date-only picker.
     */
    public function dateOnly(): static
    {
        $this->enableDate = true;
        $this->enableTime = false;
        $this->format = config('forms.datetime.date_format', 'Y-m-d');

        return $this;
    }

    /**
     * Use 24-hour time format.
     */
    public function time24hr(bool $use24hr = true): static
    {
        $this->time24hr = $use24hr;

        return $this;
    }

    /**
     * Check if 24hr mode is enabled.
     */
    public function isTime24hr(): bool
    {
        return $this->time24hr;
    }

    /**
     * Enable date range selection.
     */
    public function range(bool $range = true): static
    {
        $this->enableRange = $range;
        $this->mode = $range ? 'range' : 'single';

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
     * Enable multiple date selection.
     */
    public function multiple(bool $multiple = true): static
    {
        $this->mode = $multiple ? 'multiple' : 'single';

        return $this;
    }

    /**
     * Set selection mode (single, multiple, range).
     */
    public function mode(string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get selection mode.
     */
    public function getMode(): ?string
    {
        return $this->mode;
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
     * Set minute increment.
     */
    public function minuteIncrement(int $increment): static
    {
        $this->minuteIncrement = $increment;

        return $this;
    }

    /**
     * Get minute increment.
     */
    public function getMinuteIncrement(): ?int
    {
        return $this->minuteIncrement;
    }

    /**
     * Set hour increment.
     */
    public function hourIncrement(int $increment): static
    {
        $this->hourIncrement = $increment;

        return $this;
    }

    /**
     * Get hour increment.
     */
    public function getHourIncrement(): ?int
    {
        return $this->hourIncrement;
    }

    /**
     * Disable specific dates.
     */
    public function disableDates(array $dates): static
    {
        $this->disable = $dates;

        return $this;
    }

    /**
     * Get disabled dates.
     */
    public function getDisabledDates(): array
    {
        return $this->disable;
    }

    /**
     * Enable only specific dates.
     */
    public function enableDates(array $dates): static
    {
        $this->enable = $dates;

        return $this;
    }

    /**
     * Get enabled dates.
     */
    public function getEnabledDates(): array
    {
        return $this->enable;
    }

    /**
     * Set first day of week (0 = Sunday, 1 = Monday).
     */
    public function firstDayOfWeek(int $day): static
    {
        $this->firstDayOfWeek = $day;

        return $this;
    }

    /**
     * Get first day of week.
     */
    public function getFirstDayOfWeek(): int
    {
        return $this->firstDayOfWeek;
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
     * Get all Flatpickr options as JSON.
     */
    public function getFlatpickrOptions(): array
    {
        $options = [
            'enableTime' => $this->enableTime,
            'noCalendar' => ! $this->enableDate,
            'enableSeconds' => $this->withSeconds,
            'time_24hr' => $this->time24hr,
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

        if ($this->minTime) {
            $options['minTime'] = $this->minTime;
        }

        if ($this->maxTime) {
            $options['maxTime'] = $this->maxTime;
        }

        if ($this->minuteIncrement) {
            $options['minuteIncrement'] = $this->minuteIncrement;
        }

        if ($this->hourIncrement) {
            $options['hourIncrement'] = $this->hourIncrement;
        }

        if (! empty($this->disable)) {
            $options['disable'] = $this->disable;
        }

        if (! empty($this->enable)) {
            $options['enable'] = $this->enable;
        }

        if ($this->mode) {
            $options['mode'] = $this->mode;
        }

        return array_merge($options, $this->flatpickrOptions);
    }

    /**
     * Get the native input type.
     */
    public function getNativeType(): string
    {
        if ($this->enableTime && $this->enableDate) {
            return 'datetime-local';
        }

        if ($this->enableTime && ! $this->enableDate) {
            return 'time';
        }

        return 'date';
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.datetime-picker';
    }
}

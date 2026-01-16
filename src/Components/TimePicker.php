<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Time picker field component with Flatpickr support.
 */
class TimePicker extends Field
{
    protected ?string $format = null;

    protected ?string $displayFormat = null;

    protected ?string $minTime = null;

    protected ?string $maxTime = null;

    protected bool $withSeconds = false;

    protected bool $isNative = false;

    protected bool $time24hr = true;

    protected ?int $minuteIncrement = null;

    protected ?int $hourIncrement = null;

    protected bool $inline = false;

    protected array $flatpickrOptions = [];

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
     * Use native time input (disables Flatpickr).
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
     * Display inline picker.
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
     * Set additional Flatpickr options.
     */
    public function flatpickr(array $options): static
    {
        $this->flatpickrOptions = array_merge($this->flatpickrOptions, $options);

        return $this;
    }

    /**
     * Get the computed format based on time24hr and withSeconds settings.
     */
    protected function getComputedFormat(): string
    {
        // If user set a custom format, use it
        if ($this->format !== null && $this->format !== 'H:i') {
            return $this->format;
        }

        // Build format based on settings
        // H = 24-hour, h = 12-hour, i = minutes, S = seconds, K = AM/PM
        if ($this->time24hr) {
            return $this->withSeconds ? 'H:i:S' : 'H:i';
        }

        return $this->withSeconds ? 'h:i:S K' : 'h:i K';
    }

    /**
     * Get the computed display format for user-friendly display.
     */
    protected function getComputedDisplayFormat(): string
    {
        if ($this->displayFormat !== null) {
            return $this->displayFormat;
        }

        // Build display format based on settings
        if ($this->time24hr) {
            return $this->withSeconds ? 'H:i:S' : 'H:i';
        }

        return $this->withSeconds ? 'h:i:S K' : 'h:i K';
    }

    /**
     * Get all Flatpickr options as array.
     */
    public function getFlatpickrOptions(): array
    {
        $computedFormat = $this->getComputedFormat();
        $computedDisplayFormat = $this->getComputedDisplayFormat();

        $options = [
            'enableTime' => true,
            'noCalendar' => true,
            'enableSeconds' => $this->withSeconds,
            'time_24hr' => $this->time24hr,
            'dateFormat' => $computedFormat,
            'inline' => $this->inline,
        ];

        // Always use altInput for proper display formatting
        $options['altInput'] = true;
        $options['altFormat'] = $computedDisplayFormat;

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

        return array_merge($options, $this->flatpickrOptions);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.time-picker';
    }
}

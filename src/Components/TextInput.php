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

    // Date/time picker options (Splade compatibility)
    protected bool|array $dateOptions = false;

    protected bool|array $timeOptions = false;

    protected bool $isRange = false;

    // Static default formats
    protected static ?string $defaultDateFormat = null;

    protected static ?string $defaultTimeFormat = null;

    protected static ?string $defaultDatetimeFormat = null;

    protected static ?array $defaultFlatpickrOptions = null;

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
     * Enable date picker functionality (Splade compatibility).
     */
    public function date(bool|array $options = true): static
    {
        $this->dateOptions = $options;

        return $this;
    }

    /**
     * Check if date picker is enabled.
     */
    public function hasDate(): bool
    {
        return $this->dateOptions !== false;
    }

    /**
     * Get date picker options.
     */
    public function getDateOptions(): array
    {
        if ($this->dateOptions === true) {
            return static::$defaultFlatpickrOptions ?? [];
        }

        return is_array($this->dateOptions) ? $this->dateOptions : [];
    }

    /**
     * Enable time picker functionality (Splade compatibility).
     */
    public function time(bool|array $options = true): static
    {
        $this->timeOptions = $options;

        return $this;
    }

    /**
     * Check if time picker is enabled.
     */
    public function hasTime(): bool
    {
        return $this->timeOptions !== false;
    }

    /**
     * Get time picker options.
     */
    public function getTimeOptions(): array
    {
        if ($this->timeOptions === true) {
            return static::$defaultFlatpickrOptions ?? [];
        }

        return is_array($this->timeOptions) ? $this->timeOptions : [];
    }

    /**
     * Enable date range mode (Splade compatibility).
     */
    public function range(bool $range = true): static
    {
        $this->isRange = $range;

        return $this;
    }

    /**
     * Check if range mode is enabled.
     */
    public function isRange(): bool
    {
        return $this->isRange;
    }

    /**
     * Set default date format globally.
     */
    public static function defaultDateFormat(string $format): void
    {
        static::$defaultDateFormat = $format;
    }

    /**
     * Get default date format.
     */
    public static function getDefaultDateFormat(): string
    {
        return static::$defaultDateFormat ?? config('forms.datetime.date_format', 'Y-m-d');
    }

    /**
     * Set default time format globally.
     */
    public static function defaultTimeFormat(string $format): void
    {
        static::$defaultTimeFormat = $format;
    }

    /**
     * Get default time format.
     */
    public static function getDefaultTimeFormat(): string
    {
        return static::$defaultTimeFormat ?? config('forms.datetime.time_format', 'H:i');
    }

    /**
     * Set default datetime format globally.
     */
    public static function defaultDatetimeFormat(string $format): void
    {
        static::$defaultDatetimeFormat = $format;
    }

    /**
     * Get default datetime format.
     */
    public static function getDefaultDatetimeFormat(): string
    {
        return static::$defaultDatetimeFormat ?? config('forms.datetime.datetime_format', 'Y-m-d H:i');
    }

    /**
     * Set default Flatpickr options globally.
     */
    public static function defaultFlatpickr(array $options = []): void
    {
        static::$defaultFlatpickrOptions = $options;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.text-input';
    }
}

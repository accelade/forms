<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasMinMax;
use Accelade\Forms\Field;

/**
 * Telephone input field component with country code selector.
 */
class TelInput extends Field
{
    use HasMinMax;

    protected string $inputMode = 'tel';

    protected ?string $autocomplete = 'tel';

    protected ?string $defaultCountry = 'US';

    protected array $countries = [];

    protected bool $showFlags = true;

    protected bool $showDialCode = true;

    protected bool $searchable = true;

    protected ?string $mask = null;

    protected ?string $preferredCountries = null;

    protected bool $separateDialCode = false;

    /**
     * Default country codes and dial codes.
     */
    protected static array $defaultCountries = [
        'AF' => ['name' => 'Afghanistan', 'dial_code' => '+93', 'flag' => '🇦🇫'],
        'AL' => ['name' => 'Albania', 'dial_code' => '+355', 'flag' => '🇦🇱'],
        'DZ' => ['name' => 'Algeria', 'dial_code' => '+213', 'flag' => '🇩🇿'],
        'AR' => ['name' => 'Argentina', 'dial_code' => '+54', 'flag' => '🇦🇷'],
        'AU' => ['name' => 'Australia', 'dial_code' => '+61', 'flag' => '🇦🇺'],
        'AT' => ['name' => 'Austria', 'dial_code' => '+43', 'flag' => '🇦🇹'],
        'BD' => ['name' => 'Bangladesh', 'dial_code' => '+880', 'flag' => '🇧🇩'],
        'BE' => ['name' => 'Belgium', 'dial_code' => '+32', 'flag' => '🇧🇪'],
        'BR' => ['name' => 'Brazil', 'dial_code' => '+55', 'flag' => '🇧🇷'],
        'CA' => ['name' => 'Canada', 'dial_code' => '+1', 'flag' => '🇨🇦'],
        'CL' => ['name' => 'Chile', 'dial_code' => '+56', 'flag' => '🇨🇱'],
        'CN' => ['name' => 'China', 'dial_code' => '+86', 'flag' => '🇨🇳'],
        'CO' => ['name' => 'Colombia', 'dial_code' => '+57', 'flag' => '🇨🇴'],
        'CZ' => ['name' => 'Czech Republic', 'dial_code' => '+420', 'flag' => '🇨🇿'],
        'DK' => ['name' => 'Denmark', 'dial_code' => '+45', 'flag' => '🇩🇰'],
        'EG' => ['name' => 'Egypt', 'dial_code' => '+20', 'flag' => '🇪🇬'],
        'FI' => ['name' => 'Finland', 'dial_code' => '+358', 'flag' => '🇫🇮'],
        'FR' => ['name' => 'France', 'dial_code' => '+33', 'flag' => '🇫🇷'],
        'DE' => ['name' => 'Germany', 'dial_code' => '+49', 'flag' => '🇩🇪'],
        'GR' => ['name' => 'Greece', 'dial_code' => '+30', 'flag' => '🇬🇷'],
        'HK' => ['name' => 'Hong Kong', 'dial_code' => '+852', 'flag' => '🇭🇰'],
        'HU' => ['name' => 'Hungary', 'dial_code' => '+36', 'flag' => '🇭🇺'],
        'IN' => ['name' => 'India', 'dial_code' => '+91', 'flag' => '🇮🇳'],
        'ID' => ['name' => 'Indonesia', 'dial_code' => '+62', 'flag' => '🇮🇩'],
        'IE' => ['name' => 'Ireland', 'dial_code' => '+353', 'flag' => '🇮🇪'],
        'IL' => ['name' => 'Israel', 'dial_code' => '+972', 'flag' => '🇮🇱'],
        'IT' => ['name' => 'Italy', 'dial_code' => '+39', 'flag' => '🇮🇹'],
        'JP' => ['name' => 'Japan', 'dial_code' => '+81', 'flag' => '🇯🇵'],
        'KE' => ['name' => 'Kenya', 'dial_code' => '+254', 'flag' => '🇰🇪'],
        'KR' => ['name' => 'South Korea', 'dial_code' => '+82', 'flag' => '🇰🇷'],
        'MY' => ['name' => 'Malaysia', 'dial_code' => '+60', 'flag' => '🇲🇾'],
        'MX' => ['name' => 'Mexico', 'dial_code' => '+52', 'flag' => '🇲🇽'],
        'NL' => ['name' => 'Netherlands', 'dial_code' => '+31', 'flag' => '🇳🇱'],
        'NZ' => ['name' => 'New Zealand', 'dial_code' => '+64', 'flag' => '🇳🇿'],
        'NG' => ['name' => 'Nigeria', 'dial_code' => '+234', 'flag' => '🇳🇬'],
        'NO' => ['name' => 'Norway', 'dial_code' => '+47', 'flag' => '🇳🇴'],
        'PK' => ['name' => 'Pakistan', 'dial_code' => '+92', 'flag' => '🇵🇰'],
        'PH' => ['name' => 'Philippines', 'dial_code' => '+63', 'flag' => '🇵🇭'],
        'PL' => ['name' => 'Poland', 'dial_code' => '+48', 'flag' => '🇵🇱'],
        'PT' => ['name' => 'Portugal', 'dial_code' => '+351', 'flag' => '🇵🇹'],
        'RO' => ['name' => 'Romania', 'dial_code' => '+40', 'flag' => '🇷🇴'],
        'RU' => ['name' => 'Russia', 'dial_code' => '+7', 'flag' => '🇷🇺'],
        'SA' => ['name' => 'Saudi Arabia', 'dial_code' => '+966', 'flag' => '🇸🇦'],
        'SG' => ['name' => 'Singapore', 'dial_code' => '+65', 'flag' => '🇸🇬'],
        'ZA' => ['name' => 'South Africa', 'dial_code' => '+27', 'flag' => '🇿🇦'],
        'ES' => ['name' => 'Spain', 'dial_code' => '+34', 'flag' => '🇪🇸'],
        'SE' => ['name' => 'Sweden', 'dial_code' => '+46', 'flag' => '🇸🇪'],
        'CH' => ['name' => 'Switzerland', 'dial_code' => '+41', 'flag' => '🇨🇭'],
        'TW' => ['name' => 'Taiwan', 'dial_code' => '+886', 'flag' => '🇹🇼'],
        'TH' => ['name' => 'Thailand', 'dial_code' => '+66', 'flag' => '🇹🇭'],
        'TR' => ['name' => 'Turkey', 'dial_code' => '+90', 'flag' => '🇹🇷'],
        'UA' => ['name' => 'Ukraine', 'dial_code' => '+380', 'flag' => '🇺🇦'],
        'AE' => ['name' => 'United Arab Emirates', 'dial_code' => '+971', 'flag' => '🇦🇪'],
        'GB' => ['name' => 'United Kingdom', 'dial_code' => '+44', 'flag' => '🇬🇧'],
        'US' => ['name' => 'United States', 'dial_code' => '+1', 'flag' => '🇺🇸'],
        'VN' => ['name' => 'Vietnam', 'dial_code' => '+84', 'flag' => '🇻🇳'],
    ];

    /**
     * Set the default country.
     */
    public function defaultCountry(string $country): static
    {
        $this->defaultCountry = strtoupper($country);

        return $this;
    }

    /**
     * Get the default country code.
     */
    public function getDefaultCountry(): ?string
    {
        return $this->defaultCountry;
    }

    /**
     * Set the available countries (limit to specific countries).
     */
    public function countries(array $countries): static
    {
        $this->countries = array_map('strtoupper', $countries);

        return $this;
    }

    /**
     * Get the available countries.
     */
    public function getCountries(): array
    {
        if (empty($this->countries)) {
            return static::$defaultCountries;
        }

        // Preserve the order specified by the user
        $result = [];
        foreach ($this->countries as $code) {
            if (isset(static::$defaultCountries[$code])) {
                $result[$code] = static::$defaultCountries[$code];
            }
        }

        return $result;
    }

    /**
     * Set preferred countries to show at the top.
     */
    public function preferredCountries(string|array $countries): static
    {
        $this->preferredCountries = is_array($countries)
            ? implode(',', array_map('strtoupper', $countries))
            : strtoupper($countries);

        return $this;
    }

    /**
     * Get preferred countries.
     */
    public function getPreferredCountries(): array
    {
        if (empty($this->preferredCountries)) {
            return [];
        }

        return array_map('trim', explode(',', $this->preferredCountries));
    }

    /**
     * Show or hide country flags.
     */
    public function showFlags(bool $show = true): static
    {
        $this->showFlags = $show;

        return $this;
    }

    /**
     * Check if flags should be shown.
     */
    public function shouldShowFlags(): bool
    {
        return $this->showFlags;
    }

    /**
     * Show or hide dial codes in the dropdown.
     */
    public function showDialCode(bool $show = true): static
    {
        $this->showDialCode = $show;

        return $this;
    }

    /**
     * Check if dial codes should be shown.
     */
    public function shouldShowDialCode(): bool
    {
        return $this->showDialCode;
    }

    /**
     * Enable or disable country search.
     */
    public function searchable(bool $searchable = true): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Check if searchable.
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * Store dial code separately from phone number.
     */
    public function separateDialCode(bool $separate = true): static
    {
        $this->separateDialCode = $separate;

        return $this;
    }

    /**
     * Check if dial code should be stored separately.
     */
    public function hasSeparateDialCode(): bool
    {
        return $this->separateDialCode;
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
     * Get the input mode.
     */
    public function getInputMode(): string
    {
        return $this->inputMode;
    }

    /**
     * Set a mask pattern for the phone number.
     */
    public function mask(string $mask): static
    {
        $this->mask = $mask;

        return $this;
    }

    /**
     * Get the mask pattern.
     */
    public function getMask(): ?string
    {
        return $this->mask;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.tel-input';
    }

    /**
     * Serialize to array for JSON output with record context.
     */
    public function toArrayWithRecord(mixed $record = null): array
    {
        return array_merge(parent::toArrayWithRecord($record), [
            'inputMode' => $this->getInputMode(),
            'autocomplete' => $this->getAutocomplete(),
            'defaultCountry' => $this->getDefaultCountry(),
            'countries' => array_keys($this->getCountries()),
            'showFlags' => $this->shouldShowFlags(),
            'showDialCode' => $this->shouldShowDialCode(),
            'searchable' => $this->isSearchable(),
            'separateDialCode' => $this->hasSeparateDialCode(),
            'mask' => $this->getMask(),
        ]);
    }
}

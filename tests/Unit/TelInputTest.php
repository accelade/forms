<?php

declare(strict_types=1);

use Accelade\Forms\Components\TelInput;

it('can create a tel input', function () {
    $field = TelInput::make('phone');

    expect($field->getName())->toBe('phone')
        ->and($field->getId())->toBe('phone')
        ->and($field->getInputMode())->toBe('tel');
});

it('can set label', function () {
    $field = TelInput::make('phone')
        ->label('Phone Number');

    expect($field->getLabel())->toBe('Phone Number');
});

it('generates label from name when not set', function () {
    $field = TelInput::make('phone_number');

    expect($field->getLabel())->toBe('Phone Number');
});

it('can set placeholder', function () {
    $field = TelInput::make('phone')
        ->placeholder('+1 (555) 000-0000');

    expect($field->getPlaceholder())->toBe('+1 (555) 000-0000');
});

it('has default country set to US', function () {
    $field = TelInput::make('phone');

    expect($field->getDefaultCountry())->toBe('US');
});

it('can set default country', function () {
    $field = TelInput::make('phone')
        ->defaultCountry('GB');

    expect($field->getDefaultCountry())->toBe('GB');
});

it('normalizes country code to uppercase', function () {
    $field = TelInput::make('phone')
        ->defaultCountry('gb');

    expect($field->getDefaultCountry())->toBe('GB');
});

it('can limit available countries', function () {
    $field = TelInput::make('phone')
        ->countries(['US', 'CA', 'GB']);

    $countries = $field->getCountries();

    expect(array_keys($countries))->toBe(['US', 'CA', 'GB']);
});

it('normalizes country codes to uppercase in countries array', function () {
    $field = TelInput::make('phone')
        ->countries(['us', 'ca', 'gb']);

    $countries = $field->getCountries();

    expect(array_keys($countries))->toBe(['US', 'CA', 'GB']);
});

it('returns all default countries when no specific countries set', function () {
    $field = TelInput::make('phone');

    $countries = $field->getCountries();

    expect($countries)->toHaveKey('US')
        ->and($countries)->toHaveKey('GB')
        ->and($countries)->toHaveKey('FR')
        ->and($countries)->toHaveKey('DE');
});

it('can set preferred countries', function () {
    $field = TelInput::make('phone')
        ->preferredCountries(['US', 'GB', 'CA']);

    expect($field->getPreferredCountries())->toBe(['US', 'GB', 'CA']);
});

it('can set preferred countries from string', function () {
    $field = TelInput::make('phone')
        ->preferredCountries('US,GB,CA');

    expect($field->getPreferredCountries())->toBe(['US', 'GB', 'CA']);
});

it('shows flags by default', function () {
    $field = TelInput::make('phone');

    expect($field->shouldShowFlags())->toBeTrue();
});

it('can hide flags', function () {
    $field = TelInput::make('phone')
        ->showFlags(false);

    expect($field->shouldShowFlags())->toBeFalse();
});

it('shows dial codes by default', function () {
    $field = TelInput::make('phone');

    expect($field->shouldShowDialCode())->toBeTrue();
});

it('can hide dial codes', function () {
    $field = TelInput::make('phone')
        ->showDialCode(false);

    expect($field->shouldShowDialCode())->toBeFalse();
});

it('is searchable by default', function () {
    $field = TelInput::make('phone');

    expect($field->isSearchable())->toBeTrue();
});

it('can disable search', function () {
    $field = TelInput::make('phone')
        ->searchable(false);

    expect($field->isSearchable())->toBeFalse();
});

it('does not separate dial code by default', function () {
    $field = TelInput::make('phone');

    expect($field->hasSeparateDialCode())->toBeFalse();
});

it('can enable separate dial code storage', function () {
    $field = TelInput::make('phone')
        ->separateDialCode();

    expect($field->hasSeparateDialCode())->toBeTrue();
});

it('has tel autocomplete by default', function () {
    $field = TelInput::make('phone');

    expect($field->getAutocomplete())->toBe('tel');
});

it('can set autocomplete', function () {
    $field = TelInput::make('phone')
        ->autocomplete('tel-national');

    expect($field->getAutocomplete())->toBe('tel-national');
});

it('can set required', function () {
    $field = TelInput::make('phone')->required();

    expect($field->isRequired())->toBeTrue();
});

it('can set disabled', function () {
    $field = TelInput::make('phone')->disabled();

    expect($field->isDisabled())->toBeTrue();
});

it('can set readonly', function () {
    $field = TelInput::make('phone')->readonly();

    expect($field->isReadonly())->toBeTrue();
});

it('can set default value', function () {
    $field = TelInput::make('phone')->default('+1 555-123-4567');

    expect($field->getDefault())->toBe('+1 555-123-4567');
});

it('can set hint text', function () {
    $field = TelInput::make('phone')
        ->hint('Enter your mobile number');

    expect($field->getHint())->toBe('Enter your mobile number');
});

it('can set min and max length', function () {
    $field = TelInput::make('phone')
        ->minLength(10)
        ->maxLength(15);

    expect($field->getMinLength())->toBe(10)
        ->and($field->getMaxLength())->toBe(15);
});

it('can set a mask', function () {
    $field = TelInput::make('phone')
        ->mask('(999) 999-9999');

    expect($field->getMask())->toBe('(999) 999-9999');
});

it('can add extra attributes', function () {
    $field = TelInput::make('phone')
        ->extraAttributes(['data-test' => 'value', 'aria-label' => 'Phone input']);

    expect($field->getExtraAttributes())->toBe(['data-test' => 'value', 'aria-label' => 'Phone input']);
});

it('can set validation rules', function () {
    $field = TelInput::make('phone')
        ->rules(['regex:/^\+?[1-9]\d{1,14}$/']);

    expect($field->getRules())->toBe(['regex:/^\+?[1-9]\d{1,14}$/']);
});

it('adds required rule when required is set', function () {
    $field = TelInput::make('phone')
        ->required()
        ->rules(['min:10']);

    expect($field->getRules())->toBe(['required', 'min:10']);
});

it('can convert to array', function () {
    $field = TelInput::make('phone')
        ->label('Phone')
        ->required()
        ->defaultCountry('GB')
        ->showFlags(true)
        ->separateDialCode(true);

    $array = $field->toArray();

    expect($array['type'])->toBe('TelInput')
        ->and($array['name'])->toBe('phone')
        ->and($array['label'])->toBe('Phone')
        ->and($array['required'])->toBeTrue()
        ->and($array['inputMode'])->toBe('tel')
        ->and($array['defaultCountry'])->toBe('GB')
        ->and($array['showFlags'])->toBeTrue()
        ->and($array['separateDialCode'])->toBeTrue();
});

it('supports method chaining', function () {
    $field = TelInput::make('phone')
        ->label('Phone Number')
        ->placeholder('Enter phone')
        ->defaultCountry('US')
        ->preferredCountries(['US', 'CA'])
        ->showFlags()
        ->showDialCode()
        ->searchable()
        ->separateDialCode()
        ->required()
        ->hint('Mobile preferred');

    expect($field->getName())->toBe('phone')
        ->and($field->getLabel())->toBe('Phone Number')
        ->and($field->getPlaceholder())->toBe('Enter phone')
        ->and($field->getDefaultCountry())->toBe('US')
        ->and($field->getPreferredCountries())->toBe(['US', 'CA'])
        ->and($field->shouldShowFlags())->toBeTrue()
        ->and($field->shouldShowDialCode())->toBeTrue()
        ->and($field->isSearchable())->toBeTrue()
        ->and($field->hasSeparateDialCode())->toBeTrue()
        ->and($field->isRequired())->toBeTrue()
        ->and($field->getHint())->toBe('Mobile preferred');
});

it('contains expected country data', function () {
    $field = TelInput::make('phone');
    $countries = $field->getCountries();

    // Check US
    expect($countries['US']['name'])->toBe('United States')
        ->and($countries['US']['dial_code'])->toBe('+1')
        ->and($countries['US']['flag'])->toBe("\u{1F1FA}\u{1F1F8}");

    // Check GB
    expect($countries['GB']['name'])->toBe('United Kingdom')
        ->and($countries['GB']['dial_code'])->toBe('+44')
        ->and($countries['GB']['flag'])->toBe("\u{1F1EC}\u{1F1E7}");
});

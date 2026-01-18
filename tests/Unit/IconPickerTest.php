<?php

declare(strict_types=1);

use Accelade\Forms\Components\IconPicker;
use Accelade\Forms\Enums\IconSet;

it('can create an icon picker', function () {
    $field = IconPicker::make('icon');

    expect($field->getName())->toBe('icon')
        ->and($field->getId())->toBe('icon');
});

it('can set label', function () {
    $field = IconPicker::make('icon')
        ->label('Select Icon');

    expect($field->getLabel())->toBe('Select Icon');
});

it('can set placeholder', function () {
    $field = IconPicker::make('icon')
        ->placeholder('Choose an icon...');

    expect($field->getPlaceholder())->toBe('Choose an icon...');
});

it('can set required', function () {
    $field = IconPicker::make('icon')->required();

    expect($field->isRequired())->toBeTrue();
});

it('can set disabled', function () {
    $field = IconPicker::make('icon')->disabled();

    expect($field->isDisabled())->toBeTrue();
});

it('can enable searchable', function () {
    $field = IconPicker::make('icon')->searchable();

    expect($field->isSearchable())->toBeTrue();
});

it('can set grid columns', function () {
    $field = IconPicker::make('icon')->gridColumns(10);

    expect($field->getGridColumns())->toBe(10);
});

it('has default grid columns of 8', function () {
    $field = IconPicker::make('icon');

    expect($field->getGridColumns())->toBe(8);
});

it('can set multiple selection', function () {
    $field = IconPicker::make('icons')->multiple();

    expect($field->isMultiple())->toBeTrue();
});

it('can set max items', function () {
    $field = IconPicker::make('icons')
        ->multiple()
        ->maxItems(5);

    expect($field->getMaxItems())->toBe(5);
});

it('can set min items', function () {
    $field = IconPicker::make('icons')
        ->multiple()
        ->minItems(2);

    expect($field->getMinItems())->toBe(2);
});

it('can show icon name', function () {
    $field = IconPicker::make('icon')->showIconName();

    expect($field->getShowIconName())->toBeTrue();
});

it('can set icon sets using enum', function () {
    $field = IconPicker::make('icon')
        ->sets([IconSet::Emoji, IconSet::Heroicons]);

    $sets = $field->getSets();
    // Enums are converted to string values
    expect($sets)->toContain('emoji')
        ->toContain('heroicons');
});

it('can set icon sets using strings', function () {
    $field = IconPicker::make('icon')
        ->sets(['emoji', 'heroicons']);

    $sets = $field->getSets();
    expect($sets)->toContain('emoji')
        ->toContain('heroicons');
});

it('can set default icon set using enum', function () {
    $field = IconPicker::make('icon')
        ->sets([IconSet::Emoji, IconSet::Heroicons])
        ->defaultSet(IconSet::Heroicons);

    expect($field->getDefaultSet())->toBe('heroicons');
});

it('can set default icon set using string', function () {
    $field = IconPicker::make('icon')
        ->sets(['emoji', 'heroicons'])
        ->defaultSet('heroicons');

    expect($field->getDefaultSet())->toBe('heroicons');
});

it('defaults to emoji set', function () {
    $field = IconPicker::make('icon');

    expect($field->getDefaultSet())->toBe('emoji');
});

// Blade Icons mode tests
it('can enable blade icons mode', function () {
    $field = IconPicker::make('icon')->bladeIcons();

    expect($field->usesBladeIcons())->toBeTrue();
});

it('can disable blade icons mode', function () {
    $field = IconPicker::make('icon')->bladeIcons(false);

    expect($field->usesBladeIcons())->toBeFalse();
});

it('has default per page of 50', function () {
    $field = IconPicker::make('icon')->bladeIcons();

    expect($field->getPerPage())->toBe(50);
});

it('can set per page', function () {
    $field = IconPicker::make('icon')
        ->bladeIcons()
        ->perPage(100);

    expect($field->getPerPage())->toBe(100);
});

// Note: API endpoint tests require routes to be registered, tested in Feature tests

it('returns blade icon sets when blade icons enabled', function () {
    $field = IconPicker::make('icon')->bladeIcons();

    $sets = $field->getBladeIconSets();
    expect($sets)->toBeArray();
});

it('can set custom icons', function () {
    $customIcons = [
        'custom' => [
            'general' => [
                'icon1' => 'Icon One',
                'icon2' => 'Icon Two',
            ],
        ],
    ];

    $field = IconPicker::make('icon')->icons($customIcons);

    // getIcons() returns icons merged with default icons
    $icons = $field->getIcons();
    expect($icons)->toHaveKey('custom');
});

it('can set default value', function () {
    $field = IconPicker::make('icon')->default('😀');

    expect($field->getDefault())->toBe('😀');
});

it('can convert to array', function () {
    $field = IconPicker::make('icon')
        ->label('Icon')
        ->required()
        ->bladeIcons()
        ->perPage(100);

    $array = $field->toArray();

    expect($array['type'])->toBe('IconPicker')
        ->and($array['name'])->toBe('icon')
        ->and($array['label'])->toBe('Icon')
        ->and($array['required'])->toBeTrue();
});

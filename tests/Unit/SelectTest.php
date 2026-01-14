<?php

declare(strict_types=1);

use Accelade\Forms\Components\Select;

it('can create a select field', function () {
    $field = Select::make('country');

    expect($field->getName())->toBe('country')
        ->and($field->getId())->toBe('country');
});

it('can set options', function () {
    $field = Select::make('country')
        ->options([
            'us' => 'United States',
            'uk' => 'United Kingdom',
            'ca' => 'Canada',
        ]);

    expect($field->getOptions())->toBe([
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ]);
});

it('has empty option label by default', function () {
    $field = Select::make('country');

    expect($field->getEmptyOptionLabel())->toBe('Select an option');
});

it('can set custom empty option label', function () {
    $field = Select::make('country')
        ->emptyOptionLabel('Choose a country');

    expect($field->getEmptyOptionLabel())->toBe('Choose a country');
});

it('can disable empty option', function () {
    $field = Select::make('country')
        ->disableEmptyOption();

    expect($field->getEmptyOptionLabel())->toBeNull();
});

it('can be searchable', function () {
    $field = Select::make('country')->searchable();

    expect($field->isSearchable())->toBeTrue();
});

it('can allow multiple selections', function () {
    $field = Select::make('tags')->multiple();

    expect($field->isMultiple())->toBeTrue();
});

it('is native by default', function () {
    $field = Select::make('country');

    expect($field->isNative())->toBeTrue();
});

it('can disable native mode', function () {
    $field = Select::make('country')->native(false);

    expect($field->isNative())->toBeFalse();
});

it('can get option label', function () {
    $field = Select::make('country')
        ->options([
            'us' => 'United States',
            'uk' => 'United Kingdom',
        ]);

    expect($field->getOptionLabel('us'))->toBe('United States')
        ->and($field->getOptionLabel('invalid'))->toBeNull();
});

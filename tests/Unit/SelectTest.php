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

it('can use custom callback for getting option label', function () {
    $field = Select::make('country')
        ->getOptionLabelUsing(fn ($value) => strtoupper($value));

    expect($field->getOptionLabel('us'))->toBe('US');
});

// Splade compatibility - optionLabel / optionValue
it('can set option label property for object collections', function () {
    $field = Select::make('user')
        ->optionLabel('name');

    expect($field->getOptionLabelProperty())->toBe('name');
});

it('can set option value property for object collections', function () {
    $field = Select::make('user')
        ->optionValue('id');

    expect($field->getOptionValueProperty())->toBe('id');
});

// Splade compatibility - Choices.js integration
it('does not have choices enabled by default', function () {
    $field = Select::make('country');

    expect($field->hasChoices())->toBeFalse();
});

it('can enable choices', function () {
    $field = Select::make('country')->choices();

    expect($field->hasChoices())->toBeTrue()
        ->and($field->isNative())->toBeFalse();
});

it('can enable choices with custom options', function () {
    $field = Select::make('country')->choices(['searchEnabled' => true]);

    expect($field->hasChoices())->toBeTrue()
        ->and($field->getChoicesOptions())->toBe(['searchEnabled' => true]);
});

it('can set default choices options globally', function () {
    Select::defaultChoices(['removeItemButton' => true]);

    $field = Select::make('country')->choices();

    expect($field->getChoicesOptions())->toBe(['removeItemButton' => true]);

    // Reset for other tests
    Select::defaultChoices([]);
});

// Splade compatibility - Remote URL
it('does not have remote url by default', function () {
    $field = Select::make('country');

    expect($field->hasRemoteUrl())->toBeFalse()
        ->and($field->getRemoteUrl())->toBeNull();
});

it('can set remote url', function () {
    $field = Select::make('country')
        ->remoteUrl('/api/countries');

    expect($field->hasRemoteUrl())->toBeTrue()
        ->and($field->getRemoteUrl())->toBe('/api/countries');
});

it('can set remote root path', function () {
    $field = Select::make('country')
        ->remoteUrl('/api/countries')
        ->remoteRoot('data.countries');

    expect($field->getRemoteRoot())->toBe('data.countries');
});

it('does not select first remote option by default', function () {
    $field = Select::make('country');

    expect($field->shouldSelectFirstRemoteOption())->toBeFalse();
});

it('can select first remote option', function () {
    $field = Select::make('country')
        ->remoteUrl('/api/countries')
        ->selectFirstRemoteOption();

    expect($field->shouldSelectFirstRemoteOption())->toBeTrue();
});

it('does not reset on new remote url by default', function () {
    $field = Select::make('country');

    expect($field->shouldResetOnNewRemoteUrl())->toBeFalse();
});

it('can reset on new remote url', function () {
    $field = Select::make('country')
        ->remoteUrl('/api/countries')
        ->resetOnNewRemoteUrl();

    expect($field->shouldResetOnNewRemoteUrl())->toBeTrue();
});

// Splade compatibility - Relation
it('does not have relation by default', function () {
    $field = Select::make('tags');

    expect($field->hasRelation())->toBeFalse()
        ->and($field->getRelation())->toBeNull();
});

it('can set relation and enables multiple', function () {
    $field = Select::make('tags')
        ->relation('tags');

    expect($field->hasRelation())->toBeTrue()
        ->and($field->getRelation())->toBe('tags')
        ->and($field->isMultiple())->toBeTrue();
});

// Splade compatibility - Preload
it('does not preload by default', function () {
    $field = Select::make('country');

    expect($field->shouldPreload())->toBeFalse();
});

it('can enable preload', function () {
    $field = Select::make('country')
        ->remoteUrl('/api/countries')
        ->preload();

    expect($field->shouldPreload())->toBeTrue();
});

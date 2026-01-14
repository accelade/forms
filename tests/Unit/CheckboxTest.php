<?php

declare(strict_types=1);

use Accelade\Forms\Components\Checkbox;

it('can create a checkbox', function () {
    $field = Checkbox::make('terms');

    expect($field->getName())->toBe('terms')
        ->and($field->getId())->toBe('terms');
});

it('has default checked and unchecked values', function () {
    $field = Checkbox::make('terms');

    expect($field->getCheckedValue())->toBeTrue()
        ->and($field->getUncheckedValue())->toBeFalse();
});

it('can set custom checked and unchecked values', function () {
    $field = Checkbox::make('status')
        ->checkedValue('active')
        ->uncheckedValue('inactive');

    expect($field->getCheckedValue())->toBe('active')
        ->and($field->getUncheckedValue())->toBe('inactive');
});

it('is inline by default', function () {
    $field = Checkbox::make('terms');

    expect($field->isInline())->toBeTrue();
});

it('can be set to stacked layout', function () {
    $field = Checkbox::make('terms')->inline(false);

    expect($field->isInline())->toBeFalse();
});

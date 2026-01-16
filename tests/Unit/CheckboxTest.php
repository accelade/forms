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

// Splade compatibility - value() and falseValue() aliases
it('can set checked value using value() alias', function () {
    $field = Checkbox::make('status')->value('yes');

    expect($field->getCheckedValue())->toBe('yes');
});

it('can set unchecked value using falseValue() alias', function () {
    $field = Checkbox::make('status')->falseValue('no');

    expect($field->getUncheckedValue())->toBe('no');
});

it('can chain value() and falseValue() methods', function () {
    $field = Checkbox::make('newsletter')
        ->value('1')
        ->falseValue('0');

    expect($field->getCheckedValue())->toBe('1')
        ->and($field->getUncheckedValue())->toBe('0');
});

<?php

declare(strict_types=1);

use Accelade\Forms\Components\Toggle;

it('can create a toggle', function () {
    $field = Toggle::make('notifications');

    expect($field->getName())->toBe('notifications')
        ->and($field->getId())->toBe('notifications');
});

it('has default on and off values', function () {
    $field = Toggle::make('enabled');

    expect($field->getOnValue())->toBeTrue()
        ->and($field->getOffValue())->toBeFalse();
});

it('can set custom on and off values', function () {
    $field = Toggle::make('status')
        ->onValue('active')
        ->offValue('inactive');

    expect($field->getOnValue())->toBe('active')
        ->and($field->getOffValue())->toBe('inactive');
});

it('has default colors', function () {
    $field = Toggle::make('enabled');

    expect($field->getOnColor())->toBe('primary')
        ->and($field->getOffColor())->toBe('gray');
});

it('can set custom colors', function () {
    $field = Toggle::make('enabled')
        ->onColor('success')
        ->offColor('danger');

    expect($field->getOnColor())->toBe('success')
        ->and($field->getOffColor())->toBe('danger');
});

it('can set icons', function () {
    $field = Toggle::make('enabled')
        ->onIcon('<svg>on</svg>')
        ->offIcon('<svg>off</svg>');

    expect($field->getOnIcon())->toBe('<svg>on</svg>')
        ->and($field->getOffIcon())->toBe('<svg>off</svg>');
});

it('is inline by default', function () {
    $field = Toggle::make('enabled');

    expect($field->isInline())->toBeTrue();
});

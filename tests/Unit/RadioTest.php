<?php

declare(strict_types=1);

use Accelade\Forms\Components\Radio;

it('can create a radio field', function () {
    $field = Radio::make('gender');

    expect($field->getName())->toBe('gender')
        ->and($field->getId())->toBe('gender');
});

it('can set options', function () {
    $field = Radio::make('gender')
        ->options([
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ]);

    expect($field->getOptions())->toBe([
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
    ]);
});

it('can set descriptions for options', function () {
    $field = Radio::make('plan')
        ->options([
            'basic' => 'Basic',
            'pro' => 'Professional',
        ])
        ->descriptions([
            'basic' => 'For small teams',
            'pro' => 'For large organizations',
        ]);

    expect($field->getDescription('basic'))->toBe('For small teams')
        ->and($field->getDescription('pro'))->toBe('For large organizations')
        ->and($field->getDescription('invalid'))->toBeNull();
});

it('is inline by default', function () {
    $field = Radio::make('gender');

    expect($field->isInline())->toBeTrue();
});

it('can be set to stacked layout', function () {
    $field = Radio::make('gender')->inline(false);

    expect($field->isInline())->toBeFalse();
});

// Grid columns layout
it('has one column by default', function () {
    $field = Radio::make('plan');

    expect($field->getColumns())->toBe(1)
        ->and($field->hasColumns())->toBeFalse();
});

it('can set columns for grid layout', function () {
    $field = Radio::make('plan')->columns(3);

    expect($field->getColumns())->toBe(3)
        ->and($field->hasColumns())->toBeTrue();
});

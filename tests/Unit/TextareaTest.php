<?php

declare(strict_types=1);

use Accelade\Forms\Components\Textarea;

it('can create a textarea', function () {
    $field = Textarea::make('bio');

    expect($field->getName())->toBe('bio')
        ->and($field->getId())->toBe('bio');
});

it('has default rows of 3', function () {
    $field = Textarea::make('bio');

    expect($field->getRows())->toBe(3);
});

it('can set rows', function () {
    $field = Textarea::make('bio')->rows(5);

    expect($field->getRows())->toBe(5);
});

it('can set cols', function () {
    $field = Textarea::make('bio')->cols(80);

    expect($field->getCols())->toBe(80);
});

it('does not have autosize by default', function () {
    $field = Textarea::make('bio');

    expect($field->hasAutosize())->toBeFalse();
});

it('can enable autosize', function () {
    $field = Textarea::make('bio')->autosize();

    expect($field->hasAutosize())->toBeTrue();
});

it('can set min and max length', function () {
    $field = Textarea::make('bio')
        ->minLength(10)
        ->maxLength(500);

    expect($field->getMinLength())->toBe(10)
        ->and($field->getMaxLength())->toBe(500);
});

// Splade compatibility - defaultAutosize
it('can set default autosize globally', function () {
    Textarea::defaultAutosize();

    $field = Textarea::make('bio');

    expect($field->hasAutosize())->toBeTrue();

    // Reset for other tests
    Textarea::defaultAutosize(false);
});

it('can override default autosize per instance', function () {
    Textarea::defaultAutosize();

    $field = Textarea::make('bio')->autosize(false);

    expect($field->hasAutosize())->toBeFalse();

    // Reset for other tests
    Textarea::defaultAutosize(false);
});

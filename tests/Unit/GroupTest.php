<?php

declare(strict_types=1);

use Accelade\Forms\Components\Checkbox;
use Accelade\Forms\Components\Group;
use Accelade\Forms\Components\Radio;

it('can create a group', function () {
    $field = Group::make('tags');

    expect($field->getName())->toBe('tags')
        ->and($field->getId())->toBe('tags');
});

it('can set a label', function () {
    $field = Group::make('tags')
        ->label('Select Tags');

    expect($field->getLabel())->toBe('Select Tags');
});

it('is inline by default', function () {
    $field = Group::make('options');

    expect($field->isInline())->toBeTrue();
});

it('can be set to stacked layout', function () {
    $field = Group::make('options')->inline(false);

    expect($field->isInline())->toBeFalse();
});

it('can contain child fields via schema', function () {
    $field = Group::make('tags')
        ->schema([
            Checkbox::make('laravel')->label('Laravel'),
            Checkbox::make('vue')->label('Vue.js'),
        ]);

    expect($field->hasSchema())->toBeTrue()
        ->and($field->getSchema())->toHaveCount(2);
});

it('shows group errors by default', function () {
    $field = Group::make('tags');

    expect($field->shouldShowErrors())->toBeTrue();
});

it('can hide group errors', function () {
    $field = Group::make('tags')->showErrors(false);

    expect($field->shouldShowErrors())->toBeFalse();
});

it('does not show child errors by default', function () {
    $field = Group::make('tags');

    expect($field->shouldShowChildErrors())->toBeFalse();
});

it('can enable child errors', function () {
    $field = Group::make('tags')->showChildErrors();

    expect($field->shouldShowChildErrors())->toBeTrue();
});

it('can set helper text', function () {
    $field = Group::make('tags')
        ->helperText('Select one or more tags');

    expect($field->getHelperText())->toBe('Select one or more tags');
});

it('can be required', function () {
    $field = Group::make('tags')->required();

    expect($field->isRequired())->toBeTrue();
});

it('can group radio buttons', function () {
    $field = Group::make('theme')
        ->label('Choose a theme')
        ->schema([
            Radio::make('theme')
                ->options([
                    'light' => 'Light',
                    'dark' => 'Dark',
                ]),
        ]);

    expect($field->hasSchema())->toBeTrue()
        ->and($field->getLabel())->toBe('Choose a theme');
});

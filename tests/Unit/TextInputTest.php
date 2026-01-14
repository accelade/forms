<?php

declare(strict_types=1);

use Accelade\Forms\Components\TextInput;

it('can create a text input', function () {
    $field = TextInput::make('name');

    expect($field->getName())->toBe('name')
        ->and($field->getType())->toBe('text')
        ->and($field->getId())->toBe('name');
});

it('can set label', function () {
    $field = TextInput::make('email')
        ->label('Email Address');

    expect($field->getLabel())->toBe('Email Address');
});

it('generates label from name when not set', function () {
    $field = TextInput::make('first_name');

    expect($field->getLabel())->toBe('First Name');
});

it('can set placeholder', function () {
    $field = TextInput::make('email')
        ->placeholder('Enter your email');

    expect($field->getPlaceholder())->toBe('Enter your email');
});

it('can set as email type', function () {
    $field = TextInput::make('email')->email();

    expect($field->getType())->toBe('email')
        ->and($field->getInputMode())->toBe('email');
});

it('can set as password type', function () {
    $field = TextInput::make('password')->password();

    expect($field->getType())->toBe('password');
});

it('can set as numeric type', function () {
    $field = TextInput::make('age')->numeric();

    expect($field->getType())->toBe('number')
        ->and($field->getInputMode())->toBe('numeric');
});

it('can set as tel type', function () {
    $field = TextInput::make('phone')->tel();

    expect($field->getType())->toBe('tel')
        ->and($field->getInputMode())->toBe('tel');
});

it('can set as url type', function () {
    $field = TextInput::make('website')->url();

    expect($field->getType())->toBe('url')
        ->and($field->getInputMode())->toBe('url');
});

it('can set required', function () {
    $field = TextInput::make('name')->required();

    expect($field->isRequired())->toBeTrue();
});

it('can set disabled', function () {
    $field = TextInput::make('name')->disabled();

    expect($field->isDisabled())->toBeTrue();
});

it('can set readonly', function () {
    $field = TextInput::make('name')->readonly();

    expect($field->isReadonly())->toBeTrue();
});

it('can set hidden', function () {
    $field = TextInput::make('name')->hidden();

    expect($field->isHidden())->toBeTrue();
});

it('can set visible', function () {
    $field = TextInput::make('name')->hidden()->visible();

    expect($field->isHidden())->toBeFalse();
});

it('can set default value', function () {
    $field = TextInput::make('name')->default('John');

    expect($field->getDefault())->toBe('John');
});

it('can set prefix and suffix', function () {
    $field = TextInput::make('price')
        ->prefix('$')
        ->suffix('.00');

    expect($field->getPrefix())->toBe('$')
        ->and($field->getSuffix())->toBe('.00');
});

it('can set min and max values', function () {
    $field = TextInput::make('age')
        ->numeric()
        ->minValue(18)
        ->maxValue(100);

    expect($field->getMinValue())->toBe(18)
        ->and($field->getMaxValue())->toBe(100);
});

it('can set min and max length', function () {
    $field = TextInput::make('username')
        ->minLength(3)
        ->maxLength(20);

    expect($field->getMinLength())->toBe(3)
        ->and($field->getMaxLength())->toBe(20);
});

it('can set step', function () {
    $field = TextInput::make('price')
        ->numeric()
        ->step(0.01);

    expect($field->getStep())->toBe(0.01);
});

it('can set autocomplete', function () {
    $field = TextInput::make('email')
        ->autocomplete('email');

    expect($field->getAutocomplete())->toBe('email');
});

it('can set hint text', function () {
    $field = TextInput::make('email')
        ->hint('We will never share your email');

    expect($field->getHint())->toBe('We will never share your email');
});

it('can add extra attributes', function () {
    $field = TextInput::make('name')
        ->extraAttributes(['data-test' => 'value', 'aria-label' => 'Name input']);

    expect($field->getExtraAttributes())->toBe(['data-test' => 'value', 'aria-label' => 'Name input']);
});

it('can set validation rules', function () {
    $field = TextInput::make('email')
        ->rules(['email', 'max:255']);

    expect($field->getRules())->toBe(['email', 'max:255']);
});

it('adds required rule when required is set', function () {
    $field = TextInput::make('email')
        ->required()
        ->rules(['email']);

    expect($field->getRules())->toBe(['required', 'email']);
});

it('can convert to array', function () {
    $field = TextInput::make('email')
        ->label('Email')
        ->required()
        ->default('test@example.com');

    $array = $field->toArray();

    expect($array['type'])->toBe('TextInput')
        ->and($array['name'])->toBe('email')
        ->and($array['label'])->toBe('Email')
        ->and($array['required'])->toBeTrue()
        ->and($array['default'])->toBe('test@example.com');
});

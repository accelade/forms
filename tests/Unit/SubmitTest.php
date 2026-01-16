<?php

declare(strict_types=1);

use Accelade\Forms\Components\Submit;

it('can create a submit button', function () {
    $submit = Submit::make();

    expect($submit->getLabel())->toBe('Submit')
        ->and($submit->getType())->toBe('submit');
});

it('can create with custom label', function () {
    $submit = Submit::make('Save Changes');

    expect($submit->getLabel())->toBe('Save Changes');
});

it('can set label via method', function () {
    $submit = Submit::make()->label('Apply Settings');

    expect($submit->getLabel())->toBe('Apply Settings');
});

it('has spinner enabled by default', function () {
    $submit = Submit::make();

    expect($submit->hasSpinner())->toBeTrue();
});

it('can disable spinner', function () {
    $submit = Submit::make()->spinner(false);

    expect($submit->hasSpinner())->toBeFalse();
});

it('is primary by default', function () {
    $submit = Submit::make();

    expect($submit->isPrimary())->toBeTrue()
        ->and($submit->isDanger())->toBeFalse()
        ->and($submit->isSecondary())->toBeFalse();
});

it('can be set to danger variant', function () {
    $submit = Submit::make()->danger();

    expect($submit->isDanger())->toBeTrue()
        ->and($submit->isPrimary())->toBeFalse()
        ->and($submit->isSecondary())->toBeFalse();
});

it('can be set to secondary variant', function () {
    $submit = Submit::make()->secondary();

    expect($submit->isSecondary())->toBeTrue()
        ->and($submit->isPrimary())->toBeFalse()
        ->and($submit->isDanger())->toBeFalse();
});

it('danger clears secondary and vice versa', function () {
    $submit = Submit::make()->secondary()->danger();

    expect($submit->isDanger())->toBeTrue()
        ->and($submit->isSecondary())->toBeFalse();

    $submit->secondary();

    expect($submit->isSecondary())->toBeTrue()
        ->and($submit->isDanger())->toBeFalse();
});

it('is not disabled by default', function () {
    $submit = Submit::make();

    expect($submit->isDisabled())->toBeFalse();
});

it('can be disabled', function () {
    $submit = Submit::make()->disabled();

    expect($submit->isDisabled())->toBeTrue();
});

it('can set button type', function () {
    $submit = Submit::make()->type('button');

    expect($submit->getType())->toBe('button');
});

it('can add extra attributes', function () {
    $submit = Submit::make()
        ->extraAttributes(['data-confirm' => 'Are you sure?']);

    expect($submit->getExtraAttributes())->toBe(['data-confirm' => 'Are you sure?'])
        ->and($submit->getExtraAttributesString())->toBe('data-confirm="Are you sure?"');
});

<?php

declare(strict_types=1);

use Accelade\Forms\Components\CheckboxList;

it('can create a checkbox list', function () {
    $field = CheckboxList::make('tags');

    expect($field->getName())->toBe('tags')
        ->and($field->getId())->toBe('tags');
});

it('can set options', function () {
    $field = CheckboxList::make('tags')
        ->options([
            'laravel' => 'Laravel',
            'vue' => 'Vue.js',
            'tailwind' => 'Tailwind CSS',
        ]);

    expect($field->getOptions())->toBe([
        'laravel' => 'Laravel',
        'vue' => 'Vue.js',
        'tailwind' => 'Tailwind CSS',
    ]);
});

it('has one column by default', function () {
    $field = CheckboxList::make('tags');

    expect($field->getColumns())->toBe(1);
});

it('can set columns', function () {
    $field = CheckboxList::make('tags')->columns(3);

    expect($field->getColumns())->toBe(3);
});

it('can set descriptions for options', function () {
    $field = CheckboxList::make('tags')
        ->options([
            'laravel' => 'Laravel',
            'vue' => 'Vue.js',
        ])
        ->descriptions([
            'laravel' => 'PHP web framework',
            'vue' => 'JavaScript framework',
        ]);

    expect($field->getDescription('laravel'))->toBe('PHP web framework')
        ->and($field->getDescription('vue'))->toBe('JavaScript framework')
        ->and($field->getDescription('invalid'))->toBeNull();
});

it('is not bulk toggleable by default', function () {
    $field = CheckboxList::make('tags');

    expect($field->isBulkToggleable())->toBeFalse();
});

it('can enable bulk toggle', function () {
    $field = CheckboxList::make('tags')->bulkToggleable();

    expect($field->isBulkToggleable())->toBeTrue();
});

// Splade compatibility - relation support
it('does not have relation by default', function () {
    $field = CheckboxList::make('tags');

    expect($field->hasRelation())->toBeFalse()
        ->and($field->getRelation())->toBeNull();
});

it('can set relation with custom name', function () {
    $field = CheckboxList::make('tag_ids')
        ->relation('tags');

    expect($field->hasRelation())->toBeTrue()
        ->and($field->getRelation())->toBe('tags');
});

it('can set relation using field name as default', function () {
    $field = CheckboxList::make('tags')
        ->relation();

    expect($field->hasRelation())->toBeTrue()
        ->and($field->getRelation())->toBe('tags');
});

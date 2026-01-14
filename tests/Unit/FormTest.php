<?php

declare(strict_types=1);

use Accelade\Forms\Components\TextInput;
use Accelade\Forms\Form;

it('can create a form', function () {
    $form = Form::make('contact');

    expect($form->getId())->toBe('contact');
});

it('can set action', function () {
    $form = Form::make()
        ->action('/contact');

    expect($form->getAction())->toBe('/contact');
});

it('has POST method by default', function () {
    $form = Form::make();

    expect($form->getMethod())->toBe('POST');
});

it('can set method', function () {
    $form = Form::make()
        ->method('PUT');

    expect($form->getMethod())->toBe('PUT')
        ->and($form->needsMethodSpoofing())->toBeTrue()
        ->and($form->getFormMethod())->toBe('POST');
});

it('does not need spoofing for GET and POST', function () {
    expect(Form::make()->method('GET')->needsMethodSpoofing())->toBeFalse()
        ->and(Form::make()->method('POST')->needsMethodSpoofing())->toBeFalse()
        ->and(Form::make()->method('PUT')->needsMethodSpoofing())->toBeTrue()
        ->and(Form::make()->method('PATCH')->needsMethodSpoofing())->toBeTrue()
        ->and(Form::make()->method('DELETE')->needsMethodSpoofing())->toBeTrue();
});

it('can set schema', function () {
    $form = Form::make()
        ->schema([
            TextInput::make('name'),
            TextInput::make('email'),
        ]);

    expect($form->getSchema())->toHaveCount(2);
});

it('can bind a model', function () {
    $model = new stdClass;
    $model->name = 'John';

    $form = Form::make()
        ->model($model);

    expect($form->getModel())->toBe($model)
        ->and($form->hasModel())->toBeTrue();
});

it('does not have files by default', function () {
    $form = Form::make();

    expect($form->hasFiles())->toBeFalse()
        ->and($form->getEnctype())->toBeNull();
});

it('can enable file uploads', function () {
    $form = Form::make()
        ->withFiles();

    expect($form->hasFiles())->toBeTrue()
        ->and($form->getEnctype())->toBe('multipart/form-data');
});

it('shows submit button by default', function () {
    $form = Form::make();

    expect($form->shouldShowSubmit())->toBeTrue();
});

it('can hide submit button', function () {
    $form = Form::make()
        ->withoutSubmit();

    expect($form->shouldShowSubmit())->toBeFalse();
});

it('has default submit label', function () {
    $form = Form::make();

    expect($form->getSubmitLabel())->toBe('Submit');
});

it('can set custom submit label', function () {
    $form = Form::make()
        ->submitLabel('Send Message');

    expect($form->getSubmitLabel())->toBe('Send Message');
});

it('can filter hidden fields from schema', function () {
    $form = Form::make()
        ->schema([
            TextInput::make('name'),
            TextInput::make('secret')->hidden(),
            TextInput::make('email'),
        ]);

    expect($form->getSchema())->toHaveCount(3)
        ->and($form->getVisibleSchema())->toHaveCount(2);
});

it('can extract validation rules from schema', function () {
    $form = Form::make()
        ->schema([
            TextInput::make('name')->required(),
            TextInput::make('email')->required()->rules(['email']),
        ]);

    $rules = $form->getValidationRules();

    expect($rules)->toBe([
        'name' => ['required'],
        'email' => ['required', 'email'],
    ]);
});

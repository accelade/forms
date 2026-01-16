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

// Splade-compatible submission behavior tests
it('does not stay on page by default', function () {
    $form = Form::make();

    expect($form->shouldStay())->toBeFalse();
});

it('can enable stay on page', function () {
    $form = Form::make()->stay();

    expect($form->shouldStay())->toBeTrue();
});

it('does not preserve scroll by default', function () {
    $form = Form::make();

    expect($form->shouldPreserveScroll())->toBeFalse();
});

it('can enable preserve scroll', function () {
    $form = Form::make()->preserveScroll();

    expect($form->shouldPreserveScroll())->toBeTrue();
});

it('does not reset on success by default', function () {
    $form = Form::make();

    expect($form->shouldResetOnSuccess())->toBeFalse();
});

it('can enable reset on success', function () {
    $form = Form::make()->resetOnSuccess();

    expect($form->shouldResetOnSuccess())->toBeTrue();
});

it('does not restore on success by default', function () {
    $form = Form::make();

    expect($form->shouldRestoreOnSuccess())->toBeFalse();
});

it('can enable restore on success', function () {
    $form = Form::make()->restoreOnSuccess();

    expect($form->shouldRestoreOnSuccess())->toBeTrue();
});

// Confirmation dialog tests
it('does not require confirmation by default', function () {
    $form = Form::make();

    expect($form->requiresConfirmation())->toBeFalse();
});

it('can enable confirmation', function () {
    $form = Form::make()->confirm();

    expect($form->requiresConfirmation())->toBeTrue();
});

it('can set confirmation with text shortcut', function () {
    $form = Form::make()->confirm('Are you sure?');

    expect($form->requiresConfirmation())->toBeTrue()
        ->and($form->getConfirmText())->toBe('Are you sure?');
});

it('can set confirmation text separately', function () {
    $form = Form::make()->confirmText('Delete this item?');

    expect($form->requiresConfirmation())->toBeTrue()
        ->and($form->getConfirmText())->toBe('Delete this item?');
});

it('can set confirm button text', function () {
    $form = Form::make()->confirmButton('Yes, delete');

    expect($form->getConfirmButton())->toBe('Yes, delete');
});

it('can set cancel button text', function () {
    $form = Form::make()->cancelButton('No, keep it');

    expect($form->getCancelButton())->toBe('No, keep it');
});

it('can enable confirm danger style', function () {
    $form = Form::make()->confirmDanger();

    expect($form->requiresConfirmation())->toBeTrue()
        ->and($form->isConfirmDanger())->toBeTrue();
});

// Password confirmation tests
it('does not require password by default', function () {
    $form = Form::make();

    expect($form->requiresPassword())->toBeFalse()
        ->and($form->requiresPasswordOnce())->toBeFalse();
});

it('can require password', function () {
    $form = Form::make()->requirePassword();

    expect($form->requiresPassword())->toBeTrue();
});

it('can require password once per session', function () {
    $form = Form::make()->requirePasswordOnce();

    expect($form->requiresPasswordOnce())->toBeTrue();
});

// Auto-submit tests
it('does not submit on change by default', function () {
    $form = Form::make();

    expect($form->submitsOnChange())->toBeFalse();
});

it('can enable submit on change', function () {
    $form = Form::make()->submitOnChange();

    expect($form->submitsOnChange())->toBeTrue();
});

it('has no debounce by default', function () {
    $form = Form::make();

    expect($form->getDebounce())->toBeNull();
});

it('can set debounce time', function () {
    $form = Form::make()->debounce(500);

    expect($form->getDebounce())->toBe(500);
});

// Background submission tests
it('does not submit in background by default', function () {
    $form = Form::make();

    expect($form->submitsInBackground())->toBeFalse();
});

it('can enable background submission', function () {
    $form = Form::make()->background();

    expect($form->submitsInBackground())->toBeTrue();
});

// Blob handling tests
it('does not handle blobs by default', function () {
    $form = Form::make();

    expect($form->handlesBlob())->toBeFalse();
});

it('can enable blob handling', function () {
    $form = Form::make()->blob();

    expect($form->handlesBlob())->toBeTrue();
});

// Unguarded tests
it('is not unguarded by default', function () {
    $form = Form::make();

    expect($form->isUnguarded())->toBeFalse();
});

it('can enable unguarded mode', function () {
    $form = Form::make()->unguarded();

    expect($form->isUnguarded())->toBeTrue();
});

it('can enable selective unguarding with array of fields', function () {
    $form = Form::make()->unguarded(['name', 'email']);

    expect($form->isUnguarded())->toBeTrue()
        ->and($form->getUnguardedFields())->toBe(['name', 'email']);
});

it('can check if specific field is unguarded', function () {
    $form = Form::make()->unguarded(['name', 'email']);

    expect($form->isFieldUnguarded('name'))->toBeTrue()
        ->and($form->isFieldUnguarded('email'))->toBeTrue()
        ->and($form->isFieldUnguarded('password'))->toBeFalse();
});

it('considers all fields unguarded when unguarded is true', function () {
    $form = Form::make()->unguarded();

    expect($form->isFieldUnguarded('name'))->toBeTrue()
        ->and($form->isFieldUnguarded('email'))->toBeTrue()
        ->and($form->isFieldUnguarded('anything'))->toBeTrue();
});

it('can set default unguarded globally', function () {
    Form::defaultUnguarded(true);

    $form = Form::make();

    expect($form->isUnguarded())->toBeTrue();

    // Cleanup
    Form::flushState();
});

it('can set default unguarded fields globally', function () {
    Form::defaultUnguarded(['name', 'bio']);

    $form = Form::make();

    expect($form->isUnguarded())->toBeTrue()
        ->and($form->isFieldUnguarded('name'))->toBeTrue()
        ->and($form->isFieldUnguarded('bio'))->toBeTrue()
        ->and($form->isFieldUnguarded('password'))->toBeFalse();

    // Cleanup
    Form::flushState();
});

it('instance unguarded overrides default unguarded', function () {
    Form::defaultUnguarded(['name']);

    $form = Form::make()->unguarded(['email']);

    expect($form->getUnguardedFields())->toBe(['email'])
        ->and($form->isFieldUnguarded('email'))->toBeTrue()
        ->and($form->isFieldUnguarded('name'))->toBeFalse();

    // Cleanup
    Form::flushState();
});

it('can set guardWhen callback', function () {
    Form::guardWhen(fn ($model) => $model->is_protected ?? false);

    expect(Form::getGuardWhenCallback())->toBeCallable();

    // Cleanup
    Form::flushState();
});

it('shouldGuardModel returns true when no callback and not unguarded', function () {
    $form = Form::make();

    expect($form->shouldGuardModel())->toBeTrue();
});

it('shouldGuardModel returns false when unguarded', function () {
    $form = Form::make()->unguarded();

    expect($form->shouldGuardModel())->toBeFalse();
});

it('shouldGuardModel respects guardWhen callback', function () {
    Form::guardWhen(fn ($model) => $model->is_admin ?? false);

    $adminModel = new stdClass;
    $adminModel->is_admin = true;

    $userModel = new stdClass;
    $userModel->is_admin = false;

    $adminForm = Form::make()->model($adminModel);
    $userForm = Form::make()->model($userModel);

    expect($adminForm->shouldGuardModel())->toBeTrue()
        ->and($userForm->shouldGuardModel())->toBeFalse();

    // Cleanup
    Form::flushState();
});

it('shouldGuardModel returns true when no model bound', function () {
    Form::guardWhen(fn ($model) => true);

    $form = Form::make();

    expect($form->shouldGuardModel())->toBeTrue();

    // Cleanup
    Form::flushState();
});

it('can flush static state', function () {
    Form::defaultUnguarded(true);
    Form::guardWhen(fn ($model) => true);

    expect(Form::getDefaultUnguarded())->toBeTrue()
        ->and(Form::getGuardWhenCallback())->toBeCallable();

    Form::flushState();

    expect(Form::getDefaultUnguarded())->toBeNull()
        ->and(Form::getGuardWhenCallback())->toBeNull();
});

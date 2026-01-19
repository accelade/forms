<?php

declare(strict_types=1);

use Accelade\Forms\Components\PasswordInput;

describe('PasswordInput Component', function () {
    it('can be instantiated with make()', function () {
        $field = PasswordInput::make('password');

        expect($field)->toBeInstanceOf(PasswordInput::class);
        expect($field->getName())->toBe('password');
    });

    it('has default minimum length of 8', function () {
        $field = PasswordInput::make('password');

        expect($field->getMinLength())->toBe(8);
    });

    it('can set custom minimum length', function () {
        $field = PasswordInput::make('password')
            ->minLength(12);

        expect($field->getMinLength())->toBe(12);
    });

    it('can set maximum length', function () {
        $field = PasswordInput::make('password')
            ->maxLength(64);

        expect($field->getMaxLength())->toBe(64);
    });

    it('shows toggle button by default', function () {
        $field = PasswordInput::make('password');

        expect($field->shouldShowToggle())->toBeTrue();
    });

    it('can hide toggle button', function () {
        $field = PasswordInput::make('password')
            ->hideToggle();

        expect($field->shouldShowToggle())->toBeFalse();
    });

    it('can show toggle button', function () {
        $field = PasswordInput::make('password')
            ->showToggle(false)
            ->showToggle(true);

        expect($field->shouldShowToggle())->toBeTrue();
    });

    it('does not bcrypt by default', function () {
        $field = PasswordInput::make('password');

        expect($field->shouldBcrypt())->toBeFalse();
    });

    it('can enable bcrypt hashing', function () {
        $field = PasswordInput::make('password')
            ->bcrypt();

        expect($field->shouldBcrypt())->toBeTrue();
    });

    it('returns original value when bcrypt is disabled', function () {
        $field = PasswordInput::make('password');

        $processed = $field->getProcessedValue('secret123');

        expect($processed)->toBe('secret123');
    });

    it('does not require uppercase by default', function () {
        $field = PasswordInput::make('password');

        expect($field->isUppercaseRequired())->toBeFalse();
    });

    it('can require uppercase letters', function () {
        $field = PasswordInput::make('password')
            ->requireUppercase();

        expect($field->isUppercaseRequired())->toBeTrue();
    });

    it('does not require lowercase by default', function () {
        $field = PasswordInput::make('password');

        expect($field->isLowercaseRequired())->toBeFalse();
    });

    it('can require lowercase letters', function () {
        $field = PasswordInput::make('password')
            ->requireLowercase();

        expect($field->isLowercaseRequired())->toBeTrue();
    });

    it('does not require numbers by default', function () {
        $field = PasswordInput::make('password');

        expect($field->areNumbersRequired())->toBeFalse();
    });

    it('can require numbers', function () {
        $field = PasswordInput::make('password')
            ->requireNumbers();

        expect($field->areNumbersRequired())->toBeTrue();
    });

    it('does not require symbols by default', function () {
        $field = PasswordInput::make('password');

        expect($field->areSymbolsRequired())->toBeFalse();
    });

    it('can require symbols', function () {
        $field = PasswordInput::make('password')
            ->requireSymbols();

        expect($field->areSymbolsRequired())->toBeTrue();
    });

    it('can set strong password requirements', function () {
        $field = PasswordInput::make('password')
            ->strong();

        expect($field->isUppercaseRequired())->toBeTrue();
        expect($field->isLowercaseRequired())->toBeTrue();
        expect($field->areNumbersRequired())->toBeTrue();
        expect($field->areSymbolsRequired())->toBeTrue();
        expect($field->getMinLength())->toBe(12);
    });

    it('does not require confirmation by default', function () {
        $field = PasswordInput::make('password');

        expect($field->isConfirmationRequired())->toBeFalse();
        expect($field->getConfirmationField())->toBeNull();
    });

    it('can require confirmation', function () {
        $field = PasswordInput::make('password')
            ->requireConfirmation();

        expect($field->isConfirmationRequired())->toBeTrue();
        expect($field->getConfirmationField())->toBe('password_confirmation');
    });

    it('can use custom confirmation field name', function () {
        $field = PasswordInput::make('password')
            ->requireConfirmation(true, 'confirm_password');

        expect($field->getConfirmationField())->toBe('confirm_password');
    });

    it('can use confirmed() as alias', function () {
        $field = PasswordInput::make('password')
            ->confirmed();

        expect($field->isConfirmationRequired())->toBeTrue();
        expect($field->getConfirmationField())->toBe('password_confirmation');
    });

    it('has default autocomplete of new-password', function () {
        $field = PasswordInput::make('password');

        expect($field->getAutocomplete())->toBe('new-password');
    });

    it('can set custom autocomplete', function () {
        $field = PasswordInput::make('password')
            ->autocomplete('off');

        expect($field->getAutocomplete())->toBe('off');
    });

    it('can set current password autocomplete', function () {
        $field = PasswordInput::make('password')
            ->currentPassword();

        expect($field->getAutocomplete())->toBe('current-password');
    });

    it('can set new password autocomplete', function () {
        $field = PasswordInput::make('password')
            ->currentPassword()
            ->newPassword();

        expect($field->getAutocomplete())->toBe('new-password');
    });

    it('does not show strength indicator by default', function () {
        $field = PasswordInput::make('password');

        expect($field->shouldShowStrengthIndicator())->toBeFalse();
    });

    it('can show strength indicator', function () {
        $field = PasswordInput::make('password')
            ->showStrengthIndicator();

        expect($field->shouldShowStrengthIndicator())->toBeTrue();
    });

    it('does not show generate button by default', function () {
        $field = PasswordInput::make('password');

        expect($field->shouldShowGenerateButton())->toBeFalse();
    });

    it('can show generate button', function () {
        $field = PasswordInput::make('password')
            ->generateButton();

        expect($field->shouldShowGenerateButton())->toBeTrue();
        expect($field->getGeneratedLength())->toBe(16);
    });

    it('can set generate button with custom length', function () {
        $field = PasswordInput::make('password')
            ->generateButton(true, 24);

        expect($field->shouldShowGenerateButton())->toBeTrue();
        expect($field->getGeneratedLength())->toBe(24);
    });

    it('validates password length', function () {
        $field = PasswordInput::make('password')
            ->minLength(8);

        expect($field->validatePassword('short'))->toContain('Password must be at least 8 characters.');
        expect($field->validatePassword('longenough'))->toBeEmpty();
    });

    it('validates password maximum length', function () {
        $field = PasswordInput::make('password')
            ->maxLength(10);

        expect($field->validatePassword('toolongpassword'))->toContain('Password must not exceed 10 characters.');
        expect($field->validatePassword('shortpass'))->toBeEmpty();
    });

    it('validates uppercase requirement', function () {
        $field = PasswordInput::make('password')
            ->minLength(1)
            ->requireUppercase();

        expect($field->validatePassword('lowercase'))->toContain('Password must contain at least one uppercase letter.');
        expect($field->validatePassword('Uppercase'))->toBeEmpty();
    });

    it('validates lowercase requirement', function () {
        $field = PasswordInput::make('password')
            ->minLength(1)
            ->requireLowercase();

        expect($field->validatePassword('UPPERCASE'))->toContain('Password must contain at least one lowercase letter.');
        expect($field->validatePassword('lowercase'))->toBeEmpty();
    });

    it('validates number requirement', function () {
        $field = PasswordInput::make('password')
            ->minLength(1)
            ->requireNumbers();

        expect($field->validatePassword('nonumbers'))->toContain('Password must contain at least one number.');
        expect($field->validatePassword('has1number'))->toBeEmpty();
    });

    it('validates symbol requirement', function () {
        $field = PasswordInput::make('password')
            ->minLength(1)
            ->requireSymbols();

        expect($field->validatePassword('nosymbols'))->toContain('Password must contain at least one special character.');
        expect($field->validatePassword('has@symbol'))->toBeEmpty();
    });

    it('can validate multiple requirements', function () {
        $field = PasswordInput::make('password')
            ->minLength(8)
            ->requireUppercase()
            ->requireLowercase()
            ->requireNumbers()
            ->requireSymbols();

        // 'weak' has lowercase, so it fails: length, uppercase, numbers, symbols = 4 errors
        $errors = $field->validatePassword('weak');
        expect($errors)->toHaveCount(4);

        $errors = $field->validatePassword('StrongP@ss1');
        expect($errors)->toBeEmpty();
    });

    it('returns true for valid password', function () {
        $field = PasswordInput::make('password')
            ->strong();

        expect($field->isValidPassword('weak'))->toBeFalse();
        expect($field->isValidPassword('StrongP@ss123'))->toBeTrue();
    });

    it('calculates password strength', function () {
        $field = PasswordInput::make('password');

        expect($field->calculateStrength(''))->toBe(0);
        expect($field->calculateStrength('123'))->toBeLessThan(30);
        expect($field->calculateStrength('password'))->toBeLessThan(50);
        expect($field->calculateStrength('Password1!'))->toBeGreaterThan(60);
        expect($field->calculateStrength('MyStr0ng!P@ssw0rd'))->toBeGreaterThan(80);
    });

    it('returns correct strength labels', function () {
        $field = PasswordInput::make('password');

        expect($field->getStrengthLabel(10))->toBe('Very Weak');
        expect($field->getStrengthLabel(25))->toBe('Weak');
        expect($field->getStrengthLabel(45))->toBe('Fair');
        expect($field->getStrengthLabel(65))->toBe('Good');
        expect($field->getStrengthLabel(85))->toBe('Strong');
    });

    it('generates validation rules', function () {
        $field = PasswordInput::make('password')
            ->required()
            ->minLength(8)
            ->maxLength(64)
            ->requireUppercase()
            ->confirmed();

        $rules = $field->getValidationRules();

        expect($rules)->toContain('required');
        expect($rules)->toContain('string');
        expect($rules)->toContain('min:8');
        expect($rules)->toContain('max:64');
        expect($rules)->toContain('confirmed');
        expect($rules)->toContain('regex:/[A-Z]/');
    });

    it('converts to array', function () {
        $field = PasswordInput::make('password')
            ->showToggle()
            ->bcrypt()
            ->requireUppercase()
            ->showStrengthIndicator()
            ->generateButton(true, 20);

        $array = $field->toArray();

        expect($array['showToggle'])->toBeTrue();
        expect($array['bcrypt'])->toBeTrue();
        expect($array['requireUppercase'])->toBeTrue();
        expect($array['showStrengthIndicator'])->toBeTrue();
        expect($array['generateButton'])->toBeTrue();
        expect($array['generatedLength'])->toBe(20);
    });

    it('can set label', function () {
        $field = PasswordInput::make('password')
            ->label('Your Password');

        expect($field->getLabel())->toBe('Your Password');
    });

    it('can set placeholder', function () {
        $field = PasswordInput::make('password')
            ->placeholder('Enter your password');

        expect($field->getPlaceholder())->toBe('Enter your password');
    });

    it('can set hint', function () {
        $field = PasswordInput::make('password')
            ->hint('Minimum 8 characters');

        expect($field->getHint())->toBe('Minimum 8 characters');
    });

    it('can be required', function () {
        $field = PasswordInput::make('password')
            ->required();

        expect($field->isRequired())->toBeTrue();
    });

    it('can be disabled', function () {
        $field = PasswordInput::make('password')
            ->disabled();

        expect($field->isDisabled())->toBeTrue();
    });

    it('can be readonly', function () {
        $field = PasswordInput::make('password')
            ->readonly();

        expect($field->isReadonly())->toBeTrue();
    });

    it('can have autofocus', function () {
        $field = PasswordInput::make('password')
            ->autofocus();

        expect($field->hasAutofocus())->toBeTrue();
    });

    it('can set custom id', function () {
        $field = PasswordInput::make('password')
            ->id('custom-password-id');

        expect($field->getId())->toBe('custom-password-id');
    });
});

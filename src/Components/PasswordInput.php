<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasMinMax;
use Accelade\Forms\Field;
use Illuminate\Support\Facades\Hash;
use SensitiveParameter;

/**
 * Password input field component with show/hide toggle and validation.
 */
class PasswordInput extends Field
{
    use HasMinMax;

    protected bool $showToggle = true;

    protected bool $bcrypt = false;

    protected bool $requireUppercase = false;

    protected bool $requireLowercase = false;

    protected bool $requireNumbers = false;

    protected bool $requireSymbols = false;

    protected bool $requireConfirmation = false;

    protected ?string $confirmationField = null;

    protected ?string $autocomplete = 'new-password';

    protected bool $showStrengthIndicator = false;

    protected bool $generateButton = false;

    protected int $generatedLength = 16;

    /**
     * Set up the field with default validation.
     */
    protected function setUp(): void
    {
        $this->minLength(8);
    }

    /**
     * Show or hide the toggle button.
     */
    public function showToggle(bool $show = true): static
    {
        $this->showToggle = $show;

        return $this;
    }

    /**
     * Hide the toggle button.
     */
    public function hideToggle(): static
    {
        $this->showToggle = false;

        return $this;
    }

    /**
     * Check if toggle button should be shown.
     */
    public function shouldShowToggle(): bool
    {
        return $this->showToggle;
    }

    /**
     * Enable bcrypt hashing before saving.
     */
    public function bcrypt(bool $bcrypt = true): static
    {
        $this->bcrypt = $bcrypt;

        return $this;
    }

    /**
     * Check if bcrypt hashing is enabled.
     */
    public function shouldBcrypt(): bool
    {
        return $this->bcrypt;
    }

    /**
     * Hash the given value using bcrypt.
     */
    public function hashValue(string $value): string
    {
        return Hash::make($value);
    }

    /**
     * Get the processed value (hashed if bcrypt is enabled).
     */
    public function getProcessedValue(mixed $value): mixed
    {
        if ($this->bcrypt && is_string($value) && ! empty($value)) {
            return $this->hashValue($value);
        }

        return $value;
    }

    /**
     * Require uppercase letters.
     */
    public function requireUppercase(bool $require = true): static
    {
        $this->requireUppercase = $require;

        return $this;
    }

    /**
     * Check if uppercase is required.
     */
    public function isUppercaseRequired(): bool
    {
        return $this->requireUppercase;
    }

    /**
     * Require lowercase letters.
     */
    public function requireLowercase(bool $require = true): static
    {
        $this->requireLowercase = $require;

        return $this;
    }

    /**
     * Check if lowercase is required.
     */
    public function isLowercaseRequired(): bool
    {
        return $this->requireLowercase;
    }

    /**
     * Require numbers.
     */
    public function requireNumbers(bool $require = true): static
    {
        $this->requireNumbers = $require;

        return $this;
    }

    /**
     * Check if numbers are required.
     */
    public function areNumbersRequired(): bool
    {
        return $this->requireNumbers;
    }

    /**
     * Require symbols/special characters.
     */
    public function requireSymbols(bool $require = true): static
    {
        $this->requireSymbols = $require;

        return $this;
    }

    /**
     * Check if symbols are required.
     */
    public function areSymbolsRequired(): bool
    {
        return $this->requireSymbols;
    }

    /**
     * Enable all password requirements (uppercase, lowercase, numbers, symbols).
     */
    public function strong(): static
    {
        $this->requireUppercase = true;
        $this->requireLowercase = true;
        $this->requireNumbers = true;
        $this->requireSymbols = true;
        $this->minLength(12);

        return $this;
    }

    /**
     * Require password confirmation field.
     */
    public function requireConfirmation(bool $require = true, ?string $confirmationField = null): static
    {
        $this->requireConfirmation = $require;
        $this->confirmationField = $confirmationField ?? $this->name.'_confirmation';

        return $this;
    }

    /**
     * Alias for requireConfirmation.
     */
    public function confirmed(?string $confirmationField = null): static
    {
        return $this->requireConfirmation(true, $confirmationField);
    }

    /**
     * Check if confirmation is required.
     */
    public function isConfirmationRequired(): bool
    {
        return $this->requireConfirmation;
    }

    /**
     * Get the confirmation field name.
     */
    public function getConfirmationField(): ?string
    {
        return $this->confirmationField;
    }

    /**
     * Set the autocomplete attribute.
     */
    public function autocomplete(string $autocomplete): static
    {
        $this->autocomplete = $autocomplete;

        return $this;
    }

    /**
     * Set autocomplete for current password (login forms).
     */
    public function currentPassword(): static
    {
        $this->autocomplete = 'current-password';

        return $this;
    }

    /**
     * Set autocomplete for new password (registration/change forms).
     */
    public function newPassword(): static
    {
        $this->autocomplete = 'new-password';

        return $this;
    }

    /**
     * Get the autocomplete attribute.
     */
    public function getAutocomplete(): ?string
    {
        return $this->autocomplete;
    }

    /**
     * Show password strength indicator.
     */
    public function showStrengthIndicator(bool $show = true): static
    {
        $this->showStrengthIndicator = $show;

        return $this;
    }

    /**
     * Check if strength indicator should be shown.
     */
    public function shouldShowStrengthIndicator(): bool
    {
        return $this->showStrengthIndicator;
    }

    /**
     * Show generate password button.
     */
    public function generateButton(bool $show = true, int $length = 16): static
    {
        $this->generateButton = $show;
        $this->generatedLength = $length;

        return $this;
    }

    /**
     * Check if generate button should be shown.
     */
    public function shouldShowGenerateButton(): bool
    {
        return $this->generateButton;
    }

    /**
     * Get the generated password length.
     */
    public function getGeneratedLength(): int
    {
        return $this->generatedLength;
    }

    /**
     * Validate a password against the configured rules.
     */
    public function validatePassword(#[SensitiveParameter] string $password): array
    {
        $errors = [];

        if ($this->minLength !== null && strlen($password) < $this->minLength) {
            $errors[] = "Password must be at least {$this->minLength} characters.";
        }

        if ($this->maxLength !== null && strlen($password) > $this->maxLength) {
            $errors[] = "Password must not exceed {$this->maxLength} characters.";
        }

        if ($this->requireUppercase && ! preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter.';
        }

        if ($this->requireLowercase && ! preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter.';
        }

        if ($this->requireNumbers && ! preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number.';
        }

        if ($this->requireSymbols && ! preg_match('/[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?`~]/', $password)) {
            $errors[] = 'Password must contain at least one special character.';
        }

        return $errors;
    }

    /**
     * Check if a password is valid.
     */
    public function isValidPassword(#[SensitiveParameter] string $password): bool
    {
        return empty($this->validatePassword($password));
    }

    /**
     * Calculate password strength (0-100).
     */
    public function calculateStrength(#[SensitiveParameter] string $password): int
    {
        $strength = 0;

        // Length contribution (up to 30 points)
        $length = strlen($password);
        $strength += min(30, $length * 2);

        // Character variety (up to 40 points)
        if (preg_match('/[a-z]/', $password)) {
            $strength += 10;
        }
        if (preg_match('/[A-Z]/', $password)) {
            $strength += 10;
        }
        if (preg_match('/[0-9]/', $password)) {
            $strength += 10;
        }
        if (preg_match('/[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?`~]/', $password)) {
            $strength += 10;
        }

        // Bonus for mixed case and special chars together (up to 20 points)
        if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) {
            $strength += 10;
        }
        if (preg_match('/[0-9]/', $password) && preg_match('/[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?`~]/', $password)) {
            $strength += 10;
        }

        // Penalty for common patterns
        if (preg_match('/^[a-zA-Z]+$/', $password)) {
            $strength -= 10;
        }
        if (preg_match('/^[0-9]+$/', $password)) {
            $strength -= 20;
        }
        if (preg_match('/(.)\1{2,}/', $password)) {
            $strength -= 10;
        }

        return max(0, min(100, $strength));
    }

    /**
     * Get strength label based on score.
     */
    public function getStrengthLabel(int $strength): string
    {
        return match (true) {
            $strength >= 80 => 'Strong',
            $strength >= 60 => 'Good',
            $strength >= 40 => 'Fair',
            $strength >= 20 => 'Weak',
            default => 'Very Weak',
        };
    }

    /**
     * Get the validation rules for this field.
     */
    public function getValidationRules(): array
    {
        $rules = $this->rules;

        if ($this->isRequired) {
            $rules[] = 'required';
        }

        $rules[] = 'string';

        if ($this->minLength !== null) {
            $rules[] = "min:{$this->minLength}";
        }

        if ($this->maxLength !== null) {
            $rules[] = "max:{$this->maxLength}";
        }

        if ($this->requireConfirmation) {
            $rules[] = 'confirmed';
        }

        // Custom regex rules for character requirements
        if ($this->requireUppercase) {
            $rules[] = 'regex:/[A-Z]/';
        }

        if ($this->requireLowercase) {
            $rules[] = 'regex:/[a-z]/';
        }

        if ($this->requireNumbers) {
            $rules[] = 'regex:/[0-9]/';
        }

        if ($this->requireSymbols) {
            $rules[] = 'regex:/[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?`~]/';
        }

        return $rules;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.password-input';
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        return view($this->getView(), ['field' => $this])->render();
    }

    /**
     * Convert to array for JavaScript.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'showToggle' => $this->shouldShowToggle(),
            'bcrypt' => $this->shouldBcrypt(),
            'requireUppercase' => $this->isUppercaseRequired(),
            'requireLowercase' => $this->isLowercaseRequired(),
            'requireNumbers' => $this->areNumbersRequired(),
            'requireSymbols' => $this->areSymbolsRequired(),
            'requireConfirmation' => $this->isConfirmationRequired(),
            'confirmationField' => $this->getConfirmationField(),
            'autocomplete' => $this->getAutocomplete(),
            'showStrengthIndicator' => $this->shouldShowStrengthIndicator(),
            'generateButton' => $this->shouldShowGenerateButton(),
            'generatedLength' => $this->getGeneratedLength(),
            'minLength' => $this->getMinLength(),
            'maxLength' => $this->getMaxLength(),
        ]);
    }
}

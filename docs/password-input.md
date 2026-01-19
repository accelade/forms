# Password Input

The PasswordInput component provides a secure password input field with show/hide toggle, strength indicator, validation rules, and optional bcrypt hashing.

## Basic Usage

```blade
<x-accelade::password-input
    name="password"
    label="Password"
    placeholder="Enter your password"
    :required="true"
/>
```

```php
use Accelade\Forms\Components\PasswordInput;

PasswordInput::make('password')
    ->label('Password')
    ->placeholder('Enter your password')
    ->required();
```

## Show/Hide Toggle

The password input includes a toggle button to show/hide the password by default.

```blade
{{-- With toggle (default) --}}
<x-accelade::password-input
    name="password"
    :show-toggle="true"
/>

{{-- Without toggle --}}
<x-accelade::password-input
    name="password"
    :show-toggle="false"
/>
```

```php
// Hide the toggle button
PasswordInput::make('password')
    ->hideToggle();
```

## Password Strength Indicator

Show a visual indicator of password strength:

```blade
<x-accelade::password-input
    name="password"
    :show-strength-indicator="true"
/>
```

```php
PasswordInput::make('password')
    ->showStrengthIndicator();
```

## Password Requirements

### Minimum/Maximum Length

```blade
<x-accelade::password-input
    name="password"
    :min-length="8"
    :max-length="64"
/>
```

```php
PasswordInput::make('password')
    ->minLength(8)
    ->maxLength(64);
```

### Character Requirements

Require specific character types:

```blade
<x-accelade::password-input
    name="password"
    :require-uppercase="true"
    :require-lowercase="true"
    :require-numbers="true"
    :require-symbols="true"
/>
```

```php
PasswordInput::make('password')
    ->requireUppercase()
    ->requireLowercase()
    ->requireNumbers()
    ->requireSymbols();
```

### Strong Password Preset

Use the `strong()` method to enable all requirements with a minimum of 12 characters:

```php
PasswordInput::make('password')
    ->strong();

// Equivalent to:
PasswordInput::make('password')
    ->requireUppercase()
    ->requireLowercase()
    ->requireNumbers()
    ->requireSymbols()
    ->minLength(12);
```

## Password Confirmation

Require users to confirm their password:

```blade
<x-accelade::password-input
    name="password"
    :require-confirmation="true"
/>
```

```php
PasswordInput::make('password')
    ->requireConfirmation();

// Or use the alias
PasswordInput::make('password')
    ->confirmed();

// Custom confirmation field name
PasswordInput::make('password')
    ->requireConfirmation(true, 'confirm_password');
```

## Generate Password Button

Add a button to generate random strong passwords:

```blade
<x-accelade::password-input
    name="password"
    :generate-button="true"
    :generated-length="20"
/>
```

```php
PasswordInput::make('password')
    ->generateButton(true, 20);  // Enable with 20 character length
```

## Bcrypt Hashing

Automatically hash the password using bcrypt before saving:

```blade
<x-accelade::password-input
    name="password"
    :bcrypt="true"
/>
```

```php
PasswordInput::make('password')
    ->bcrypt();

// In your controller, you can use:
$hashedPassword = $field->getProcessedValue($request->password);
```

## Autocomplete

Set the autocomplete attribute for browser password managers:

```blade
{{-- For login forms --}}
<x-accelade::password-input
    name="password"
    autocomplete="current-password"
/>

{{-- For registration forms (default) --}}
<x-accelade::password-input
    name="password"
    autocomplete="new-password"
/>
```

```php
// For login forms
PasswordInput::make('password')
    ->currentPassword();

// For registration forms
PasswordInput::make('password')
    ->newPassword();
```

## States

```blade
{{-- Disabled --}}
<x-accelade::password-input
    name="password"
    :disabled="true"
/>

{{-- Required --}}
<x-accelade::password-input
    name="password"
    :required="true"
/>
```

## Complete Example

```blade
<x-accelade::password-input
    name="password"
    label="New Password"
    placeholder="Enter a strong password"
    :required="true"
    :min-length="12"
    :require-uppercase="true"
    :require-lowercase="true"
    :require-numbers="true"
    :require-symbols="true"
    :show-strength-indicator="true"
    :generate-button="true"
    :require-confirmation="true"
    hint="Your password must be at least 12 characters with mixed case, numbers, and symbols."
/>
```

```php
PasswordInput::make('password')
    ->label('New Password')
    ->placeholder('Enter a strong password')
    ->required()
    ->strong()
    ->showStrengthIndicator()
    ->generateButton()
    ->confirmed()
    ->bcrypt()
    ->hint('Your password must be at least 12 characters with mixed case, numbers, and symbols.');
```

## Validation

The component automatically generates Laravel validation rules:

```php
$field = PasswordInput::make('password')
    ->required()
    ->minLength(8)
    ->requireUppercase()
    ->confirmed();

$rules = $field->getValidationRules();
// Returns: ['required', 'string', 'min:8', 'confirmed', 'regex:/[A-Z]/']
```

### Programmatic Validation

You can also validate passwords programmatically:

```php
$field = PasswordInput::make('password')
    ->strong();

// Get validation errors
$errors = $field->validatePassword('weak');
// Returns array of error messages

// Check if valid
$isValid = $field->isValidPassword('StrongP@ss123');
// Returns true or false

// Calculate strength (0-100)
$strength = $field->calculateStrength('MyPassword123!');
// Returns integer 0-100

// Get strength label
$label = $field->getStrengthLabel($strength);
// Returns: 'Very Weak', 'Weak', 'Fair', 'Good', or 'Strong'
```

## Available Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | required | The input name attribute |
| `id` | string | name | The input id attribute |
| `label` | string | null | Label text |
| `placeholder` | string | null | Placeholder text |
| `value` | string | null | Default value |
| `required` | bool | false | Mark as required |
| `disabled` | bool | false | Disable the input |
| `readonly` | bool | false | Make readonly |
| `autofocus` | bool | false | Auto-focus on page load |
| `hint` | string | null | Helper text below input |
| `show-toggle` | bool | true | Show/hide password toggle |
| `bcrypt` | bool | false | Hash with bcrypt before saving |
| `require-uppercase` | bool | false | Require uppercase letter |
| `require-lowercase` | bool | false | Require lowercase letter |
| `require-numbers` | bool | false | Require number |
| `require-symbols` | bool | false | Require special character |
| `require-confirmation` | bool | false | Show confirmation field |
| `confirmation-field` | string | {name}_confirmation | Confirmation field name |
| `autocomplete` | string | 'new-password' | Autocomplete attribute |
| `show-strength-indicator` | bool | false | Show strength meter |
| `generate-button` | bool | false | Show generate button |
| `generated-length` | int | 16 | Generated password length |
| `min-length` | int | 8 | Minimum password length |
| `max-length` | int | null | Maximum password length |

## PHP Methods

| Method | Description |
|--------|-------------|
| `showToggle(bool)` | Show/hide the toggle button |
| `hideToggle()` | Hide the toggle button |
| `bcrypt(bool)` | Enable bcrypt hashing |
| `requireUppercase(bool)` | Require uppercase letters |
| `requireLowercase(bool)` | Require lowercase letters |
| `requireNumbers(bool)` | Require numbers |
| `requireSymbols(bool)` | Require special characters |
| `strong()` | Enable all requirements + 12 char minimum |
| `requireConfirmation(bool, ?string)` | Require password confirmation |
| `confirmed(?string)` | Alias for requireConfirmation |
| `currentPassword()` | Set autocomplete for login forms |
| `newPassword()` | Set autocomplete for new passwords |
| `showStrengthIndicator(bool)` | Show strength meter |
| `generateButton(bool, int)` | Show generate button with length |
| `minLength(int)` | Set minimum length |
| `maxLength(int)` | Set maximum length |
| `validatePassword(string)` | Get validation errors for password |
| `isValidPassword(string)` | Check if password is valid |
| `calculateStrength(string)` | Get strength score (0-100) |
| `getStrengthLabel(int)` | Get label for strength score |
| `getProcessedValue(mixed)` | Get value (hashed if bcrypt enabled) |
| `getValidationRules()` | Get Laravel validation rules array |

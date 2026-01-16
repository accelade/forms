# Text Input

The TextInput component provides a versatile text input field supporting various input types like text, email, password, number, tel, and url.

## Basic Usage

```php
use Accelade\Forms\Components\TextInput;

TextInput::make('name')
    ->label('Full Name')
    ->placeholder('Enter your name')
    ->required();
```

```blade
{{-- Blade Component --}}
<x-accelade::text-input
    name="name"
    label="Full Name"
    placeholder="Enter your name"
    required
/>
```

## Input Types

### Email Input

```php
TextInput::make('email')
    ->label('Email Address')
    ->email()
    ->required();
```

```blade
<x-accelade::text-input
    name="email"
    type="email"
    label="Email Address"
    placeholder="you@example.com"
    hint="We'll never share your email"
    required
/>
```

### Password Input

```php
TextInput::make('password')
    ->label('Password')
    ->password()
    ->required();
```

```blade
<x-accelade::text-input
    name="password"
    type="password"
    label="Password"
    placeholder="Enter password"
    minlength="8"
/>
```

### Number Input

```php
TextInput::make('age')
    ->label('Age')
    ->numeric()
    ->min(0)
    ->max(120);
```

```blade
<x-accelade::text-input
    name="age"
    type="number"
    label="Age"
    min="0"
    max="120"
/>
```

### Phone Input

```php
TextInput::make('phone')
    ->label('Phone Number')
    ->tel();
```

```blade
<x-accelade::text-input
    name="phone"
    type="tel"
    label="Phone Number"
    placeholder="+1 (555) 000-0000"
/>
```

### URL Input

```php
TextInput::make('website')
    ->label('Website')
    ->url();
```

```blade
<x-accelade::text-input
    name="website"
    type="url"
    label="Website"
    placeholder="https://example.com"
/>
```

## Prefix & Suffix (Prepend & Append)

Add prefix or suffix text to the input:

```php
TextInput::make('price')
    ->label('Price')
    ->prefix('$')        // or ->prepend('$')
    ->suffix('.00')      // or ->append('.00')
    ->numeric();

TextInput::make('domain')
    ->label('Domain')
    ->prefix('https://')
    ->suffix('.com');
```

```blade
{{-- With prefix/suffix --}}
<x-accelade::text-input
    name="price"
    type="number"
    label="Price"
    prefix="$"
    suffix="USD"
    placeholder="0.00"
/>

{{-- URL with prefix --}}
<x-accelade::text-input
    name="website"
    type="url"
    label="Website"
    prefix="https://"
    placeholder="example.com"
/>
```

## Masking

Apply input masks for formatted input:

```php
TextInput::make('phone')
    ->label('Phone')
    ->mask('(999) 999-9999');

TextInput::make('ssn')
    ->label('SSN')
    ->mask('999-99-9999');
```

```blade
<x-accelade::text-input
    name="phone"
    label="Phone"
    mask="(999) 999-9999"
/>

<x-accelade::text-input
    name="ssn"
    label="SSN"
    mask="999-99-9999"
/>
```

## Validation

```php
TextInput::make('username')
    ->label('Username')
    ->required()
    ->minLength(3)
    ->maxLength(20)
    ->alphaDash();
```

```blade
<x-accelade::text-input
    name="username"
    label="Username"
    required
    minlength="3"
    maxlength="20"
/>
```

## Autocomplete

```php
TextInput::make('email')
    ->autocomplete('email');

TextInput::make('password')
    ->autocomplete('new-password');
```

```blade
<x-accelade::text-input
    name="email"
    type="email"
    label="Email"
    autocomplete="email"
/>

<x-accelade::text-input
    name="password"
    type="password"
    label="Password"
    autocomplete="new-password"
/>
```

## Date & Time Picker

Enable date and/or time picker functionality:

```php
// Date picker
TextInput::make('birthday')
    ->label('Birthday')
    ->date();

// Date picker with options
TextInput::make('event_date')
    ->date(['showMonths' => 2]);

// Time picker
TextInput::make('start_time')
    ->label('Start Time')
    ->time();

// Date and time picker
TextInput::make('appointment')
    ->label('Appointment')
    ->date()
    ->time();

// Date range picker
TextInput::make('vacation')
    ->label('Vacation Period')
    ->date()
    ->range();
```

```blade
{{-- Date picker --}}
<x-accelade::text-input
    name="birthday"
    type="date"
    label="Birthday"
/>

{{-- Time picker --}}
<x-accelade::text-input
    name="start_time"
    type="time"
    label="Start Time"
/>

{{-- DateTime picker --}}
<x-accelade::text-input
    name="appointment"
    type="datetime-local"
    label="Appointment"
/>
```

## States

```php
// Disabled state
TextInput::make('locked')
    ->label('Locked')
    ->disabled();

// Readonly state
TextInput::make('readonly')
    ->label('Read Only')
    ->readonly();
```

```blade
{{-- Disabled state --}}
<x-accelade::text-input
    name="disabled_field"
    label="Disabled"
    value="Cannot edit"
    disabled
/>

{{-- Readonly state --}}
<x-accelade::text-input
    name="readonly_field"
    label="Readonly"
    value="Read only value"
    readonly
/>
```

## Default Formats

Set default formats globally in a service provider:

```php
use Accelade\Forms\Components\TextInput;

// In AppServiceProvider::boot()
TextInput::defaultDateFormat('d/m/Y');
TextInput::defaultTimeFormat('g:i A');
TextInput::defaultDatetimeFormat('d/m/Y g:i A');

// Set default Flatpickr options
TextInput::defaultFlatpickr([
    'locale' => 'fr',
    'firstDayOfWeek' => 1,
]);
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `email()` | Set input type to email |
| `password()` | Set input type to password |
| `numeric()` | Set input type to number |
| `integer()` | Set input type to integer |
| `tel()` | Set input type to tel |
| `url()` | Set input type to url |
| `prefix($text)` | Add prefix text |
| `prepend($text)` | Alias for prefix |
| `suffix($text)` | Add suffix text |
| `append($text)` | Alias for suffix |
| `mask($pattern)` | Apply input mask |
| `minLength($length)` | Set minimum length |
| `maxLength($length)` | Set maximum length |
| `autocomplete($value)` | Set autocomplete attribute |
| `date($options)` | Enable date picker |
| `time($options)` | Enable time picker |
| `range()` | Enable date range mode |
| `datalist($options)` | Add autocomplete suggestions |

## Static Methods

| Method | Description |
|--------|-------------|
| `defaultDateFormat($format)` | Set default date format globally |
| `defaultTimeFormat($format)` | Set default time format globally |
| `defaultDatetimeFormat($format)` | Set default datetime format globally |
| `defaultFlatpickr($options)` | Set default Flatpickr options globally |

## Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `type` | string | Input type (text, email, password, number, tel, url) |
| `placeholder` | string | Placeholder text |
| `value` | string | Default value |
| `hint` | string | Help text below input |
| `prefix` | string | Text before input |
| `suffix` | string | Text after input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |
| `readonly` | bool | Make read-only |
| `minlength` | int | Minimum length |
| `maxlength` | int | Maximum length |

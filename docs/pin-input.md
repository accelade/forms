# PIN Input

The PinInput component provides a PIN/OTP code input with individual digit fields.

## Basic Usage

```php
use Accelade\Forms\Components\PinInput;

PinInput::make('verification_code')
    ->label('Verification Code');
```

## Length

Set the number of digits:

```php
PinInput::make('otp')
    ->label('OTP')
    ->length(6);
```

## Masked Input

Hide input for security:

```php
PinInput::make('pin')
    ->label('PIN')
    ->length(4)
    ->mask();
```

## OTP Mode

Enable one-time password mode:

```php
PinInput::make('code')
    ->label('Code')
    ->otp();
```

## Input Type

Set allowed character types:

```php
// Numeric only (default)
PinInput::make('code')
    ->type('numeric');

// Letters only
PinInput::make('code')
    ->type('alpha');

// Letters and numbers
PinInput::make('code')
    ->type('alphanumeric');
```

## Alignment

Set input alignment:

```php
PinInput::make('code')
    ->label('Code')
    ->align('center');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `length($count)` | Set number of digits |
| `mask()` | Hide input characters |
| `otp()` | Enable OTP mode |
| `type($type)` | Set input type |
| `align($alignment)` | Set alignment |

## Blade Component

You can also use the PinInput as a Blade component:

```blade
{{-- Basic PIN input --}}
<x-accelade::pin-input
    name="verification_code"
    label="Verification Code"
/>

{{-- 6-digit OTP --}}
<x-accelade::pin-input
    name="otp"
    label="OTP Code"
    :length="6"
/>

{{-- 4-digit masked PIN --}}
<x-accelade::pin-input
    name="pin"
    label="Enter PIN"
    :length="4"
    mask
/>

{{-- OTP mode --}}
<x-accelade::pin-input
    name="code"
    label="One-Time Code"
    :length="6"
    otp
/>

{{-- Alphanumeric --}}
<x-accelade::pin-input
    name="serial"
    label="Serial Number"
    :length="8"
    type="alphanumeric"
/>

{{-- Required PIN --}}
<x-accelade::pin-input
    name="access_code"
    label="Access Code"
    :length="4"
    required
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string | Default value |
| `length` | int | Number of digits (default: 4) |
| `mask` | bool | Hide input characters |
| `otp` | bool | Enable OTP mode |
| `type` | string | Input type (numeric, alpha, alphanumeric) |
| `align` | string | Input alignment (left, center, right) |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

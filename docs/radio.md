# Radio

The Radio component provides radio button groups for selecting a single option from a list.

## Basic Usage

```php
use Accelade\Forms\Components\Radio;

Radio::make('gender')
    ->label('Gender')
    ->options([
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
    ]);
```

## Inline Layout

Display radio buttons horizontally:

```php
Radio::make('plan')
    ->label('Select Plan')
    ->options([
        'basic' => 'Basic',
        'pro' => 'Professional',
        'enterprise' => 'Enterprise',
    ])
    ->inline();
```

## Grid Layout

Arrange radio buttons in a grid with multiple columns:

```php
Radio::make('color')
    ->label('Select Color')
    ->options([
        'red' => 'Red',
        'blue' => 'Blue',
        'green' => 'Green',
        'yellow' => 'Yellow',
        'purple' => 'Purple',
        'orange' => 'Orange',
    ])
    ->columns(3);
```

## Default Value

Set a default selected option:

```php
Radio::make('priority')
    ->label('Priority')
    ->options([
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ])
    ->default('medium');
```

## Option Descriptions

Add descriptions to options:

```php
Radio::make('shipping')
    ->label('Shipping Method')
    ->options([
        'standard' => 'Standard Shipping',
        'express' => 'Express Shipping',
        'overnight' => 'Overnight Shipping',
    ])
    ->descriptions([
        'standard' => '5-7 business days',
        'express' => '2-3 business days',
        'overnight' => 'Next business day',
    ]);
```

## Disabled Options

Disable specific options:

```php
Radio::make('tier')
    ->options([
        'free' => 'Free',
        'pro' => 'Pro',
        'enterprise' => 'Enterprise',
    ])
    ->disableOptionWhen(fn ($value) => $value === 'enterprise');
```

## Boolean Radio

For true/false selections:

```php
Radio::make('active')
    ->label('Status')
    ->boolean()
    ->inline();
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `options($options)` | Set available options |
| `inline()` | Display horizontally |
| `columns($count)` | Arrange in grid columns |
| `default($value)` | Set default selection |
| `descriptions($descriptions)` | Add option descriptions |
| `disableOptionWhen($callback)` | Conditionally disable options |
| `boolean()` | Use Yes/No options |

## Blade Component

You can also use the Radio as a Blade component:

```blade
{{-- Basic radio group --}}
<x-accelade::radio
    name="payment"
    label="Payment Method"
    :options="[
        'card' => 'Credit Card',
        'paypal' => 'PayPal',
        'bank' => 'Bank Transfer',
    ]"
    value="card"
/>

{{-- Inline radio group --}}
<x-accelade::radio
    name="size"
    label="Size"
    :options="[
        's' => 'Small',
        'm' => 'Medium',
        'l' => 'Large',
        'xl' => 'X-Large',
    ]"
    inline
    value="m"
/>

{{-- Radio with descriptions --}}
<x-accelade::radio
    name="plan"
    label="Subscription Plan"
    :options="[
        'free' => 'Free Plan',
        'pro' => 'Pro Plan - $9/mo',
        'enterprise' => 'Enterprise - $29/mo',
    ]"
    :descriptions="[
        'free' => 'Basic features for personal use',
        'pro' => 'Advanced features for professionals',
        'enterprise' => 'Full features for teams',
    ]"
/>

{{-- Multi-column layout --}}
<x-accelade::radio
    name="color"
    label="Favorite Color"
    :options="[
        'red' => 'Red',
        'blue' => 'Blue',
        'green' => 'Green',
        'yellow' => 'Yellow',
        'purple' => 'Purple',
        'orange' => 'Orange',
    ]"
    :columns="3"
/>

{{-- Required radio --}}
<x-accelade::radio
    name="priority"
    label="Priority Level"
    :options="$priorities"
    required
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `options` | array | Options array (key => label) |
| `value` | string | Selected value |
| `descriptions` | array | Option descriptions |
| `inline` | bool | Display horizontally |
| `columns` | int | Grid columns |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable all options |

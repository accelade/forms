# Number Field

The NumberField component provides a number input with increment/decrement buttons and formatting options.

## Basic Usage

```php
use Accelade\Forms\Components\NumberField;

NumberField::make('quantity')
    ->label('Quantity')
    ->default(1);
```

## Min/Max Values

Set value boundaries:

```php
NumberField::make('age')
    ->label('Age')
    ->min(0)
    ->max(120);
```

## Step Increment

Control increment/decrement step:

```php
NumberField::make('price')
    ->label('Price')
    ->step(0.01)
    ->min(0);
```

## Prefix & Suffix

Add prefix or suffix text:

```php
NumberField::make('price')
    ->label('Price')
    ->prefix('$')
    ->step(0.01);

NumberField::make('weight')
    ->label('Weight')
    ->suffix('kg');
```

## Currency Formatting

Format as currency:

```php
NumberField::make('amount')
    ->label('Amount')
    ->currency('USD');
```

## Percentage

Format as percentage:

```php
NumberField::make('discount')
    ->label('Discount')
    ->percentage()
    ->min(0)
    ->max(1);
```

## Locale Formatting

Use locale-specific formatting:

```php
NumberField::make('salary')
    ->label('Salary')
    ->locale('de-DE')
    ->currency('EUR');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `min($value)` | Set minimum value |
| `max($value)` | Set maximum value |
| `step($value)` | Set step increment |
| `prefix($text)` | Add prefix text |
| `suffix($text)` | Add suffix text |
| `currency($code)` | Format as currency |
| `percentage()` | Format as percentage |
| `locale($locale)` | Set number locale |

## Blade Component

You can also use the NumberField as a Blade component:

```blade
{{-- Basic number field --}}
<x-accelade::number-field
    name="quantity"
    label="Quantity"
    value="1"
    min="1"
    max="99"
/>

{{-- With step --}}
<x-accelade::number-field
    name="rating"
    label="Rating"
    min="0"
    max="5"
    step="0.5"
    value="3"
/>

{{-- Currency format --}}
<x-accelade::number-field
    name="price"
    label="Price"
    prefix="$"
    suffix="USD"
    step="0.01"
    value="99.99"
/>

{{-- Percentage --}}
<x-accelade::number-field
    name="discount"
    label="Discount"
    suffix="%"
    min="0"
    max="100"
    value="10"
/>

{{-- Age range --}}
<x-accelade::number-field
    name="age"
    label="Age"
    min="18"
    max="100"
    value="25"
    hint="Must be between 18 and 100"
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | number | Default value |
| `min` | number | Minimum value |
| `max` | number | Maximum value |
| `step` | number | Step increment |
| `prefix` | string | Text before input |
| `suffix` | string | Text after input |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

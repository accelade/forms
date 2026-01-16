# Slider

The Slider component provides a range slider for selecting numeric values visually.

## Basic Usage

```php
use Accelade\Forms\Components\Slider;

Slider::make('volume')
    ->label('Volume');
```

## Min/Max Range

Set the value range:

```php
Slider::make('temperature')
    ->label('Temperature')
    ->min(-20)
    ->max(50);
```

## Step Value

Control step increments:

```php
Slider::make('brightness')
    ->label('Brightness')
    ->min(0)
    ->max(100)
    ->step(10);
```

## Show Value

Display current value:

```php
Slider::make('opacity')
    ->label('Opacity')
    ->min(0)
    ->max(100)
    ->showValue();
```

## Marks

Add marks/ticks on the slider:

```php
Slider::make('rating')
    ->label('Rating')
    ->min(1)
    ->max(5)
    ->step(1)
    ->marks([
        1 => 'Poor',
        2 => 'Fair',
        3 => 'Good',
        4 => 'Great',
        5 => 'Excellent',
    ]);
```

## Range Mode

Select a range with two handles:

```php
Slider::make('price_range')
    ->label('Price Range')
    ->min(0)
    ->max(1000)
    ->range();
```

## Custom Color

Set slider track color:

```php
Slider::make('progress')
    ->label('Progress')
    ->color('#10b981');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `min($value)` | Set minimum value |
| `max($value)` | Set maximum value |
| `step($value)` | Set step increment |
| `showValue()` | Display current value |
| `marks($marks)` | Add tick marks |
| `range()` | Enable range mode |
| `color($color)` | Set track color |

## Blade Component

You can also use the Slider as a Blade component:

```blade
{{-- Basic slider --}}
<x-accelade::slider
    name="volume"
    label="Volume"
    min="0"
    max="100"
    value="50"
/>

{{-- With step --}}
<x-accelade::slider
    name="brightness"
    label="Brightness"
    min="0"
    max="100"
    step="10"
    value="70"
/>

{{-- With marks --}}
<x-accelade::slider
    name="temperature"
    label="Temperature"
    min="0"
    max="100"
    step="25"
    :marks="[0, 25, 50, 75, 100]"
/>

{{-- Custom color --}}
<x-accelade::slider
    name="progress"
    label="Progress"
    min="0"
    max="100"
    value="80"
    color="success"
/>

{{-- With suffix display --}}
<x-accelade::slider
    name="price"
    label="Max Price"
    min="0"
    max="1000"
    step="50"
    value="500"
    prefix="$"
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
| `marks` | array | Tick marks |
| `color` | string | Track color |
| `prefix` | string | Value prefix |
| `suffix` | string | Value suffix |
| `show-value` | bool | Display current value |
| `disabled` | bool | Disable slider |

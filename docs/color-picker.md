# Color Picker

The ColorPicker component provides color selection with swatches and custom color support.

## Basic Usage

```php
use Accelade\Forms\Components\ColorPicker;

ColorPicker::make('theme_color')
    ->label('Theme Color');
```

## Color Swatches

Provide predefined color options:

```php
ColorPicker::make('brand_color')
    ->label('Brand Color')
    ->swatches([
        '#ef4444', // Red
        '#f97316', // Orange
        '#eab308', // Yellow
        '#22c55e', // Green
        '#3b82f6', // Blue
        '#8b5cf6', // Purple
    ]);
```

## Format

Set the color format:

```php
ColorPicker::make('color')
    ->label('Color')
    ->format('hsl'); // hex, rgb, hsl, rgba
```

## Alpha Channel

Enable alpha/transparency:

```php
ColorPicker::make('overlay')
    ->label('Overlay Color')
    ->rgba();
```

## Default Color

Set a default color:

```php
ColorPicker::make('accent')
    ->label('Accent Color')
    ->default('#3b82f6');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `swatches($colors)` | Set color swatches |
| `format($format)` | Set output format |
| `rgba()` | Enable alpha channel |
| `default($color)` | Set default color |

## Blade Component

You can also use the ColorPicker as a Blade component:

```blade
{{-- Basic color picker --}}
<x-accelade::color-picker
    name="brand_color"
    label="Brand Color"
    value="#6366f1"
/>

{{-- With preset swatches --}}
<x-accelade::color-picker
    name="theme_color"
    label="Theme Color"
    :swatches="[
        '#ef4444', '#f97316', '#eab308',
        '#22c55e', '#3b82f6', '#8b5cf6',
        '#ec4899', '#64748b',
    ]"
/>

{{-- With alpha channel --}}
<x-accelade::color-picker
    name="overlay"
    label="Overlay Color"
    rgba
/>

{{-- Required color --}}
<x-accelade::color-picker
    name="accent"
    label="Accent Color"
    required
/>

{{-- Disabled color picker --}}
<x-accelade::color-picker
    name="locked_color"
    label="Locked Color"
    value="#3b82f6"
    disabled
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string | Default color value |
| `swatches` | array | Preset color swatches |
| `format` | string | Output format (hex, rgb, hsl, rgba) |
| `rgba` | bool | Enable alpha channel |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

# Toggle

The Toggle component provides an on/off switch for boolean values, and ToggleButtons for selecting from button options.

## Basic Usage

```php
use Accelade\Forms\Components\Toggle;

Toggle::make('notifications')
    ->label('Enable Notifications');
```

## Default State

Set the default toggle state:

```php
Toggle::make('dark_mode')
    ->label('Dark Mode')
    ->default(true);
```

## On/Off Labels

Add custom on/off labels:

```php
Toggle::make('status')
    ->label('Status')
    ->onLabel('Active')
    ->offLabel('Inactive');
```

## Colors

Customize toggle colors:

```php
Toggle::make('feature')
    ->label('Feature Flag')
    ->onColor('success')
    ->offColor('danger');
```

## Inline Layout

Display toggle inline with label:

```php
Toggle::make('marketing')
    ->label('Receive marketing emails')
    ->inline();
```

## Toggle Buttons

For selecting from button options:

```php
use Accelade\Forms\Components\ToggleButtons;

ToggleButtons::make('view')
    ->label('View Mode')
    ->options([
        'grid' => 'Grid',
        'list' => 'List',
        'table' => 'Table',
    ]);
```

## Toggle Buttons with Icons

Add icons to toggle buttons:

```php
ToggleButtons::make('alignment')
    ->label('Text Alignment')
    ->options([
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ])
    ->icons([
        'left' => 'align-left',
        'center' => 'align-center',
        'right' => 'align-right',
    ]);
```

## Grouped Toggle Buttons

Display as a connected button group:

```php
ToggleButtons::make('size')
    ->options([
        'sm' => 'S',
        'md' => 'M',
        'lg' => 'L',
        'xl' => 'XL',
    ])
    ->grouped();
```

## Methods Reference

### Toggle

| Method | Description |
|--------|-------------|
| `default($value)` | Set default state |
| `onLabel($label)` | Set on state label |
| `offLabel($label)` | Set off state label |
| `onColor($color)` | Set on state color |
| `offColor($color)` | Set off state color |
| `inline()` | Display inline |

### ToggleButtons

| Method | Description |
|--------|-------------|
| `options($options)` | Set button options |
| `icons($icons)` | Add icons to buttons |
| `colors($colors)` | Set button colors |
| `grouped()` | Display as connected group |

## Blade Component

You can also use the Toggle and ToggleButtons as Blade components:

### Toggle

```blade
{{-- Basic toggle --}}
<x-accelade::toggle
    name="notifications"
    label="Enable Notifications"
/>

{{-- Default on --}}
<x-accelade::toggle
    name="dark_mode"
    label="Dark Mode"
    :default="true"
/>

{{-- With on/off labels --}}
<x-accelade::toggle
    name="status"
    label="Status"
    on-label="Active"
    off-label="Inactive"
/>

{{-- Custom colors --}}
<x-accelade::toggle
    name="feature"
    label="Feature Flag"
    on-color="success"
    off-color="danger"
/>

{{-- Inline layout --}}
<x-accelade::toggle
    name="marketing"
    label="Receive marketing emails"
    inline
/>
```

### Toggle Buttons

```blade
{{-- Basic toggle buttons --}}
<x-accelade::toggle-buttons
    name="view"
    label="View Mode"
    :options="[
        'grid' => 'Grid',
        'list' => 'List',
        'table' => 'Table',
    ]"
/>

{{-- With icons --}}
<x-accelade::toggle-buttons
    name="alignment"
    label="Text Alignment"
    :options="[
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ]"
    :icons="[
        'left' => 'align-left',
        'center' => 'align-center',
        'right' => 'align-right',
    ]"
/>

{{-- Grouped buttons --}}
<x-accelade::toggle-buttons
    name="size"
    label="Size"
    :options="[
        'sm' => 'S',
        'md' => 'M',
        'lg' => 'L',
        'xl' => 'XL',
    ]"
    grouped
/>

{{-- Required --}}
<x-accelade::toggle-buttons
    name="priority"
    label="Priority"
    :options="$priorities"
    required
/>
```

### Blade Component Attributes

#### Toggle

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `default` | bool | Default state |
| `on-label` | string | On state label |
| `off-label` | string | Off state label |
| `on-color` | string | On state color |
| `off-color` | string | Off state color |
| `inline` | bool | Display inline |
| `hint` | string | Help text below input |
| `disabled` | bool | Disable toggle |

#### ToggleButtons

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `options` | array | Button options |
| `value` | string | Selected value |
| `icons` | array | Button icons |
| `colors` | array | Button colors |
| `grouped` | bool | Display as connected group |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable buttons |

# Toggle Buttons

Toggle Buttons provide a button group interface for selecting single or multiple options. They're ideal for settings, view modes, and status selection.

## Basic Usage

```php
use Accelade\Forms\Components\ToggleButtons;

ToggleButtons::make('size')
    ->label('Size')
    ->options([
        'small' => 'Small',
        'medium' => 'Medium',
        'large' => 'Large',
    ]);
```

## With Icons

Add icons to make options more visual:

```php
ToggleButtons::make('view')
    ->options([
        'list' => 'List',
        'grid' => 'Grid',
        'columns' => 'Columns',
    ])
    ->icons([
        'list' => '<svg>...</svg>',
        'grid' => '<svg>...</svg>',
        'columns' => '<svg>...</svg>',
    ]);
```

## Grouped Style

Use grouped style for a compact button bar:

```php
ToggleButtons::make('alignment')
    ->grouped()
    ->options([
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ]);
```

## Custom Colors

Apply different colors to each option:

```php
ToggleButtons::make('status')
    ->options([
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ])
    ->colors([
        'pending' => 'amber',
        'approved' => 'emerald',
        'rejected' => 'red',
    ]);
```

## Available Methods

| Method | Description |
|--------|-------------|
| `options(array)` | Set available options |
| `icons(array)` | Set icons for each option |
| `colors(array)` | Set colors for each option |
| `grouped()` | Enable grouped/connected style |
| `default(string)` | Set default selected value |
| `disabled()` | Disable the field |

## Blade Component

You can also use the ToggleButtons as a Blade component:

```blade
{{-- Basic toggle buttons --}}
<x-accelade::toggle-buttons
    name="size"
    label="Size"
    :options="[
        'small' => 'Small',
        'medium' => 'Medium',
        'large' => 'Large',
    ]"
/>

{{-- With icons --}}
<x-accelade::toggle-buttons
    name="view"
    label="View Mode"
    :options="[
        'list' => 'List',
        'grid' => 'Grid',
        'columns' => 'Columns',
    ]"
    :icons="[
        'list' => 'list-icon',
        'grid' => 'grid-icon',
        'columns' => 'columns-icon',
    ]"
/>

{{-- Grouped style --}}
<x-accelade::toggle-buttons
    name="alignment"
    label="Alignment"
    :options="[
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ]"
    grouped
/>

{{-- With custom colors --}}
<x-accelade::toggle-buttons
    name="status"
    label="Status"
    :options="[
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ]"
    :colors="[
        'pending' => 'amber',
        'approved' => 'emerald',
        'rejected' => 'red',
    ]"
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

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `options` | array | Button options (key => label) |
| `value` | string | Selected value |
| `icons` | array | Button icons |
| `colors` | array | Button colors |
| `grouped` | bool | Display as connected group |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable buttons |

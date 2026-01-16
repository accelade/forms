# Checkbox

The Checkbox component provides a single checkbox for boolean values, and CheckboxList for selecting multiple options.

## Basic Usage

```php
use Accelade\Forms\Components\Checkbox;

Checkbox::make('terms')
    ->label('I agree to the terms and conditions')
    ->required();
```

## Inline Label

Display label inline with the checkbox:

```php
Checkbox::make('newsletter')
    ->label('Subscribe to newsletter')
    ->inline();
```

## Default Value

Set a default checked state:

```php
Checkbox::make('notifications')
    ->label('Enable notifications')
    ->default(true);
```

## Custom Values

By default, the checkbox submits `true` when checked. You can customize both checked and unchecked values:

```php
Checkbox::make('newsletter')
    ->label('Subscribe to newsletter')
    ->checkedValue('yes')
    ->uncheckedValue('no');
```

### Splade-Compatible Aliases

For Splade compatibility, you can use `value()` and `falseValue()`:

```php
Checkbox::make('status')
    ->value('active')
    ->falseValue('inactive');
```

When a `falseValue` is set, a hidden input is automatically rendered so the unchecked value is submitted with the form.

## Checkbox List

For selecting multiple options from a list:

```php
use Accelade\Forms\Components\CheckboxList;

CheckboxList::make('roles')
    ->label('User Roles')
    ->options([
        'admin' => 'Administrator',
        'editor' => 'Editor',
        'viewer' => 'Viewer',
    ]);
```

## Inline Checkbox List

Display options in a horizontal row:

```php
CheckboxList::make('features')
    ->label('Features')
    ->options([
        'dark_mode' => 'Dark Mode',
        'notifications' => 'Notifications',
        'analytics' => 'Analytics',
    ])
    ->inline();
```

## Grid Layout

Arrange checkboxes in columns:

```php
CheckboxList::make('permissions')
    ->label('Permissions')
    ->options($permissions)
    ->columns(3);
```

## Bulk Actions

Enable select all / deselect all:

```php
CheckboxList::make('items')
    ->options($items)
    ->bulkToggleable();
```

## Option Descriptions

Add descriptions below each option:

```php
CheckboxList::make('plans')
    ->options([
        'basic' => 'Basic Plan',
        'pro' => 'Pro Plan',
        'enterprise' => 'Enterprise Plan',
    ])
    ->descriptions([
        'basic' => 'For individuals and small teams',
        'pro' => 'For growing businesses',
        'enterprise' => 'For large organizations',
    ]);
```

## Eloquent Relations

For BelongsToMany or MorphToMany relationships:

```php
CheckboxList::make('tags')
    ->options(Tag::pluck('name', 'id'))
    ->relation();
```

The `relation()` method uses the field name as the relation name by default. You can specify a different relation name:

```php
CheckboxList::make('tag_ids')
    ->options(Tag::pluck('name', 'id'))
    ->relation('tags');
```

## Methods Reference

### Checkbox

| Method | Description |
|--------|-------------|
| `inline()` | Display label inline |
| `default($value)` | Set default checked state |
| `checkedValue($value)` | Value when checked (default: `true`) |
| `uncheckedValue($value)` | Value when unchecked (default: `false`) |
| `value($value)` | Alias for checkedValue() |
| `falseValue($value)` | Alias for uncheckedValue() |

### CheckboxList

| Method | Description |
|--------|-------------|
| `options($options)` | Set available options |
| `inline()` | Display options horizontally |
| `columns($count)` | Arrange in grid columns |
| `bulkToggleable()` | Enable bulk toggle actions |
| `descriptions($array)` | Set descriptions for options |
| `relation($name)` | Set Eloquent relation name |
| `searchable()` | Enable option search |

## Blade Component

You can also use the Checkbox and CheckboxList as Blade components:

### Single Checkbox

```blade
{{-- Basic checkbox --}}
<x-accelade::checkbox
    name="terms"
    label="I agree to the terms and conditions"
    required
/>

{{-- Checkbox with default checked --}}
<x-accelade::checkbox
    name="newsletter"
    label="Subscribe to newsletter"
    checked
/>

{{-- Checkbox with custom values --}}
<x-accelade::checkbox
    name="is_active"
    label="Active Status"
    value="yes"
    false-value="no"
/>

{{-- Disabled checkbox --}}
<x-accelade::checkbox
    name="locked"
    label="This option is locked"
    disabled
/>
```

### Checkbox List

```blade
{{-- Basic checkbox list --}}
<x-accelade::checkbox-list
    name="interests"
    label="Select your interests"
    :options="[
        'tech' => 'Technology',
        'sports' => 'Sports',
        'music' => 'Music',
        'travel' => 'Travel',
    ]"
/>

{{-- Inline checkbox list --}}
<x-accelade::checkbox-list
    name="notifications"
    label="Notifications"
    :options="[
        'email' => 'Email',
        'sms' => 'SMS',
        'push' => 'Push',
    ]"
    inline
/>

{{-- Grid layout --}}
<x-accelade::checkbox-list
    name="permissions"
    label="Permissions"
    :options="$permissions"
    :columns="3"
/>

{{-- With descriptions --}}
<x-accelade::checkbox-list
    name="plans"
    label="Select Plans"
    :options="[
        'basic' => 'Basic Plan',
        'pro' => 'Pro Plan',
    ]"
    :descriptions="[
        'basic' => 'For individuals',
        'pro' => 'For teams',
    ]"
/>
```

### Blade Component Attributes

#### Checkbox

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string | Value when checked |
| `false-value` | string | Value when unchecked |
| `checked` | bool | Default checked state |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable checkbox |

#### CheckboxList

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `options` | array | Options array |
| `value` | array | Selected values |
| `inline` | bool | Display horizontally |
| `columns` | int | Grid columns |
| `descriptions` | array | Option descriptions |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable all options |

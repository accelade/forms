# Repeater

The Repeater component allows creating repeatable field groups for dynamic forms.

## Basic Usage

```php
use Accelade\Forms\Components\Repeater;
use Accelade\Forms\Components\TextInput;

Repeater::make('addresses')
    ->label('Addresses')
    ->schema([
        TextInput::make('street')->label('Street'),
        TextInput::make('city')->label('City'),
        TextInput::make('zip')->label('ZIP Code'),
    ]);
```

## Min/Max Items

Control the number of items:

```php
Repeater::make('contacts')
    ->label('Emergency Contacts')
    ->schema([...])
    ->minItems(1)
    ->maxItems(5);
```

## Default Items

Set initial number of empty items:

```php
Repeater::make('items')
    ->label('Line Items')
    ->schema([...])
    ->defaultItems(3);
```

## Collapsible Items

Make items collapsible:

```php
Repeater::make('sections')
    ->label('Sections')
    ->schema([...])
    ->collapsible();
```

## Cloneable Items

Allow cloning items:

```php
Repeater::make('products')
    ->label('Products')
    ->schema([...])
    ->cloneable();
```

## Reorderable

Enable drag-and-drop reordering:

```php
Repeater::make('tasks')
    ->label('Tasks')
    ->schema([...])
    ->reorderable();
```

## Custom Add Button

Customize the add button:

```php
Repeater::make('features')
    ->label('Features')
    ->schema([...])
    ->addActionLabel('Add Feature');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `schema($fields)` | Set nested field schema |
| `minItems($count)` | Set minimum items |
| `maxItems($count)` | Set maximum items |
| `defaultItems($count)` | Set initial items |
| `collapsible()` | Make items collapsible |
| `collapsed()` | Start items collapsed |
| `cloneable()` | Enable item cloning |
| `reorderable()` | Enable reordering |
| `addable($condition)` | Control add capability |
| `deletable($condition)` | Control delete capability |
| `addActionLabel($label)` | Set add button text |

## Blade Component

You can also use the Repeater as a Blade component:

```blade
{{-- Basic repeater --}}
<x-accelade::repeater
    name="addresses"
    label="Addresses"
>
    <x-accelade::text-input name="street" label="Street" />
    <x-accelade::text-input name="city" label="City" />
    <x-accelade::text-input name="zip" label="ZIP Code" />
</x-accelade::repeater>

{{-- With min/max items --}}
<x-accelade::repeater
    name="contacts"
    label="Emergency Contacts"
    :min-items="1"
    :max-items="5"
>
    <x-accelade::text-input name="name" label="Name" />
    <x-accelade::text-input name="phone" label="Phone" />
</x-accelade::repeater>

{{-- Collapsible items --}}
<x-accelade::repeater
    name="sections"
    label="Sections"
    collapsible
>
    <x-accelade::text-input name="title" label="Title" />
    <x-accelade::textarea name="content" label="Content" />
</x-accelade::repeater>

{{-- Reorderable --}}
<x-accelade::repeater
    name="tasks"
    label="Tasks"
    reorderable
>
    <x-accelade::text-input name="task" label="Task" />
    <x-accelade::checkbox name="completed" label="Completed" />
</x-accelade::repeater>

{{-- Custom add button --}}
<x-accelade::repeater
    name="features"
    label="Features"
    add-action-label="Add Feature"
>
    <x-accelade::text-input name="feature" label="Feature" />
</x-accelade::repeater>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `min-items` | int | Minimum items |
| `max-items` | int | Maximum items |
| `default-items` | int | Initial empty items |
| `collapsible` | bool | Make items collapsible |
| `collapsed` | bool | Start items collapsed |
| `cloneable` | bool | Enable item cloning |
| `reorderable` | bool | Enable drag-and-drop |
| `addable` | bool | Allow adding items |
| `deletable` | bool | Allow deleting items |
| `add-action-label` | string | Add button text |
| `hint` | string | Help text below input |

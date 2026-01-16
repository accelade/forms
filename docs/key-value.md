# Key Value

The KeyValue component provides a key-value pair editor for structured data.

## Basic Usage

```php
use Accelade\Forms\Components\KeyValue;

KeyValue::make('metadata')
    ->label('Metadata');
```

## Custom Labels

Set custom column labels:

```php
KeyValue::make('headers')
    ->label('HTTP Headers')
    ->keyLabel('Header Name')
    ->valueLabel('Header Value');
```

## Placeholders

Add placeholder text:

```php
KeyValue::make('env')
    ->label('Environment Variables')
    ->keyPlaceholder('VARIABLE_NAME')
    ->valuePlaceholder('value');
```

## Actions

Control add/delete/reorder capabilities:

```php
KeyValue::make('config')
    ->label('Configuration')
    ->addable(true)
    ->deletable(true)
    ->reorderable(true);
```

## Read-only

Disable editing:

```php
KeyValue::make('info')
    ->label('System Info')
    ->addable(false)
    ->deletable(false);
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `keyLabel($label)` | Set key column label |
| `valueLabel($label)` | Set value column label |
| `keyPlaceholder($text)` | Set key placeholder |
| `valuePlaceholder($text)` | Set value placeholder |
| `addable($condition)` | Enable/disable adding rows |
| `deletable($condition)` | Enable/disable deleting rows |
| `reorderable($condition)` | Enable/disable reordering |

## Blade Component

You can also use the KeyValue as a Blade component:

```blade
{{-- Basic key-value --}}
<x-accelade::key-value
    name="metadata"
    label="Metadata"
/>

{{-- With custom labels --}}
<x-accelade::key-value
    name="headers"
    label="HTTP Headers"
    key-label="Header Name"
    value-label="Header Value"
/>

{{-- With placeholders --}}
<x-accelade::key-value
    name="env"
    label="Environment Variables"
    key-placeholder="VARIABLE_NAME"
    value-placeholder="value"
/>

{{-- With default values --}}
<x-accelade::key-value
    name="config"
    label="Configuration"
    :value="[
        'APP_DEBUG' => 'true',
        'CACHE_DRIVER' => 'redis',
    ]"
/>

{{-- Read-only mode --}}
<x-accelade::key-value
    name="info"
    label="System Info"
    :addable="false"
    :deletable="false"
/>

{{-- Reorderable --}}
<x-accelade::key-value
    name="settings"
    label="Settings"
    reorderable
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | array | Default key-value pairs |
| `key-label` | string | Key column header |
| `value-label` | string | Value column header |
| `key-placeholder` | string | Key input placeholder |
| `value-placeholder` | string | Value input placeholder |
| `addable` | bool | Allow adding rows |
| `deletable` | bool | Allow deleting rows |
| `reorderable` | bool | Allow reordering rows |
| `hint` | string | Help text below input |
| `disabled` | bool | Disable all inputs |

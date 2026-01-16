# Textarea

The Textarea component provides a multi-line text input with auto-resize capabilities.

## Basic Usage

```php
use Accelade\Forms\Components\Textarea;

Textarea::make('description')
    ->label('Description')
    ->placeholder('Enter a description...');
```

```blade
{{-- Blade Component --}}
<x-accelade::textarea
    name="description"
    label="Description"
    placeholder="Enter a description..."
    rows="4"
/>
```

## Rows

Set the initial number of visible rows:

```php
Textarea::make('bio')
    ->label('Biography')
    ->rows(5);
```

```blade
<x-accelade::textarea
    name="bio"
    label="Biography"
    rows="5"
/>
```

## Auto-resize

Enable auto-resize based on content:

```php
Textarea::make('notes')
    ->label('Notes')
    ->autosize();
```

```blade
<x-accelade::textarea
    name="content"
    label="Content"
    autosize
    min-rows="3"
    max-rows="10"
/>
```

## Default Autosize

Set autosize as the default for all textarea instances globally:

```php
use Accelade\Forms\Components\Textarea;

// In AppServiceProvider::boot()
Textarea::defaultAutosize();
```

Now all textarea fields will have autosize enabled by default:

```php
// This will have autosize enabled
Textarea::make('bio');

// Override for specific field
Textarea::make('code')->autosize(false);
```

## Length Constraints

Set min and max character length:

```php
Textarea::make('summary')
    ->label('Summary')
    ->minLength(10)
    ->maxLength(500);
```

```blade
<x-accelade::textarea
    name="bio"
    label="Bio"
    placeholder="Write a short bio..."
    rows="3"
    maxlength="200"
    hint="Maximum 200 characters"
/>
```

## States

```php
// Required
Textarea::make('message')
    ->label('Message')
    ->required();

// Disabled
Textarea::make('notes')
    ->label('Notes')
    ->disabled();
```

```blade
{{-- Required textarea --}}
<x-accelade::textarea
    name="message"
    label="Message"
    placeholder="Your message..."
    rows="5"
    required
/>

{{-- Disabled textarea --}}
<x-accelade::textarea
    name="readonly_content"
    label="Notes"
    value="This content cannot be edited"
    disabled
/>
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `rows($count)` | Set visible rows |
| `cols($count)` | Set visible columns |
| `autosize()` | Enable auto-resize |
| `minLength($length)` | Set minimum character length |
| `maxLength($length)` | Set maximum character length |

## Static Methods

| Method | Description |
|--------|-------------|
| `defaultAutosize()` | Set autosize as default for all instances |

## Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `placeholder` | string | Placeholder text |
| `value` | string | Default value |
| `hint` | string | Help text below input |
| `rows` | int | Number of visible rows |
| `cols` | int | Number of visible columns |
| `autosize` | bool | Enable auto-resize |
| `min-rows` | int | Minimum rows for autosize |
| `max-rows` | int | Maximum rows for autosize |
| `minlength` | int | Minimum character length |
| `maxlength` | int | Maximum character length |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

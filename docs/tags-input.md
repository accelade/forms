# Tags Input

The TagsInput component provides a tag/chip input for entering multiple values.

## Basic Usage

```php
use Accelade\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->label('Tags');
```

## Suggestions

Provide autocomplete suggestions:

```php
TagsInput::make('skills')
    ->label('Skills')
    ->suggestions([
        'PHP', 'JavaScript', 'Python',
        'Laravel', 'Vue', 'React',
    ]);
```

## Separator

Set the tag separator:

```php
TagsInput::make('emails')
    ->label('Email Addresses')
    ->separator(',');
```

## Maximum Tags

Limit the number of tags:

```php
TagsInput::make('categories')
    ->label('Categories')
    ->maxTags(5);
```

## Placeholder

Set placeholder text:

```php
TagsInput::make('keywords')
    ->label('Keywords')
    ->placeholder('Type and press Enter...');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `suggestions($items)` | Set autocomplete suggestions |
| `separator($char)` | Set tag separator |
| `maxTags($count)` | Limit tag count |
| `placeholder($text)` | Set placeholder text |

## Blade Component

You can also use the TagsInput as a Blade component:

```blade
{{-- Basic tags input --}}
<x-accelade::tags-input
    name="tags"
    label="Tags"
    placeholder="Add a tag..."
/>

{{-- With suggestions --}}
<x-accelade::tags-input
    name="skills"
    label="Skills"
    :suggestions="['PHP', 'JavaScript', 'Python', 'Laravel', 'Vue', 'React']"
    placeholder="Type to search..."
/>

{{-- With maximum limit --}}
<x-accelade::tags-input
    name="categories"
    label="Categories"
    :max-tags="5"
    hint="Maximum 5 tags"
/>

{{-- With default values --}}
<x-accelade::tags-input
    name="technologies"
    label="Technologies"
    :value="['Laravel', 'Vue.js']"
/>

{{-- Required tags --}}
<x-accelade::tags-input
    name="keywords"
    label="Keywords"
    placeholder="Enter keywords..."
    required
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | array | Default tags |
| `suggestions` | array | Autocomplete suggestions |
| `max-tags` | int | Maximum tag count |
| `separator` | string | Tag separator character |
| `placeholder` | string | Placeholder text |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

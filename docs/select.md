# Select

The Select component provides a dropdown selection with support for searching, multiple selection, and grouped options.

## Basic Usage

```php
use Accelade\Forms\Components\Select;

Select::make('country')
    ->label('Country')
    ->options([
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ]);
```

```blade
{{-- Blade Component --}}
<x-accelade::select
    name="country"
    label="Country"
    placeholder="Select a country..."
    :options="[
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ]"
/>
```

## Searchable Select

Enable search functionality for large option lists:

```php
Select::make('country')
    ->label('Country')
    ->options($countries)
    ->searchable();
```

```blade
<x-accelade::select
    name="user_id"
    label="User"
    :options="$users"
    searchable
/>
```

## Multiple Selection

Allow selecting multiple options:

```php
Select::make('skills')
    ->label('Skills')
    ->options([
        'php' => 'PHP',
        'js' => 'JavaScript',
        'python' => 'Python',
        'go' => 'Go',
    ])
    ->multiple();
```

```blade
<x-accelade::select
    name="skills"
    label="Skills"
    :options="[
        'php' => 'PHP',
        'js' => 'JavaScript',
        'python' => 'Python',
    ]"
    multiple
    hint="Hold Ctrl/Cmd to select multiple"
/>
```

## Empty/Placeholder Option

Add a placeholder option:

```php
Select::make('category')
    ->label('Category')
    ->options($categories)
    ->emptyOptionLabel('Select a category...');
```

```blade
<x-accelade::select
    name="category"
    label="Category"
    :options="$categories"
    placeholder="Select a category..."
/>
```

## Grouped Options

Group options into categories:

```php
Select::make('timezone')
    ->label('Timezone')
    ->options([
        'America' => [
            'america/new_york' => 'New York',
            'america/los_angeles' => 'Los Angeles',
        ],
        'Europe' => [
            'europe/london' => 'London',
            'europe/paris' => 'Paris',
        ],
    ]);
```

```blade
<x-accelade::select
    name="timezone"
    label="Timezone"
    placeholder="Select timezone..."
    :options="[
        'Americas' => [
            'est' => 'Eastern Time (EST)',
            'cst' => 'Central Time (CST)',
            'pst' => 'Pacific Time (PST)',
        ],
        'Europe' => [
            'gmt' => 'Greenwich Mean Time (GMT)',
            'cet' => 'Central European Time (CET)',
        ],
    ]"
/>
```

## Dynamic Options

Load options from a relationship or callback:

```php
Select::make('user_id')
    ->label('User')
    ->options(fn () => User::pluck('name', 'id'))
    ->searchable();
```

## Preloaded Options

Preload options on page load for better UX:

```php
Select::make('category')
    ->options($categories)
    ->preload();
```

## Native Select

Use native HTML select instead of custom dropdown:

```php
Select::make('country')
    ->options($countries)
    ->native();
```

## Choices.js Integration

Enable Choices.js for enhanced select functionality:

```php
Select::make('country')
    ->options($countries)
    ->choices();
```

With custom Choices.js options:

```php
Select::make('country')
    ->options($countries)
    ->choices([
        'searchEnabled' => true,
        'removeItemButton' => true,
    ]);
```

```blade
<x-accelade::select
    name="tags"
    label="Tags"
    :options="$tags"
    multiple
    :choices="['removeItemButton' => true, 'searchEnabled' => true]"
/>
```

### Default Choices Options

Set default Choices.js options globally for all select instances:

```php
use Accelade\Forms\Components\Select;

// In AppServiceProvider::boot()
Select::defaultChoices([
    'removeItemButton' => true,
    'placeholderValue' => 'Select an option',
]);
```

Now all select fields with `->choices()` enabled will use these defaults:

```php
// Will use the default options
Select::make('country')->choices();

// Override for specific field
Select::make('tags')->choices(['searchEnabled' => false]);
```

## Object Collections

When working with collections of objects (like Eloquent models), specify which properties to use for labels and values:

```php
Select::make('user_id')
    ->options(User::all())
    ->optionLabel('name')
    ->optionValue('id');
```

## Remote Options (Async Loading)

Load options from a remote URL asynchronously:

```php
Select::make('country')
    ->remoteUrl('/api/countries')
    ->optionLabel('name')
    ->optionValue('code');
```

### Remote Root Path

Extract options from nested response data:

```php
Select::make('country')
    ->remoteUrl('/api/countries')
    ->remoteRoot('data.countries')
    ->optionLabel('name')
    ->optionValue('code');
```

### Auto-Select First Remote Option

Automatically select the first option when remote data loads:

```php
Select::make('country')
    ->remoteUrl('/api/countries')
    ->selectFirstRemoteOption();
```

### Reset on URL Change

Reset the selection when the remote URL changes (useful for dependent selects):

```php
Select::make('city')
    ->remoteUrl('/api/cities?country=' . $selectedCountry)
    ->resetOnNewRemoteUrl();
```

## Eloquent Relations

For BelongsToMany or MorphToMany relationships:

```php
Select::make('tags')
    ->relation('tags')
    ->options(Tag::pluck('name', 'id'));
```

The `relation()` method automatically enables multiple selection.

## Custom Option Label Callback

Use a callback to generate option labels:

```php
Select::make('user_id')
    ->options(User::all()->pluck('id'))
    ->getOptionLabelUsing(fn ($id) => User::find($id)?->full_name);
```

## States

```php
// Required
Select::make('category')
    ->label('Category')
    ->options($categories)
    ->required();

// Disabled
Select::make('locked')
    ->label('Locked')
    ->options($options)
    ->disabled();
```

```blade
{{-- Required select --}}
<x-accelade::select
    name="category"
    label="Category"
    :options="$categories"
    required
/>

{{-- Disabled select --}}
<x-accelade::select
    name="locked"
    label="Locked"
    :options="$options"
    disabled
/>
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `options($options)` | Set available options |
| `searchable()` | Enable search functionality |
| `multiple()` | Allow multiple selection |
| `emptyOptionLabel($label)` | Set placeholder label |
| `disableEmptyOption()` | Remove empty/placeholder option |
| `preload()` | Preload options on mount |
| `native()` | Use native HTML select |
| `choices($options)` | Enable Choices.js with optional config |
| `optionLabel($property)` | Property name for option labels |
| `optionValue($property)` | Property name for option values |
| `remoteUrl($url)` | URL for async option loading |
| `remoteRoot($path)` | Path to extract options from response |
| `selectFirstRemoteOption()` | Auto-select first remote option |
| `resetOnNewRemoteUrl()` | Reset selection when URL changes |
| `relation($name)` | Set Eloquent relation name |
| `getOptionLabelUsing($callback)` | Custom label callback |
| `getOptionsUsing($callback)` | Dynamic options callback |

## Static Methods

| Method | Description |
|--------|-------------|
| `defaultChoices($options)` | Set default Choices.js options globally |

## Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `placeholder` | string | Placeholder/empty option text |
| `options` | array | Options array (key => label) |
| `value` | mixed | Selected value(s) |
| `hint` | string | Help text below select |
| `multiple` | bool | Allow multiple selection |
| `searchable` | bool | Enable search functionality |
| `choices` | array | Choices.js configuration |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable select |

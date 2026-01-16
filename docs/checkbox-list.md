# Checkbox List

The CheckboxList component allows users to select multiple items from a predefined list, with support for grid layouts, search, bulk toggle, descriptions, and more.

## Basic Usage

```php
use Accelade\Forms\Components\CheckboxList;

CheckboxList::make('interests')
    ->label('Select your interests')
    ->options([
        'tech' => 'Technology',
        'sports' => 'Sports',
        'music' => 'Music',
        'travel' => 'Travel',
    ]);
```

## Setting Default Values

Pre-select options using the `default()` method:

```php
CheckboxList::make('skills')
    ->label('Your Skills')
    ->options([
        'php' => 'PHP',
        'javascript' => 'JavaScript',
        'python' => 'Python',
    ])
    ->default(['php', 'javascript']);
```

## Grid Layout

Arrange checkboxes in a multi-column grid:

```php
CheckboxList::make('permissions')
    ->label('User Permissions')
    ->options([
        'create' => 'Create',
        'read' => 'Read',
        'update' => 'Update',
        'delete' => 'Delete',
        'export' => 'Export',
        'import' => 'Import',
    ])
    ->columns(3);
```

### Grid Direction

Control how options flow in the grid:

```php
// Options flow top-to-bottom, then left-to-right (default)
CheckboxList::make('items')
    ->options($items)
    ->columns(3)
    ->gridDirection('column');

// Options flow left-to-right, then top-to-bottom
CheckboxList::make('items')
    ->options($items)
    ->columns(3)
    ->gridDirection('row');
```

## Option Descriptions

Add descriptions below each option to provide more context:

```php
CheckboxList::make('plans')
    ->label('Choose your subscription')
    ->options([
        'basic' => 'Basic Plan',
        'pro' => 'Pro Plan',
        'enterprise' => 'Enterprise Plan',
    ])
    ->descriptions([
        'basic' => 'Perfect for individuals, includes basic features',
        'pro' => 'For professionals, includes advanced analytics',
        'enterprise' => 'For teams, includes priority support and API access',
    ]);
```

## Searchable Options

Enable search functionality for lists with many options:

```php
CheckboxList::make('countries')
    ->label('Select countries')
    ->options($allCountries)
    ->searchable()
    ->searchPrompt('Type to search countries...')
    ->noSearchResultsMessage('No countries found');
```

### Search Debounce

Control the search debounce time (default: 300ms):

```php
CheckboxList::make('items')
    ->options($items)
    ->searchable()
    ->searchDebounce(500); // Wait 500ms after typing before filtering
```

## Bulk Toggle

Enable "Select All" and "Deselect All" actions:

```php
CheckboxList::make('permissions')
    ->label('User Permissions')
    ->options([
        'create' => 'Create',
        'read' => 'Read',
        'update' => 'Update',
        'delete' => 'Delete',
    ])
    ->bulkToggleable();
```

### Custom Action Labels

Customize the bulk action labels:

```php
CheckboxList::make('permissions')
    ->options($permissions)
    ->bulkToggleable()
    ->selectAllActionLabel('Grant all permissions')
    ->deselectAllActionLabel('Revoke all permissions');
```

## Disabling Options

Disable specific options conditionally:

```php
CheckboxList::make('features')
    ->label('Available Features')
    ->options([
        'dashboard' => 'Dashboard',
        'reports' => 'Reports',
        'analytics' => 'Analytics (Pro)',
        'api' => 'API Access (Enterprise)',
    ])
    ->disableOptionWhen(fn (string $value): bool => in_array($value, ['analytics', 'api']));
```

You can also access the label in the callback:

```php
CheckboxList::make('items')
    ->options($items)
    ->disableOptionWhen(fn (string $value, string $label): bool => 
        str_contains($label, 'Premium')
    );
```

## Allow HTML in Labels

By default, labels are escaped. Enable HTML rendering:

```php
CheckboxList::make('badges')
    ->label('Select badges')
    ->options([
        'verified' => '<span class="text-green-600">✓ Verified</span>',
        'premium' => '<span class="text-amber-600">★ Premium</span>',
    ])
    ->allowHtml();
```

> **Warning:** Only enable `allowHtml()` with trusted content to prevent XSS attacks.

## Eloquent Relationships

For BelongsToMany or MorphToMany relationships:

```php
CheckboxList::make('roles')
    ->label('User Roles')
    ->relationship('roles', 'name');
```

The first argument is the relationship name, the second is the title attribute to display.

### Pivot Data

Add additional pivot data when saving relationships:

```php
CheckboxList::make('projects')
    ->relationship('projects', 'name')
    ->pivotData([
        'assigned_at' => now(),
        'assigned_by' => auth()->id(),
    ]);
```

## Full Featured Example

Combining multiple features:

```php
CheckboxList::make('technologies')
    ->label('Technology Stack')
    ->options([
        'laravel' => 'Laravel',
        'vue' => 'Vue.js',
        'react' => 'React',
        'tailwind' => 'Tailwind CSS',
        'mysql' => 'MySQL',
        'redis' => 'Redis',
    ])
    ->descriptions([
        'laravel' => 'PHP web framework',
        'vue' => 'Progressive JavaScript framework',
        'react' => 'JavaScript library for UIs',
        'tailwind' => 'Utility-first CSS framework',
        'mysql' => 'Relational database',
        'redis' => 'In-memory data store',
    ])
    ->columns(2)
    ->searchable()
    ->bulkToggleable()
    ->default(['laravel', 'vue', 'tailwind'])
    ->helperText('Select all technologies you have experience with');
```

## JavaScript Events

The CheckboxList component emits events you can listen to:

```javascript
// Listen for value changes
document.querySelector('[data-checkbox-list]').addEventListener('checkbox-list:change', (e) => {
    console.log('Selected values:', e.detail.values);
});

// Listen for search events
document.querySelector('[data-checkbox-list]').addEventListener('checkbox-list:search', (e) => {
    console.log('Search query:', e.detail.query);
    console.log('Visible options:', e.detail.visibleCount);
});
```

## Programmatic Control

Access the CheckboxListManager instance:

```javascript
const wrapper = document.querySelector('[data-checkbox-list]');
const manager = AcceladeForms.CheckboxList.getInstance(wrapper);

// Get selected values
const values = manager.getSelectedValues();

// Set selected values
manager.setSelectedValues(['php', 'javascript']);

// Select all visible options
manager.selectAll();

// Deselect all visible options
manager.deselectAll();
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `options(array\|Closure $options)` | Set available options |
| `default(array $values)` | Set default selected values |
| `columns(int\|Closure $count)` | Set grid columns (1-6) |
| `gridDirection(string $direction)` | Set grid flow ('column' or 'row') |
| `searchable(bool $condition = true)` | Enable search functionality |
| `searchPrompt(string $prompt)` | Set search placeholder text |
| `searchDebounce(int $ms)` | Set search debounce time |
| `noSearchResultsMessage(string $msg)` | Set no results message |
| `bulkToggleable(bool $condition = true)` | Enable bulk toggle actions |
| `selectAllActionLabel(string $label)` | Set "Select All" label |
| `deselectAllActionLabel(string $label)` | Set "Deselect All" label |
| `descriptions(array\|Closure $descriptions)` | Set option descriptions |
| `disableOptionWhen(Closure $callback)` | Conditionally disable options |
| `allowHtml(bool $condition = true)` | Allow HTML in labels |
| `relationship(string $name, string $titleAttr)` | Set Eloquent relationship |
| `pivotData(array $data)` | Set additional pivot data |

## Blade Component

Use CheckboxList as a Blade component:

```blade
{{-- Basic usage --}}
<x-forms::checkbox-list
    name="interests"
    label="Select your interests"
    :options="[
        'tech' => 'Technology',
        'sports' => 'Sports',
        'music' => 'Music',
    ]"
/>

{{-- With all features --}}
<x-forms::checkbox-list
    name="technologies"
    label="Technology Stack"
    :options="$technologies"
    :descriptions="$descriptions"
    :columns="3"
    :default="['php', 'laravel']"
    searchable
    bulk-toggleable
/>
```

### Blade Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `options` | array | Options array |
| `default` | array | Pre-selected values |
| `columns` | int | Grid columns |
| `descriptions` | array | Option descriptions |
| `searchable` | bool | Enable search |
| `bulk-toggleable` | bool | Enable bulk actions |
| `disabled` | bool | Disable all options |
| `required` | bool | Mark as required |
| `helper-text` | string | Helper text below field |

# Icon Picker

The IconPicker component provides an icon selection grid with support for multiple icon libraries including Emoji, Boxicons, Heroicons, and Lucide icons.

## Basic Usage

```php
use Accelade\Forms\Components\IconPicker;

IconPicker::make('icon')
    ->label('Select Icon');
```

## Icon Sets

The IconPicker supports multiple icon libraries. Use the `IconSet` enum for type-safe icon set selection:

```php
use Accelade\Forms\Components\IconPicker;
use Accelade\Forms\Enums\IconSet;

// Single icon set
IconPicker::make('icon')
    ->label('Choose Emoji')
    ->sets([IconSet::Emoji])
    ->searchable();

// Multiple icon sets with tabs
IconPicker::make('icon')
    ->label('Choose Icon')
    ->sets([IconSet::Emoji, IconSet::Boxicons, IconSet::Heroicons, IconSet::Lucide])
    ->defaultSet(IconSet::Emoji)
    ->searchable()
    ->showIconName();

// Single icon library
IconPicker::make('icon')
    ->label('Heroicons')
    ->sets([IconSet::Heroicons])
    ->searchable()
    ->gridColumns(10);

// Multiple libraries (no emoji)
IconPicker::make('icon')
    ->label('Icon Libraries')
    ->sets([IconSet::Boxicons, IconSet::Heroicons, IconSet::Lucide])
    ->defaultSet(IconSet::Heroicons);
```

### Available Icon Sets

| Icon Set | Enum Value | Description |
|----------|------------|-------------|
| Emoji | `IconSet::Emoji` | Native Unicode emoji organized by categories |
| Boxicons | `IconSet::Boxicons` | 700+ premium vector icons, regular and solid styles |
| Heroicons | `IconSet::Heroicons` | Beautiful icons by Tailwind CSS creators |
| Lucide | `IconSet::Lucide` | 1000+ community icons, fork of Feather |

All icon libraries use **inline SVG** rendering - no external CSS or font files required.

### String Values (Backward Compatible)

String values are still supported for backward compatibility:

```php
IconPicker::make('icon')
    ->sets(['emoji', 'heroicons'])
    ->defaultSet('heroicons');
```

## Custom Icons

Provide custom icon set:

```php
IconPicker::make('icon')
    ->label('Icon')
    ->icons([
        'home', 'user', 'settings', 'search',
        'heart', 'star', 'mail', 'phone',
    ]);
```

## Searchable

Enable icon search:

```php
IconPicker::make('icon')
    ->label('Icon')
    ->searchable();
```

## Grid Columns

Set number of columns:

```php
IconPicker::make('icon')
    ->label('Icon')
    ->gridColumns(6);
```

## Show Icon Names

Display icon names:

```php
IconPicker::make('icon')
    ->label('Icon')
    ->showIconName();
```

## Multiple Selection

Allow selecting multiple icons:

```php
IconPicker::make('icons')
    ->label('Icons')
    ->multiple()
    ->maxItems(5);
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `sets($sets)` | Set icon sets to include (array of IconSet enum or strings) |
| `defaultSet($set)` | Set default icon set to display (IconSet enum or string) |
| `icons($icons)` | Set available custom icons |
| `searchable()` | Enable search |
| `gridColumns($count)` | Set grid columns |
| `showIconName()` | Show icon names |
| `multiple()` | Allow multiple selection |
| `maxItems($count)` | Limit selections |
| `minItems($count)` | Require minimum |
| `placeholder($text)` | Set placeholder |

## IconSet Enum

The `IconSet` enum provides type-safe icon set selection:

```php
use Accelade\Forms\Enums\IconSet;

// Available cases
IconSet::Emoji      // 'emoji'
IconSet::Boxicons   // 'boxicons'
IconSet::Heroicons  // 'heroicons'
IconSet::Lucide     // 'lucide'

// Get display label
IconSet::Heroicons->label(); // 'Heroicons'

// Get all icon sets
IconSet::all(); // [IconSet::Emoji, IconSet::Boxicons, IconSet::Heroicons, IconSet::Lucide]

// Get all values as strings
IconSet::values(); // ['emoji', 'boxicons', 'heroicons', 'lucide']
```

## Blade Component

You can also use the IconPicker as a Blade component:

```blade
{{-- Basic icon picker --}}
<x-accelade::icon-picker
    name="icon"
    label="Select Icon"
/>

{{-- With specific icon sets --}}
<x-accelade::icon-picker
    name="icon"
    label="Icon"
    :sets="['heroicons', 'lucide']"
/>

{{-- Searchable --}}
<x-accelade::icon-picker
    name="icon"
    label="Icon"
    searchable
/>

{{-- Custom grid columns --}}
<x-accelade::icon-picker
    name="icon"
    label="Icon"
    :grid-columns="6"
/>

{{-- Show icon names --}}
<x-accelade::icon-picker
    name="icon"
    label="Icon"
    show-icon-name
/>

{{-- Multiple selection --}}
<x-accelade::icon-picker
    name="icons"
    label="Icons"
    multiple
    :max-items="5"
/>

{{-- Required icon --}}
<x-accelade::icon-picker
    name="category_icon"
    label="Category Icon"
    required
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string/array | Selected icon(s) |
| `sets` | array | Icon sets to include |
| `default-set` | string | Default icon set |
| `icons` | array | Custom available icons |
| `searchable` | bool | Enable search |
| `grid-columns` | int | Number of columns |
| `show-icon-name` | bool | Display icon names |
| `multiple` | bool | Allow multiple selection |
| `max-items` | int | Maximum selections |
| `min-items` | int | Minimum selections |
| `placeholder` | string | Placeholder text |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable picker |

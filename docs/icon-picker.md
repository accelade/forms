# Icon Picker

The IconPicker component provides an icon selection grid with support for multiple icon libraries. It supports two modes:
- **Embedded Icons**: Pre-defined icons embedded in the page (emoji, boxicons, heroicons, lucide)
- **Blade Icons**: Dynamically loaded icons from any Blade Icons package with lazy loading and search

## Basic Usage

```php
use Accelade\Forms\Components\IconPicker;

IconPicker::make('icon')
    ->label('Select Icon');
```

## Blade Icons Mode

Enable Blade Icons mode to use any installed Blade Icons package (Heroicons, Tabler Icons, Feather Icons, etc.):

```php
IconPicker::make('icon')
    ->label('Icon')
    ->bladeIcons() // Enable Blade Icons mode
    ->perPage(50)  // Icons per page (default: 50)
```

### How It Works

When Blade Icons mode is enabled:

1. **Automatic Detection**: The component automatically detects all installed Blade Icons packages
2. **Lazy Loading**: Icons are loaded on-demand as the user scrolls (50 icons at a time by default)
3. **Search**: Users can search icons by name across the selected set
4. **Set Switching**: Users can switch between different icon sets (Heroicons, Tabler, etc.)

### Installing Blade Icons Packages

Install any Blade Icons package via Composer:

```bash
# Heroicons
composer require blade-ui-kit/blade-heroicons

# Tabler Icons
composer require blade-ui-kit/blade-tabler-icons

# Feather Icons
composer require blade-ui-kit/blade-feather-icons

# Font Awesome
composer require owenvoke/blade-fontawesome

# Bootstrap Icons
composer require davidhsianern/blade-bootstrap-icons
```

See [Blade Icons](https://blade-ui-kit.com/blade-icons) for a full list of available icon packages.

### Value Format

When using Blade Icons mode, the selected value is stored in the format `set:icon-name`:

```
heroicons:academic-cap
tabler-icons:arrow-right
feathericons:check
```

To render the selected icon in your Blade templates:

```blade
@php
    [$set, $name] = explode(':', $icon);
@endphp

<x-dynamic-component :component="$set . '-' . $name" class="w-6 h-6" />

{{-- Or use the @svg directive --}}
@svg($icon, 'w-6 h-6')
```

## Embedded Icon Sets

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
| `bladeIcons()` | Enable Blade Icons mode with lazy loading |
| `perPage($count)` | Set number of icons per page (default: 50) |

## API Endpoints (Blade Icons Mode)

When using Blade Icons mode, the component uses these API endpoints:

| Endpoint | Description |
|----------|-------------|
| `GET /accelade/api/icons/sets` | List all available icon sets |
| `GET /accelade/api/icons/{set}` | Get icons from a set (paginated) |
| `GET /accelade/api/icons/search` | Search icons by name |
| `GET /accelade/api/icons/svg/{icon}` | Get SVG for a specific icon |

### Query Parameters

**GET /accelade/api/icons/{set}**
- `offset` - Start position (default: 0)
- `limit` - Number of icons to return (default: 50)
- `search` - Filter icons by name

**GET /accelade/api/icons/search**
- `q` - Search query
- `set` - Limit search to a specific set
- `limit` - Maximum results (default: 50)

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

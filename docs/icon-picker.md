# Icon Picker

The IconPicker component provides an icon selection grid with support for multiple icon libraries via Blade Icons.

> **Note:** For emoji selection, use the dedicated `EmojiInput` component which provides a full emoji picker with categories and search.

## Basic Usage

```php
use Accelade\Forms\Components\IconPicker;

IconPicker::make('icon')
    ->label('Select Icon')
    ->bladeIcons();
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

## Custom Icons

Provide a custom icon set:

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
    ->bladeIcons()
    ->searchable();
```

## Grid Columns

Set number of columns:

```php
IconPicker::make('icon')
    ->label('Icon')
    ->bladeIcons()
    ->gridColumns(6);
```

## Show Icon Names

Display icon names:

```php
IconPicker::make('icon')
    ->label('Icon')
    ->bladeIcons()
    ->showIconName();
```

## Multiple Selection

Allow selecting multiple icons:

```php
IconPicker::make('icons')
    ->label('Icons')
    ->bladeIcons()
    ->multiple()
    ->maxItems(5);
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `bladeIcons()` | Enable Blade Icons mode with lazy loading |
| `perPage($count)` | Set number of icons per page (default: 50) |
| `icons($icons)` | Set available custom icons |
| `searchable()` | Enable search |
| `gridColumns($count)` | Set grid columns |
| `showIconName()` | Show icon names |
| `multiple()` | Allow multiple selection |
| `maxItems($count)` | Limit selections |
| `minItems($count)` | Require minimum |
| `placeholder($text)` | Set placeholder |

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

## Blade Component

You can also use the IconPicker as a Blade component:

```blade
{{-- Basic icon picker with Blade Icons --}}
<x-accelade::icon-picker
    name="icon"
    label="Select Icon"
    blade-icons
/>

{{-- Searchable --}}
<x-accelade::icon-picker
    name="icon"
    label="Icon"
    blade-icons
    searchable
/>

{{-- Custom grid columns --}}
<x-accelade::icon-picker
    name="icon"
    label="Icon"
    blade-icons
    :grid-columns="6"
/>

{{-- Show icon names --}}
<x-accelade::icon-picker
    name="icon"
    label="Icon"
    blade-icons
    show-icon-name
/>

{{-- Multiple selection --}}
<x-accelade::icon-picker
    name="icons"
    label="Icons"
    blade-icons
    multiple
    :max-items="5"
/>

{{-- Required icon --}}
<x-accelade::icon-picker
    name="category_icon"
    label="Category Icon"
    blade-icons
    required
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string/array | Selected icon(s) |
| `blade-icons` | bool | Enable Blade Icons mode |
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

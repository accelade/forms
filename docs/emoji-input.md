# Emoji Input

The EmojiInput component provides an emoji picker with categories, search, and preview.

## Basic Usage

```php
use Accelade\Forms\Components\EmojiInput;

EmojiInput::make('emoji')
    ->label('Select Emoji');
```

## Categories

Filter which emoji categories are shown:

```php
EmojiInput::make('reaction')
    ->label('Reaction')
    ->categories(['smileys', 'people', 'animals']);
```

Available categories:
- `smileys` - Smileys & Emotion
- `people` - People & Body
- `animals` - Animals & Nature
- `food` - Food & Drink
- `travel` - Travel & Places
- `activities` - Activities
- `objects` - Objects
- `symbols` - Symbols
- `flags` - Flags

## Search

Enable or disable the search input:

```php
EmojiInput::make('emoji')
    ->searchable(false); // Disable search
```

## Grid Columns

Control the number of columns in the emoji grid:

```php
EmojiInput::make('emoji')
    ->gridColumns(10); // Default is 8
```

## Preview

Show or hide the emoji preview area:

```php
EmojiInput::make('emoji')
    ->showPreview(false); // Hide preview
```

## Multiple Selection

Allow selecting multiple emojis:

```php
EmojiInput::make('tags')
    ->label('Emoji Tags')
    ->multiple();
```

## Custom Emojis

Provide a custom set of emojis:

```php
EmojiInput::make('status')
    ->label('Status')
    ->customEmojis([
        'status' => [
            '✅' => 'Done',
            '🔄' => 'In Progress',
            '⏸️' => 'Paused',
            '❌' => 'Cancelled',
            '🚀' => 'Launched',
        ],
    ]);
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `categories($categories)` | Set emoji categories to display |
| `searchable($condition)` | Enable/disable search |
| `gridColumns($columns)` | Set grid column count |
| `showPreview($condition)` | Show/hide preview area |
| `multiple($condition)` | Enable multiple selection |
| `customEmojis($emojis)` | Set custom emoji set |

## Blade Component

You can also use the EmojiInput as a Blade component:

```blade
{{-- Basic emoji picker --}}
<x-accelade::emoji-input
    name="emoji"
    label="Select Emoji"
/>

{{-- With specific categories --}}
<x-accelade::emoji-input
    name="reaction"
    label="Reaction"
    :categories="['smileys', 'people']"
/>

{{-- Without search --}}
<x-accelade::emoji-input
    name="quick"
    label="Quick Select"
    :searchable="false"
/>

{{-- Multiple selection --}}
<x-accelade::emoji-input
    name="tags"
    label="Emoji Tags"
    multiple
/>

{{-- Custom grid columns --}}
<x-accelade::emoji-input
    name="emoji"
    label="Emoji"
    :grid-columns="10"
/>

{{-- Required emoji --}}
<x-accelade::emoji-input
    name="reaction"
    label="Your Reaction"
    required
/>

{{-- Disabled picker --}}
<x-accelade::emoji-input
    name="emoji"
    label="Locked"
    value="😊"
    disabled
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string | Default emoji value |
| `categories` | array | Emoji categories to show |
| `searchable` | bool | Enable search (default: true) |
| `grid-columns` | int | Grid columns (default: 8) |
| `show-preview` | bool | Show preview (default: true) |
| `multiple` | bool | Allow multiple selection |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

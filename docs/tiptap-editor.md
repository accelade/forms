# TipTap Editor

The TipTapEditor component provides a powerful WYSIWYG rich text editor using TipTap with Filament-compatible API.

## Basic Usage

```php
use Accelade\Forms\Components\TipTapEditor;

TipTapEditor::make('content')
    ->label('Content')
    ->placeholder('Start writing...');
```

## Toolbar Profiles

Choose from built-in profiles:

```php
// Default - Full featured toolbar
TipTapEditor::make('content')
    ->profile('default');

// Simple - Essential tools
TipTapEditor::make('content')
    ->profile('simple');

// Minimal - Basic formatting
TipTapEditor::make('content')
    ->profile('minimal');

// None - No toolbar
TipTapEditor::make('content')
    ->profile('none');
```

### Profile Tools

| Profile | Included Tools |
|---------|---------------|
| `default` | heading, bold, italic, strike, underline, bulletList, orderedList, blockquote, codeBlock, horizontalRule, link, media, table, alignment, undo, redo |
| `simple` | heading, bold, italic, bulletList, orderedList, link, media, undo, redo |
| `minimal` | bold, italic, link, bulletList, orderedList |

## Custom Tools

Override the profile with custom tools:

```php
TipTapEditor::make('body')
    ->tools([
        'bold', 'italic', 'underline',
        '|', // Separator
        'h2', 'h3',
        '|',
        'bulletList', 'orderedList',
        '|',
        'link',
    ]);
```

## Available Tools

### Text Formatting
- `bold` - Bold text
- `italic` - Italic text
- `underline` - Underlined text
- `strike` - Strikethrough text
- `subscript` - Subscript text
- `superscript` - Superscript text
- `highlight` - Highlight text
- `code` - Inline code

### Headings
- `heading` - Toggle heading (H1)
- `h1` through `h6` - Specific heading levels
- `paragraph` - Normal paragraph

### Lists & Blocks
- `bulletList` - Unordered list
- `orderedList` - Ordered list
- `blockquote` - Block quote
- `codeBlock` - Code block
- `horizontalRule` - Horizontal line

### Alignment
- `alignLeft` - Align left
- `alignCenter` - Align center
- `alignRight` - Align right
- `alignJustify` - Justify text

### Links & Media
- `link` - Insert/edit link
- `unlink` - Remove link
- `media` - Insert image
- `image` - Insert image (alias)
- `table` - Insert table

### History
- `undo` - Undo
- `redo` - Redo
- `clearFormatting` - Remove all formatting

## Character Limit

Set maximum content length with optional counter:

```php
TipTapEditor::make('summary')
    ->maxLength(1000)
    ->showCharacterCount();
```

## Output Format

Configure the output format:

```php
// HTML output (default)
TipTapEditor::make('content')
    ->output('html');

// JSON output (for structured storage)
TipTapEditor::make('content')
    ->output('json');

// Plain text
TipTapEditor::make('content')
    ->output('text');
```

## Floating & Bubble Menus

TipTap includes floating and bubble menus for inline editing:

```php
// Disable floating menus
TipTapEditor::make('content')
    ->disableFloatingMenus();

// Disable bubble menus
TipTapEditor::make('content')
    ->disableBubbleMenus();

// Custom floating menu tools
TipTapEditor::make('content')
    ->floatingMenuTools(['media', 'table', 'horizontalRule']);
```

## File Attachments

Configure file upload storage:

```php
TipTapEditor::make('article')
    ->disk('public')
    ->directory('articles/images')
    ->maxSize(5120) // 5MB in KB
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
    ->visibility('public')
    ->preserveFilenames();
```

## Image Settings

Configure image handling:

```php
TipTapEditor::make('content')
    ->imageCropAspectRatio('16:9')
    ->imageResizeTargetWidth(1200)
    ->imageResizeTargetHeight(675);
```

## RTL Support

Enable right-to-left text direction:

```php
TipTapEditor::make('content')
    ->rtl();

// Or explicitly
TipTapEditor::make('content')
    ->direction('rtl');
```

## Advanced Features

### Merge Tags

Add merge tags for mail merge functionality:

```php
TipTapEditor::make('template')
    ->mergeTags(['first_name', 'last_name', 'company']);
```

### Grid Layouts

Configure grid builder layouts:

```php
TipTapEditor::make('content')
    ->gridLayouts([
        'two-columns',
        'three-columns',
        'asymmetric-left-thirds',
    ]);
```

### Preset Colors

Set preset colors for the color picker:

```php
TipTapEditor::make('content')
    ->presetColors([
        'primary' => '#3B82F6',
        'secondary' => '#6B7280',
        'success' => '#10B981',
    ]);
```

### Custom Extensions

Load custom TipTap extensions:

```php
TipTapEditor::make('content')
    ->extensions(['MyCustomExtension']);
```

## States

```php
// Readonly mode
TipTapEditor::make('preview')
    ->readonly();

// Disabled state
TipTapEditor::make('locked')
    ->disabled();

// Required field
TipTapEditor::make('content')
    ->required();
```

## Extra Attributes

Add custom attributes to the editor container:

```php
TipTapEditor::make('content')
    ->extraInputAttributes([
        'style' => 'min-height: 20rem;',
        'data-custom' => 'value',
    ]);
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `profile($name)` | Set toolbar profile (default, simple, minimal, none) |
| `tools($array)` | Override profile with custom tools |
| `output($format)` | Set output format (html, json, text) |
| `maxLength($length)` | Set character limit |
| `showCharacterCount()` | Display character counter |
| `placeholder($text)` | Set placeholder text |
| `disableFloatingMenus()` | Disable floating menus |
| `disableBubbleMenus()` | Disable bubble menus |
| `floatingMenuTools($array)` | Customize floating menu tools |
| `disk($disk)` | Storage disk for uploads |
| `directory($dir)` | Storage directory for uploads |
| `visibility($visibility)` | File visibility (public/private) |
| `maxSize($kb)` | Max file size in KB |
| `acceptedFileTypes($array)` | Allowed MIME types |
| `preserveFilenames()` | Keep original file names |
| `imageCropAspectRatio($ratio)` | Image crop aspect ratio |
| `imageResizeTargetWidth($px)` | Image resize width |
| `imageResizeTargetHeight($px)` | Image resize height |
| `rtl()` | Enable RTL direction |
| `direction($dir)` | Set text direction (ltr/rtl) |
| `mergeTags($array)` | Add merge tags |
| `gridLayouts($array)` | Configure grid layouts |
| `presetColors($array)` | Set preset colors |
| `extensions($array)` | Load custom extensions |
| `collaboration()` | Enable collaboration mode |
| `extraInputAttributes($array)` | Add custom attributes |

## Blade Component

Use TipTapEditor as a Blade component:

```blade
{{-- Basic editor --}}
<x-forms::tiptap-editor
    name="content"
    label="Content"
/>

{{-- With profile --}}
<x-forms::tiptap-editor
    name="body"
    label="Body"
    profile="simple"
/>

{{-- With custom tools --}}
<x-forms::tiptap-editor
    name="content"
    :tools="['bold', 'italic', '|', 'link']"
/>

{{-- With character limit --}}
<x-forms::tiptap-editor
    name="summary"
    label="Summary"
    :max-length="500"
/>

{{-- RTL support --}}
<x-forms::tiptap-editor
    name="content"
    direction="rtl"
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string | Default content |
| `profile` | string | Toolbar profile |
| `tools` | array | Custom toolbar tools |
| `max-length` | int | Character limit |
| `placeholder` | string | Placeholder text |
| `output` | string | Output format |
| `direction` | string | Text direction |
| `hint` | string | Help text |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable editor |
| `readonly` | bool | Make read-only |

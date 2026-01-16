# Rich Editor

The RichEditor component provides a WYSIWYG rich text editor with a Filament-compatible API.

## Basic Usage

```php
use Accelade\Forms\Components\RichEditor;

RichEditor::make('content')
    ->label('Content');
```

## Toolbar Buttons

Customize the toolbar with grouped buttons (Filament style):

```php
RichEditor::make('body')
    ->label('Body')
    ->toolbarButtons([
        ['bold', 'italic', 'underline', 'strike'],
        ['h2', 'h3'],
        ['bulletList', 'orderedList', 'blockquote'],
        ['link'],
        ['undo', 'redo'],
    ]);
```

Or with a flat array:

```php
RichEditor::make('comment')
    ->toolbarButtons(['bold', 'italic', 'link']);
```

## Available Toolbar Buttons

### Text Formatting
- `bold` - Bold text
- `italic` - Italic text
- `underline` - Underlined text
- `strike` - Strikethrough text
- `subscript` - Subscript text
- `superscript` - Superscript text

### Headings
- `h1` - Heading 1
- `h2` - Heading 2
- `h3` - Heading 3
- `h4` - Heading 4
- `h5` - Heading 5
- `h6` - Heading 6
- `paragraph` - Normal paragraph

### Lists & Blocks
- `bulletList` - Bullet list
- `orderedList` - Numbered list
- `blockquote` - Block quote
- `codeBlock` - Code block
- `horizontalRule` - Horizontal line

### Alignment
- `alignStart` - Align left
- `alignCenter` - Align center
- `alignEnd` - Align right
- `alignJustify` - Justify text

### Links & Media
- `link` - Insert link
- `unlink` - Remove link
- `image` - Insert image
- `attachFiles` - Attach files
- `table` - Insert table

### History & Misc
- `undo` - Undo
- `redo` - Redo
- `clearFormatting` - Remove formatting
- `highlight` - Highlight text

## Disable Buttons

Disable specific toolbar buttons:

```php
RichEditor::make('description')
    ->disableToolbarButtons(['underline', 'strike']);
```

Disable all toolbar buttons:

```php
RichEditor::make('plain')
    ->disableAllToolbarButtons();
```

## Character Limit

Set maximum content length with a counter:

```php
RichEditor::make('summary')
    ->label('Summary')
    ->maxLength(1000);
```

## Placeholder

Set placeholder text:

```php
RichEditor::make('notes')
    ->label('Notes')
    ->placeholder('Start typing...');
```

## File Attachments

Configure file attachment storage:

```php
RichEditor::make('article')
    ->fileAttachmentsDisk('public')
    ->fileAttachmentsDirectory('uploads/articles')
    ->fileAttachmentsVisibility('public');
```

## Output Format

Configure the output format:

```php
// HTML output (default)
RichEditor::make('content')->output('html');

// JSON output (for structured storage)
RichEditor::make('content')->output('json');

// Plain text
RichEditor::make('content')->output('text');
```

## States

```php
// Readonly mode
RichEditor::make('preview')
    ->readonly();

// Disabled state
RichEditor::make('locked')
    ->disabled();

// Required field
RichEditor::make('content')
    ->required();
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `toolbarButtons($buttons)` | Set toolbar buttons (grouped or flat array) |
| `disableToolbarButtons($buttons)` | Disable specific buttons |
| `disableAllToolbarButtons()` | Disable all toolbar buttons |
| `enableToolbarButtons($buttons)` | Enable only specific buttons |
| `maxLength($length)` | Set character limit with counter |
| `placeholder($text)` | Set placeholder text |
| `output($format)` | Set output format (html, json, text) |
| `fileAttachmentsDisk($disk)` | Set storage disk for attachments |
| `fileAttachmentsDirectory($dir)` | Set upload directory |
| `fileAttachmentsVisibility($visibility)` | Set file visibility (public/private) |

## Blade Component

You can also use the RichEditor as a Blade component:

```blade
{{-- Basic rich editor --}}
<x-forms::rich-editor
    name="content"
    label="Content"
/>

{{-- With custom toolbar --}}
<x-forms::rich-editor
    name="body"
    label="Body"
    :toolbar-buttons="[
        ['bold', 'italic', 'underline'],
        ['bulletList', 'orderedList'],
        ['link'],
    ]"
/>

{{-- With character limit --}}
<x-forms::rich-editor
    name="summary"
    label="Summary"
    :max-length="1000"
    hint="Maximum 1000 characters"
/>

{{-- Required editor --}}
<x-forms::rich-editor
    name="description"
    label="Description"
    placeholder="Start typing..."
    required
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string | Default content |
| `toolbar-buttons` | array | Toolbar buttons |
| `max-length` | int | Character limit |
| `placeholder` | string | Placeholder text |
| `output` | string | Output format (html, json, text) |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable editor |
| `readonly` | bool | Make read-only |

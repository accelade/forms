# Markdown Editor

The MarkdownEditor component provides a markdown text editor with preview.

## Basic Usage

```php
use Accelade\Forms\Components\MarkdownEditor;

MarkdownEditor::make('readme')
    ->label('README');
```

## Preview Mode

Enable live preview:

```php
MarkdownEditor::make('content')
    ->label('Content')
    ->preview();
```

## Toolbar Buttons

Customize the toolbar:

```php
MarkdownEditor::make('post')
    ->label('Post')
    ->toolbarButtons([
        'bold', 'italic', 'code',
        'heading', 'bulletList', 'orderedList',
        'link', 'image', 'blockquote',
    ]);
```

## Character Limit

Set maximum content length:

```php
MarkdownEditor::make('bio')
    ->label('Bio')
    ->maxLength(500);
```

## File Attachments

Enable file attachments:

```php
MarkdownEditor::make('documentation')
    ->label('Documentation')
    ->attachments()
    ->attachmentsDirectory('docs');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `toolbarButtons($buttons)` | Set toolbar buttons |
| `preview()` | Enable preview mode |
| `maxLength($length)` | Set character limit |
| `placeholder($text)` | Set placeholder |
| `attachments()` | Enable file attachments |
| `attachmentsDirectory($path)` | Set attachments path |

## Blade Component

You can also use the MarkdownEditor as a Blade component:

```blade
{{-- Basic markdown editor --}}
<x-accelade::markdown-editor
    name="readme"
    label="README"
/>

{{-- With preview --}}
<x-accelade::markdown-editor
    name="content"
    label="Content"
    preview
/>

{{-- With custom toolbar --}}
<x-accelade::markdown-editor
    name="post"
    label="Post"
    :toolbar-buttons="['bold', 'italic', 'code', 'heading', 'link', 'image']"
/>

{{-- With character limit --}}
<x-accelade::markdown-editor
    name="bio"
    label="Bio"
    :max-length="500"
    hint="Maximum 500 characters"
/>

{{-- With attachments --}}
<x-accelade::markdown-editor
    name="documentation"
    label="Documentation"
    attachments
    attachments-directory="docs"
/>

{{-- Required editor --}}
<x-accelade::markdown-editor
    name="description"
    label="Description"
    placeholder="Write your content in markdown..."
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
| `preview` | bool | Enable live preview |
| `max-length` | int | Character limit |
| `placeholder` | string | Placeholder text |
| `attachments` | bool | Enable file attachments |
| `attachments-directory` | string | Attachments directory |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable editor |

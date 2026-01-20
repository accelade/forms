# Code Editor

The CodeEditor component provides a syntax-highlighted code editor powered by CodeMirror. It supports multiple programming languages, themes, and extensive customization options.

## Basic Usage

```php
use Accelade\Forms\Components\CodeEditor;

CodeEditor::make('code')
    ->label('Code');
```

## Language Support

Set the programming language for syntax highlighting:

```php
CodeEditor::make('script')
    ->label('JavaScript Code')
    ->language('javascript');

// Or use the shorthand methods
CodeEditor::make('query')
    ->label('SQL Query')
    ->sql();
```

### Supported Languages

| Language | Aliases | Shorthand Method |
|----------|---------|------------------|
| JavaScript | `javascript`, `js` | `->javascript()` |
| TypeScript | `typescript`, `ts` | `->typescript()` |
| JSX | `jsx` | - |
| TSX | `tsx` | - |
| PHP | `php` | `->php()` |
| HTML | `html` | `->html()` |
| CSS | `css` | `->css()` |
| JSON | `json` | `->json()` |
| SQL | `sql` | `->sql()` |
| Python | `python`, `py` | `->python()` |
| Markdown | `markdown`, `md` | `->markdown()` |
| YAML | `yaml`, `yml` | `->yaml()` |
| XML | `xml` | - |
| Java | `java` | - |
| C++ | `cpp`, `c++` | - |
| Rust | `rust` | - |
| Go | `go` | - |
| Bash | `bash`, `shell` | - |

## Theme

Choose between light and dark themes:

```php
// Dark theme
CodeEditor::make('code')
    ->label('Dark Editor')
    ->dark();

// Light theme (default)
CodeEditor::make('code')
    ->label('Light Editor')
    ->light();

// Or use the theme method
CodeEditor::make('code')
    ->theme('dark');
```

## Editor Options

### Line Numbers

Enable or disable line numbers:

```php
CodeEditor::make('code')
    ->lineNumbers(false); // Disable line numbers
```

### Height

Set minimum and maximum height:

```php
CodeEditor::make('code')
    ->minHeight(200)  // Minimum height in pixels
    ->maxHeight(600); // Maximum height in pixels
```

### Line Wrapping

Enable line wrapping for long lines:

```php
CodeEditor::make('code')
    ->lineWrapping();
```

### Tab Size

Configure tab size and indentation:

```php
CodeEditor::make('code')
    ->tabSize(2)           // 2 spaces per tab
    ->indentWithTabs();    // Use actual tabs instead of spaces
```

### Active Line Highlighting

Toggle active line highlighting:

```php
CodeEditor::make('code')
    ->highlightActiveLine(false);
```

### Bracket Matching

Enable or disable bracket matching:

```php
CodeEditor::make('code')
    ->bracketMatching(false);
```

### Auto-Close Brackets

Enable or disable auto-closing brackets:

```php
CodeEditor::make('code')
    ->autoCloseBrackets(false);
```

### Code Folding

Enable the fold gutter for code folding:

```php
CodeEditor::make('code')
    ->foldGutter();
```

## Full Example

```php
CodeEditor::make('source_code')
    ->label('Source Code')
    ->language('typescript')
    ->dark()
    ->lineNumbers()
    ->minHeight(400)
    ->maxHeight(800)
    ->tabSize(2)
    ->lineWrapping()
    ->highlightActiveLine()
    ->bracketMatching()
    ->autoCloseBrackets()
    ->foldGutter()
    ->placeholder('// Enter your TypeScript code here...')
    ->helperText('Write your code with full syntax highlighting');
```

## States

```php
// Required
CodeEditor::make('code')
    ->label('Required Code')
    ->required();

// Readonly
CodeEditor::make('output')
    ->label('Output')
    ->readonly();

// Disabled
CodeEditor::make('locked')
    ->label('Locked Code')
    ->disabled();
```

## Default Value

Set a default value:

```php
CodeEditor::make('config')
    ->label('Configuration')
    ->json()
    ->default('{\n  "key": "value"\n}');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `language($lang)` | Set the programming language |
| `theme($theme)` | Set the theme ('light' or 'dark') |
| `dark()` | Use dark theme |
| `light()` | Use light theme |
| `lineNumbers($show)` | Enable/disable line numbers |
| `minHeight($px)` | Set minimum height in pixels |
| `maxHeight($px)` | Set maximum height in pixels |
| `lineWrapping($wrap)` | Enable/disable line wrapping |
| `tabSize($size)` | Set tab size (number of spaces) |
| `indentWithTabs($use)` | Use tabs instead of spaces |
| `highlightActiveLine($highlight)` | Enable/disable active line highlighting |
| `bracketMatching($match)` | Enable/disable bracket matching |
| `autoCloseBrackets($close)` | Enable/disable auto-closing brackets |
| `foldGutter($show)` | Enable/disable code folding gutter |

### Language Shorthand Methods

| Method | Language |
|--------|----------|
| `javascript()` | JavaScript |
| `typescript()` | TypeScript |
| `php()` | PHP |
| `html()` | HTML |
| `css()` | CSS |
| `json()` | JSON |
| `sql()` | SQL |
| `python()` | Python |
| `markdown()` | Markdown |
| `yaml()` | YAML |

## JavaScript API

The CodeEditor exposes a JavaScript API for programmatic control:

```javascript
// Get the editor instance
const editor = AcceladeForms.CodeEditor.getInstance('code');

// Get the current value
const value = editor.getValue();

// Set a new value
editor.setValue('const x = 1;');

// Change language dynamically
editor.setLanguage('python');

// Toggle theme
editor.setTheme('dark');

// Focus the editor
editor.focus();

// Destroy the editor
editor.destroy();
```

## Blade Component

You can also use the CodeEditor as a Blade component:

```blade
{{-- Basic code editor --}}
<x-forms::code-editor
    name="code"
    label="Code"
    language="javascript"
/>

{{-- With dark theme --}}
<x-forms::code-editor
    name="script"
    label="Script"
    language="typescript"
    theme="dark"
    :min-height="400"
/>

{{-- SQL editor --}}
<x-forms::code-editor
    name="query"
    label="SQL Query"
    language="sql"
    placeholder="SELECT * FROM users..."
/>

{{-- JSON configuration --}}
<x-forms::code-editor
    name="config"
    label="Configuration"
    language="json"
    :line-wrapping="true"
    hint="Enter valid JSON"
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `language` | string | Programming language |
| `theme` | string | Editor theme ('light' or 'dark') |
| `value` | string | Default value |
| `placeholder` | string | Placeholder text |
| `hint` | string | Help text below editor |
| `line-numbers` | bool | Show line numbers |
| `min-height` | int | Minimum height in pixels |
| `max-height` | int | Maximum height in pixels |
| `line-wrapping` | bool | Enable line wrapping |
| `tab-size` | int | Tab size |
| `indent-with-tabs` | bool | Use tabs for indentation |
| `fold-gutter` | bool | Show code folding gutter |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable editor |
| `readonly` | bool | Make read-only |

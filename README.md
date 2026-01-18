# Accelade Forms

A powerful form builder package for Laravel and Accelade. Create dynamic forms with a Filament-compatible API using Blade components.

## Installation

```bash
composer require accelade/forms
```

The package will auto-register its service provider.

## Quick Start

```php
use Accelade\Forms\Form;
use Accelade\Forms\Components\TextInput;
use Accelade\Forms\Components\Select;
use Accelade\Forms\Components\Toggle;

$form = Form::make()
    ->action('/users')
    ->schema([
        TextInput::make('name')
            ->label('Full Name')
            ->required()
            ->placeholder('Enter your name'),

        TextInput::make('email')
            ->email()
            ->required(),

        Select::make('role')
            ->options([
                'admin' => 'Administrator',
                'editor' => 'Editor',
                'viewer' => 'Viewer',
            ])
            ->searchable(),

        Toggle::make('active')
            ->label('Active Status'),
    ]);
```

## Available Components

### Text Inputs
- **TextInput** - Text, email, password, URL, tel, and numeric inputs
- **Textarea** - Multi-line text input with autosize support
- **Hidden** - Hidden form fields

### Selection
- **Select** - Dropdown select with searchable, multiple, and remote options
- **CheckboxList** - Multiple checkbox selection with grid layout
- **Radio** - Radio button groups
- **Checkbox** - Single checkbox
- **Toggle** - Toggle switch
- **ToggleButtons** - Button-style toggle group

### Rich Content
- **RichEditor** - WYSIWYG editor with toolbar customization
- **TipTapEditor** - TipTap-based editor with collaboration support
- **MarkdownEditor** - Markdown editing with preview

### Specialized
- **FileUpload** - File uploads with FilePond integration
- **IconPicker** - Icon selection from multiple icon sets
- **ColorPicker** - Color selection with presets
- **DatePicker** / **TimePicker** / **DateTimePicker** - Date/time selection
- **DateRangePicker** - Date range selection
- **TagsInput** - Tag input with suggestions
- **EmojiInput** - Emoji picker
- **PinInput** - PIN/OTP code input
- **RateInput** - Star rating input
- **Slider** - Range slider input
- **NumberField** - Numeric input with increment/decrement
- **KeyValue** - Key-value pair editor
- **Repeater** - Repeatable field groups

### Layout
- **Group** - Group fields together

## Component Examples

### TextInput

```php
TextInput::make('username')
    ->label('Username')
    ->placeholder('Enter username')
    ->required()
    ->minLength(3)
    ->maxLength(20)
    ->prefix('@')
    ->hint('Your unique identifier');

// Email input
TextInput::make('email')->email()->required();

// Password input
TextInput::make('password')->password()->required();

// With date picker
TextInput::make('birthday')
    ->datePicker()
    ->format('Y-m-d');
```

### Select

```php
// Basic select
Select::make('country')
    ->options([
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ])
    ->searchable();

// Multiple selection
Select::make('tags')
    ->multiple()
    ->options($tags);

// With Choices.js styling
Select::make('category')
    ->choices(['searchEnabled' => true])
    ->options($categories);

// Remote options
Select::make('user')
    ->remoteUrl('/api/users')
    ->remoteRoot('data')
    ->optionLabel('name')
    ->optionValue('id');

// With relationship
Select::make('roles')
    ->relation('roles')
    ->multiple();
```

### FileUpload

```php
// Image upload
FileUpload::make('avatar')
    ->image()
    ->maxSize(2048)
    ->disk('public')
    ->directory('avatars');

// Multiple files
FileUpload::make('documents')
    ->multiple()
    ->maxFiles(5)
    ->acceptedFileTypes(['application/pdf', 'image/*'])
    ->downloadable();

// With FilePond
FileUpload::make('photos')
    ->filepond(['allowImagePreview' => true])
    ->multiple();

// With Spatie Media Library
FileUpload::make('gallery')
    ->collection('gallery')
    ->mediaBrowser();
```

### CheckboxList

```php
CheckboxList::make('permissions')
    ->options([
        'create' => 'Create',
        'read' => 'Read',
        'update' => 'Update',
        'delete' => 'Delete',
    ])
    ->columns(2)
    ->bulkToggleable()
    ->descriptions([
        'create' => 'Ability to create new records',
        'delete' => 'Ability to delete records',
    ]);
```

### RichEditor

```php
RichEditor::make('content')
    ->toolbarButtons([
        'bold', 'italic', 'underline',
        '|',
        'bulletList', 'orderedList',
        '|',
        'link', 'attachFiles',
    ])
    ->fileAttachmentsDisk('public')
    ->fileAttachmentsDirectory('uploads');
```

### IconPicker

```php
IconPicker::make('icon')
    ->sets(['emoji', 'heroicons'])
    ->defaultSet('heroicons')
    ->searchable()
    ->gridColumns(10);

// With Blade Icons
IconPicker::make('icon')
    ->bladeIcons()
    ->perPage(50);
```

## Form Configuration

```php
Form::make()
    ->action('/submit')
    ->method('POST')
    ->hasFiles() // Enable file uploads
    ->confirm('Are you sure?')
    ->confirmButtonText('Yes, submit')
    ->cancelButtonText('Cancel')
    ->stayOnPage() // Don't redirect after submit
    ->preserveScroll()
    ->resetOnSuccess()
    ->submitOnChange() // Auto-submit on field change
    ->debounce(500)
    ->schema([...]);
```

## Validation

Validation rules are automatically extracted from field definitions:

```php
TextInput::make('email')
    ->email()
    ->required()
    ->rules(['unique:users,email']);
```

You can also use Form Request validation alongside component rules.

## Documentation

Detailed documentation for each component is available in the [docs](docs/) directory:

### Getting Started
- [Getting Started](docs/getting-started.md) - Installation and basic usage
- [API Reference](docs/api-reference.md) - Complete API documentation

### Form Components
- [Form](docs/form.md) - Form wrapper component
- [Group](docs/group.md) - Field grouping
- [Submit](docs/submit.md) - Submit button

### Text Inputs
- [Text Input](docs/text-input.md) - Text, email, password, URL inputs
- [Textarea](docs/textarea.md) - Multi-line text with autosize
- [Number Field](docs/number-field.md) - Numeric input with controls

### Selection Components
- [Select](docs/select.md) - Dropdown with search, multiple, remote options
- [Checkbox](docs/checkbox.md) - Single checkbox
- [Checkbox List](docs/checkbox-list.md) - Multiple checkbox selection
- [Radio](docs/radio.md) - Radio button groups
- [Toggle](docs/toggle.md) - Toggle switch
- [Toggle Buttons](docs/toggle-buttons.md) - Button-style toggles

### Rich Content Editors
- [Rich Editor](docs/rich-editor.md) - WYSIWYG editor
- [TipTap Editor](docs/tiptap-editor.md) - TipTap-based editor
- [Markdown Editor](docs/markdown-editor.md) - Markdown with preview

### Date & Time
- [Date Picker](docs/date-picker.md) - Date selection
- [Time Picker](docs/time-picker.md) - Time selection
- [DateTime Picker](docs/datetime-picker.md) - Combined date/time
- [Date Range Picker](docs/date-range-picker.md) - Date range selection

### Specialized Inputs
- [File Upload](docs/file-upload.md) - File uploads with FilePond
- [Icon Picker](docs/icon-picker.md) - Icon selection
- [Color Picker](docs/color-picker.md) - Color selection
- [Tags Input](docs/tags-input.md) - Tag input with suggestions
- [Pin Input](docs/pin-input.md) - PIN/OTP code input
- [Rate Input](docs/rate-input.md) - Star rating
- [Slider](docs/slider.md) - Range slider
- [Key Value](docs/key-value.md) - Key-value pair editor
- [Repeater](docs/repeater.md) - Repeatable field groups

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=forms-config
```

## Requirements

- PHP 8.2+
- Laravel 11.0+ or 12.0+
- Accelade core package

## Testing

```bash
composer test
```

## License

MIT License. See [LICENSE](LICENSE) for details.

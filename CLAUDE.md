# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Accelade Forms is a Laravel package that provides form builder components for Accelade. It offers a fluent PHP API for building forms with various field types, validation, and model binding support.

## Common Commands

### Testing
```bash
# PHP tests (Pest) - run from package directory
cd packages/forms
vendor/bin/pest --compact
```

### Code Quality
```bash
vendor/bin/pint packages/forms/src  # Format PHP with Pint
```

## Architecture

### PHP Components (src/)
- `Field.php` - Base field class with fluent API for all form fields
- `Form.php` - Form container class for grouping fields
- `Components/` - Field components (TextInput, Textarea, Select, Checkbox, Radio, Toggle, DatePicker, FileUpload, etc.)
- `Concerns/` - Reusable traits (HasOptions, HasMinMax, HasStep, CanBeInline)

### Blade Views (resources/views/components/)
All components render as anonymous Blade components with Tailwind CSS styling.

### Integration with Accelade
- Views are registered under `forms` namespace
- Toggle component uses Accelade's reactivity system (`<x-accelade::data>`)
- Forms integrate with Laravel's validation system

## Key Patterns

### Field API
```php
use Accelade\Forms\Components\TextInput;
use Accelade\Forms\Components\Select;
use Accelade\Forms\Components\Checkbox;

TextInput::make('email')
    ->email()
    ->required()
    ->placeholder('Enter your email')
    ->hint('We will never share your email');

Select::make('country')
    ->options(['us' => 'United States', 'uk' => 'United Kingdom'])
    ->searchable()
    ->emptyOptionLabel('Select a country');

Checkbox::make('terms')
    ->label('I agree to the terms')
    ->required();
```

### Form Container
```php
use Accelade\Forms\Form;
use Accelade\Forms\Components\TextInput;

Form::make('contact')
    ->action('/contact')
    ->method('POST')
    ->schema([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),
    ])
    ->submitLabel('Send Message');
```

### Blade Component Usage
```blade
{!! $form->render() !!}

{{-- Or individual fields --}}
{!! TextInput::make('email')->email()->render() !!}
```

## Configuration

Key config options in `config/forms.php`:
- `default_method` - Default HTTP method for forms
- `errors.show_inline` - Show validation errors below fields
- `errors.scroll_to_first` - Scroll to first error on validation failure
- `styles.*` - Default CSS classes for inputs, labels, hints
- `file_upload.*` - File upload settings
- `datetime.*` - Date/time picker settings
- `demo.enabled` - Enable demo routes

## Available Field Components

- `TextInput` - Text, email, password, number, tel, url inputs
- `Textarea` - Multi-line text input with autosize support
- `Select` - Dropdown select with searchable and multiple options
- `Checkbox` - Single checkbox
- `CheckboxList` - Multiple checkboxes from options
- `Radio` - Radio button group
- `Toggle` - On/off toggle switch
- `ToggleButtons` - Button group for selecting options
- `DatePicker` - Date input with min/max constraints
- `DateRangePicker` - Date range selection with start/end dates
- `TimePicker` - Time input with optional seconds
- `DateTimePicker` - Combined date and time input
- `NumberField` - Number input with stepper buttons and formatting
- `Slider` - Range slider for numeric values
- `ColorPicker` - Color selection with swatches
- `FileUpload` - File upload with type restrictions
- `TagsInput` - Tag/chip input with suggestions
- `KeyValue` - Key-value pair editor
- `Repeater` - Repeatable field groups
- `RichEditor` - WYSIWYG rich text editor
- `MarkdownEditor` - Markdown text editor with preview
- `PinInput` - PIN/OTP code input
- `RateInput` - Star rating input
- `IconPicker` - Icon selection grid
- `Hidden` - Hidden input field

## Test Structure
- `tests/Unit/` - Unit tests for field classes
- `tests/Feature/` - Feature tests for service provider and rendering

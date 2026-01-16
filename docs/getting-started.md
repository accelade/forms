# Forms

Accelade Forms provides a fluent PHP API for building form components. It offers a wide range of input types with consistent styling and behavior across all frontend frameworks.

## Installation

Forms is included with the Accelade package. No additional installation is required.

```bash
composer require accelade/forms
```

## Basic Usage

Create form fields using the fluent PHP API:

```php
use Accelade\Forms\Components\TextInput;
use Accelade\Forms\Components\Select;

// Create a text input
$email = TextInput::make('email')
    ->label('Email Address')
    ->email()
    ->required()
    ->placeholder('Enter your email');

// Create a select dropdown
$country = Select::make('country')
    ->label('Country')
    ->options([
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ])
    ->searchable();
```

```blade
{{-- Text Input --}}
<x-accelade::text-input
    name="email"
    label="Email Address"
    type="email"
    placeholder="Enter your email"
    required
/>

{{-- Select Dropdown --}}
<x-accelade::select
    name="country"
    label="Country"
    :options="[
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ]"
    searchable
/>
```

## Form Container

Group fields into a form with the Form container:

```php
use Accelade\Forms\Form;
use Accelade\Forms\Components\TextInput;
use Accelade\Forms\Components\Textarea;

Form::make('contact')
    ->action('/contact')
    ->method('POST')
    ->schema([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),
        Textarea::make('message')->rows(4),
    ])
    ->submitLabel('Send Message');
```

```blade
{{-- Form Container --}}
<x-accelade::form
    name="contact"
    action="/contact"
    method="POST"
    submit-label="Send Message"
>
    <x-accelade::text-input name="name" label="Name" required />
    <x-accelade::text-input name="email" label="Email" type="email" required />
    <x-accelade::textarea name="message" label="Message" rows="4" />
</x-accelade::form>
```

## HTTP Methods

```php
Form::make()->method('POST');   // Default
Form::make()->method('PUT');    // Update
Form::make()->method('PATCH');  // Partial update
Form::make()->method('DELETE'); // Delete
```

```blade
<x-accelade::form method="PUT" action="/users/1">
    {{-- Update form --}}
</x-accelade::form>

<x-accelade::form method="DELETE" action="/users/1">
    {{-- Delete form --}}
</x-accelade::form>
```

## Submission Behavior

Control what happens after form submission:

```php
Form::make('settings')
    ->stay()              // Stay on page after success
    ->preserveScroll()    // Keep scroll position
    ->resetOnSuccess()    // Clear form after success
    ->restoreOnSuccess(); // Restore defaults after success
```

```blade
<x-accelade::form
    stay
    preserve-scroll
    reset-on-success
>
    {{-- Form fields --}}
</x-accelade::form>
```

## Confirmation Dialog

Require confirmation before submitting:

```php
Form::make('delete-account')
    ->confirm('Are you sure you want to delete your account?')
    ->confirmButton('Yes, delete')
    ->cancelButton('Cancel')
    ->confirmDanger();    // Red/danger styling
```

```blade
<x-accelade::form
    confirm="Are you sure you want to delete your account?"
    confirm-button="Yes, delete"
    cancel-button="Cancel"
    confirm-danger
>
    {{-- Delete form --}}
</x-accelade::form>
```

## Password Confirmation

Require password before sensitive actions:

```php
Form::make('change-email')
    ->requirePassword();      // Always require password

Form::make('settings')
    ->requirePasswordOnce();  // Once per session
```

```blade
<x-accelade::form require-password>
    {{-- Sensitive form --}}
</x-accelade::form>

<x-accelade::form require-password-once>
    {{-- Sensitive form (once per session) --}}
</x-accelade::form>
```

## Auto-Submit

Submit form automatically when fields change:

```php
Form::make('filters')
    ->submitOnChange()    // Submit on any change
    ->debounce(300);      // Wait 300ms before submitting
```

```blade
<x-accelade::form submit-on-change debounce="300">
    <x-accelade::select name="sort" :options="$sortOptions" />
    <x-accelade::select name="filter" :options="$filterOptions" />
</x-accelade::form>
```

## File Uploads

Enable multipart form data for file uploads:

```php
Form::make()
    ->withFiles();
```

```blade
<x-accelade::form with-files>
    <x-accelade::file-upload name="avatar" label="Profile Picture" />
</x-accelade::form>
```

## Model Binding

Bind Eloquent models to pre-fill forms with existing values:

```php
Form::make('profile')
    ->model($user)     // Bind model for default values
    ->schema([
        TextInput::make('name'),
        TextInput::make('email'),
    ]);
```

```blade
<x-accelade::form :model="$user">
    <x-accelade::text-input name="name" label="Name" />
    <x-accelade::text-input name="email" label="Email" type="email" />
</x-accelade::form>
```

### Unguarded Attributes

By default, guarded model attributes are protected. Enable access:

```php
// All attributes unguarded
Form::make()
    ->model($user)
    ->unguarded();

// Only specific fields
Form::make()
    ->model($user)
    ->unguarded(['name', 'email']);
```

```blade
{{-- All attributes unguarded --}}
<x-accelade::form :model="$user" unguarded>
    {{-- Fields --}}
</x-accelade::form>

{{-- Specific fields unguarded --}}
<x-accelade::form :model="$user" :unguarded="['name', 'email']">
    {{-- Fields --}}
</x-accelade::form>
```

### Default Unguarded Configuration

Set default unguarded behavior globally (typically in `AppServiceProvider::boot()`):

```php
use Accelade\Forms\Form;

// Unguard all forms by default
Form::defaultUnguarded();

// Or unguard specific fields by default
Form::defaultUnguarded(['name', 'email', 'bio']);
```

### Conditional Guarding

Use `guardWhen()` to conditionally guard based on the model type:

```php
use Accelade\Forms\Form;
use App\Models\Admin;
use App\Models\SuperUser;

// In AppServiceProvider::boot()
Form::guardWhen(function ($model) {
    // Always guard admin and super user models
    return $model instanceof Admin || $model instanceof SuperUser;
});
```

## Available Components

### Basic Inputs
- **TextInput** - Text, email, password, number, tel, url
- **Textarea** - Multi-line text input
- **Select** - Dropdown with search and multiple options
- **Checkbox** - Single checkbox
- **Radio** - Radio button group
- **Toggle** - On/off switch

### Date & Time
- **DatePicker** - Date selection
- **TimePicker** - Time selection
- **DateTimePicker** - Combined date and time
- **DateRangePicker** - Date range selection

### Numeric
- **NumberField** - Number with stepper buttons
- **Slider** - Range slider

### Rich Content
- **RichEditor** - WYSIWYG editor
- **MarkdownEditor** - Markdown with preview
- **ColorPicker** - Color selection

### Advanced
- **TagsInput** - Tag/chip input
- **KeyValue** - Key-value pair editor
- **Repeater** - Repeatable field groups
- **FileUpload** - File uploads
- **PinInput** - PIN/OTP input
- **RateInput** - Star rating
- **IconPicker** - Icon selection

## Common Methods

All field components share these methods:

```php
->label('Field Label')     // Set label
->placeholder('Hint...')   // Set placeholder
->helperText('Help text')  // Add helper text
->hint('Hint message')     // Add hint
->required()               // Mark as required
->disabled()               // Disable field
->readonly()               // Make read-only
->default('value')         // Set default value
->hidden()                 // Hide field conditionally
```

```blade
<x-accelade::text-input
    name="field"
    label="Field Label"
    placeholder="Hint..."
    hint="Hint message"
    required
    disabled
/>
```

## Form Methods Reference

| Method | Description |
|--------|-------------|
| `action($url)` | Set form action URL |
| `method($method)` | Set HTTP method (POST, PUT, PATCH, DELETE) |
| `id($id)` | Set form ID |
| `schema($fields)` | Set form fields array |
| `model($model)` | Bind model for default values |
| `unguarded($fields)` | Enable unguarded attribute access |
| `withFiles()` | Enable file upload support |
| `submitLabel($label)` | Set submit button text |
| `withoutSubmit()` | Hide submit button |
| `stay()` | Stay on page after success |
| `preserveScroll()` | Preserve scroll position |
| `resetOnSuccess()` | Reset form on success |
| `restoreOnSuccess()` | Restore defaults on success |
| `confirm($text)` | Enable confirmation dialog |
| `confirmButton($text)` | Set confirm button text |
| `cancelButton($text)` | Set cancel button text |
| `confirmDanger()` | Style as danger confirmation |
| `requirePassword()` | Require password confirmation |
| `requirePasswordOnce()` | Require password once per session |
| `submitOnChange()` | Auto-submit on field change |
| `debounce($ms)` | Set debounce time for auto-submit |
| `background()` | Enable background submission |
| `blob()` | Enable blob/file download handling |

## Static Methods

| Method | Description |
|--------|-------------|
| `defaultUnguarded($fields)` | Set default unguarded fields globally |
| `guardWhen($callback)` | Set conditional guarding callback |
| `flushState()` | Reset static configuration (for testing) |

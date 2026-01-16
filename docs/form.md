# Form

The Form component provides a container for form fields with built-in submission handling, confirmation dialogs, and model binding.

## Basic Usage

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

## HTTP Methods

```php
Form::make()->method('POST');   // Default
Form::make()->method('PUT');    // Update
Form::make()->method('PATCH');  // Partial update
Form::make()->method('DELETE'); // Delete
```

## File Uploads

Enable multipart form data for file uploads:

```php
Form::make()
    ->withFiles();
```

## Submit Button

### Custom Label

```php
Form::make()->submitLabel('Save Changes');
```

### Hide Submit Button

```php
Form::make()->withoutSubmit();
```

## Submission Behavior

### Stay on Page

Prevent navigation after successful submission:

```php
Form::make()->stay();
```

### Preserve Scroll

Keep scroll position after submission:

```php
Form::make()->preserveScroll();
```

### Reset Form

Clear form data after success:

```php
Form::make()->resetOnSuccess();
```

### Restore Defaults

Restore form to default values after success:

```php
Form::make()->restoreOnSuccess();
```

## Confirmation Dialog

Require confirmation before submitting:

```php
Form::make()
    ->confirm('Are you sure?')
    ->confirmButton('Yes, proceed')
    ->cancelButton('Cancel');
```

### Danger Confirmation

Style the confirmation for destructive actions:

```php
Form::make()
    ->confirmText('Delete this item?')
    ->confirmButton('Delete')
    ->confirmDanger();
```

## Password Confirmation

Require password for sensitive actions:

```php
// Always require password
Form::make()->requirePassword();

// Require once per session
Form::make()->requirePasswordOnce();
```

## Auto-Submit

Submit form automatically when fields change:

```php
Form::make()
    ->submitOnChange()
    ->debounce(300);  // Wait 300ms before submitting
```

## Background Submission

Keep inputs enabled during form submission:

```php
Form::make()->background();
```

## Blob/File Download

Handle file download responses:

```php
Form::make()->blob();
```

## Model Binding

Bind an Eloquent model to pre-fill form fields with existing values:

```php
Form::make('profile')
    ->model($user)
    ->schema([
        TextInput::make('name'),
        TextInput::make('email'),
    ]);
```

### Unguarded Attributes

By default, guarded model attributes are protected. Enable access to all attributes:

```php
Form::make()
    ->model($user)
    ->unguarded();  // Access all attributes including guarded ones
```

### Selective Unguarding

Unguard only specific fields:

```php
Form::make()
    ->model($user)
    ->unguarded(['name', 'email']);  // Only these fields are unguarded
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

Use `guardWhen()` to conditionally guard based on the model type. This is useful when certain resources should always be guarded:

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

The callback receives the bound model and should return `true` if the model should be guarded (protected), or `false` if it should be unguarded.

### Checking Guard Status

Check if a form or specific field is unguarded:

```php
$form = Form::make()->model($user)->unguarded(['name', 'email']);

// Check if form has any unguarding
$form->isUnguarded();  // true

// Check specific field
$form->isFieldUnguarded('name');     // true
$form->isFieldUnguarded('email');    // true
$form->isFieldUnguarded('password'); // false

// Check if model should be guarded based on guardWhen callback
$form->shouldGuardModel();
```

## Extra Attributes

Add custom HTML attributes:

```php
Form::make()
    ->extraAttributes([
        'data-turbo' => 'false',
        'x-data' => '{ loading: false }',
    ]);
```

## Validation Rules

Extract validation rules from all fields:

```php
$form = Form::make()
    ->schema([
        TextInput::make('name')->required(),
        TextInput::make('email')->required()->rules(['email']),
    ]);

$rules = $form->getValidationRules();
// ['name' => ['required'], 'email' => ['required', 'email']]
```

## Methods Reference

### Instance Methods

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
| `withSubmit()` | Show submit button |
| `stay()` | Stay on page after success |
| `preserveScroll()` | Preserve scroll position |
| `resetOnSuccess()` | Reset form on success |
| `restoreOnSuccess()` | Restore defaults on success |
| `confirm($text)` | Enable confirmation dialog |
| `confirmText($text)` | Set confirmation message |
| `confirmButton($text)` | Set confirm button text |
| `cancelButton($text)` | Set cancel button text |
| `confirmDanger()` | Style as danger confirmation |
| `requirePassword()` | Require password confirmation |
| `requirePasswordOnce()` | Require password once per session |
| `submitOnChange()` | Auto-submit on field change |
| `debounce($ms)` | Set debounce time for auto-submit |
| `background()` | Enable background submission |
| `blob()` | Enable blob/file download handling |
| `extraAttributes($attrs)` | Add custom HTML attributes |

### Static Methods

| Method | Description |
|--------|-------------|
| `defaultUnguarded($fields)` | Set default unguarded fields globally |
| `guardWhen($callback)` | Set conditional guarding callback |
| `flushState()` | Reset static configuration (for testing) |

### Getter Methods

| Method | Description |
|--------|-------------|
| `getAction()` | Get form action URL |
| `getMethod()` | Get HTTP method |
| `getFormMethod()` | Get actual form method (GET/POST) |
| `getId()` | Get form ID |
| `getSchema()` | Get all fields |
| `getVisibleSchema()` | Get visible fields only |
| `getModel()` | Get bound model |
| `isUnguarded()` | Check if unguarded |
| `isFieldUnguarded($name)` | Check if specific field is unguarded |
| `getUnguardedFields()` | Get array of unguarded field names |
| `shouldGuardModel()` | Check if model should be guarded |
| `hasModel()` | Check if model is bound |
| `hasFiles()` | Check if file uploads enabled |
| `getEnctype()` | Get form enctype |
| `getSubmitLabel()` | Get submit button label |
| `shouldShowSubmit()` | Check if submit button shown |
| `getValidationRules()` | Extract validation rules from fields |

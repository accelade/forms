# Submit

The Submit component provides a form submission button with built-in loading spinner and styling variants.

## Basic Usage

```php
use Accelade\Forms\Components\Submit;

Submit::make();
```

## Custom Label

```php
Submit::make('Save Changes');

// Or using the label method
Submit::make()->label('Apply Settings');
```

## Loading Spinner

By default, the submit button shows a loading spinner during form submission. You can disable it:

```php
Submit::make('Submit')->spinner(false);
```

## Styling Variants

### Primary (Default)

```php
Submit::make('Save');
```

### Danger

For destructive actions like deletions:

```php
Submit::make('Delete Account')->danger();
```

### Secondary

For less prominent actions:

```php
Submit::make('Save Draft')->secondary();
```

## Disabled State

```php
Submit::make('Submit')->disabled();
```

## Button Type

Change the button type (default is `submit`):

```php
Submit::make('Click Me')->type('button');
```

## Extra Attributes

Add custom HTML attributes:

```php
Submit::make('Delete')
    ->danger()
    ->extraAttributes([
        'data-confirm' => 'Are you sure?',
        'onclick' => 'return confirm(this.dataset.confirm)',
    ]);
```

## Usage in Forms

```php
use Accelade\Forms\Form;
use Accelade\Forms\Components\TextInput;
use Accelade\Forms\Components\Submit;

Form::make('contact')
    ->schema([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),
    ])
    ->submitButton(Submit::make('Send Message'));
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `label($label)` | Set the button text |
| `spinner($bool)` | Enable/disable loading spinner |
| `danger()` | Apply danger/destructive styling |
| `secondary()` | Apply secondary styling |
| `disabled()` | Disable the button |
| `type($type)` | Set button type (submit, button, reset) |
| `extraAttributes($attrs)` | Add custom HTML attributes |

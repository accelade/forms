# Group

The Group component bundles related form elements together with shared label and error display. This is particularly useful for grouping checkboxes and radio buttons.

## Basic Usage

```php
use Accelade\Forms\Components\Group;
use Accelade\Forms\Components\Checkbox;

Group::make('tags')
    ->label('Select Tags')
    ->schema([
        Checkbox::make('tags[]')->label('Laravel')->value('laravel'),
        Checkbox::make('tags[]')->label('Vue.js')->value('vue'),
        Checkbox::make('tags[]')->label('Tailwind')->value('tailwind'),
    ]);
```

## Radio Button Groups

Group radio buttons with shared validation:

```php
use Accelade\Forms\Components\Group;
use Accelade\Forms\Components\Radio;

Group::make('theme')
    ->label('Choose a theme')
    ->schema([
        Radio::make('theme')
            ->options([
                'light' => 'Light Theme',
                'dark' => 'Dark Theme',
                'system' => 'System Default',
            ]),
    ]);
```

## Inline Layout

Display grouped elements horizontally:

```php
Group::make('notification_channel')
    ->label('Preferred notification channel')
    ->inline()
    ->schema([
        Radio::make('notification_channel')
            ->options([
                'email' => 'Email',
                'sms' => 'SMS',
                'push' => 'Push',
            ]),
    ]);
```

## Stacked Layout

Display grouped elements vertically:

```php
Group::make('preferences')
    ->label('Preferences')
    ->inline(false)
    ->schema([
        Checkbox::make('preferences[]')->label('Receive updates')->value('updates'),
        Checkbox::make('preferences[]')->label('Marketing emails')->value('marketing'),
    ]);
```

## Error Display

By default, validation errors are shown at the group level, not on individual elements. This provides cleaner error display for grouped fields:

```php
Group::make('tags')
    ->label('Select at least one tag')
    ->required()
    ->schema([
        Checkbox::make('tags[]')->label('Option 1')->value('1'),
        Checkbox::make('tags[]')->label('Option 2')->value('2'),
    ]);
```

### Hide Group Errors

If you want to handle errors differently:

```php
Group::make('items')
    ->showErrors(false)
    ->schema([...]);
```

### Show Child Errors

Enable errors on individual child elements:

```php
Group::make('items')
    ->showChildErrors()
    ->schema([...]);
```

## Helper Text

Add helper text below the group:

```php
Group::make('permissions')
    ->label('Permissions')
    ->helperText('Select the permissions for this role')
    ->schema([...]);
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `label($label)` | Set the group label |
| `schema($fields)` | Set child fields |
| `inline()` | Display elements horizontally |
| `inline(false)` | Display elements vertically |
| `showErrors($bool)` | Show/hide group-level errors |
| `showChildErrors($bool)` | Show/hide errors on children |
| `helperText($text)` | Add helper text |
| `required()` | Mark group as required |

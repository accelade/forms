# Select

The Select component provides a dropdown selection with support for searching, multiple selection, and grouped options.

## Basic Usage

```php
use Accelade\Forms\Components\Select;

Select::make('country')
    ->label('Country')
    ->options([
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ]);
```

```blade
{{-- Blade Component --}}
<x-accelade::select
    name="country"
    label="Country"
    placeholder="Select a country..."
    :options="[
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ]"
/>
```

## Searchable Select

Enable search functionality for large option lists:

```php
Select::make('country')
    ->label('Country')
    ->options($countries)
    ->searchable();
```

```blade
<x-accelade::select
    name="user_id"
    label="User"
    :options="$users"
    searchable
/>
```

## Multiple Selection

Allow selecting multiple options:

```php
Select::make('skills')
    ->label('Skills')
    ->options([
        'php' => 'PHP',
        'js' => 'JavaScript',
        'python' => 'Python',
        'go' => 'Go',
    ])
    ->multiple();
```

```blade
<x-accelade::select
    name="skills"
    label="Skills"
    :options="[
        'php' => 'PHP',
        'js' => 'JavaScript',
        'python' => 'Python',
    ]"
    multiple
    hint="Hold Ctrl/Cmd to select multiple"
/>
```

## Empty/Placeholder Option

Add a placeholder option:

```php
Select::make('category')
    ->label('Category')
    ->options($categories)
    ->emptyOptionLabel('Select a category...');
```

```blade
<x-accelade::select
    name="category"
    label="Category"
    :options="$categories"
    placeholder="Select a category..."
/>
```

## Grouped Options

Group options into categories:

```php
Select::make('timezone')
    ->label('Timezone')
    ->options([
        'America' => [
            'america/new_york' => 'New York',
            'america/los_angeles' => 'Los Angeles',
        ],
        'Europe' => [
            'europe/london' => 'London',
            'europe/paris' => 'Paris',
        ],
    ]);
```

```blade
<x-accelade::select
    name="timezone"
    label="Timezone"
    placeholder="Select timezone..."
    :options="[
        'Americas' => [
            'est' => 'Eastern Time (EST)',
            'cst' => 'Central Time (CST)',
            'pst' => 'Pacific Time (PST)',
        ],
        'Europe' => [
            'gmt' => 'Greenwich Mean Time (GMT)',
            'cet' => 'Central European Time (CET)',
        ],
    ]"
/>
```

## Dynamic Options

Load options from a relationship or callback:

```php
Select::make('user_id')
    ->label('User')
    ->options(fn () => User::pluck('name', 'id'))
    ->searchable();
```

## Preloaded Options

Preload options on page load for better UX:

```php
Select::make('category')
    ->options($categories)
    ->preload();
```

## Native Select

Use native HTML select instead of custom dropdown:

```php
Select::make('country')
    ->options($countries)
    ->native();
```

## Choices.js Integration

Enable Choices.js for enhanced select functionality:

```php
Select::make('country')
    ->options($countries)
    ->choices();
```

With custom Choices.js options:

```php
Select::make('country')
    ->options($countries)
    ->choices([
        'searchEnabled' => true,
        'removeItemButton' => true,
    ]);
```

```blade
<x-accelade::select
    name="tags"
    label="Tags"
    :options="$tags"
    multiple
    :choices="['removeItemButton' => true, 'searchEnabled' => true]"
/>
```

### Default Choices Options

Set default Choices.js options globally for all select instances:

```php
use Accelade\Forms\Components\Select;

// In AppServiceProvider::boot()
Select::defaultChoices([
    'removeItemButton' => true,
    'placeholderValue' => 'Select an option',
]);
```

Now all select fields with `->choices()` enabled will use these defaults:

```php
// Will use the default options
Select::make('country')->choices();

// Override for specific field
Select::make('tags')->choices(['searchEnabled' => false]);
```

## Object Collections

When working with collections of objects (like Eloquent models), specify which properties to use for labels and values:

```php
Select::make('user_id')
    ->options(User::all())
    ->optionLabel('name')
    ->optionValue('id');
```

## Remote Options (Async Loading)

Load options from a remote URL asynchronously:

```php
Select::make('country')
    ->remoteUrl('/api/countries')
    ->optionLabel('name')
    ->optionValue('code');
```

### Remote Root Path

Extract options from nested response data:

```php
Select::make('country')
    ->remoteUrl('/api/countries')
    ->remoteRoot('data.countries')
    ->optionLabel('name')
    ->optionValue('code');
```

### Auto-Select First Remote Option

Automatically select the first option when remote data loads:

```php
Select::make('country')
    ->remoteUrl('/api/countries')
    ->selectFirstRemoteOption();
```

### Reset on URL Change

Reset the selection when the remote URL changes (useful for dependent selects):

```php
Select::make('city')
    ->remoteUrl('/api/cities?country=' . $selectedCountry)
    ->resetOnNewRemoteUrl();
```

## Eloquent Relations

For BelongsToMany or MorphToMany relationships:

```php
Select::make('tags')
    ->relation('tags')
    ->options(Tag::pluck('name', 'id'));
```

The `relation()` method automatically enables multiple selection.

## Custom Option Label Callback

Use a callback to generate option labels:

```php
Select::make('user_id')
    ->options(User::all()->pluck('id'))
    ->getOptionLabelUsing(fn ($id) => User::find($id)?->full_name);
```

## States

```php
// Required
Select::make('category')
    ->label('Category')
    ->options($categories)
    ->required();

// Disabled
Select::make('locked')
    ->label('Locked')
    ->options($options)
    ->disabled();
```

```blade
{{-- Required select --}}
<x-accelade::select
    name="category"
    label="Category"
    :options="$categories"
    required
/>

{{-- Disabled select --}}
<x-accelade::select
    name="locked"
    label="Locked"
    :options="$options"
    disabled
/>
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `options($options)` | Set available options |
| `searchable()` | Enable search functionality |
| `multiple()` | Allow multiple selection |
| `emptyOptionLabel($label)` | Set placeholder label |
| `disableEmptyOption()` | Remove empty/placeholder option |
| `preload()` | Preload options on mount |
| `native()` | Use native HTML select |
| `choices($options)` | Enable Choices.js with optional config |
| `optionLabel($property)` | Property name for option labels |
| `optionValue($property)` | Property name for option values |
| `remoteUrl($url)` | URL for async option loading |
| `remoteRoot($path)` | Path to extract options from response |
| `selectFirstRemoteOption()` | Auto-select first remote option |
| `resetOnNewRemoteUrl()` | Reset selection when URL changes |
| `relation($name)` | Set Eloquent relation name |
| `getOptionLabelUsing($callback)` | Custom label callback |
| `getOptionsUsing($callback)` | Dynamic options callback |

## Static Methods

| Method | Description |
|--------|-------------|
| `defaultChoices($options)` | Set default Choices.js options globally |

## Creating Options in a Modal

You can define a custom form that allows users to create new records directly from the select dropdown. This is useful when users need to add a new option that doesn't exist yet.

```php
use Accelade\Forms\Components\Select;
use Accelade\Forms\Components\TextInput;

Select::make('author_id')
    ->label('Author')
    ->model(User::class, 'name', 'id')
    ->createOptionForm([
        TextInput::make('name')
            ->label('Full Name')
            ->required(),
        TextInput::make('email')
            ->label('Email Address')
            ->required(),
    ])
    ->createOptionModalHeading('Create New Author')
    ->createOptionModalSubmitButtonLabel('Create Author');
```

The form opens in a modal when the user clicks the "+" button. Upon form submission, the new record is created and automatically selected.

### Customizing Option Creation

You can customize the creation process using the `createOptionUsing()` method, which should return the primary key of the newly created record:

```php
Select::make('author_id')
    ->model(User::class, 'name', 'id')
    ->createOptionForm([
        TextInput::make('name')->required(),
        TextInput::make('email')->required(),
    ])
    ->createOptionUsing(function (array $data): int {
        return auth()->user()->team->members()->create($data)->getKey();
    });
```

## Editing the Selected Option in a Modal

You can define a custom form that allows users to edit the currently selected record:

```php
Select::make('author_id')
    ->label('Author')
    ->model(User::class, 'name', 'id')
    ->editOptionForm([
        TextInput::make('name')
            ->label('Full Name')
            ->required(),
        TextInput::make('email')
            ->label('Email Address')
            ->required(),
    ])
    ->editOptionModalHeading('Edit Author')
    ->editOptionModalSubmitButtonLabel('Update Author');
```

The form opens in a modal when the user clicks the pencil icon (visible when an option is selected). The form fields are automatically pre-filled with the current record's data.

### Customizing Option Updates

You can customize the update process using the `updateOptionUsing()` method:

```php
Select::make('author_id')
    ->model(User::class, 'name', 'id')
    ->editOptionForm([
        TextInput::make('name')->required(),
        TextInput::make('email')->required(),
    ])
    ->updateOptionUsing(function (array $data, $record) {
        $record->update($data);
    });
```

## Complete Create/Edit Example

Here's a full example with both create and edit functionality:

```php
Select::make('user_id')
    ->label('User')
    ->model(User::class, 'name', 'id')
    ->limit(20)
    ->allowClear()
    ->emptyOptionLabel('Select user...')
    ->createOptionForm([
        TextInput::make('name')
            ->label('Full Name')
            ->required(),
        TextInput::make('email')
            ->label('Email Address')
            ->required(),
        TextInput::make('password')
            ->label('Password')
            ->required()
            ->type('password'),
    ])
    ->createOptionModalHeading('Create New User')
    ->createOptionModalSubmitButtonLabel('Create User')
    ->editOptionForm([
        TextInput::make('name')
            ->label('Full Name')
            ->required(),
        TextInput::make('email')
            ->label('Email Address')
            ->required(),
    ])
    ->editOptionModalHeading('Edit User')
    ->editOptionModalSubmitButtonLabel('Update User');
```

## Success Notifications

By default, success notifications are shown after creating or updating a record. You can customize the notification messages:

```php
Select::make('user_id')
    ->model(User::class, 'name', 'id')
    ->createOptionForm([...])
    ->editOptionForm([...])
    ->createSuccessNotificationTitle('User Created')
    ->createSuccessNotificationBody('The new user has been created and selected.')
    ->updateSuccessNotificationTitle('User Updated')
    ->updateSuccessNotificationBody('The user information has been saved.');
```

To disable notifications entirely:

```php
Select::make('user_id')
    ->model(User::class, 'name', 'id')
    ->createOptionForm([...])
    ->successNotification(false);
```

## Model-Based Options with Pagination

For large datasets, use the `model()` method to enable server-side search and pagination:

```php
Select::make('user_id')
    ->label('User')
    ->model(User::class, 'name', 'id')
    ->limit(20)           // Show 20 options per page
    ->searchable()        // Enable server-side search
    ->allowClear();       // Allow clearing the selection
```

This automatically:
- Paginates options (infinite scroll)
- Searches on the server when the user types
- Generates secure API endpoints for data fetching

## Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `placeholder` | string | Placeholder/empty option text |
| `options` | array | Options array (key => label) |
| `value` | mixed | Selected value(s) |
| `hint` | string | Help text below select |
| `multiple` | bool | Allow multiple selection |
| `searchable` | bool | Enable search functionality |
| `choices` | array | Choices.js configuration |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable select |

## Create/Edit Modal Methods

| Method | Description |
|--------|-------------|
| `createOptionForm($fields)` | Define form fields for creating new options |
| `createOptionUsing($callback)` | Custom callback for creating records |
| `createOptionModalHeading($heading)` | Modal title for create form |
| `createOptionModalSubmitButtonLabel($label)` | Submit button text for create form |
| `editOptionForm($fields)` | Define form fields for editing selected option |
| `updateOptionUsing($callback)` | Custom callback for updating records |
| `editOptionModalHeading($heading)` | Modal title for edit form |
| `editOptionModalSubmitButtonLabel($label)` | Submit button text for edit form |

## Notification Methods

| Method | Description |
|--------|-------------|
| `successNotification($enabled)` | Enable/disable success notifications (default: true) |
| `createSuccessNotificationTitle($title)` | Title for create success notification |
| `createSuccessNotificationBody($body)` | Body text for create success notification |
| `updateSuccessNotificationTitle($title)` | Title for update success notification |
| `updateSuccessNotificationBody($body)` | Body text for update success notification |

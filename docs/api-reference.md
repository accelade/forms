# API Reference

Complete API reference for all Accelade Forms components and methods.

## Form

The `Form` class is the main container for grouping form fields.

### Creating a Form

```php
use Accelade\Forms\Form;

$form = Form::make('contact');
```

### Basic Methods

| Method | Description |
|--------|-------------|
| `make(?string $id)` | Create a new form instance |
| `id(string $id)` | Set the form ID |
| `action(string $url)` | Set the form action URL |
| `method(string $method)` | Set HTTP method (GET, POST, PUT, PATCH, DELETE) |
| `schema(array $fields)` | Set the form fields |
| `model(mixed $model)` | Bind a model for default values |
| `withFiles()` | Enable multipart/form-data for file uploads |

### Submit Button

| Method | Description |
|--------|-------------|
| `submitLabel(string $label)` | Set submit button text |
| `withSubmit(bool $show)` | Show/hide submit button |
| `withoutSubmit()` | Hide submit button |

### Submission Behavior

| Method | Description |
|--------|-------------|
| `stay()` | Stay on page after successful submission |
| `preserveScroll()` | Preserve scroll position after submission |
| `resetOnSuccess()` | Reset form data after successful submission |
| `restoreOnSuccess()` | Restore form to defaults after success |
| `background()` | Submit without disabling inputs |
| `blob()` | Handle file download responses |

### Confirmation Dialog

| Method | Description |
|--------|-------------|
| `confirm(bool\|string $confirm)` | Enable confirmation dialog (optionally with message) |
| `confirmText(string $text)` | Set confirmation dialog message |
| `confirmButton(string $text)` | Set confirm button text |
| `cancelButton(string $text)` | Set cancel button text |
| `confirmDanger()` | Style confirmation as danger/destructive |

### Password Confirmation

| Method | Description |
|--------|-------------|
| `requirePassword()` | Require password before submission |
| `requirePasswordOnce()` | Require password once per session |

### Auto-Submit

| Method | Description |
|--------|-------------|
| `submitOnChange()` | Submit automatically when fields change |
| `debounce(int $ms)` | Debounce time in milliseconds |

### Model Binding

| Method | Description |
|--------|-------------|
| `model(mixed $model)` | Bind model for default values |
| `unguarded(bool\|array $fields)` | Enable unguarded access (true for all, or array of field names) |
| `isUnguarded()` | Check if form has unguarding enabled |
| `isFieldUnguarded(string $name)` | Check if specific field is unguarded |
| `getUnguardedFields()` | Get array of unguarded field names |
| `shouldGuardModel()` | Check if model should be guarded based on guardWhen callback |

### Static Configuration

| Method | Description |
|--------|-------------|
| `defaultUnguarded(bool\|array $fields)` | Set default unguarded fields globally |
| `getDefaultUnguarded()` | Get the default unguarded setting |
| `guardWhen(?Closure $callback)` | Set callback to determine when model should be guarded |
| `getGuardWhenCallback()` | Get the guard condition callback |
| `flushState()` | Reset static configuration (for testing) |

### Extra Attributes

| Method | Description |
|--------|-------------|
| `extraAttributes(array $attrs)` | Add custom HTML attributes |

---

## Field (Base Class)

All field components extend the base `Field` class and share these methods.

### Creating Fields

```php
use Accelade\Forms\Components\TextInput;

$field = TextInput::make('email');
```

### Labels & Text

| Method | Description |
|--------|-------------|
| `label(string $label)` | Set field label |
| `placeholder(string $text)` | Set placeholder text |
| `helperText(string $text)` | Add helper text below field |
| `hint(string $text)` | Add hint text |
| `prefix(string $text)` | Add prefix text/icon |
| `suffix(string $text)` | Add suffix text/icon |

### State

| Method | Description |
|--------|-------------|
| `default(mixed $value)` | Set default value |
| `required(bool $required)` | Mark as required |
| `disabled(bool $disabled)` | Disable the field |
| `readonly(bool $readonly)` | Make read-only |
| `hidden(bool $hidden)` | Hide the field |
| `autofocus()` | Focus on page load |

### Validation

| Method | Description |
|--------|-------------|
| `rules(array $rules)` | Add validation rules |
| `required()` | Add required rule |
| `nullable()` | Allow null values |

### Styling

| Method | Description |
|--------|-------------|
| `extraAttributes(array $attrs)` | Add custom HTML attributes |
| `extraInputAttributes(array $attrs)` | Add attributes to input element |

---

## TextInput

Text input for single-line text, email, password, etc.

```php
use Accelade\Forms\Components\TextInput;

TextInput::make('email')
    ->email()
    ->required();
```

### Type Methods

| Method | Description |
|--------|-------------|
| `type(string $type)` | Set input type |
| `email()` | Set type to email |
| `password()` | Set type to password |
| `tel()` | Set type to telephone |
| `url()` | Set type to URL |
| `numeric()` | Set type to number |

### Constraints

| Method | Description |
|--------|-------------|
| `minLength(int $length)` | Minimum character length |
| `maxLength(int $length)` | Maximum character length |
| `mask(string $mask)` | Apply input mask |
| `autocomplete(string $value)` | Set autocomplete attribute |
| `datalist(array $options)` | Add datalist suggestions |
| `inputmode(string $mode)` | Set inputmode attribute |
| `pattern(string $regex)` | Set pattern validation |

### Splade Compatibility

| Method | Description |
|--------|-------------|
| `prepend(string $text)` | Alias for prefix() |
| `append(string $text)` | Alias for suffix() |

---

## Textarea

Multi-line text input.

```php
use Accelade\Forms\Components\Textarea;

Textarea::make('message')
    ->rows(5)
    ->autosize();
```

### Methods

| Method | Description |
|--------|-------------|
| `rows(int $rows)` | Set visible rows |
| `cols(int $cols)` | Set visible columns |
| `autosize()` | Auto-expand with content |
| `minLength(int $length)` | Minimum character length |
| `maxLength(int $length)` | Maximum character length |

---

## Select

Dropdown select with optional search and multiple selection.

```php
use Accelade\Forms\Components\Select;

Select::make('country')
    ->options(['us' => 'USA', 'uk' => 'UK'])
    ->searchable();
```

### Basic Methods

| Method | Description |
|--------|-------------|
| `options(array\|Closure $options)` | Set available options |
| `searchable(bool $condition)` | Enable search/filter |
| `multiple(bool $condition)` | Allow multiple selections |
| `emptyOptionLabel(string $label)` | Placeholder option text |
| `preload(bool $condition)` | Preload all options |

### Object Collections (Splade/Filament)

| Method | Description |
|--------|-------------|
| `optionLabel(string $key)` | Key/attribute for option labels |
| `optionValue(string $key)` | Key/attribute for option values |

### Choices.js Integration (Splade)

| Method | Description |
|--------|-------------|
| `choices(bool\|array $options)` | Enable Choices.js (optionally with config) |
| `hasChoices()` | Check if Choices.js is enabled |
| `getChoicesOptions()` | Get Choices.js configuration |

### Static Configuration

| Method | Description |
|--------|-------------|
| `defaultChoices(array $options)` | Set default Choices.js options globally |

### Remote Options (Splade)

| Method | Description |
|--------|-------------|
| `remoteUrl(string $url)` | URL for loading options asynchronously |
| `remoteRoot(string $key)` | JSON key containing options array |
| `getRemoteUrl()` | Get the remote URL |
| `getRemoteRoot()` | Get the remote root key |
| `hasRemoteUrl()` | Check if remote URL is set |

### Eloquent Relations (Splade)

| Method | Description |
|--------|-------------|
| `relation(?string $name)` | Bind to Eloquent relation |
| `getRelation()` | Get the relation name |
| `hasRelation()` | Check if relation is set |

---

## Checkbox

Single checkbox for boolean values.

```php
use Accelade\Forms\Components\Checkbox;

Checkbox::make('terms')
    ->label('I agree to the terms')
    ->required();
```

### Methods

| Method | Description |
|--------|-------------|
| `inline(bool $condition)` | Display label inline |
| `accepted()` | Must be checked (for terms) |

### Value Handling (Splade)

| Method | Description |
|--------|-------------|
| `checkedValue(mixed $value)` | Value when checked (default: true) |
| `uncheckedValue(mixed $value)` | Value when unchecked (default: false) |
| `value(mixed $value)` | Alias for checkedValue() |
| `falseValue(mixed $value)` | Alias for uncheckedValue() |
| `getCheckedValue()` | Get the checked value |
| `getUncheckedValue()` | Get the unchecked value |

---

## CheckboxList

Multiple checkboxes from options.

```php
use Accelade\Forms\Components\CheckboxList;

CheckboxList::make('notifications')
    ->options([
        'email' => 'Email',
        'sms' => 'SMS',
        'push' => 'Push',
    ]);
```

### Methods

| Method | Description |
|--------|-------------|
| `options(array\|Closure $options)` | Set available options |
| `columns(int $cols)` | Number of columns |
| `inline(bool $condition)` | Display inline |

### Eloquent Relations (Splade)

| Method | Description |
|--------|-------------|
| `relation(?string $name)` | Bind to Eloquent relation |
| `getRelation()` | Get the relation name |
| `hasRelation()` | Check if relation is set |

---

## Radio

Radio button group for single selection.

```php
use Accelade\Forms\Components\Radio;

Radio::make('plan')
    ->options([
        'basic' => 'Basic',
        'pro' => 'Professional',
    ]);
```

### Methods

| Method | Description |
|--------|-------------|
| `options(array\|Closure $options)` | Set available options |
| `inline(bool $condition)` | Display inline |
| `boolean()` | Yes/No options |

### Grid Layout (Splade)

| Method | Description |
|--------|-------------|
| `columns(int $columns)` | Display in grid with N columns |
| `getColumns()` | Get the column count |
| `hasColumns()` | Check if columns > 1 |

---

## Toggle

On/off toggle switch.

```php
use Accelade\Forms\Components\Toggle;

Toggle::make('notifications')
    ->onLabel('On')
    ->offLabel('Off');
```

### Methods

| Method | Description |
|--------|-------------|
| `onLabel(string $label)` | Label when on |
| `offLabel(string $label)` | Label when off |
| `onColor(string $color)` | Color when on |
| `offColor(string $color)` | Color when off |
| `inline(bool $condition)` | Display inline with label |

---

## ToggleButtons

Button group for selecting options.

```php
use Accelade\Forms\Components\ToggleButtons;

ToggleButtons::make('view')
    ->options(['list' => 'List', 'grid' => 'Grid']);
```

### Methods

| Method | Description |
|--------|-------------|
| `options(array\|Closure $options)` | Set available options |
| `icons(array $icons)` | Set icons for options |
| `colors(array $colors)` | Set colors for options |
| `grouped(bool $condition)` | Connected button style |

---

## DatePicker

Date selection input.

```php
use Accelade\Forms\Components\DatePicker;

DatePicker::make('birthday')
    ->minDate('1900-01-01')
    ->maxDate(now());
```

### Methods

| Method | Description |
|--------|-------------|
| `minDate(Carbon\|string $date)` | Minimum selectable date |
| `maxDate(Carbon\|string $date)` | Maximum selectable date |
| `format(string $format)` | Display format |
| `displayFormat(string $format)` | Alternative display format |
| `firstDayOfWeek(int $day)` | First day (0=Sun, 1=Mon) |

---

## TimePicker

Time selection input.

```php
use Accelade\Forms\Components\TimePicker;

TimePicker::make('meeting_time')
    ->withSeconds()
    ->format('H:i:s');
```

### Methods

| Method | Description |
|--------|-------------|
| `withSeconds()` | Include seconds |
| `format(string $format)` | Time format |
| `hoursStep(int $step)` | Hours increment |
| `minutesStep(int $step)` | Minutes increment |
| `secondsStep(int $step)` | Seconds increment |

---

## DateTimePicker

Combined date and time input.

```php
use Accelade\Forms\Components\DateTimePicker;

DateTimePicker::make('event_at')
    ->minDate(now())
    ->withSeconds();
```

### Methods

| Method | Description |
|--------|-------------|
| `minDate(Carbon\|string $date)` | Minimum datetime |
| `maxDate(Carbon\|string $date)` | Maximum datetime |
| `withSeconds()` | Include seconds |
| `format(string $format)` | Datetime format |

---

## DateRangePicker

Date range selection with start and end dates.

```php
use Accelade\Forms\Components\DateRangePicker;

DateRangePicker::make('vacation')
    ->startDatePlaceholder('Check-in')
    ->endDatePlaceholder('Check-out');
```

### Methods

| Method | Description |
|--------|-------------|
| `minDate(Carbon\|string $date)` | Minimum selectable date |
| `maxDate(Carbon\|string $date)` | Maximum selectable date |
| `startDatePlaceholder(string $text)` | Start date placeholder |
| `endDatePlaceholder(string $text)` | End date placeholder |
| `numberOfMonths(int $months)` | Months to display |

---

## NumberField

Numeric input with stepper buttons.

```php
use Accelade\Forms\Components\NumberField;

NumberField::make('quantity')
    ->min(1)
    ->max(100)
    ->step(1);
```

### Methods

| Method | Description |
|--------|-------------|
| `min(int\|float $value)` | Minimum value |
| `max(int\|float $value)` | Maximum value |
| `step(int\|float $step)` | Increment step |
| `prefix(string $text)` | Prefix (e.g., "$") |
| `suffix(string $text)` | Suffix (e.g., "kg") |

---

## Slider

Range slider for numeric values.

```php
use Accelade\Forms\Components\Slider;

Slider::make('volume')
    ->min(0)
    ->max(100)
    ->step(5);
```

### Methods

| Method | Description |
|--------|-------------|
| `min(int\|float $value)` | Minimum value |
| `max(int\|float $value)` | Maximum value |
| `step(int\|float $step)` | Increment step |

---

## ColorPicker

Color selection with optional swatches.

```php
use Accelade\Forms\Components\ColorPicker;

ColorPicker::make('theme_color')
    ->swatches(['#FF0000', '#00FF00', '#0000FF']);
```

### Methods

| Method | Description |
|--------|-------------|
| `swatches(array $colors)` | Preset color swatches |
| `format(string $format)` | Color format (hex, rgb, hsl) |

---

## FileUpload

File upload with validation and FilePond integration.

```php
use Accelade\Forms\Components\FileUpload;

FileUpload::make('avatar')
    ->image()
    ->maxSize(2048);
```

### Basic Methods

| Method | Description |
|--------|-------------|
| `multiple(bool $condition)` | Allow multiple files |
| `maxSize(int $kb)` | Max file size in KB |
| `acceptedFileTypes(array $types)` | Allowed MIME types |
| `accept(array $types)` | Alias for acceptedFileTypes() |
| `image()` | Accept images only |
| `avatar()` | Circular avatar style |
| `directory(string $path)` | Upload directory |

### FilePond Integration (Splade)

| Method | Description |
|--------|-------------|
| `filepond(bool\|array $options)` | Enable FilePond (optionally with config) |
| `hasFilepond()` | Check if FilePond is enabled |
| `getFilepondOptions()` | Get FilePond configuration |

### Static Configuration

| Method | Description |
|--------|-------------|
| `defaultFilepond(array $options)` | Set default FilePond options globally |

### Image Preview (Splade)

| Method | Description |
|--------|-------------|
| `preview(bool $condition)` | Enable image preview |
| `hasPreview()` | Check if preview is enabled |

### Image Dimension Validation (Splade)

| Method | Description |
|--------|-------------|
| `minWidth(int $width)` | Minimum image width in pixels |
| `maxWidth(int $width)` | Maximum image width in pixels |
| `minHeight(int $height)` | Minimum image height in pixels |
| `maxHeight(int $height)` | Maximum image height in pixels |
| `width(int $width)` | Exact image width required |
| `height(int $height)` | Exact image height required |
| `dimensions(int $width, int $height)` | Set both exact dimensions |
| `imageDimensions(int $width, int $height)` | Alias for dimensions() |
| `minResolution(int $pixels)` | Minimum total pixels (width * height) |
| `maxResolution(int $pixels)` | Maximum total pixels |

---

## TagsInput

Tag/chip input with suggestions.

```php
use Accelade\Forms\Components\TagsInput;

TagsInput::make('skills')
    ->suggestions(['PHP', 'JavaScript', 'Python']);
```

### Methods

| Method | Description |
|--------|-------------|
| `suggestions(array $tags)` | Suggested tags |
| `separator(string $sep)` | Tag separator |
| `max(int $count)` | Maximum tags |

---

## KeyValue

Key-value pair editor.

```php
use Accelade\Forms\Components\KeyValue;

KeyValue::make('metadata')
    ->keyLabel('Property')
    ->valueLabel('Value');
```

### Methods

| Method | Description |
|--------|-------------|
| `keyLabel(string $label)` | Label for key column |
| `valueLabel(string $label)` | Label for value column |
| `addActionLabel(string $label)` | Add button text |
| `deleteActionLabel(string $label)` | Delete button text |

---

## Repeater

Repeatable field groups.

```php
use Accelade\Forms\Components\Repeater;

Repeater::make('addresses')
    ->schema([
        TextInput::make('street'),
        TextInput::make('city'),
    ]);
```

### Methods

| Method | Description |
|--------|-------------|
| `schema(array $fields)` | Fields to repeat |
| `min(int $count)` | Minimum items |
| `max(int $count)` | Maximum items |
| `addActionLabel(string $label)` | Add button text |
| `deleteActionLabel(string $label)` | Delete button text |
| `collapsible()` | Make items collapsible |
| `defaultItems(int $count)` | Initial item count |

---

## RichEditor

WYSIWYG rich text editor with Filament-compatible API.

```php
use Accelade\Forms\Components\RichEditor;

RichEditor::make('content')
    ->toolbarButtons([
        ['bold', 'italic', 'underline'],
        ['h2', 'h3'],
        ['bulletList', 'orderedList'],
        ['link'],
    ]);
```

### Basic Methods

| Method | Description |
|--------|-------------|
| `toolbarButtons(array $buttons)` | Set toolbar buttons (grouped or flat array) |
| `disableToolbarButtons(array $buttons)` | Disable specific buttons |
| `disableAllToolbarButtons()` | Disable all toolbar buttons |
| `enableToolbarButtons(array $buttons)` | Enable only specific buttons |
| `maxLength(int $length)` | Set character limit with counter |

### File Attachments

| Method | Description |
|--------|-------------|
| `fileAttachmentsDisk(string $disk)` | Set storage disk for attachments |
| `fileAttachmentsDirectory(string $dir)` | Set upload directory |
| `fileAttachmentsVisibility(string $visibility)` | Set file visibility (public/private) |

### Output Format

| Method | Description |
|--------|-------------|
| `output(string $format)` | Set output format (html, json, text) |
| `getOutputFormat()` | Get the output format |

### Available Toolbar Buttons

**Text Formatting:** `bold`, `italic`, `underline`, `strike`, `subscript`, `superscript`

**Headings:** `h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `paragraph`

**Lists & Blocks:** `bulletList`, `orderedList`, `blockquote`, `codeBlock`, `horizontalRule`

**Alignment:** `alignStart`, `alignCenter`, `alignEnd`, `alignJustify`

**Links & Media:** `link`, `unlink`, `image`, `attachFiles`, `table`

**History & Misc:** `undo`, `redo`, `clearFormatting`, `highlight`

---

## MarkdownEditor

Markdown editor with preview.

```php
use Accelade\Forms\Components\MarkdownEditor;

MarkdownEditor::make('readme')
    ->toolbarButtons(['bold', 'italic', 'code']);
```

### Methods

| Method | Description |
|--------|-------------|
| `toolbarButtons(array $buttons)` | Visible toolbar buttons |
| `disableToolbarButtons(array $buttons)` | Hide specific buttons |

---

## PinInput

PIN/OTP code input.

```php
use Accelade\Forms\Components\PinInput;

PinInput::make('otp')
    ->length(6)
    ->mask();
```

### Methods

| Method | Description |
|--------|-------------|
| `length(int $length)` | Number of digits |
| `mask()` | Hide entered values |

---

## RateInput

Star rating input.

```php
use Accelade\Forms\Components\RateInput;

RateInput::make('rating')
    ->max(5)
    ->allowHalf();
```

### Methods

| Method | Description |
|--------|-------------|
| `max(int $stars)` | Maximum stars |
| `allowHalf()` | Allow half-star ratings |
| `color(string $color)` | Star color |

---

## IconPicker

Icon selection grid.

```php
use Accelade\Forms\Components\IconPicker;

IconPicker::make('icon')
    ->columns(6);
```

### Methods

| Method | Description |
|--------|-------------|
| `columns(int $cols)` | Grid columns |
| `searchable()` | Enable search |

---

## Group

Group multiple fields together with shared label and error display.

```php
use Accelade\Forms\Components\Group;

Group::make('address')
    ->label('Address')
    ->schema([
        TextInput::make('street'),
        TextInput::make('city'),
        TextInput::make('zip'),
    ]);
```

### Methods

| Method | Description |
|--------|-------------|
| `schema(array\|Closure $fields)` | Set grouped fields |
| `getSchema()` | Get the schema array |
| `hasSchema()` | Check if schema is set |
| `inline(bool $condition)` | Display fields inline |
| `showChildErrors(bool $condition)` | Show errors for child fields |
| `shouldShowChildErrors()` | Check if child errors shown |
| `showErrors(bool $condition)` | Show group-level errors |
| `shouldShowErrors()` | Check if errors shown |

---

## Submit

Form submit button with loading spinner and styling variants.

```php
use Accelade\Forms\Components\Submit;

Submit::make('Save Changes')
    ->danger();
```

### Creating Submit Buttons

| Method | Description |
|--------|-------------|
| `make(?string $label)` | Create a submit button |
| `label(string\|Closure $label)` | Set button text |
| `getLabel()` | Get the button text |

### Loading State

| Method | Description |
|--------|-------------|
| `spinner(bool $condition)` | Enable/disable loading spinner |
| `hasSpinner()` | Check if spinner is enabled |

### Styling Variants

| Method | Description |
|--------|-------------|
| `danger(bool $condition)` | Apply danger/destructive styling |
| `isDanger()` | Check if danger style is applied |
| `secondary(bool $condition)` | Apply secondary styling |
| `isSecondary()` | Check if secondary style is applied |
| `isPrimary()` | Check if primary (default) style |

### State

| Method | Description |
|--------|-------------|
| `disabled(bool $condition)` | Disable the button |
| `isDisabled()` | Check if disabled |
| `type(string $type)` | Set button type (submit, button, reset) |
| `getType()` | Get the button type |

### Attributes

| Method | Description |
|--------|-------------|
| `extraAttributes(array $attrs)` | Add custom HTML attributes |
| `getExtraAttributes()` | Get extra attributes |

---

## Hidden

Hidden input field.

```php
use Accelade\Forms\Components\Hidden;

Hidden::make('user_id')
    ->default(auth()->id());
```

### Methods

| Method | Description |
|--------|-------------|
| `default(mixed $value)` | Set hidden value |

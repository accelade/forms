# DateTime Picker

The DateTime Picker component provides a combined date and time input for selecting datetime values.

## Basic Usage

```php
use Accelade\Forms\Components\DateTimePicker;

DateTimePicker::make('event_at')
    ->label('Event Date & Time');
```

## With Constraints

Limit the selectable date range:

```php
DateTimePicker::make('appointment')
    ->label('Appointment')
    ->minDate(now())
    ->maxDate(now()->addDays(30));
```

## With Seconds

Include seconds for precise timestamps:

```php
DateTimePicker::make('timestamp')
    ->label('Precise Timestamp')
    ->withSeconds();
```

## Custom Format

Set a custom display format:

```php
DateTimePicker::make('scheduled_at')
    ->label('Schedule')
    ->format('Y-m-d H:i');
```

## Available Methods

| Method | Description |
|--------|-------------|
| `minDate(Carbon\|string)` | Set minimum selectable datetime |
| `maxDate(Carbon\|string)` | Set maximum selectable datetime |
| `withSeconds()` | Include seconds in the picker |
| `format(string)` | Set the datetime format |
| `placeholder(string)` | Set placeholder text |
| `disabled()` | Disable the field |
| `readonly()` | Make the field read-only |

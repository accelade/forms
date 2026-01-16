# Date Picker

The DatePicker component provides date selection with calendar interface.

## Basic Usage

```php
use Accelade\Forms\Components\DatePicker;

DatePicker::make('birthdate')
    ->label('Date of Birth');
```

## Min/Max Dates

Restrict selectable date range:

```php
DatePicker::make('appointment')
    ->label('Appointment Date')
    ->minDate(now())
    ->maxDate(now()->addMonths(3));
```

## Display Format

Customize the display format:

```php
DatePicker::make('event_date')
    ->label('Event Date')
    ->displayFormat('F j, Y'); // January 1, 2024
```

## Week Start

Set the first day of the week:

```php
DatePicker::make('date')
    ->label('Date')
    ->firstDayOfWeek(1); // Monday
```

## Disable Dates

Disable specific dates:

```php
DatePicker::make('booking')
    ->label('Booking Date')
    ->disabledDates(['2024-12-25', '2024-01-01']);
```

## DateTime Picker

Include time selection:

```php
use Accelade\Forms\Components\DateTimePicker;

DateTimePicker::make('meeting')
    ->label('Meeting Time')
    ->withSeconds();
```

## Date Range Picker

Select a date range:

```php
use Accelade\Forms\Components\DateRangePicker;

DateRangePicker::make('vacation')
    ->label('Vacation Period')
    ->startDatePlaceholder('Start date')
    ->endDatePlaceholder('End date');
```

## Methods Reference

### DatePicker

| Method | Description |
|--------|-------------|
| `minDate($date)` | Set minimum date |
| `maxDate($date)` | Set maximum date |
| `displayFormat($format)` | Set display format |
| `firstDayOfWeek($day)` | Set week start day |
| `disabledDates($dates)` | Disable specific dates |
| `closeOnDateSelection()` | Close on selection |

### DateTimePicker

| Method | Description |
|--------|-------------|
| `withSeconds()` | Include seconds |
| `hoursStep($step)` | Set hour step |
| `minutesStep($step)` | Set minute step |

### DateRangePicker

| Method | Description |
|--------|-------------|
| `numberOfMonths($count)` | Show multiple months |
| `startDatePlaceholder($text)` | Set start placeholder |
| `endDatePlaceholder($text)` | Set end placeholder |
| `closeOnSelect()` | Close on selection |

## Blade Component

You can also use the DatePicker as a Blade component:

```blade
{{-- Basic date picker --}}
<x-accelade::date-picker
    name="birth_date"
    label="Birth Date"
/>

{{-- With default value --}}
<x-accelade::date-picker
    name="event_date"
    label="Event Date"
    :value="now()->format('Y-m-d')"
/>

{{-- Future dates only --}}
<x-accelade::date-picker
    name="appointment"
    label="Appointment"
    :min="now()->format('Y-m-d')"
    hint="Cannot select past dates"
/>

{{-- With date constraints --}}
<x-accelade::date-picker
    name="deadline"
    label="Deadline"
    :min="now()->format('Y-m-d')"
    :max="now()->addYear()->format('Y-m-d')"
/>

{{-- Required date --}}
<x-accelade::date-picker
    name="start_date"
    label="Start Date"
    required
/>

{{-- Date range picker --}}
<x-accelade::date-range-picker
    name="booking"
    label="Booking Period"
    start-label="Check-in"
    end-label="Check-out"
/>

{{-- DateTime picker --}}
<x-accelade::datetime-picker
    name="meeting"
    label="Meeting Time"
/>
```

### Blade Component Attributes

#### DatePicker

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string | Default value (Y-m-d format) |
| `min` | string | Minimum selectable date |
| `max` | string | Maximum selectable date |
| `format` | string | Display format |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

#### DateRangePicker

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `start-label` | string | Start date label |
| `end-label` | string | End date label |
| `min` | string | Minimum selectable date |
| `max` | string | Maximum selectable date |

#### DateTimePicker

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string | Default value |
| `with-seconds` | bool | Include seconds |

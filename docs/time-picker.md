# Time Picker

The TimePicker component provides time selection.

## Basic Usage

```php
use Accelade\Forms\Components\TimePicker;

TimePicker::make('start_time')
    ->label('Start Time');
```

## With Seconds

Include seconds in the picker:

```php
TimePicker::make('precise_time')
    ->label('Precise Time')
    ->withSeconds();
```

## 12/24 Hour Format

Set time format:

```php
TimePicker::make('meeting')
    ->label('Meeting Time')
    ->format('12'); // 12-hour with AM/PM
```

## Min/Max Time

Restrict selectable time range:

```php
TimePicker::make('appointment')
    ->label('Appointment Time')
    ->minTime('09:00')
    ->maxTime('17:00');
```

## Time Steps

Set minute intervals:

```php
TimePicker::make('slot')
    ->label('Time Slot')
    ->minutesStep(15); // 15-minute intervals
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `withSeconds()` | Include seconds |
| `format($format)` | Set 12/24 hour format |
| `minTime($time)` | Set minimum time |
| `maxTime($time)` | Set maximum time |
| `hoursStep($step)` | Set hour intervals |
| `minutesStep($step)` | Set minute intervals |
| `secondsStep($step)` | Set second intervals |

## Blade Component

You can also use the TimePicker as a Blade component:

```blade
{{-- Basic time picker --}}
<x-accelade::time-picker
    name="start_time"
    label="Start Time"
/>

{{-- With default value --}}
<x-accelade::time-picker
    name="end_time"
    label="End Time"
    value="17:00"
/>

{{-- With seconds --}}
<x-accelade::time-picker
    name="precise_time"
    label="Precise Time"
    with-seconds
    value="14:30:00"
/>

{{-- DateTime combined --}}
<x-accelade::datetime-picker
    name="appointment"
    label="Appointment"
/>

{{-- Required time --}}
<x-accelade::time-picker
    name="meeting_time"
    label="Meeting Time"
    required
/>

{{-- With min/max constraints --}}
<x-accelade::time-picker
    name="office_hours"
    label="Office Hours"
    min="09:00"
    max="17:00"
    hint="Business hours only"
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | string | Default value (HH:MM format) |
| `with-seconds` | bool | Include seconds |
| `min` | string | Minimum selectable time |
| `max` | string | Maximum selectable time |
| `step` | int | Minute step intervals |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

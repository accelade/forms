# Date Range Picker

The Date Range Picker component allows users to select a date range with start and end dates.

## Basic Usage

```php
use Accelade\Forms\Components\DateRangePicker;

DateRangePicker::make('dates')
    ->label('Select Dates');
```

## With Placeholders

Customize the placeholder text for each input:

```php
DateRangePicker::make('booking')
    ->startDatePlaceholder('Check-in')
    ->endDatePlaceholder('Check-out');
```

## With Constraints

Limit the selectable date range:

```php
DateRangePicker::make('vacation')
    ->label('Vacation Period')
    ->minDate(now())
    ->maxDate(now()->addYear());
```

## Multiple Months

Display multiple months at once:

```php
DateRangePicker::make('project_dates')
    ->label('Project Duration')
    ->numberOfMonths(2);
```

## Available Methods

| Method | Description |
|--------|-------------|
| `minDate(Carbon\|string)` | Set minimum selectable date |
| `maxDate(Carbon\|string)` | Set maximum selectable date |
| `startDatePlaceholder(string)` | Placeholder for start date input |
| `endDatePlaceholder(string)` | Placeholder for end date input |
| `numberOfMonths(int)` | Number of months to display |
| `disabled()` | Disable both inputs |
| `readonly()` | Make both inputs read-only |

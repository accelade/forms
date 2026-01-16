# Rate Input

The RateInput component provides a star rating input.

## Basic Usage

```php
use Accelade\Forms\Components\RateInput;

RateInput::make('rating')
    ->label('Rating');
```

## Max Rating

Set maximum rating value:

```php
RateInput::make('quality')
    ->label('Quality')
    ->maxRating(10);
```

## Half Ratings

Allow half-star ratings:

```php
RateInput::make('score')
    ->label('Score')
    ->allowHalf();
```

## Custom Icon

Use a custom icon:

```php
RateInput::make('hearts')
    ->label('Love It?')
    ->icon('heart');
```

## Custom Color

Set the active color:

```php
RateInput::make('rating')
    ->label('Rating')
    ->color('#fbbf24'); // Yellow
```

## Show Value

Display numeric value:

```php
RateInput::make('rating')
    ->label('Rating')
    ->showValue();
```

## Clearable

Allow clearing the rating:

```php
RateInput::make('feedback')
    ->label('Feedback')
    ->clearable();
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `maxRating($count)` | Set maximum rating |
| `allowHalf()` | Enable half ratings |
| `icon($icon)` | Set custom icon |
| `color($color)` | Set active color |
| `showValue()` | Display numeric value |
| `clearable()` | Allow clearing rating |

## Blade Component

You can also use the RateInput as a Blade component:

```blade
{{-- Basic rating --}}
<x-accelade::rate-input
    name="rating"
    label="Rating"
/>

{{-- 10-star rating --}}
<x-accelade::rate-input
    name="quality"
    label="Quality"
    :max-rating="10"
/>

{{-- Half-star ratings --}}
<x-accelade::rate-input
    name="score"
    label="Score"
    allow-half
/>

{{-- Custom heart icon --}}
<x-accelade::rate-input
    name="hearts"
    label="Love It?"
    icon="heart"
    color="#ef4444"
/>

{{-- With value display --}}
<x-accelade::rate-input
    name="feedback"
    label="Feedback"
    show-value
/>

{{-- Clearable rating --}}
<x-accelade::rate-input
    name="review"
    label="Review"
    clearable
/>

{{-- Required rating --}}
<x-accelade::rate-input
    name="experience"
    label="Rate Your Experience"
    required
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `value` | number | Default rating |
| `max-rating` | int | Maximum rating (default: 5) |
| `allow-half` | bool | Enable half ratings |
| `icon` | string | Custom icon (star, heart, etc.) |
| `color` | string | Active color |
| `show-value` | bool | Display numeric value |
| `clearable` | bool | Allow clearing rating |
| `hint` | string | Help text below input |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

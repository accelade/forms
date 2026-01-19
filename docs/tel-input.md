# Telephone Input

The TelInput component provides a telephone input field with an integrated country code selector, making it easy to collect international phone numbers.

## Basic Usage

```blade
<x-accelade::tel-input
    name="phone"
    label="Phone Number"
    placeholder="(555) 000-0000"
    :required="true"
/>
```

## Default Country

Set the initially selected country:

```blade
<x-accelade::tel-input
    name="phone"
    label="UK Phone"
    default-country="GB"
/>

<x-accelade::tel-input
    name="phone"
    label="German Phone"
    default-country="DE"
/>
```

## Preferred Countries

Show specific countries at the top of the dropdown for easy access:

```blade
<x-accelade::tel-input
    name="phone"
    :preferred-countries="['US', 'CA', 'GB', 'AU']"
/>
```

## Limit Available Countries

Restrict the country selector to specific countries:

```blade
{{-- Only show North American countries --}}
<x-accelade::tel-input
    name="phone"
    :countries="['US', 'CA', 'MX']"
/>

{{-- Only European countries --}}
<x-accelade::tel-input
    name="phone"
    :countries="['GB', 'FR', 'DE', 'ES', 'IT', 'NL', 'BE']"
/>
```

## Separate Dial Code Storage

Store the dial code in a separate field for easier processing:

```blade
<x-accelade::tel-input
    name="phone"
    :separate-dial-code="true"
/>
```

This creates additional hidden inputs:
- `phone` - The phone number without dial code
- `phone_country` - The selected country code (e.g., 'US')
- `phone_dial_code` - The dial code (e.g., '+1')

## Display Options

### Hide Flags

```blade
<x-accelade::tel-input
    name="phone"
    :show-flags="false"
/>
```

### Hide Dial Codes in Dropdown

```blade
<x-accelade::tel-input
    name="phone"
    :show-dial-code="false"
/>
```

### Disable Search

```blade
<x-accelade::tel-input
    name="phone"
    :searchable="false"
/>
```

## Validation

```blade
<x-accelade::tel-input
    name="phone"
    :required="true"
    :min-length="10"
    :max-length="15"
/>
```

## States

```blade
{{-- Disabled state --}}
<x-accelade::tel-input
    name="phone"
    :disabled="true"
/>

{{-- Readonly state --}}
<x-accelade::tel-input
    name="phone"
    :readonly="true"
/>
```

## Helper Text

```blade
<x-accelade::tel-input
    name="phone"
    hint="We will only use this for account verification"
/>
```

## Complete Example

```blade
<x-accelade::tel-input
    name="phone"
    label="Mobile Phone"
    placeholder="Enter your mobile number"
    default-country="US"
    :preferred-countries="['US', 'CA', 'GB']"
    :show-flags="true"
    :show-dial-code="true"
    :searchable="true"
    :separate-dial-code="true"
    :required="true"
    hint="We will send verification codes to this number"
/>
```

## Available Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | required | The input name attribute |
| `id` | string | name | The input id attribute |
| `label` | string | null | Label text |
| `placeholder` | string | '(555) 000-0000' | Placeholder text |
| `value` | string | null | Default value |
| `default-country` | string | 'US' | Default selected country code |
| `countries` | array | null | Limit available countries |
| `preferred-countries` | array | [] | Countries to show at top |
| `show-flags` | bool | true | Show country flags |
| `show-dial-code` | bool | true | Show dial codes in dropdown |
| `searchable` | bool | true | Enable country search |
| `separate-dial-code` | bool | false | Store dial code separately |
| `required` | bool | false | Mark as required |
| `disabled` | bool | false | Disable the input |
| `readonly` | bool | false | Make readonly |
| `hint` | string | null | Helper text below input |
| `min-length` | int | null | Minimum length |
| `max-length` | int | null | Maximum length |
| `autocomplete` | string | 'tel' | Autocomplete attribute |

## Available Countries

The component includes 55+ countries with their flags and dial codes:

| Code | Country | Dial Code |
|------|---------|-----------|
| US | United States | +1 |
| CA | Canada | +1 |
| GB | United Kingdom | +44 |
| AU | Australia | +61 |
| DE | Germany | +49 |
| FR | France | +33 |
| JP | Japan | +81 |
| CN | China | +86 |
| IN | India | +91 |
| BR | Brazil | +55 |
| ... | ... | ... |

## Form Submission

When `separate-dial-code` is enabled, you receive three fields:

```php
// In your controller
$phone = $request->input('phone');           // "5551234567"
$country = $request->input('phone_country'); // "US"
$dialCode = $request->input('phone_dial_code'); // "+1"

// Combine for full international number
$fullNumber = $dialCode . $phone; // "+15551234567"
```

When `separate-dial-code` is not enabled (default), you receive the phone number as entered:

```php
$phone = $request->input('phone'); // "(555) 123-4567"
```

# Mapbox Picker

The MapboxPicker component provides location selection using Mapbox GL with modern vector maps, search, geolocation, and draggable markers.

## Configuration

Add your Mapbox access token to your `.env` file:

```env
MAPBOX_ACCESS_TOKEN=your-access-token-here
```

## Basic Usage

```php
use Accelade\Forms\Components\MapboxPicker;

MapboxPicker::make('coordinates')
    ->label('Select Coordinates');
```

## Default Location

Set the initial map center:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->defaultLocation(51.5074, -0.1278); // London
```

## Zoom Level

Set the initial zoom level (0-22):

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->zoom(14);
```

## Map Height

Set the map container height:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->height('400px');
```

## Map Style

Set the Mapbox style:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->style('streets-v12'); // Default

// Available styles:
// streets-v12, light-v11, dark-v11, satellite-v9,
// satellite-streets-v12, outdoors-v12,
// navigation-day-v1, navigation-night-v1
```

## Draggable Marker

Enable dragging the marker to select location:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->draggable(); // Enabled by default
```

## Searchable

Enable address search with Mapbox Geocoder:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->searchable();
```

## Geolocation

Enable "Find my location" button:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->geolocation();
```

## Clickable Map

Allow clicking on the map to place marker:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->clickable(); // Enabled by default
```

## Separate Fields

Store latitude and longitude in separate database fields:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->separateFields('latitude', 'longitude');
```

This stores:
- Latitude in the `latitude` column
- Longitude in the `longitude` column

## Combined Field

By default, the location is stored as JSON:

```php
MapboxPicker::make('location')
    ->label('Location');

// Stores: {"lat": 51.5074, "lng": -0.1278}
```

## Custom Marker

Customize the marker color:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->markerColor('#ef4444');
```

## Map Options

Pass additional Mapbox GL options:

```php
MapboxPicker::make('coordinates')
    ->label('Coordinates')
    ->mapOptions([
        'pitch' => 45,
        'bearing' => -17.6,
        'minZoom' => 5,
        'maxZoom' => 18,
    ]);
```

## Full Example

```php
MapboxPicker::make('venue_location')
    ->label('Venue Location')
    ->defaultLocation(40.7128, -74.0060) // New York
    ->zoom(14)
    ->height('350px')
    ->style('outdoors-v12')
    ->draggable()
    ->searchable()
    ->geolocation()
    ->clickable()
    ->markerColor('#22c55e')
    ->separateFields('venue_lat', 'venue_lng');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `defaultLocation($lat, $lng)` | Set initial center |
| `zoom($level)` | Set zoom level (0-22) |
| `height($height)` | Set map height |
| `style($style)` | Set map style |
| `draggable($bool = true)` | Enable marker dragging |
| `searchable($bool = true)` | Enable address search |
| `geolocation($bool = true)` | Enable locate button |
| `clickable($bool = true)` | Enable click-to-place |
| `separateFields($lat, $lng)` | Store in separate fields |
| `markerColor($color)` | Marker color |
| `mapOptions($options)` | Additional map options |

## Blade Component

You can also use the MapboxPicker as a Blade component:

```blade
{{-- Basic map picker --}}
<x-forms::mapbox-picker
    name="coordinates"
    label="Select Coordinates"
    :latitude="51.5074"
    :longitude="-0.1278"
/>

{{-- With search and geolocation --}}
<x-forms::mapbox-picker
    name="venue"
    label="Venue Location"
    :latitude="40.7128"
    :longitude="-74.0060"
    :zoom="14"
    searchable
    geolocation
    height="350px"
/>

{{-- Separate latitude/longitude fields --}}
<x-forms::mapbox-picker
    name="pickup"
    label="Pickup Location"
    :latitude="48.8566"
    :longitude="2.3522"
    separate-fields
    latitude-field="pickup_lat"
    longitude-field="pickup_lng"
/>

{{-- Light style --}}
<x-forms::mapbox-picker
    name="light_loc"
    label="Light Style"
    style="light-v11"
    searchable
/>

{{-- Dark style --}}
<x-forms::mapbox-picker
    name="dark_loc"
    label="Dark Style"
    style="dark-v11"
    marker-color="#f59e0b"
/>

{{-- Satellite style --}}
<x-forms::mapbox-picker
    name="property"
    label="Property Location"
    style="satellite-streets-v12"
    :zoom="15"
    searchable
/>

{{-- Outdoors style --}}
<x-forms::mapbox-picker
    name="hiking"
    label="Hiking Location"
    style="outdoors-v12"
    marker-color="#22c55e"
    geolocation
/>

{{-- Disabled map picker --}}
<x-forms::mapbox-picker
    name="locked"
    label="Fixed Location"
    :latitude="35.6762"
    :longitude="139.6503"
    disabled
/>
```

### Blade Component Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Input name (required) |
| `label` | string | Label text |
| `latitude` | float | Initial latitude |
| `longitude` | float | Initial longitude |
| `zoom` | integer | Zoom level (0-22) |
| `height` | string | Map height |
| `style` | string | Mapbox style |
| `draggable` | bool | Enable dragging |
| `searchable` | bool | Enable search |
| `geolocation` | bool | Enable locate |
| `clickable` | bool | Enable click |
| `separate-fields` | bool | Use separate fields |
| `latitude-field` | string | Latitude field name |
| `longitude-field` | string | Longitude field name |
| `marker-color` | string | Marker color |
| `hint` | string | Help text |
| `required` | bool | Mark as required |
| `disabled` | bool | Disable input |

## Available Styles

| Style | Description |
|-------|-------------|
| `streets-v12` | Standard street map (default) |
| `light-v11` | Light, minimal style |
| `dark-v11` | Dark style |
| `satellite-v9` | Satellite imagery |
| `satellite-streets-v12` | Satellite with labels |
| `outdoors-v12` | Topographic/outdoor |
| `navigation-day-v1` | Navigation daytime |
| `navigation-night-v1` | Navigation nighttime |

## JavaScript Events

The component emits events you can listen to:

```javascript
document.addEventListener('mapbox-picker:change', (e) => {
    console.log('Location changed:', e.detail.lat, e.detail.lng);
});
```

## JavaScript API

Access the picker instance:

```javascript
const wrapper = document.querySelector('.mapbox-picker-wrapper');
const picker = MapboxPickerManager.getInstance(wrapper);

// Set location programmatically
picker.setLocation(51.5074, -0.1278);

// Get current location
const location = picker.getValue();
console.log(location.lat, location.lng);

// Locate user
await picker.locateUser();

// Get underlying Mapbox GL map
const map = picker.getMap();

// Get marker
const marker = picker.getMarker();
```

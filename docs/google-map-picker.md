# Google Map Picker

The GoogleMapPicker component provides location selection using Google Maps with search, geolocation, click-to-place, and draggable markers.

## Configuration

Add your Google Maps API key to your `.env` file:

```env
GOOGLE_MAPS_API_KEY=your-api-key-here
```

## Basic Usage

```php
use Accelade\Forms\Components\GoogleMapPicker;

GoogleMapPicker::make('location')
    ->label('Select Location');
```

## Default Location

Set the initial map center:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->defaultLocation(51.5074, -0.1278); // London
```

## Zoom Level

Set the initial zoom level (1-20):

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->zoom(14);
```

## Map Height

Set the map container height:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->height('400px');
```

## Draggable Marker

Enable dragging the marker to select location:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->draggable(); // Enabled by default
```

## Searchable

Enable address search with autocomplete:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->searchable();
```

## Geolocation

Enable "Find my location" button:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->geolocation();
```

## Clickable Map

Allow clicking on the map to place marker:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->clickable(); // Enabled by default
```

## Map Type

Set the map type:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->mapType('roadmap'); // roadmap, satellite, hybrid, terrain
```

## Separate Fields

Store latitude and longitude in separate database fields:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->separateFields('latitude', 'longitude');
```

This stores:
- Latitude in the `latitude` column
- Longitude in the `longitude` column

## Combined Field

By default, the location is stored as JSON:

```php
GoogleMapPicker::make('coordinates')
    ->label('Coordinates');

// Stores: {"lat": 51.5074, "lng": -0.1278}
```

## Custom Marker

Customize the marker appearance:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->markerIcon('/images/custom-marker.png')
    ->markerColor('#ef4444');
```

## Map Options

Pass additional Google Maps options:

```php
GoogleMapPicker::make('location')
    ->label('Location')
    ->mapOptions([
        'disableDefaultUI' => true,
        'zoomControl' => true,
        'minZoom' => 5,
        'maxZoom' => 18,
    ]);
```

## Full Example

```php
GoogleMapPicker::make('office_location')
    ->label('Office Location')
    ->defaultLocation(40.7128, -74.0060) // New York
    ->zoom(14)
    ->height('350px')
    ->draggable()
    ->searchable()
    ->geolocation()
    ->clickable()
    ->mapType('hybrid')
    ->separateFields('office_lat', 'office_lng');
```

## Methods Reference

| Method | Description |
|--------|-------------|
| `defaultLocation($lat, $lng)` | Set initial center |
| `zoom($level)` | Set zoom level (1-20) |
| `height($height)` | Set map height |
| `draggable($bool = true)` | Enable marker dragging |
| `searchable($bool = true)` | Enable address search |
| `geolocation($bool = true)` | Enable locate button |
| `clickable($bool = true)` | Enable click-to-place |
| `mapType($type)` | Set map type |
| `separateFields($lat, $lng)` | Store in separate fields |
| `markerIcon($url)` | Custom marker image |
| `markerColor($color)` | Marker color |
| `mapOptions($options)` | Additional map options |

## Blade Component

You can also use the GoogleMapPicker as a Blade component:

```blade
{{-- Basic map picker --}}
<x-forms::google-map-picker
    name="location"
    label="Select Location"
    :latitude="51.5074"
    :longitude="-0.1278"
/>

{{-- With search and geolocation --}}
<x-forms::google-map-picker
    name="office"
    label="Office Location"
    :latitude="40.7128"
    :longitude="-74.0060"
    :zoom="14"
    searchable
    geolocation
    height="350px"
/>

{{-- Separate latitude/longitude fields --}}
<x-forms::google-map-picker
    name="delivery"
    label="Delivery Location"
    :latitude="48.8566"
    :longitude="2.3522"
    separate-fields
    latitude-field="delivery_lat"
    longitude-field="delivery_lng"
/>

{{-- Satellite view --}}
<x-forms::google-map-picker
    name="property"
    label="Property Location"
    map-type="satellite"
    :zoom="16"
    searchable
/>

{{-- Disabled map picker --}}
<x-forms::google-map-picker
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
| `zoom` | integer | Zoom level (1-20) |
| `height` | string | Map height |
| `map-type` | string | Map type |
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

## JavaScript Events

The component emits events you can listen to:

```javascript
document.addEventListener('map-picker:change', (e) => {
    console.log('Location changed:', e.detail.lat, e.detail.lng);
});
```

## JavaScript API

Access the picker instance:

```javascript
const wrapper = document.querySelector('.google-map-picker-wrapper');
const picker = GoogleMapPickerManager.getInstance(wrapper);

// Set location programmatically
picker.setLocation(51.5074, -0.1278);

// Get current location
const location = picker.getValue();
console.log(location.lat, location.lng);

// Locate user
await picker.locateUser();

// Get underlying Google Map
const map = picker.getMap();

// Get marker
const marker = picker.getMarker();
```

<?php

declare(strict_types=1);

use Accelade\Forms\Components\MapboxPicker;

it('can create a mapbox picker', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->getName())->toBe('coordinates')
        ->and($field->getId())->toBe('coordinates');
});

it('can set default location', function () {
    $field = MapboxPicker::make('coordinates')
        ->defaultLocation(51.5074, -0.1278);

    expect($field->getDefaultLatitude())->toBe(51.5074)
        ->and($field->getDefaultLongitude())->toBe(-0.1278);
});

it('can set zoom level', function () {
    $field = MapboxPicker::make('coordinates')
        ->zoom(16);

    expect($field->getZoom())->toBe(16);
});

it('has default zoom of 12', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->getZoom())->toBe(12);
});

it('can set height', function () {
    $field = MapboxPicker::make('coordinates')
        ->height('450px');

    expect($field->getHeight())->toBe('450px');
});

it('has default height of 300px', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->getHeight())->toBe('300px');
});

it('can set style', function () {
    $field = MapboxPicker::make('coordinates')
        ->style('dark-v11');

    expect($field->getStyle())->toBe('dark-v11');
});

it('has default style of streets-v12', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->getStyle())->toBe('streets-v12');
});

it('generates full style url for short style names', function () {
    $field = MapboxPicker::make('coordinates')
        ->style('satellite-v9');

    expect($field->getStyleUrl())->toBe('mapbox://styles/mapbox/satellite-v9');
});

it('preserves full mapbox style urls', function () {
    $field = MapboxPicker::make('coordinates')
        ->style('mapbox://styles/custom/style-id');

    expect($field->getStyleUrl())->toBe('mapbox://styles/custom/style-id');
});

it('preserves http style urls', function () {
    $field = MapboxPicker::make('coordinates')
        ->style('https://example.com/style.json');

    expect($field->getStyleUrl())->toBe('https://example.com/style.json');
});

it('can enable draggable', function () {
    $field = MapboxPicker::make('coordinates')
        ->draggable();

    expect($field->isDraggable())->toBeTrue();
});

it('can disable draggable', function () {
    $field = MapboxPicker::make('coordinates')
        ->draggable(false);

    expect($field->isDraggable())->toBeFalse();
});

it('is draggable by default', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->isDraggable())->toBeTrue();
});

it('can enable searchable', function () {
    $field = MapboxPicker::make('coordinates')
        ->searchable();

    expect($field->isSearchable())->toBeTrue();
});

it('can disable searchable', function () {
    $field = MapboxPicker::make('coordinates')
        ->searchable(false);

    expect($field->isSearchable())->toBeFalse();
});

it('is searchable by default', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->isSearchable())->toBeTrue();
});

it('can enable geolocation', function () {
    $field = MapboxPicker::make('coordinates')
        ->geolocation();

    expect($field->hasGeolocation())->toBeTrue();
});

it('can disable geolocation', function () {
    $field = MapboxPicker::make('coordinates')
        ->geolocation(false);

    expect($field->hasGeolocation())->toBeFalse();
});

it('has geolocation by default', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->hasGeolocation())->toBeTrue();
});

it('can enable clickable', function () {
    $field = MapboxPicker::make('coordinates')
        ->clickable();

    expect($field->isClickable())->toBeTrue();
});

it('can disable clickable', function () {
    $field = MapboxPicker::make('coordinates')
        ->clickable(false);

    expect($field->isClickable())->toBeFalse();
});

it('is clickable by default', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->isClickable())->toBeTrue();
});

it('can set separate fields', function () {
    $field = MapboxPicker::make('coordinates')
        ->separateFields('lat', 'lng');

    expect($field->hasSeparateFields())->toBeTrue()
        ->and($field->getLatitudeField())->toBe('lat')
        ->and($field->getLongitudeField())->toBe('lng');
});

it('does not have separate fields by default', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->hasSeparateFields())->toBeFalse()
        ->and($field->getLatitudeField())->toBeNull()
        ->and($field->getLongitudeField())->toBeNull();
});

it('can set custom access token', function () {
    $field = MapboxPicker::make('coordinates')
        ->accessToken('pk.test-token');

    expect($field->getAccessToken())->toBe('pk.test-token');
});

it('can set map options', function () {
    $field = MapboxPicker::make('coordinates')
        ->mapOptions(['pitch' => 60, 'bearing' => -17.6]);

    expect($field->getMapOptions())->toBe(['pitch' => 60, 'bearing' => -17.6]);
});

it('can merge map options', function () {
    $field = MapboxPicker::make('coordinates')
        ->mapOptions(['pitch' => 60])
        ->mapOptions(['bearing' => -17.6]);

    expect($field->getMapOptions())->toBe(['pitch' => 60, 'bearing' => -17.6]);
});

it('can set marker color', function () {
    $field = MapboxPicker::make('coordinates')
        ->markerColor('#22c55e');

    expect($field->getMarkerColor())->toBe('#22c55e');
});

it('has default marker color', function () {
    $field = MapboxPicker::make('coordinates');

    expect($field->getMarkerColor())->toBe('#3b82f6');
});

it('can get picker config', function () {
    $field = MapboxPicker::make('coordinates')
        ->defaultLocation(51.5074, -0.1278)
        ->zoom(14)
        ->style('outdoors-v12')
        ->draggable()
        ->searchable()
        ->geolocation()
        ->clickable()
        ->markerColor('#ef4444');

    $config = $field->getPickerConfig();

    expect($config['latitude'])->toBe(51.5074)
        ->and($config['longitude'])->toBe(-0.1278)
        ->and($config['zoom'])->toBe(14)
        ->and($config['style'])->toBe('mapbox://styles/mapbox/outdoors-v12')
        ->and($config['draggable'])->toBeTrue()
        ->and($config['searchable'])->toBeTrue()
        ->and($config['geolocation'])->toBeTrue()
        ->and($config['clickable'])->toBeTrue()
        ->and($config['separateFields'])->toBeFalse()
        ->and($config['markerColor'])->toBe('#ef4444');
});

it('can get picker config with separate fields', function () {
    $field = MapboxPicker::make('coordinates')
        ->separateFields('pickup_lat', 'pickup_lng');

    $config = $field->getPickerConfig();

    expect($config['separateFields'])->toBeTrue()
        ->and($config['latitudeField'])->toBe('pickup_lat')
        ->and($config['longitudeField'])->toBe('pickup_lng');
});

it('can set label', function () {
    $field = MapboxPicker::make('coordinates')
        ->label('Pick Location');

    expect($field->getLabel())->toBe('Pick Location');
});

it('generates label from name when not set', function () {
    $field = MapboxPicker::make('venue_location');

    expect($field->getLabel())->toBe('Venue Location');
});

it('can set as required', function () {
    $field = MapboxPicker::make('coordinates')
        ->required();

    expect($field->isRequired())->toBeTrue();
});

it('can set as disabled', function () {
    $field = MapboxPicker::make('coordinates')
        ->disabled();

    expect($field->isDisabled())->toBeTrue();
});

it('can chain multiple methods', function () {
    $field = MapboxPicker::make('coordinates')
        ->label('Select Coordinates')
        ->defaultLocation(48.8566, 2.3522)
        ->zoom(13)
        ->height('380px')
        ->style('light-v11')
        ->draggable()
        ->searchable()
        ->geolocation()
        ->clickable()
        ->markerColor('#f59e0b')
        ->required();

    expect($field->getLabel())->toBe('Select Coordinates')
        ->and($field->getDefaultLatitude())->toBe(48.8566)
        ->and($field->getDefaultLongitude())->toBe(2.3522)
        ->and($field->getZoom())->toBe(13)
        ->and($field->getHeight())->toBe('380px')
        ->and($field->getStyle())->toBe('light-v11')
        ->and($field->isDraggable())->toBeTrue()
        ->and($field->isSearchable())->toBeTrue()
        ->and($field->hasGeolocation())->toBeTrue()
        ->and($field->isClickable())->toBeTrue()
        ->and($field->getMarkerColor())->toBe('#f59e0b')
        ->and($field->isRequired())->toBeTrue();
});

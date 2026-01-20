<?php

declare(strict_types=1);

use Accelade\Forms\Components\GoogleMapPicker;

it('can create a google map picker', function () {
    $field = GoogleMapPicker::make('location');

    expect($field->getName())->toBe('location')
        ->and($field->getId())->toBe('location');
});

it('can set default location', function () {
    $field = GoogleMapPicker::make('location')
        ->defaultLocation(40.7128, -74.0060);

    expect($field->getDefaultLatitude())->toBe(40.7128)
        ->and($field->getDefaultLongitude())->toBe(-74.0060);
});

it('can set zoom level', function () {
    $field = GoogleMapPicker::make('location')
        ->zoom(15);

    expect($field->getZoom())->toBe(15);
});

it('has default zoom of 12', function () {
    $field = GoogleMapPicker::make('location');

    expect($field->getZoom())->toBe(12);
});

it('can set height', function () {
    $field = GoogleMapPicker::make('location')
        ->height('400px');

    expect($field->getHeight())->toBe('400px');
});

it('has default height of 300px', function () {
    $field = GoogleMapPicker::make('location');

    expect($field->getHeight())->toBe('300px');
});

it('can set map type', function () {
    $field = GoogleMapPicker::make('location')
        ->mapType('satellite');

    expect($field->getMapType())->toBe('satellite');
});

it('has default map type of roadmap', function () {
    $field = GoogleMapPicker::make('location');

    expect($field->getMapType())->toBe('roadmap');
});

it('can enable draggable', function () {
    $field = GoogleMapPicker::make('location')
        ->draggable();

    expect($field->isDraggable())->toBeTrue();
});

it('can disable draggable', function () {
    $field = GoogleMapPicker::make('location')
        ->draggable(false);

    expect($field->isDraggable())->toBeFalse();
});

it('is draggable by default', function () {
    $field = GoogleMapPicker::make('location');

    expect($field->isDraggable())->toBeTrue();
});

it('can enable searchable', function () {
    $field = GoogleMapPicker::make('location')
        ->searchable();

    expect($field->isSearchable())->toBeTrue();
});

it('can disable searchable', function () {
    $field = GoogleMapPicker::make('location')
        ->searchable(false);

    expect($field->isSearchable())->toBeFalse();
});

it('is searchable by default', function () {
    $field = GoogleMapPicker::make('location');

    expect($field->isSearchable())->toBeTrue();
});

it('can enable geolocation', function () {
    $field = GoogleMapPicker::make('location')
        ->geolocation();

    expect($field->hasGeolocation())->toBeTrue();
});

it('can disable geolocation', function () {
    $field = GoogleMapPicker::make('location')
        ->geolocation(false);

    expect($field->hasGeolocation())->toBeFalse();
});

it('has geolocation by default', function () {
    $field = GoogleMapPicker::make('location');

    expect($field->hasGeolocation())->toBeTrue();
});

it('can enable clickable', function () {
    $field = GoogleMapPicker::make('location')
        ->clickable();

    expect($field->isClickable())->toBeTrue();
});

it('can disable clickable', function () {
    $field = GoogleMapPicker::make('location')
        ->clickable(false);

    expect($field->isClickable())->toBeFalse();
});

it('is clickable by default', function () {
    $field = GoogleMapPicker::make('location');

    expect($field->isClickable())->toBeTrue();
});

it('can set separate fields', function () {
    $field = GoogleMapPicker::make('location')
        ->separateFields('lat', 'lng');

    expect($field->hasSeparateFields())->toBeTrue()
        ->and($field->getLatitudeField())->toBe('lat')
        ->and($field->getLongitudeField())->toBe('lng');
});

it('does not have separate fields by default', function () {
    $field = GoogleMapPicker::make('location');

    expect($field->hasSeparateFields())->toBeFalse()
        ->and($field->getLatitudeField())->toBeNull()
        ->and($field->getLongitudeField())->toBeNull();
});

it('can set custom api key', function () {
    $field = GoogleMapPicker::make('location')
        ->apiKey('test-api-key');

    expect($field->getApiKey())->toBe('test-api-key');
});

it('can set map options', function () {
    $field = GoogleMapPicker::make('location')
        ->mapOptions(['disableDefaultUI' => true, 'styles' => []]);

    expect($field->getMapOptions())->toBe(['disableDefaultUI' => true, 'styles' => []]);
});

it('can merge map options', function () {
    $field = GoogleMapPicker::make('location')
        ->mapOptions(['disableDefaultUI' => true])
        ->mapOptions(['zoomControl' => true]);

    expect($field->getMapOptions())->toBe(['disableDefaultUI' => true, 'zoomControl' => true]);
});

it('can set marker icon', function () {
    $field = GoogleMapPicker::make('location')
        ->markerIcon('/images/marker.png');

    expect($field->getMarkerIcon())->toBe('/images/marker.png');
});

it('can set marker color', function () {
    $field = GoogleMapPicker::make('location')
        ->markerColor('#ff0000');

    expect($field->getMarkerColor())->toBe('#ff0000');
});

it('can get picker config', function () {
    $field = GoogleMapPicker::make('location')
        ->defaultLocation(40.7128, -74.0060)
        ->zoom(15)
        ->mapType('hybrid')
        ->draggable()
        ->searchable()
        ->geolocation()
        ->clickable()
        ->markerColor('#3b82f6');

    $config = $field->getPickerConfig();

    expect($config['latitude'])->toBe(40.7128)
        ->and($config['longitude'])->toBe(-74.0060)
        ->and($config['zoom'])->toBe(15)
        ->and($config['mapType'])->toBe('hybrid')
        ->and($config['draggable'])->toBeTrue()
        ->and($config['searchable'])->toBeTrue()
        ->and($config['geolocation'])->toBeTrue()
        ->and($config['clickable'])->toBeTrue()
        ->and($config['separateFields'])->toBeFalse()
        ->and($config['markerColor'])->toBe('#3b82f6');
});

it('can get picker config with separate fields', function () {
    $field = GoogleMapPicker::make('location')
        ->separateFields('latitude', 'longitude');

    $config = $field->getPickerConfig();

    expect($config['separateFields'])->toBeTrue()
        ->and($config['latitudeField'])->toBe('latitude')
        ->and($config['longitudeField'])->toBe('longitude');
});

it('can set label', function () {
    $field = GoogleMapPicker::make('location')
        ->label('Select Location');

    expect($field->getLabel())->toBe('Select Location');
});

it('generates label from name when not set', function () {
    $field = GoogleMapPicker::make('office_location');

    expect($field->getLabel())->toBe('Office Location');
});

it('can set as required', function () {
    $field = GoogleMapPicker::make('location')
        ->required();

    expect($field->isRequired())->toBeTrue();
});

it('can set as disabled', function () {
    $field = GoogleMapPicker::make('location')
        ->disabled();

    expect($field->isDisabled())->toBeTrue();
});

it('can chain multiple methods', function () {
    $field = GoogleMapPicker::make('location')
        ->label('Select Location')
        ->defaultLocation(40.7128, -74.0060)
        ->zoom(14)
        ->height('350px')
        ->mapType('satellite')
        ->draggable()
        ->searchable()
        ->geolocation()
        ->clickable()
        ->markerColor('#ef4444')
        ->required();

    expect($field->getLabel())->toBe('Select Location')
        ->and($field->getDefaultLatitude())->toBe(40.7128)
        ->and($field->getDefaultLongitude())->toBe(-74.0060)
        ->and($field->getZoom())->toBe(14)
        ->and($field->getHeight())->toBe('350px')
        ->and($field->getMapType())->toBe('satellite')
        ->and($field->isDraggable())->toBeTrue()
        ->and($field->isSearchable())->toBeTrue()
        ->and($field->hasGeolocation())->toBeTrue()
        ->and($field->isClickable())->toBeTrue()
        ->and($field->getMarkerColor())->toBe('#ef4444')
        ->and($field->isRequired())->toBeTrue();
});

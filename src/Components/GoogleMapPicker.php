<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Google Maps location picker field.
 *
 * Allows users to select a location on a Google Map by clicking, searching,
 * or using geolocation. Stores the location as latitude/longitude coordinates.
 */
class GoogleMapPicker extends Field
{
    protected float $defaultLatitude = 0.0;

    protected float $defaultLongitude = 0.0;

    protected int $defaultZoom = 12;

    protected string $height = '300px';

    protected string $mapType = 'roadmap';

    protected bool|Closure $draggable = true;

    protected bool|Closure $searchable = true;

    protected bool|Closure $geolocation = true;

    protected bool|Closure $clickable = true;

    protected ?string $latitudeField = null;

    protected ?string $longitudeField = null;

    protected ?string $apiKey = null;

    protected array $mapOptions = [];

    protected ?string $markerIcon = null;

    protected ?string $markerColor = null;

    /**
     * Set the default location.
     */
    public function defaultLocation(float $latitude, float $longitude): static
    {
        $this->defaultLatitude = $latitude;
        $this->defaultLongitude = $longitude;

        return $this;
    }

    /**
     * Get the default latitude.
     */
    public function getDefaultLatitude(): float
    {
        return $this->defaultLatitude;
    }

    /**
     * Get the default longitude.
     */
    public function getDefaultLongitude(): float
    {
        return $this->defaultLongitude;
    }

    /**
     * Set the default zoom level.
     */
    public function zoom(int $zoom): static
    {
        $this->defaultZoom = $zoom;

        return $this;
    }

    /**
     * Get the default zoom level.
     */
    public function getZoom(): int
    {
        return $this->defaultZoom;
    }

    /**
     * Set the map height.
     */
    public function height(string $height): static
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get the map height.
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * Set the map type.
     */
    public function mapType(string $type): static
    {
        $this->mapType = $type;

        return $this;
    }

    /**
     * Get the map type.
     */
    public function getMapType(): string
    {
        return $this->mapType;
    }

    /**
     * Enable or disable marker dragging.
     */
    public function draggable(bool|Closure $condition = true): static
    {
        $this->draggable = $condition;

        return $this;
    }

    /**
     * Check if marker is draggable.
     */
    public function isDraggable(): bool
    {
        return (bool) $this->evaluate($this->draggable);
    }

    /**
     * Enable or disable place search.
     */
    public function searchable(bool|Closure $condition = true): static
    {
        $this->searchable = $condition;

        return $this;
    }

    /**
     * Check if search is enabled.
     */
    public function isSearchable(): bool
    {
        return (bool) $this->evaluate($this->searchable);
    }

    /**
     * Enable or disable geolocation.
     */
    public function geolocation(bool|Closure $condition = true): static
    {
        $this->geolocation = $condition;

        return $this;
    }

    /**
     * Check if geolocation is enabled.
     */
    public function hasGeolocation(): bool
    {
        return (bool) $this->evaluate($this->geolocation);
    }

    /**
     * Enable or disable click to place marker.
     */
    public function clickable(bool|Closure $condition = true): static
    {
        $this->clickable = $condition;

        return $this;
    }

    /**
     * Check if map is clickable.
     */
    public function isClickable(): bool
    {
        return (bool) $this->evaluate($this->clickable);
    }

    /**
     * Store latitude and longitude in separate fields.
     */
    public function separateFields(string $latitudeField, string $longitudeField): static
    {
        $this->latitudeField = $latitudeField;
        $this->longitudeField = $longitudeField;

        return $this;
    }

    /**
     * Get the latitude field name.
     */
    public function getLatitudeField(): ?string
    {
        return $this->latitudeField;
    }

    /**
     * Get the longitude field name.
     */
    public function getLongitudeField(): ?string
    {
        return $this->longitudeField;
    }

    /**
     * Check if using separate fields.
     */
    public function hasSeparateFields(): bool
    {
        return $this->latitudeField !== null && $this->longitudeField !== null;
    }

    /**
     * Set a custom API key.
     */
    public function apiKey(string $key): static
    {
        $this->apiKey = $key;

        return $this;
    }

    /**
     * Get the API key.
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey ?? config('accelade.maps.google_api_key');
    }

    /**
     * Set additional map options.
     */
    public function mapOptions(array $options): static
    {
        $this->mapOptions = array_merge($this->mapOptions, $options);

        return $this;
    }

    /**
     * Get map options.
     */
    public function getMapOptions(): array
    {
        return $this->mapOptions;
    }

    /**
     * Set a custom marker icon.
     */
    public function markerIcon(string $icon): static
    {
        $this->markerIcon = $icon;

        return $this;
    }

    /**
     * Get the marker icon.
     */
    public function getMarkerIcon(): ?string
    {
        return $this->markerIcon;
    }

    /**
     * Set the marker color.
     */
    public function markerColor(string $color): static
    {
        $this->markerColor = $color;

        return $this;
    }

    /**
     * Get the marker color.
     */
    public function getMarkerColor(): ?string
    {
        return $this->markerColor;
    }

    /**
     * Get the picker configuration for JavaScript.
     */
    public function getPickerConfig(): array
    {
        return [
            'latitude' => $this->defaultLatitude,
            'longitude' => $this->defaultLongitude,
            'zoom' => $this->defaultZoom,
            'mapType' => $this->mapType,
            'draggable' => $this->isDraggable(),
            'searchable' => $this->isSearchable(),
            'geolocation' => $this->hasGeolocation(),
            'clickable' => $this->isClickable(),
            'separateFields' => $this->hasSeparateFields(),
            'latitudeField' => $this->latitudeField,
            'longitudeField' => $this->longitudeField,
            'markerIcon' => $this->markerIcon,
            'markerColor' => $this->markerColor,
            'options' => $this->mapOptions,
        ];
    }

    /**
     * Get the view name for this field.
     */
    protected function getView(): string
    {
        return 'forms::components.google-map-picker';
    }
}

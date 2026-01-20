/**
 * Google Map Picker Form Component Manager
 *
 * Manages Google Maps location picker instances for form fields.
 * Supports search, geolocation, click-to-place, and draggable markers.
 */

import { Loader } from '@googlemaps/js-api-loader';

export interface GoogleMapPickerConfig {
    latitude: number;
    longitude: number;
    zoom: number;
    mapType: string;
    draggable: boolean;
    searchable: boolean;
    geolocation: boolean;
    clickable: boolean;
    separateFields: boolean;
    latitudeField: string | null;
    longitudeField: string | null;
    markerIcon: string | null;
    markerColor: string | null;
    options: google.maps.MapOptions;
}

export class GoogleMapPickerManager {
    private static instances = new WeakMap<HTMLElement, GoogleMapPickerManager>();
    private static loader: Loader | null = null;
    private static loadPromise: Promise<typeof google> | null = null;

    private wrapper: HTMLElement;
    private map: google.maps.Map | null = null;
    private marker: google.maps.Marker | null = null;
    private config: GoogleMapPickerConfig;
    private isDisabled: boolean;

    // Input elements
    private valueInput: HTMLInputElement | null;
    private latInput: HTMLInputElement | null;
    private lngInput: HTMLInputElement | null;
    private latDisplay: HTMLElement | null;
    private lngDisplay: HTMLElement | null;
    private searchInput: HTMLInputElement | null;
    private locateButton: HTMLButtonElement | null;

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.config = this.parseConfig();
        this.isDisabled = wrapper.dataset.disabled === 'true';

        // Get input elements
        this.valueInput = wrapper.querySelector<HTMLInputElement>('.map-picker-value');
        this.latInput = wrapper.querySelector<HTMLInputElement>('.map-picker-lat');
        this.lngInput = wrapper.querySelector<HTMLInputElement>('.map-picker-lng');
        this.latDisplay = wrapper.querySelector<HTMLElement>('.map-picker-lat-display');
        this.lngDisplay = wrapper.querySelector<HTMLElement>('.map-picker-lng-display');
        this.searchInput = wrapper.querySelector<HTMLInputElement>('.map-picker-search');
        this.locateButton = wrapper.querySelector<HTMLButtonElement>('.map-picker-locate');

        this.init();
    }

    private parseConfig(): GoogleMapPickerConfig {
        const configAttr = this.wrapper.dataset.mapPickerConfig;
        return configAttr ? JSON.parse(configAttr) : {
            latitude: 0,
            longitude: 0,
            zoom: 12,
            mapType: 'roadmap',
            draggable: true,
            searchable: true,
            geolocation: true,
            clickable: true,
            separateFields: false,
            latitudeField: null,
            longitudeField: null,
            markerIcon: null,
            markerColor: null,
            options: {},
        };
    }

    private async init(): Promise<void> {
        const apiKey = this.wrapper.dataset.mapApiKey;
        if (!apiKey) {
            console.error('GoogleMapPicker: Missing API key');
            return;
        }

        try {
            await this.loadGoogleMaps(apiKey);
            this.initMap();
            this.initMarker();
            this.initSearch();
            this.initGeolocation();
            this.initClickHandler();

            // Mark as initialized
            this.wrapper.dataset.initialized = 'true';
        } catch (error) {
            console.error('GoogleMapPicker: Failed to initialize', error);
        }
    }

    private async loadGoogleMaps(apiKey: string): Promise<void> {
        if (!GoogleMapPickerManager.loadPromise) {
            GoogleMapPickerManager.loader = new Loader({
                apiKey,
                version: 'weekly',
                libraries: ['places'],
            });
            GoogleMapPickerManager.loadPromise = GoogleMapPickerManager.loader.load();
        }
        await GoogleMapPickerManager.loadPromise;
    }

    private initMap(): void {
        const container = this.wrapper.querySelector<HTMLElement>('.map-picker-container');
        if (!container) return;

        this.map = new google.maps.Map(container, {
            center: { lat: this.config.latitude, lng: this.config.longitude },
            zoom: this.config.zoom,
            mapTypeId: this.config.mapType as google.maps.MapTypeId,
            disableDefaultUI: false,
            zoomControl: true,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            ...this.config.options,
        });
    }

    private initMarker(): void {
        if (!this.map) return;

        const markerOptions: google.maps.MarkerOptions = {
            position: { lat: this.config.latitude, lng: this.config.longitude },
            map: this.map,
            draggable: this.config.draggable && !this.isDisabled,
            animation: google.maps.Animation.DROP,
        };

        if (this.config.markerIcon) {
            markerOptions.icon = this.config.markerIcon;
        }

        this.marker = new google.maps.Marker(markerOptions);

        if (this.config.draggable && !this.isDisabled) {
            this.marker.addListener('dragend', () => {
                const position = this.marker?.getPosition();
                if (position) {
                    this.updateValue(position.lat(), position.lng());
                }
            });
        }
    }

    private initSearch(): void {
        if (!this.config.searchable || !this.searchInput || !this.map || this.isDisabled) {
            return;
        }

        const autocomplete = new google.maps.places.Autocomplete(this.searchInput, {
            fields: ['geometry', 'name', 'formatted_address'],
        });

        autocomplete.bindTo('bounds', this.map);

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();

            if (!place.geometry?.location) {
                return;
            }

            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();

            this.setLocation(lat, lng);

            // Fit to viewport if available
            if (place.geometry.viewport) {
                this.map?.fitBounds(place.geometry.viewport);
            } else {
                this.map?.setCenter({ lat, lng });
                this.map?.setZoom(15);
            }
        });
    }

    private initGeolocation(): void {
        if (!this.config.geolocation || !this.locateButton || this.isDisabled) {
            return;
        }

        this.locateButton.addEventListener('click', () => {
            this.locateUser();
        });
    }

    private initClickHandler(): void {
        if (!this.config.clickable || !this.map || this.isDisabled) {
            return;
        }

        this.map.addListener('click', (e: google.maps.MapMouseEvent) => {
            if (e.latLng) {
                this.setLocation(e.latLng.lat(), e.latLng.lng());
            }
        });
    }

    /**
     * Set the marker location and update form values.
     */
    public setLocation(lat: number, lng: number): void {
        const position = { lat, lng };

        this.marker?.setPosition(position);
        this.map?.panTo(position);
        this.updateValue(lat, lng);
    }

    /**
     * Update the hidden input values and display.
     */
    private updateValue(lat: number, lng: number): void {
        // Update hidden inputs
        if (this.config.separateFields) {
            if (this.latInput) {
                this.latInput.value = lat.toString();
                this.latInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
            if (this.lngInput) {
                this.lngInput.value = lng.toString();
                this.lngInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        } else if (this.valueInput) {
            this.valueInput.value = JSON.stringify({ lat, lng });
            this.valueInput.dispatchEvent(new Event('input', { bubbles: true }));
        }

        // Update display
        if (this.latDisplay) {
            this.latDisplay.textContent = lat.toFixed(6);
        }
        if (this.lngDisplay) {
            this.lngDisplay.textContent = lng.toFixed(6);
        }

        // Dispatch custom event
        this.wrapper.dispatchEvent(new CustomEvent('map-picker:change', {
            detail: { lat, lng },
            bubbles: true,
        }));
    }

    /**
     * Get the user's current location.
     */
    public async locateUser(): Promise<void> {
        if (!navigator.geolocation) {
            console.warn('GoogleMapPicker: Geolocation not supported');
            return;
        }

        // Show loading state on button
        if (this.locateButton) {
            this.locateButton.disabled = true;
            this.locateButton.classList.add('animate-pulse');
        }

        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    this.setLocation(lat, lng);
                    this.map?.setZoom(15);

                    // Reset button state
                    if (this.locateButton) {
                        this.locateButton.disabled = false;
                        this.locateButton.classList.remove('animate-pulse');
                    }

                    resolve();
                },
                (error) => {
                    console.error('GoogleMapPicker: Geolocation failed', error);

                    // Reset button state
                    if (this.locateButton) {
                        this.locateButton.disabled = false;
                        this.locateButton.classList.remove('animate-pulse');
                    }

                    reject(error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0,
                }
            );
        });
    }

    /**
     * Get the current location value.
     */
    public getValue(): { lat: number; lng: number } | null {
        const position = this.marker?.getPosition();
        return position ? { lat: position.lat(), lng: position.lng() } : null;
    }

    /**
     * Get the underlying Google Maps instance.
     */
    public getMap(): google.maps.Map | null {
        return this.map;
    }

    /**
     * Get the marker instance.
     */
    public getMarker(): google.maps.Marker | null {
        return this.marker;
    }

    /**
     * Initialize all Google Map Picker instances on the page.
     */
    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.google-map-picker-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                GoogleMapPickerManager.instances.set(wrapper, new GoogleMapPickerManager(wrapper));
            }
        });
    }

    /**
     * Get an instance by wrapper element.
     */
    public static getInstance(wrapper: HTMLElement): GoogleMapPickerManager | undefined {
        return GoogleMapPickerManager.instances.get(wrapper);
    }
}

// Export to global scope for access
if (typeof window !== 'undefined') {
    (window as any).GoogleMapPickerManager = GoogleMapPickerManager;
}

export default GoogleMapPickerManager;

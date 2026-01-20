/**
 * Mapbox GL Map Picker Form Component Manager
 *
 * Manages Mapbox GL location picker instances for form fields.
 * Supports search, geolocation, click-to-place, and draggable markers.
 */

import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';

export interface MapboxPickerConfig {
    latitude: number;
    longitude: number;
    zoom: number;
    style: string;
    draggable: boolean;
    searchable: boolean;
    geolocation: boolean;
    clickable: boolean;
    separateFields: boolean;
    latitudeField: string | null;
    longitudeField: string | null;
    markerColor: string | null;
    options: mapboxgl.MapOptions;
}

export class MapboxPickerManager {
    private static instances = new WeakMap<HTMLElement, MapboxPickerManager>();

    private wrapper: HTMLElement;
    private map: mapboxgl.Map | null = null;
    private marker: mapboxgl.Marker | null = null;
    private config: MapboxPickerConfig;
    private isDisabled: boolean;
    private searchTimeout: ReturnType<typeof setTimeout> | null = null;

    // Input elements
    private valueInput: HTMLInputElement | null;
    private latInput: HTMLInputElement | null;
    private lngInput: HTMLInputElement | null;
    private latDisplay: HTMLElement | null;
    private lngDisplay: HTMLElement | null;
    private searchInput: HTMLInputElement | null;
    private searchResults: HTMLElement | null;
    private locateButton: HTMLButtonElement | null;

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.config = this.parseConfig();
        this.isDisabled = wrapper.dataset.disabled === 'true';

        // Get input elements
        this.valueInput = wrapper.querySelector<HTMLInputElement>('.mapbox-picker-value');
        this.latInput = wrapper.querySelector<HTMLInputElement>('.mapbox-picker-lat');
        this.lngInput = wrapper.querySelector<HTMLInputElement>('.mapbox-picker-lng');
        this.latDisplay = wrapper.querySelector<HTMLElement>('.mapbox-picker-lat-display');
        this.lngDisplay = wrapper.querySelector<HTMLElement>('.mapbox-picker-lng-display');
        this.searchInput = wrapper.querySelector<HTMLInputElement>('.mapbox-picker-search');
        this.searchResults = wrapper.querySelector<HTMLElement>('.mapbox-picker-results');
        this.locateButton = wrapper.querySelector<HTMLButtonElement>('.mapbox-picker-locate');

        this.init();
    }

    private parseConfig(): MapboxPickerConfig {
        const configAttr = this.wrapper.dataset.mapboxPickerConfig;
        return configAttr ? JSON.parse(configAttr) : {
            latitude: 0,
            longitude: 0,
            zoom: 12,
            style: 'mapbox://styles/mapbox/streets-v12',
            draggable: true,
            searchable: true,
            geolocation: true,
            clickable: true,
            separateFields: false,
            latitudeField: null,
            longitudeField: null,
            markerColor: '#3b82f6',
            options: {},
        };
    }

    private init(): void {
        const accessToken = this.wrapper.dataset.mapAccessToken;
        if (!accessToken) {
            console.error('MapboxPicker: Missing access token');
            return;
        }

        try {
            // Set Mapbox access token
            mapboxgl.accessToken = accessToken;

            this.initMap();
            this.initMarker();
            this.initSearch();
            this.initGeolocation();
            this.initClickHandler();

            // Mark as initialized
            this.wrapper.dataset.initialized = 'true';
        } catch (error) {
            console.error('MapboxPicker: Failed to initialize', error);
        }
    }

    private initMap(): void {
        const container = this.wrapper.querySelector<HTMLElement>('.mapbox-picker-container');
        if (!container) return;

        this.map = new mapboxgl.Map({
            container,
            style: this.config.style,
            center: [this.config.longitude, this.config.latitude],
            zoom: this.config.zoom,
            attributionControl: false,
            ...this.config.options,
        });

        // Add minimal controls
        this.map.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'bottom-right');
    }

    private initMarker(): void {
        if (!this.map) return;

        const el = document.createElement('div');
        el.className = 'mapbox-picker-marker';
        el.innerHTML = `
            <svg width="32" height="40" viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 0C7.163 0 0 7.163 0 16c0 8.837 16 24 16 24s16-15.163 16-24C32 7.163 24.837 0 16 0z" fill="${this.config.markerColor || '#3b82f6'}"/>
                <circle cx="16" cy="14" r="6" fill="white"/>
            </svg>
        `;

        this.marker = new mapboxgl.Marker({
            element: el,
            draggable: this.config.draggable && !this.isDisabled,
            anchor: 'bottom',
        })
            .setLngLat([this.config.longitude, this.config.latitude])
            .addTo(this.map);

        if (this.config.draggable && !this.isDisabled) {
            this.marker.on('dragend', () => {
                const lngLat = this.marker?.getLngLat();
                if (lngLat) {
                    this.updateValue(lngLat.lat, lngLat.lng);
                }
            });
        }
    }

    private initSearch(): void {
        if (!this.config.searchable || !this.searchInput || this.isDisabled) {
            return;
        }

        const accessToken = this.wrapper.dataset.mapAccessToken;

        this.searchInput.addEventListener('input', () => {
            const query = this.searchInput!.value.trim();

            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            if (query.length < 2) {
                this.hideSearchResults();
                return;
            }

            this.searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(
                        `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${accessToken}&limit=5`
                    );
                    const data = await response.json();

                    if (data.features && data.features.length > 0) {
                        this.showSearchResults(data.features);
                    } else {
                        this.hideSearchResults();
                    }
                } catch (error) {
                    console.error('MapboxPicker: Search failed', error);
                    this.hideSearchResults();
                }
            }, 300);
        });

        // Close results on click outside
        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target as Node)) {
                this.hideSearchResults();
            }
        });
    }

    private showSearchResults(features: any[]): void {
        if (!this.searchResults) return;

        this.searchResults.innerHTML = features
            .map(
                (feature) => `
                <button
                    type="button"
                    class="search-result-item w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none"
                    data-lng="${feature.center[0]}"
                    data-lat="${feature.center[1]}"
                >
                    <div class="font-medium text-gray-900 dark:text-white">${feature.text}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">${feature.place_name}</div>
                </button>
            `
            )
            .join('');

        this.searchResults.classList.remove('hidden');

        // Add click handlers to results
        this.searchResults.querySelectorAll('.search-result-item').forEach((item) => {
            item.addEventListener('click', () => {
                const lng = parseFloat((item as HTMLElement).dataset.lng!);
                const lat = parseFloat((item as HTMLElement).dataset.lat!);

                this.setLocation(lat, lng);
                this.map?.flyTo({ center: [lng, lat], zoom: 15 });
                this.searchInput!.value = '';
                this.hideSearchResults();
            });
        });
    }

    private hideSearchResults(): void {
        if (this.searchResults) {
            this.searchResults.classList.add('hidden');
            this.searchResults.innerHTML = '';
        }
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

        this.map.on('click', (e) => {
            this.setLocation(e.lngLat.lat, e.lngLat.lng);
        });
    }

    /**
     * Set the marker location and update form values.
     */
    public setLocation(lat: number, lng: number): void {
        this.marker?.setLngLat([lng, lat]);
        this.map?.panTo([lng, lat]);
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
        this.wrapper.dispatchEvent(
            new CustomEvent('mapbox-picker:change', {
                detail: { lat, lng },
                bubbles: true,
            })
        );
    }

    /**
     * Get the user's current location.
     */
    public async locateUser(): Promise<void> {
        if (!navigator.geolocation) {
            console.warn('MapboxPicker: Geolocation not supported');
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
                    this.map?.flyTo({ center: [lng, lat], zoom: 15 });

                    // Reset button state
                    if (this.locateButton) {
                        this.locateButton.disabled = false;
                        this.locateButton.classList.remove('animate-pulse');
                    }

                    resolve();
                },
                (error) => {
                    console.error('MapboxPicker: Geolocation failed', error);

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
        const lngLat = this.marker?.getLngLat();
        return lngLat ? { lat: lngLat.lat, lng: lngLat.lng } : null;
    }

    /**
     * Get the underlying Mapbox GL map instance.
     */
    public getMap(): mapboxgl.Map | null {
        return this.map;
    }

    /**
     * Get the marker instance.
     */
    public getMarker(): mapboxgl.Marker | null {
        return this.marker;
    }

    /**
     * Initialize all Mapbox Picker instances on the page.
     */
    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.mapbox-picker-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                MapboxPickerManager.instances.set(wrapper, new MapboxPickerManager(wrapper));
            }
        });
    }

    /**
     * Get an instance by wrapper element.
     */
    public static getInstance(wrapper: HTMLElement): MapboxPickerManager | undefined {
        return MapboxPickerManager.instances.get(wrapper);
    }
}

// Export to global scope for access
if (typeof window !== 'undefined') {
    (window as any).MapboxPickerManager = MapboxPickerManager;
}

export default MapboxPickerManager;

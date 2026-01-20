@props(['field'])

@php
    $id = $field->getId();
    $name = $field->getName();
    $isDisabled = $field->isDisabled();
    $isRequired = $field->isRequired();
    $hasSeparateFields = $field->hasSeparateFields();
    $accessToken = $field->getAccessToken();
    $height = $field->getHeight();

    $config = $field->getPickerConfig();

    $containerClasses = 'mapbox-picker-wrapper relative rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden';

    // Get current value
    $value = $field->getValue();
    $currentLat = $config['latitude'];
    $currentLng = $config['longitude'];

    if ($value) {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if ($decoded) {
                $currentLat = $decoded['lat'] ?? $config['latitude'];
                $currentLng = $decoded['lng'] ?? $config['longitude'];
            }
        } elseif (is_array($value)) {
            $currentLat = $value['lat'] ?? $config['latitude'];
            $currentLng = $value['lng'] ?? $config['longitude'];
        }
    }

    $config['latitude'] = $currentLat;
    $config['longitude'] = $currentLng;
@endphp

<div class="{{ config('forms.styles.field', 'mb-4') }}">
    @if($field->getLabel())
        <label for="{{ $id }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500') }}">*</span>
            @endif
        </label>
    @endif

    <div
        class="{{ $containerClasses }}"
        data-mapbox-picker-config="{{ json_encode($config) }}"
        data-map-access-token="{{ $accessToken }}"
        @if($isDisabled) data-disabled="true" @endif
    >
        {{-- Map Container --}}
        <div class="mapbox-picker-container" style="height: {{ $height }};"></div>

        {{-- Loading State --}}
        <div class="mapbox-picker-loading absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
            <div class="flex flex-col items-center gap-2">
                <svg class="h-8 w-8 animate-spin text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-gray-500 dark:text-gray-400">Loading map...</span>
            </div>
        </div>

        @if($config['searchable'])
        {{-- Search Input --}}
        <div class="absolute left-3 top-3 z-10 w-64">
            <div class="relative">
                <input
                    type="text"
                    class="mapbox-picker-search w-full rounded-lg border-0 bg-white py-2 pl-9 pr-3 text-sm shadow-md placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Search for a place..."
                    @if($isDisabled) disabled @endif
                />
                <svg class="absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            {{-- Search Results Dropdown --}}
            <div class="mapbox-picker-results mt-1 hidden max-h-48 overflow-y-auto rounded-lg bg-white shadow-lg dark:bg-gray-700"></div>
        </div>
        @endif

        @if($config['geolocation'])
        {{-- Locate Button --}}
        <button
            type="button"
            class="mapbox-picker-locate absolute bottom-3 right-3 z-10 rounded-lg bg-white p-2 shadow-md transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:hover:bg-gray-600"
            title="Use my location"
            @if($isDisabled) disabled @endif
        >
            <svg class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </button>
        @endif

        {{-- No Access Token Warning --}}
        @if(empty($accessToken))
        <div class="absolute inset-0 flex items-center justify-center bg-yellow-50 dark:bg-yellow-900/20">
            <div class="max-w-xs rounded-lg bg-white p-3 shadow-lg dark:bg-gray-800">
                <div class="flex items-start gap-2">
                    <svg class="h-5 w-5 flex-shrink-0 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Set <code class="rounded bg-gray-100 px-1 text-xs dark:bg-gray-700">MAPBOX_ACCESS_TOKEN</code> in .env
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Hidden Input(s) for Form Submission --}}
    @if($hasSeparateFields)
        <input
            type="hidden"
            name="{{ $field->getLatitudeField() }}"
            class="mapbox-picker-lat"
            value="{{ $currentLat }}"
            @if($isDisabled) disabled @endif
        />
        <input
            type="hidden"
            name="{{ $field->getLongitudeField() }}"
            class="mapbox-picker-lng"
            value="{{ $currentLng }}"
            @if($isDisabled) disabled @endif
        />
    @else
        <input
            type="hidden"
            id="{{ $id }}"
            name="{{ $name }}"
            class="mapbox-picker-value"
            value="{{ json_encode(['lat' => $currentLat, 'lng' => $currentLng]) }}"
            @if($isDisabled) disabled @endif
        />
    @endif

    {{-- Coordinate Display --}}
    <div class="mt-2 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
        <span>Lat: <span class="mapbox-picker-lat-display font-mono">{{ number_format($currentLat, 6) }}</span></span>
        <span>Lng: <span class="mapbox-picker-lng-display font-mono">{{ number_format($currentLng, 6) }}</span></span>
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'mt-1 text-sm text-gray-500 dark:text-gray-400') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($name)
        <p class="{{ config('forms.styles.error', 'mt-1 text-sm text-red-600 dark:text-red-400') }}">{{ $message }}</p>
    @enderror
</div>

<style>
    .mapbox-picker-wrapper[data-initialized="true"] .mapbox-picker-loading {
        display: none;
    }
</style>

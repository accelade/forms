{{-- Google Map Picker Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\GoogleMapPicker;

    // Basic location picker
    $basicPicker = GoogleMapPicker::make('location')
        ->label('Select Location')
        ->defaultLocation(51.5074, -0.1278)
        ->zoom(12);

    // With search and geolocation
    $searchPicker = GoogleMapPicker::make('office_location')
        ->label('Office Location')
        ->defaultLocation(40.7128, -74.0060)
        ->zoom(14)
        ->searchable()
        ->geolocation()
        ->height('350px');

    // Separate lat/lng fields
    $separatePicker = GoogleMapPicker::make('delivery_location')
        ->label('Delivery Location')
        ->defaultLocation(48.8566, 2.3522)
        ->zoom(13)
        ->separateFields('delivery_lat', 'delivery_lng')
        ->draggable()
        ->clickable();

    // Satellite view with custom marker
    $satellitePicker = GoogleMapPicker::make('property_location')
        ->label('Property Location')
        ->defaultLocation(34.0522, -118.2437)
        ->zoom(16)
        ->mapType('satellite')
        ->markerColor('#ef4444')
        ->searchable();

    // Hybrid view with all features
    $fullPicker = GoogleMapPicker::make('store_location')
        ->label('Store Location')
        ->defaultLocation(35.6762, 139.6503)
        ->zoom(15)
        ->mapType('hybrid')
        ->draggable()
        ->searchable()
        ->geolocation()
        ->clickable()
        ->height('400px');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Google Map Picker</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Location selection using Google Maps with search, geolocation, and draggable markers.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Location Picker -->
        <div class="rounded-xl p-4 border border-blue-500/30" style="background: rgba(59, 130, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-blue-500/20 text-blue-500 rounded">Basic</span>
                Simple Location Picker
            </h4>

            {!! $basicPicker !!}
        </div>

        <!-- With Search & Geolocation -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Search</span>
                With Search & Geolocation
            </h4>

            {!! $searchPicker !!}
        </div>

        <!-- Separate Lat/Lng Fields -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Separate</span>
                Separate Latitude & Longitude
            </h4>

            {!! $separatePicker !!}
        </div>

        <!-- Satellite View -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Satellite</span>
                Satellite View
            </h4>

            {!! $satellitePicker !!}
        </div>

        <!-- Full Features -->
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Full</span>
                All Features
            </h4>

            {!! $fullPicker !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="google-map-picker-examples.php">
use Accelade\Forms\Components\GoogleMapPicker;

// Basic location picker
GoogleMapPicker::make('location')
    ->label('Select Location')
    ->defaultLocation(51.5074, -0.1278)
    ->zoom(12);

// With search and geolocation
GoogleMapPicker::make('office_location')
    ->label('Office Location')
    ->defaultLocation(40.7128, -74.0060)
    ->zoom(14)
    ->searchable()
    ->geolocation()
    ->height('350px');

// Separate lat/lng fields
GoogleMapPicker::make('delivery_location')
    ->label('Delivery Location')
    ->separateFields('latitude', 'longitude')
    ->draggable()
    ->clickable();

// Satellite view with custom marker
GoogleMapPicker::make('property_location')
    ->label('Property Location')
    ->mapType('satellite')
    ->markerColor('#ef4444')
    ->searchable();
    </x-accelade::code-block>
</section>

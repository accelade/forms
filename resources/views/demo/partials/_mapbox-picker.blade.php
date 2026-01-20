{{-- Mapbox Picker Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\MapboxPicker;

    // Basic location picker
    $basicPicker = MapboxPicker::make('coordinates')
        ->label('Select Coordinates')
        ->defaultLocation(51.5074, -0.1278)
        ->zoom(12);

    // With search and geolocation
    $searchPicker = MapboxPicker::make('venue_location')
        ->label('Venue Location')
        ->defaultLocation(40.7128, -74.0060)
        ->zoom(14)
        ->searchable()
        ->geolocation()
        ->height('350px');

    // Separate lat/lng fields
    $separatePicker = MapboxPicker::make('pickup_location')
        ->label('Pickup Location')
        ->defaultLocation(48.8566, 2.3522)
        ->zoom(13)
        ->separateFields('pickup_lat', 'pickup_lng')
        ->draggable()
        ->clickable();

    // Light style
    $lightPicker = MapboxPicker::make('light_location')
        ->label('Light Style')
        ->defaultLocation(35.6762, 139.6503)
        ->zoom(14)
        ->style('light-v11')
        ->searchable()
        ->markerColor('#3b82f6');

    // Dark style
    $darkPicker = MapboxPicker::make('dark_location')
        ->label('Dark Style')
        ->defaultLocation(37.7749, -122.4194)
        ->zoom(13)
        ->style('dark-v11')
        ->markerColor('#f59e0b');

    // Satellite style
    $satellitePicker = MapboxPicker::make('satellite_location')
        ->label('Satellite Style')
        ->defaultLocation(34.0522, -118.2437)
        ->zoom(15)
        ->style('satellite-streets-v12')
        ->searchable()
        ->markerColor('#ef4444');

    // Outdoors with all features
    $outdoorsPicker = MapboxPicker::make('hiking_location')
        ->label('Hiking Location')
        ->defaultLocation(46.8182, 8.2275)
        ->zoom(10)
        ->style('outdoors-v12')
        ->draggable()
        ->searchable()
        ->geolocation()
        ->clickable()
        ->height('400px')
        ->markerColor('#22c55e');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-cyan-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Mapbox Picker</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Location selection using Mapbox GL with modern vector maps and multiple styles.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Location Picker -->
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">Basic</span>
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

        <!-- Map Styles -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Styles</span>
                Map Styles
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs mb-2" style="color: var(--docs-text-muted);">Light</p>
                    {!! $lightPicker !!}
                </div>
                <div>
                    <p class="text-xs mb-2" style="color: var(--docs-text-muted);">Dark</p>
                    {!! $darkPicker !!}
                </div>
            </div>
        </div>

        <!-- Satellite Style -->
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Satellite</span>
                Satellite Streets
            </h4>

            {!! $satellitePicker !!}
        </div>

        <!-- Outdoors with All Features -->
        <div class="rounded-xl p-4 border border-green-500/30" style="background: rgba(34, 197, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-green-500/20 text-green-500 rounded">Full</span>
                Outdoors with All Features
            </h4>

            {!! $outdoorsPicker !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="mapbox-picker-examples.php">
use Accelade\Forms\Components\MapboxPicker;

// Basic location picker
MapboxPicker::make('coordinates')
    ->label('Select Coordinates')
    ->defaultLocation(51.5074, -0.1278)
    ->zoom(12);

// With search and geolocation
MapboxPicker::make('venue_location')
    ->label('Venue Location')
    ->defaultLocation(40.7128, -74.0060)
    ->zoom(14)
    ->searchable()
    ->geolocation()
    ->height('350px');

// Separate lat/lng fields
MapboxPicker::make('pickup_location')
    ->label('Pickup Location')
    ->separateFields('pickup_lat', 'pickup_lng')
    ->draggable()
    ->clickable();

// Different styles
MapboxPicker::make('location')
    ->style('streets-v12');  // Default
MapboxPicker::make('location')
    ->style('light-v11');
MapboxPicker::make('location')
    ->style('dark-v11');
MapboxPicker::make('location')
    ->style('satellite-streets-v12');
MapboxPicker::make('location')
    ->style('outdoors-v12');
    </x-accelade::code-block>
</section>

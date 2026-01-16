<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Forms Package Routes
|--------------------------------------------------------------------------
|
| These routes are loaded when forms.demo.enabled is true.
| Most form documentation is registered through Accelade's DocsRegistry.
|
*/

Route::prefix(config('forms.demo.prefix', 'demo/forms'))
    ->middleware(config('forms.demo.middleware', ['web']))
    ->group(function () {
        // Forms demo routes are primarily handled through Accelade's docs system
        // Additional form-specific demo routes can be added here if needed
    });

<?php

declare(strict_types=1);

use Accelade\Forms\Http\Controllers\FileUploadController;
use Accelade\Forms\Http\Controllers\MediaBrowserController;
use Accelade\Forms\Http\Controllers\SelectOptionsController;
use Accelade\Forms\Http\Middleware\ValidateFileUploadToken;
use Accelade\Forms\Http\Middleware\ValidateSelectToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Forms Package API Routes
|--------------------------------------------------------------------------
|
| API routes for the Forms package, including select options endpoints
| and file upload endpoints. These routes are protected by signed tokens
| that contain all configuration encrypted for security.
|
*/

$apiPrefix = config('forms.api.prefix', 'api/forms');
$apiMiddleware = config('forms.api.middleware', ['web']);

// Select options routes (with select token validation)
Route::prefix($apiPrefix)
    ->middleware(array_merge($apiMiddleware, [ValidateSelectToken::class]))
    ->group(function () {
        // Select options API for paginated/searchable options
        // All configuration (model, attributes) is encrypted in the token
        // The model is NOT exposed in the URL for security
        Route::get('/select-options', [SelectOptionsController::class, 'index'])
            ->name('forms.select-options');
    });

// File upload routes (with upload token validation)
Route::prefix($apiPrefix)
    ->middleware(array_merge($apiMiddleware, [ValidateFileUploadToken::class]))
    ->group(function () {
        // Upload file(s)
        Route::post('/upload', [FileUploadController::class, 'store'])
            ->name('forms.upload');

        // Delete a file
        Route::delete('/upload', [FileUploadController::class, 'destroy'])
            ->name('forms.upload.destroy');

        // Revert (delete) a temporary upload - FilePond calls this
        Route::delete('/upload/revert', [FileUploadController::class, 'revert'])
            ->name('forms.upload.revert');

        // Generate temporary URL for private files
        Route::post('/upload/temporary-url', [FileUploadController::class, 'temporaryUrl'])
            ->name('forms.upload.temporary-url');

        // Load existing file for preview
        Route::get('/upload/load', [FileUploadController::class, 'load'])
            ->name('forms.upload.load');

        // Media browser routes
        Route::get('/media', [MediaBrowserController::class, 'index'])
            ->name('forms.media.index');

        Route::get('/media/collections', [MediaBrowserController::class, 'collections'])
            ->name('forms.media.collections');

        Route::get('/media/{id}', [MediaBrowserController::class, 'show'])
            ->name('forms.media.show')
            ->where('id', '[0-9]+');
    });

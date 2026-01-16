<?php

declare(strict_types=1);

namespace Accelade\Forms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * Controller for the media browser/picker functionality.
 *
 * Provides a WordPress-style media library interface organized by
 * Model -> Collection structure when using Spatie Media Library.
 *
 * Falls back to file system browsing when Media Library is not available.
 */
class MediaBrowserController extends Controller
{
    /**
     * List media items.
     *
     * Supports both Spatie Media Library and direct file system browsing.
     */
    public function index(Request $request): JsonResponse
    {
        $payload = $request->input('_upload_token_payload', []);

        $disk = $payload['disk'] ?? $request->input('disk', 'public');
        $directory = $payload['directory'] ?? $request->input('directory', 'uploads');
        $collection = $payload['collection'] ?? $request->input('collection');
        $modelClass = $payload['model_class'] ?? $request->input('model_class');
        $search = $request->input('search', '');
        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', config('forms.media_browser.per_page', 24));

        // Check if Spatie Media Library is installed and collection is specified
        if ($collection && class_exists(\Spatie\MediaLibrary\MediaCollections\Models\Media::class)) {
            return $this->getMediaLibraryItems($collection, $modelClass, $search, $page, $perPage);
        }

        // Fall back to file system browsing
        return $this->getFileSystemItems($disk, $directory, $search, $page, $perPage);
    }

    /**
     * Get collections available in the media library.
     *
     * Returns a tree structure of Model -> Collection for navigation.
     */
    public function collections(Request $request): JsonResponse
    {
        // Check if Spatie Media Library is installed
        if (! class_exists(\Spatie\MediaLibrary\MediaCollections\Models\Media::class)) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Media Library not installed',
            ]);
        }

        $mediaClass = config('media-library.media_model', \Spatie\MediaLibrary\MediaCollections\Models\Media::class);

        // Get distinct model types and collections
        $collections = $mediaClass::query()
            ->selectRaw('model_type, collection_name, COUNT(*) as count')
            ->groupBy('model_type', 'collection_name')
            ->orderBy('model_type')
            ->orderBy('collection_name')
            ->get()
            ->groupBy('model_type')
            ->map(function ($items, $modelType) {
                // Get a friendly name for the model
                $modelName = class_basename($modelType);

                return [
                    'model_type' => $modelType,
                    'model_name' => $modelName,
                    'collections' => $items->map(function ($item) {
                        return [
                            'name' => $item->collection_name,
                            'count' => $item->count,
                        ];
                    })->values()->all(),
                    'total' => $items->sum('count'),
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'success' => true,
            'data' => $collections,
        ]);
    }

    /**
     * Get a single media item details.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        // Check if Spatie Media Library is installed
        if (! class_exists(\Spatie\MediaLibrary\MediaCollections\Models\Media::class)) {
            return response()->json([
                'success' => false,
                'error' => 'Media Library not installed',
            ], 400);
        }

        $mediaClass = config('media-library.media_model', \Spatie\MediaLibrary\MediaCollections\Models\Media::class);

        $media = $mediaClass::find($id);

        if (! $media) {
            return response()->json([
                'success' => false,
                'error' => 'Media not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatMediaItem($media),
        ]);
    }

    /**
     * Get items from Spatie Media Library.
     */
    protected function getMediaLibraryItems(
        ?string $collection,
        ?string $modelClass,
        string $search,
        int $page,
        int $perPage
    ): JsonResponse {
        $mediaClass = config('media-library.media_model', \Spatie\MediaLibrary\MediaCollections\Models\Media::class);

        $query = $mediaClass::query()
            ->orderBy('created_at', 'desc');

        // Filter by collection
        if ($collection) {
            $query->where('collection_name', $collection);
        }

        // Filter by model type
        if ($modelClass) {
            $query->where('model_type', $modelClass);
        }

        // Search by file name
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Paginate
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $items = collect($paginator->items())->map(function ($media) {
            return $this->formatMediaItem($media);
        })->all();

        return response()->json([
            'success' => true,
            'data' => $items,
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'has_more' => $paginator->hasMorePages(),
            ],
        ]);
    }

    /**
     * Get items from file system.
     */
    protected function getFileSystemItems(
        string $disk,
        string $directory,
        string $search,
        int $page,
        int $perPage
    ): JsonResponse {
        // Get all files in the directory
        $allFiles = Storage::disk($disk)->files($directory);

        // Filter by search term
        if ($search) {
            $allFiles = array_filter($allFiles, function ($file) use ($search) {
                return stripos(basename($file), $search) !== false;
            });
        }

        // Sort by modification time (newest first)
        usort($allFiles, function ($a, $b) use ($disk) {
            return Storage::disk($disk)->lastModified($b) <=> Storage::disk($disk)->lastModified($a);
        });

        // Paginate manually
        $total = count($allFiles);
        $offset = ($page - 1) * $perPage;
        $files = array_slice($allFiles, $offset, $perPage);

        // Format file items
        $items = array_map(function ($path) use ($disk) {
            return $this->formatFileItem($disk, $path);
        }, $files);

        return response()->json([
            'success' => true,
            'data' => $items,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => (int) ceil($total / $perPage),
                'has_more' => $offset + $perPage < $total,
            ],
        ]);
    }

    /**
     * Format a Spatie Media Library item.
     */
    protected function formatMediaItem($media): array
    {
        $isImage = str_starts_with($media->mime_type, 'image/');

        return [
            'id' => $media->id,
            'type' => 'media_library',
            'name' => $media->name,
            'file_name' => $media->file_name,
            'mime_type' => $media->mime_type,
            'size' => $media->size,
            'size_formatted' => $this->formatFileSize($media->size),
            'is_image' => $isImage,
            'url' => $media->getUrl(),
            'thumbnail_url' => $isImage ? ($media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl()) : null,
            'path' => $media->getPath(),
            'collection' => $media->collection_name,
            'model_type' => class_basename($media->model_type),
            'model_id' => $media->model_id,
            'custom_properties' => $media->custom_properties,
            'created_at' => $media->created_at->toIso8601String(),
            'updated_at' => $media->updated_at->toIso8601String(),
        ];
    }

    /**
     * Format a file system item.
     */
    protected function formatFileItem(string $disk, string $path): array
    {
        $mimeType = Storage::disk($disk)->mimeType($path);
        $isImage = str_starts_with($mimeType ?? '', 'image/');
        $size = Storage::disk($disk)->size($path);

        return [
            'id' => md5($path),
            'type' => 'file_system',
            'name' => pathinfo($path, PATHINFO_FILENAME),
            'file_name' => basename($path),
            'mime_type' => $mimeType,
            'size' => $size,
            'size_formatted' => $this->formatFileSize($size),
            'is_image' => $isImage,
            'url' => Storage::disk($disk)->url($path),
            'thumbnail_url' => $isImage ? Storage::disk($disk)->url($path) : null,
            'path' => $path,
            'collection' => null,
            'model_type' => null,
            'model_id' => null,
            'custom_properties' => [],
            'created_at' => date('c', Storage::disk($disk)->lastModified($path)),
            'updated_at' => date('c', Storage::disk($disk)->lastModified($path)),
        ];
    }

    /**
     * Format file size to human readable format.
     */
    protected function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);
        $factor = min($factor, count($units) - 1);

        return sprintf('%.2f %s', $bytes / pow(1024, $factor), $units[$factor]);
    }
}

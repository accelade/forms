<?php

declare(strict_types=1);

namespace Accelade\Forms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controller for handling file upload operations.
 *
 * Supports:
 * - Single and multiple file uploads
 * - File deletion
 * - Temporary URL generation for private files
 * - Spatie Media Library integration
 */
class FileUploadController extends Controller
{
    /**
     * Store uploaded file(s).
     *
     * Returns the file path as plain text for FilePond compatibility.
     */
    public function store(Request $request): Response|JsonResponse
    {
        // Check if uploads are disabled in demo mode
        if (config('forms.file_upload.upload_in_demo', false)) {
            $message = config('forms.file_upload.demo_message', "You can't upload files in demo mode");

            return response()->json([
                'error' => $message,
                'success' => false,
            ], 403);
        }

        $payload = $request->input('_upload_token_payload', []);

        if (empty($payload)) {
            return response()->json([
                'error' => 'Invalid upload request',
                'success' => false,
            ], 400);
        }

        // Get configuration from token payload
        $disk = $payload['disk'] ?? 'public';
        $directory = $payload['directory'] ?? 'uploads';
        $visibility = $payload['visibility'] ?? 'public';
        $maxSize = $payload['max_size'] ?? null;
        $acceptedTypes = $payload['accepted_types'] ?? [];
        $preserveFilenames = $payload['preserve_filenames'] ?? false;

        // Check if file was uploaded
        // FilePond sends as 'filepond' by default, but with image transform plugins it sends as 'filepond_file'
        // Also check common field names 'file' and the input name
        $file = $request->file('filepond')
            ?? $request->file('filepond_file')
            ?? $request->file('file');

        // If still not found, check all uploaded files
        if (! $file) {
            $allFiles = $request->allFiles();
            if (! empty($allFiles)) {
                // Get the first uploaded file regardless of field name
                $file = reset($allFiles);
                // Handle nested arrays (e.g., files[0])
                if (is_array($file)) {
                    $file = reset($file);
                }
            }
        }

        if (! $file) {
            return response()->json([
                'error' => 'No file uploaded',
                'success' => false,
            ], 400);
        }

        // Validate file
        if (! $file->isValid()) {
            return response()->json([
                'error' => 'Invalid file upload',
                'success' => false,
            ], 400);
        }

        // Validate file size
        if ($maxSize !== null) {
            $fileSizeKb = $file->getSize() / 1024;
            if ($fileSizeKb > $maxSize) {
                return response()->json([
                    'error' => 'File size exceeds maximum allowed ('.$maxSize.' KB)',
                    'success' => false,
                ], 422);
            }
        }

        // Validate file type
        if (! empty($acceptedTypes)) {
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();

            $isAllowed = false;
            foreach ($acceptedTypes as $type) {
                // Check MIME type (e.g., image/*)
                if (str_contains($type, '/')) {
                    if ($type === $mimeType) {
                        $isAllowed = true;
                        break;
                    }
                    // Handle wildcard (e.g., image/*)
                    if (str_ends_with($type, '/*')) {
                        $prefix = substr($type, 0, -1);
                        if (str_starts_with($mimeType, $prefix)) {
                            $isAllowed = true;
                            break;
                        }
                    }
                } else {
                    // Check extension (e.g., .pdf or pdf)
                    $ext = ltrim($type, '.');
                    if (strtolower($extension) === strtolower($ext)) {
                        $isAllowed = true;
                        break;
                    }
                }
            }

            if (! $isAllowed) {
                return response()->json([
                    'error' => 'File type not allowed',
                    'success' => false,
                ], 422);
            }
        }

        // Generate filename
        if ($preserveFilenames) {
            $filename = $file->getClientOriginalName();
            // Ensure unique filename by adding timestamp if file exists
            $baseName = pathinfo($filename, PATHINFO_FILENAME);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $counter = 1;

            while (Storage::disk($disk)->exists($directory.'/'.$filename)) {
                $filename = $baseName.'-'.$counter.'.'.$extension;
                $counter++;
            }
        } else {
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        }

        // Store the file
        $path = $file->storeAs($directory, $filename, [
            'disk' => $disk,
            'visibility' => $visibility,
        ]);

        if ($path === false) {
            return response()->json([
                'error' => 'Failed to store file',
                'success' => false,
            ], 500);
        }

        // Return the path as plain text for FilePond
        return response($path, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Delete a file.
     */
    public function destroy(Request $request): JsonResponse
    {
        $payload = $request->input('_upload_token_payload', []);

        if (empty($payload)) {
            return response()->json([
                'error' => 'Invalid request',
                'success' => false,
            ], 400);
        }

        $path = $request->input('path');

        if (! $path) {
            return response()->json([
                'error' => 'No file path provided',
                'success' => false,
            ], 400);
        }

        $disk = $payload['disk'] ?? 'public';
        $directory = $payload['directory'] ?? 'uploads';

        // Security: Ensure the path is within the allowed directory
        if (! str_starts_with($path, $directory.'/') && $path !== $directory) {
            // Also check if path starts directly with filename (no directory prefix)
            $normalizedPath = $directory.'/'.basename($path);
            if (! Storage::disk($disk)->exists($normalizedPath)) {
                return response()->json([
                    'error' => 'Invalid file path',
                    'success' => false,
                ], 403);
            }
            $path = $normalizedPath;
        }

        // Delete the file
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully',
            ]);
        }

        return response()->json([
            'error' => 'File not found',
            'success' => false,
        ], 404);
    }

    /**
     * Generate a temporary URL for a file.
     *
     * Used for previewing private files.
     */
    public function temporaryUrl(Request $request): JsonResponse
    {
        $payload = $request->input('_upload_token_payload', []);

        if (empty($payload)) {
            return response()->json([
                'error' => 'Invalid request',
                'success' => false,
            ], 400);
        }

        $path = $request->input('path');

        if (! $path) {
            return response()->json([
                'error' => 'No file path provided',
                'success' => false,
            ], 400);
        }

        $disk = $payload['disk'] ?? 'public';
        $expiration = config('forms.file_upload.temporary_url_expiration', 3600); // 1 hour default

        // Check if file exists
        if (! Storage::disk($disk)->exists($path)) {
            return response()->json([
                'error' => 'File not found',
                'success' => false,
            ], 404);
        }

        // Check if disk supports temporary URLs
        try {
            $url = Storage::disk($disk)->temporaryUrl(
                $path,
                now()->addSeconds($expiration)
            );

            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        } catch (\RuntimeException $e) {
            // Disk doesn't support temporary URLs, return public URL instead
            $url = Storage::disk($disk)->url($path);

            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        }
    }

    /**
     * Revert (delete) a temporary upload.
     *
     * Called by FilePond when user removes a file before form submission.
     */
    public function revert(Request $request): Response|JsonResponse
    {
        $payload = $request->input('_upload_token_payload', []);

        if (empty($payload)) {
            return response()->json([
                'error' => 'Invalid request',
                'success' => false,
            ], 400);
        }

        // FilePond sends the server ID (file path) as the request body
        $path = $request->getContent();

        if (! $path) {
            return response()->json([
                'error' => 'No file path provided',
                'success' => false,
            ], 400);
        }

        $disk = $payload['disk'] ?? 'public';
        $directory = $payload['directory'] ?? 'uploads';

        // Security: Ensure the path is within the allowed directory
        if (! str_starts_with($path, $directory.'/') && $path !== $directory) {
            return response()->json([
                'error' => 'Invalid file path',
                'success' => false,
            ], 403);
        }

        // Delete the file
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }

        return response('', 200);
    }

    /**
     * Load an existing file for FilePond.
     *
     * Returns the file content for preview.
     */
    public function load(Request $request): Response|JsonResponse
    {
        $payload = $request->input('_upload_token_payload', []);

        if (empty($payload)) {
            return response()->json([
                'error' => 'Invalid request',
                'success' => false,
            ], 400);
        }

        $path = $request->input('source') ?? $request->query('source');

        if (! $path) {
            return response()->json([
                'error' => 'No file path provided',
                'success' => false,
            ], 400);
        }

        $disk = $payload['disk'] ?? 'public';

        // Check if file exists
        if (! Storage::disk($disk)->exists($path)) {
            return response()->json([
                'error' => 'File not found',
                'success' => false,
            ], 404);
        }

        // Get file contents and mime type
        $content = Storage::disk($disk)->get($path);
        $mimeType = Storage::disk($disk)->mimeType($path);
        $filename = basename($path);

        return response($content, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="'.$filename.'"');
    }
}

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
        $demoError = $this->checkDemoMode();
        if ($demoError !== null) {
            return $demoError;
        }

        $payload = $request->input('_upload_token_payload', []);
        if (empty($payload)) {
            return $this->errorResponse('Invalid upload request', 400);
        }

        $file = $this->extractUploadedFile($request);
        if (! $file) {
            return $this->errorResponse('No file uploaded', 400);
        }

        if (! $file->isValid()) {
            return $this->errorResponse('Invalid file upload', 400);
        }

        $sizeError = $this->validateFileSize($file, $payload['max_size'] ?? null);
        if ($sizeError !== null) {
            return $sizeError;
        }

        $typeError = $this->validateFileType($file, $payload['accepted_types'] ?? []);
        if ($typeError !== null) {
            return $typeError;
        }

        return $this->storeFile($file, $payload);
    }

    /**
     * Check if demo mode is enabled and return error if so.
     */
    protected function checkDemoMode(): ?JsonResponse
    {
        if (! config('forms.file_upload.upload_in_demo', false)) {
            return null;
        }

        $message = config('forms.file_upload.demo_message', "You can't upload files in demo mode");

        return $this->errorResponse($message, 403);
    }

    /**
     * Extract the uploaded file from the request.
     */
    protected function extractUploadedFile(Request $request): mixed
    {
        $file = $request->file('filepond')
            ?? $request->file('filepond_file')
            ?? $request->file('file');

        if ($file) {
            return $file;
        }

        $allFiles = $request->allFiles();
        if (empty($allFiles)) {
            return null;
        }

        $file = reset($allFiles);

        return is_array($file) ? reset($file) : $file;
    }

    /**
     * Validate file size against maximum allowed.
     */
    protected function validateFileSize(mixed $file, ?int $maxSize): ?JsonResponse
    {
        if ($maxSize === null) {
            return null;
        }

        $fileSizeKb = $file->getSize() / 1024;
        if ($fileSizeKb <= $maxSize) {
            return null;
        }

        return $this->errorResponse('File size exceeds maximum allowed ('.$maxSize.' KB)', 422);
    }

    /**
     * Validate file type against accepted types.
     */
    protected function validateFileType(mixed $file, array $acceptedTypes): ?JsonResponse
    {
        if (empty($acceptedTypes)) {
            return null;
        }

        $mimeType = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();

        if ($this->isFileTypeAllowed($mimeType, $extension, $acceptedTypes)) {
            return null;
        }

        return $this->errorResponse('File type not allowed', 422);
    }

    /**
     * Check if a file type is allowed.
     */
    protected function isFileTypeAllowed(string $mimeType, string $extension, array $acceptedTypes): bool
    {
        foreach ($acceptedTypes as $type) {
            if ($this->matchesMimeOrExtension($mimeType, $extension, $type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if MIME type or extension matches an accepted type.
     */
    protected function matchesMimeOrExtension(string $mimeType, string $extension, string $type): bool
    {
        if (! str_contains($type, '/')) {
            return strtolower($extension) === strtolower(ltrim($type, '.'));
        }

        if ($type === $mimeType) {
            return true;
        }

        if (str_ends_with($type, '/*')) {
            return str_starts_with($mimeType, substr($type, 0, -1));
        }

        return false;
    }

    /**
     * Store the file and return the response.
     */
    protected function storeFile(mixed $file, array $payload): Response|JsonResponse
    {
        $disk = $payload['disk'] ?? 'public';
        $directory = $payload['directory'] ?? 'uploads';
        $visibility = $payload['visibility'] ?? 'public';
        $preserveFilenames = $payload['preserve_filenames'] ?? false;

        $filename = $this->generateFilename($file, $disk, $directory, $preserveFilenames);

        $path = $file->storeAs($directory, $filename, [
            'disk' => $disk,
            'visibility' => $visibility,
        ]);

        if ($path === false) {
            return $this->errorResponse('Failed to store file', 500);
        }

        return response($path, 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Generate filename for the uploaded file.
     */
    protected function generateFilename(mixed $file, string $disk, string $directory, bool $preserveFilenames): string
    {
        if (! $preserveFilenames) {
            return Str::uuid().'.'.$file->getClientOriginalExtension();
        }

        $filename = $file->getClientOriginalName();
        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $counter = 1;

        while (Storage::disk($disk)->exists($directory.'/'.$filename)) {
            $filename = $baseName.'-'.$counter.'.'.$extension;
            $counter++;
        }

        return $filename;
    }

    /**
     * Return a JSON error response.
     */
    protected function errorResponse(string $message, int $status): JsonResponse
    {
        return response()->json([
            'error' => $message,
            'success' => false,
        ], $status);
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

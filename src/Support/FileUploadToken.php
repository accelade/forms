<?php

declare(strict_types=1);

namespace Accelade\Forms\Support;

use Illuminate\Support\Facades\Crypt;

/**
 * Secure token generation for file upload operations.
 *
 * All upload configuration is encrypted in the token to prevent tampering:
 * - Storage disk and directory
 * - File size limits
 * - Accepted file types
 * - Media library collection
 */
class FileUploadToken
{
    /**
     * Generate a secure token for file upload operations.
     *
     * @param  array  $options  Configuration options to encrypt
     */
    public static function generate(array $options = []): string
    {
        $payload = [
            'timestamp' => time(),
            'disk' => $options['disk'] ?? 'public',
            'directory' => $options['directory'] ?? 'uploads',
            'visibility' => $options['visibility'] ?? 'public',
            'max_size' => $options['max_size'] ?? null,
            'min_size' => $options['min_size'] ?? null,
            'accepted_types' => $options['accepted_types'] ?? [],
            'max_files' => $options['max_files'] ?? null,
            'min_files' => $options['min_files'] ?? null,
            'preserve_filenames' => $options['preserve_filenames'] ?? false,
            'use_media_library' => $options['use_media_library'] ?? false,
            'collection' => $options['collection'] ?? null,
            'model_class' => $options['model_class'] ?? null,
            'model_id' => $options['model_id'] ?? null,
        ];

        return Crypt::encryptString(json_encode($payload));
    }

    /**
     * Generate the full upload URL with token.
     */
    public static function url(#[\SensitiveParameter] string $token): string
    {
        $baseUrl = route('forms.upload');

        return $baseUrl.'?'.http_build_query([
            '_token' => $token,
        ]);
    }

    /**
     * Decrypt and validate a token.
     *
     * @return array|null The token payload or null if invalid
     */
    public static function decrypt(#[\SensitiveParameter] string $token): ?array
    {
        try {
            $payload = Crypt::decryptString($token);
            $data = json_decode($payload, true);

            if (! $data || ! isset($data['timestamp'])) {
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if a token is expired.
     */
    public static function isExpired(array $payload): bool
    {
        $tokenAge = time() - ($payload['timestamp'] ?? 0);
        $maxAge = config('forms.file_upload.token_lifetime', 86400); // 24 hours default

        return $tokenAge > $maxAge;
    }

    /**
     * Validate a token and return the payload if valid.
     *
     * @return array|null The token payload or null if invalid/expired
     */
    public static function validate(#[\SensitiveParameter] string $token): ?array
    {
        $payload = self::decrypt($token);

        if ($payload === null) {
            return null;
        }

        if (self::isExpired($payload)) {
            return null;
        }

        return $payload;
    }
}

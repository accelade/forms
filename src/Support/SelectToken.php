<?php

declare(strict_types=1);

namespace Accelade\Forms\Support;

class SelectToken
{
    /**
     * Generate a secure token for the select component.
     *
     * The token contains ALL configuration encrypted:
     * - Full model class name (encrypted, not exposed in URL)
     * - Label attribute
     * - Value attribute
     * - Search columns
     * - Per page limit
     * - Timestamp for expiration
     */
    public static function generate(string $modelClass, array $options = []): string
    {
        $payload = [
            'model' => $modelClass, // Full class name e.g. App\Models\User
            'timestamp' => time(),
            'label_attribute' => $options['label_attribute'] ?? 'name',
            'value_attribute' => $options['value_attribute'] ?? 'id',
            'search_columns' => $options['search_columns'] ?? null,
            'per_page' => $options['per_page'] ?? 50,
        ];

        return \Illuminate\Support\Facades\Crypt::encryptString(json_encode($payload));
    }

    /**
     * Generate the full search URL with token.
     *
     * The URL only contains the encrypted token - no model information is exposed.
     */
    public static function url(string $modelClass, array $options = []): string
    {
        $token = self::generate($modelClass, $options);

        // URL does NOT include model name - everything is in the encrypted token
        $baseUrl = route('forms.select-options');

        return $baseUrl.'?'.http_build_query([
            '_token' => $token,
        ]);
    }

    /**
     * Generate the URL for fetching a single record.
     *
     * Used for pre-filling edit forms with record data.
     */
    public static function recordUrl(string $modelClass, array $options = []): string
    {
        $token = self::generate($modelClass, $options);

        $baseUrl = route('forms.select-options.show');

        return $baseUrl.'?'.http_build_query([
            '_token' => $token,
        ]);
    }

    /**
     * Generate the URL for creating a new record.
     */
    public static function createUrl(string $modelClass, array $options = []): string
    {
        $token = self::generate($modelClass, $options);

        $baseUrl = route('forms.select-options.store');

        return $baseUrl.'?'.http_build_query([
            '_token' => $token,
        ]);
    }

    /**
     * Generate the URL for updating an existing record.
     */
    public static function updateUrl(string $modelClass, array $options = []): string
    {
        $token = self::generate($modelClass, $options);

        $baseUrl = route('forms.select-options.update');

        return $baseUrl.'?'.http_build_query([
            '_token' => $token,
        ]);
    }
}

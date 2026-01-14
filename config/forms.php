<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Form Configuration
    |--------------------------------------------------------------------------
    |
    | Configure default behavior for Accelade Forms components.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Form Method
    |--------------------------------------------------------------------------
    |
    | The default HTTP method for forms when not explicitly specified.
    |
    */
    'default_method' => 'POST',

    /*
    |--------------------------------------------------------------------------
    | Error Display
    |--------------------------------------------------------------------------
    |
    | Configure how validation errors are displayed.
    |
    */
    'errors' => [
        // Automatically show errors below each field
        'show_inline' => true,

        // Scroll to the first error on validation failure
        'scroll_to_first' => true,

        // CSS classes for error messages
        'classes' => 'text-sm text-red-600 dark:text-red-400 mt-1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Styling
    |--------------------------------------------------------------------------
    |
    | Default CSS classes for form inputs.
    |
    */
    'styles' => [
        'input' => 'block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm',
        'select' => 'block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm',
        'textarea' => 'block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm',
        'checkbox' => 'rounded border-gray-300 dark:border-gray-600 text-primary-600 shadow-sm focus:ring-primary-500',
        'radio' => 'border-gray-300 dark:border-gray-600 text-primary-600 shadow-sm focus:ring-primary-500',
        'toggle' => 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2',
        'label' => 'block text-sm font-medium text-gray-700 dark:text-gray-300',
        'hint' => 'text-sm text-gray-500 dark:text-gray-400 mt-1',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for file upload fields.
    |
    */
    'file_upload' => [
        // Maximum file size in kilobytes
        'max_size' => 10240, // 10MB

        // Allowed file types (mime types or extensions)
        'accepted_types' => ['image/*', 'application/pdf', '.doc', '.docx'],

        // Enable multiple file uploads by default
        'multiple' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Date/Time Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for date and time picker fields.
    |
    */
    'datetime' => [
        // Default date format for display
        'date_format' => 'Y-m-d',

        // Default time format for display
        'time_format' => 'H:i',

        // Default datetime format for display
        'datetime_format' => 'Y-m-d H:i',

        // First day of the week (0 = Sunday, 1 = Monday)
        'first_day_of_week' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Demo Routes
    |--------------------------------------------------------------------------
    |
    | Enable demo routes for testing form components.
    |
    */
    'demo' => [
        'enabled' => env('APP_DEBUG', false),
        'prefix' => 'demo/forms',
        'middleware' => ['web'],
    ],
];

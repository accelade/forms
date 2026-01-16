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
    | Default CSS classes for form inputs. All styles support:
    | - Light mode (default)
    | - Dark mode (via dark: prefix)
    | - RTL support (via rtl: prefix or logical properties)
    |
    */
    'styles' => [
        // Input container - controls background, border, and visual styling
        'input_container' => 'relative rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800',

        // Input container disabled state
        'input_container_disabled' => 'bg-gray-50 dark:bg-gray-900 cursor-not-allowed',

        // Text input - transparent background, inherits from container
        'input' => 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 placeholder-gray-400 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed dark:text-gray-100 dark:placeholder-gray-500 dark:disabled:text-gray-600',

        // Select dropdown
        'select_container' => 'relative rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800',

        'select' => 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed dark:text-gray-100 dark:disabled:text-gray-600 appearance-none',

        // Textarea
        'textarea_container' => 'relative rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-150 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800',

        'textarea' => 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 placeholder-gray-400 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed resize-y dark:text-gray-100 dark:placeholder-gray-500 dark:disabled:text-gray-600',

        // Checkbox
        'checkbox' => 'h-4 w-4 rounded border-gray-300 bg-white text-primary-600 shadow-sm transition-colors duration-150 focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-0 disabled:bg-gray-100 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-primary-500 dark:focus:ring-primary-400/20',

        // Radio button
        'radio' => 'h-4 w-4 border-gray-300 bg-white text-primary-600 shadow-sm transition-colors duration-150 focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-0 disabled:bg-gray-100 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-primary-500 dark:focus:ring-primary-400/20',

        // Toggle switch
        'toggle' => 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900',

        // Field label
        'label' => 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5',

        // Helper text / hint
        'hint' => 'text-sm text-gray-500 dark:text-gray-400 mt-1.5',

        // Input prefix (e.g., $ symbol)
        'prefix' => 'inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border-e border-gray-300 rounded-s-lg dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600',

        // Input suffix (e.g., .com)
        'suffix' => 'inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border-s border-gray-300 rounded-e-lg dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600',

        // Input wrapper (when has prefix/suffix)
        'input_wrapper' => 'flex rounded-lg border border-gray-300 bg-white shadow-sm overflow-hidden focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800',

        // Input with prefix (remove start border radius)
        'input_with_prefix' => 'rounded-s-none border-s-0',

        // Input with suffix (remove end border radius)
        'input_with_suffix' => 'rounded-e-none border-e-0',

        // Required field indicator
        'required' => 'text-red-500 dark:text-red-400 ms-0.5',

        // Field wrapper
        'field' => 'mb-4',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for file upload fields with FilePond integration.
    |
    */
    'file_upload' => [
        // Maximum file size in kilobytes
        'max_size' => 10240, // 10MB

        // Default storage disk
        'disk' => 'public',

        // Default upload directory
        'directory' => 'uploads',

        // Default file visibility (public or private)
        'visibility' => 'public',

        // Allowed file types (mime types or extensions)
        'accepted_types' => ['image/*', 'application/pdf', '.doc', '.docx'],

        // Enable multiple file uploads by default
        'multiple' => false,

        // Upload token lifetime in seconds (24 hours)
        'token_lifetime' => 86400,

        // Temporary URL expiration for private files (1 hour)
        'temporary_url_expiration' => 3600,

        // Demo mode - when enabled, uploads are rejected with a message
        // Useful for demo/showcase applications where actual uploads should be disabled
        'upload_in_demo' => false,

        // Custom message to display when uploads are rejected in demo mode
        'demo_message' => "You can't upload files in demo mode",
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Browser Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for the WordPress-style media browser.
    |
    */
    'media_browser' => [
        // Enable media browser feature
        'enabled' => true,

        // Items per page in the media browser
        'per_page' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for the forms API endpoints.
    |
    */
    'api' => [
        // API route prefix
        'prefix' => 'api/forms',

        // API middleware
        'middleware' => ['web'],
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
    | Form Submission Behavior
    |--------------------------------------------------------------------------
    |
    | Default behavior settings for form submissions.
    |
    */
    'submission' => [
        // Keep user on the same page after successful submission
        'stay' => false,

        // Preserve scroll position after submission
        'preserve_scroll' => false,

        // Reset form data after successful submission
        'reset_on_success' => false,

        // Restore form to default values after successful submission
        'restore_on_success' => false,

        // Submit form in background (keep inputs enabled)
        'background' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Confirmation Dialog
    |--------------------------------------------------------------------------
    |
    | Default settings for form confirmation dialogs.
    |
    */
    'confirmation' => [
        // Default confirmation message
        'text' => 'Are you sure you want to proceed?',

        // Default confirm button text
        'confirm_button' => 'Confirm',

        // Default cancel button text
        'cancel_button' => 'Cancel',
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-Submit Settings
    |--------------------------------------------------------------------------
    |
    | Settings for automatic form submission on field changes.
    |
    */
    'auto_submit' => [
        // Default debounce time in milliseconds
        'debounce' => 300,
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

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Unisender API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Unisender API integration.
    | You can get your API key from your Unisender account dashboard.
    |
    */

    // Your Unisender API key
    'api_key' => env('UNISENDER_API_KEY', ''),

    // Character encoding (default: UTF-8)
    'encoding' => env('UNISENDER_ENCODING', 'UTF-8'),

    // Number of retry attempts for failed requests (default: 4)
    'retry_count' => env('UNISENDER_RETRY_COUNT', 4),

    // Request timeout in seconds (null = no timeout)
    'timeout' => env('UNISENDER_TIMEOUT', null),

    // Enable compression for requests (default: false)
    'compression' => env('UNISENDER_COMPRESSION', false),

    // Platform identifier (e.g., "My E-commerce v1.0")
    'platform' => env('UNISENDER_PLATFORM', 'Laravel Unisender Service'),

    // API language (en, ru, ua)
    'lang' => env('UNISENDER_LANG', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for common operations
    |
    */

    // Default sender name for SMS
    'default_sms_sender' => env('UNISENDER_DEFAULT_SMS_SENDER', ''),

    // Default sender email for emails
    'default_email_sender' => env('UNISENDER_DEFAULT_EMAIL_SENDER', ''),

    // Default list ID for contacts
    'default_list_id' => env('UNISENDER_DEFAULT_LIST_ID', null),

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure logging for API requests and responses
    |
    */

    // Enable detailed logging
    'enable_logging' => env('UNISENDER_ENABLE_LOGGING', true),

    // Log level for API requests
    'log_level' => env('UNISENDER_LOG_LEVEL', 'info'),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching for API responses
    |
    */

    // Enable caching for API responses
    'enable_cache' => env('UNISENDER_ENABLE_CACHE', false),

    // Cache TTL in seconds
    'cache_ttl' => env('UNISENDER_CACHE_TTL', 3600),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for API requests
    |
    */

    // Enable rate limiting
    'enable_rate_limiting' => env('UNISENDER_ENABLE_RATE_LIMITING', false),

    // Maximum requests per minute
    'rate_limit_per_minute' => env('UNISENDER_RATE_LIMIT_PER_MINUTE', 60),

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configure webhook endpoints for async operations
    |
    */

    // Webhook URL for async operations
    'webhook_url' => env('UNISENDER_WEBHOOK_URL', null),

    // Webhook secret for verification
    'webhook_secret' => env('UNISENDER_WEBHOOK_SECRET', null),
]; 

<?php


// File: config/fbr-digital-invoicing.php
return [
    /*
    |--------------------------------------------------------------------------
    | FBR Digital Invoicing Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Pakistan FBR Digital Invoicing API integration
    |
    */

    // Bearer token for API authentication (5 years validity)
    'bearer_token' => env('FBR_BEARER_TOKEN', ''),

    // Sandbox mode (true for testing, false for production)
    'sandbox' => env('FBR_SANDBOX', true),

    // API URLs
    'urls' => [
        'sandbox' => 'https://gw.fbr.gov.pk/di_data/v1/di/',
        'production' => 'https://gw.fbr.gov.pk/di_data/v1/di/',
        'reference' => 'https://gw.fbr.gov.pk/pdi/v1/',
        'statl' => 'https://gw.fbr.gov.pk/dist/v1/',
    ],

    // QR Code Settings
    'qr_code' => [
        'version' => '2.0',
        'dimensions' => '1.0x1.0 Inch',
        'size' => '25x25',
    ],

    // Default values
    'defaults' => [
        'invoice_type' => 'Sale Invoice',
        'buyer_registration_type' => 'Registered',
        'sale_type' => 'Goods at standard rate (default)',
    ],

    // Error handling
    'timeout' => env('FBR_API_TIMEOUT', 30), // seconds
    'retry_attempts' => env('FBR_RETRY_ATTEMPTS', 3),

    // Logging
    'logging' => [
        'enabled' => env('FBR_LOGGING_ENABLED', true),
        'channel' => env('FBR_LOG_CHANNEL', 'daily'),
        'level' => env('FBR_LOG_LEVEL', 'info'),
    ],
];

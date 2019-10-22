<?php
declare(strict_types=1);

return [
    /**
     * The Trustpilot accounts API key.
     */
    'apiKey' => env('TRUSTPILOT_API_KEY'),

    /**
     * The Trustpilot accounts API secret.
     */
    'apiSecret' => env('TRUSTPILOT_API_SECRET'),

    /**
     * The Trustpilot accounts API secret.
     */
    'redirectUrl' => env('TRUSTPILOT_REDIRECT_URL'),

    /**
     * If the plan is to use the "password" grant type,
     * the username and password should be entered here.
     */
    'credentials' => [
        'username' => env('TRUSTPILOT_CREDENTIALS_USERNAME'),
        'password' => env('TRUSTPILOT_CREDENTIALS_PASSWORD'),
    ],

    /**
     * Provide a default business unit ID,
     * so you don't to provide it with every endpoint.
     */
    'businessUnitId' => env('TRUSTPILOT_BUSINESS_UNIT_ID'),
];

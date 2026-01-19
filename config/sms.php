<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SMS API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Text-Ware SMS Gateway
    |
    */

    'api_url' => env('SMS_API_URL', 'https://msg.text-ware.com/send_sms.php'),

    // NOTE: Do NOT commit credentials to the repository. Set these values in your environment (.env) file.
    'username' => env('SMS_USERNAME'),
    'password' => env('SMS_PASSWORD'),
    'source' => env('SMS_SOURCE'),
    
    /*
    |--------------------------------------------------------------------------
    | Test Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, SMS will only be logged instead of being sent.
    | Useful for local development and testing.
    |
    */
    
    'test_mode' => env('SMS_TEST_MODE', true),
];

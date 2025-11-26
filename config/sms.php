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
    'username' => env('SMS_USERNAME', 'TW01050_sitc_campus_tr'),
    'password' => env('SMS_PASSWORD', 'k0oMp@1DmBXbd'),
    'source' => env('SMS_SOURCE', 'SITC CAMPUS'),
    
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

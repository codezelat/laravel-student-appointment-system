<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Credentials
    |--------------------------------------------------------------------------
    |
    | These credentials are used for the admin authentication system.
    | The password should be a bcrypt hashed value.
    | You can generate a hash using: php artisan tinker
    | Then run: Hash::make('your-password')
    |
    */

    'username' => env('ADMIN_USERNAME', 'admin'),
    
    'password' => env('ADMIN_PASSWORD'),

];

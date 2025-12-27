<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Cloud URL
    |--------------------------------------------------------------------------
    */
    'cloud_url' => env('CLOUDINARY_URL'), // Required, empty string as fallback

    /*
    |--------------------------------------------------------------------------
    | Upload Preset (optional)
    |--------------------------------------------------------------------------
    | Default to empty string to avoid null array offset errors
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', null),

    /*
    |--------------------------------------------------------------------------
    | Notification URL (optional)
    |--------------------------------------------------------------------------
    | Default to empty string
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Upload Route / Action (optional)
    |--------------------------------------------------------------------------
    | Default to empty string
    */
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE', null),
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION', null),

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME','durrjejwp'),
    'api_key'    => env('CLOUDINARY_API_KEY','788679216488928'),
    'api_secret' => env('CLOUDINARY_API_SECRET','QTzgORhisAKmzRFiWLZrWnvSv24'),
];

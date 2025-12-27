<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Cloud URL
    |--------------------------------------------------------------------------
    */
    'cloud_url' => env('CLOUDINARY_URL', ''), // Required, empty string as fallback

    /*
    |--------------------------------------------------------------------------
    | Upload Preset (optional)
    |--------------------------------------------------------------------------
    | Default to empty string to avoid null array offset errors
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', ''),

    /*
    |--------------------------------------------------------------------------
    | Notification URL (optional)
    |--------------------------------------------------------------------------
    | Default to empty string
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Upload Route / Action (optional)
    |--------------------------------------------------------------------------
    | Default to empty string
    */
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE', ''),
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION', ''),
];

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Cloud URL
    |--------------------------------------------------------------------------
    |
    | Your Cloudinary URL, in the format:
    | cloudinary://API_KEY:API_SECRET@CLOUD_NAME
    |
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload Preset (optional)
    |--------------------------------------------------------------------------
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', null),

    /*
    |--------------------------------------------------------------------------
    | Notification URL (optional)
    |--------------------------------------------------------------------------
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Upload route/action (optional)
    |--------------------------------------------------------------------------
    */
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE', null),
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION', null),
];

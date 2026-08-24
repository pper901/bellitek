<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Guest Authentication Routes
|--------------------------------------------------------------------------
|
| These routes are accessible to unauthenticated users.
|
| The blacklist middleware is intentionally applied here so a blacklisted
| IP cannot:
|
| - Register
| - Login
| - Request a password reset
| - Submit a password reset
|
*/

Route::middleware([
    'guest',
    'blacklist',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    */

    Route::get(
        'register',
        [RegisteredUserController::class, 'create']
    )->name('register');

    Route::post(
        'register',
        [RegisteredUserController::class, 'store']
    );


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    Route::get(
        'login',
        [AuthenticatedSessionController::class, 'create']
    )->name('login');

    Route::post(
        'login',
        [AuthenticatedSessionController::class, 'store']
    );


    /*
    |--------------------------------------------------------------------------
    | Password Reset Request
    |--------------------------------------------------------------------------
    */

    Route::get(
        'forgot-password',
        [PasswordResetLinkController::class, 'create']
    )->name('password.request');

    Route::post(
        'forgot-password',
        [PasswordResetLinkController::class, 'store']
    )->name('password.email');


    /*
    |--------------------------------------------------------------------------
    | Password Reset
    |--------------------------------------------------------------------------
    */

    Route::get(
        'reset-password/{token}',
        [NewPasswordController::class, 'create']
    )->name('password.reset');

    Route::post(
        'reset-password',
        [NewPasswordController::class, 'store']
    )->name('password.store');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
| The blacklist middleware is also applied here.
|
| This means a user who was blacklisted after logging in cannot continue
| making authenticated requests.
|
*/

Route::middleware([
    'auth',
    'blacklist',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    */

    Route::get(
        'verify-email',
        EmailVerificationPromptController::class
    )->name('verification.notice');


    Route::get(
        'verify-email/{id}/{hash}',
        VerifyEmailController::class
    )
        ->middleware('signed')
        ->middleware('throttle:6,1')
        ->name('verification.verify');


    Route::post(
        'email/verification-notification',
        [EmailVerificationNotificationController::class, 'store']
    )
        ->middleware('throttle:6,1')
        ->name('verification.send');


    /*
    |--------------------------------------------------------------------------
    | Password Confirmation
    |--------------------------------------------------------------------------
    */

    Route::get(
        'confirm-password',
        [ConfirmablePasswordController::class, 'show']
    )->name('password.confirm');

    Route::post(
        'confirm-password',
        [ConfirmablePasswordController::class, 'store']
    );


    /*
    |--------------------------------------------------------------------------
    | Password
    |--------------------------------------------------------------------------
    */

    Route::put(
        'password',
        [PasswordController::class, 'update']
    )->name('password.update');


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post(
        'logout',
        [AuthenticatedSessionController::class, 'destroy']
    )->name('logout');
});
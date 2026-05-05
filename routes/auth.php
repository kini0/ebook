<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication routes (FR)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/connexion',    [LoginController::class, 'show'])->name('login');
    Route::post('/connexion',   [LoginController::class, 'store'])->middleware('throttle:5,1');

    // Register
    Route::get('/inscription',  [RegisterController::class, 'show'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store']);

    // Password
    Route::get('/mot-de-passe-oublie',     [PasswordResetController::class, 'showRequest'])->name('password.request');
    Route::post('/mot-de-passe-oublie',    [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/mot-de-passe/reinitialiser/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/mot-de-passe/reinitialiser', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    // Email verification
    Route::get('/verifier-email',           [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/verifier-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/verifier-email/renvoyer', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');

    Route::post('/deconnexion', [LoginController::class, 'destroy'])->name('logout');
});

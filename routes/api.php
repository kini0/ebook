<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EbookController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/ebooks',              [EbookController::class, 'index']);
    Route::get('/ebooks/{ebook:slug}', [EbookController::class, 'show']);

    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
    Route::post('/auth/login',    [AuthController::class, 'login'])->middleware('throttle:6,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me',      [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/orders',                   [OrderController::class, 'index']);
        Route::get('/orders/{order:reference}', [OrderController::class, 'show']);
    });
});

<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PricingController;

Route::get('/ping', function () {
    return response()->json(['message' => 'Backend OK']);
});
Route::apiResource('bookings', BookingController::class)
    ->only(['index', 'store', 'show']);
Route::post('/bookings/{booking}/approve', [BookingController::class, 'approve']);
Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject']);
Route::post('/pricing/preview', [PricingController::class, 'previewPublic']);
Route::apiResource('events', EventController::class)
    ->only(['index', 'show', 'store', 'update']);
Route::post('/events/{event}/recommend-price', [PricingController::class, 'recommendForEvent']);
Route::post(
    '/price-recommendations/{priceRecommendation}/apply',
    [PricingController::class, 'applyRecommendation']
);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

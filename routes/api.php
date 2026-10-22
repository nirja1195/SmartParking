<?php

use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\BookingApiController;
use App\Http\Controllers\Api\V1\ParkingLotApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Prefix: /api)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware(['force.json'])->group(function () {

    // Public Authentication & Information Endpoints
    Route::post('/auth/login', [AuthApiController::class, 'login']);
    Route::post('/auth/register', [AuthApiController::class, 'register']);

    // Public Parking Lot Feeds
    Route::get('/parking-lots', [ParkingLotApiController::class, 'index']);
    Route::get('/parking-lots/{id}', [ParkingLotApiController::class, 'show']);
    Route::get('/parking-lots/{id}/spots', [ParkingLotApiController::class, 'availableSpots']);

    // Protected API Endpoints (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/profile', [AuthApiController::class, 'profile']);
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);

        // Bookings
        Route::get('/bookings', [BookingApiController::class, 'index']);
        Route::post('/bookings', [BookingApiController::class, 'store']);
        Route::get('/bookings/{reference}', [BookingApiController::class, 'show']);
        Route::post('/bookings/{reference}/check-in', [BookingApiController::class, 'checkIn']);
        Route::post('/bookings/{reference}/check-out', [BookingApiController::class, 'checkOut']);
        Route::post('/bookings/{reference}/cancel', [BookingApiController::class, 'cancel']);
    });
});

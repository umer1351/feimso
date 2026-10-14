<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ListingController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\VerificationController;
use App\Http\Controllers\Api\V1\DisputeController;

// Legacy (keep as-is)
Route::middleware(['web', 'auth'])->post('/delete-user', [App\Http\Controllers\ApiController::class, 'deleteUserFromDb'])->name('deleteUserFromDb');

// ─── Feimso 2.0 Mobile API ───────────────────────────────────────────────────
Route::prefix('v1')->group(function () {

    // Auth (public)
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login',    [AuthController::class, 'login']);

    // Listings (public browse)
    Route::get('/listings',       [ListingController::class, 'index']);
    Route::get('/listings/{id}',  [ListingController::class, 'show']);

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/auth/logout',   [AuthController::class, 'logout']);
        Route::get('/auth/me',        [AuthController::class, 'me']);
        Route::patch('/auth/profile', [AuthController::class, 'updateProfile']);

        // Verification
        Route::post('/verification/submit', [VerificationController::class, 'submit']);
        Route::get('/verification/status',  [VerificationController::class, 'status']);

        // Listings (owner actions)
        Route::post('/listings',           [ListingController::class, 'store']);
        Route::patch('/listings/{id}',     [ListingController::class, 'update']);
        Route::get('/my/listings',         [ListingController::class, 'myListings']);

        // Bookings
        Route::post('/listings/{listingId}/book', [BookingController::class, 'store']);
        Route::get('/my/bookings',                [BookingController::class, 'myBookings']);
        Route::get('/my/sales',                   [BookingController::class, 'mySales']);
        Route::get('/bookings/{id}',              [BookingController::class, 'show']);
        Route::post('/bookings/{id}/cancel',      [BookingController::class, 'cancel']);

        // Disputes
        Route::post('/disputes',     [DisputeController::class, 'store']);
        Route::get('/my/disputes',   [DisputeController::class, 'myDisputes']);
    });
});

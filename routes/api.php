<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AdminController;
use App\Models\Room;
use Illuminate\Support\Facades\Route;
use App\Events\RoomStatusUpdated;

// PUBLIC
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/qr/verify/{token}', [BookingController::class, 'verifyQr']);
Route::get('/buildings',         [BuildingController::class, 'index']);
Route::get('/buildings/{id}',    [BuildingController::class, 'show']);

// AUTHENTICATED
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    Route::get('/rooms',      [RoomController::class, 'index']);
    Route::get('/rooms/{id}', [RoomController::class, 'show']);

    Route::get('/schedules',                   [ScheduleController::class, 'index']);
    Route::get('/schedules/{id}',              [ScheduleController::class, 'show']);
    Route::get('/schedules/by-room/{roomId}',  [ScheduleController::class, 'byRoom']);

    Route::get('/bookings/my',                  [BookingController::class, 'myBookings']);
    Route::post('/bookings',                    [BookingController::class, 'store']);
    Route::delete('/bookings/{id}/cancel',      [BookingController::class, 'cancel']);
    Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability']);

    // ADMIN ONLY
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/stats',                       [AdminController::class, 'stats']);
        Route::get('/bookings',                    [AdminController::class, 'bookings']);
        Route::post('/bookings/{id}/approve',      [AdminController::class, 'approve']);
        Route::post('/bookings/{id}/reject',       [AdminController::class, 'reject']);

        Route::post('/buildings',        [BuildingController::class, 'store']);
        Route::put('/buildings/{id}',    [BuildingController::class, 'update']);
        Route::delete('/buildings/{id}', [BuildingController::class, 'destroy']);

        Route::post('/rooms',        [RoomController::class, 'store']);
        Route::put('/rooms/{id}',    [RoomController::class, 'update']);
        Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);
        Route::delete('/rooms/{id}/photo',  [RoomController::class, 'deletePhoto']); // ← tambahkan

        Route::post('/schedules',        [ScheduleController::class, 'store']);
        Route::put('/schedules/{id}',    [ScheduleController::class, 'update']);
        Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);
    });
});
//FINAL ROUTE
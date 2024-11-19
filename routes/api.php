<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdditionalServiceController;


Route::prefix('v1')->group(function () {

    Route::get('user', [UserController::class, 'getCurrent']);
    Route::get('users', [UserController::class, 'getAll']);

    Route::post('register', [UserController::class, 'register'])->name('register');
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::get('logout', [UserController::class, 'logout'])->name('logout');

    Route::apiResource('trips', TripController::class);
    Route::apiResource('tours', TourController::class);
    Route::apiResource('tourists',TouristController::class);
    Route::apiResource('reviews',ReviewController::class);
    Route::apiResource('additional_service',AdditionalServiceController::class);

    Route::apiResource('tours/{tour_id}/trips', TripController::class);

    Route::get('wp-json/tour/get', [TourController::class, 'getOldTours'])->name('oldTours');
});

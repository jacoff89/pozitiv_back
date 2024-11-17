<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdditionalServiceController;
use App\Http\Controllers\ApiController;


Route::prefix('v1')->group(function () {
    Route::get('user', function () {
        return Auth::user();
    })->middleware('auth');

    Route::post('register', [AuthController::class, 'createUser'])->name('register');
    Route::post('login', [AuthController::class, 'loginUser'])->name('login');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('trips', TripController::class);
    Route::apiResource('tours', TourController::class);
    Route::apiResource('tourists',TouristController::class);
    Route::apiResource('reviews',ReviewController::class);
    Route::apiResource('additional_service',AdditionalServiceController::class);

    Route::get('wp-json/tour/get', [ApiController::class, 'tours'])->name('tours');
});

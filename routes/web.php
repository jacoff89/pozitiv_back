<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('api/v1/reviews', [ApiController::class, 'reviews'])->name('reviews');

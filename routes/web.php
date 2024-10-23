<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('api/v1/reviews', [ApiController::class, 'reviews'])->name('reviews');
//Route::match(['get', 'post'],'wp-json/pozitiv/v1/tour/get', [ApiController::class, 'tours'])->name('tours');
//Route::match(['get', 'post'],'wp-json/pozitiv/v1/user/current', [ApiController::class, 'tours'])->name('test1');

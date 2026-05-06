<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stats', [StatsController::class, 'index'])
    ->name('stats')
    ->middleware('basic.auth');
Route::get('/api/stats/hourly', [StatsController::class, 'hourlyStats']);
Route::get('/api/stats/cities', [StatsController::class, 'cityStats']);
Route::get('/api/stats/general', [StatsController::class, 'generalStats']);
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\JokeController;
use App\Http\Controllers\VisitController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/jokes', [JokeController::class, 'index']);
Route::get('/jokes/{type}', [JokeController::class, 'byType']);

Route::post('/collect-visit', [VisitController::class, 'collect']);
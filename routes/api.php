<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/login', 'loginWithCredentials');
    Route::get('/me', 'me')->middleware('valid.token');
    Route::post('/logout', 'logout')->middleware('valid.token');
});

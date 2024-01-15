<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusScheduleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OrganizationController;
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



Route::get('/health', function () {
    return response()->json(['success' => true]);
});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/login', 'loginWithCredentials');
    Route::get('/me', 'me')->middleware('valid.token');
    Route::post('/logout', 'logout')->middleware('valid.token');
});


Route::middleware('valid.token')->group(function () {
    Route::middleware('verify.busyan:bus-operator')->group(function () {
        Route::controller(BusController::class)->prefix('buses')->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

        Route::controller(EmployeeController::class)->prefix('employees')->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

    Route::controller(JobController::class)->prefix('jobs')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        // Route::middleware('verify.busyan:bus-operator')->group(function () {
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        // });
    });

    Route::controller(ApplicationController::class)->prefix('applications')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::middleware('verify.busyan:jobseeker')->group(function () {
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

    Route::controller(OrganizationController::class)->prefix('organizations')->group(function () {
        Route::post('/', 'store');
        Route::middleware('verify.busyan:admin')->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

    Route::controller(OperatorController::class)->prefix('operators')->group(function () {
        Route::post('/', 'store');
        Route::middleware('verify.busyan:admin')->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

    Route::controller(BusScheduleController::class)->prefix('bus-schedules')->group(function () {
        Route::post('/', 'store');
    });
});

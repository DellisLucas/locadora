<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReportController;


Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {
    Route::get('/vehicles/search', [VehicleController::class, 'search']);
    Route::apiResource('vehicles', VehicleController::class);
});

Route::apiResource('customers', CustomerController::class);

Route::prefix('rentals')->middleware('auth:api')->group(function () {
    Route::post('/', [RentalController::class, 'store']);
    Route::post('{id}/start', [RentalController::class, 'start']);
    Route::post('{id}/end', [RentalController::class, 'end']);
    Route::get('/', [RentalController::class, 'index']);
    Route::get('{id}', [RentalController::class, 'show']);
});

Route::middleware('auth:api')->get('/reports/revenue', [ReportController::class, 'revenue']);

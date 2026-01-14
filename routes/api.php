<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CommunityController;
use App\Http\Controllers\Api\V1\MunicipalityController;
use App\Http\Controllers\Api\V1\RegionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('regions', RegionController::class);
    Route::patch('regions/{id}/status', [RegionController::class, 'toggleStatus']);

    Route::apiResource('municipalities', MunicipalityController::class);
    Route::patch('municipalities/{id}/status', [MunicipalityController::class, 'toggleStatus']);

    Route::apiResource('communities', CommunityController::class);
    Route::patch('communities/{id}/status', [CommunityController::class, 'toggleStatus']);

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });
});

<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CommunityController;
use App\Http\Controllers\Api\V1\MunicipalityController;
use App\Http\Controllers\Api\V1\RegionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {

        /** ##### Rutas de autenticacion publicas ##### */
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        /** ##### Rutas de autenticacion protegidas ##### */
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

    /** ##### Rutas publicas ##### */
    Route::get('regions', [RegionController::class, 'index']);
    Route::get('regions/{id}', [RegionController::class, 'show']);
    Route::get('municipalities', [MunicipalityController::class, 'index']);
    Route::get('municipalities/{id}', [MunicipalityController::class, 'show']);
    Route::get('communities', [CommunityController::class, 'index']);
    Route::get('communities/{id}', [CommunityController::class, 'show']);

    /** ##### Rutas protegidas ##### */
    /** ##### Acceso solo permitido para el administrador ##### */
    Route::middleware(['auth:sanctum', 'role:administrator'])->group(function () {

        /** ##### Rutas para regiones ##### */
        Route::post('regions', [RegionController::class, 'store']);
        Route::put('regions/{id}', [RegionController::class, 'update']);
        Route::delete('regions/{id}', [RegionController::class, 'destroy']);
        Route::patch('regions/{id}/status', [RegionController::class, 'toggleStatus']);

        /** ##### Rutas para municipios ##### */
        Route::post('municipalities', [MunicipalityController::class, 'store']);
        Route::post('municipalities/{id}', [MunicipalityController::class, 'update']);
        Route::delete('municipalities/{id}', [MunicipalityController::class, 'destroy']);
        Route::patch('municipalities/{id}/status', [MunicipalityController::class, 'toggleStatus']);
        Route::patch(
            'municipalities/{id}/translations/{languageCode}',
            [MunicipalityController::class, 'updateTranslation']
        );

        /** ##### Rutas para comunidades ##### */
        Route::post('communities', [CommunityController::class, 'store']);
        Route::put('communities/{id}', [CommunityController::class, 'update']);
        Route::delete('communities/{id}', [CommunityController::class, 'destroy']);
        Route::patch('communities/{id}/status', [CommunityController::class, 'toggleStatus']);
    });
});

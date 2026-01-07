<?php

use App\Http\Controllers\Api\V1\MunicipalityController;
use App\Http\Controllers\Api\V1\RegionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::apiResource('regions', RegionController::class);
    Route::patch('regions/{id}/status', [RegionController::class, 'toggleStatus']);

    Route::apiResource('municipalities', MunicipalityController::class);
});

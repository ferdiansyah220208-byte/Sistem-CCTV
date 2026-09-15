<?php

use App\Http\Controllers\Api\BuildingApiController;
use App\Http\Controllers\Api\CameraApiController;
use App\Http\Controllers\Api\FloorApiController;
use App\Http\Controllers\Api\RoomApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('buildings', BuildingApiController::class);
    Route::apiResource('floors', FloorApiController::class);
    Route::apiResource('rooms', RoomApiController::class);
    Route::apiResource('cameras', CameraApiController::class);

    Route::get('cameras/search/{keyword}', [CameraApiController::class, 'search']);
    Route::get('floors/{floor}/layout', [FloorApiController::class, 'layout']);
});
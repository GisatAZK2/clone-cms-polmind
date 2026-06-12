<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\PopupController;
use App\Http\Controllers\V1\NewsController;
use App\Http\Controllers\V1\UserController;

Route::prefix('v1')->group(function () {

    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    Route::middleware('auth:sanctum', 'api.auth')->group(function () {
        Route::get('/me', [UserController::class, 'me']);
        Route::post('/logout', [UserController::class, 'logout']);
        Route::apiResource('news', NewsController::class);
        Route::apiResource('popups', PopupController::class);
    });

});





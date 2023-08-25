<?php

use App\Http\Controllers\Api\v1\AdminController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Middleware\AdminCheckMiddleware;
use App\Http\Middleware\UserCheckMiddleware;
use App\Http\Middleware\JwtMiddleware;
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

Route::prefix('v1')->group(function () {
    // Admin routes
    Route::post('admin/login', [AdminController::class, 'login']);

    Route::middleware([JwtMiddleware::class, AdminCheckMiddleware::class])->prefix('admin')->group(function () {
        Route::get('logout', [AdminController::class, 'logout']);
    });

    // User routes
    Route::post('user/login', [UserController::class, 'login']);

    Route::middleware([JwtMiddleware::class, UserCheckMiddleware::class])->prefix('user')->group(function () {
        Route::get('logout', [UserController::class, 'logout']);
    });
});


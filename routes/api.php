<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthApiController;
Route::prefix('v1')->group(function () {
    Route::controller(AuthApiController::class)->group(function () {
        route::post('/login', 'authenticate')->name('login');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::controller(AuthApiController::class)->group(function () {
            route::post('/logout', 'logout')->name('logout');
        });
    });
});

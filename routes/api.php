<?php

use App\Http\Controllers\api\InboundRequestController;
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
        Route::controller(InboundRequestController::class)->group(function () {
            route::post('/inbound/cek_kode_inbound', 'cek_kode_inbound')->name('inbound.cek_kode_inbound');
            route::post('/inbound/inbound_cek_sku', 'inbound_cek_sku')->name('inbound.inbound_cek_sku');
            route::post('/inbound/inbound_register_dtl', 'inbound_register_dtl')->name('inbound.inbound_register_dtl');
        });
    });
});

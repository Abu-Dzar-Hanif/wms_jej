<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\editor\CustomerController;
use App\Http\Controllers\editor\DashboardController;
use App\Http\Controllers\editor\MenuController;
use App\Http\Controllers\editor\SkuTypeController;
use App\Http\Controllers\editor\UomController;
use App\Http\Controllers\editor\UserController;
use App\Http\Controllers\editor\UserAccessController;
use App\Http\Controllers\editor\VendorController;
use App\Http\Controllers\editor\WarehouseController;

Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'index')->name('login');
        Route::post('/login', 'authenticate')->name('login.auth');
    });
});
Route::prefix('editor')->middleware('auth')->group(function(){
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('editor.dashboard');
    });
    Route::controller(MenuController::class)->group(function(){
        Route::get('/menu','index')->name('editor.menu');
        Route::get('/menu/data','getData')->name('editor.menu.data');
        Route::post('/menu/manage','StoreUpdateData')->name('editor.menu.manage');
        Route::get('/menu/detail','detailData')->name('editor.menu.detail');
        Route::delete('/menu/delete','deleteData')->name('editor.menu.delete');
    });
    Route::controller(UserController::class)->group(function(){
        Route::get('/user','index')->name('editor.user');
        Route::get('/user/data','getData')->name('editor.user.data');
        Route::post('/user/store','storeData')->name('editor.user.store');
        Route::post('/user/update','updateData')->name('editor.user.update');
        Route::get('/user/detail','detailData')->name('editor.user.detail');
        Route::delete('/user/delete','deleteData')->name('editor.user.delete');
    });
    Route::controller(UserAccessController::class)->group(function(){
        Route::get('/access/data','getData')->name('editor.access.data');
        Route::post('/access/manage','StoreUpdateData')->name('editor.access.manage');
    });
    Route::controller(UomController::class)->group(function(){
        Route::get('/uom','index')->name('editor.uom');
        Route::get('/uom/data','getData')->name('editor.uom.data');
        Route::post('/uom/manage','StoreUpdateData')->name('editor.uom.manage');
        Route::get('/uom/detail','detailData')->name('editor.uom.detail');
        Route::delete('/uom/delete','deleteData')->name('editor.uom.delete');
    });
    Route::controller(SkuTypeController::class)->group(function(){
        Route::get('/sku-type','index')->name('editor.sku-type');
        Route::get('/sku-type/data','getData')->name('editor.sku-type.data');
        Route::post('/sku-type/manage','StoreUpdateData')->name('editor.sku-type.manage');
        Route::get('/sku-type/detail','detailData')->name('editor.sku-type.detail');
        Route::delete('/sku-type/delete','deleteData')->name('editor.sku-type.delete');
    });
    Route::controller(WarehouseController::class)->group(function(){
        Route::get('/warehouse','index')->name('editor.warehouse');
        Route::get('/warehouse/data','getData')->name('editor.warehouse.data');
        Route::post('/warehouse/manage','StoreUpdateData')->name('editor.warehouse.manage');
        Route::get('/warehouse/detail','detailData')->name('editor.warehouse.detail');
        Route::delete('/warehouse/delete','deleteData')->name('editor.warehouse.delete');
    });
    Route::controller(VendorController::class)->group(function(){
        Route::get('/vendor','index')->name('editor.vendor');
        Route::get('/vendor/data','getData')->name('editor.vendor.data');
        Route::post('/vendor/manage','StoreUpdateData')->name('editor.vendor.manage');
        Route::get('/vendor/detail','detailData')->name('editor.vendor.detail');
        Route::delete('/vendor/delete','deleteData')->name('editor.vendor.delete');
    });
    Route::controller(CustomerController::class)->group(function(){
        Route::get('/customer','index')->name('editor.customer');
        Route::get('/customer/data','getData')->name('editor.customer.data');
        Route::post('/customer/manage','StoreUpdateData')->name('editor.customer.manage');
        Route::get('/customer/detail','detailData')->name('editor.customer.detail');
        Route::delete('/customer/delete','deleteData')->name('editor.customer.delete');
    });
});
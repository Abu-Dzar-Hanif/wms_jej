<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\editor\CategoryController;
use App\Http\Controllers\editor\CustomerController;
use App\Http\Controllers\editor\DashboardController;
use App\Http\Controllers\editor\InboundRequestController;
use App\Http\Controllers\editor\MenuController;
use App\Http\Controllers\editor\SkuDataController;
use App\Http\Controllers\editor\SkuTypeController;
use App\Http\Controllers\editor\UomController;
use App\Http\Controllers\editor\UserController;
use App\Http\Controllers\editor\UserAccessController;
use App\Http\Controllers\editor\UserWhAccessController;
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
        Route::get('/uom/data/select','getDataSelect')->name('editor.uom.data.select');
        Route::post('/uom/manage','StoreUpdateData')->name('editor.uom.manage');
        Route::get('/uom/detail','detailData')->name('editor.uom.detail');
        Route::delete('/uom/delete','deleteData')->name('editor.uom.delete');
    });
    Route::controller(SkuTypeController::class)->group(function(){
        Route::get('/sku-type','index')->name('editor.sku-type');
        Route::get('/sku-type/data','getData')->name('editor.sku-type.data');
        Route::get('/sku-type/data/select','getDataSelect')->name('editor.sku-type.data.select');
        Route::post('/sku-type/manage','StoreUpdateData')->name('editor.sku-type.manage');
        Route::get('/sku-type/detail','detailData')->name('editor.sku-type.detail');
        Route::delete('/sku-type/delete','deleteData')->name('editor.sku-type.delete');
    });
    Route::controller(WarehouseController::class)->group(function(){
        Route::get('/warehouse','index')->name('editor.warehouse');
        Route::get('/warehouse/data','getData')->name('editor.warehouse.data');
        Route::get('/warehouse/data/select','getDataSelect')->name('editor.warehouse.data.select');
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
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/category','index')->name('editor.category');
        Route::get('/category/data','getData')->name('editor.category.data');
        Route::get('/category/data/select','getDataSelect')->name('editor.category.data.select');
        Route::post('/category/manage','StoreUpdateData')->name('editor.category.manage');
        Route::get('/category/detail','detailData')->name('editor.category.detail');
        Route::delete('/category/delete','deleteData')->name('editor.category.delete');
    });
    Route::controller(SkuDataController::class)->group(function(){
        Route::get('/sku','index')->name('editor.sku');
        Route::get('/sku/data','getData')->name('editor.sku.data');
        Route::post('/sku/manage','StoreUpdateData')->name('editor.sku.manage');
        Route::get('/sku/detail','detailData')->name('editor.sku.detail');
        Route::delete('/sku/delete','deleteData')->name('editor.sku.delete');
        Route::get('/sku/generate/code','generateCode')->name('editor.sku.generate.code');
    });
    Route::controller(InboundRequestController::class)->group(function(){
        Route::get('/inbound-request','index')->name('editor.inbound-request');
        Route::get('/inbound-request/data','getData')->name('editor.inbound-request.data');
        Route::post('/inbound-request/upload/stock','uploadDataStock')->name('editor.inbound-request.upload.stock');
    });
    Route::controller(UserWhAccessController::class)->group(function(){
        Route::get('/access-wh/data','getData')->name('editor.access-wh.data');
        Route::post('/access-wh/store','StoreData')->name('editor.access-wh.store');
        Route::delete('/access-wh/delete','deleteData')->name('editor.access-wh.delete');
    });
});

<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// User Routes
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::get('/users/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
Route::post('/users/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
Route::get('/user/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('users.delete');

// Companies Routes
Route::get('/company', [App\Http\Controllers\CompanyController::class, 'index'])->name('company.index');
Route::get('/company/create', [App\Http\Controllers\CompanyController::class, 'create'])->name('company.create');
Route::post('/company/store', [App\Http\Controllers\CompanyController::class, 'store'])->name('company.store');
Route::get('/company/edit/{id}', [App\Http\Controllers\CompanyController::class, 'edit'])->name('company.edit');
Route::post('/company/update/{id}', [App\Http\Controllers\CompanyController::class, 'update'])->name('company.update');
Route::get('/company/delete/{id}', [App\Http\Controllers\CompanyController::class, 'delete'])->name('company.delete');

// Service Routes
Route::get('/service', [App\Http\Controllers\ServiceController::class, 'index'])->name('service.index');
Route::get('/service/create', [App\Http\Controllers\ServiceController::class, 'create'])->name('service.create');
Route::post('/service/store', [App\Http\Controllers\ServiceController::class, 'store'])->name('service.store');
Route::get('/service/edit/{id}', [App\Http\Controllers\ServiceController::class, 'edit'])->name('service.edit');
Route::post('/service/update/{id}', [App\Http\Controllers\ServiceController::class, 'update'])->name('service.update');
Route::get('/service/delete/{id}', [App\Http\Controllers\ServiceController::class, 'delete'])->name('service.delete');

// Product Routes
Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index');
Route::get('/product/create', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create');
Route::post('/product/store', [App\Http\Controllers\ProductController::class, 'store'])->name('product.store');
Route::get('/product/edit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit');
Route::post('/product/update/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('product.update');
Route::get('/product/delete/{id}', [App\Http\Controllers\ProductController::class, 'delete'])->name('product.delete');

// Mechanic Routes
Route::get('/mechanic', [App\Http\Controllers\MechanicController::class, 'index'])->name('mechanic.index');
Route::get('/mechanic/create', [App\Http\Controllers\MechanicController::class, 'create'])->name('mechanic.create');
Route::post('/mechanic/store', [App\Http\Controllers\MechanicController::class, 'store'])->name('mechanic.store');
Route::get('/mechanic/edit/{id}', [App\Http\Controllers\MechanicController::class, 'edit'])->name('mechanic.edit');
Route::post('/mechanic/update/{id}', [App\Http\Controllers\MechanicController::class, 'update'])->name('mechanic.update');
Route::get('/mechanic/delete/{id}', [App\Http\Controllers\MechanicController::class, 'delete'])->name('mechanic.delete');

// Order Routes
Route::get('/order', [App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
Route::get('/order/create', [App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
Route::post('/order/store', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
Route::get('/order/edit/{id}', [App\Http\Controllers\OrderController::class, 'edit'])->name('order.edit');
Route::post('/order/update/{id}', [App\Http\Controllers\OrderController::class, 'update'])->name('order.update');
Route::get('/order/delete/{id}', [App\Http\Controllers\OrderController::class, 'delete'])->name('order.delete');
Route::get('/order/view/{id}', [App\Http\Controllers\OrderController::class, 'view'])->name('order.view');
Route::post('/orders/update-status', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::get('/get-edit-data', [App\Http\Controllers\OrderController::class, 'getEditData'])->name('get.edit.data');

Route::post('/get-product-details', [App\Http\Controllers\OrderController::class, 'getProductDetails'])->name('get.product.details');
Route::post('/get-service-details', [App\Http\Controllers\OrderController::class, 'getServiceDetails'])->name('get.service.details');
Route::post('/get-selected-details', [App\Http\Controllers\OrderController::class, 'getSelectedDetails'])->name('get.selected.details');

// Vendor Routes
Route::get('/vendor', [App\Http\Controllers\VendorController::class, 'index'])->name('vendor.index');
Route::get('/vendor/create', [App\Http\Controllers\VendorController::class, 'create'])->name('vendor.create');
Route::post('/vendor/store', [App\Http\Controllers\VendorController::class, 'store'])->name('vendor.store');
Route::get('/vendor/edit/{id}', [App\Http\Controllers\VendorController::class, 'edit'])->name('vendor.edit');
Route::post('/vendor/update/{id}', [App\Http\Controllers\VendorController::class, 'update'])->name('vendor.update');
Route::get('/vendor/delete/{id}', [App\Http\Controllers\VendorController::class, 'delete'])->name('vendor.delete');

// Vendor Purchase Routes
Route::get('/purchase', [App\Http\Controllers\PurchaseController::class, 'index'])->name('purchase.index');
Route::get('/purchase/create', [App\Http\Controllers\PurchaseController::class, 'create'])->name('purchase.create');
Route::post('/purchase/store', [App\Http\Controllers\PurchaseController::class, 'store'])->name('purchase.store');
Route::get('/purchase/edit/{id}', [App\Http\Controllers\PurchaseController::class, 'edit'])->name('purchase.edit');
Route::post('/purchase/update/{id}', [App\Http\Controllers\PurchaseController::class, 'update'])->name('purchase.update');
Route::get('/purchase/delete/{id}', [App\Http\Controllers\PurchaseController::class, 'delete'])->name('purchase.delete');

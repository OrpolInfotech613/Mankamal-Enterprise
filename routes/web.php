<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProcessingStepController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\EmployeeController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth', 'check.remember'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RoleController::class);
    Route::resource('departments', DepartmentController::class);
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::resource('products', ProductController::class);
    Route::resource('types', TypeController::class);
    Route::resource('users', UserController::class);
    Route::resource('processing-steps', ProcessingStepController::class);
    Route::resource('orders', OrderController::class);
    Route::get('/dealers/search', [DealerController::class, 'search'])->name('dealers.search');
    Route::resource('dealers', DealerController::class);
    Route::resource('employees', EmployeeController::class);

    Route::post('/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});


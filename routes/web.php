<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProcessingStepController;


Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('roles', RoleController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('users', UserController::class);
Route::resource('processing-steps', ProcessingStepController::class);


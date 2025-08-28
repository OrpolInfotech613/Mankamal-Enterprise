<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProcessingStepController;
use App\Http\Controllers\OrderController;


Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::patch('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
    Route::patch('/{id}/status', [OrderController::class, 'updateStatus']);
    
    Route::prefix('reports')->group(function () {
        Route::get('/status/{status}', [OrderController::class, 'index']);
        Route::get('/dealer/{dealerName}', [OrderController::class, 'index']);
    });
});
    

Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'API is running',
        'timestamp' => now()->toISOString()
    ]);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProcessingStepController;
use App\Http\Controllers\Api\APIOrderController;


Route::prefix('orders')->group(function () {
    Route::get('/', [APIOrderController::class, 'index']);
    Route::post('/', [APIOrderController::class, 'store']);
    Route::get('/{id}', [APIOrderController::class, 'show']);
    Route::put('/{id}', [APIOrderController::class, 'update']);
    Route::patch('/{id}', [APIOrderController::class, 'update']);
    Route::delete('/{id}', [APIOrderController::class, 'destroy']);
    Route::patch('/{id}/status', [APIOrderController::class, 'updateStatus']);
    
    Route::prefix('reports')->group(function () {
        Route::get('/status/{status}', [APIOrderController::class, 'index']);
        Route::get('/dealer/{dealerName}', [APIOrderController::class, 'index']);
    });
});
    

Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'API is running',
        'timestamp' => now()->toISOString()
    ]);
});
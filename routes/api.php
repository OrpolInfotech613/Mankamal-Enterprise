<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\DepartmentAuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProcessingStepController;
use App\Http\Controllers\Api\APIOrderController;

Route::post('login', [DepartmentAuthController::class, 'login']);

// Protected department API routes
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/profile', [DepartmentAuthController::class, 'profile']);
    Route::post('/logout', [DepartmentAuthController::class, 'logout']);

    // Orders routes with department scope
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
    Route::get('/get-processing-steps', [APIOrderController::class, 'getProcessingSteps']);
});

// Health check route (public)
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'API is running',
        'timestamp' => now()->toISOString()
    ]);
});

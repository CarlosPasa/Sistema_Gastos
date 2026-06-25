<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExpenseController;

Route::post('/login', [AuthController::class, 'login'])
    ->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('api.logout');

    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    })->name('api.user');

    Route::apiResource('categories', CategoryController::class)
        ->names('api.categories');

    Route::apiResource('expenses', ExpenseController::class)
        ->names('api.expenses');

    Route::apiResource('budgets', BudgetController::class)
        ->names('api.budgets');
});

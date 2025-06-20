<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\BudgetController;
use App\Http\Controllers\User\IncomeCategoryController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Middleware\JwtCookieAuth;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::Class,'login']);
    Route::post('signup', [AuthController::Class,'signup']);
});

Route::middleware(JwtCookieAuth::class)->group(function() {

    Route::prefix('auth')->group(function () {
        Route::post('refresh-token', [AuthController::Class,'refresh']);
        Route::get('me', [AuthController::Class,'me']);
        Route::post('logout', [AuthController::Class,'logout']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::Class,'index']);
        Route::get('/dashboard', [TransactionController::Class,'dashboard']);
        Route::post('/{incomeCategory}/credit', [TransactionController::Class,'createCredit']);
        Route::post('/{budget}/debit', [TransactionController::Class,'createDebit']);
        Route::get('/{transaction}', [TransactionController::Class,'view']);
        Route::delete('/{transaction}', [TransactionController::Class,'delete']);
    });

    Route::prefix('budgets')->group(function () {
        Route::get('/', [BudgetController::Class,'index']);
        Route::get('/{budget}', [BudgetController::Class,'view']);
        Route::patch('/{budget}', [BudgetController::Class,'update']);
        Route::post('/', [BudgetController::Class,'create']);
    });

    Route::prefix('income-categories')->group(function () {
        Route::get('/', [IncomeCategoryController::Class,'index']);
        Route::post('/', [IncomeCategoryController::Class,'create']);
        Route::patch('/{incomeCategory}', [IncomeCategoryController::Class,'update']);
    });

});

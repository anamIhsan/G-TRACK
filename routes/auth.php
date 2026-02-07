<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

$prefix = 'auth';

Route::prefix($prefix)->name('auth.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login-proccess', [AuthController::class, 'login'])->name('login-proccess');
    });
    Route::get('logout', [AuthController::class, 'logout'])->middleware(['auth'])->name('logout');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginUser'])->name('login');
        Route::post('login-proccess', [AuthController::class, 'loginUser'])->name('login-proccess');
    });
});

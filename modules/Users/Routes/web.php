<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UserController;

Route::middleware(['web', 'auth'])
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index')->middleware('can:view_users');
        Route::get('/create', [UserController::class, 'create'])->name('create')->middleware('can:create_users');
        Route::post('/', [UserController::class, 'store'])->name('store')->middleware('can:create_users');
        Route::get('/{user}', [UserController::class, 'show'])->name('show')->middleware('can:view_users');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit')->middleware('can:edit_users');
        Route::put('/{user}', [UserController::class, 'update'])->name('update')->middleware('can:edit_users');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->middleware('can:delete_users');
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status')->middleware('can:edit_users');
        Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password')->middleware('can:edit_users');
    });

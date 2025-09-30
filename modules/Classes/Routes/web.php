<?php

use Illuminate\Support\Facades\Route;
use Modules\Classes\Http\Controllers\ClassController;

Route::middleware(['web', 'auth'])
    ->prefix('classes')
    ->name('classes.')
    ->group(function () {
        Route::get('/', [ClassController::class, 'index'])
            ->name('index')
            ->middleware('can:view_classes');

        Route::get('/create', [ClassController::class, 'create'])
            ->name('create')
            ->middleware('can:create_classes');

        Route::post('/', [ClassController::class, 'store'])
            ->name('store')
            ->middleware('can:create_classes');

        Route::get('/{class}', [ClassController::class, 'show'])
            ->name('show')
            ->middleware('can:view_classes');

        Route::get('/{class}/edit', [ClassController::class, 'edit'])
            ->name('edit')
            ->middleware('can:edit_classes');

        Route::put('/{class}', [ClassController::class, 'update'])
            ->name('update')
            ->middleware('can:edit_classes');

        Route::delete('/{class}', [ClassController::class, 'destroy'])
            ->name('destroy')
            ->middleware('can:delete_classes');
    });

<?php

use Illuminate\Support\Facades\Route;
use Modules\Subjects\Http\Controllers\SubjectController;

Route::middleware(['web', 'auth'])
    ->prefix('subjects')
    ->name('subjects.')
    ->group(function () {
        Route::get('/', [SubjectController::class, 'index'])
            ->name('index')
            ->middleware('can:view_subjects');

        Route::get('/create', [SubjectController::class, 'create'])
            ->name('create')
            ->middleware('can:create_subjects');

        Route::post('/', [SubjectController::class, 'store'])
            ->name('store')
            ->middleware('can:create_subjects');

        Route::get('/{subject}', [SubjectController::class, 'show'])
            ->name('show')
            ->middleware('can:view_subjects');

        Route::get('/{subject}/edit', [SubjectController::class, 'edit'])
            ->name('edit')
            ->middleware('can:edit_subjects');

        Route::put('/{subject}', [SubjectController::class, 'update'])
            ->name('update')
            ->middleware('can:edit_subjects');

        Route::delete('/{subject}', [SubjectController::class, 'destroy'])
            ->name('destroy')
            ->middleware('can:delete_subjects');
    });

<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Modules\Teachers\Http\Controllers\TeacherController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::bind('teacher', function ($value) {
        return User::role('teacher')->with('teacherProfile')->findOrFail($value);
    });

    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])
            ->name('index')
            ->middleware('can:view_teachers');

        Route::get('/create', [TeacherController::class, 'create'])
            ->name('create')
            ->middleware('can:create_teachers');

        Route::post('/', [TeacherController::class, 'store'])
            ->name('store')
            ->middleware('can:create_teachers');

        Route::get('/schedules', [TeacherController::class, 'schedules'])
            ->name('schedules')
            ->middleware('can:view_teachers');

        Route::get('/{teacher}', [TeacherController::class, 'show'])
            ->name('show')
            ->middleware('can:view_teachers');

        Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])
            ->name('edit')
            ->middleware('can:edit_teachers');

        Route::put('/{teacher}', [TeacherController::class, 'update'])
            ->name('update')
            ->middleware('can:edit_teachers');

        Route::delete('/{teacher}', [TeacherController::class, 'destroy'])
            ->name('destroy')
            ->middleware('can:delete_teachers');
    });
});

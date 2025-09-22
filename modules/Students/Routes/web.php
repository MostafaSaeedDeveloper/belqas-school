<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\StudentController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::bind('student', function ($value) {
        return User::whereKey($value)
            ->whereHas('roles', fn ($query) => $query->where('name', 'student'))
            ->firstOrFail();
    });

    Route::prefix('students')
        ->name('students.')
        ->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index')->middleware('can:view_students');
            Route::get('/create', [StudentController::class, 'create'])->name('create')->middleware('can:create_students');
            Route::post('/', [StudentController::class, 'store'])->name('store')->middleware('can:create_students');
            Route::get('/reports', [StudentController::class, 'reports'])->name('reports')->middleware('can:view_students');
            Route::get('/{student}', [StudentController::class, 'show'])->name('show')->middleware('can:view_students')->whereNumber('student');
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit')->middleware('can:edit_students')->whereNumber('student');
            Route::put('/{student}', [StudentController::class, 'update'])->name('update')->middleware('can:edit_students')->whereNumber('student');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy')->middleware('can:delete_students')->whereNumber('student');
            Route::patch('/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('toggle-status')->middleware('can:edit_students')->whereNumber('student');
            Route::post('/{student}/reset-password', [StudentController::class, 'resetPassword'])->name('reset-password')->middleware('can:edit_students')->whereNumber('student');
        });
});

<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', StudentController::class)->except(['create', 'edit']);
    Route::resource('teachers', TeacherController::class)->except(['create', 'edit']);
    Route::resource('classrooms', ClassroomController::class)->except(['create', 'edit']);
    Route::resource('subjects', SubjectController::class)->except(['create', 'edit']);
    Route::resource('enrollments', EnrollmentController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('assessments', AssessmentController::class)->except(['create', 'edit']);
    Route::resource('grades', GradeController::class)->except(['create', 'edit']);
    Route::resource('attendance', AttendanceController::class)->except(['create', 'edit']);
});

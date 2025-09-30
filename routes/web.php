<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolClasses\ClassController as SchoolClassController;
use App\Http\Controllers\Students\StudentController;
use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\Teachers\TeacherController;
use App\Http\Controllers\Users\UserController;

// Authentication Routes
Auth::routes();

// Root redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Routes (Protected)
Route::middleware(['auth'])->group(function () {

    // Main Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Teachers Management
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('index')->middleware('can:view_teachers');
        Route::get('/create', [TeacherController::class, 'create'])->name('create')->middleware('can:create_teachers');
        Route::post('/', [TeacherController::class, 'store'])->name('store')->middleware('can:create_teachers');
        Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show')->middleware('can:view_teachers');
        Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit')->middleware('can:edit_teachers');
        Route::put('/{teacher}', [TeacherController::class, 'update'])->name('update')->middleware('can:edit_teachers');
        Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy')->middleware('can:delete_teachers');
    });

    // Students Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index')->middleware('can:view_students');
        Route::get('/create', [StudentController::class, 'create'])->name('create')->middleware('can:create_students');
        Route::post('/', [StudentController::class, 'store'])->name('store')->middleware('can:create_students');
        Route::get('/reports', [StudentController::class, 'reports'])->name('reports')->middleware('can:view_students');
        Route::get('/{student}', [StudentController::class, 'show'])->name('show')->middleware('can:view_students');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit')->middleware('can:edit_students');
        Route::put('/{student}', [StudentController::class, 'update'])->name('update')->middleware('can:edit_students');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy')->middleware('can:delete_students');
    });

    // Classes Management
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/', [SchoolClassController::class, 'index'])->name('index')->middleware('can:view_classes');
        Route::get('/create', [SchoolClassController::class, 'create'])->name('create')->middleware('can:create_classes');
        Route::post('/', [SchoolClassController::class, 'store'])->name('store')->middleware('can:create_classes');
        Route::get('/{class}', [SchoolClassController::class, 'show'])->name('show')->middleware('can:view_classes');
        Route::get('/{class}/edit', [SchoolClassController::class, 'edit'])->name('edit')->middleware('can:edit_classes');
        Route::put('/{class}', [SchoolClassController::class, 'update'])->name('update')->middleware('can:edit_classes');
        Route::delete('/{class}', [SchoolClassController::class, 'destroy'])->name('destroy')->middleware('can:delete_classes');
    });

    // Subjects Management
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index')->middleware('can:view_subjects');
        Route::get('/create', [SubjectController::class, 'create'])->name('create')->middleware('can:create_subjects');
        Route::post('/', [SubjectController::class, 'store'])->name('store')->middleware('can:create_subjects');
        Route::get('/{subject}', [SubjectController::class, 'show'])->name('show')->middleware('can:view_subjects');
        Route::get('/{subject}/edit', [SubjectController::class, 'edit'])->name('edit')->middleware('can:edit_subjects');
        Route::put('/{subject}', [SubjectController::class, 'update'])->name('update')->middleware('can:edit_subjects');
        Route::delete('/{subject}', [SubjectController::class, 'destroy'])->name('destroy')->middleware('can:delete_subjects');
    });

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
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

    // Attendance Routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/daily', function() { return view('attendance.daily'); })->name('daily')->middleware('can:view_attendance');
        Route::get('/reports', function() { return view('attendance.reports'); })->name('reports')->middleware('can:attendance_reports');
        Route::get('/statistics', function() { return view('attendance.statistics'); })->name('statistics')->middleware('can:attendance_reports');
    });

    // Exams Routes
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', function() { return view('exams.index'); })->name('index')->middleware('can:view_exams');
        Route::get('/create', function() { return view('exams.create'); })->name('create')->middleware('can:create_exams');
        Route::get('/{exam}', function() { return view('exams.show'); })->name('show')->middleware('can:view_exams');
        Route::get('/{exam}/edit', function() { return view('exams.edit'); })->name('edit')->middleware('can:edit_exams');
    });

    // Grades Routes
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/input', function() { return view('grades.input'); })->name('input')->middleware('can:create_grades');
        Route::get('/reports', function() { return view('grades.reports'); })->name('reports')->middleware('can:grades_reports');
    });

    // Finance Routes
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', function() { return view('finance.index'); })->name('index')->middleware('can:view_fees');
        Route::get('/fees', function() { return view('finance.fees'); })->name('fees')->middleware('can:view_fees');
        Route::get('/payments', function() { return view('finance.payments'); })->name('payments')->middleware('can:view_payments');
        Route::get('/reports', function() { return view('finance.reports'); })->name('reports')->middleware('can:financial_reports');
    });

    // Library Routes
    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/', function() { return view('library.index'); })->name('index')->middleware('can:view_library');
        Route::get('/books', function() { return view('library.books'); })->name('books')->middleware('can:view_library');
        Route::get('/issue', function() { return view('library.issue'); })->name('issue')->middleware('can:issue_books');
    });

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function() { return view('reports.index'); })->name('index')->middleware('can:view_reports');
        Route::get('/academic', function() { return view('reports.academic'); })->name('academic')->middleware('can:view_reports');
        Route::get('/attendance', function() { return view('reports.attendance'); })->name('attendance')->middleware('can:attendance_reports');
        Route::get('/financial', function() { return view('reports.financial'); })->name('financial')->middleware('can:financial_reports');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', function() { return view('settings.general'); })->name('general')->middleware('can:view_settings');
        Route::get('/users', function() {
            return redirect()->route('users.index');
        })->name('users')->middleware('can:view_users');
        Route::get('/permissions', function() { return view('settings.permissions'); })->name('permissions')->middleware('can:view_users');
        Route::get('/backup', function() { return view('settings.backup'); })->name('backup')->middleware('can:system_backup');
    });

    // Events Routes
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', function() { return view('events.index'); })->name('index')->middleware('can:view_notifications');
    });

    // Activity Log Routes
    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/', function() { return view('activity.index'); })->name('index')->middleware('can:view_reports');
    });

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function() { return view('profile.show'); })->name('show');
        Route::get('/edit', function() { return view('profile.edit'); })->name('edit');
    });

});

// API Routes for AJAX calls
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {

    // Dashboard Stats
    Route::get('/dashboard/stats', function() {
        return response()->json([
            'students' => 1247,
            'teachers' => 85,
            'classes' => 24,
            'attendance' => 94
        ]);
    })->name('dashboard.stats');

    // Search API
    Route::get('/search', function() {
        $query = request('q');
        // Mock search results
        return response()->json([
            [
                'type' => 'student',
                'name' => 'أحمد محمد علي',
                'id' => '12345'
            ],
            [
                'type' => 'teacher',
                'name' => 'فاطمة أحمد',
                'id' => '67890'
            ]
        ]);
    })->name('search');

    // Notifications API
    Route::get('/notifications', function() {
        return response()->json([
            [
                'id' => 1,
                'title' => 'طالب جديد',
                'message' => 'تم تسجيل طالب جديد',
                'time' => '5 دقائق',
                'type' => 'info',
                'read' => false
            ]
        ]);
    })->name('notifications');

});

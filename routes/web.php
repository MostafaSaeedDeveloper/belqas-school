<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

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

    // Students Routes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', function() { return view('students.index'); })->name('index')->middleware('can:view_students');
        Route::get('/create', function() { return view('students.create'); })->name('create')->middleware('can:create_students');
        Route::get('/{student}', function() { return view('students.show'); })->name('show')->middleware('can:view_students');
        Route::get('/{student}/edit', function() { return view('students.edit'); })->name('edit')->middleware('can:edit_students');
        Route::get('/reports', function() { return view('students.reports'); })->name('reports')->middleware('can:view_students');
    });

    // Teachers Routes
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', function() { return view('teachers.index'); })->name('index')->middleware('can:view_teachers');
        Route::get('/create', function() { return view('teachers.create'); })->name('create')->middleware('can:create_teachers');
        Route::get('/{teacher}', function() { return view('teachers.show'); })->name('show')->middleware('can:view_teachers');
        Route::get('/{teacher}/edit', function() { return view('teachers.edit'); })->name('edit')->middleware('can:edit_teachers');
        Route::get('/schedules', function() { return view('teachers.schedules'); })->name('schedules')->middleware('can:view_teachers');
    });

    // Classes Routes
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/', function() { return view('classes.index'); })->name('index')->middleware('can:view_classes');
        Route::get('/create', function() { return view('classes.create'); })->name('create')->middleware('can:create_classes');
        Route::get('/{class}', function() { return view('classes.show'); })->name('show')->middleware('can:view_classes');
        Route::get('/{class}/edit', function() { return view('classes.edit'); })->name('edit')->middleware('can:edit_classes');
        Route::get('/timetables', function() { return view('classes.timetables'); })->name('timetables')->middleware('can:view_timetable');
    });

    // Subjects Routes
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', function() { return view('subjects.index'); })->name('index')->middleware('can:view_subjects');
        Route::get('/create', function() { return view('subjects.create'); })->name('create')->middleware('can:create_subjects');
        Route::get('/{subject}', function() { return view('subjects.show'); })->name('show')->middleware('can:view_subjects');
        Route::get('/{subject}/edit', function() { return view('subjects.edit'); })->name('edit')->middleware('can:edit_subjects');
        Route::get('/assignments', function() { return view('subjects.assignments'); })->name('assignments')->middleware('can:view_subjects');
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

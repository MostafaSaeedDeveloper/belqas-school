<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SubjectController;

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

    // Classes Routes
    Route::get('classes/timetables', [ClassroomController::class, 'timetables'])->name('classes.timetables');
    Route::resource('classes', ClassroomController::class)->parameters([
        'classes' => 'classroom',
    ]);

    // Subjects Routes
    Route::get('subjects/assignments', [SubjectController::class, 'assignments'])->name('subjects.assignments');
    Route::post('subjects/assignments', [SubjectController::class, 'storeAssignment'])->name('subjects.assignments.store');
    Route::resource('subjects', SubjectController::class);

    // Attendance Routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/daily', [AttendanceController::class, 'daily'])->name('daily');
        Route::post('/daily', [AttendanceController::class, 'storeDaily'])->name('daily.store');
        Route::get('/reports', [AttendanceController::class, 'reports'])->name('reports');
        Route::get('/statistics', [AttendanceController::class, 'statistics'])->name('statistics');
    });

    // Exams Routes
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::get('/create', [ExamController::class, 'create'])->name('create');
        Route::post('/', [ExamController::class, 'store'])->name('store');
        Route::get('/{exam}', [ExamController::class, 'show'])->name('show');
        Route::get('/{exam}/edit', [ExamController::class, 'edit'])->name('edit');
        Route::put('/{exam}', [ExamController::class, 'update'])->name('update');
        Route::delete('/{exam}', [ExamController::class, 'destroy'])->name('destroy');
    });

    // Grades Routes
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/input', [GradeController::class, 'input'])->name('input');
        Route::post('/input', [GradeController::class, 'store'])->name('input.store');
        Route::get('/reports', [GradeController::class, 'reports'])->name('reports');
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
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/academic', [ReportController::class, 'academic'])->name('academic');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [SettingController::class, 'general'])->name('general');
        Route::post('/general', [SettingController::class, 'updateGeneral'])->name('general.update');
        Route::get('/users', function() {
            return redirect()->route('users.index');
        })->name('users')->middleware('can:view_users');
        Route::get('/permissions', [SettingController::class, 'permissions'])->name('permissions');
        Route::post('/permissions/{role}', [SettingController::class, 'updateRolePermissions'])->name('permissions.update');
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
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
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

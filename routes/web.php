<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Support\DashboardMetrics;
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
        $metrics = DashboardMetrics::build();

        return response()->json([
            'stats' => $metrics['stats'],
            'attendanceSummary' => $metrics['attendanceSummary'],
            'studentTrend' => $metrics['studentTrend'],
            'attendanceTrend' => $metrics['attendanceTrend']->values()->toArray(),
            'studentGrowth' => $metrics['studentGrowth']->values()->toArray(),
        ]);
    })->name('dashboard.stats');

    // Search API
    Route::get('/search', function() {
        $query = trim(request('q', ''));

        if ($query === '') {
            return response()->json([]);
        }

        $canViewUsers = auth()->user()?->can('view_users') ?? false;
        $roleLabels = [
            'student' => 'طالب',
            'teacher' => 'معلم',
            'parent' => 'ولي أمر',
            'admin' => 'مشرف',
            'super-admin' => 'مدير النظام',
        ];

        $users = User::query()
            ->with('roles')
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $results = $users->map(function ($user) use ($roleLabels, $canViewUsers) {
            $roleKey = $user->getRoleNames()->first();
            $roleLabel = $roleLabels[$roleKey] ?? ($roleKey ? ucfirst($roleKey) : 'مستخدم');

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $roleLabel,
                'role_key' => $roleKey,
                'url' => $canViewUsers ? route('users.show', $user) : null,
            ];
        });

        return response()->json($results);
    })->name('search');

    // Notifications API
    Route::get('/notifications', function() {
        $metrics = DashboardMetrics::build();
        $notifications = [];

        $attendanceSummary = $metrics['attendanceSummary'];
        if (! is_null($attendanceSummary['percentage'])) {
            $notifications[] = [
                'id' => 'attendance-daily',
                'title' => 'متابعة الحضور',
                'message' => 'تم تسجيل ' . number_format($attendanceSummary['total_marked']) . ' سجل حضور اليوم.',
                'time' => now()->diffForHumans(),
                'type' => $attendanceSummary['percentage'] >= 90 ? 'success' : 'warning',
                'read' => false,
            ];
        }

        $nextExam = $metrics['upcomingExams']->first();
        if ($nextExam) {
            $notifications[] = [
                'id' => 'exam-' . $nextExam->id,
                'title' => 'امتحان قريب',
                'message' => sprintf(
                    'امتحان %s يوم %s.',
                    $nextExam->subject?->name ?? 'بدون مادة',
                    optional($nextExam->exam_date)->translatedFormat('l j F')
                ),
                'time' => optional($nextExam->exam_date)->diffForHumans(),
                'type' => 'info',
                'read' => false,
            ];
        }

        $newUser = $metrics['recentUsers']->first();
        if ($newUser) {
            $role = $newUser->getRoleNames()->first() ?? 'مستخدم';
            $notifications[] = [
                'id' => 'user-' . $newUser->id,
                'title' => 'انضمام مستخدم جديد',
                'message' => $newUser->name . ' انضم بدور ' . $role . '.',
                'time' => $newUser->created_at->diffForHumans(),
                'type' => 'info',
                'read' => false,
            ];
        }

        return response()->json($notifications);
    })->name('notifications');

});

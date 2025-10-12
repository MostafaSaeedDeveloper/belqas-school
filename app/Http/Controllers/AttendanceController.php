<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Classroom;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view_attendance')->only(['daily']);
        $this->middleware('can:attendance_reports')->only(['reports', 'statistics']);
        $this->middleware('can:create_attendance')->only(['storeDaily']);
    }

    /**
     * Display the daily attendance dashboard.
     */
    public function daily(Request $request): View
    {
        $user = $request->user();
        $selectedDate = Carbon::parse($request->input('date', now()->toDateString()))->toDateString();

        $classrooms = $this->availableClassrooms($user);
        $selectedClassroomId = $request->integer('classroom_id');
        $selectedClassroom = $classrooms->firstWhere('id', $selectedClassroomId);

        $students = collect();
        $records = collect();

        if ($selectedClassroom) {
            $students = $selectedClassroom->students()->with('studentProfile')->orderBy('name')->get();
            $records = AttendanceRecord::query()
                ->whereDate('attendance_date', $selectedDate)
                ->where('classroom_id', $selectedClassroom->id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }

        return view('attendance.daily', [
            'classrooms' => $classrooms,
            'selectedClassroom' => $selectedClassroom,
            'selectedDate' => $selectedDate,
            'students' => $students,
            'records' => $records,
            'statuses' => [
                'present' => 'حاضر',
                'absent' => 'غائب',
                'late' => 'متأخر',
                'excused' => 'مُبرر',
            ],
        ]);
    }

    /**
     * Store the submitted daily attendance.
     */
    public function storeDaily(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.notes' => 'nullable|string|max:500',
        ]);

        /** @var User $user */
        $user = $request->user();
        $classroom = Classroom::findOrFail($validated['classroom_id']);
        $this->authorizeClassroom($user, $classroom);

        $studentIds = collect($validated['attendance'])->keys()->map(fn($id) => (int) $id);
        $students = $classroom->students()->whereIn('users.id', $studentIds)->pluck('users.id');

        DB::transaction(function () use ($validated, $user, $students, $classroom) {
            foreach ($students as $studentId) {
                $payload = $validated['attendance'][$studentId];

                AttendanceRecord::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'attendance_date' => $validated['date'],
                    ],
                    [
                        'classroom_id' => $classroom->id,
                        'recorded_by' => $user->id,
                        'status' => $payload['status'],
                        'notes' => $payload['notes'] ?: null,
                    ]
                );
            }
        });

        return redirect()
            ->route('attendance.daily', [
                'classroom_id' => $classroom->id,
                'date' => $validated['date'],
            ])
            ->with('success', 'تم حفظ سجلات الحضور بنجاح.');
    }

    /**
     * Attendance reports page.
     */
    public function reports(Request $request): View
    {
        $user = $request->user();
        $classrooms = $this->availableClassrooms($user);

        $startDate = Carbon::parse($request->input('start_date', now()->startOfMonth()->toDateString()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', now()->endOfMonth()->toDateString()))->endOfDay();

        $selectedClassroomId = $request->integer('classroom_id');
        $selectedClassroom = $classrooms->firstWhere('id', $selectedClassroomId);

        $query = AttendanceRecord::query()
            ->with(['student', 'classroom'])
            ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()]);

        if ($selectedClassroom) {
            $query->where('classroom_id', $selectedClassroom->id);
        } elseif ($classrooms->isNotEmpty()) {
            $query->whereIn('classroom_id', $classrooms->pluck('id'));
        }

        $summaryByStatus = (clone $query)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $summaryByClassroom = (clone $query)
            ->select('classroom_id', DB::raw('count(*) as total'))
            ->groupBy('classroom_id')
            ->with('classroom')
            ->get()
            ->mapWithKeys(fn($row) => [optional($row->classroom)->name ?? 'غير محدد' => $row->total]);

        $detailedRecords = $query->latest('attendance_date')->limit(50)->get();

        $attendanceRate = $this->calculateAttendanceRate($summaryByStatus);

        return view('attendance.reports', [
            'classrooms' => $classrooms,
            'selectedClassroom' => $selectedClassroom,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'summaryByStatus' => $summaryByStatus,
            'summaryByClassroom' => $summaryByClassroom,
            'detailedRecords' => $detailedRecords,
            'attendanceRate' => $attendanceRate,
        ]);
    }

    /**
     * Attendance statistics and analytics.
     */
    public function statistics(Request $request): View
    {
        $months = CarbonPeriod::create(now()->startOfMonth()->subMonths(5), '1 month', now()->startOfMonth());

        $monthlyData = collect();
        foreach ($months as $month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $records = AttendanceRecord::query()
                ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
                ->get();

            $total = $records->count();
            $present = $records->where('status', 'present')->count();
            $rate = $total > 0 ? round(($present / $total) * 100, 2) : null;

            $monthlyData->push([
                'label' => $month->translatedFormat('F Y'),
                'total' => $total,
                'present' => $present,
                'rate' => $rate,
            ]);
        }

        $topAbsentees = AttendanceRecord::query()
            ->select('student_id', DB::raw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absences"))
            ->groupBy('student_id')
            ->orderByDesc('absences')
            ->with('student')
            ->limit(5)
            ->get();

        $recentRecords = AttendanceRecord::query()
            ->with(['student', 'classroom', 'recorder'])
            ->latest()
            ->limit(10)
            ->get();

        return view('attendance.statistics', [
            'monthlyData' => $monthlyData,
            'topAbsentees' => $topAbsentees,
            'recentRecords' => $recentRecords,
        ]);
    }

    /**
     * Ensure the authenticated user can manage the classroom.
     */
    protected function authorizeClassroom(User $user, Classroom $classroom): void
    {
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return;
        }

        $isTeacherOfClass = $classroom->teachers()->where('users.id', $user->id)->exists();
        $isHomeroomTeacher = $classroom->homeroom_teacher_id === $user->id;

        abort_unless($isTeacherOfClass || $isHomeroomTeacher, 403, 'غير مصرح لك بإدارة هذا الفصل.');
    }

    /**
     * Get the classrooms available for the current user.
     */
    protected function availableClassrooms(User $user): Collection
    {
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return Classroom::query()->orderBy('name')->get();
        }

        if ($user->can('create_attendance')) {
            return Classroom::query()
                ->where(function ($query) use ($user) {
                    $query->where('homeroom_teacher_id', $user->id)
                        ->orWhereHas('teachers', function ($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                })
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    /**
     * Calculate overall attendance rate.
     */
    protected function calculateAttendanceRate(Collection $summaryByStatus): float
    {
        $total = $summaryByStatus->sum();
        $present = $summaryByStatus->get('present', 0);

        if ($total === 0) {
            return 0.0;
        }

        return round(($present / $total) * 100, 2);
    }
}

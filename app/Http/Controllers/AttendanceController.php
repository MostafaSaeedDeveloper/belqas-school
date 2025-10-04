<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Classroom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display the daily attendance management screen.
     */
    public function daily(Request $request): View
    {
        $selectedDate = $this->resolveDate($request->query('date'));
        $classroomId = $request->query('classroom_id');
        $user = $request->user();

        $classrooms = $this->classroomsAccessibleBy($user);
        $accessibleClassroomIds = $classrooms->pluck('id')->map(fn ($id) => (int) $id);

        $classroom = null;
        $session = null;
        $students = collect();
        $records = collect();
        $statusCounts = $this->emptyStatusCounts();
        $lastUpdated = null;

        if ($classroomId) {
            if (! $accessibleClassroomIds->contains((int) $classroomId)) {
                abort(403, 'غير مصرح لك بإدارة حضور هذا الفصل.');
            }

            $classroom = Classroom::with([
                'students' => function ($query) {
                    $query->orderBy('name');
                },
                'teachers',
            ])->find($classroomId);

            if ($classroom) {
                $students = $classroom->students;

                $session = AttendanceSession::with('records')
                    ->where('classroom_id', $classroomId)
                    ->whereDate('date', $selectedDate)
                    ->first();

                if ($session) {
                    $records = $session->records->keyBy('student_id');
                    $statusCounts = $this->calculateStatusCounts($session->records);
                    $lastUpdated = $session->updated_at;
                }
            }
        }

        return view('attendance.daily', [
            'classrooms' => $classrooms,
            'classroom' => $classroom,
            'students' => $students,
            'session' => $session,
            'selectedDate' => $selectedDate,
            'records' => $records,
            'statuses' => AttendanceRecord::statuses(),
            'statusCounts' => $statusCounts,
            'lastUpdated' => $lastUpdated,
        ]);
    }

    /**
     * Retrieve classrooms the authenticated user can manage attendance for.
     */
    protected function classroomsAccessibleBy(?User $user): Collection
    {
        if (! $user) {
            return collect();
        }

        $query = Classroom::query()
            ->orderBy('grade_level')
            ->orderBy('name');

        if ($user->hasRole(['super_admin', 'admin'])) {
            return $query->get();
        }

        if ($user->hasRole('teacher')) {
            return $query
                ->where(function (Builder $builder) use ($user) {
                    $builder->where('homeroom_teacher_id', $user->id)
                        ->orWhereHas('teachers', function (Builder $relation) use ($user) {
                            $relation->where('teacher_id', $user->id);
                        });
                })
                ->get();
        }

        return collect();
    }

    /**
     * Determine if the given user can manage attendance for the classroom.
     */
    protected function userCanManageClassroom(?User $user, Classroom $classroom): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        if ($user->hasRole('teacher')) {
            $classroom->loadMissing('teachers');

            if ((int) $classroom->homeroom_teacher_id === $user->id) {
                return true;
            }

            return $classroom->teachers->contains('id', $user->id);
        }

        return false;
    }

    /**
     * Store the daily attendance for a classroom.
     */
    public function storeDaily(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'records' => ['required', 'array'],
            'records.*.status' => ['required', 'in:' . implode(',', array_keys(AttendanceRecord::statuses()))],
            'records.*.remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $classroom = Classroom::with(['students', 'teachers'])->findOrFail($data['classroom_id']);

        abort_unless(
            $this->userCanManageClassroom($request->user(), $classroom),
            403,
            'غير مصرح لك بتسجيل حضور هذا الفصل.'
        );
        $studentIds = $classroom->students->pluck('id')->map(fn ($id) => (int) $id)->all();

        $session = AttendanceSession::firstOrCreate([
            'classroom_id' => $classroom->id,
            'date' => Carbon::parse($data['date'])->toDateString(),
        ], [
            'recorded_by' => $request->user()?->id,
        ]);

        $session->fill([
            'notes' => $data['notes'] ?? null,
            'status' => 'completed',
        ])->save();

        foreach ($data['records'] as $studentId => $recordData) {
            $studentId = (int) $studentId;

            if (! in_array($studentId, $studentIds, true)) {
                continue;
            }

            AttendanceRecord::updateOrCreate([
                'attendance_session_id' => $session->id,
                'student_id' => $studentId,
            ], [
                'status' => $recordData['status'],
                'remarks' => Arr::get($recordData, 'remarks'),
                'recorded_at' => now(),
            ]);
        }

        return redirect()
            ->route('attendance.daily', [
                'classroom_id' => $classroom->id,
                'date' => Carbon::parse($data['date'])->toDateString(),
            ])
            ->with('success', 'تم حفظ سجل الحضور بنجاح.');
    }

    /**
     * Display attendance reports with filtering options.
     */
    public function reports(Request $request): View
    {
        $filters = $request->validate([
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'status' => ['nullable', 'in:' . implode(',', array_keys(AttendanceRecord::statuses()))],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $records = $this->buildReportsQuery($filters)->paginate(25)->withQueryString();
        $summary = $this->buildSummary($filters);

        return view('attendance.reports', [
            'records' => $records,
            'summary' => $summary,
            'classrooms' => Classroom::orderBy('grade_level')->orderBy('name')->get(),
            'filters' => array_merge([
                'classroom_id' => null,
                'status' => null,
                'date_from' => null,
                'date_to' => null,
            ], $filters),
            'statuses' => AttendanceRecord::statuses(),
        ]);
    }

    /**
     * Display attendance statistics for a specific month.
     */
    public function statistics(Request $request): View
    {
        $monthInput = $request->query('month');

        try {
            $selectedMonth = $monthInput ? Carbon::createFromFormat('Y-m', $monthInput) : Carbon::now();
        } catch (\Throwable $e) {
            $selectedMonth = Carbon::now();
        }

        $startDate = $selectedMonth->copy()->startOfMonth();
        $endDate = $selectedMonth->copy()->endOfMonth();

        $statusTotals = $this->statusTotalsBetween($startDate, $endDate);
        $totalRecords = array_sum($statusTotals);
        $attendanceRate = $totalRecords > 0
            ? round(($statusTotals[AttendanceRecord::STATUS_PRESENT] ?? 0) / $totalRecords * 100, 1)
            : null;

        $dailyTrends = $this->dailyTrendsBetween($startDate, $endDate);
        $topAbsentees = $this->topAbsenteesBetween($startDate, $endDate);
        $classroomPerformance = $this->classroomPerformanceBetween($startDate, $endDate);

        return view('attendance.statistics', [
            'selectedMonth' => $selectedMonth,
            'statusTotals' => $statusTotals,
            'attendanceRate' => $attendanceRate,
            'totalSessions' => AttendanceSession::whereBetween('date', [$startDate, $endDate])->count(),
            'uniqueStudents' => $this->uniqueStudentsBetween($startDate, $endDate),
            'dailyTrends' => $dailyTrends,
            'topAbsentees' => $topAbsentees,
            'classroomPerformance' => $classroomPerformance,
        ]);
    }

    /**
     * Build the base query for attendance reports.
     */
    protected function buildReportsQuery(array $filters): Builder
    {
        return AttendanceRecord::query()
            ->select('attendance_records.*')
            ->with(['student', 'session.classroom', 'session.recordedBy'])
            ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
            ->when($filters['classroom_id'] ?? null, function (Builder $query, $classroomId) {
                $query->where('attendance_sessions.classroom_id', $classroomId);
            })
            ->when($filters['status'] ?? null, function (Builder $query, $status) {
                $query->where('attendance_records.status', $status);
            })
            ->when($filters['date_from'] ?? null, function (Builder $query, $from) {
                $query->whereDate('attendance_sessions.date', '>=', $from);
            })
            ->when($filters['date_to'] ?? null, function (Builder $query, $to) {
                $query->whereDate('attendance_sessions.date', '<=', $to);
            })
            ->orderByDesc('attendance_sessions.date')
            ->orderBy('student_id');
    }

    /**
     * Build attendance summary counts.
     */
    protected function buildSummary(array $filters): array
    {
        $summary = AttendanceRecord::query()
            ->select('attendance_records.status', DB::raw('count(*) as total'))
            ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
            ->when($filters['classroom_id'] ?? null, function (Builder $query, $classroomId) {
                $query->where('attendance_sessions.classroom_id', $classroomId);
            })
            ->when($filters['status'] ?? null, function (Builder $query, $status) {
                $query->where('attendance_records.status', $status);
            })
            ->when($filters['date_from'] ?? null, function (Builder $query, $from) {
                $query->whereDate('attendance_sessions.date', '>=', $from);
            })
            ->when($filters['date_to'] ?? null, function (Builder $query, $to) {
                $query->whereDate('attendance_sessions.date', '<=', $to);
            })
            ->groupBy('attendance_records.status')
            ->pluck('total', 'attendance_records.status')
            ->all();

        return array_merge($this->emptyStatusCounts(), $summary);
    }

    /**
     * Calculate totals for each status between two dates.
     */
    protected function statusTotalsBetween(Carbon $startDate, Carbon $endDate): array
    {
        $totals = AttendanceRecord::query()
            ->select('attendance_records.status', DB::raw('count(*) as total'))
            ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
            ->whereBetween('attendance_sessions.date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('attendance_records.status')
            ->pluck('total', 'attendance_records.status')
            ->all();

        return array_merge($this->emptyStatusCounts(), $totals);
    }

    /**
     * Calculate daily attendance trends.
     */
    protected function dailyTrendsBetween(Carbon $startDate, Carbon $endDate): Collection
    {
        return AttendanceRecord::query()
            ->select([
                'attendance_sessions.date',
                DB::raw("sum(case when attendance_records.status = 'present' then 1 else 0 end) as present_total"),
                DB::raw('count(*) as total_records'),
            ])
            ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
            ->whereBetween('attendance_sessions.date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('attendance_sessions.date')
            ->orderBy('attendance_sessions.date')
            ->get()
            ->map(function ($row) {
                $rate = $row->total_records > 0
                    ? round(($row->present_total / $row->total_records) * 100, 1)
                    : 0;

                return [
                    'date' => Carbon::parse($row->date),
                    'rate' => $rate,
                    'present' => (int) $row->present_total,
                    'total' => (int) $row->total_records,
                ];
            });
    }

    /**
     * Determine the top students with absences in a date range.
     */
    protected function topAbsenteesBetween(Carbon $startDate, Carbon $endDate): Collection
    {
        $absentees = AttendanceRecord::query()
            ->selectRaw('student_id, count(*) as total_absences')
            ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
            ->where('attendance_records.status', AttendanceRecord::STATUS_ABSENT)
            ->whereBetween('attendance_sessions.date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('student_id')
            ->orderByDesc('total_absences')
            ->limit(5)
            ->get();

        $students = User::whereIn('id', $absentees->pluck('student_id'))
            ->get()
            ->keyBy('id');

        return $absentees->map(function ($row) use ($students) {
            return [
                'student' => $students->get($row->student_id),
                'total' => (int) $row->total_absences,
            ];
        });
    }

    /**
     * Calculate classroom performance for a date range.
     */
    protected function classroomPerformanceBetween(Carbon $startDate, Carbon $endDate): Collection
    {
        $performance = AttendanceRecord::query()
            ->selectRaw('attendance_sessions.classroom_id, sum(case when attendance_records.status = "present" then 1 else 0 end) as present_total, count(*) as total_records')
            ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
            ->whereBetween('attendance_sessions.date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('attendance_sessions.classroom_id')
            ->get();

        $classrooms = Classroom::whereIn('id', $performance->pluck('classroom_id'))
            ->get()
            ->keyBy('id');

        return $performance
            ->map(function ($row) use ($classrooms) {
                $rate = $row->total_records > 0
                    ? round(($row->present_total / $row->total_records) * 100, 1)
                    : 0;

                return [
                    'classroom' => $classrooms->get($row->classroom_id),
                    'rate' => $rate,
                    'present' => (int) $row->present_total,
                    'total' => (int) $row->total_records,
                ];
            })
            ->sortByDesc('rate')
            ->values()
            ->take(5);
    }

    /**
     * Count unique students with attendance records in date range.
     */
    protected function uniqueStudentsBetween(Carbon $startDate, Carbon $endDate): int
    {
        return AttendanceRecord::query()
            ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
            ->whereBetween('attendance_sessions.date', [$startDate->toDateString(), $endDate->toDateString()])
            ->distinct('attendance_records.student_id')
            ->count('attendance_records.student_id');
    }

    /**
     * Build an empty array with zeroed status counts.
     */
    protected function emptyStatusCounts(): array
    {
        return array_fill_keys(array_keys(AttendanceRecord::statuses()), 0);
    }

    /**
     * Calculate status counts for a collection of records.
     */
    protected function calculateStatusCounts(Collection $records): array
    {
        $counts = $this->emptyStatusCounts();

        foreach ($records as $record) {
            if (isset($counts[$record->status])) {
                $counts[$record->status]++;
            }
        }

        return $counts;
    }

    /**
     * Resolve date from query parameter.
     */
    protected function resolveDate(?string $date): string
    {
        if (! $date) {
            return Carbon::today()->toDateString();
        }

        try {
            return Carbon::parse($date)->toDateString();
        } catch (\Throwable $e) {
            return Carbon::today()->toDateString();
        }
    }
}

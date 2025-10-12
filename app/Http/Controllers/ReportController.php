<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\GradeRecord;
use App\Models\SchoolSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view_reports')->only(['index', 'academic']);
        $this->middleware('can:attendance_reports')->only(['attendance']);
        $this->middleware('can:financial_reports')->only(['financial']);
    }

    public function index(): View
    {
        $totalStudents = User::role('student')->count();
        $totalTeachers = User::role('teacher')->count();
        $attendanceRate = AttendanceRecord::query()
            ->select(DB::raw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) / COUNT(*) * 100 as rate"))
            ->value('rate');

        $averageGrade = GradeRecord::query()->avg(DB::raw('score / max_score * 100'));

        return view('reports.index', [
            'metrics' => [
                'students' => $totalStudents,
                'teachers' => $totalTeachers,
                'attendanceRate' => $attendanceRate ? round($attendanceRate, 2) : null,
                'averageGrade' => $averageGrade ? round($averageGrade, 2) : null,
            ],
        ]);
    }

    public function academic(Request $request): View
    {
        $gradeQuery = GradeRecord::query()->with(['student', 'subject', 'classroom']);

        if ($request->filled('classroom_id')) {
            $gradeQuery->where('classroom_id', $request->integer('classroom_id'));
        }

        if ($request->filled('subject_id')) {
            $gradeQuery->where('subject_id', $request->integer('subject_id'));
        }

        $averages = (clone $gradeQuery)
            ->select('subject_id', DB::raw('AVG(score / max_score * 100) as average'))
            ->groupBy('subject_id')
            ->with('subject')
            ->get();

        $topPerformers = (clone $gradeQuery)
            ->select('student_id', DB::raw('AVG(score / max_score * 100) as average'))
            ->groupBy('student_id')
            ->orderByDesc('average')
            ->with('student')
            ->limit(10)
            ->get();

        $atRiskStudents = (clone $gradeQuery)
            ->select('student_id', DB::raw('AVG(score / max_score * 100) as average'))
            ->groupBy('student_id')
            ->having('average', '<', 60)
            ->orderBy('average')
            ->with('student')
            ->limit(10)
            ->get();

        return view('reports.academic', [
            'averages' => $averages,
            'topPerformers' => $topPerformers,
            'atRiskStudents' => $atRiskStudents,
        ]);
    }

    public function attendance(Request $request): View
    {
        $startDate = Carbon::parse($request->input('start_date', now()->startOfMonth()->toDateString()));
        $endDate = Carbon::parse($request->input('end_date', now()->endOfMonth()->toDateString()));

        $query = AttendanceRecord::query()
            ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()]);

        $byStatus = (clone $query)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $byDay = (clone $query)
            ->select('attendance_date', DB::raw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present"), DB::raw('count(*) as total'))
            ->groupBy('attendance_date')
            ->orderBy('attendance_date')
            ->get()
            ->map(function ($row) {
                $rate = $row->total > 0 ? round(($row->present / $row->total) * 100, 2) : 0;
                return [
                    'date' => $row->attendance_date,
                    'rate' => $rate,
                ];
            });

        return view('reports.attendance', [
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'byStatus' => $byStatus,
            'byDay' => $byDay,
        ]);
    }

    public function financial(): View
    {
        $settings = [
            'tuition_target' => SchoolSetting::getValue('finance.tuition_target', 0),
            'tuition_collected' => SchoolSetting::getValue('finance.tuition_collected', 0),
            'expenses' => SchoolSetting::getValue('finance.expenses', 0),
        ];

        $performance = [
            'collectionRate' => $settings['tuition_target'] > 0
                ? round(($settings['tuition_collected'] / $settings['tuition_target']) * 100, 2)
                : 0,
            'netBalance' => $settings['tuition_collected'] - $settings['expenses'],
        ];

        $expenseBreakdown = collect(SchoolSetting::getValue('finance.expense_breakdown', []));

        return view('reports.financial', [
            'settings' => $settings,
            'performance' => $performance,
            'expenseBreakdown' => $expenseBreakdown,
        ]);
    }
}

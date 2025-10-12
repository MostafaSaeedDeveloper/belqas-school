<?php

namespace App\Support;

use App\Models\Announcement;
use App\Models\AttendanceRecord;
use App\Models\Classroom;
use App\Models\ExamSchedule;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardMetrics
{
    /**
     * Build the dashboard metrics dataset used by the UI and APIs.
     */
    public static function build(): array
    {
        $now = Carbon::now();
        $today = $now->copy()->startOfDay();
        $rangeStart = $now->copy()->subDays(6)->startOfDay();

        $stats = static::buildStats();

        $attendanceRecords = AttendanceRecord::whereBetween('attendance_date', [$rangeStart, $now->copy()->endOfDay()])->get();
        $todayAttendance = $attendanceRecords->filter(fn ($record) => $record->attendance_date->isSameDay($today));

        $attendanceSummary = static::buildAttendanceSummary($todayAttendance, $stats['students']);
        $studentGrowth = static::buildStudentGrowth($now);
        $attendanceTrend = static::buildAttendanceTrend($now, $attendanceRecords);

        $currentGrowth = $studentGrowth->last();
        $previousGrowth = $studentGrowth->slice(-2, 1)->first();

        $studentTrend = [
            'current' => $currentGrowth['count'] ?? 0,
            'previous' => $previousGrowth['count'] ?? 0,
        ];
        $studentTrend['change'] = $studentTrend['previous'] > 0
            ? round((($studentTrend['current'] - $studentTrend['previous']) / $studentTrend['previous']) * 100, 1)
            : null;

        $topClasses = Classroom::withCount('students')
            ->with('homeroomTeacher')
            ->orderByDesc('students_count')
            ->take(5)
            ->get();

        $upcomingExams = ExamSchedule::with(['subject', 'classroom'])
            ->whereDate('exam_date', '>=', $today)
            ->orderBy('exam_date')
            ->take(5)
            ->get();

        $recentUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        $announcements = Announcement::query()
            ->active()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        if ($announcements->isEmpty()) {
            $announcements = Announcement::query()
                ->orderByDesc('is_published')
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->take(6)
                ->get();
        }

        $announcementFeed = static::formatAnnouncements($announcements);

        $systemInsights = [
            'student_teacher_ratio' => $stats['teachers'] > 0 ? round($stats['students'] / $stats['teachers'], 1) : null,
            'students_per_class' => $stats['classes'] > 0 ? round($stats['students'] / $stats['classes'], 1) : null,
            'parents' => $stats['parents'],
        ];

        $stats['attendance'] = $attendanceSummary['percentage'];

        return [
            'stats' => $stats,
            'attendanceSummary' => $attendanceSummary,
            'studentGrowth' => $studentGrowth,
            'attendanceTrend' => $attendanceTrend,
            'studentTrend' => $studentTrend,
            'topClasses' => $topClasses,
            'upcomingExams' => $upcomingExams,
            'recentUsers' => $recentUsers,
            'systemInsights' => $systemInsights,
            'announcements' => $announcements,
            'announcementFeed' => $announcementFeed,
        ];
    }

    /**
     * Prepare the headline stats.
     */
    protected static function buildStats(): array
    {
        return [
            'students' => User::role('student')->count(),
            'teachers' => User::role('teacher')->count(),
            'parents' => User::role('parent')->count(),
            'classes' => Classroom::count(),
            'classrooms' => Classroom::count(),
            'subjects' => Subject::count(),
            'total_users' => User::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
        ];
    }

    /**
     * Build the attendance summary block.
     */
    protected static function buildAttendanceSummary(Collection $attendanceRecords, int $studentsCount): array
    {
        $summary = [
            'present' => $attendanceRecords->where('status', 'present')->count(),
            'absent' => $attendanceRecords->where('status', 'absent')->count(),
            'late' => $attendanceRecords->where('status', 'late')->count(),
            'excused' => $attendanceRecords->where('status', 'excused')->count(),
        ];

        $summary['total_marked'] = array_sum($summary);
        $summary['unmarked'] = max($studentsCount - $summary['total_marked'], 0);
        $summary['percentage'] = $summary['total_marked'] > 0
            ? round(($summary['present'] / $summary['total_marked']) * 100, 1)
            : null;

        return $summary;
    }

    /**
     * Build student growth for the last six months.
     */
    protected static function buildStudentGrowth(Carbon $now): Collection
    {
        return collect(range(5, 0))->map(function (int $offset) use ($now) {
            $month = $now->copy()->subMonths($offset);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $count = User::role('student')
                ->whereBetween('created_at', [$start, $end])
                ->count();

            return [
                'label' => $month->translatedFormat('M Y'),
                'count' => $count,
                'month' => $month->format('Y-m'),
            ];
        });
    }

    /**
     * Build the attendance trend for the last seven days.
     */
    protected static function buildAttendanceTrend(Carbon $now, Collection $attendanceRecords): Collection
    {
        $start = $now->copy()->subDays(6)->startOfDay();

        $grouped = $attendanceRecords
            ->filter(fn ($record) => $record->attendance_date->greaterThanOrEqualTo($start))
            ->groupBy(fn ($record) => $record->attendance_date->format('Y-m-d'));

        return collect(range(6, 0))->map(function (int $offset) use ($now, $grouped) {
            $day = $now->copy()->subDays($offset);
            $key = $day->format('Y-m-d');
            $records = $grouped->get($key, collect());
            $total = $records->count();
            $present = $records->where('status', 'present')->count();

            return [
                'label' => $day->translatedFormat('D d M'),
                'present' => $present,
                'total' => $total,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : null,
            ];
        });
    }

    /**
     * Prepare announcement feed payloads for the dashboard widgets.
     */
    protected static function formatAnnouncements(Collection $announcements): array
    {
        return $announcements->map(function (Announcement $announcement) {
            $publishedAt = $announcement->published_at;
            $summaryText = $announcement->summary ?? strip_tags($announcement->content);
            $summary = Str::limit($summaryText, 110);

            return [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'summary' => $summary,
                'type' => $announcement->type,
                'type_label' => $announcement->type === 'news' ? 'خبر' : 'إعلان',
                'type_icon' => $announcement->type === 'news' ? 'fa-newspaper' : 'fa-bell',
                'is_published' => (bool) $announcement->is_published,
                'is_expired' => (bool) $announcement->isExpired,
                'published_human' => $announcement->is_published
                    ? optional($publishedAt)->diffForHumans()
                    : 'في وضع المسودة',
                'day' => $publishedAt ? $publishedAt->format('d') : 'مسودة',
                'month' => $publishedAt ? $publishedAt->translatedFormat('M') : 'لم تنشر',
            ];
        })->values()->all();
    }
}

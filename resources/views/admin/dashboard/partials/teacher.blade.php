@php
    $teacher = auth()->user();
    $classes = $teacherClasses ?? collect();
    $upcoming = $upcomingExams ?? collect();
    $attendanceSummary = $attendanceSummary ?? [];
    $recentAttendance = $recentAttendance ?? collect();
    $announcements = $announcements ?? collect();
    $trendItems = isset($attendanceTrend) ? collect($attendanceTrend) : collect();
@endphp

<div class="role-dashboard teacher-dashboard">
    <div class="card teacher-hero-card mb-4">
        <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="teacher-hero-meta">
                <span class="hero-label">مرحبا {{ $teacher?->name }}</span>
                <h2 class="hero-title">لوحة متابعة المعلم</h2>
                <p class="hero-subtitle">لديك {{ number_format($classes->count()) }} فصلًا نشطًا و {{ number_format($stats['students'] ?? 0) }} طالبًا تحت إشرافك هذا الأسبوع.</p>
            </div>
            <div class="teacher-hero-actions d-flex gap-2">
                <a href="{{ route('attendance.daily') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i>
                    تسجيل الحضور اليومي
                </a>
                <a href="{{ route('exams.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-clipboard-list"></i>
                    إدارة الاختبارات
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="role-stat-card">
                <span class="stat-label">عدد الفصول</span>
                <span class="stat-value" data-stat="classes">{{ number_format($stats['classes'] ?? 0) }}</span>
                <span class="stat-hint">مقسمة على {{ $classes->pluck('grade_level')->unique()->count() }} مراحل</span>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="role-stat-card">
                <span class="stat-label">عدد الطلاب</span>
                <span class="stat-value" data-stat="students">{{ number_format($stats['students'] ?? 0) }}</span>
                <span class="stat-hint">{{ number_format($classes->sum('students_count')) }} طالبًا مسجلًا</span>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="role-stat-card">
                <span class="stat-label">المواد المكلف بها</span>
                <span class="stat-value" data-stat="subjects">{{ number_format($stats['subjects'] ?? 0) }}</span>
                <span class="stat-hint">توزيع المواد عبر الفصول</span>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="role-stat-card">
                <span class="stat-label">نسبة الحضور اليوم</span>
                <span class="stat-value" data-stat="attendance">
                    {{ isset($stats['attendance']) && $stats['attendance'] !== null ? $stats['attendance'] . '%' : '—' }}
                </span>
                <span class="stat-hint">آخر تحديث {{ now()->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">إجماليات الحضور لهذا اليوم</h5>
                        <small class="text-muted">تفاصيل حالة الطلاب في حصصك</small>
                    </div>
                    <span class="badge bg-primary-subtle text-primary fs-6" data-attendance-percentage>
                        {{ isset($attendanceSummary['percentage']) && $attendanceSummary['percentage'] !== null ? $attendanceSummary['percentage'] . '%' : '—' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="progress attendance-progress mb-3">
                        <div class="progress-bar" role="progressbar" data-attendance-progress style="width: {{ isset($attendanceSummary['percentage']) && $attendanceSummary['percentage'] !== null ? min($attendanceSummary['percentage'], 100) : 0 }}%"></div>
                    </div>
                    <div class="row text-center g-3">
                        <div class="col-6 col-md">
                            <div class="attendance-chip present">
                                <span>حاضر</span>
                                <strong data-attendance="present">{{ number_format($attendanceSummary['present'] ?? 0) }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md">
                            <div class="attendance-chip absent">
                                <span>غائب</span>
                                <strong data-attendance="absent">{{ number_format($attendanceSummary['absent'] ?? 0) }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md">
                            <div class="attendance-chip late">
                                <span>متأخر</span>
                                <strong data-attendance="late">{{ number_format($attendanceSummary['late'] ?? 0) }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md">
                            <div class="attendance-chip excused">
                                <span>مُبرر</span>
                                <strong data-attendance="excused">{{ number_format($attendanceSummary['excused'] ?? 0) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">اتجاه الحضور خلال الأسبوع</h5>
                </div>
                <div class="card-body">
                    <ul class="attendance-trend-list" data-attendance-trend>
                        @forelse($trendItems as $item)
                            <li>
                                <span>{{ $item['label'] ?? '' }}</span>
                                <strong>{{ isset($item['percentage']) && $item['percentage'] !== null ? $item['percentage'] . '%' : '—' }}</strong>
                            </li>
                        @empty
                            <li class="empty">لا توجد بيانات حضور للأيام الماضية.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">سجل الحضور الأخير</h5>
                    <a href="{{ route('attendance.daily') }}" class="btn btn-sm btn-link">عرض جميع السجلات</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush" data-attendance-history>
                        @forelse($recentAttendance as $record)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ optional($record->classroom)->name ?? 'فصل غير محدد' }}</strong>
                                    <div class="text-muted small">{{ optional($record->attendance_date)->translatedFormat('l d F Y') }}</div>
                                </div>
                                <span class="badge rounded-pill attendance-status-{{ $record->status }}">{{ $record->status_label }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">لم يتم تسجيل حضور بعد.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">فصولي النشطة</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($classes as $classroom)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $classroom->name }}</strong>
                                        <div class="text-muted small">{{ $classroom->grade_level }} - {{ $classroom->section }}</div>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary">{{ number_format($classroom->students_count ?? 0) }} طالب</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">لا توجد فصول مرتبطة بك حالياً.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">الاختبارات القادمة</h5>
                    <span class="badge bg-info-subtle text-info">{{ $upcoming->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($upcoming as $exam)
                            <li class="list-group-item">
                                <div class="fw-bold">{{ $exam->title ?? $exam->subject?->name ?? 'امتحان' }}</div>
                                <div class="text-muted small">{{ optional($exam->exam_date)->translatedFormat('l d F Y') }} - {{ $exam->classroom?->name }}</div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">لا توجد اختبارات مجدولة قريباً.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">الأخبار والإعلانات</h5>
                    <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-link">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush" data-announcements data-create-url="{{ route('announcements.create') }}">
                        @forelse($announcements as $announcement)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $announcement->title }}</strong>
                                        <div class="text-muted small">{{ optional($announcement->published_at)->diffForHumans() ?? 'مسودة' }}</div>
                                    </div>
                                    <span class="badge bg-light text-muted">{{ $announcement->type === 'news' ? 'خبر' : 'إعلان' }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">لا توجد إعلانات جديدة.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .teacher-dashboard .teacher-hero-card {
            border: none;
            background: linear-gradient(135deg, var(--school-primary, #2c5aa0), #163462);
            color: #fff;
            box-shadow: 0 20px 45px rgba(22, 52, 98, 0.28);
        }

        .teacher-hero-card .hero-label {
            display: inline-block;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .teacher-hero-card .hero-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .teacher-hero-card .hero-subtitle {
            margin: 0;
            opacity: 0.85;
        }

        .teacher-dashboard .role-stat-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 18px 40px rgba(22, 52, 98, 0.08);
            border: 1px solid rgba(44, 90, 160, 0.08);
            height: 100%;
        }

        .role-stat-card .stat-label {
            font-size: 0.9rem;
            color: rgba(15, 23, 42, 0.6);
            display: block;
            margin-bottom: 0.5rem;
        }

        .role-stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #15315a;
        }

        .role-stat-card .stat-hint {
            font-size: 0.8rem;
            color: rgba(15, 23, 42, 0.45);
        }

        .attendance-progress {
            background: #f8fafc;
            border-radius: 999px;
            height: 10px;
            overflow: hidden;
        }

        .attendance-progress .progress-bar {
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.85), rgba(22, 52, 98, 0.85));
        }

        .attendance-chip {
            background: #f1f5f9;
            border-radius: 1rem;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            font-weight: 600;
        }

        .attendance-chip.present { color: #16a34a; }
        .attendance-chip.absent { color: #dc2626; }
        .attendance-chip.late { color: #d97706; }
        .attendance-chip.excused { color: #2563eb; }

        .attendance-status-present { background: rgba(34, 197, 94, 0.15); color: #15803d; }
        .attendance-status-absent { background: rgba(220, 38, 38, 0.15); color: #b91c1c; }
        .attendance-status-late { background: rgba(234, 179, 8, 0.15); color: #b45309; }
        .attendance-status-excused { background: rgba(37, 99, 235, 0.15); color: #1d4ed8; }

        .attendance-trend-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 0.75rem;
        }

        .attendance-trend-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .attendance-trend-list li strong {
            color: #2563eb;
        }

        .attendance-trend-list li.empty {
            justify-content: center;
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .teacher-hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .teacher-hero-actions .btn {
                width: 100%;
            }
        }
    </style>
@endpush

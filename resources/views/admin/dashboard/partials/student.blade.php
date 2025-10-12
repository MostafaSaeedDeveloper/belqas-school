@php
    $student = auth()->user();
    $classrooms = $studentClassrooms ?? collect();
    $upcoming = $upcomingExams ?? collect();
    $grades = $recentGrades ?? collect();
    $announcements = $announcements ?? collect();
    $attendanceSummary = $attendanceSummary ?? [];
    $trendItems = isset($attendanceTrend) ? collect($attendanceTrend) : collect();
@endphp

<div class="role-dashboard student-dashboard">
    <div class="card student-hero-card mb-4">
        <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="student-hero-meta">
                <span class="hero-label">مرحباً {{ $student?->name }}</span>
                <h2 class="hero-title">خطتك الدراسية اليوم</h2>
                <p class="hero-subtitle">أنت مسجل في {{ number_format($classrooms->count()) }} فصول دراسية ولديك {{ number_format($upcoming->count()) }} اختبارات قادمة.</p>
            </div>
            <div class="student-hero-actions d-flex gap-2">
                <a href="{{ route('classes.index') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-alt"></i>
                    جدولي الدراسي
                </a>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user"></i>
                    ملفي الشخصي
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="role-stat-card">
                <span class="stat-label">عدد الفصول</span>
                <span class="stat-value" data-stat="classes">{{ number_format($stats['classes'] ?? 0) }}</span>
                <span class="stat-hint">الفصول المسجل بها هذا العام</span>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="role-stat-card">
                <span class="stat-label">نسبة حضورك</span>
                <span class="stat-value" data-stat="attendance">
                    {{ isset($stats['attendance']) && $stats['attendance'] !== null ? $stats['attendance'] . '%' : '—' }}
                </span>
                <span class="stat-hint">آخر 30 يوماً</span>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="role-stat-card">
                <span class="stat-label">الاختبارات القادمة</span>
                <span class="stat-value" data-stat="upcoming_exams">{{ number_format($stats['upcoming_exams'] ?? 0) }}</span>
                <span class="stat-hint">خلال الأسبوعين المقبلين</span>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="role-stat-card">
                <span class="stat-label">المعدل العام</span>
                <span class="stat-value" data-stat="average_grade">
                    {{ isset($stats['average_grade']) && $stats['average_grade'] !== null ? $stats['average_grade'] . '%' : '—' }}
                </span>
                <span class="stat-hint">آخر الاختبارات المسجلة</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">الحضور خلال الأسبوع</h5>
                        <small class="text-muted">متابعة التزامك خلال آخر 7 أيام</small>
                    </div>
                    <span class="badge bg-primary-subtle text-primary" data-attendance-percentage>
                        {{ isset($attendanceSummary['percentage']) && $attendanceSummary['percentage'] !== null ? $attendanceSummary['percentage'] . '%' : '—' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="progress attendance-progress mb-3">
                        <div class="progress-bar" role="progressbar" data-attendance-progress style="width: {{ isset($attendanceSummary['percentage']) && $attendanceSummary['percentage'] !== null ? min($attendanceSummary['percentage'], 100) : 0 }}%"></div>
                    </div>
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

            <div class="card">
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
                                @if($exam->start_time)
                                    <div class="text-muted small">{{ optional($exam->start_time)->format('H:i') }} - {{ optional($exam->end_time)->format('H:i') }}</div>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">لا توجد اختبارات مجدولة قريباً.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">درجاتي الأخيرة</h5>
                    <span class="badge bg-success-subtle text-success">{{ $grades->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($grades as $grade)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $grade->subject?->name ?? 'مادة غير محددة' }}</strong>
                                        <div class="text-muted small">{{ optional($grade->created_at)->diffForHumans() }}</div>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary">{{ $grade->score_percentage !== null ? round($grade->score_percentage, 1) : '—' }}%</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">لم يتم تسجيل درجات بعد.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">فصولي الدراسية</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($classrooms as $classroom)
                            <li class="list-group-item">
                                <div class="fw-bold">{{ $classroom->name }}</div>
                                <div class="text-muted small">{{ $classroom->grade_level }} - {{ $classroom->section }}</div>
                                <div class="text-muted small">المعلم المسؤول: {{ $classroom->homeroomTeacher?->name ?? 'غير محدد' }}</div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">لم يتم ربطك بفصول حالياً.</li>
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
                    <ul class="list-group list-group-flush" data-announcements>
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
        .student-dashboard .student-hero-card {
            border: none;
            background: linear-gradient(135deg, var(--school-primary, #2c5aa0), #1b3f72);
            color: #fff;
            box-shadow: 0 20px 45px rgba(27, 63, 114, 0.28);
        }

        .student-hero-card .hero-label {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #fff;
        }

        .student-hero-card .hero-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .student-hero-card .hero-subtitle {
            margin: 0;
            opacity: 0.92;
            color: #fff;
        }

        .student-hero-actions .btn.btn-primary {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.35);
            color: #fff;
            backdrop-filter: blur(6px);
        }

        .student-hero-actions .btn.btn-primary:hover,
        .student-hero-actions .btn.btn-primary:focus {
            background: rgba(255, 255, 255, 0.26);
            border-color: rgba(255, 255, 255, 0.45);
            color: #fff;
        }

        .student-hero-actions .btn.btn-outline-primary {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(6px);
        }

        .student-hero-actions .btn.btn-outline-primary:hover,
        .student-hero-actions .btn.btn-outline-primary:focus {
            color: #0f172a;
            background: #fff;
            border-color: #fff;
        }

        .student-dashboard .role-stat-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 18px 40px rgba(27, 63, 114, 0.08);
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

        .student-dashboard .attendance-progress {
            background: #f8fafc;
            border-radius: 999px;
            height: 10px;
            overflow: hidden;
        }

        .student-dashboard .attendance-progress .progress-bar {
            background: linear-gradient(90deg, rgba(248, 181, 0, 0.85), rgba(27, 63, 114, 0.95));
        }

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
            background: #f1f5f9;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .attendance-trend-list li strong {
            color: #0284c7;
        }

        .attendance-trend-list li.empty {
            justify-content: center;
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .student-hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .student-hero-actions .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@php
    $studentChange = $studentTrend['change'];
    $attendancePercentage = $attendanceSummary['percentage'];
    $studentsPerClass = $systemInsights['students_per_class'] ?? null;
    $studentTeacherRatio = $systemInsights['student_teacher_ratio'] ?? null;
@endphp

<div class="dashboard-hero">
    <div class="hero-content">
        <div class="hero-label">لوحة المتابعة اللحظية</div>
        <h2 class="hero-title">مرحباً بك في لوحة التحكم</h2>
        <p class="hero-subtitle">
            لديك {{ number_format($stats['students']) }} طالباً و {{ number_format($stats['teachers']) }} معلمًا مسجلاً في النظام هذا العام.
        </p>

        <div class="hero-insights">
            <div class="hero-insight">
                <span class="insight-label">الطلاب النشطون</span>
                <span class="insight-value" data-stat="students">{{ number_format($stats['students']) }}</span>
                @if(! is_null($studentChange))
                    <span class="insight-change {{ $studentChange >= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-arrow-{{ $studentChange >= 0 ? 'trend-up' : 'trend-down' }}"></i>
                        {{ $studentChange >= 0 ? '+' : '' }}{{ $studentChange }}% مقارنة بالشهر الماضي
                    </span>
                @else
                    <span class="insight-change neutral"><i class="fas fa-circle"></i> بيانات شهرية قيد التجميع</span>
                @endif
            </div>
            <div class="hero-insight">
                <span class="insight-label">الحضور اليوم</span>
                <span class="insight-value" data-stat="attendance" data-attendance-percentage>
                    {{ ! is_null($attendancePercentage) ? $attendancePercentage . '%' : '—' }}
                </span>
                <span class="insight-change {{ ! is_null($attendancePercentage) && $attendancePercentage >= 90 ? 'positive' : 'warning' }}">
                    <i class="fas fa-calendar-check"></i>
                    {{ number_format($attendanceSummary['total_marked']) }} سجل مكتمل اليوم
                </span>
            </div>
            <div class="hero-insight">
                <span class="insight-label">الفصول النشطة</span>
                <span class="insight-value" data-stat="classes">{{ number_format($stats['classes']) }}</span>
                <span class="insight-change neutral">
                    <i class="fas fa-users"></i>
                    {{ $studentsPerClass ? 'متوسط ' . $studentsPerClass . ' طلاب لكل فصل' : 'بانتظار توزيع الطلاب' }}
                </span>
            </div>
        </div>

        <div class="hero-actions">
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i>
                إضافة طالب جديد
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-light">
                <i class="fas fa-chart-line"></i>
                استعراض التقارير
            </a>
        </div>
    </div>

    <div class="hero-widget">
        <div class="widget-header">
            <span class="widget-title">متابعة الحضور اليومي</span>
            <span class="widget-date">تحديث: {{ now()->translatedFormat('j F Y - H:i') }}</span>
        </div>
        <div class="widget-progress">
            <span class="progress-value" data-attendance-percentage>
                {{ ! is_null($attendancePercentage) ? $attendancePercentage . '%' : 'لا توجد سجلات' }}
            </span>
            <div class="progress-bar">
                <span data-attendance-progress style="width: {{ ! is_null($attendancePercentage) ? min($attendancePercentage, 100) : 0 }}%"></span>
            </div>
        </div>
        <ul class="widget-meta">
            <li><span>حاضر</span><strong data-attendance="present">{{ number_format($attendanceSummary['present']) }}</strong></li>
            <li><span>غائب</span><strong data-attendance="absent">{{ number_format($attendanceSummary['absent']) }}</strong></li>
            <li><span>متأخر</span><strong data-attendance="late">{{ number_format($attendanceSummary['late']) }}</strong></li>
            <li><span>إذن مسبق</span><strong data-attendance="excused">{{ number_format($attendanceSummary['excused']) }}</strong></li>
            <li><span>بانتظار التسجيل</span><strong data-attendance="unmarked">{{ number_format($attendanceSummary['unmarked']) }}</strong></li>
        </ul>
    </div>
</div>

<section class="metrics-grid">
    <article class="metric-card metric-students" data-type="students">
        <div class="metric-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="metric-content">
            <span class="metric-label">إجمالي الطلاب</span>
            <div class="metric-value" data-stat="students">{{ number_format($stats['students']) }}</div>
            <div class="metric-trend {{ $studentChange >= 0 ? 'positive' : 'negative' }}">
                <i class="fas fa-arrow-{{ $studentChange >= 0 ? 'up' : 'down' }}"></i>
                {{ $studentChange !== null ? ($studentChange >= 0 ? '+' : '') . $studentChange . '%' : 'بيانات شهرية قيد التحديث' }}
            </div>
        </div>
    </article>

    <article class="metric-card metric-teachers" data-type="teachers">
        <div class="metric-icon"><i class="fas fa-chalkboard-user"></i></div>
        <div class="metric-content">
            <span class="metric-label">عدد المعلمين</span>
            <div class="metric-value" data-stat="teachers">{{ number_format($stats['teachers']) }}</div>
            <div class="metric-trend positive">
                <i class="fas fa-user-check"></i>
                {{ $studentTeacherRatio ? $studentTeacherRatio . ' طالب لكل معلم' : 'لا يوجد توزيع بعد' }}
            </div>
        </div>
    </article>

    <article class="metric-card metric-classes" data-type="classes">
        <div class="metric-icon"><i class="fas fa-school"></i></div>
        <div class="metric-content">
            <span class="metric-label">الفصول النشطة</span>
            <div class="metric-value" data-stat="classes">{{ number_format($stats['classes']) }}</div>
            <div class="metric-trend neutral"><i class="fas fa-circle-notch"></i> {{ $stats['subjects'] }} مادة دراسية</div>
        </div>
    </article>

    <article class="metric-card metric-attendance" data-type="attendance">
        <div class="metric-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="metric-content">
            <span class="metric-label">نسبة الحضور</span>
            <div class="metric-value" data-stat="attendance">{{ ! is_null($attendancePercentage) ? $attendancePercentage . '%' : '—' }}</div>
            <div class="metric-trend {{ ! is_null($attendancePercentage) && $attendancePercentage >= 90 ? 'positive' : 'warning' }}">
                <i class="fas fa-clock"></i>
                آخر تحديث {{ now()->diffForHumans() }}
            </div>
        </div>
    </article>
</section>

<section class="analytics-layout">
    <div class="analytics-main">
        <div class="analytics-card">
            <div class="analytics-header">
                <div>
                    <h3 class="analytics-title">نمو أعداد الطلاب</h3>
                    <span class="analytics-subtitle">آخر ستة أشهر</span>
                </div>
            </div>
            <div class="analytics-body">
                <ul class="analytics-list" data-student-growth>
                    @foreach($studentGrowth as $item)
                        <li>
                            <span>{{ $item['label'] }}</span>
                            <strong>{{ number_format($item['count']) }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="analytics-card">
            <div class="analytics-header">
                <div>
                    <h3 class="analytics-title">معدل الحضور الأسبوعي</h3>
                    <span class="analytics-subtitle">آخر سبعة أيام</span>
                </div>
            </div>
            <div class="analytics-body">
                <ul class="analytics-list" data-attendance-trend>
                    @foreach($attendanceTrend as $item)
                        <li>
                            <span>{{ $item['label'] }}</span>
                            <strong>{{ ! is_null($item['percentage']) ? $item['percentage'] . '%' : '—' }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <aside class="analytics-side">
        <div class="quick-actions-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt"></i> الإجراءات السريعة</h3>
                <span class="card-subtitle">اختر ما تحتاجه للبدء فوراً</span>
            </div>
            <div class="quick-actions-grid">
                <a href="{{ route('students.create') }}" class="quick-action" data-action="add-student">
                    <i class="fas fa-user-plus"></i>
                    <span>إضافة طالب</span>
                </a>
                <a href="{{ route('teachers.create') }}" class="quick-action" data-action="add-teacher">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>إضافة معلم</span>
                </a>
                <a href="{{ route('attendance.daily') }}" class="quick-action" data-action="take-attendance">
                    <i class="fas fa-calendar-check"></i>
                    <span>تسجيل الحضور</span>
                </a>
                <a href="{{ route('reports.index') }}" class="quick-action" data-action="view-reports">
                    <i class="fas fa-chart-pie"></i>
                    <span>عرض التقارير</span>
                </a>
                <a href="{{ route('classes.index') }}" class="quick-action" data-action="manage-classes">
                    <i class="fas fa-school"></i>
                    <span>إدارة الفصول</span>
                </a>
                <a href="{{ route('finance.index') }}" class="quick-action" data-action="financial-overview">
                    <i class="fas fa-money-check-alt"></i>
                    <span>الوضع المالي</span>
                </a>
            </div>
        </div>

        <div class="status-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> مؤشرات الأداء</h3>
            </div>
            <ul class="status-list">
                <li>
                    <span>نسبة الحضور اليوم</span>
                    <div class="status-meter">
                        <span class="{{ ! is_null($attendancePercentage) && $attendancePercentage >= 90 ? 'positive' : 'warning' }}" data-attendance-progress style="width: {{ ! is_null($attendancePercentage) ? min($attendancePercentage, 100) : 0 }}%"></span>
                    </div>
                    <strong data-attendance-percentage>{{ ! is_null($attendancePercentage) ? $attendancePercentage . '%' : '—' }}</strong>
                </li>
                <li>
                    <span>متوسط الطلاب لكل معلم</span>
                    @php $ratioPercent = $studentTeacherRatio ? min(($studentTeacherRatio / 25) * 100, 100) : 0; @endphp
                    <div class="status-meter">
                        <span style="width: {{ $ratioPercent }}%"></span>
                    </div>
                    <strong>{{ $studentTeacherRatio ? $studentTeacherRatio . ' طالب' : 'لا توجد بيانات' }}</strong>
                </li>
                <li>
                    <span>أولياء الأمور المسجلون</span>
                    @php $parentsPercent = $stats['students'] > 0 ? min(($stats['parents'] / max($stats['students'], 1)) * 100, 100) : 0; @endphp
                    <div class="status-meter">
                        <span class="positive" style="width: {{ $parentsPercent }}%"></span>
                    </div>
                    <strong>{{ number_format($stats['parents']) }} ولي أمر</strong>
                </li>
            </ul>
        </div>
    </aside>
</section>

<section class="engagement-grid">
    <div class="engagement-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-history"></i> النشاط الأخير</h3>
        </div>
        <div class="activity-timeline activity-list">
            @forelse($recentUsers as $user)
                <div class="activity-item">
                    <div class="activity-item-header">
                        <strong>{{ $user->name }}</strong>
                        <span class="activity-role">{{ $user->getRoleNames()->first() ?? 'مستخدم' }}</span>
                    </div>
                    <span class="activity-time">{{ $user->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                    <p>لم يتم تسجيل نشاطات جديدة مؤخراً.</p>
                </div>
            @endforelse
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('users.index') }}" class="btn btn-soft-primary">
                <i class="fas fa-users"></i>
                إدارة المستخدمين
            </a>
        </div>
    </div>

    <div class="engagement-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-days"></i> الامتحانات القادمة</h3>
        </div>
        <div class="calendar" data-calendar>
            <div class="calendar-header">
                <div class="calendar-month">{{ now()->translatedFormat('F Y') }}</div>
            </div>
            <div class="calendar-events">
                @forelse($upcomingExams as $exam)
                    <div class="calendar-event">
                        <div class="calendar-event-date">
                            <span class="day">{{ optional($exam->exam_date)->format('d') }}</span>
                            <span class="month">{{ optional($exam->exam_date)->translatedFormat('M') }}</span>
                        </div>
                        <div class="calendar-event-content">
                            <h4>{{ $exam->title ?? $exam->subject?->name ?? 'امتحان' }}</h4>
                            <p>
                                {{ $exam->classroom?->name ?? 'بدون فصل محدد' }}
                                @if($exam->exam_date)
                                    • {{ $exam->exam_date->translatedFormat('l g:i A') }}
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-calendar-minus"></i></div>
                        <p>لا توجد امتحانات قادمة حالياً.</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('exams.index') }}" class="btn btn-soft-primary btn-sm">
                <i class="fas fa-calendar-alt"></i>
                جميع الامتحانات
            </a>
        </div>
    </div>

    <div class="engagement-card news-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-bullhorn"></i> آخر الأخبار والإعلانات</h3>
        </div>
        <div class="news-list" data-announcements data-create-url="{{ route('announcements.create') }}">
            @forelse($announcements as $announcement)
                <article class="news-item">
                    <div class="news-date {{ $announcement->is_published ? '' : 'is-draft' }}">
                        <span class="day">
                            {{ $announcement->published_at ? $announcement->published_at->format('d') : 'مسودة' }}
                        </span>
                        <span class="month">
                            {{ $announcement->published_at ? $announcement->published_at->translatedFormat('M') : 'لم تنشر' }}
                        </span>
                    </div>
                    <div class="news-content">
                        <h4>{{ $announcement->title }}</h4>
                        <p>
                            {{ $announcement->summary
                                ? \Illuminate\Support\Str::limit($announcement->summary, 110)
                                : \Illuminate\Support\Str::limit(strip_tags($announcement->content), 110) }}
                        </p>
                        <div class="news-meta">
                            <span class="news-type {{ $announcement->type === 'news' ? 'is-news' : 'is-announcement' }}">
                                <i class="fas {{ $announcement->type === 'news' ? 'fa-newspaper' : 'fa-bell' }}"></i>
                                {{ $announcement->type === 'news' ? 'خبر' : 'إعلان' }}
                            </span>
                            <span class="news-time">
                                {{ $announcement->is_published
                                    ? $announcement->published_at?->diffForHumans()
                                    : 'في وضع المسودة' }}
                                @if($announcement->isExpired)
                                    • <span class="text-warning">منتهي</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state" data-announcements-empty>
                    <div class="empty-icon"><i class="fas fa-bullhorn"></i></div>
                    <p>لم تتم إضافة أخبار أو إعلانات بعد.</p>
                    <a href="{{ route('announcements.create') }}" class="btn btn-soft-primary btn-sm mt-2">
                        <i class="fas fa-plus"></i>
                        إنشاء أول رسالة
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="insights-grid">
    <div class="engagement-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-school"></i> الفصول الأعلى كثافة</h3>
        </div>
        <div class="news-list">
            @forelse($topClasses as $classroom)
                <article class="news-item">
                    <div class="news-date">
                        <span class="day">{{ number_format($classroom->students_count) }}</span>
                        <span class="month">طالب</span>
                    </div>
                    <div class="news-content">
                        <h4>{{ $classroom->name }}</h4>
                        <p>
                            {{ $classroom->grade_level ? 'الصف ' . $classroom->grade_level : 'المستوى غير محدد' }}
                            • المعلم: {{ optional($classroom->homeroomTeacher)->name ?? 'غير محدد' }}
                        </p>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-school"></i></div>
                    <p>لا توجد بيانات فصول بعد، ابدأ بإضافة الفصول والطلاب.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@push('styles')
<style>
    .btn-soft-primary {
        background: rgba(37, 99, 235, 0.12);
        color: #1d4ed8;
        border-radius: 999px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-soft-primary:hover {
        background: rgba(37, 99, 235, 0.2);
        color: #1d4ed8;
    }

    .analytics-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .analytics-list li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-radius: 14px;
        background: rgba(248, 250, 252, 0.9);
        border: 1px solid rgba(226, 232, 240, 0.6);
        color: #0f172a;
        font-weight: 600;
    }

    .analytics-list li strong {
        color: #2563eb;
    }

    .calendar-events {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .calendar-event {
        display: flex;
        gap: 1rem;
        align-items: center;
        padding: 0.9rem 1.1rem;
        border-radius: 18px;
        background: rgba(248, 250, 252, 0.95);
        border: 1px solid rgba(226, 232, 240, 0.6);
    }

    .calendar-event-date {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        color: #fff;
        font-weight: 700;
    }

    .calendar-event-date .day {
        font-size: 1.25rem;
        line-height: 1.2;
    }

    .calendar-event-date .month {
        font-size: 0.8rem;
    }

    .calendar-event-content h4 {
        margin: 0;
        font-size: 1rem;
        color: #0f172a;
        font-weight: 700;
    }

    .calendar-event-content p {
        margin: 0.25rem 0 0;
        color: #475569;
        font-size: 0.9rem;
    }

    .activity-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.35rem;
    }

    .activity-role {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.12);
        color: #1d4ed8;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .activity-time {
        display: block;
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    @media (max-width: 992px) {
        .analytics-list li {
            padding: 0.75rem;
        }

        .calendar-event {
            flex-direction: column;
            align-items: flex-start;
        }

        .calendar-event-date {
            width: 100%;
            flex-direction: row;
            justify-content: center;
            gap: 0.75rem;
        }
    }
</style>
@endpush


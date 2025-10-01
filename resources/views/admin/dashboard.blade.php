@extends('admin.layouts.master')

@section('title', 'لوحة التحكم - نظام إدارة مدرسة بلقاس')

@section('page-header')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'نظرة شاملة على أداء المدرسة')

@section('breadcrumb')
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection
@endsection

@section('content')
<div class="dashboard-hero">
    <div class="dashboard-hero__content">
        <span class="hero-chip">
            <i class="fas fa-magic"></i>
            تجربة إدارة حديثة
        </span>
        <h2 class="hero-title">مرحباً بكم في لوحة قيادة مدرسة بلقاس</h2>
        <p class="hero-description">
            تابع أداء المدرسة لحظة بلحظة، واطلع على المؤشرات المهمة لتخطيط يوم دراسي أكثر تنظيماً وفاعلية.
        </p>
        <div class="hero-actions">
            <a href="{{ route('reports.index') }}" class="hero-action primary">
                <i class="fas fa-chart-line"></i>
                عرض التقارير الشاملة
            </a>
            <a href="{{ route('events.index') }}" class="hero-action ghost">
                <i class="fas fa-calendar-plus"></i>
                إدارة جدول الأحداث
            </a>
        </div>
        <div class="hero-glance">
            <div class="glance-item">
                <span class="glance-label">إجمالي الطلاب</span>
                <span class="glance-number" data-stat="students">0</span>
                <span class="glance-trend">
                    <i class="fas fa-arrow-up"></i>
                    +12% نمو شهري
                </span>
            </div>
            <div class="glance-item">
                <span class="glance-label">طاقم المعلمين</span>
                <span class="glance-number" data-stat="teachers">0</span>
                <span class="glance-trend">
                    <i class="fas fa-user-plus"></i>
                    +3 معلمين جدد
                </span>
            </div>
            <div class="glance-item">
                <span class="glance-label">متوسط الحضور</span>
                <span class="glance-number" data-stat="attendance">0</span>
                <span class="glance-trend">
                    <i class="fas fa-level-up-alt"></i>
                    +2% هذا الأسبوع
                </span>
            </div>
        </div>
    </div>
    <div class="dashboard-hero__visual">
        <div class="visual-card">
            <h3 class="visual-title">نبض الحضور اليومي</h3>
            <p class="visual-subtitle">متابعة مباشرة لنسبة حضور الطلاب في جميع الفصول.</p>
            <div class="visual-progress">
                <div class="progress-circle">
                    <span data-stat="attendance">0</span>
                    <small>%</small>
                </div>
                <div class="visual-list">
                    <div class="visual-item">
                        <i class="fas fa-calendar-day"></i>
                        <div>
                            <span class="visual-value">8 حصص</span>
                            <span class="visual-label">مجدوَلة لليوم</span>
                        </div>
                    </div>
                    <div class="visual-item">
                        <i class="fas fa-user-check"></i>
                        <div>
                            <span class="visual-value">95%</span>
                            <span class="visual-label">متوسط الأسبوع الماضي</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card" data-type="students">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="stat-content">
            <span class="stat-meta">الطلاب</span>
            <div class="stat-number" data-stat="students">0</div>
            <div class="stat-label">الطلاب المسجلين حالياً</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12% من الشهر الماضي</span>
            </div>
        </div>
    </div>

    <div class="stat-card" data-type="teachers">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
        <div class="stat-content">
            <span class="stat-meta">المعلمون</span>
            <div class="stat-number" data-stat="teachers">0</div>
            <div class="stat-label">طاقم التدريس الفعّال</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+3 معلمين جدد</span>
            </div>
        </div>
    </div>

    <div class="stat-card" data-type="classes">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-school"></i>
            </div>
        </div>
        <div class="stat-content">
            <span class="stat-meta">الفصول</span>
            <div class="stat-number" data-stat="classes">0</div>
            <div class="stat-label">الفصول الدراسية النشطة</div>
            <div class="stat-change neutral">
                <i class="fas fa-minus"></i>
                <span>لا توجد تغييرات</span>
            </div>
        </div>
    </div>

    <div class="stat-card" data-type="attendance">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-content">
            <span class="stat-meta">الحضور</span>
            <div class="stat-number" data-stat="attendance">0</div>
            <div class="stat-label">متوسط نسبة الحضور</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+2% من الأسبوع الماضي</span>
            </div>
        </div>
    </div>
</div>

<div class="quick-actions-section">
    <h2 class="section-title">
        <i class="fas fa-bolt"></i>
        العمليات السريعة
    </h2>
    <div class="actions-grid">
        <a href="{{ route('students.create') }}" class="action-btn" data-action="add-student">
            <i class="fas fa-user-plus"></i>
            <span>إضافة طالب</span>
            <small>تسجيل طالب جديد وإسناد فصله الدراسي</small>
        </a>

        <a href="{{ route('teachers.create') }}" class="action-btn" data-action="add-teacher">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>إضافة معلم</span>
            <small>استكمال بيانات المعلم وتحديد جدوله</small>
        </a>

        <a href="{{ route('attendance.daily') }}" class="action-btn" data-action="take-attendance">
            <i class="fas fa-calendar-check"></i>
            <span>تسجيل الحضور</span>
            <small>متابعة حضور وغياب الطلاب في الفصول</small>
        </a>

        <a href="{{ route('reports.index') }}" class="action-btn" data-action="view-reports">
            <i class="fas fa-chart-bar"></i>
            <span>عرض التقارير</span>
            <small>تحليل الأداء الأكاديمي والإداري</small>
        </a>

        <a href="{{ route('classes.index') }}" class="action-btn" data-action="manage-classes">
            <i class="fas fa-school"></i>
            <span>إدارة الفصول</span>
            <small>مراجعة الفصول والجداول المعتمدة</small>
        </a>

        <a href="{{ route('finance.index') }}" class="action-btn" data-action="financial-overview">
            <i class="fas fa-money-bill-wave"></i>
            <span>الشؤون المالية</span>
            <small>متابعة الرسوم والمدفوعات اليومية</small>
        </a>
    </div>
</div>

<div class="charts-section">
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">نمو أعداد الطلاب</h3>
            <span class="chart-period">آخر 6 أشهر</span>
        </div>
        <div class="chart-container" id="students-chart">
            <!-- Chart will be rendered by JavaScript -->
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">معدل الحضور الأسبوعي</h3>
            <span class="chart-period">آخر 6 أسابيع</span>
        </div>
        <div class="chart-container" id="attendance-chart">
            <!-- Chart will be rendered by JavaScript -->
        </div>
    </div>
</div>

<div class="dashboard-bottom">
    <div class="activity-section">
        <h2 class="section-title">
            <i class="fas fa-history"></i>
            النشاط الأخير
        </h2>
        <div class="activity-list">
            <!-- Activities will be loaded by JavaScript -->
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('activity.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-eye"></i>
                عرض جميع الأنشطة
            </a>
        </div>
    </div>

    <div class="calendar-section">
        <h2 class="section-title">
            <i class="fas fa-calendar"></i>
            التقويم المدرسي
        </h2>
        <div class="calendar">
            <div class="calendar-header">
                <button class="calendar-nav-btn calendar-prev">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="calendar-month">سبتمبر 2025</div>
                <button class="calendar-nav-btn calendar-next">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <div class="calendar-grid">
                <!-- Calendar will be rendered by JavaScript -->
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('events.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-calendar-alt"></i>
                عرض جميع الأحداث
            </a>
        </div>
    </div>
</div>

<div class="news-section">
    <div class="news-header">
        <h3>
            <i class="fas fa-newspaper"></i>
            آخر الأخبار والإعلانات
        </h3>
    </div>
    <div class="news-list">
        <div class="news-item">
            <div class="news-date">
                <span class="day">15</span>
                <span class="month">سبتمبر</span>
            </div>
            <div class="news-content">
                <h4>بداية العام الدراسي الجديد</h4>
                <p>يسر إدارة المدرسة أن تعلن عن بداية العام الدراسي الجديد 2025/2026</p>
            </div>
            <span class="news-tag">إعلان</span>
        </div>

        <div class="news-item">
            <div class="news-date">
                <span class="day">12</span>
                <span class="month">سبتمبر</span>
            </div>
            <div class="news-content">
                <h4>اجتماع أولياء الأمور</h4>
                <p>اجتماع عام لأولياء الأمور يوم الخميس الساعة 10 صباحاً</p>
            </div>
            <span class="news-tag">تنويه</span>
        </div>

        <div class="news-item">
            <div class="news-date">
                <span class="day">10</span>
                <span class="month">سبتمبر</span>
            </div>
            <div class="news-content">
                <h4>ورشة تدريبية للمعلمين</h4>
                <p>ورشة تدريبية حول استخدام التكنولوجيا في التعليم</p>
            </div>
            <span class="news-tag">تطوير مهني</span>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.news-section {
    position: relative;
    background: rgba(255, 255, 255, 0.94);
    border-radius: 30px;
    border: 1px solid rgba(148, 163, 184, 0.16);
    box-shadow: 0 32px 65px rgba(15, 23, 42, 0.1);
    padding: var(--spacing-xxl);
    margin-bottom: var(--spacing-xxl);
}

.news-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-xl);
}

.news-header h3 {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    font-size: 1.4rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}

.news-header h3 i {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.18), rgba(59, 130, 246, 0.18));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4338ca;
    font-size: 1.4rem;
    box-shadow: 0 12px 24px rgba(99, 102, 241, 0.2);
}

.news-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.news-item {
    display: grid;
    grid-template-columns: 90px 1fr auto;
    align-items: center;
    gap: var(--spacing-xl);
    padding: var(--spacing-lg) var(--spacing-xl);
    background: rgba(248, 250, 252, 0.86);
    border: 1px solid rgba(148, 163, 184, 0.18);
    border-radius: 24px;
    box-shadow: 0 20px 45px rgba(148, 163, 184, 0.18);
    transition: transform var(--transition-normal), box-shadow var(--transition-normal);
}

.news-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 28px 60px rgba(59, 130, 246, 0.18);
}

.news-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #4338ca, #2563eb);
    color: var(--white);
    border-radius: 20px;
    padding: var(--spacing-md) var(--spacing-sm);
    min-width: 80px;
    text-align: center;
}

.news-date .day {
    font-size: 1.6rem;
    font-weight: 800;
    line-height: 1;
}

.news-date .month {
    font-size: 0.85rem;
    opacity: 0.85;
}

.news-content h4 {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: var(--spacing-xs);
}

.news-content p {
    margin: 0;
    color: #475569;
    font-size: 0.92rem;
    line-height: 1.7;
}

.news-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.2));
    color: #047857;
    font-weight: 700;
    font-size: 0.85rem;
}

@media (max-width: 992px) {
    .news-item {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .news-tag {
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Additional dashboard functionality can be added here
document.addEventListener('DOMContentLoaded', function() {
    console.log('لوحة التحكم جاهزة');
});
</script>
@endpush

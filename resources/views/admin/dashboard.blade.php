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
<!-- Statistics Cards -->
<div class="stats-grid">
    <!-- Students Card -->
    <div class="stat-card" data-type="students">
        <div class="stat-card-header">
            <div class="stat-icon students">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-number" data-stat="students">0</div>
            <div class="stat-label">إجمالي الطلاب</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12% من الشهر الماضي</span>
            </div>
        </div>
    </div>

    <!-- Teachers Card -->
    <div class="stat-card" data-type="teachers">
        <div class="stat-card-header">
            <div class="stat-icon teachers">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-number" data-stat="teachers">0</div>
            <div class="stat-label">المعلمين</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+3 معلمين جدد</span>
            </div>
        </div>
    </div>

    <!-- Classes Card -->
    <div class="stat-card" data-type="classes">
        <div class="stat-card-header">
            <div class="stat-icon classes">
                <i class="fas fa-school"></i>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-number" data-stat="classes">0</div>
            <div class="stat-label">الفصول الدراسية</div>
            <div class="stat-change neutral">
                <i class="fas fa-minus"></i>
                <span>لا توجد تغييرات</span>
            </div>
        </div>
    </div>

    <!-- Attendance Card -->
    <div class="stat-card" data-type="attendance">
        <div class="stat-card-header">
            <div class="stat-icon attendance">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-number" data-stat="attendance">0</div>
            <div class="stat-label">نسبة الحضور %</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+2% من الأسبوع الماضي</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions-section">
    <h2 class="section-title">
        <i class="fas fa-bolt"></i>
        العمليات السريعة
    </h2>
    <div class="actions-grid">
        <a href="{{ route('students.create') }}" class="action-btn" data-action="add-student">
            <i class="fas fa-user-plus"></i>
            <span>إضافة طالب</span>
        </a>

        <a href="{{ route('teachers.create') }}" class="action-btn" data-action="add-teacher">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>إضافة معلم</span>
        </a>

        <a href="{{ route('attendance.daily') }}" class="action-btn" data-action="take-attendance">
            <i class="fas fa-calendar-check"></i>
            <span>أخذ الحضور</span>
        </a>

        <a href="{{ route('reports.index') }}" class="action-btn" data-action="view-reports">
            <i class="fas fa-chart-bar"></i>
            <span>عرض التقارير</span>
        </a>

        <a href="{{ route('classes.index') }}" class="action-btn" data-action="manage-classes">
            <i class="fas fa-school"></i>
            <span>إدارة الفصول</span>
        </a>

        <a href="{{ route('finance.index') }}" class="action-btn" data-action="financial-overview">
            <i class="fas fa-money-bill-wave"></i>
            <span>الشؤون المالية</span>
        </a>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-section">
    <!-- Students Growth Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">نمو أعداد الطلاب</h3>
            <span class="chart-period">آخر 6 أشهر</span>
        </div>
        <div class="chart-container" id="students-chart">
            <!-- Chart will be rendered by JavaScript -->
        </div>
    </div>

    <!-- Attendance Rate Chart -->
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

<!-- Bottom Section: Activity & Calendar -->
<div class="dashboard-bottom">
    <!-- Recent Activity -->
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

    <!-- Calendar -->
    <div class="calendar-section">
        <h2 class="section-title">
            <i class="fas fa-calendar"></i>
            التقويم
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

<!-- Latest News Section -->
<div class="news-section" style="margin-bottom: 2rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title mb-0">
                <i class="fas fa-newspaper"></i>
                آخر الأخبار والإعلانات
            </h3>
        </div>
        <div class="card-body">
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
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* News Section Styles */
.news-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.news-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.news-item {
    display: flex;
    gap: var(--spacing-lg);
    padding: var(--spacing-lg);
    background: rgba(102, 126, 234, 0.02);
    border-radius: var(--border-radius-lg);
    transition: var(--transition-normal);
}

.news-item:hover {
    background: rgba(102, 126, 234, 0.05);
    transform: translateX(-5px);
}

.news-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: var(--gradient-primary);
    color: var(--white);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-sm);
    min-width: 60px;
    text-align: center;
}

.news-date .day {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.news-date .month {
    font-size: 0.8rem;
    opacity: 0.9;
}

.news-content h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: var(--spacing-xs);
    color: var(--dark-color);
}

.news-content p {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .news-item {
        flex-direction: column;
        text-align: center;
    }

    .news-date {
        align-self: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Additional dashboard functionality can be added here
document.addEventListener('DOMContentLoaded', function() {
    // Add any page-specific JavaScript here
    console.log('لوحة التحكم جاهزة');
});
</script>
@endpush

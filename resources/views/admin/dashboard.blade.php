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
<!-- Dashboard Hero -->
<div class="dashboard-hero">
    <div class="hero-content">
        <span class="hero-badge">
            <i class="fas fa-chart-pie"></i>
            نظرة عامة سريعة
        </span>
        <h1 class="hero-title">مرحباً بعودتك، {{ auth()->user()?->name ?? 'مدير النظام' }}</h1>
        <p class="hero-subtitle">تابع أداء المدرسة عبر ملخص تفاعلي يساعدك على اتخاذ القرارات بشكل أسرع وأكثر دقة.</p>

        <div class="hero-meta">
            <div class="meta-item">
                <span class="meta-label">تقارير اليوم</span>
                <span class="meta-value">12</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">طلبات الانتظار</span>
                <span class="meta-value">4</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">رسائل جديدة</span>
                <span class="meta-value">8</span>
            </div>
        </div>
    </div>
    <div class="hero-actions">
        <a href="{{ route('reports.index') }}" class="hero-btn primary">
            <i class="fas fa-chart-line"></i>
            عرض تقرير الأداء
        </a>
        <a href="{{ route('activity.index') }}" class="hero-btn ghost">
            <i class="fas fa-history"></i>
            متابعة النشاط
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <!-- Students Card -->
    <div class="stat-card" data-type="students">
        <div class="stat-card-pattern"></div>
        <div class="stat-card-header">
            <span class="stat-badge">
                <i class="fas fa-user-graduate"></i>
                الطلاب
            </span>
            <span class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                12%
            </span>
        </div>
        <div class="stat-main">
            <div class="stat-number" data-stat="students">0</div>
            <p class="stat-label">إجمالي الطلاب المسجلين</p>
        </div>
        <div class="stat-footer">
            <div class="stat-progress">
                <span class="progress-label">معدل الالتحاق السنوي</span>
                <div class="progress-bar">
                    <span style="width: 78%"></span>
                </div>
            </div>
            <span class="stat-note"><i class="fas fa-clock"></i> تحديث منذ 3 دقائق</span>
        </div>
    </div>

    <!-- Teachers Card -->
    <div class="stat-card" data-type="teachers">
        <div class="stat-card-pattern"></div>
        <div class="stat-card-header">
            <span class="stat-badge">
                <i class="fas fa-chalkboard-teacher"></i>
                المعلمون
            </span>
            <span class="stat-trend positive">
                <i class="fas fa-user-plus"></i>
                +3
            </span>
        </div>
        <div class="stat-main">
            <div class="stat-number" data-stat="teachers">0</div>
            <p class="stat-label">طاقم التدريس النشط</p>
        </div>
        <div class="stat-footer">
            <div class="stat-progress">
                <span class="progress-label">نسبة تغطية الفصول</span>
                <div class="progress-bar">
                    <span style="width: 92%"></span>
                </div>
            </div>
            <span class="stat-note"><i class="fas fa-user-shield"></i> 96% رضا المعلمين</span>
        </div>
    </div>

    <!-- Classes Card -->
    <div class="stat-card" data-type="classes">
        <div class="stat-card-pattern"></div>
        <div class="stat-card-header">
            <span class="stat-badge">
                <i class="fas fa-school"></i>
                الفصول
            </span>
            <span class="stat-trend neutral">
                <i class="fas fa-circle-notch"></i>
                مستقر
            </span>
        </div>
        <div class="stat-main">
            <div class="stat-number" data-stat="classes">0</div>
            <p class="stat-label">عدد الفصول العاملة</p>
        </div>
        <div class="stat-footer">
            <div class="stat-progress">
                <span class="progress-label">استخدام الطاقة الاستيعابية</span>
                <div class="progress-bar">
                    <span style="width: 64%"></span>
                </div>
            </div>
            <span class="stat-note"><i class="fas fa-info-circle"></i> 5 فصول بحاجة لمتابعة</span>
        </div>
    </div>

    <!-- Attendance Card -->
    <div class="stat-card" data-type="attendance">
        <div class="stat-card-pattern"></div>
        <div class="stat-card-header">
            <span class="stat-badge">
                <i class="fas fa-calendar-check"></i>
                الحضور
            </span>
            <span class="stat-trend positive">
                <i class="fas fa-arrow-trend-up"></i>
                +2%
            </span>
        </div>
        <div class="stat-main">
            <div class="stat-number" data-stat="attendance">0</div>
            <p class="stat-label">نسبة الحضور اليومية</p>
        </div>
        <div class="stat-footer">
            <div class="stat-progress">
                <span class="progress-label">الالتزام بالخطة الأسبوعية</span>
                <div class="progress-bar">
                    <span style="width: 84%"></span>
                </div>
            </div>
            <span class="stat-note"><i class="fas fa-bell"></i> 3 تنبيهات متأخرة</span>
        </div>
    </div>
</div>

<!-- Performance Snapshots -->
<div class="performance-section">
    <div class="performance-card">
        <div class="performance-icon success">
            <i class="fas fa-medal"></i>
        </div>
        <div class="performance-details">
            <h3>أفضل أداء أكاديمي</h3>
            <p>الصف الثاني الثانوي - نسبة النجاح 98%</p>
        </div>
        <span class="performance-badge">+6 نقاط</span>
    </div>

    <div class="performance-card">
        <div class="performance-icon info">
            <i class="fas fa-lightbulb"></i>
        </div>
        <div class="performance-details">
            <h3>مبادرات قيد التنفيذ</h3>
            <p>4 مبادرات لتحسين التعلم الرقمي هذا الشهر</p>
        </div>
        <span class="performance-badge neutral">65% منجزة</span>
    </div>

    <div class="performance-card">
        <div class="performance-icon warning">
            <i class="fas fa-user-clock"></i>
        </div>
        <div class="performance-details">
            <h3>متابعة الحضور</h3>
            <p>7 طلاب بحاجة للتواصل بسبب غياب متكرر</p>
        </div>
        <span class="performance-badge alert">يتطلب متابعة</span>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions-section">
    <h2 class="section-title">
        <i class="fas fa-bolt"></i>
        العمليات السريعة
    </h2>
    <div class="actions-grid">
        <a href="{{ route('students.create') }}" class="action-card" data-action="add-student">
            <div class="action-icon primary">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="action-info">
                <h3>إضافة طالب جديد</h3>
                <p>تسجيل طالب خلال خطوات بسيطة مع التحقق من البيانات.</p>
            </div>
            <span class="action-link">ابدأ الآن</span>
        </a>

        <a href="{{ route('teachers.create') }}" class="action-card" data-action="add-teacher">
            <div class="action-icon success">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="action-info">
                <h3>إضافة معلم</h3>
                <p>تعيين معلم جديد وربطه بالفصول والمقررات المناسبة.</p>
            </div>
            <span class="action-link">متابعة</span>
        </a>

        <a href="{{ route('attendance.daily') }}" class="action-card" data-action="take-attendance">
            <div class="action-icon info">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="action-info">
                <h3>تسجيل الحضور</h3>
                <p>تحديث حضور الطلاب اليومي ومتابعة الغيابات المتكررة.</p>
            </div>
            <span class="action-link">سجّل الآن</span>
        </a>

        <a href="{{ route('reports.index') }}" class="action-card" data-action="view-reports">
            <div class="action-icon purple">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="action-info">
                <h3>عرض التقارير</h3>
                <p>تحليلات تفاعلية حول الأداء الأكاديمي والإداري.</p>
            </div>
            <span class="action-link">عرض التفاصيل</span>
        </a>

        <a href="{{ route('classes.index') }}" class="action-card" data-action="manage-classes">
            <div class="action-icon warning">
                <i class="fas fa-school"></i>
            </div>
            <div class="action-info">
                <h3>إدارة الفصول</h3>
                <p>تنظيم الجداول الدراسية وتوزيع الطلبة على الفصول.</p>
            </div>
            <span class="action-link">تنظيم الآن</span>
        </a>

        <a href="{{ route('finance.index') }}" class="action-card" data-action="financial-overview">
            <div class="action-icon danger">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="action-info">
                <h3>إدارة الشؤون المالية</h3>
                <p>متابعة المصروفات والإيرادات مع نظرة تفصيلية للتحصيل.</p>
            </div>
            <span class="action-link">تصفح السجلات</span>
        </a>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-section">
    <!-- Students Growth Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <h3 class="chart-title">نمو أعداد الطلاب</h3>
                <p class="chart-description">معدل التسجيل الشهري ومؤشرات الاستمرارية خلال العام الحالي.</p>
            </div>
            <div class="chart-toolbar">
                <button class="chart-filter active">6 أشهر</button>
                <button class="chart-filter">سنة كاملة</button>
            </div>
        </div>
        <div class="chart-container" id="students-chart">
            <!-- Chart will be rendered by JavaScript -->
        </div>
    </div>

    <!-- Attendance Rate Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <h3 class="chart-title">معدل الحضور الأسبوعي</h3>
                <p class="chart-description">مقارنة بين الصفوف مع تحديد الفروقات البارزة في نسب الحضور.</p>
            </div>
            <div class="chart-toolbar">
                <button class="chart-filter active">6 أسابيع</button>
                <button class="chart-filter">13 أسبوع</button>
            </div>
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

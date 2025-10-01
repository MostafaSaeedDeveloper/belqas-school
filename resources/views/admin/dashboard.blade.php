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
    <div class="hero-content">
        <div class="hero-label">لوحة المتابعة اللحظية</div>
        <h2 class="hero-title">مرحباً بك في لوحة القيادة الذكية</h2>
        <p class="hero-subtitle">تتبع المؤشرات الأساسية، أدر العمليات اليومية، واطلع على كل ما يحدث في المدرسة من مكان واحد.</p>

        <div class="hero-insights">
            <div class="hero-insight">
                <span class="insight-label">الطلاب النشطون</span>
                <span class="insight-value" data-stat="students">0</span>
                <span class="insight-change positive"><i class="fas fa-arrow-trend-up"></i> +12%</span>
            </div>
            <div class="hero-insight">
                <span class="insight-label">الحضور اليوم</span>
                <span class="insight-value" data-stat="attendance">0%</span>
                <span class="insight-change neutral"><i class="fas fa-circle"></i> مستقر</span>
            </div>
            <div class="hero-insight">
                <span class="insight-label">الرسائل الجديدة</span>
                <span class="insight-value">8</span>
                <span class="insight-change positive"><i class="fas fa-envelope-open-text"></i> تمت المتابعة</span>
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
            <span class="widget-title">مؤشر الإنجاز العام</span>
            <span class="widget-date">تحديث: {{ \Carbon\Carbon::now()->translatedFormat('j F Y') }}</span>
        </div>
        <div class="widget-progress">
            <span class="progress-value">82%</span>
            <div class="progress-bar">
                <span style="width: 82%"></span>
            </div>
        </div>
        <ul class="widget-meta">
            <li><span>الخطط الأكاديمية</span><strong>مكتملة بنسبة 76%</strong></li>
            <li><span>الطلبات المعلقة</span><strong>5 بحاجة للمتابعة</strong></li>
            <li><span>الدعم الفني</span><strong>رد خلال 1 ساعة</strong></li>
        </ul>
    </div>
</div>

<section class="metrics-grid">
    <article class="metric-card metric-students" data-type="students">
        <div class="metric-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="metric-content">
            <span class="metric-label">إجمالي الطلاب</span>
            <div class="metric-value" data-stat="students">0</div>
            <div class="metric-trend positive"><i class="fas fa-arrow-up"></i> +12% من الشهر الماضي</div>
        </div>
    </article>

    <article class="metric-card metric-teachers" data-type="teachers">
        <div class="metric-icon"><i class="fas fa-chalkboard-user"></i></div>
        <div class="metric-content">
            <span class="metric-label">عدد المعلمين</span>
            <div class="metric-value" data-stat="teachers">0</div>
            <div class="metric-trend positive"><i class="fas fa-user-plus"></i> +3 معلمين جدد</div>
        </div>
    </article>

    <article class="metric-card metric-classes" data-type="classes">
        <div class="metric-icon"><i class="fas fa-school"></i></div>
        <div class="metric-content">
            <span class="metric-label">الفصول النشطة</span>
            <div class="metric-value" data-stat="classes">0</div>
            <div class="metric-trend neutral"><i class="fas fa-circle-notch"></i> بدون تغييرات</div>
        </div>
    </article>

    <article class="metric-card metric-attendance" data-type="attendance">
        <div class="metric-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="metric-content">
            <span class="metric-label">نسبة الحضور</span>
            <div class="metric-value" data-stat="attendance">0%</div>
            <div class="metric-trend positive"><i class="fas fa-arrow-trend-up"></i> +2% هذا الأسبوع</div>
        </div>
    </article>
</section>

<section class="analytics-layout">
    <div class="analytics-main">
        <div class="analytics-card">
            <div class="analytics-header">
                <div>
                    <h3 class="analytics-title">نمو أعداد الطلاب</h3>
                    <span class="analytics-subtitle">متوسط التسجيل خلال آخر 6 أشهر</span>
                </div>
                <button class="icon-btn icon-btn-light"><i class="fas fa-arrow-rotate-right"></i></button>
            </div>
            <div class="analytics-body" id="students-chart"></div>
        </div>

        <div class="analytics-card">
            <div class="analytics-header">
                <div>
                    <h3 class="analytics-title">معدل الحضور الأسبوعي</h3>
                    <span class="analytics-subtitle">إحصاءات الانضباط للمدارس</span>
                </div>
                <button class="icon-btn icon-btn-light"><i class="fas fa-ellipsis"></i></button>
            </div>
            <div class="analytics-body" id="attendance-chart"></div>
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
                    <span>رضا أولياء الأمور</span>
                    <div class="status-meter">
                        <span style="width: 68%"></span>
                    </div>
                    <strong>68%</strong>
                </li>
                <li>
                    <span>الإنجاز الأكاديمي</span>
                    <div class="status-meter">
                        <span class="positive" style="width: 84%"></span>
                    </div>
                    <strong>+6%</strong>
                </li>
                <li>
                    <span>الأنشطة اللاصفية</span>
                    <div class="status-meter">
                        <span class="warning" style="width: 44%"></span>
                    </div>
                    <strong>بحاجة لدعم</strong>
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
            <!-- Activities will be loaded by JavaScript -->
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('activity.index') }}" class="btn btn-soft-primary">
                <i class="fas fa-eye"></i>
                عرض جميع الأنشطة
            </a>
        </div>
    </div>

    <div class="engagement-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-days"></i> التقويم</h3>
        </div>
        <div class="calendar" data-calendar>
            <div class="calendar-header">
                <button class="icon-btn icon-btn-light calendar-prev" aria-label="الشهر السابق">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="calendar-month">سبتمبر 2025</div>
                <button class="icon-btn icon-btn-light calendar-next" aria-label="الشهر التالي">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <div class="calendar-grid">
                <!-- Calendar will be rendered by JavaScript -->
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('events.index') }}" class="btn btn-soft-primary btn-sm">
                <i class="fas fa-calendar-alt"></i>
                جميع الأحداث
            </a>
        </div>
    </div>

    <div class="engagement-card news-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-newspaper"></i> آخر الأخبار والإعلانات</h3>
        </div>
        <div class="news-list">
            <article class="news-item">
                <div class="news-date">
                    <span class="day">15</span>
                    <span class="month">سبتمبر</span>
                </div>
                <div class="news-content">
                    <h4>بداية العام الدراسي الجديد</h4>
                    <p>يسر إدارة المدرسة أن تعلن عن بداية العام الدراسي الجديد 2025/2026.</p>
                </div>
            </article>
            <article class="news-item">
                <div class="news-date">
                    <span class="day">12</span>
                    <span class="month">سبتمبر</span>
                </div>
                <div class="news-content">
                    <h4>اجتماع أولياء الأمور</h4>
                    <p>اجتماع عام لأولياء الأمور يوم الخميس الساعة 10 صباحاً في القاعة الكبرى.</p>
                </div>
            </article>
            <article class="news-item">
                <div class="news-date">
                    <span class="day">10</span>
                    <span class="month">سبتمبر</span>
                </div>
                <div class="news-content">
                    <h4>ورشة تدريبية للمعلمين</h4>
                    <p>ورشة حول استخدام التكنولوجيا في التعليم الحديث لجميع الكوادر التعليمية.</p>
                </div>
            </article>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* إضافات بسيطة خاصة بالصفحة */
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('واجهة لوحة التحكم الجديدة جاهزة');
    });
</script>
@endpush

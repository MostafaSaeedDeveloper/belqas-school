@extends('admin.layouts.master')

@section('title', 'لوحة التحكم - نظام إدارة مدرسة بلقاس')

@section('page-header')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'واجهة مبسطة لمتابعة أهم المؤشرات اليومية')

@section('breadcrumb')
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection
@endsection

@section('content')
<div class="dashboard-page">
    <section class="dashboard-intro">
        <div class="intro-text">
            <span class="intro-eyebrow">اليوم {{ now()->translatedFormat('d F Y') }}</span>
            <h1>مرحباً بعودتك، {{ auth()->user()?->name ?? 'مدير النظام' }}</h1>
            <p class="intro-lead">استعرض أداء المدرسة وابدأ يومك بخطوات واضحة. تم تبسيط لوحة التحكم لتركز على المؤشرات الأساسية والمهام اليومية الأهم.</p>
        </div>
        <div class="intro-actions">
            <a href="{{ route('reports.index') }}" class="intro-btn intro-btn-primary">
                <i class="fas fa-chart-line"></i>
                عرض التقارير التفصيلية
            </a>
            <a href="{{ route('events.index') }}" class="intro-btn intro-btn-light">
                <i class="fas fa-calendar"></i>
                فتح التقويم
            </a>
        </div>
    </section>

    <section class="dashboard-metrics">
        <div class="section-heading">
            <h2>المؤشرات الأساسية</h2>
            <span class="section-subtle">آخر تحديث منذ 10 دقائق</span>
        </div>
        <div class="metrics-grid">
            <article class="metric-card">
                <div class="metric-header">
                    <span>الطلاب</span>
                    <span class="metric-delta positive">
                        <i class="fas fa-arrow-up"></i>
                        12%
                    </span>
                </div>
                <div class="metric-value" data-stat="students">0</div>
                <p class="metric-subtitle">إجمالي الطلاب المسجلين</p>
            </article>

            <article class="metric-card">
                <div class="metric-header">
                    <span>المعلمون</span>
                    <span class="metric-delta neutral">
                        <i class="fas fa-equals"></i>
                        مستقر
                    </span>
                </div>
                <div class="metric-value" data-stat="teachers">0</div>
                <p class="metric-subtitle">طاقم التدريس النشط</p>
            </article>

            <article class="metric-card">
                <div class="metric-header">
                    <span>الفصول</span>
                    <span class="metric-delta positive">
                        <i class="fas fa-arrow-up"></i>
                        +3
                    </span>
                </div>
                <div class="metric-value" data-stat="classes">0</div>
                <p class="metric-subtitle">عدد الفصول العاملة</p>
            </article>

            <article class="metric-card">
                <div class="metric-header">
                    <span>الحضور</span>
                    <span class="metric-delta negative">
                        <i class="fas fa-arrow-down"></i>
                        -1%
                    </span>
                </div>
                <div class="metric-value" data-stat="attendance">0%</div>
                <p class="metric-subtitle">متوسط نسبة الحضور اليومي</p>
            </article>
        </div>
    </section>

    <section class="dashboard-panels">
        <article class="panel-card">
            <header class="panel-header">
                <div>
                    <h2 class="panel-title">تقدم التسجيل</h2>
                    <p class="panel-subtitle">مقارنة بين الأشهر الستة الأخيرة</p>
                </div>
                <span class="section-subtle">يتم التحديث تلقائياً</span>
            </header>
            <div class="panel-placeholder" id="students-chart">
                سيتم عرض المخطط عند تفعيل التكامل مع البيانات.
            </div>
        </article>

        <article class="panel-card">
            <header class="panel-header">
                <div>
                    <h2 class="panel-title">معدل الحضور</h2>
                    <p class="panel-subtitle">متابعة سريعة للفصول ذات النسب المنخفضة</p>
                </div>
                <span class="section-subtle">آخر تحديث منذ ساعة</span>
            </header>
            <div class="panel-placeholder" id="attendance-chart">
                سيتم عرض المخطط عند تفعيل التكامل مع البيانات.
            </div>
        </article>

        <article class="panel-card">
            <header class="panel-header">
                <div>
                    <h2 class="panel-title">أولويات اليوم</h2>
                    <p class="panel-subtitle">قائمة مختصرة بالمهام العاجلة</p>
                </div>
            </header>
            <div class="panel-list">
                <div class="panel-list-item">
                    <div>
                        <p class="panel-list-title">متابعة غياب طلاب الصف الثالث</p>
                        <p class="panel-list-desc">3 طلاب بحاجة لتواصل مع أولياء الأمور</p>
                    </div>
                    <span class="panel-chip">متابعة</span>
                </div>
                <div class="panel-list-item">
                    <div>
                        <p class="panel-list-title">إرسال تقرير أسبوعي للمعلمين</p>
                        <p class="panel-list-desc">جهز التقرير ليرسل نهاية اليوم</p>
                    </div>
                    <span class="panel-chip">اليوم</span>
                </div>
                <div class="panel-list-item">
                    <div>
                        <p class="panel-list-title">مراجعة طلبات تسجيل جديدة</p>
                        <p class="panel-list-desc">طلبات بانتظار الاعتماد</p>
                    </div>
                    <span class="panel-chip">5 طلبات</span>
                </div>
            </div>
        </article>
    </section>

    <section class="quick-actions-wrap">
        <div class="section-heading">
            <h2>عمليات سريعة</h2>
            <a href="{{ route('activity.index') }}">سجل النشاط</a>
        </div>
        <div class="quick-actions">
            <a href="{{ route('students.create') }}" class="quick-action">
                <strong>إضافة طالب</strong>
                <span>إدخال بيانات طالب جديد وربطه بالفصل المناسب.</span>
            </a>
            <a href="{{ route('teachers.create') }}" class="quick-action">
                <strong>تعيين معلم</strong>
                <span>تسجيل معلم جديد وتحديد المواد المكلف بها.</span>
            </a>
            <a href="{{ route('attendance.daily') }}" class="quick-action">
                <strong>تحديث الحضور</strong>
                <span>إدخال نسب الحضور اليومية لكل فصل.</span>
            </a>
            <a href="{{ route('finance.index') }}" class="quick-action">
                <strong>متابعة المالية</strong>
                <span>استعراض التحصيل والمدفوعات الحديثة.</span>
            </a>
        </div>
    </section>

    <section class="dashboard-bottom">
        <article class="activity-feed">
            <div class="section-heading">
                <h2>النشاط الأخير</h2>
                <a href="{{ route('activity.index') }}">عرض الكل</a>
            </div>
            <div class="activity-item">
                <div class="activity-body">
                    <p class="activity-title">تم اعتماد غياب أحمد علي</p>
                    <span class="activity-meta">منذ 15 دقيقة • الصف الثاني الثانوي</span>
                </div>
                <span class="panel-chip">حضور</span>
            </div>
            <div class="activity-item">
                <div class="activity-body">
                    <p class="activity-title">إضافة معلم جديد لقسم العلوم</p>
                    <span class="activity-meta">منذ ساعة • بواسطة إدارة الموارد البشرية</span>
                </div>
                <span class="panel-chip">الموارد</span>
            </div>
            <div class="activity-item">
                <div class="activity-body">
                    <p class="activity-title">إرسال تنبيه لأولياء أمور الصف الرابع</p>
                    <span class="activity-meta">منذ ساعتين • تذكير بامتحان منتصف الفصل</span>
                </div>
                <span class="panel-chip">تنبيه</span>
            </div>
        </article>

        <article class="schedule-card">
            <div class="section-heading">
                <h2>جدول اليوم</h2>
                <a href="{{ route('events.index') }}">إدارة التقويم</a>
            </div>
            <div class="schedule-list">
                <div class="schedule-item">
                    <div>
                        <strong>09:00 ص</strong>
                        <span>اجتماع فريق المرحلة الابتدائية</span>
                    </div>
                    <span>قاعة الاجتماعات</span>
                </div>
                <div class="schedule-item">
                    <div>
                        <strong>11:30 ص</strong>
                        <span>جلسة تدريب للمعلمين الجدد</span>
                    </div>
                    <span>مركز التطوير</span>
                </div>
                <div class="schedule-item">
                    <div>
                        <strong>01:00 م</strong>
                        <span>متابعة حالات السداد المتأخرة</span>
                    </div>
                    <span>الإدارة المالية</span>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('لوحة التحكم ذات التصميم البسيط جاهزة');
    });
</script>
@endpush

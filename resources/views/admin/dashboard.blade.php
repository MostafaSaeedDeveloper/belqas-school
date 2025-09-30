@extends('admin.layouts.master')

@section('title', 'لوحة التحكم - نظام إدارة مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'لوحة التحكم')
    @section('page-subtitle', 'متابعة شاملة لأداء المدرسة ومؤشراتها اليومية')
    @section('breadcrumb')
        <li class="breadcrumb-item active">لوحة التحكم</li>
    @endsection
@endsection

@section('content')
<div class="dashboard-shell">
    <header class="dashboard-hero">
        <div class="hero-details">
            <p class="hero-date">{{ now()->translatedFormat('l d F Y') }}</p>
            <h1>مرحباً بعودتك، {{ auth()->user()?->name ?? 'مدير النظام' }}</h1>
            <p class="hero-description">
                صممت لوحة التحكم الجديدة لتمنحك رؤية واضحة حول الأداء الأكاديمي والتشغيلي، مع إمكانية الوصول
                الفوري لأهم الإجراءات اليومية.
            </p>
            <div class="hero-actions">
                <a href="{{ route('reports.index') }}" class="hero-action hero-action-primary">
                    <i class="fas fa-poll"></i>
                    استعراض تقارير الأداء
                </a>
                <a href="{{ route('events.index') }}" class="hero-action hero-action-secondary">
                    <i class="fas fa-calendar-week"></i>
                    مراجعة جدول الفعاليات
                </a>
            </div>
            <div class="hero-filters">
                <span class="filters-label">نطاق العرض</span>
                <div class="filters-chips">
                    <button type="button" class="filter-chip is-active">هذا الأسبوع</button>
                    <button type="button" class="filter-chip">هذا الشهر</button>
                    <button type="button" class="filter-chip">ربع سنوي</button>
                </div>
            </div>
        </div>
        <div class="hero-summary">
            <div class="hero-summary-card">
                <span class="hero-summary-label">الطلاب المسجلون</span>
                <strong class="hero-summary-value" data-stat="students">0</strong>
                <small>+12% خلال هذا الشهر</small>
            </div>
            <div class="hero-summary-card">
                <span class="hero-summary-label">معدل الحضور اليومي</span>
                <strong class="hero-summary-value" data-stat="attendance">0%</strong>
                <small>مقارنة بالأسبوع الماضي</small>
            </div>
            <div class="hero-summary-card">
                <span class="hero-summary-label">طلبات التسجيل الجديدة</span>
                <strong class="hero-summary-value">18</strong>
                <small>4 بانتظار الاعتماد</small>
            </div>
        </div>
    </header>

    <section class="dashboard-overview">
        <div class="section-heading">
            <div>
                <h2>نظرة سريعة على المؤشرات</h2>
                <p class="section-subtitle">قياس مستمر للأداء الأكاديمي والتشغيلي في المدرسة</p>
            </div>
            <button type="button">تحديث البيانات</button>
        </div>
        <div class="overview-metrics">
            <article class="metric-card">
                <header class="metric-header">
                    <span>الطلاب</span>
                    <span class="metric-delta metric-delta-positive">
                        <i class="fas fa-arrow-trend-up"></i>
                        12%
                    </span>
                </header>
                <div class="metric-value" data-stat="students">0</div>
                <p class="metric-footnote">إجمالي الطلاب المسجلين خلال العام الدراسي الحالي</p>
            </article>
            <article class="metric-card">
                <header class="metric-header">
                    <span>المعلمون</span>
                    <span class="metric-delta metric-delta-neutral">
                        <i class="fas fa-circle-notch"></i>
                        مستقر
                    </span>
                </header>
                <div class="metric-value" data-stat="teachers">0</div>
                <p class="metric-footnote">أعضاء هيئة التدريس النشطون ضمن الجداول الحالية</p>
            </article>
            <article class="metric-card">
                <header class="metric-header">
                    <span>الفصول الدراسية</span>
                    <span class="metric-delta metric-delta-positive">
                        <i class="fas fa-plus"></i>
                        +3
                    </span>
                </header>
                <div class="metric-value" data-stat="classes">0</div>
                <p class="metric-footnote">الفصول المفتوحة حالياً وجاهزية القاعات التعليمية</p>
            </article>
            <article class="metric-card">
                <header class="metric-header">
                    <span>نسبة الحضور</span>
                    <span class="metric-delta metric-delta-negative">
                        <i class="fas fa-arrow-trend-down"></i>
                        -1%
                    </span>
                </header>
                <div class="metric-value" data-stat="attendance">0%</div>
                <p class="metric-footnote">متوسط الحضور اليومي على مستوى المدرسة</p>
            </article>
        </div>
    </section>

    <section class="dashboard-main-grid">
        <div class="dashboard-primary">
            <article class="card insight-card">
                <header class="card-header">
                    <div>
                        <h3>تطور التسجيل خلال العام</h3>
                        <p>قراءة شهرية لأعداد الطلاب الجدد عبر الأقسام</p>
                    </div>
                    <button type="button">تصدير</button>
                </header>
                <div class="insight-placeholder" id="enrollment-chart">
                    سيتم إظهار مخطط تفاعلي عند تفعيل التكامل مع البيانات الحية.
                </div>
            </article>

            <article class="card performance-card">
                <header class="card-header">
                    <div>
                        <h3>أداء الأقسام الأكاديمية</h3>
                        <p>مقارنة بين نسب الإنجاز وخطط المتابعة لكل قسم</p>
                    </div>
                    <span class="badge badge-success">محدّث الآن</span>
                </header>
                <div class="progress-rows">
                    <div class="progress-row">
                        <span>المرحلة الابتدائية</span>
                        <div class="progress-track">
                            <div class="progress-fill" style="width: 78%"></div>
                        </div>
                        <span class="progress-value">78%</span>
                    </div>
                    <div class="progress-row">
                        <span>المرحلة الإعدادية</span>
                        <div class="progress-track">
                            <div class="progress-fill" style="width: 64%"></div>
                        </div>
                        <span class="progress-value">64%</span>
                    </div>
                    <div class="progress-row">
                        <span>المرحلة الثانوية</span>
                        <div class="progress-track">
                            <div class="progress-fill" style="width: 82%"></div>
                        </div>
                        <span class="progress-value">82%</span>
                    </div>
                </div>
            </article>

            <article class="card tasks-card">
                <header class="card-header">
                    <div>
                        <h3>متابعة المهام التنفيذية</h3>
                        <p>خطوات مقترحة لضمان استقرار العمليات اليومية</p>
                    </div>
                    <a href="{{ route('tasks.index') }}">عرض كل المهام</a>
                </header>
                <ul class="tasks-list">
                    <li class="tasks-item">
                        <div>
                            <h4>مراجعة حالات الغياب المرتفعة</h4>
                            <p>راجع تقارير الحضور لفصول الصف الثالث المتوسطة ونسّق مع أولياء الأمور.</p>
                        </div>
                        <span class="badge">أولوية عالية</span>
                    </li>
                    <li class="tasks-item">
                        <div>
                            <h4>تحضير تقرير الأداء الأسبوعي</h4>
                            <p>جمع مؤشرات الأقسام الأكاديمية وإرسالها إلى الإدارة العليا قبل نهاية اليوم.</p>
                        </div>
                        <span class="badge badge-info">قيد المتابعة</span>
                    </li>
                    <li class="tasks-item">
                        <div>
                            <h4>اعتماد طلبات التسجيل الجديدة</h4>
                            <p>تحقق من اكتمال المستندات وخصص الفصول المناسبة للطلاب الجدد.</p>
                        </div>
                        <span class="badge badge-warning">5 طلبات</span>
                    </li>
                </ul>
            </article>
        </div>

        <aside class="dashboard-secondary">
            <article class="card schedule-card">
                <header class="card-header">
                    <div>
                        <h3>جدول اليوم</h3>
                        <p>أبرز الاجتماعات والفعاليات الأكاديمية</p>
                    </div>
                    <a href="{{ route('events.index') }}">إدارة التقويم</a>
                </header>
                <div class="schedule-list">
                    <div class="schedule-item">
                        <div>
                            <strong>09:00 ص</strong>
                            <span>اجتماع متابعة المرحلة الابتدائية</span>
                        </div>
                        <span>قاعة الاجتماعات</span>
                    </div>
                    <div class="schedule-item">
                        <div>
                            <strong>11:30 ص</strong>
                            <span>ورشة تدريبية للمعلمين الجدد</span>
                        </div>
                        <span>مركز التطوير</span>
                    </div>
                    <div class="schedule-item">
                        <div>
                            <strong>01:00 م</strong>
                            <span>مراجعة حالات السداد المتأخرة</span>
                        </div>
                        <span>الإدارة المالية</span>
                    </div>
                </div>
            </article>

            <article class="card announcements-card">
                <header class="card-header">
                    <div>
                        <h3>آخر التحديثات</h3>
                        <p>رسائل داخلية وتوصيات من الأقسام المختلفة</p>
                    </div>
                    <a href="{{ route('activity.index') }}">عرض الكل</a>
                </header>
                <ul class="announcement-list">
                    <li>
                        <h4>اعتماد غياب أحمد علي</h4>
                        <span>منذ 15 دقيقة • الصف الثاني الثانوي</span>
                    </li>
                    <li>
                        <h4>إضافة معلم لقسم العلوم</h4>
                        <span>منذ ساعة • إدارة الموارد البشرية</span>
                    </li>
                    <li>
                        <h4>تنبيه لأولياء أمور الصف الرابع</h4>
                        <span>منذ ساعتين • تذكير بامتحان منتصف الفصل</span>
                    </li>
                </ul>
            </article>

            <article class="card support-card">
                <header class="card-header">
                    <div>
                        <h3>مركز الدعم السريع</h3>
                        <p>تواصل مع الفرق المختصة لمتابعة الطلبات العاجلة</p>
                    </div>
                </header>
                <div class="support-grid">
                    <div class="support-tile">
                        <span class="support-title">الموارد البشرية</span>
                        <p>متابعة تعيين المعلمين والحضور.</p>
                        <a href="mailto:hr@belqas-school.edu">hr@belqas-school.edu</a>
                    </div>
                    <div class="support-tile">
                        <span class="support-title">الدعم الفني</span>
                        <p>الإبلاغ عن مشكلات المنصة أو حسابات المستخدمين.</p>
                        <a href="mailto:support@belqas-school.edu">support@belqas-school.edu</a>
                    </div>
                </div>
            </article>
        </aside>
    </section>

    <section class="quick-actions-section">
        <div class="section-heading">
            <div>
                <h2>إجراءات سريعة</h2>
                <p class="section-subtitle">إبدأ عملك اليومي بخطوة واحدة</p>
            </div>
            <a href="{{ route('activity.index') }}">سجل النشاط</a>
        </div>
        <div class="quick-actions-grid">
            <a href="{{ route('students.create') }}" class="quick-action">
                <div class="quick-icon"><i class="fas fa-user-plus"></i></div>
                <div>
                    <strong>إضافة طالب جديد</strong>
                    <span>إدخال بيانات طالب وربطها بالفصل المناسب خلال دقائق.</span>
                </div>
            </a>
            <a href="{{ route('teachers.create') }}" class="quick-action">
                <div class="quick-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>
                    <strong>تعيين معلم</strong>
                    <span>تحديد المواد المكلف بها المعلم وإشعاره بالجدول.</span>
                </div>
            </a>
            <a href="{{ route('attendance.daily') }}" class="quick-action">
                <div class="quick-icon"><i class="fas fa-clipboard-check"></i></div>
                <div>
                    <strong>تحديث بيانات الحضور</strong>
                    <span>إدخال نسب الحضور اليومية وتوثيق حالات الغياب المتكررة.</span>
                </div>
            </a>
            <a href="{{ route('finance.index') }}" class="quick-action">
                <div class="quick-icon"><i class="fas fa-coins"></i></div>
                <div>
                    <strong>متابعة الحالة المالية</strong>
                    <span>استعرض التحصيل والمدفوعات وخطط السداد القادمة.</span>
                </div>
            </a>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('تم تهيئة لوحة التحكم بالتصميم الاحترافي الجديد');
    });
</script>
@endpush

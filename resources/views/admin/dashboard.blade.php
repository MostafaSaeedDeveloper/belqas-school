@extends('admin.layouts.master')

@section('title', 'لوحة التحكم - نظام إدارة مدرسة بلقاس')

@section('page-header')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'نظرة تفاعلية على أداء المدرسة')

@section('breadcrumb')
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection
@endsection

@section('content')
<div class="dashboard-wrapper">
    <div class="dashboard-main">
        <section class="welcome-card">
            <div class="welcome-info">
                <span class="welcome-badge">
                    <i class="fas fa-sun"></i>
                    مرحباً بعودتك
                </span>
                <h1 class="welcome-title">كل ما تحتاجه لإدارة المدرسة في مكان واحد</h1>
                <p class="welcome-subtitle">استعرض مؤشرات الأداء، الأنشطة، والمهام اليومية بخطوات أقل وتصميم واضح.</p>
                <div class="welcome-meta">
                    <div class="meta-item">
                        <span class="meta-label">آخر تحديث</span>
                        <span class="meta-value">اليوم 09:30 ص</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">حالة الأنظمة</span>
                        <span class="meta-pill success">
                            <i class="fas fa-check-circle"></i>
                            مستقرة
                        </span>
                    </div>
                </div>
            </div>
            <div class="welcome-progress">
                <div class="progress-circle" aria-hidden="true">
                    <svg viewBox="0 0 120 120">
                        <defs>
                            <linearGradient id="progress-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#4f46e5" />
                                <stop offset="100%" stop-color="#0ea5e9" />
                            </linearGradient>
                        </defs>
                        <circle class="progress-track" cx="60" cy="60" r="52"></circle>
                        <circle class="progress-bar" cx="60" cy="60" r="52"></circle>
                    </svg>
                    <span class="progress-value">82%</span>
                </div>
                <div class="progress-details">
                    <span class="progress-label">نسبة الإنجاز العامة</span>
                    <strong class="progress-score">82%</strong>
                    <span class="progress-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        +6% عن الشهر الماضي
                    </span>
                    <p class="progress-caption">إغلاق المهام الإدارية الرئيسة والتحضير للأنشطة القادمة يسيران ضمن الخطة.</p>
                </div>
            </div>
        </section>

        <section class="stat-grid">
            <article class="stat-card">
                <div class="stat-icon students"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-content">
                    <span class="stat-label">إجمالي الطلاب</span>
                    <strong class="stat-value" data-stat="students">0</strong>
                    <span class="stat-trend neutral">
                        <i class="fas fa-chart-line"></i>
                        مستقر خلال الأسبوع
                    </span>
                </div>
            </article>
            <article class="stat-card">
                <div class="stat-icon teachers"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-content">
                    <span class="stat-label">عدد المعلمين</span>
                    <strong class="stat-value" data-stat="teachers">0</strong>
                    <span class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        +3 خلال هذا الشهر
                    </span>
                </div>
            </article>
            <article class="stat-card">
                <div class="stat-icon attendance"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-content">
                    <span class="stat-label">معدل الحضور</span>
                    <strong class="stat-value" data-stat="attendance">0%</strong>
                    <span class="stat-trend positive">
                        <i class="fas fa-level-up-alt"></i>
                        زيادة 2%
                    </span>
                </div>
            </article>
            <article class="stat-card">
                <div class="stat-icon finance"><i class="fas fa-wallet"></i></div>
                <div class="stat-content">
                    <span class="stat-label">الالتزامات المالية</span>
                    <strong class="stat-value">₺ 0</strong>
                    <span class="stat-trend warning">
                        <i class="fas fa-exclamation-circle"></i>
                        متبقي متابعات
                    </span>
                </div>
            </article>
        </section>

        <section class="overview-grid">
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <h2>نمو الطلاب</h2>
                        <span class="panel-subtitle">آخر 6 أشهر</span>
                    </div>
                    <button class="panel-action">
                        <i class="fas fa-download"></i>
                        تصدير
                    </button>
                </div>
                <div class="panel-body chart" id="students-chart">
                    <span class="chart-placeholder">مخطط الطلاب سيظهر هنا</span>
                </div>
            </div>
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <h2>مؤشر الحضور اليومي</h2>
                        <span class="panel-subtitle">المعدل الأسبوعي</span>
                    </div>
                    <span class="status-chip positive">
                        <i class="fas fa-arrow-up"></i>
                        اتجاه تصاعدي
                    </span>
                </div>
                <ul class="panel-list">
                    <li>
                        <span>أعلى حضور</span>
                        <strong>الثلاثاء - 96%</strong>
                    </li>
                    <li>
                        <span>أقل حضور</span>
                        <strong>الخميس - 88%</strong>
                    </li>
                    <li>
                        <span>غياب متكرر</span>
                        <strong>5 طلاب بحاجة متابعة</strong>
                    </li>
                </ul>
                <a href="{{ route('attendance.daily') }}" class="panel-link">
                    عرض تفاصيل الحضور
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>
        </section>

        <section class="timeline-grid">
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <h2>آخر الأنشطة</h2>
                        <span class="panel-subtitle">متابعة حية لأحداث اليوم</span>
                    </div>
                    <a href="{{ route('activity.index') }}" class="panel-action subtle">
                        عرض الكل
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </div>
                <ul class="activity-list">
                    <li>
                        <div class="activity-icon success"><i class="fas fa-user-check"></i></div>
                        <div class="activity-details">
                            <strong>تسجيل حضور الصف الثالث</strong>
                            <span>تم تحديث الحضور منذ 5 دقائق</span>
                        </div>
                    </li>
                    <li>
                        <div class="activity-icon info"><i class="fas fa-bullhorn"></i></div>
                        <div class="activity-details">
                            <strong>إرسال إشعار لأولياء الأمور</strong>
                            <span>تمت العملية بنجاح</span>
                        </div>
                    </li>
                    <li>
                        <div class="activity-icon warning"><i class="fas fa-exclamation"></i></div>
                        <div class="activity-details">
                            <strong>متابعة طلب صيانة معملي</strong>
                            <span>بانتظار التأكيد</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <h2>المهام السريعة</h2>
                        <span class="panel-subtitle">مهام مقترحة لهذا الأسبوع</span>
                    </div>
                </div>
                <ul class="task-list">
                    <li>
                        <label>
                            <input type="checkbox" />
                            تحديث بيانات الطلاب المستجدين
                        </label>
                        <span class="task-badge">اليوم</span>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" />
                            مراجعة خطة الأنشطة الفصلية
                        </label>
                        <span class="task-badge warning">غداً</span>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" checked disabled />
                            إرسال كشوف الدرجات الأولية
                        </label>
                        <span class="task-badge muted">مكتمل</span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="events-news-grid">
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <h2>الأحداث القادمة</h2>
                        <span class="panel-subtitle">الأسبوعان القادمان</span>
                    </div>
                    <a href="{{ route('events.index') }}" class="panel-action subtle">
                        التقويم الكامل
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </div>
                <table class="events-table">
                    <tbody>
                        <tr>
                            <td class="event-date">
                                <span class="day">18</span>
                                <span class="month">سبتمبر</span>
                            </td>
                            <td class="event-info">
                                <strong>لقاء تعريف أولياء الأمور</strong>
                                <span>الساعة 11:00 ص - القاعة الكبرى</span>
                            </td>
                            <td class="event-status">
                                <span class="status-pill info">مجدول</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="event-date">
                                <span class="day">21</span>
                                <span class="month">سبتمبر</span>
                            </td>
                            <td class="event-info">
                                <strong>رحلة علمية للصف الرابع</strong>
                                <span>زيارة متحف العلوم</span>
                            </td>
                            <td class="event-status">
                                <span class="status-pill success">جاهز</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="event-date">
                                <span class="day">24</span>
                                <span class="month">سبتمبر</span>
                            </td>
                            <td class="event-info">
                                <strong>ورشة تدريب للمعلمين</strong>
                                <span>التقنيات الحديثة في التدريس</span>
                            </td>
                            <td class="event-status">
                                <span class="status-pill warning">تأكيد مطلوب</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <h2>أحدث الإعلانات</h2>
                        <span class="panel-subtitle">إشعارات قصيرة</span>
                    </div>
                    <a href="{{ route('reports.index') }}" class="panel-action subtle">
                        مركز الرسائل
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </div>
                <ul class="announcement-list">
                    <li>
                        <strong>إطلاق منصة الواجبات المنزلية الجديدة</strong>
                        <span>متاحة لجميع المراحل اعتباراً من اليوم.</span>
                    </li>
                    <li>
                        <strong>تحديث نظام الحضور بالبصمة</strong>
                        <span>يرجى إبلاغ الطلاب بالتسجيل خلال هذا الأسبوع.</span>
                    </li>
                    <li>
                        <strong>موعد تسليم تقارير الأداء</strong>
                        <span>آخر موعد هو 25 سبتمبر لتقارير المرحلة الإعدادية.</span>
                    </li>
                </ul>
            </div>
        </section>
    </div>

    <aside class="dashboard-aside">
        <div class="aside-card quick-actions">
            <div class="aside-header">
                <h3>الوصول السريع</h3>
                <span>عمليات يومية</span>
            </div>
            <div class="aside-actions">
                <a href="{{ route('students.create') }}">
                    <i class="fas fa-user-plus"></i>
                    إضافة طالب
                </a>
                <a href="{{ route('teachers.create') }}">
                    <i class="fas fa-id-badge"></i>
                    تسجيل معلم
                </a>
                <a href="{{ route('classes.index') }}">
                    <i class="fas fa-layer-group"></i>
                    إدارة الفصول
                </a>
                <a href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-pie"></i>
                    التقارير الذكية
                </a>
            </div>
        </div>

        <div class="aside-card status">
            <div class="aside-header">
                <h3>ملخص اليوم</h3>
                <span>أرقام سريعة</span>
            </div>
            <ul class="status-list">
                <li>
                    <span>طلبات القبول الجديدة</span>
                    <strong class="positive">+8</strong>
                </li>
                <li>
                    <span>تنبيهات عاجلة</span>
                    <strong class="warning">3</strong>
                </li>
                <li>
                    <span>رسائل غير مقروءة</span>
                    <strong>14</strong>
                </li>
                <li>
                    <span>اجتماعات اليوم</span>
                    <strong>2</strong>
                </li>
            </ul>
        </div>

        <div class="aside-card support">
            <div class="aside-header">
                <h3>الدعم والمساعدة</h3>
                <span>كيف يمكننا خدمتك؟</span>
            </div>
            <p>تواصل مع فريق الدعم الفني أو تصفح قاعدة المعرفة لحلول فورية.</p>
            <a href="mailto:support@belqas-school.com" class="support-btn">
                <i class="fas fa-life-ring"></i>
                طلب مساعدة
            </a>
        </div>
    </aside>
</div>
@endsection

@push('styles')
<style>
:root {
    --dashboard-bg: #f4f6fb;
    --card-bg: #ffffff;
    --card-border: #e6e9f4;
    --primary: #4f46e5;
    --primary-soft: rgba(79, 70, 229, 0.12);
    --success: #16a34a;
    --warning: #f97316;
    --info: #0ea5e9;
    --text-main: #1f2937;
    --text-muted: #6b7280;
    --radius-lg: 1.5rem;
    --radius-md: 1rem;
    --shadow-sm: 0 8px 20px rgba(79, 70, 229, 0.08);
    --shadow-lg: 0 24px 48px rgba(15, 23, 42, 0.08);
}

.dashboard-wrapper {
    display: grid;
    gap: 2rem;
    background: var(--dashboard-bg);
    padding: 1rem 0 3rem;
}

@media (min-width: 1200px) {
    .dashboard-wrapper {
        grid-template-columns: minmax(0, 1fr) 340px;
        align-items: start;
    }
}

.dashboard-main {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.welcome-card {
    display: grid;
    gap: 2rem;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.12), rgba(14, 165, 233, 0.12));
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    box-shadow: var(--shadow-lg);
}

@media (min-width: 992px) {
    .welcome-card {
        grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
        align-items: center;
    }
}

.welcome-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.9rem;
    border-radius: 999px;
    background: rgba(79, 70, 229, 0.15);
    color: var(--primary);
    font-weight: 600;
    font-size: 0.85rem;
}

.welcome-title {
    font-size: 2rem;
    margin: 1rem 0 0.5rem;
    color: var(--text-main);
    line-height: 1.5;
}

.welcome-subtitle {
    margin: 0 0 1.5rem;
    color: var(--text-muted);
    line-height: 1.8;
}

.welcome-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.meta-item {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    min-width: 140px;
}

.meta-label {
    color: var(--text-muted);
    font-size: 0.85rem;
}

.meta-value {
    font-weight: 600;
    color: var(--text-main);
}

.meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    font-size: 0.85rem;
}

.meta-pill.success {
    background: rgba(22, 163, 74, 0.12);
    color: var(--success);
}

.welcome-progress {
    display: grid;
    gap: 1.25rem;
    justify-items: center;
    text-align: center;
}

.progress-circle {
    position: relative;
    width: 180px;
    height: 180px;
}

.progress-circle svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.progress-track {
    fill: none;
    stroke: rgba(79, 70, 229, 0.15);
    stroke-width: 12;
}

.progress-bar {
    fill: none;
    stroke: url(#progress-gradient);
    stroke-width: 12;
    stroke-linecap: round;
    stroke-dasharray: 326;
    stroke-dashoffset: calc(326 - (326 * 82) / 100);
}

.progress-value {
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    font-size: 1.9rem;
    font-weight: 700;
    color: var(--primary);
}

.progress-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    max-width: 260px;
}

.progress-label {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.progress-score {
    font-size: 2rem;
    color: var(--text-main);
}

.progress-trend {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.85rem;
    font-weight: 600;
}

.progress-trend.positive {
    color: var(--success);
}

.progress-caption {
    margin: 0;
    color: var(--text-muted);
    line-height: 1.6;
}

.stat-grid {
    display: grid;
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (min-width: 1200px) {
    .stat-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

.stat-card {
    display: flex;
    gap: 1.25rem;
    align-items: center;
    background: var(--card-bg);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    border: 1px solid var(--card-border);
    box-shadow: var(--shadow-sm);
}

.stat-icon {
    width: 54px;
    height: 54px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    font-size: 1.4rem;
    color: #fff;
}

.stat-icon.students { background: linear-gradient(135deg, #6366f1, #4338ca); }
.stat-icon.teachers { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
.stat-icon.attendance { background: linear-gradient(135deg, #22c55e, #16a34a); }
.stat-icon.finance { background: linear-gradient(135deg, #f97316, #ea580c); }

.stat-content {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.stat-label {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.stat-value {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text-main);
}

.stat-trend {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.85rem;
    font-weight: 600;
}

.stat-trend.positive { color: var(--success); }
.stat-trend.warning { color: var(--warning); }
.stat-trend.neutral { color: var(--text-muted); }

.overview-grid {
    display: grid;
    gap: 1.5rem;
}

@media (min-width: 992px) {
    .overview-grid {
        grid-template-columns: 2fr 1.2fr;
        align-items: stretch;
    }
}

.panel-card {
    background: var(--card-bg);
    border-radius: var(--radius-md);
    border: 1px solid var(--card-border);
    padding: 1.75rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    box-shadow: var(--shadow-sm);
    height: 100%;
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.panel-header h2 {
    margin: 0;
    font-size: 1.2rem;
    color: var(--text-main);
}

.panel-subtitle {
    color: var(--text-muted);
    font-size: 0.85rem;
}

.panel-action {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 0.85rem;
    border-radius: 999px;
    border: none;
    background: var(--primary-soft);
    color: var(--primary);
    font-size: 0.85rem;
    cursor: pointer;
}

.panel-action.subtle {
    background: transparent;
    color: var(--text-muted);
    font-weight: 600;
    text-decoration: none;
}

.panel-action.subtle:hover {
    color: var(--primary);
}

.status-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-chip.positive {
    background: rgba(22, 163, 74, 0.12);
    color: var(--success);
}

.panel-body.chart {
    min-height: 220px;
    border-radius: 1rem;
    border: 1px dashed var(--card-border);
    display: grid;
    place-items: center;
    color: var(--text-muted);
    font-size: 0.95rem;
}

.chart-placeholder {
    color: var(--text-muted);
}

.panel-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.panel-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 0.65rem;
    border-bottom: 1px dashed var(--card-border);
    font-size: 0.95rem;
    color: var(--text-muted);
}

.panel-list li strong {
    color: var(--text-main);
}

.panel-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}

.timeline-grid {
    display: grid;
    gap: 1.5rem;
}

@media (min-width: 992px) {
    .timeline-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

.activity-list,
.task-list,
.announcement-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-list li {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    background: rgba(79, 70, 229, 0.06);
}

.activity-icon {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    color: #fff;
}

.activity-icon.success { background: linear-gradient(135deg, #22c55e, #16a34a); }
.activity-icon.info { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
.activity-icon.warning { background: linear-gradient(135deg, #f97316, #ea580c); }

.activity-details strong {
    color: var(--text-main);
    display: block;
    margin-bottom: 0.25rem;
}

.activity-details span {
    color: var(--text-muted);
    font-size: 0.85rem;
}

.task-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 1rem;
    border-radius: 1rem;
    border: 1px dashed var(--card-border);
}

.task-list label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    color: var(--text-main);
}

.task-list input[type="checkbox"] {
    width: 18px;
    height: 18px;
    border-radius: 4px;
    border: 2px solid var(--primary);
}

.task-badge {
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-size: 0.75rem;
    background: rgba(79, 70, 229, 0.1);
    color: var(--primary);
}

.task-badge.warning {
    background: rgba(249, 115, 22, 0.12);
    color: var(--warning);
}

.task-badge.muted {
    background: rgba(107, 114, 128, 0.12);
    color: var(--text-muted);
}

.events-news-grid {
    display: grid;
    gap: 1.5rem;
}

@media (min-width: 992px) {
    .events-news-grid {
        grid-template-columns: 1.4fr 1fr;
    }
}

.events-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 0.75rem;
}

.events-table tr {
    background: rgba(79, 70, 229, 0.05);
    border-radius: 1rem;
}

.events-table td {
    padding: 0.9rem 1rem;
    vertical-align: middle;
}

.events-table tr td:first-child {
    border-radius: 1rem 0 0 1rem;
}

.events-table tr td:last-child {
    border-radius: 0 1rem 1rem 0;
    text-align: left;
}

.event-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 70px;
    color: var(--primary);
    font-weight: 700;
}

.event-date .month {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.event-info strong {
    display: block;
    color: var(--text-main);
}

.event-info span {
    color: var(--text-muted);
    font-size: 0.85rem;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-pill.info {
    background: rgba(14, 165, 233, 0.12);
    color: var(--info);
}

.status-pill.success {
    background: rgba(22, 163, 74, 0.12);
    color: var(--success);
}

.status-pill.warning {
    background: rgba(249, 115, 22, 0.12);
    color: var(--warning);
}

.announcement-list li {
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    background: rgba(15, 23, 42, 0.04);
}

.announcement-list strong {
    display: block;
    color: var(--text-main);
    margin-bottom: 0.35rem;
}

.announcement-list span {
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.6;
}

.dashboard-aside {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.aside-card {
    background: var(--card-bg);
    border-radius: var(--radius-md);
    border: 1px solid var(--card-border);
    padding: 1.75rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.aside-header h3 {
    margin: 0;
    color: var(--text-main);
    font-size: 1.1rem;
}

.aside-header span {
    color: var(--text-muted);
    font-size: 0.85rem;
}

.aside-actions {
    display: grid;
    gap: 0.75rem;
}

.aside-actions a {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.65rem 0.9rem;
    border-radius: 0.9rem;
    background: rgba(79, 70, 229, 0.08);
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}

.aside-actions a:hover {
    background: rgba(79, 70, 229, 0.15);
}

.status-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-list li {
    display: flex;
    justify-content: space-between;
    color: var(--text-muted);
    font-size: 0.95rem;
}

.status-list strong {
    color: var(--text-main);
}

.status-list .positive { color: var(--success); }
.status-list .warning { color: var(--warning); }

.support p {
    margin: 0;
    color: var(--text-muted);
    line-height: 1.6;
}

.support-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    border-radius: 999px;
    background: var(--primary);
    color: #fff;
    font-weight: 600;
    text-decoration: none;
}

@media (max-width: 1199px) {
    .dashboard-wrapper {
        grid-template-columns: minmax(0, 1fr);
    }

    .dashboard-aside {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .dashboard-aside > * {
        flex: 1 1 320px;
    }
}

@media (max-width: 768px) {
    .welcome-card {
        padding: 1.75rem;
    }

    .stat-card,
    .panel-card,
    .aside-card {
        padding: 1.5rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('لوحة التحكم مُحدثة بالتصميم الجديد');
    });
</script>
@endpush

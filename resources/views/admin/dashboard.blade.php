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
<div class="stats-grid">
    <div class="stat-card" data-type="students">
        <div class="stat-card-header">
            <div class="stat-icon students">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-number" data-stat="students">{{ number_format($stats['students']) }}</div>
            <div class="stat-label">إجمالي الطلاب</div>
        </div>
    </div>

    <div class="stat-card" data-type="teachers">
        <div class="stat-card-header">
            <div class="stat-icon teachers">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-number" data-stat="teachers">{{ number_format($stats['teachers']) }}</div>
            <div class="stat-label">المعلمون المسجلون</div>
        </div>
    </div>

    <div class="stat-card" data-type="classes">
        <div class="stat-card-header">
            <div class="stat-icon classes">
                <i class="fas fa-school"></i>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-number" data-stat="classes">{{ number_format($stats['classrooms']) }}</div>
            <div class="stat-label">الفصول الدراسية النشطة</div>
        </div>
    </div>

    <div class="stat-card" data-type="assessments">
        <div class="stat-card-header">
            <div class="stat-icon attendance">
                <i class="fas fa-file-signature"></i>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-number" data-stat="assessments">{{ number_format($stats['assessments']) }}</div>
            <div class="stat-label">التقييمات المسجلة</div>
        </div>
    </div>

    <div class="stat-card" data-type="grades">
        <div class="stat-card-header">
            <div class="stat-icon grades">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-number" data-stat="grades">{{ number_format($stats['grades_recorded']) }}</div>
            <div class="stat-label">الدرجات المدخلة</div>
        </div>
    </div>
</div>

<div class="quick-actions-section">
    <h2 class="section-title">
        <i class="fas fa-bolt"></i>
        العمليات السريعة
    </h2>
    <div class="actions-grid">
        <a href="{{ route('students.index') }}" class="action-btn" data-action="manage-students">
            <i class="fas fa-user-graduate"></i>
            <span>إدارة الطلاب</span>
        </a>

        <a href="{{ route('teachers.index') }}" class="action-btn" data-action="manage-teachers">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>إدارة المعلمين</span>
        </a>

        <a href="{{ route('classrooms.index') }}" class="action-btn" data-action="manage-classes">
            <i class="fas fa-school"></i>
            <span>إدارة الفصول</span>
        </a>

        <a href="{{ route('subjects.index') }}" class="action-btn" data-action="manage-subjects">
            <i class="fas fa-book-open"></i>
            <span>المواد الدراسية</span>
        </a>

        <a href="{{ route('assessments.index') }}" class="action-btn" data-action="manage-assessments">
            <i class="fas fa-file-signature"></i>
            <span>التقييمات والامتحانات</span>
        </a>

        <a href="{{ route('grades.index') }}" class="action-btn" data-action="manage-grades">
            <i class="fas fa-star"></i>
            <span>سجل الدرجات</span>
        </a>

        <a href="{{ route('attendance.index') }}" class="action-btn" data-action="manage-attendance">
            <i class="fas fa-calendar-check"></i>
            <span>الحضور اليومي</span>
        </a>
    </div>
</div>

<div class="data-panels">
    <div class="data-panel">
        <div class="panel-header">
            <h3>
                <i class="fas fa-user-clock"></i>
                أحدث الطلاب المسجلين
            </h3>
        </div>
        <div class="panel-body">
            @if($recentStudents->isEmpty())
                <p class="text-muted">لا توجد بيانات طلاب حديثة.</p>
            @else
                <ul class="recent-list">
                    @foreach($recentStudents as $student)
                        <li>
                            <div>
                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                <small class="d-block text-muted">تم التسجيل في {{ optional($student->created_at)->format('Y-m-d') }}</small>
                            </div>
                            <span class="badge">{{ optional($student->classroom)->name ?? 'بدون فصل' }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="data-panel">
        <div class="panel-header">
            <h3>
                <i class="fas fa-star"></i>
                أحدث الدرجات المسجلة
            </h3>
        </div>
        <div class="panel-body">
            @if($recentGrades->isEmpty())
                <p class="text-muted">لا توجد درجات جديدة حتى الآن.</p>
            @else
                <ul class="recent-list">
                    @foreach($recentGrades as $grade)
                        <li>
                            <div>
                                <strong>{{ $grade->enrollment->student->first_name }} {{ $grade->enrollment->student->last_name }}</strong>
                                <small class="d-block text-muted">{{ $grade->assessment->name }} - {{ optional($grade->graded_at)->format('Y-m-d') }}</small>
                            </div>
                            <span class="badge">{{ $grade->score }} / {{ $grade->assessment->max_score }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xxl);
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255, 255, 255, 0.4);
}

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
}

.stat-icon {
    width: 48px;
    height: 48px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    color: #fff;
}

.stat-icon.students { background: var(--gradient-primary); }
.stat-icon.teachers { background: linear-gradient(45deg, #fbc531, #e1b12c); }
.stat-icon.classes { background: linear-gradient(45deg, #4cd137, #44bd32); }
.stat-icon.attendance { background: linear-gradient(45deg, #00a8ff, #0097e6); }
.stat-icon.grades { background: linear-gradient(45deg, #e056fd, #be2edd); }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: var(--spacing-xs);
}

.stat-label {
    color: #666;
}

.quick-actions-section {
    background: rgba(255, 255, 255, 0.95);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-lg);
    margin-bottom: var(--spacing-xxl);
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: var(--spacing-lg);
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-sm);
    background: rgba(102, 126, 234, 0.08);
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-lg);
    color: inherit;
    transition: var(--transition-normal);
    text-align: center;
}

.action-btn:hover {
    background: rgba(102, 126, 234, 0.16);
    transform: translateY(-3px);
    color: inherit;
}

.data-panels {
    display: grid;
    gap: var(--spacing-xl);
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

.data-panel {
    background: rgba(255, 255, 255, 0.95);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255, 255, 255, 0.4);
}

.panel-header {
    padding: var(--spacing-lg);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.panel-body {
    padding: var(--spacing-lg);
}

.recent-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.recent-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-md);
    background: rgba(102, 126, 234, 0.06);
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-md);
}

.recent-list .badge {
    background: var(--gradient-primary);
    color: #fff;
    border-radius: var(--border-radius-md);
    padding: 0.35rem 0.75rem;
    font-size: 0.85rem;
}

@media (max-width: 768px) {
    .recent-list li {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('لوحة التحكم جاهزة بالبيانات الحية.');
});
</script>
@endpush

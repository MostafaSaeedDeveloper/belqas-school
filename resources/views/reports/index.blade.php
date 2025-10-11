@extends('admin.layouts.master')

@section('title', 'التقارير الشاملة - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'مركز التقارير')
    @section('page-subtitle', 'الوصول السريع إلى مؤشرات الأداء الرئيسية')
    @section('breadcrumb')
        <li class="breadcrumb-item active">التقارير</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x text-primary mb-3"></i>
                    <h6 class="text-muted">إجمالي الطلاب</h6>
                    <div class="display-6 fw-bold">{{ $metrics['students'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard-teacher fa-2x text-success mb-3"></i>
                    <h6 class="text-muted">عدد المعلمين</h6>
                    <div class="display-6 fw-bold">{{ $metrics['teachers'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-2x text-info mb-3"></i>
                    <h6 class="text-muted">نسبة الحضور العامة</h6>
                    <div class="display-6 fw-bold">{{ $metrics['attendanceRate'] !== null ? number_format($metrics['attendanceRate'], 1) . '%' : '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-graduation-cap fa-2x text-warning mb-3"></i>
                    <h6 class="text-muted">متوسط الدرجات</h6>
                    <div class="display-6 fw-bold">{{ $metrics['averageGrade'] !== null ? number_format($metrics['averageGrade'], 1) . '%' : '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">التقارير الأكاديمية</h5>
                    <p class="text-muted">تحليل أداء الطلاب بحسب المواد والفصول واكتشاف حالات التعثر المبكر.</p>
                    <a href="{{ route('reports.academic') }}" class="btn btn-outline-primary w-100">عرض التقارير الأكاديمية</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">تقارير الحضور</h5>
                    <p class="text-muted">تتبع نسب الحضور والغياب ورصد الحالات المتكررة لكل فصل دراسي.</p>
                    <a href="{{ route('reports.attendance') }}" class="btn btn-outline-success w-100">عرض تقارير الحضور</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">التقارير المالية</h5>
                    <p class="text-muted">مراجعة مؤشرات التحصيل المالي والمصروفات التشغيلية وأثرها على الميزانية.</p>
                    <a href="{{ route('reports.financial') }}" class="btn btn-outline-secondary w-100">عرض التقارير المالية</a>
                </div>
            </div>
        </div>
    </div>
@endsection

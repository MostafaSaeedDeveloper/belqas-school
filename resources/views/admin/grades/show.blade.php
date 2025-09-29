@extends('admin.layouts.master')

@section('title', 'تفاصيل الدرجة')

@section('page-header')
    @section('page-title', 'تفاصيل الدرجة المسجلة')
    @section('page-subtitle', $grade->enrollment->student->first_name . ' ' . $grade->enrollment->student->last_name)
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('grades.index') }}">الدرجات</a></li>
        <li class="breadcrumb-item active">عرض</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-graduate me-2 text-primary"></i>
                    بيانات الطالب
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">الاسم</dt>
                    <dd class="col-7">{{ $grade->enrollment->student->first_name }} {{ $grade->enrollment->student->last_name }}</dd>

                    <dt class="col-5 text-muted">الفصل</dt>
                    <dd class="col-7">{{ $grade->enrollment->classroom->name }}</dd>

                    <dt class="col-5 text-muted">التقييم</dt>
                    <dd class="col-7">{{ $grade->assessment->name }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2 text-success"></i>
                    تفاصيل الدرجة
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted">الدرجة المحصلة</div>
                            <div class="display-6 fw-bold">{{ $grade->score }}</div>
                            <small class="text-muted">من {{ $grade->assessment->max_score }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted">تاريخ الرصد</div>
                            <div class="fw-semibold">{{ optional($grade->graded_at)->format('Y-m-d') ?: '—' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted">آخر تحديث</div>
                            <div class="fw-semibold">{{ optional($grade->updated_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">ملاحظات المعلم</label>
                        <div class="p-3 border rounded-3 bg-white">{{ $grade->remarks ?: 'لا توجد ملاحظات.' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('grades.index') }}" class="btn btn-outline-primary mt-4">
            <i class="fas fa-arrow-right"></i>
            الرجوع لسجل الدرجات
        </a>
    </div>
</div>
@endsection

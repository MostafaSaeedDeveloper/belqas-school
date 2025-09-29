@extends('admin.layouts.master')

@section('title', 'تفاصيل التقييم')

@section('page-header')
    @section('page-title', 'تفاصيل التقييم')
    @section('page-subtitle', $assessment->name)
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('assessments.index') }}">التقييمات</a></li>
        <li class="breadcrumb-item active">عرض</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-list me-2 text-primary"></i>
                    معلومات التقييم
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">اسم التقييم</dt>
                    <dd class="col-7">{{ $assessment->name }}</dd>

                    <dt class="col-5 text-muted">المادة</dt>
                    <dd class="col-7">{{ $assessment->subject->name }}</dd>

                    <dt class="col-5 text-muted">الفصل</dt>
                    <dd class="col-7">{{ $assessment->classroom->name }}</dd>

                    <dt class="col-5 text-muted">تاريخ الإجراء</dt>
                    <dd class="col-7">{{ optional($assessment->assessment_date)->format('Y-m-d') ?: '—' }}</dd>

                    <dt class="col-5 text-muted">الدرجة العظمى</dt>
                    <dd class="col-7">{{ $assessment->max_score }}</dd>
                </dl>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-align-left me-2 text-info"></i>
                    وصف التقييم
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $assessment->description ?: 'لا يوجد وصف متاح.' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2 text-warning"></i>
                    الدرجات المسجلة
                </h5>
                <span class="badge bg-light text-dark border">{{ $assessment->grades->count() }} درجة</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>الفصل</th>
                                <th>الدرجة</th>
                                <th>تاريخ الرصد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assessment->grades as $grade)
                                <tr>
                                    <td>{{ $grade->enrollment->student->first_name }} {{ $grade->enrollment->student->last_name }}</td>
                                    <td>{{ $grade->enrollment->classroom->name }}</td>
                                    <td>{{ $grade->score }} / {{ $assessment->max_score }}</td>
                                    <td>{{ optional($grade->graded_at)->format('Y-m-d') ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">لم يتم تسجيل درجات لهذا التقييم بعد.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{ route('assessments.index') }}" class="btn btn-outline-primary mt-4">
            <i class="fas fa-arrow-right"></i>
            الرجوع لقائمة التقييمات
        </a>
    </div>
</div>
@endsection

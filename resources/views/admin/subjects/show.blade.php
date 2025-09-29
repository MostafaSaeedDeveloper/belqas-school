@extends('admin.layouts.master')

@section('title', 'بيانات المادة الدراسية')

@section('page-header')
    @section('page-title', 'بيانات المادة الدراسية')
    @section('page-subtitle', $subject->name)
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">المواد</a></li>
        <li class="breadcrumb-item active">عرض</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    تفاصيل المادة
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">اسم المادة</dt>
                    <dd class="col-7">{{ $subject->name }}</dd>

                    <dt class="col-5 text-muted">الرمز</dt>
                    <dd class="col-7">{{ $subject->code }}</dd>

                    <dt class="col-5 text-muted">المعلم المسؤول</dt>
                    <dd class="col-7">{{ optional($subject->teacher)->first_name ? ($subject->teacher->first_name . ' ' . $subject->teacher->last_name) : 'غير محدد' }}</dd>

                    <dt class="col-5 text-muted">الوصف</dt>
                    <dd class="col-7">{{ $subject->description ?: 'لا يوجد وصف.' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-school me-2 text-success"></i>
                    الفصول المرتبطة
                </h5>
                <span class="badge bg-light text-dark border">{{ $subject->classrooms->count() }} فصل</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>الفصل</th>
                                <th>المرحلة</th>
                                <th>رائد الفصل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subject->classrooms as $classroom)
                                <tr>
                                    <td>{{ $classroom->name }}</td>
                                    <td>{{ $classroom->grade_level }}</td>
                                    <td>{{ optional($classroom->homeroomTeacher)->first_name ? ($classroom->homeroomTeacher->first_name . ' ' . $classroom->homeroomTeacher->last_name) : 'غير محدد' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">لا توجد فصول مرتبطة حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-signature me-2 text-info"></i>
                    التقييمات المرتبطة
                </h5>
                <span class="badge bg-light text-dark border">{{ $subject->assessments->count() }} تقييم</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>اسم التقييم</th>
                                <th>التاريخ</th>
                                <th>الدرجة العظمى</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subject->assessments as $assessment)
                                <tr>
                                    <td>{{ $assessment->name }}</td>
                                    <td>{{ optional($assessment->assessment_date)->format('Y-m-d') ?: '—' }}</td>
                                    <td>{{ $assessment->max_score }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">لا توجد تقييمات مرتبطة بعد.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{ route('subjects.index') }}" class="btn btn-outline-primary mt-4">
            <i class="fas fa-arrow-right"></i>
            الرجوع لقائمة المواد
        </a>
    </div>
</div>
@endsection

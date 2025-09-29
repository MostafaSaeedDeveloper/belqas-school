@extends('admin.layouts.master')

@section('title', 'تفاصيل القيد الدراسي')

@section('page-header')
    @section('page-title', 'تفاصيل القيد الدراسي')
    @section('page-subtitle', $enrollment->student->first_name . ' ' . $enrollment->student->last_name)
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">القيود الدراسية</a></li>
        <li class="breadcrumb-item active">عرض</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-id-card me-2 text-primary"></i>
                    بيانات القيد
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">الطالب</dt>
                    <dd class="col-7">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</dd>

                    <dt class="col-5 text-muted">الفصل</dt>
                    <dd class="col-7">{{ $enrollment->classroom->name }}</dd>

                    <dt class="col-5 text-muted">تاريخ القيد</dt>
                    <dd class="col-7">{{ optional($enrollment->enrolled_at)->format('Y-m-d') ?: '—' }}</dd>

                    <dt class="col-5 text-muted">الحالة</dt>
                    <dd class="col-7">
                        <span class="badge bg-{{ $enrollment->active ? 'success' : 'secondary' }}">
                            {{ $enrollment->active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2 text-warning"></i>
                    الدرجات المرتبطة بالقيد
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>التقييم</th>
                                <th>الدرجة</th>
                                <th>تاريخ الرصد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollment->grades as $grade)
                                <tr>
                                    <td>{{ $grade->assessment->name }}</td>
                                    <td>{{ $grade->score }} / {{ $grade->assessment->max_score }}</td>
                                    <td>{{ optional($grade->graded_at)->format('Y-m-d') ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">لا توجد درجات مرتبطة حتى الآن.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-check me-2 text-success"></i>
                    سجلات الحضور للطالب
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollment->attendanceRecords as $record)
                                <tr>
                                    <td>{{ optional($record->attendance_date)->format('Y-m-d') ?: '—' }}</td>
                                    <td>{{ $record->status }}</td>
                                    <td>{{ $record->remarks ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">لا توجد سجلات حضور.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{ route('enrollments.index') }}" class="btn btn-outline-primary mt-4">
            <i class="fas fa-arrow-right"></i>
            الرجوع لقائمة القيود
        </a>
    </div>
</div>
@endsection

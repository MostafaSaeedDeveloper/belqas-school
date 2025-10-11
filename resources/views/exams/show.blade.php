@extends('admin.layouts.master')

@section('title', 'تفاصيل الامتحان - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تفاصيل الامتحان')
    @section('page-subtitle', 'استعراض بيانات الامتحان والتكليفات المرتبطة')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">الامتحانات</a></li>
        <li class="breadcrumb-item active">تفاصيل الامتحان</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">معلومات الامتحان</h5>
                    @can('edit_exams')
                        <a href="{{ route('exams.edit', $exam) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-1"></i>تعديل</a>
                    @endcan
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">العنوان</dt>
                        <dd class="col-sm-8">{{ $exam->title }}</dd>
                        <dt class="col-sm-4">المادة</dt>
                        <dd class="col-sm-8">{{ $exam->subject->name }}</dd>
                        <dt class="col-sm-4">الفصل</dt>
                        <dd class="col-sm-8">{{ $exam->classroom->name }}</dd>
                        <dt class="col-sm-4">التاريخ</dt>
                        <dd class="col-sm-8">{{ $exam->exam_date->translatedFormat('d F Y') }}</dd>
                        <dt class="col-sm-4">الوقت</dt>
                        <dd class="col-sm-8">{{ optional($exam->start_time)->format('H:i') }} - {{ optional($exam->end_time)->format('H:i') }}</dd>
                        <dt class="col-sm-4">الموقع</dt>
                        <dd class="col-sm-8">{{ $exam->location ?? '—' }}</dd>
                        <dt class="col-sm-4">المراقب</dt>
                        <dd class="col-sm-8">{{ $exam->supervisor?->name ?? 'لم يحدد' }}</dd>
                        <dt class="col-sm-4">الحالة</dt>
                        <dd class="col-sm-8"><span class="badge bg-{{ $exam->status === 'completed' ? 'success' : ($exam->status === 'cancelled' ? 'danger' : 'info') }}">{{ $exam->status_label }}</span></dd>
                        <dt class="col-sm-4">الملاحظات</dt>
                        <dd class="col-sm-8">{{ $exam->notes ?: 'لا توجد ملاحظات' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">ملخص الدرجات</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6 class="text-muted">المتوسط</h6>
                            <div class="display-6 fw-bold text-primary">{{ $gradeSummary?->average ? number_format($gradeSummary->average, 1) . '%' : '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">أفضل نتيجة</h6>
                            <div class="display-6 fw-bold text-success">{{ $gradeSummary?->best ? number_format($gradeSummary->best, 1) . '%' : '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">أقل نتيجة</h6>
                            <div class="display-6 fw-bold text-danger">{{ $gradeSummary?->worst ? number_format($gradeSummary->worst, 1) . '%' : '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">أفضل الطلاب في الامتحان</h5>
                    <a href="{{ route('grades.reports', ['subject_id' => $exam->subject_id, 'classroom_id' => $exam->classroom_id]) }}" class="btn btn-sm btn-outline-secondary">تقرير شامل</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>الدرجة</th>
                                <th>النسبة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($topStudents as $record)
                                <tr>
                                    <td>{{ $record->student->name }}</td>
                                    <td>{{ $record->score }} / {{ $record->max_score }}</td>
                                    <td>{{ number_format(($record->score / $record->max_score) * 100, 1) }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">لم يتم تسجيل درجات لهذا الامتحان بعد.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

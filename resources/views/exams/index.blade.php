@extends('admin.layouts.master')

@section('title', 'إدارة الامتحانات - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الامتحانات')
    @section('page-subtitle', 'تنظيم الجداول ومتابعة لجان الامتحانات')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الامتحانات</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('exams.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">الفصل الدراسي</label>
                    <select name="classroom_id" class="form-select">
                        <option value="">جميع الفصول</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ ($filters['classroom_id'] ?? null) == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">المادة</label>
                    <select name="subject_id" class="form-select">
                        <option value="">جميع المواد</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ ($filters['subject_id'] ?? null) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="scheduled" {{ ($filters['status'] ?? null) === 'scheduled' ? 'selected' : '' }}>مجدول</option>
                        <option value="completed" {{ ($filters['status'] ?? null) === 'completed' ? 'selected' : '' }}>منفذ</option>
                        <option value="cancelled" {{ ($filters['status'] ?? null) === 'cancelled' ? 'selected' : '' }}>ملغى</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">من تاريخ</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">إلى تاريخ</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-12 text-end">
                    <button class="btn btn-outline-secondary"><i class="fas fa-sync-alt me-2"></i>تحديث</button>
                    @can('create_exams')
                        <a href="{{ route('exams.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>امتحان جديد</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>الامتحان</th>
                        <th>المادة</th>
                        <th>الفصل</th>
                        <th>التاريخ</th>
                        <th>الوقت</th>
                        <th>المراقب</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td class="fw-semibold">{{ $exam->title }}</td>
                            <td>{{ $exam->subject->name }}</td>
                            <td>{{ $exam->classroom->name }}</td>
                            <td>{{ $exam->exam_date->translatedFormat('d/m/Y') }}</td>
                            <td>{{ optional($exam->start_time)->format('H:i') }} - {{ optional($exam->end_time)->format('H:i') }}</td>
                            <td>{{ $exam->supervisor?->name ?? 'لم يحدد' }}</td>
                            <td>
                                <span class="badge bg-{{ $exam->status === 'completed' ? 'success' : ($exam->status === 'cancelled' ? 'danger' : 'info') }}">
                                    {{ $exam->status_label }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('exams.show', $exam) }}" class="btn btn-sm btn-outline-primary">التفاصيل</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">لا توجد امتحانات مطابقة للمعايير الحالية.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $exams->withQueryString()->links() }}
        </div>
    </div>
@endsection

@extends('admin.layouts.master')

@section('title', 'تقارير الحضور - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الحضور')
    @section('page-subtitle', 'تحليل سجلات الحضور وفق الفصول والحالات المختلفة')
    @section('breadcrumb')
        <li class="breadcrumb-item">الحضور</li>
        <li class="breadcrumb-item active">تقارير الحضور</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-success">{{ $summary['present'] ?? 0 }}</div>
                    <div class="stat-label">إجمالي الحضور</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-times"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-danger">{{ $summary['absent'] ?? 0 }}</div>
                    <div class="stat-label">إجمالي الغياب</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-clock"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $summary['late'] ?? 0 }}</div>
                    <div class="stat-label">حالات التأخر</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-door-open"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $summary['excused'] ?? 0 }}</div>
                    <div class="stat-label">إذونات الخروج</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.reports') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" for="filter_classroom">الفصل الدراسي</label>
                    <select class="form-select" id="filter_classroom" name="classroom_id">
                        <option value="">الكل</option>
                        @foreach($classrooms as $item)
                            <option value="{{ $item->id }}" @selected($filters['classroom_id'] == $item->id)>
                                {{ $item->name }} @if($item->grade_level) ({{ $item->grade_level }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="filter_status">حالة الحضور</label>
                    <select class="form-select" id="filter_status" name="status">
                        <option value="">الكل</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="filter_from">من تاريخ</label>
                    <input type="date" class="form-control" id="filter_from" name="date_from" value="{{ $filters['date_from'] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="filter_to">إلى تاريخ</label>
                    <input type="date" class="form-control" id="filter_to" name="date_to" value="{{ $filters['date_to'] }}">
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('attendance.reports') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate-left"></i> إعادة تعيين
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> عرض النتائج
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-table-list"></i> نتائج التقارير</h5>
            <span class="text-muted small">إجمالي السجلات: {{ $records->total() }}</span>
        </div>
        <div class="card-body p-0">
            @if($records->isEmpty())
                <div class="empty-state py-5">
                    <div class="empty-state-icon bg-light"><i class="fas fa-file-circle-xmark"></i></div>
                    <h5 class="empty-state-title">لا توجد سجلات مطابقة</h5>
                    <p class="empty-state-text">قم بتعديل معايير البحث أو اختر فترة زمنية أوسع لإظهار النتائج.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>التاريخ</th>
                                <th>الفصل</th>
                                <th>الطالب</th>
                                <th class="text-center">الحالة</th>
                                <th>ملاحظات</th>
                                <th>تم التسجيل بواسطة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $record->session?->date?->format('Y-m-d') }}</div>
                                        <div class="text-muted small">{{ $record->session?->date?->translatedFormat('l') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $record->session?->classroom?->name ?? '—' }}</div>
                                        <div class="text-muted small">{{ $record->session?->classroom?->grade_level }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $record->student?->name ?? '—' }}</div>
                                        <div class="text-muted small">{{ $record->student?->studentProfile?->guardian_name }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="attendance-status attendance-{{ $record->status }}">
                                            {{ $statuses[$record->status] ?? $record->status }}
                                        </span>
                                    </td>
                                    <td>{{ $record->remarks ?: '—' }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $record->session?->recordedBy?->name ?? '—' }}</div>
                                        <div class="text-muted small">{{ optional($record->updated_at)->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

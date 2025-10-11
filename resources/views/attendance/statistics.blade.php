@extends('admin.layouts.master')

@section('title', 'تحليلات الحضور - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'لوحة تحليلات الحضور')
    @section('page-subtitle', 'مؤشرات الأداء الشهرية وسجلات الغياب المرتفع')
    @section('breadcrumb')
        <li class="breadcrumb-item">الحضور</li>
        <li class="breadcrumb-item active">التحليلات</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        @foreach($monthlyData as $month)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">{{ $month['label'] }}</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold fs-4 text-success">{{ $month['rate'] !== null ? number_format($month['rate'], 1) . '%' : '—' }}</div>
                                <small class="text-muted">نسبة الحضور</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold">{{ $month['present'] }} / {{ $month['total'] }}</div>
                                <small class="text-muted">حاضر / إجمالي</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-xl-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user-times text-danger me-2"></i>أكثر الطلاب غيابًا</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($topAbsentees as $record)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $record->student->name }}</span>
                                <span class="badge bg-danger">{{ $record->absences }} غياب</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">لا توجد بيانات غياب مسجلة.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-stream text-primary me-2"></i>سجلات الحضور الأخيرة</h5>
                    <a href="{{ route('attendance.daily') }}" class="btn btn-sm btn-outline-secondary">تسجيل جديد</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الطالب</th>
                                <th>الفصل</th>
                                <th>الحالة</th>
                                <th>مسجل بواسطة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentRecords as $record)
                                <tr>
                                    <td>{{ \\Carbon\\Carbon::parse($record->attendance_date)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $record->student->name }}</td>
                                    <td>{{ $record->classroom->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning' : 'danger') }}">
                                            {{ $record->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ $record->recorder->name ?? 'النظام' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">لا توجد سجلات متاحة.</td>
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

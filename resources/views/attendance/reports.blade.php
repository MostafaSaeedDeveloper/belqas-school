@extends('admin.layouts.master')

@section('title', 'تقارير الحضور - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الحضور')
    @section('page-subtitle', 'متابعة نسب الحضور لكل الفصول وتحليل الغياب المتكرر')
    @section('breadcrumb')
        <li class="breadcrumb-item">الحضور</li>
        <li class="breadcrumb-item active">التقارير</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.reports') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">الفصل الدراسي</label>
                    <select name="classroom_id" class="form-select">
                        <option value="">جميع الفصول</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ optional($selectedClassroom)->id === $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }} - {{ $classroom->grade_level }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">من تاريخ</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">إلى تاريخ</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fas fa-sync me-2"></i>تحديث
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">نسبة الحضور العامة</h6>
                    <div class="display-6 fw-bold text-success">{{ number_format($attendanceRate, 2) }}%</div>
                </div>
            </div>
        </div>
        @foreach(['present' => 'حاضر', 'late' => 'متأخر', 'excused' => 'مُبرر', 'absent' => 'غائب'] as $status => $label)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">{{ $label }}</h6>
                        <div class="display-6 fw-bold">{{ $summaryByStatus[$status] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">الحضور حسب الفصول</h5>
                    <span class="badge bg-primary">{{ $summaryByClassroom->sum() }} تسجيل</span>
                </div>
                <div class="card-body">
                    @if($summaryByClassroom->isEmpty())
                        <p class="text-muted mb-0">لا توجد بيانات ضمن الفترة المحددة.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($summaryByClassroom as $classroomName => $total)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $classroomName }}</span>
                                    <span class="badge bg-light text-dark">{{ $total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">آخر سجلات الحضور</h5>
                    <a href="{{ route('attendance.statistics') }}" class="btn btn-sm btn-outline-secondary">لوحة التحليلات</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>الفصل</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>مسجل بواسطة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($detailedRecords as $record)
                                <tr>
                                    <td>{{ $record->student->name }}</td>
                                    <td>{{ $record->classroom->name }}</td>
                                    <td>{{ \\Carbon\\Carbon::parse($record->attendance_date)->translatedFormat('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning' : 'danger') }}">
                                            {{ $record->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ $record->recorder->name ?? 'النظام' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">لا توجد سجلات لعرضها.</td>
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

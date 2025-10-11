@extends('admin.layouts.master')

@section('title', 'الحضور اليومي - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الحضور اليومي')
    @section('page-subtitle', 'تسجيل وتتبع حضور الطلاب في الوقت الفعلي')
    @section('breadcrumb')
        <li class="breadcrumb-item">الحضور</li>
        <li class="breadcrumb-item active">الحضور اليومي</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.daily') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">الفصل الدراسي</label>
                    <select name="classroom_id" class="form-select" required>
                        <option value="">اختر الفصل</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ optional($selectedClassroom)->id === $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }} - {{ $classroom->grade_level }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">تاريخ المتابعة</label>
                    <input type="date" name="date" value="{{ $selectedDate }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">بحث</label>
                    <button class="btn btn-primary d-block w-100" type="submit">
                        <i class="fas fa-filter me-2"></i>تحديث القائمة
                    </button>
                </div>
                <div class="col-md-2 text-md-end">
                    <a href="{{ route('attendance.statistics') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-chart-line me-2"></i>التحليلات
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($selectedClassroom)
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">{{ $selectedClassroom->name }}</h5>
                <p class="text-muted mb-0">عدد الطلاب: {{ $students->count() }} | تاريخ المتابعة: {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</p>
            </div>
            @can('attendance_reports')
                <a href="{{ route('attendance.reports', ['classroom_id' => $selectedClassroom->id, 'start_date' => $selectedDate, 'end_date' => $selectedDate]) }}"
                   class="btn btn-outline-primary">
                    <i class="fas fa-file-export me-2"></i>تقرير اليوم
                </a>
            @endcan
        </div>

        @can('create_attendance')
            <form method="POST" action="{{ route('attendance.daily.store') }}">
                @csrf
                <input type="hidden" name="classroom_id" value="{{ $selectedClassroom->id }}">
                <input type="hidden" name="date" value="{{ $selectedDate }}">
        @endcan
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>الطالب</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-center">وقت الحضور</th>
                                    <th class="text-center">وقت الانصراف</th>
                                    <th>ملاحظات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($students as $student)
                                    @php($record = $records->get($student->id))
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $student->avatar_url }}" class="rounded-circle me-3" width="44" height="44" alt="avatar">
                                                <div>
                                                    <div class="fw-semibold">{{ $student->name }}</div>
                                                    <small class="text-muted">{{ optional($student->studentProfile)->student_number ?? 'بدون رقم' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @can('create_attendance')
                                                <div class="btn-group" role="group">
                                                    @foreach($statuses as $value => $label)
                                                        <input type="radio"
                                                               class="btn-check"
                                                               name="attendance[{{ $student->id }}][status]"
                                                               value="{{ $value }}"
                                                               id="status-{{ $student->id }}-{{ $value }}"
                                                               {{ ($record?->status ?? 'present') === $value ? 'checked' : '' }}>
                                                        <label class="btn btn-sm btn-outline-primary" for="status-{{ $student->id }}-{{ $value }}">{{ $label }}</label>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="badge bg-{{ $record?->status === 'present' ? 'success' : ($record?->status === 'late' ? 'warning' : 'danger') }}">
                                                    {{ $record?->status_label ?? 'غير مسجل' }}
                                                </span>
                                            @endcan
                                        </td>
                                        <td class="text-center" style="max-width: 140px;">
                                            @can('create_attendance')
                                                <input type="time" class="form-control form-control-sm"
                                                       name="attendance[{{ $student->id }}][check_in_time]"
                                                       value="{{ $record?->check_in_time?->format('H:i') }}">
                                            @else
                                                <span>{{ optional($record?->check_in_time)->format('H:i') ?? '—' }}</span>
                                            @endcan
                                        </td>
                                        <td class="text-center" style="max-width: 140px;">
                                            @can('create_attendance')
                                                <input type="time" class="form-control form-control-sm"
                                                       name="attendance[{{ $student->id }}][check_out_time]"
                                                       value="{{ $record?->check_out_time?->format('H:i') }}">
                                            @else
                                                <span>{{ optional($record?->check_out_time)->format('H:i') ?? '—' }}</span>
                                            @endcan
                                        </td>
                                        <td>
                                            @can('create_attendance')
                                                <input type="text" class="form-control form-control-sm"
                                                       name="attendance[{{ $student->id }}][notes]"
                                                       placeholder="ملاحظات إضافية"
                                                       value="{{ $record?->notes }}">
                                            @else
                                                <span class="text-muted">{{ $record?->notes ?: '—' }}</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">لا توجد بيانات طلاب لهذا الفصل.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        @can('create_attendance')
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>حفظ سجل الحضور
                    </button>
                </div>
            </form>
        @endcan
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> يرجى اختيار فصل دراسي وتاريخ لعرض قائمة الطلاب وتسجيل حضورهم.
        </div>
    @endif
@endsection

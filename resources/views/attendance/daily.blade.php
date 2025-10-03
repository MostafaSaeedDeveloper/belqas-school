@extends('admin.layouts.master')

@section('title', 'الحضور اليومي - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الحضور اليومي')
    @section('page-subtitle', 'تسجيل وتتبع حضور الطلاب لكل فصل دراسي')
    @section('breadcrumb')
        <li class="breadcrumb-item">الحضور</li>
        <li class="breadcrumb-item active">الحضور اليومي</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.daily') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="classroom_id" class="form-label">اختر الفصل</label>
                    <select id="classroom_id" name="classroom_id" class="form-select" required>
                        <option value="">-- اختر الفصل --</option>
                        @foreach($classrooms as $item)
                            <option value="{{ $item->id }}" @selected($classroom && $classroom->id === $item->id)>
                                {{ $item->name }}
                                @if($item->grade_level)
                                    ({{ $item->grade_level }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date" class="form-label">التاريخ</label>
                    <input type="date" id="date" name="date" value="{{ $selectedDate }}" class="form-control" required>
                </div>
                <div class="col-md-5 d-flex align-items-end justify-content-end gap-2">
                    <a href="{{ route('attendance.daily') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate-left"></i> إعادة تعيين
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> عرض الطلاب
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($classroom)
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-card-header">
                        <div class="stat-icon attendance"><i class="fas fa-chalkboard"></i></div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $classroom->name }}</div>
                        <div class="stat-label">{{ $classroom->grade_level ?? 'فصل دراسي' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-card-header">
                        <div class="stat-icon attendance"><i class="fas fa-user-check"></i></div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number text-success">{{ $statusCounts['present'] ?? 0 }}</div>
                        <div class="stat-label">طلاب حاضرين</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-card-header">
                        <div class="stat-icon attendance"><i class="fas fa-user-times"></i></div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number text-danger">{{ $statusCounts['absent'] ?? 0 }}</div>
                        <div class="stat-label">طلاب غائبين</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-card-header">
                        <div class="stat-icon attendance"><i class="fas fa-clock"></i></div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ ($statusCounts['late'] ?? 0) + ($statusCounts['excused'] ?? 0) }}</div>
                        <div class="stat-label">متأخرون / مستأذنون</div>
                    </div>
                </div>
            </div>
        </div>

        @if($lastUpdated)
            <div class="alert alert-info d-flex align-items-center gap-2">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>آخر تحديث:</strong> {{ $lastUpdated->diffForHumans() }}
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users"></i> قائمة الطلاب ({{ $students->count() }})</h5>
                @can('manage_attendance')
                    <span class="text-muted small">قم بتحديد حالة كل طالب ثم احفظ السجل</span>
                @endcan
            </div>
            <div class="card-body">
                @if($students->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon bg-light"><i class="fas fa-user-graduate"></i></div>
                        <h5 class="empty-state-title">لا يوجد طلاب مسجلين في هذا الفصل</h5>
                        <p class="empty-state-text">قم بإضافة الطلاب إلى الفصل من خلال شاشة إدارة الطلاب ثم عُد لتسجيل الحضور.</p>
                    </div>
                @else
                    @can('manage_attendance')
                        @php($defaultStatus = \App\Models\AttendanceRecord::STATUS_PRESENT)

                        <form method="POST" action="{{ route('attendance.daily.store') }}">
                            @csrf
                            <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                            <input type="hidden" name="date" value="{{ $selectedDate }}">

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>الطالب</th>
                                            <th class="text-center" style="width: 200px;">حالة الحضور</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            @php($record = $records->get($student->id))
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="{{ $student->avatar_url }}" class="rounded-circle" width="40" height="40" alt="{{ $student->name }}">
                                                        <div>
                                                            <div class="fw-semibold">{{ $student->name }}</div>
                                                            <div class="text-muted small">{{ $student->studentProfile?->grade_level }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <select name="records[{{ $student->id }}][status]" class="form-select @error('records.' . $student->id . '.status') is-invalid @enderror">
                                                        @foreach($statuses as $value => $label)
                                                            <option value="{{ $value }}" @selected(old('records.' . $student->id . '.status', $record->status ?? $defaultStatus) === $value)>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('records.' . $student->id . '.status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" name="records[{{ $student->id }}][remarks]" class="form-control" placeholder="ملاحظات اختيارية" value="{{ old('records.' . $student->id . '.remarks', $record->remarks ?? '') }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <label for="notes" class="form-label">ملاحظات عامة على اليوم الدراسي</label>
                                <textarea id="notes" name="notes" rows="3" class="form-control" placeholder="أضف أي معلومات إضافية عن الحصة أو الحالات الخاصة">{{ old('notes', $session?->notes) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ الحضور
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-lock"></i> لا تملك صلاحية تعديل الحضور. يمكنك عرض النتائج فقط.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>الطالب</th>
                                        <th class="text-center">الحالة</th>
                                        <th>ملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        @php($record = $records->get($student->id))
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ $student->avatar_url }}" class="rounded-circle" width="40" height="40" alt="{{ $student->name }}">
                                                    <div>
                                                        <div class="fw-semibold">{{ $student->name }}</div>
                                                        <div class="text-muted small">{{ $student->studentProfile?->grade_level }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($record)
                                                    <span class="attendance-status attendance-{{ $record->status }}">{{ $statuses[$record->status] ?? $record->status }}</span>
                                                @else
                                                    <span class="text-muted">لم يتم تسجيل الحضور</span>
                                                @endif
                                            </td>
                                            <td>{{ $record?->remarks ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endcan
                @endif
            </div>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon bg-light"><i class="fas fa-calendar-check"></i></div>
            <h5 class="empty-state-title">اختر فصلاً وتاريخاً لبدء تسجيل الحضور</h5>
            <p class="empty-state-text">استخدم نموذج التصفية بالأعلى لتحديد الفصل الدراسي والتاريخ المطلوبين.</p>
        </div>
    @endif
@endsection

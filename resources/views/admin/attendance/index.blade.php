@extends('admin.layouts.master')

@section('title', 'سجل الحضور اليومي')

@section('page-header')
    @section('page-title', 'إدارة سجلات الحضور والانصراف')
    @section('page-subtitle', 'توثيق حضور الطلاب وتتبع الغياب والتأخير')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الحضور</li>
    @endsection
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-check me-2 text-success"></i>
                سجل الحضور
            </h5>
            <small class="text-muted">{{ $attendanceRecords->total() }} سجل</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <select name="classroom_id" class="form-select">
                        <option value="">كل الفصول</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected((string) $filters['classroom_id'] === (string) $classroom->id)>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="student_id" class="form-select">
                        <option value="">كل الطلاب</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" @selected((string) $filters['student_id'] === (string) $student->id)>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input type="date" name="from" class="form-control" value="{{ $filters['from'] }}" placeholder="من">
                </div>
                <div class="col-auto">
                    <input type="date" name="to" class="form-control" value="{{ $filters['to'] }}" placeholder="إلى">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">تصفية</button>
                </div>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAttendanceModal">
                <i class="fas fa-plus-circle me-1"></i>
                تسجيل حضور
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>التاريخ</th>
                    <th>الطالب</th>
                    <th>الفصل</th>
                    <th>الحالة</th>
                    <th>ملاحظات</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendanceRecords as $record)
                    <tr>
                        <td>{{ optional($record->attendance_date)->format('Y-m-d') }}</td>
                        <td>{{ $record->enrollment->student->first_name }} {{ $record->enrollment->student->last_name }}</td>
                        <td>{{ $record->enrollment->classroom->name }}</td>
                        <td>
                            @php($statusClasses = ['present' => 'success', 'absent' => 'danger', 'late' => 'warning', 'excused' => 'info'])
                            <span class="badge bg-{{ $statusClasses[$record->status] ?? 'secondary' }}">
                                @switch($record->status)
                                    @case('present') حاضر @break
                                    @case('absent') غائب @break
                                    @case('late') متأخر @break
                                    @case('excused') مستأذن @break
                                    @default غير محدد
                                @endswitch
                            </span>
                        </td>
                        <td>{{ $record->remarks ?: '—' }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('attendance.show', $record) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editAttendanceModal{{ $record->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('attendance.destroy', $record) }}" method="POST" onsubmit="return confirm('تأكيد حذف السجل؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editAttendanceModal{{ $record->id }}" tabindex="-1" aria-labelledby="editAttendanceLabel{{ $record->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editAttendanceLabel{{ $record->id }}">تعديل سجل الحضور</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                </div>
                                <form action="{{ route('attendance.update', $record) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        @include('admin.attendance.partials.form-fields', ['record' => $record, 'enrollments' => $enrollments])
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">لا توجد سجلات حضور مطابقة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($attendanceRecords->hasPages())
        <div class="card-footer border-0">
            {{ $attendanceRecords->links() }}
        </div>
    @endif
</div>

<div class="modal fade" id="createAttendanceModal" tabindex="-1" aria-labelledby="createAttendanceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAttendanceLabel">تسجيل حضور جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.attendance.partials.form-fields', ['record' => null, 'enrollments' => $enrollments])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ السجل</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

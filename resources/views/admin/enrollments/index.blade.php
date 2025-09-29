@extends('admin.layouts.master')

@section('title', 'إدارة القيود الدراسية')

@section('page-header')
    @section('page-title', 'إدارة قيود الطلاب')
    @section('page-subtitle', 'تسجيل الطلاب في الفصول ومتابعة حالاتهم')
    @section('breadcrumb')
        <li class="breadcrumb-item active">القيود الدراسية</li>
    @endsection
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-0">
                <i class="fas fa-user-tag me-2 text-primary"></i>
                القيود الحالية
            </h5>
            <small class="text-muted">{{ $enrollments->total() }} قيد</small>
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
                    <button type="submit" class="btn btn-outline-primary">تصفية</button>
                </div>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEnrollmentModal">
                <i class="fas fa-plus-circle me-1"></i>
                إضافة قيد
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>الطالب</th>
                    <th>الفصل</th>
                    <th>تاريخ القيد</th>
                    <th>الحالة</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                    <tr>
                        <td>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</td>
                        <td>{{ $enrollment->classroom->name }}</td>
                        <td>{{ optional($enrollment->enrolled_at)->format('Y-m-d') ?: '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $enrollment->active ? 'success' : 'secondary' }}">
                                {{ $enrollment->active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editEnrollmentModal{{ $enrollment->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" onsubmit="return confirm('تأكيد حذف القيد؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editEnrollmentModal{{ $enrollment->id }}" tabindex="-1" aria-labelledby="editEnrollmentLabel{{ $enrollment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editEnrollmentLabel{{ $enrollment->id }}">تعديل بيانات القيد</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                </div>
                                <form action="{{ route('enrollments.update', $enrollment) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        @include('admin.enrollments.partials.form-fields', ['enrollment' => $enrollment, 'students' => $students, 'classrooms' => $classrooms])
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
                        <td colspan="5" class="text-center text-muted py-5">لا توجد قيود دراسية مسجلة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($enrollments->hasPages())
        <div class="card-footer border-0">
            {{ $enrollments->links() }}
        </div>
    @endif
</div>

<div class="modal fade" id="createEnrollmentModal" tabindex="-1" aria-labelledby="createEnrollmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEnrollmentLabel">إضافة قيد دراسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('enrollments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.enrollments.partials.form-fields', ['enrollment' => null, 'students' => $students, 'classrooms' => $classrooms])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ القيد</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

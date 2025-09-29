@extends('admin.layouts.master')

@section('title', 'سجل الدرجات')

@section('page-header')
    @section('page-title', 'إدارة الدرجات الطلابية')
    @section('page-subtitle', 'متابعة نتائج الاختبارات والتقييمات لكل طالب')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الدرجات</li>
    @endsection
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-0">
                <i class="fas fa-star me-2 text-warning"></i>
                سجل الدرجات
            </h5>
            <small class="text-muted">{{ $grades->total() }} نتيجة</small>
        </div>
        <div class="d-flex gap-2">
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
                    <select name="assessment_id" class="form-select">
                        <option value="">كل التقييمات</option>
                        @foreach($assessments as $assessmentOption)
                            <option value="{{ $assessmentOption->id }}" @selected((string) $filters['assessment_id'] === (string) $assessmentOption->id)>
                                {{ $assessmentOption->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">تصفية</button>
                </div>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGradeModal">
                <i class="fas fa-plus-circle me-1"></i>
                تسجيل درجة
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>الطالب</th>
                    <th>الفصل</th>
                    <th>التقييم</th>
                    <th>الدرجة</th>
                    <th>تاريخ الرصد</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                    <tr>
                        <td>{{ $grade->enrollment->student->first_name }} {{ $grade->enrollment->student->last_name }}</td>
                        <td>{{ $grade->enrollment->classroom->name }}</td>
                        <td>{{ $grade->assessment->name }}</td>
                        <td>
                            <strong>{{ $grade->score }}</strong>
                            <small class="text-muted">/ {{ $grade->assessment->max_score }}</small>
                        </td>
                        <td>{{ optional($grade->graded_at)->format('Y-m-d') ?: '—' }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('grades.show', $grade) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editGradeModal{{ $grade->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('grades.destroy', $grade) }}" method="POST" onsubmit="return confirm('تأكيد حذف الدرجة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editGradeModal{{ $grade->id }}" tabindex="-1" aria-labelledby="editGradeLabel{{ $grade->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editGradeLabel{{ $grade->id }}">تحديث درجة الطالب</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                </div>
                                <form action="{{ route('grades.update', $grade) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        @include('admin.grades.partials.form-fields', ['grade' => $grade, 'enrollments' => $enrollments, 'assessments' => $assessments])
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
                        <td colspan="6" class="text-center text-muted py-5">لا توجد درجات مسجلة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($grades->hasPages())
        <div class="card-footer border-0">
            {{ $grades->links() }}
        </div>
    @endif
</div>

<div class="modal fade" id="createGradeModal" tabindex="-1" aria-labelledby="createGradeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createGradeLabel">تسجيل درجة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('grades.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.grades.partials.form-fields', ['grade' => null, 'enrollments' => $enrollments, 'assessments' => $assessments])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الدرجة</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

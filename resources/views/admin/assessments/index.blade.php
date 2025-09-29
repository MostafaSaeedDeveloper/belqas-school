@extends('admin.layouts.master')

@section('title', 'إدارة التقييمات والامتحانات')

@section('page-header')
    @section('page-title', 'إدارة التقييمات')
    @section('page-subtitle', 'متابعة الامتحانات والاختبارات المسجلة لكل فصل')
    @section('breadcrumb')
        <li class="breadcrumb-item active">التقييمات</li>
    @endsection
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-0">
                <i class="fas fa-file-signature me-2 text-primary"></i>
                سجل التقييمات
            </h5>
            <small class="text-muted">{{ $assessments->total() }} تقييم مسجل</small>
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
                    <select name="subject_id" class="form-select">
                        <option value="">كل المواد</option>
                        @foreach($subjects as $subjectOption)
                            <option value="{{ $subjectOption->id }}" @selected((string) $filters['subject_id'] === (string) $subjectOption->id)>
                                {{ $subjectOption->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">تصفية</button>
                </div>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAssessmentModal">
                <i class="fas fa-plus-circle me-1"></i>
                إضافة تقييم
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>التقييم</th>
                    <th>المادة</th>
                    <th>الفصل</th>
                    <th>التاريخ</th>
                    <th>الدرجة العظمى</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $assessment)
                    <tr>
                        <td>{{ $assessment->name }}</td>
                        <td>{{ $assessment->subject->name }}</td>
                        <td>{{ $assessment->classroom->name }}</td>
                        <td>{{ optional($assessment->assessment_date)->format('Y-m-d') ?: '—' }}</td>
                        <td>{{ $assessment->max_score }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('assessments.show', $assessment) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editAssessmentModal{{ $assessment->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('assessments.destroy', $assessment) }}" method="POST" onsubmit="return confirm('تأكيد حذف التقييم؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editAssessmentModal{{ $assessment->id }}" tabindex="-1" aria-labelledby="editAssessmentLabel{{ $assessment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editAssessmentLabel{{ $assessment->id }}">تعديل بيانات التقييم</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                </div>
                                <form action="{{ route('assessments.update', $assessment) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        @include('admin.assessments.partials.form-fields', ['assessment' => $assessment, 'subjects' => $subjects, 'classrooms' => $classrooms])
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
                        <td colspan="6" class="text-center text-muted py-5">لا توجد تقييمات مسجلة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($assessments->hasPages())
        <div class="card-footer border-0">
            {{ $assessments->links() }}
        </div>
    @endif
</div>

<div class="modal fade" id="createAssessmentModal" tabindex="-1" aria-labelledby="createAssessmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAssessmentLabel">إضافة تقييم جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('assessments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.assessments.partials.form-fields', ['assessment' => null, 'subjects' => $subjects, 'classrooms' => $classrooms])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التقييم</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.master')

@section('title', 'إدخال الدرجات - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدخال الدرجات')
    @section('page-subtitle', 'تسجيل نتائج الطلاب لكل مادة واختبار')
    @section('breadcrumb')
        <li class="breadcrumb-item">الدرجات</li>
        <li class="breadcrumb-item active">الإدخال</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('grades.input') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">الفصل الدراسي</label>
                    <select name="classroom_id" class="form-select" required>
                        <option value="">اختر الفصل</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ optional($selectedClassroom)->id === $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">المادة الدراسية</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">اختر المادة</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ optional($selectedSubject)->id === $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">الاختبار</label>
                    <select name="exam_id" class="form-select">
                        <option value="">بدون ارتباط باختبار</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ optional($selectedExam)->id === $exam->id ? 'selected' : '' }}>
                                {{ $exam->title }} ({{ $exam->exam_date->translatedFormat('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">نوع التقييم</label>
                    <select name="assessment_type" class="form-select" required>
                        @foreach($assessmentTypes as $value => $label)
                            <option value="{{ $value }}" {{ request('assessment_type', 'exam') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedClassroom && $selectedSubject)
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ $selectedSubject->name }}</h5>
                    <small class="text-muted">{{ $selectedClassroom->name }} - {{ $students->count() }} طالب/ة - نوع التقييم: {{ \Illuminate\Support\Str::title(str_replace('_', ' ', request('assessment_type', 'exam'))) }}</small>
                </div>
                @can('grades_reports')
                    <a href="{{ route('grades.reports', ['classroom_id' => $selectedClassroom->id, 'subject_id' => $selectedSubject->id]) }}"
                       class="btn btn-outline-secondary">
                        <i class="fas fa-chart-pie me-2"></i>التقارير التفصيلية
                    </a>
                @endcan
            </div>
            <div class="card-body p-0">
                @can('create_grades')
                    <form method="POST" action="{{ route('grades.input.store') }}">
                        @csrf
                        <input type="hidden" name="classroom_id" value="{{ $selectedClassroom->id }}">
                        <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
                        <input type="hidden" name="exam_id" value="{{ optional($selectedExam)->id }}">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 align-middle">
                                <thead>
                                <tr>
                                    <th>الطالب</th>
                                    <th style="width: 120px;">الدرجة</th>
                                    <th style="width: 120px;">الدرجة العظمى</th>
                                    <th>ملاحظات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $student)
                                    @php($record = $records->get($student->id))
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <small class="text-muted">{{ optional($student->studentProfile)->student_number ?? 'بدون رقم' }}</small>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="grades[{{ $student->id }}][score]"
                                                   class="form-control form-control-sm"
                                                   value="{{ old("grades.{$student->id}.score", $record->score) }}" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="1" name="grades[{{ $student->id }}][max_score]"
                                                   class="form-control form-control-sm"
                                                   value="{{ old("grades.{$student->id}.max_score", $record->max_score ?? 100) }}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="grades[{{ $student->id }}][notes]"
                                                   class="form-control form-control-sm"
                                                   value="{{ old("grades.{$student->id}.notes", $record->notes) }}"
                                                   placeholder="ملاحظات المتابعة">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="assessment_type" value="{{ request('assessment_type', 'exam') }}">
                        <div class="text-end p-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>حفظ الدرجات
                            </button>
                        </div>
                    </form>
                @else
                    <div class="p-4 text-center text-muted">
                        لا تملك صلاحية إدخال الدرجات، يمكنك عرض البيانات فقط.
                    </div>
                @endcan
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> يرجى اختيار الفصل والمادة لعرض قائمة الطلاب وإدخال الدرجات.
        </div>
    @endif
@endsection

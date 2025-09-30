@extends('admin.layouts.master')

@section('title', 'تكليف المواد الدراسية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تكليف المواد الدراسية')
    @section('page-subtitle', 'إدارة إسناد المواد للمعلمين والفصول')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">المواد الدراسية</a></li>
        <li class="breadcrumb-item active">تكليف المواد</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3"><i class="fas fa-plus me-2"></i> إضافة أو تحديث تكليف جديد</h5>
            <form action="{{ route('subjects.assignments.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">الفصل الدراسي</label>
                    <select name="classroom_id" class="form-select" required>
                        <option value="">اختر الفصل</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected(old('classroom_id') == $classroom->id)>{{ $classroom->name }} @if($classroom->grade_level) ({{ $classroom->grade_level }}) @endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">المادة الدراسية</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">اختر المادة</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }} @if($subject->grade_level) ({{ $subject->grade_level }}) @endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">المعلم المكلف</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">بدون معلم</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }} @if($teacher->teacherProfile?->specialization) ({{ $teacher->teacherProfile->specialization }}) @endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($classrooms as $classroom)
            <div class="col-xl-4 col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">{{ $classroom->name }}</h5>
                            <div class="text-muted small">{{ $classroom->grade_level }}</div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary">{{ $classroom->subjects->count() }} مادة</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>المادة</th>
                                        <th>المعلم المكلف</th>
                                        <th class="text-center">حفظ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($classroom->subjects as $subject)
                                        @php($assignedTeacher = $teachers->firstWhere('id', $subject->pivot->teacher_id))
                                        <tr>
                                            <td>{{ $subject->name }}</td>
                                            <td>
                                                <form action="{{ route('subjects.assignments.store') }}" method="POST" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                                                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                                    <select name="teacher_id" class="form-select form-select-sm">
                                                        <option value="">بدون معلم</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" @selected($assignedTeacher && $assignedTeacher->id === $teacher->id)>
                                                                {{ $teacher->name }}
                                                                @if($teacher->teacherProfile?->specialization)
                                                                    ({{ $teacher->teacherProfile->specialization }})
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-muted">{{ $subject->teachers->count() }} معلم متاح</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">لا توجد مواد مرتبطة بهذا الفصل.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="text-muted small">رائد الفصل: {{ $classroom->homeroomTeacher?->name ?? 'غير محدد' }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">لا توجد فصول مسجلة لعرض التكليفات.</div>
            </div>
        @endforelse
    </div>
@endsection

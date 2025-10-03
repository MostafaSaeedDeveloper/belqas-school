@extends('admin.layouts.master')

@section('title', 'الجداول الدراسية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الجداول الدراسية للفصول')
    @section('page-subtitle', 'عرض توزيع المواد والمعلمين على الفصول الدراسية')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الفصول الدراسية</a></li>
        <li class="breadcrumb-item active">الجداول الدراسية</li>
    @endsection
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="fas fa-table me-2"></i> توزيع المواد على الفصول</h5>
        <a href="{{ route('subjects.assignments') }}" class="btn btn-outline-primary">
            <i class="fas fa-pen"></i> إدارة التكليفات
        </a>
    </div>

    <div class="row g-4">
        @forelse($classrooms as $classroom)
            <div class="col-xl-4 col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">
                                    @can('view_classes')
                                        <a href="{{ route('classes.show', $classroom) }}" class="resource-link">{{ $classroom->name }}</a>
                                    @else
                                        {{ $classroom->name }}
                                    @endcan
                                </h5>
                                <div class="text-muted small">{{ $classroom->grade_level }}</div>
                            </div>
                            <span class="badge bg-primary-subtle text-primary">{{ $classroom->subjects->count() }} مادة</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($classroom->subjects as $subject)
                                @php($assignedTeacher = $subject->teachers->firstWhere('id', $subject->pivot->teacher_id))
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">{{ $subject->name }}</div>
                                            <div class="text-muted small">{{ $subject->grade_level ?? 'غير محدد' }}</div>
                                        </div>
                                        <div class="text-end">
                                            @if($assignedTeacher)
                                                <div class="fw-semibold">
                                                    @can('view_teachers')
                                                        <a href="{{ route('teachers.show', $assignedTeacher) }}" class="resource-link">{{ $assignedTeacher->name }}</a>
                                                    @else
                                                        {{ $assignedTeacher->name }}
                                                    @endcan
                                                </div>
                                                <div class="text-muted small">{{ $assignedTeacher->teacherProfile?->specialization ?? '—' }}</div>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">بدون معلم</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">لا توجد مواد مسجلة للفصل.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                        <span class="text-muted small">رائد الفصل:
                            @if($classroom->homeroomTeacher)
                                @can('view_teachers')
                                    <a href="{{ route('teachers.show', $classroom->homeroomTeacher) }}" class="resource-link">{{ $classroom->homeroomTeacher->name }}</a>
                                @else
                                    {{ $classroom->homeroomTeacher->name }}
                                @endcan
                            @else
                                غير محدد
                            @endif
                        </span>
                        <span class="text-muted small">عدد الطلاب: {{ $classroom->students->count() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">لا توجد فصول مسجلة لعرض الجداول الدراسية.</div>
            </div>
        @endforelse
    </div>
@endsection

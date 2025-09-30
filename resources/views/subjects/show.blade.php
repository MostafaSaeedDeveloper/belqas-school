@extends('admin.layouts.master')

@section('title', 'تفاصيل المادة الدراسية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تفاصيل المادة الدراسية')
    @section('page-subtitle', 'متابعة تكليفات المعلمين والفصول')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">المواد الدراسية</a></li>
        <li class="breadcrumb-item active">{{ $subject->name }}</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-1">{{ $subject->name }}</h4>
                    <div class="text-muted mb-2">كود المادة: {{ $subject->code ?? '—' }}</div>
                    <div class="mb-2"><strong>الصف الدراسي:</strong> {{ $subject->grade_level ?? 'غير محدد' }}</div>
                    <div class="mb-2"><strong>عدد الفصول المطبق بها:</strong> {{ $subject->classrooms->count() }}</div>
                    <div class="mb-2"><strong>عدد المعلمين:</strong> {{ $subject->teachers->count() }}</div>
                    <div class="mb-2"><strong>إجمالي الطلاب:</strong> {{ $studentsCount }}</div>

                    <div class="d-grid gap-2 mt-4">
                        @can('edit_subjects')
                            <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> تعديل بيانات المادة
                            </a>
                        @endcan
                        <a href="{{ route('subjects.assignments') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-tasks"></i> إدارة التكليفات
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">وصف المادة</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $subject->description ?? 'لا يوجد وصف مسجل.' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">المعلمين المكلفين</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse($subject->teachers as $teacher)
                            <li class="mb-3">
                                <div class="fw-semibold">{{ $teacher->name }}</div>
                                <div class="text-muted small">{{ $teacher->teacherProfile?->specialization ?? '—' }}</div>
                                <div class="text-muted small" dir="ltr">{{ $teacher->email ?? $teacher->username }}</div>
                            </li>
                        @empty
                            <li class="text-muted">لا يوجد معلمين مرتبطين بهذه المادة.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">الفصول المرتبطة</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الفصل</th>
                                    <th>الصف</th>
                                    <th>رائد الفصل</th>
                                    <th>المعلم المكلف بالمادة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subject->classrooms as $classroom)
                                    @php($assignedTeacher = $subject->teachers->firstWhere('id', $classroom->pivot->teacher_id))
                                    <tr>
                                        <td>{{ $classroom->name }}</td>
                                        <td>{{ $classroom->grade_level }}</td>
                                        <td>{{ $classroom->homeroomTeacher?->name ?? 'غير محدد' }}</td>
                                        <td>
                                            @if($assignedTeacher)
                                                <div class="fw-semibold">{{ $assignedTeacher->name }}</div>
                                                <div class="text-muted small">{{ $assignedTeacher->teacherProfile?->specialization ?? '—' }}</div>
                                            @else
                                                <span class="text-muted">لم يتم تحديد معلم</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">لم يتم ربط المادة بأي فصل حتى الآن.</td>
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

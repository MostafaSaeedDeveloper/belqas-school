@extends('admin.layouts.master')

@section('title', 'تفاصيل الفصل - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تفاصيل الفصل الدراسي')
    @section('page-subtitle', 'متابعة توزيع الطلاب والمعلمين')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الفصول الدراسية</a></li>
        <li class="breadcrumb-item active">{{ $classroom->name }}</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1">{{ $classroom->name }}</h4>
                            <div class="text-muted">الصف الدراسي: {{ $classroom->grade_level }}</div>
                            @if($classroom->section)
                                <div class="text-muted">الشعبة: {{ $classroom->section }}</div>
                            @endif
                            @if($classroom->capacity)
                                <div class="text-muted">السعة الاستيعابية: {{ $classroom->capacity }}</div>
                            @endif
                        </div>
                        <div class="badge bg-primary">{{ $classroom->students->count() }} طالب</div>
                    </div>

                    <hr>

                    <h6 class="fw-semibold">رائد الفصل</h6>
                    @if($classroom->homeroomTeacher)
                        <div class="mb-2">{{ $classroom->homeroomTeacher->name }}</div>
                        <div class="text-muted small" dir="ltr">{{ $classroom->homeroomTeacher->email ?? $classroom->homeroomTeacher->username }}</div>
                    @else
                        <div class="text-muted">لم يتم تعيين رائد بعد.</div>
                    @endif

                    <div class="d-grid gap-2 mt-4">
                        @can('edit_classes')
                            <a href="{{ route('classes.edit', ['class' => $classroom->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> تعديل بيانات الفصل
                            </a>
                        @endcan
                        <a href="{{ route('subjects.assignments') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-book"></i> إدارة تكليف المواد
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">المعلمين المرتبطين</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse($classroom->teachers as $teacher)
                            <li class="mb-2">
                                <div class="fw-semibold">{{ $teacher->name }}</div>
                                <div class="text-muted small">{{ $teacher->teacherProfile?->specialization ?? '—' }}</div>
                            </li>
                        @empty
                            <li class="text-muted">لا يوجد معلمين مرتبطين بالفصل.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">المواد الدراسية</h5>
                    <a href="{{ route('subjects.assignments') }}" class="btn btn-sm btn-outline-primary">تحديث التكليفات</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>المادة</th>
                                    <th>الصف</th>
                                    <th>المعلم المكلف</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classroom->subjects as $subject)
                                    @php($assignedTeacher = $subject->teachers->firstWhere('id', $subject->pivot->teacher_id))
                                    <tr>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ $subject->grade_level ?? '—' }}</td>
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
                                        <td colspan="3" class="text-center text-muted py-4">لم يتم إسناد مواد لهذا الفصل بعد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">قائمة الطلاب</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الطالب</th>
                                    <th>الصف</th>
                                    <th>تاريخ الالتحاق</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classroom->students as $student)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <div class="text-muted small" dir="ltr">{{ $student->username }}</div>
                                        </td>
                                        <td>{{ $student->studentProfile?->grade_level ?? '—' }}</td>
                                        <td>{{ $student->studentProfile?->enrollment_date?->translatedFormat('d F Y') ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">لا يوجد طلاب مسجلون في هذا الفصل.</td>
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

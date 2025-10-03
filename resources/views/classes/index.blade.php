@extends('admin.layouts.master')

@section('title', 'الفصول الدراسية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة الفصول الدراسية')
    @section('page-subtitle', 'متابعة توزيع الطلاب والمعلمين على الفصول')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">الفصول الدراسية</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon classes"><i class="fas fa-school"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['total']) }}</div>
                    <div class="stat-label">إجمالي الفصول</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon teachers"><i class="fas fa-user-tie"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['with_homeroom']) }}</div>
                    <div class="stat-label">فصول بها رائد</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon students"><i class="fas fa-user-graduate"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['students']) }}</div>
                    <div class="stat-label">مقاعد مشغولة</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon subjects"><i class="fas fa-book"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['subjects']) }}</div>
                    <div class="stat-label">مواد مسندة</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i> قائمة الفصول الدراسية</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('classes.timetables') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-table"></i> الجداول الدراسية
                    </a>
                    @can('create_classes')
                        <a href="{{ route('classes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة فصل جديد
                        </a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>الفصل</th>
                            <th>الصف الدراسي</th>
                            <th>رائد الفصل</th>
                            <th class="text-center">الطلاب</th>
                            <th class="text-center">المواد</th>
                            <th class="text-center">المعلمين</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classrooms as $classroom)
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        @can('view_classes')
                                            <a href="{{ route('classes.show', $classroom) }}" class="resource-link">{{ $classroom->name }}</a>
                                        @else
                                            {{ $classroom->name }}
                                        @endcan
                                    </div>
                                    @if($classroom->section)
                                        <div class="text-muted small">الشعبة: {{ $classroom->section }}</div>
                                    @endif
                                    @if($classroom->capacity)
                                        <div class="text-muted small">السعة: {{ $classroom->capacity }}</div>
                                    @endif
                                </td>
                                <td>{{ $classroom->grade_level }}</td>
                                <td>
                                    @if($classroom->homeroomTeacher)
                                        <div class="fw-semibold">
                                            @can('view_teachers')
                                                <a href="{{ route('teachers.show', $classroom->homeroomTeacher) }}" class="resource-link">{{ $classroom->homeroomTeacher->name }}</a>
                                            @else
                                                {{ $classroom->homeroomTeacher->name }}
                                            @endcan
                                        </div>
                                        <div class="text-muted small" dir="ltr">{{ $classroom->homeroomTeacher->email ?? $classroom->homeroomTeacher->username }}</div>
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary">{{ $classroom->students_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info">{{ $classroom->subjects_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success">{{ $classroom->teachers_count }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view_classes')
                                            <a href="{{ route('classes.show', $classroom) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('edit_classes')
                                            <a href="{{ route('classes.edit', $classroom) }}" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_classes')
                                            <form action="{{ route('classes.destroy', $classroom) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا الفصل؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">لم يتم إضافة فصول بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $classrooms->links() }}
            </div>
        </div>
    </div>
@endsection

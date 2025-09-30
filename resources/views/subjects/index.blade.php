@extends('admin.layouts.master')

@section('title', 'المواد الدراسية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة المواد الدراسية')
    @section('page-subtitle', 'متابعة المواد وربطها بالمعلمين والفصول')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">المواد الدراسية</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon subjects"><i class="fas fa-book"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['total']) }}</div>
                    <div class="stat-label">إجمالي المواد</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon teachers"><i class="fas fa-chalkboard-teacher"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['with_teachers']) }}</div>
                    <div class="stat-label">مواد مرتبطة بمعلمين</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon classes"><i class="fas fa-layer-group"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['with_classes']) }}</div>
                    <div class="stat-label">مواد مرتبطة بفصول</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                <h5 class="mb-0"><i class="fas fa-book-open me-2"></i> قائمة المواد الدراسية</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('subjects.assignments') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-tasks"></i> تكليف المواد
                    </a>
                    @can('create_subjects')
                        <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة مادة جديدة
                        </a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>المادة</th>
                            <th>الصف الدراسي</th>
                            <th class="text-center">عدد المعلمين</th>
                            <th class="text-center">عدد الفصول</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $subject->name }}</div>
                                    <div class="text-muted small">{{ $subject->code ?? 'بدون كود' }}</div>
                                </td>
                                <td>{{ $subject->grade_level ?? 'غير محدد' }}</td>
                                <td class="text-center"><span class="badge bg-success-subtle text-success">{{ $subject->teachers_count }}</span></td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary">{{ $subject->classrooms_count }}</span></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view_subjects')
                                            <a href="{{ route('subjects.show', $subject) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('edit_subjects')
                                            <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_subjects')
                                            <form action="{{ route('subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذه المادة؟');">
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
                                <td colspan="5" class="text-center text-muted py-4">لم يتم إضافة مواد دراسية بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $subjects->links() }}
            </div>
        </div>
    </div>
@endsection

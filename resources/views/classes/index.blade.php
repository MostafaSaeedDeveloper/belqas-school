@extends('admin.layouts.master')

@section('title', 'الفصول الدراسية')
@section('page-title', 'قائمة الفصول الدراسية')
@section('page-subtitle', 'إدارة الفصول والشعب المرتبطة بها')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">الفصول الدراسية</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">جميع الفصول</h2>
            <p class="text-muted mb-0">يعرض هذا الجدول الفصول مع الصفوف المرتبطة وعدد الشعب المسجلة لكل فصل.</p>
        </div>
        @can('create_classes')
            <a href="{{ route('classes.create') }}" class="btn btn-primary">
                <i class="fas fa-door-open ms-1"></i>
                إضافة فصل جديد
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>رمز الفصل</th>
                            <th>اسم الفصل</th>
                            <th>الصف الدراسي</th>
                            <th>عدد الشعب</th>
                            <th>السعة القصوى</th>
                            <th>آخر تحديث</th>
                            <th class="text-end">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td class="fw-semibold">{{ $class->code ?? '—' }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('classes.show', $class) }}" class="fw-semibold text-decoration-none">{{ $class->name }}</a>
                                        <span class="text-muted small">تم إنشاء الفصل في {{ optional($class->created_at)->format('Y/m/d') }}</span>
                                    </div>
                                </td>
                                <td>{{ optional($class->grade)->name ?? 'غير محدد' }}</td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">{{ $class->sections_count }}</span>
                                </td>
                                <td>{{ $class->capacity ? $class->capacity . ' طالب' : 'غير محدد' }}</td>
                                <td>{{ optional($class->updated_at)->diffForHumans() }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('classes.show', $class) }}" class="btn btn-sm btn-light">عرض</a>
                                        @can('edit_classes')
                                            <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-warning">تعديل</a>
                                        @endcan
                                        @can('delete_classes')
                                            <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الفصل مع الشعب المرتبطة به؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-door-closed fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">لا توجد فصول مسجلة حتى الآن. يمكنك البدء بإضافة فصل جديد.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($classes->hasPages())
            <div class="card-footer">
                {{ $classes->links() }}
            </div>
        @endif
    </div>
@endsection

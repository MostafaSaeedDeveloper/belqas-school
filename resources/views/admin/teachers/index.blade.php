@extends('admin.layouts.master')

@section('title', 'إدارة المعلمين')

@section('page-header')
    @section('page-title', 'إدارة المعلمين')
    @section('page-subtitle', 'متابعة بيانات الكادر التعليمي وتعيينهم')
    @section('breadcrumb')
        <li class="breadcrumb-item active">المعلمين</li>
    @endsection
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-0">
                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                قائمة المعلمين
            </h5>
            <small class="text-muted">{{ $teachers->total() }} معلم/ة</small>
        </div>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="search" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد"
                           value="{{ $filters['search'] }}">
                </div>
                <button class="btn btn-outline-primary" type="submit">بحث</button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTeacherModal">
                <i class="fas fa-plus-circle me-1"></i>
                إضافة معلم
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>المعلم</th>
                    <th>البريد</th>
                    <th>الهاتف</th>
                    <th>التخصص</th>
                    <th>الفصول المشرف عليها</th>
                    <th>المواد</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $teacher->first_name }} {{ $teacher->last_name }}</div>
                            <small class="text-muted">تاريخ التعيين: {{ optional($teacher->hire_date)->format('Y-m-d') ?: '—' }}</small>
                        </td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->phone ?: '—' }}</td>
                        <td>{{ $teacher->specialization ?: '—' }}</td>
                        <td>
                            @if($teacher->classrooms->isEmpty())
                                <span class="text-muted">لا يوجد</span>
                            @else
                                <span class="badge bg-light text-dark border">{{ $teacher->classrooms->count() }}</span>
                            @endif
                        </td>
                        <td>
                            @if($teacher->subjects->isEmpty())
                                <span class="text-muted">لا يوجد</span>
                            @else
                                <span class="badge bg-light text-dark border">{{ $teacher->subjects->count() }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editTeacherModal{{ $teacher->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('تأكيد حذف المعلم؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editTeacherModal{{ $teacher->id }}" tabindex="-1" aria-labelledby="editTeacherLabel{{ $teacher->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editTeacherLabel{{ $teacher->id }}">تعديل بيانات المعلم</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                </div>
                                <form action="{{ route('teachers.update', $teacher) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        @include('admin.teachers.partials.form-fields', ['teacher' => $teacher])
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
                        <td colspan="7" class="text-center text-muted py-5">لا توجد بيانات معلمين.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teachers->hasPages())
        <div class="card-footer border-0">
            {{ $teachers->links() }}
        </div>
    @endif
</div>

<div class="modal fade" id="createTeacherModal" tabindex="-1" aria-labelledby="createTeacherLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTeacherLabel">إضافة معلم جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('teachers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.teachers.partials.form-fields', ['teacher' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ المعلم</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

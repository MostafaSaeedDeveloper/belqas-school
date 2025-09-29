@extends('admin.layouts.master')

@section('title', 'إدارة المواد الدراسية')

@section('page-header')
    @section('page-title', 'إدارة المواد الدراسية')
    @section('page-subtitle', 'تنظيم المواد وإسنادها للمعلمين والفصول')
    @section('breadcrumb')
        <li class="breadcrumb-item active">المواد</li>
    @endsection
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-0">
                <i class="fas fa-book-open me-2 text-primary"></i>
                قائمة المواد
            </h5>
            <small class="text-muted">{{ $subjects->total() }} مادة دراسية</small>
        </div>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="teacher_id" class="form-select">
                    <option value="">كل المعلمين</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected((string) $filters['teacher_id'] === (string) $teacher->id)>
                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-primary">تصفية</button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                <i class="fas fa-plus-circle me-1"></i>
                إضافة مادة
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>المادة</th>
                    <th>الرمز</th>
                    <th>المعلم المسؤول</th>
                    <th>عدد الفصول المرتبطة</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                    <tr>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->code }}</td>
                        <td>{{ optional($subject->teacher)->first_name ? ($subject->teacher->first_name . ' ' . $subject->teacher->last_name) : 'غير محدد' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $subject->classrooms_count }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('subjects.show', $subject) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editSubjectModal{{ $subject->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('تأكيد حذف المادة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1" aria-labelledby="editSubjectLabel{{ $subject->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSubjectLabel{{ $subject->id }}">تعديل بيانات المادة</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                </div>
                                <form action="{{ route('subjects.update', $subject) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        @include('admin.subjects.partials.form-fields', ['subject' => $subject, 'teachers' => $teachers, 'classrooms' => $classrooms])
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
                        <td colspan="5" class="text-center text-muted py-5">لا توجد مواد مسجلة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subjects->hasPages())
        <div class="card-footer border-0">
            {{ $subjects->links() }}
        </div>
    @endif
</div>

<div class="modal fade" id="createSubjectModal" tabindex="-1" aria-labelledby="createSubjectLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubjectLabel">إضافة مادة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.subjects.partials.form-fields', ['subject' => null, 'teachers' => $teachers, 'classrooms' => $classrooms])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ المادة</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

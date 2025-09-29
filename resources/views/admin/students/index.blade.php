@extends('admin.layouts.master')

@section('title', 'إدارة الطلاب')

@section('page-header')
    @section('page-title', 'إدارة الطلاب')
    @section('page-subtitle', 'متابعة بيانات الطلاب المسجلين')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الطلاب</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-graduate me-2 text-primary"></i>
                        قائمة الطلاب
                    </h5>
                    <small class="text-muted">{{ $students->total() }} طالب مسجل</small>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudentModal">
                    <i class="fas fa-plus-circle me-1"></i>
                    إضافة طالب جديد
                </button>
            </div>
            <div class="card-body border-top">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="search" class="form-label">بحث بالاسم</label>
                        <input type="search" name="search" id="search" class="form-control"
                               value="{{ $filters['search'] }}" placeholder="اكتب اسم الطالب...">
                    </div>
                    <div class="col-md-5">
                        <label for="filter_classroom" class="form-label">تصفية حسب الفصل</label>
                        <select name="classroom_id" id="filter_classroom" class="form-select">
                            <option value="">جميع الفصول</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}"
                                    @selected((string) $filters['classroom_id'] === (string) $classroom->id)>
                                    {{ $classroom->name }} - {{ $classroom->grade_level }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-filter"></i>
                            تطبيق
                        </button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>الطالب</th>
                            <th>الفصل</th>
                            <th>ولي الأمر</th>
                            <th>الهاتف</th>
                            <th class="text-center">تاريخ الالتحاق</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</div>
                                    <small class="text-muted d-block">{{ $student->gender === 'male' ? 'ذكر' : ($student->gender === 'female' ? 'أنثى' : 'غير محدد') }}</small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border">
                                        {{ optional($student->classroom)->name ?? 'غير مسند' }}
                                    </span>
                                </td>
                                <td>{{ $student->guardian_name ?: '-' }}</td>
                                <td>{{ $student->guardian_phone ?: '-' }}</td>
                                <td class="text-center">{{ optional($student->admission_date)->format('Y-m-d') ?: '—' }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#editStudentModal{{ $student->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('تأكيد حذف الطالب؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1" aria-labelledby="editStudentModalLabel{{ $student->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editStudentModalLabel{{ $student->id }}">تعديل بيانات الطالب</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                        </div>
                                        <form action="{{ route('students.update', $student) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                @include('admin.students.partials.form-fields', ['student' => $student, 'classrooms' => $classrooms])
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
                                <td colspan="6" class="text-center py-5 text-muted">لا توجد بيانات طلاب مطابقة للبحث الحالي.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($students->hasPages())
                <div class="card-footer border-0">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2 text-info"></i>
                    ملخص سريع
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span>إجمالي الطلاب</span>
                        <strong>{{ number_format($students->total()) }}</strong>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span>عدد الفصول المتاحة</span>
                        <strong>{{ $classrooms->count() }}</strong>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-2">
                        <span>أحدث تحديث</span>
                        <strong>{{ optional($students->first()?->updated_at)->diffForHumans() ?? '—' }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createStudentModal" tabindex="-1" aria-labelledby="createStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStudentModalLabel">إضافة طالب جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.students.partials.form-fields', ['student' => null, 'classrooms' => $classrooms])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الطالب</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

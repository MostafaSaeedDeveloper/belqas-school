@extends('admin.layouts.master')

@section('title', 'إدارة الفصول الدراسية')

@section('page-header')
    @section('page-title', 'إدارة الفصول الدراسية')
    @section('page-subtitle', 'تنظيم الفصول ورؤساء الفصول وتوزيع الطلاب')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الفصول</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-0">
                        <i class="fas fa-school me-2 text-primary"></i>
                        قائمة الفصول
                    </h5>
                    <small class="text-muted">{{ $classrooms->total() }} فصل</small>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClassroomModal">
                    <i class="fas fa-plus-circle me-1"></i>
                    إضافة فصل
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>الفصل</th>
                            <th>المرحلة</th>
                            <th>رائد الفصل</th>
                            <th class="text-center">عدد الطلاب</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classrooms as $classroom)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $classroom->name }}</div>
                                    <small class="text-muted">الغرفة: {{ $classroom->room_number ?: '—' }} | الشعبة: {{ $classroom->section ?: '—' }}</small>
                                </td>
                                <td>{{ $classroom->grade_level }}</td>
                                <td>{{ optional($classroom->homeroomTeacher)->first_name ? ($classroom->homeroomTeacher->first_name . ' ' . $classroom->homeroomTeacher->last_name) : 'غير محدد' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $classroom->students_count }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('classrooms.show', $classroom) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#editClassroomModal{{ $classroom->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" onsubmit="return confirm('تأكيد حذف الفصل؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="editClassroomModal{{ $classroom->id }}" tabindex="-1" aria-labelledby="editClassroomLabel{{ $classroom->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editClassroomLabel{{ $classroom->id }}">تعديل بيانات الفصل</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                        </div>
                                        <form action="{{ route('classrooms.update', $classroom) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                @include('admin.classrooms.partials.form-fields', ['classroom' => $classroom, 'teachers' => $teachers])
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
                                <td colspan="5" class="text-center text-muted py-5">لا توجد فصول دراسية مسجلة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($classrooms->hasPages())
                <div class="card-footer border-0">
                    {{ $classrooms->links() }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2 text-success"></i>
                    توزيع الفصول حسب المرحلة
                </h5>
            </div>
            <div class="card-body">
                @php($byGrade = $classrooms->getCollection()->groupBy('grade_level'))
                <ul class="list-unstyled mb-0">
                    @forelse($byGrade as $grade => $items)
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>{{ $grade }}</span>
                            <span class="badge bg-light text-dark border">{{ $items->count() }} فصل</span>
                        </li>
                    @empty
                        <li class="text-muted text-center py-3">لا توجد بيانات كافية للعرض.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createClassroomModal" tabindex="-1" aria-labelledby="createClassroomLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createClassroomLabel">إنشاء فصل جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('classrooms.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.classrooms.partials.form-fields', ['classroom' => null, 'teachers' => $teachers])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الفصل</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

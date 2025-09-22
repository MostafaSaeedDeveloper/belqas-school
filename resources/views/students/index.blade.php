@extends('admin.layouts.master')

@section('title', 'الطلاب')
@section('page-title', 'قائمة الطلاب')
@section('page-subtitle', 'إدارة جميع بيانات الطلاب المسجلين')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">الطلاب</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">سجل الطلاب</h2>
            <p class="text-muted mb-0">يعرض جميع الطلاب مع بياناتهم الأكاديمية الحالية.</p>
        </div>
        @can('create_students')
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus ms-1"></i>
                إضافة طالب جديد
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>رقم الطالب</th>
                            <th>اسم الطالب</th>
                            <th>الصف</th>
                            <th>الفصل</th>
                            <th>الشعبة</th>
                            <th>حالة القيد</th>
                            <th class="text-end">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="fw-semibold">{{ $student->student_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            @if($student->profile_photo_url)
                                                <img src="{{ $student->profile_photo_url }}" alt="{{ $student->display_name }}" class="rounded-circle" width="40" height="40">
                                            @else
                                                <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex justify-content-center align-items-center" style="width:40px;height:40px;">
                                                    {{ mb_substr($student->display_name, 0, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('students.show', $student) }}" class="fw-semibold text-decoration-none">{{ $student->display_name }}</a>
                                            <div class="small text-muted">{{ $student->email ?: 'بلا بريد إلكتروني' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ optional($student->grade)->name ?: '-' }}</td>
                                <td>{{ optional($student->classRoom)->name ?: '-' }}</td>
                                <td>{{ optional($student->section)->name ?: '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $student->status_badge_class }}">{{ $student->status_label }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-light">عرض</a>
                                        @can('edit_students')
                                            <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning">تعديل</a>
                                        @endcan
                                        @can('delete_students')
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟');">
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
                                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">لا توجد بيانات طلاب مسجلة بعد.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($students->hasPages())
            <div class="card-footer">
                {{ $students->links() }}
            </div>
        @endif
    </div>
@endsection

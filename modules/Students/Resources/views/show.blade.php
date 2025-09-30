@extends('admin.layouts.master')

@section('title', 'ملف الطالب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'ملف الطالب')
    @section('page-subtitle', 'عرض تفاصيل الطالب وبيانات ولي الأمر')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">{{ $student->name }}</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <img src="{{ $student->avatar_url }}" class="rounded-circle mb-3" width="120" height="120" alt="{{ $student->name }}">
                    <h4 class="mb-1">{{ $student->name }}</h4>
                    <p class="text-muted mb-2" dir="ltr">{{ $student->username }}</p>
                    <div class="mb-3">
                        <span class="badge {{ $student->active ? 'bg-success' : 'bg-danger' }}">{{ $student->active ? 'نشط' : 'موقوف' }}</span>
                    </div>
                    <div class="d-grid gap-2">
                        @can('edit_students')
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> تعديل البيانات
                            </a>
                        @endcan
                        @can('delete_students')
                            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('هل تريد حذف الطالب؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i> حذف الطالب
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
                <div class="card-footer text-muted" dir="ltr">
                    آخر تحديث {{ $student->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">المعلومات الأكاديمية</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted">الصف الدراسي</label>
                            <div class="fw-semibold">{{ $student->studentProfile?->grade_level ?? 'غير محدد' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">الفصل</label>
                            <div class="fw-semibold">{{ $student->studentProfile?->classroom ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">تاريخ الالتحاق</label>
                            <div class="fw-semibold">{{ $student->studentProfile?->formatted_enrollment_date ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">الرقم التعريفي</label>
                            <div class="fw-semibold">{{ $student->studentProfile?->student_code ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">تاريخ الميلاد</label>
                            <div class="fw-semibold">{{ $student->studentProfile?->formatted_birth_date ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">الجنس</label>
                            <div class="fw-semibold">
                                @switch($student->studentProfile?->gender)
                                    @case('male') ذكر @break
                                    @case('female') أنثى @break
                                    @default غير محدد
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">بيانات التواصل</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted">البريد الإلكتروني</label>
                            <div class="fw-semibold" dir="ltr">{{ $student->email ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">رقم الهاتف</label>
                            <div class="fw-semibold" dir="ltr">{{ $student->phone ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">العنوان</label>
                            <div class="fw-semibold">{{ $student->studentProfile?->address ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">بيانات ولي الأمر</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted">اسم ولي الأمر</label>
                            <div class="fw-semibold">{{ $student->studentProfile?->guardian_name ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">هاتف ولي الأمر</label>
                            <div class="fw-semibold" dir="ltr">{{ $student->studentProfile?->guardian_phone ?? '—' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted">ملاحظات</label>
                            <div>{{ $student->studentProfile?->notes ?? 'لا توجد ملاحظات' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

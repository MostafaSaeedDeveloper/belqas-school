@extends('admin.layouts.master')

@section('title', 'ملف المعلم - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'ملف المعلم')
    @section('page-subtitle', 'عرض تفاصيل المعلم وسجله الوظيفي')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">المعلمين</a></li>
        <li class="breadcrumb-item active">{{ $teacher->name }}</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <img src="{{ $teacher->avatar_url }}" class="rounded-circle mb-3" width="120" height="120" alt="{{ $teacher->name }}">
                    <h4 class="mb-1">{{ $teacher->name }}</h4>
                    <p class="text-muted mb-2" dir="ltr">{{ $teacher->username }}</p>
                    <span class="badge {{ $teacher->active ? 'bg-success' : 'bg-danger' }}">{{ $teacher->active ? 'نشط' : 'موقوف' }}</span>
                    <div class="mt-3 text-start">
                        <div class="mb-2"><strong>التخصص:</strong> {{ $teacher->teacherProfile?->specialization ?? 'غير محدد' }}</div>
                        <div class="mb-2"><strong>المؤهل:</strong> {{ $teacher->teacherProfile?->qualification ?? '—' }}</div>
                        <div class="mb-2"><strong>المواد:</strong> {{ $teacher->teacherProfile?->subjects_list ?? '—' }}</div>
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        @can('edit_teachers')
                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> تعديل البيانات
                            </a>
                        @endcan
                        @can('delete_teachers')
                            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('هل تريد حذف المعلم؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i> حذف المعلم
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
                <div class="card-footer text-muted" dir="ltr">
                    آخر تحديث {{ $teacher->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">البيانات الوظيفية</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted">الرقم الوظيفي</label>
                            <div class="fw-semibold">{{ $teacher->teacherProfile?->teacher_code ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">تاريخ التعيين</label>
                            <div class="fw-semibold">{{ $teacher->teacherProfile?->formatted_hire_date ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">سنوات الخبرة</label>
                            <div class="fw-semibold">{{ $teacher->teacherProfile?->experience_years ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">ساعات التواجد</label>
                            <div class="fw-semibold">{{ $teacher->teacherProfile?->office_hours ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">النوع</label>
                            <div class="fw-semibold">
                                @switch($teacher->teacherProfile?->gender)
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
                            <div class="fw-semibold" dir="ltr">{{ $teacher->email ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">رقم الهاتف</label>
                            <div class="fw-semibold" dir="ltr">{{ $teacher->phone ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">هاتف إضافي</label>
                            <div class="fw-semibold" dir="ltr">{{ $teacher->teacherProfile?->phone_secondary ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">العنوان</label>
                            <div class="fw-semibold">{{ $teacher->teacherProfile?->address ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">ملاحظات إضافية</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $teacher->teacherProfile?->notes ?? 'لا توجد ملاحظات.' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

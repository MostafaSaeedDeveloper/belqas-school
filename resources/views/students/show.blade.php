@extends('admin.layouts.master')

@section('title', $student->display_name)
@section('page-title', 'ملف الطالب')
@section('page-subtitle', 'عرض جميع البيانات المسجلة للطالب في النظام')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $student->display_name }}</li>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-12 col-xl-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="position-relative mb-3">
                        @if($student->profile_photo_url)
                            <img src="{{ $student->profile_photo_url }}" alt="{{ $student->display_name }}" class="img-fluid rounded shadow-sm" style="max-height: 240px;">
                        @else
                            <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex justify-content-center align-items-center mx-auto" style="width:120px;height:120px;">
                                {{ mb_substr($student->display_name, 0, 2) }}
                            </div>
                        @endif
                    </div>
                    <h3 class="h4 mb-1">{{ $student->display_name }}</h3>
                    <p class="text-muted mb-2">رقم الطالب: {{ $student->student_id }}</p>
                    <span class="badge bg-{{ $student->status_badge_class }}">{{ $student->status_label }}</span>

                    <div class="mt-4 d-grid gap-2">
                        @can('edit_students')
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">
                                <i class="fas fa-edit ms-1"></i>
                                تعديل البيانات
                            </a>
                        @endcan
                        <a href="{{ route('students.index') }}" class="btn btn-light">عودة لقائمة الطلاب</a>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">بيانات الحساب المرتبط</h5>
                </div>
                <div class="card-body">
                    @if($student->user)
                        <div class="mb-2">
                            <span class="text-muted">اسم المستخدم:</span>
                            <span class="fw-semibold">{{ $student->user->username }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">البريد الإلكتروني:</span>
                            <span class="fw-semibold">{{ $student->user->email }}</span>
                        </div>
                        <div class="mb-0">
                            <span class="text-muted">الدور:</span>
                            <span class="fw-semibold">{{ $student->user->getRoleNames()->first() ?? 'طالب' }}</span>
                        </div>
                    @else
                        <p class="text-muted mb-0">لا يوجد حساب مرتبط بهذا الطالب.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">البيانات الأساسية</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">الاسم الكامل</span>
                            <span class="fw-semibold">{{ $student->full_name ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">النوع</span>
                            <span class="fw-semibold">{{ $student->gender === 'male' ? 'ذكر' : 'أنثى' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">تاريخ الميلاد</span>
                            <span class="fw-semibold">{{ optional($student->date_of_birth)->format('Y-m-d') ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">الجنسية</span>
                            <span class="fw-semibold">{{ $student->nationality ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">رقم الهوية</span>
                            <span class="fw-semibold">{{ $student->national_id ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">رقم الباسبور</span>
                            <span class="fw-semibold">{{ $student->passport_number ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">فصيلة الدم</span>
                            <span class="fw-semibold">{{ $student->blood_type ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">الديانة</span>
                            <span class="fw-semibold">{{ $student->religion ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">بيانات الاتصال والعنوان</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">رقم الجوال</span>
                            <span class="fw-semibold">{{ $student->phone ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">البريد الإلكتروني</span>
                            <span class="fw-semibold">{{ $student->email ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">الدولة</span>
                            <span class="fw-semibold">{{ $student->country ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">المدينة</span>
                            <span class="fw-semibold">{{ $student->city ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">المحافظة / المنطقة</span>
                            <span class="fw-semibold">{{ $student->state ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">الرمز البريدي</span>
                            <span class="fw-semibold">{{ $student->postal_code ?: '—' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block mb-1">العنوان</span>
                            <span class="fw-semibold">{{ $student->address ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">بيانات ولي الأمر</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">اسم ولي الأمر</span>
                            <span class="fw-semibold">{{ $student->guardian_name ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">صلة القرابة</span>
                            <span class="fw-semibold">{{ $student->guardian_relation ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">رقم الجوال</span>
                            <span class="fw-semibold">{{ $student->guardian_phone ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">البريد الإلكتروني</span>
                            <span class="fw-semibold">{{ $student->guardian_email ?: '—' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block mb-1">عنوان ولي الأمر</span>
                            <span class="fw-semibold">{{ $student->guardian_address ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">البيانات الأكاديمية</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">تاريخ الالتحاق</span>
                            <span class="fw-semibold">{{ optional($student->admission_date)->format('Y-m-d') ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">رقم القيد</span>
                            <span class="fw-semibold">{{ $student->admission_number ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">الصف الدراسي</span>
                            <span class="fw-semibold">{{ optional($student->grade)->name ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">الفصل</span>
                            <span class="fw-semibold">{{ optional($student->classRoom)->name ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">الشعبة</span>
                            <span class="fw-semibold">{{ optional($student->section)->name ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">رقم الجلوس</span>
                            <span class="fw-semibold">{{ $student->roll_number ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">السنة الدراسية</span>
                            <span class="fw-semibold">{{ $student->academic_year ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">بيانات إضافية</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">وسيلة المواصلات</span>
                            <span class="fw-semibold">{{ $student->transportation ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block mb-1">المدرسة السابقة</span>
                            <span class="fw-semibold">{{ $student->previous_school ?: '—' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block mb-1">ملاحظات طبية</span>
                            <span class="fw-semibold">{{ $student->medical_info ?: '—' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block mb-1">ملاحظات عامة</span>
                            <span class="fw-semibold">{{ $student->notes ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.master')

@section('title', 'تفاصيل الطالب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'ملف الطالب')
    @section('page-subtitle', 'عرض تفاصيل الطالب والمتابعة الأكاديمية')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">{{ $student->name }}</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="avatar-placeholder avatar-xl bg-primary text-white mx-auto mb-3">
                        {{ $student->initials }}
                    </div>
                    <h4 class="mb-1">{{ $student->name }}</h4>
                    <p class="text-muted mb-3">{{ $student->grade_level }} {{ $student->classroom ? ' - ' . $student->classroom : '' }}</p>
                    <span class="badge bg-{{ $student->status === 'active' ? 'success' : ($student->status === 'inactive' ? 'warning' : 'info') }} mb-3">{{ $student->status_label }}</span>

                    <div class="d-grid gap-2">
                        @can('edit_students')
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> تعديل البيانات
                            </a>
                        @endcan
                        @can('delete_students')
                            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف سجل الطالب؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i> حذف الطالب</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> المعلومات الأساسية</h5>
                    <span class="text-muted" dir="ltr">{{ $student->student_code }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>الاسم بالإنجليزية:</strong>
                            <div class="text-muted">{{ $student->english_name ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>الجنس:</strong>
                            <div class="text-muted">{{ $student->gender_label ?? 'غير محدد' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>تاريخ الميلاد:</strong>
                            <div class="text-muted">{{ $student->birth_date ? $student->birth_date->format('Y-m-d') : '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>العمر:</strong>
                            <div class="text-muted">{{ $student->age ? $student->age . ' سنة' : '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>الرقم القومي:</strong>
                            <div class="text-muted" dir="ltr">{{ $student->national_id ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>تاريخ القبول:</strong>
                            <div class="text-muted">{{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>البريد الإلكتروني:</strong>
                            <div class="text-muted" dir="ltr">{{ $student->email ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>رقم الجوال:</strong>
                            <div class="text-muted" dir="ltr">{{ $student->phone ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i> بيانات ولي الأمر</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>اسم ولي الأمر:</strong>
                            <div class="text-muted">{{ $student->guardian_name ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>صلة القرابة:</strong>
                            <div class="text-muted">{{ $student->guardian_relationship ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>هاتف ولي الأمر:</strong>
                            <div class="text-muted" dir="ltr">{{ $student->guardian_phone ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>وسيلة المواصلات:</strong>
                            <div class="text-muted">{{ $student->transportation_method ?? '—' }}</div>
                        </div>
                        <div class="col-md-12">
                            <strong>العنوان:</strong>
                            <div class="text-muted">{{ $student->address ? $student->address . '، ' . $student->city : ($student->city ?? '—') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i> ملاحظات إضافية</h5>
                    <span class="badge bg-secondary">الرصيد المستحق: {{ number_format($student->outstanding_fees, 2) }} ج.م</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>ملاحظات طبية:</strong>
                        <p class="text-muted mb-0">{{ $student->medical_notes ? nl2br(e($student->medical_notes)) : 'لا توجد ملاحظات طبية.' }}</p>
                    </div>
                    <div>
                        <strong>ملاحظات إدارية:</strong>
                        <p class="text-muted mb-0">{{ $student->notes ? nl2br(e($student->notes)) : 'لا توجد ملاحظات إضافية.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.master')

@section('title', 'بيانات الطالب')

@section('page-header')
    @section('page-title', 'بيانات الطالب')
    @section('page-subtitle', $student->first_name . ' ' . $student->last_name)
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">عرض</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-circle me-2 text-primary"></i>
                    معلومات أساسية
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">الاسم الكامل</dt>
                    <dd class="col-7">{{ $student->first_name }} {{ $student->last_name }}</dd>

                    <dt class="col-5 text-muted">النوع</dt>
                    <dd class="col-7">{{ $student->gender === 'male' ? 'ذكر' : ($student->gender === 'female' ? 'أنثى' : 'غير محدد') }}</dd>

                    <dt class="col-5 text-muted">تاريخ الميلاد</dt>
                    <dd class="col-7">{{ optional($student->birth_date)->format('Y-m-d') ?: '—' }}</dd>

                    <dt class="col-5 text-muted">تاريخ الالتحاق</dt>
                    <dd class="col-7">{{ optional($student->admission_date)->format('Y-m-d') ?: '—' }}</dd>

                    <dt class="col-5 text-muted">الفصل الحالي</dt>
                    <dd class="col-7">{{ optional($student->classroom)->name ?? 'غير مسند' }}</dd>
                </dl>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-phone me-2 text-success"></i>
                    بيانات التواصل
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">ولي الأمر</dt>
                    <dd class="col-7">{{ $student->guardian_name ?: '—' }}</dd>

                    <dt class="col-5 text-muted">هاتف ولي الأمر</dt>
                    <dd class="col-7">{{ $student->guardian_phone ?: '—' }}</dd>

                    <dt class="col-5 text-muted">العنوان</dt>
                    <dd class="col-7">{{ $student->address ?: '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-school me-2 text-info"></i>
                    قيود الطالب الحالية
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>الفصل</th>
                                <th>الحالة</th>
                                <th>تاريخ القيد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->classroom->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $enrollment->active ? 'success' : 'secondary' }}">
                                            {{ $enrollment->active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>{{ optional($enrollment->enrolled_at)->format('Y-m-d') ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">لا توجد قيود دراسية للطالب.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-right"></i>
            الرجوع لقائمة الطلاب
        </a>
    </div>
</div>
@endsection

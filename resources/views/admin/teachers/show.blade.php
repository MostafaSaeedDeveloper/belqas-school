@extends('admin.layouts.master')

@section('title', 'بيانات المعلم')

@section('page-header')
    @section('page-title', 'بيانات المعلم')
    @section('page-subtitle', $teacher->first_name . ' ' . $teacher->last_name)
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">المعلمين</a></li>
        <li class="breadcrumb-item active">عرض</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-id-card me-2 text-primary"></i>
                    المعلومات الأساسية
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">الاسم الكامل</dt>
                    <dd class="col-7">{{ $teacher->first_name }} {{ $teacher->last_name }}</dd>

                    <dt class="col-5 text-muted">البريد الإلكتروني</dt>
                    <dd class="col-7">{{ $teacher->email }}</dd>

                    <dt class="col-5 text-muted">رقم الهاتف</dt>
                    <dd class="col-7">{{ $teacher->phone ?: '—' }}</dd>

                    <dt class="col-5 text-muted">تاريخ التعيين</dt>
                    <dd class="col-7">{{ optional($teacher->hire_date)->format('Y-m-d') ?: '—' }}</dd>

                    <dt class="col-5 text-muted">التخصص</dt>
                    <dd class="col-7">{{ $teacher->specialization ?: '—' }}</dd>
                </dl>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sticky-note me-2 text-warning"></i>
                    ملاحظات إضافية
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $teacher->notes ?: 'لا توجد ملاحظات.' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-school me-2 text-info"></i>
                    الفصول المشرف عليها
                </h5>
                <span class="badge bg-light text-dark border">{{ $teacher->classrooms->count() }} فصل</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>اسم الفصل</th>
                                <th>المرحلة</th>
                                <th>عدد الطلاب</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teacher->classrooms as $classroom)
                                <tr>
                                    <td>{{ $classroom->name }}</td>
                                    <td>{{ $classroom->grade_level }}</td>
                                    <td>{{ $classroom->students()->count() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">لا توجد فصول مسندة.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-book-open me-2 text-success"></i>
                    المواد التي يدرسها
                </h5>
                <span class="badge bg-light text-dark border">{{ $teacher->subjects->count() }} مادة</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>المادة</th>
                                <th>الرمز</th>
                                <th>آخر تحديث</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teacher->subjects as $subject)
                                <tr>
                                    <td>{{ $subject->name }}</td>
                                    <td>{{ $subject->code }}</td>
                                    <td>{{ optional($subject->updated_at)->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">لم يتم إسناد مواد بعد.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{ route('teachers.index') }}" class="btn btn-outline-primary mt-4">
            <i class="fas fa-arrow-right"></i>
            الرجوع لقائمة المعلمين
        </a>
    </div>
</div>
@endsection

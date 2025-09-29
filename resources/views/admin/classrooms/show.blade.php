@extends('admin.layouts.master')

@section('title', 'بيانات الفصل الدراسي')

@section('page-header')
    @section('page-title', 'بيانات الفصل الدراسي')
    @section('page-subtitle', $classroom->name)
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('classrooms.index') }}">الفصول</a></li>
        <li class="breadcrumb-item active">عرض</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    المعلومات الأساسية
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">الاسم</dt>
                    <dd class="col-7">{{ $classroom->name }}</dd>

                    <dt class="col-5 text-muted">المرحلة</dt>
                    <dd class="col-7">{{ $classroom->grade_level }}</dd>

                    <dt class="col-5 text-muted">الشعبة</dt>
                    <dd class="col-7">{{ $classroom->section ?: '—' }}</dd>

                    <dt class="col-5 text-muted">الغرفة</dt>
                    <dd class="col-7">{{ $classroom->room_number ?: '—' }}</dd>

                    <dt class="col-5 text-muted">رائد الفصل</dt>
                    <dd class="col-7">{{ optional($classroom->homeroomTeacher)->first_name ? ($classroom->homeroomTeacher->first_name . ' ' . $classroom->homeroomTeacher->last_name) : 'غير محدد' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2 text-success"></i>
                    الطلاب في الفصل
                </h5>
                <span class="badge bg-light text-dark border">{{ $classroom->students->count() }} طالب</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>تاريخ الالتحاق</th>
                                <th>وسيلة التواصل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classroom->students as $student)
                                <tr>
                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td>{{ optional($student->admission_date)->format('Y-m-d') ?: '—' }}</td>
                                    <td>{{ $student->guardian_phone ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">لا يوجد طلاب مسجلون في هذا الفصل حالياً.</td>
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
                    <i class="fas fa-book me-2 text-info"></i>
                    المواد المرتبطة بالفصل
                </h5>
                <span class="badge bg-light text-dark border">{{ $classroom->subjects->count() }} مادة</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>المادة</th>
                                <th>المعلم</th>
                                <th>آخر تحديث</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classroom->subjects as $subject)
                                <tr>
                                    <td>{{ $subject->name }}</td>
                                    <td>{{ optional($subject->teacher)->first_name ? ($subject->teacher->first_name . ' ' . $subject->teacher->last_name) : 'غير محدد' }}</td>
                                    <td>{{ optional($subject->pivot?->updated_at)->diffForHumans() ?? optional($subject->updated_at)->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">لم يتم إسناد مواد لهذا الفصل بعد.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{ route('classrooms.index') }}" class="btn btn-outline-primary mt-4">
            <i class="fas fa-arrow-right"></i>
            الرجوع لقائمة الفصول
        </a>
    </div>
</div>
@endsection

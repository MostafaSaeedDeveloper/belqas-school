@extends('admin.layouts.master')

@section('title', 'جداول المعلمين - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'جداول المعلمين')
    @section('page-subtitle', 'نظرة شاملة على توزيع الحصص وساعات التواجد')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">المعلمين</a></li>
        <li class="breadcrumb-item active">الجداول</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">توزيع المعلمين حسب التخصص</h5>
                </div>
                <div class="card-body">
                    @if($bySpecialization->isEmpty())
                        <p class="text-muted mb-0">لا توجد بيانات لعرضها.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($bySpecialization as $specialization => $count)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $specialization }}</span>
                                    <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">شعبية المواد الدراسية</h5>
                    <span class="badge bg-info">{{ $subjectsCloud->sum() }} مادة</span>
                </div>
                <div class="card-body">
                    @if($subjectsCloud->isEmpty())
                        <p class="text-muted mb-0">لم يتم تحديد مواد للمعلمين بعد.</p>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($subjectsCloud as $subject => $count)
                                <span class="badge bg-light text-dark border">{{ $subject }} <span class="text-muted">({{ $count }})</span></span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">ساعات التواجد وجدول الحصص</h5>
            <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-right"></i> الرجوع لقائمة المعلمين
            </a>
        </div>
        <div class="card-body">
            @if($teachers->isEmpty())
                <p class="text-muted mb-0">لا توجد بيانات متاحة حالياً.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>المعلم</th>
                                <th>التخصص</th>
                                <th>المواد</th>
                                <th>ساعات التواجد</th>
                                <th>رقم التواصل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->name }}</td>
                                    <td>{{ $teacher->teacherProfile?->specialization ?? '—' }}</td>
                                    <td>{{ $teacher->teacherProfile?->subjects_list ?? '—' }}</td>
                                    <td>{{ $teacher->teacherProfile?->office_hours ?? '—' }}</td>
                                    <td dir="ltr">{{ $teacher->phone ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

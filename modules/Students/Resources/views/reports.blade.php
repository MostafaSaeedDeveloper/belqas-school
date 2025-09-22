@extends('admin.layouts.master')

@section('title', 'تقارير الطلاب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الطلاب')
    @section('page-subtitle', 'نظرة عامة على التسجيلات والنشاط الحديث')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">التقارير</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon students"><i class="fas fa-user-graduate"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $statistics['total'] }}</div>
                    <div class="stat-label">إجمالي الطلاب</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-success">{{ $statistics['active'] }}</div>
                    <div class="stat-label">طلاب نشطون</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon teachers"><i class="fas fa-user-slash"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-danger">{{ $statistics['inactive'] }}</div>
                    <div class="stat-label">طلاب غير نشطين</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i> آخر الطلاب المسجلين</h5>
            <span class="text-muted">آخر {{ $recentStudents->count() }} طلاب</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>الطالب</th>
                            <th>البريد الإلكتروني</th>
                            <th>الحالة</th>
                            <th>تاريخ التسجيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentStudents as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $student->avatar_url }}" class="rounded-circle" width="36" height="36" alt="{{ $student->name }}">
                                        <div>
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <small class="text-muted" dir="ltr">{{ $student->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td dir="ltr">{{ $student->email ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $student->active ? 'bg-success' : 'bg-danger' }}">{{ $student->active ? 'نشط' : 'غير نشط' }}</span>
                                </td>
                                <td>{{ $student->created_at?->diffForHumans() ?? 'غير متوفر' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">لا توجد بيانات حديثة لعرضها.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i> ملاحظات إرشادية</h5>
        </div>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> قم بمتابعة الطلاب غير النشطين لتحديث بياناتهم أو إغلاق حساباتهم إن لزم.</li>
                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> يمكن تصدير تقارير تفصيلية لاحقاً عند إضافة تكامل مع Excel.</li>
                <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> استخدم هذه الصفحة لمراقبة التسجيلات الحديثة واتخاذ الإجراءات المناسبة.</li>
            </ul>
        </div>
    </div>
@endsection

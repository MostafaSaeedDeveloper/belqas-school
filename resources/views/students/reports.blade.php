@extends('admin.layouts.master')

@section('title', 'تقارير الطلاب')
@section('page-title', 'تقارير الطلاب')
@section('page-subtitle', 'متابعة الإحصائيات والتحليلات الخاصة بسجلات الطلاب')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
    <li class="breadcrumb-item active" aria-current="page">التقارير</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">تجهيز تقارير الطلاب</h5>
            <p class="text-muted">يمكنك لاحقاً ربط هذه الصفحة بتقارير الحضور، الدرجات، والمصاريف الخاصة بالطلاب.</p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 border rounded-3 bg-light">
                        <h6 class="fw-bold">عدد الطلاب المسجلين</h6>
                        <p class="display-6 mb-0">{{ \App\Models\Student::count() }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded-3">
                        <h6 class="fw-bold">الطلاب المقيدون</h6>
                        <p class="display-6 text-success mb-0">{{ \App\Models\Student::where('status', 'enrolled')->count() }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded-3">
                        <h6 class="fw-bold">الخريجون</h6>
                        <p class="display-6 text-info mb-0">{{ \App\Models\Student::where('status', 'graduated')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-4" role="alert">
                <i class="fas fa-info-circle ms-1"></i>
                يمكن تطوير هذه الصفحة لعرض تقارير مفصلة يتم سحبها من جداول الحضور، الدرجات، والرسوم فور توفرها.
            </div>
        </div>
    </div>
@endsection

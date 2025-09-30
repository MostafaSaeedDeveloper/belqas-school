@extends('admin.layouts.master')

@section('title', 'تقارير الطلاب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الطلاب')
    @section('page-subtitle', 'تحليلات التوزيع والالتحاق للطلاب')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">التقارير</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">توزيع الطلاب حسب النوع</h5>
                </div>
                <div class="card-body">
                    @if($genderDistribution->isEmpty())
                        <p class="text-muted mb-0">لا توجد بيانات كافية لعرض التوزيع.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($genderDistribution as $row)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $row->gender === 'male' ? 'طلاب' : 'طالبات' }}</span>
                                    <span class="badge bg-primary rounded-pill">{{ $row->total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">التحاق الطلاب خلال العام</h5>
                </div>
                <div class="card-body">
                    @if($enrollmentTrend->isEmpty())
                        <p class="text-muted mb-0">لم يتم تسجيل حالات التحاق بعد.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>الشهر</th>
                                        <th>عدد الطلاب الملتحقين</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollmentTrend as $row)
                                        <tr>
                                            <td>{{ $row['month'] }}</td>
                                            <td>{{ $row['total'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">توزيع الطلاب حسب الصفوف</h5>
            <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-right"></i> الرجوع لقائمة الطلاب
            </a>
        </div>
        <div class="card-body">
            @if($gradeDistribution->isEmpty())
                <p class="text-muted mb-0">لا توجد بيانات صفوف متاحة حالياً.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الصف الدراسي</th>
                                <th>عدد الطلاب</th>
                                <th>النسبة المئوية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($total = $gradeDistribution->sum('total'))
                            @foreach($gradeDistribution as $row)
                                <tr>
                                    <td>{{ $row->grade_level }}</td>
                                    <td>{{ $row->total }}</td>
                                    <td>{{ $total ? number_format(($row->total / $total) * 100, 1) : 0 }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@extends('admin.layouts.master')

@section('title', 'التقارير الأكاديمية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'التقارير الأكاديمية')
    @section('page-subtitle', 'تحليل أداء الطلاب حسب المواد والفصول')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">التقارير</a></li>
        <li class="breadcrumb-item active">الأكاديمية</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">متوسط الدرجات حسب المادة</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($averages as $row)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $row->subject->name }}</span>
                                <span class="badge bg-primary">{{ number_format($row->average, 1) }}%</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">لا توجد بيانات درجات متاحة.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">أفضل الطلاب أداءً</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($topPerformers as $student)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $student->student->name }}</span>
                                <span class="badge bg-success">{{ number_format($student->average, 1) }}%</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">لا توجد بيانات متاحة.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">طلاب بحاجة إلى متابعة</h5>
                    <small class="text-muted">أقل من 60%</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>متوسط الأداء</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($atRiskStudents as $student)
                                <tr>
                                    <td>{{ $student->student->name }}</td>
                                    <td><span class="badge bg-danger">{{ number_format($student->average, 1) }}%</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">لا توجد حالات تعثر ضمن المعايير الحالية.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.master')

@section('title', 'التقارير المالية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'التقارير المالية')
    @section('page-subtitle', 'تحليل التحصيل والمصروفات التشغيلية')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">التقارير</a></li>
        <li class="breadcrumb-item active">المالية</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">المستهدف من الرسوم</h6>
                    <div class="display-6 fw-bold text-primary">{{ number_format($settings['tuition_target'], 2) }} ج.م</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">التحصيل الفعلي</h6>
                    <div class="display-6 fw-bold text-success">{{ number_format($settings['tuition_collected'], 2) }} ج.م</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">نسبة الإنجاز</h6>
                    <div class="display-6 fw-bold">{{ number_format($performance['collectionRate'], 1) }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">صافي الميزانية</h5>
                </div>
                <div class="card-body">
                    <p class="fs-4 fw-bold {{ $performance['netBalance'] >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($performance['netBalance'], 2) }} ج.م
                    </p>
                    <p class="text-muted mb-0">الفرق بين التحصيل الفعلي والمصروفات المسجلة.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">تفصيل المصروفات</h5>
                </div>
                <div class="card-body">
                    @if($expenseBreakdown->isEmpty())
                        <p class="text-muted mb-0">لم يتم تسجيل مصروفات تفصيلية.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($expenseBreakdown as $label => $amount)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $label }}</span>
                                    <span class="badge bg-secondary">{{ number_format($amount, 2) }} ج.م</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.master')

@section('title', 'تقارير الحضور - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الحضور')
    @section('page-subtitle', 'تحليلات تفصيلية عن معدلات الحضور والغياب')
    @section('breadcrumb')
        <li class="breadcrumb-item">الحضور</li>
        <li class="breadcrumb-item active">تقارير الحضور</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-chart-line',
        'title' => 'تقارير الحضور ستظهر هنا',
        'description' => 'سيتم عرض الرسوم البيانية والجداول التفصيلية للحضور اليومي والأسبوعي والشهري للطلاب.',
        'tips' => [
            'قم بتحديد الفترة الزمنية والفصل الدراسي للحصول على نتائج أكثر دقة.',
            'يمكن مقارنة نتائج الحضور بين الفصول المختلفة لاكتشاف الأنماط مبكراً.',
            'يوفر النظام خاصية تصدير التقارير إلى PDF أو Excel لمشاركتها مع الإدارة.',
        ],
    ])
@endsection

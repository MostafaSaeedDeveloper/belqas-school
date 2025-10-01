@extends('admin.layouts.master')

@section('title', 'التقارير الأكاديمية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'التقارير الأكاديمية')
    @section('page-subtitle', 'قياس مستويات التحصيل الدراسي للطلاب')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">التقارير</a></li>
        <li class="breadcrumb-item active">التقارير الأكاديمية</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-graduation-cap',
        'title' => 'تحليلات الأداء الأكاديمي',
        'description' => 'سيتم عرض مؤشرات النجاح والتقدم ومقارنات الأداء بين الفصول والمعلمين.',
        'tips' => [
            'استخدم الرسوم البيانية لمراقبة نسب النجاح والتفوق.',
            'تعرف على الطلاب المحتاجين للدعم الإضافي من خلال مؤشرات التحذير المبكر.',
            'قارن الأداء بين الأعوام الدراسية لتقييم فعالية الخطط التربوية.',
        ],
    ])
@endsection

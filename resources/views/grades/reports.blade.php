@extends('admin.layouts.master')

@section('title', 'تقارير الدرجات - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الدرجات')
    @section('page-subtitle', 'مؤشرات الأداء الأكاديمي للطلاب')
    @section('breadcrumb')
        <li class="breadcrumb-item">الدرجات</li>
        <li class="breadcrumb-item active">تقارير الدرجات</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-chart-column',
        'title' => 'لوحة تقارير الدرجات',
        'description' => 'سيتم عرض متوسطات الدرجات ومعدلات التفوق والتحسين لكل صف ومادة.',
        'tips' => [
            'قارن بين نتائج الفصول المختلفة لتحديد نقاط القوة وفرص التطوير.',
            'استخدم المرشحات لاختيار العام الدراسي والفصل الدراسي المطلوب.',
            'يمكن تصدير التقارير ومشاركتها مع أولياء الأمور بسهولة.',
        ],
    ])
@endsection

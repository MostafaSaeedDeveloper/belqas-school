@extends('admin.layouts.master')

@section('title', 'المالية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الإدارة المالية')
    @section('page-subtitle', 'متابعة الموارد المالية والالتزامات الدراسية')
    @section('breadcrumb')
        <li class="breadcrumb-item active">المالية</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-coins',
        'title' => 'لوحة التحكم المالية',
        'description' => 'سيتم عرض ملخص شامل للرسوم المدرسية والمدفوعات وسجلات المصروفات.',
        'tips' => [
            'تابع الذمم المالية المتأخرة وحدد أولويات التحصيل.',
            'يمكن ربط العمليات المالية مع إشعارات البريد الإلكتروني والرسائل القصيرة.',
            'استعرض التدفقات النقدية الشهرية لمراقبة صحة الوضع المالي.',
        ],
        'actions' => [
            [
                'label' => 'إدارة الرسوم الدراسية',
                'href' => route('finance.fees'),
                'icon' => 'fas fa-receipt',
            ],
            [
                'label' => 'سجل المدفوعات',
                'href' => route('finance.payments'),
                'style' => 'outline-secondary',
                'icon' => 'fas fa-wallet',
            ],
        ],
    ])
@endsection

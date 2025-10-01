@extends('admin.layouts.master')

@section('title', 'الحضور اليومي - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الحضور اليومي')
    @section('page-subtitle', 'تسجيل وتتبع حضور الطلاب في الوقت الفعلي')
    @section('breadcrumb')
        <li class="breadcrumb-item">الحضور</li>
        <li class="breadcrumb-item active">الحضور اليومي</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-user-check',
        'title' => 'شاشة الحضور اليومية جاهزة للعمل',
        'description' => 'سيتم هنا إدارة حضور الطلاب لكل فصل دراسي مع إمكانية البحث السريع وتسجيل الحالات الخاصة.',
        'tips' => [
            'استخدم فلاتر البحث لاختيار الفصل أو الصف الدراسي قبل البدء بالتسجيل.',
            'يمكن تسجيل حالات الغياب مع توضيح السبب وإرسال إشعارات لأولياء الأمور.',
            'سيتم حفظ سجل الحضور تلقائياً مع إتاحة التصدير بتنسيقات متعددة.',
        ],
        'actions' => [
            [
                'label' => 'العودة للوحة التحكم',
                'href' => route('dashboard'),
                'style' => 'outline-secondary',
                'icon' => 'fas fa-arrow-right'
            ],
        ],
    ])
@endsection

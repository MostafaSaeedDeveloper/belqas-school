@extends('admin.layouts.master')

@section('title', 'سجل النشاط - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'سجل نشاط النظام')
    @section('page-subtitle', 'متابعة العمليات التي تمت داخل النظام')
    @section('breadcrumb')
        <li class="breadcrumb-item active">سجل النشاط</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-list-check',
        'title' => 'متابعة نشاط المستخدمين',
        'description' => 'سيوفر هذا القسم تفاصيل العمليات المنفذة داخل النظام مع إمكانية البحث والتصفية.',
        'tips' => [
            'يمكن تتبع التعديلات الحساسة ومعرفة المستخدم المنفذ للعملية.',
            'استخدم المرشحات الزمنية للوصول إلى الأحداث المهمة بسرعة.',
            'صدّر تقارير النشاط لمشاركتها مع مسؤولي الأمن التقني.',
        ],
    ])
@endsection

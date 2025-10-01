@extends('admin.layouts.master')

@section('title', 'إدخال الدرجات - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدخال الدرجات')
    @section('page-subtitle', 'إدارة درجات الطلاب لكل مادة وفصل')
    @section('breadcrumb')
        <li class="breadcrumb-item">الدرجات</li>
        <li class="breadcrumb-item active">إدخال الدرجات</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-marker',
        'title' => 'تسجيل درجات الطلاب',
        'description' => 'من خلال هذه الصفحة سيتمكن المعلمون من إدخال درجات الاختبارات والأعمال الفصلية بسهولة.',
        'tips' => [
            'اختر الصف والمادة لتظهر قائمة الطلاب فوراً.',
            'يدعم النظام إدخال درجات التقييم المستمر مع إمكانية إضافة ملاحظات لكل طالب.',
            'يمكن حفظ التعديلات بشكل جزئي والعودة لاحقاً لاستكمال الإدخال.',
        ],
    ])
@endsection

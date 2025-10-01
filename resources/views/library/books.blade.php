@extends('admin.layouts.master')

@section('title', 'قائمة الكتب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'قائمة الكتب')
    @section('page-subtitle', 'عرض وإدارة الكتب المتاحة في المكتبة')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('library.index') }}">المكتبة</a></li>
        <li class="breadcrumb-item active">قائمة الكتب</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-books',
        'title' => 'إدارة عناوين الكتب',
        'description' => 'سيظهر هنا جدول الكتب مع تفاصيل المؤلف، التصنيف، عدد النسخ المتاحة، وحالة الاستعارة.',
        'tips' => [
            'استخدم المرشحات للبحث حسب المؤلف أو التصنيف أو سنة النشر.',
            'يمكن إضافة نسخ جديدة أو تحديث حالة الكتاب مباشرة من الجدول.',
            'تابع الطلبات المعلقة لحجز الكتب وتأكيدها بنقرة واحدة.',
        ],
    ])
@endsection

@extends('admin.layouts.master')

@section('title', 'إعارة الكتب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إعارة الكتب')
    @section('page-subtitle', 'تسجيل عمليات الإعارة والإرجاع للطلاب والمعلمين')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('library.index') }}">المكتبة</a></li>
        <li class="breadcrumb-item active">إعارة الكتب</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-book-reader',
        'title' => 'إدارة عمليات الإعارة',
        'description' => 'سيتم توفير نموذج سريع لإعارة الكتب وتسجيل المواعيد النهائية للتسليم مع إشعارات تلقائية.',
        'tips' => [
            'تحقق من توافر الكتاب قبل الموافقة على الطلب.',
            'أرسل تنبيهاً آلياً قبل موعد الإرجاع بيومين لتجنب التأخير.',
            'سجل حالة الكتاب عند الإرجاع ودوّن أي ملاحظات حول التلف أو الفقد.',
        ],
    ])
@endsection

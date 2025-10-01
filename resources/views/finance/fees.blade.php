@extends('admin.layouts.master')

@section('title', 'الرسوم الدراسية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة الرسوم الدراسية')
    @section('page-subtitle', 'تعريف باقات الرسوم وجدولة التحصيل')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
        <li class="breadcrumb-item active">الرسوم الدراسية</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-file-invoice-dollar',
        'title' => 'تفاصيل الرسوم الدراسية',
        'description' => 'قم بإدارة خطط الرسوم، الأقساط، والخصومات الخاصة بالطلاب.',
        'tips' => [
            'أنشئ خطط رسوم مختلفة بحسب الصف أو البرنامج الدراسي.',
            'فعّل الإشعارات الآلية لتذكير أولياء الأمور بمواعيد السداد.',
            'يمكن تسجيل المنح الجزئية أو الكاملة وتوثيق موافقات الإدارة.',
        ],
    ])
@endsection

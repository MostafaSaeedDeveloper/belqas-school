@extends('admin.layouts.master')

@section('title', 'سجلات المدفوعات - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'سجل المدفوعات')
    @section('page-subtitle', 'متابعة إيصالات التحصيل والمدفوعات اليدوية والإلكترونية')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
        <li class="breadcrumb-item active">سجل المدفوعات</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-wallet',
        'title' => 'إدارة المدفوعات',
        'description' => 'سيتم عرض جميع عمليات السداد مع إمكانية البحث والتصفية وطباعة الإيصالات.',
        'tips' => [
            'يمكن تسجيل المدفوعات النقدية أو الإلكترونية وربطها بالفواتير.',
            'تابع حالات المبالغ المتأخرة وحدد خططاً للتحصيل.',
            'احفظ نسخة من الإيصالات الرسمية وشاركها مع أولياء الأمور عند الحاجة.',
        ],
    ])
@endsection

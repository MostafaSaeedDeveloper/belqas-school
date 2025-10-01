@extends('admin.layouts.master')

@section('title', 'النسخ الاحتياطي للنظام - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة النسخ الاحتياطي')
    @section('page-subtitle', 'حماية البيانات من خلال النسخ الاحتياطية المجدولة')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('settings.general') }}">الإعدادات</a></li>
        <li class="breadcrumb-item active">النسخ الاحتياطي</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-database',
        'title' => 'إعداد النسخ الاحتياطية',
        'description' => 'ستتمكن من جدولة النسخ الاحتياطية ومتابعة حالتها وتنزيلها عند الحاجة.',
        'tips' => [
            'حدد تكرار النسخ الاحتياطي بحسب أهمية البيانات وتحديثها.',
            'قم بتنزيل نسخة خارجية بصفة دورية للحفاظ على سلامة البيانات.',
            'تتبع سجل النسخ لمعرفة آخر العمليات الناجحة وأي أخطاء محتملة.',
        ],
    ])
@endsection

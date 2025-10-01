@extends('admin.layouts.master')

@section('title', 'الإعدادات العامة - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الإعدادات العامة')
    @section('page-subtitle', 'تهيئة البيانات الرئيسية والهوية المؤسسية للنظام')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الإعدادات</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-sliders',
        'title' => 'ضبط الإعدادات العامة',
        'description' => 'سيتم توفير واجهة متكاملة لتحديث معلومات المدرسة، الشعار، وأوقات العمل.',
        'tips' => [
            'أدخل بيانات التواصل الرسمية ليتم استخدامها في التقارير والإشعارات.',
            'يمكن التحكم في إعدادات العام الدراسي وتنسيقات التاريخ من هنا.',
            'حدّث هوية النظام لتظهر في جميع التقارير والمستندات الرسمية.',
        ],
    ])
@endsection

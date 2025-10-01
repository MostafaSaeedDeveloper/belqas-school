@extends('admin.layouts.master')

@section('title', 'فعاليات المدرسة - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة الفعاليات والإشعارات')
    @section('page-subtitle', 'تنظيم الفعاليات والأنشطة وتوزيع الإشعارات الرسمية')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الفعاليات</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-bullhorn',
        'title' => 'إدارة الفعاليات المدرسية',
        'description' => 'سيتم هنا عرض الفعاليات القادمة، الجارية، والمنتهية مع تفاصيل الدعوات والإشعارات.',
        'tips' => [
            'أضف تفاصيل الفعالية مثل التاريخ والموقع والفئة المستهدفة.',
            'أرسل دعوات مخصصة للطلاب والمعلمين وأولياء الأمور.',
            'تابع التقارير اللاحقة للفعاليات لقياس نسبة التفاعل والحضور.',
        ],
    ])
@endsection

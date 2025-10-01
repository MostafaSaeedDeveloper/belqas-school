@extends('admin.layouts.master')

@section('title', 'إدارة المكتبة - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'المكتبة المدرسية')
    @section('page-subtitle', 'متابعة الكتب والمقتنيات وإجراءات الإعارة')
    @section('breadcrumb')
        <li class="breadcrumb-item active">المكتبة</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-book-open',
        'title' => 'لوحة إدارة المكتبة',
        'description' => 'ستتمكن من متابعة المخزون، المتأخرات، وجدول الحجوزات الخاصة بالمكتبة.',
        'tips' => [
            'قم بإضافة الكتب الجديدة مع تفاصيل النسخ المتاحة.',
            'تابع طلبات الاستعارة والتمديد وإرسال التنبيهات في حال التأخر.',
            'يمكن تنظيم قوائم القراءة الموصى بها وربطها بالمواد الدراسية.',
        ],
        'actions' => [
            [
                'label' => 'قائمة الكتب',
                'href' => route('library.books'),
                'icon' => 'fas fa-layer-group',
            ],
        ],
    ])
@endsection

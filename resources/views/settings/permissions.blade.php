@extends('admin.layouts.master')

@section('title', 'صلاحيات الوصول - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة الصلاحيات')
    @section('page-subtitle', 'تحديد أدوار المستخدمين وتوزيع الأذونات بدقة')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('settings.general') }}">الإعدادات</a></li>
        <li class="breadcrumb-item active">الصلاحيات</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-user-shield',
        'title' => 'لوحة إدارة الصلاحيات',
        'description' => 'اضبط الأدوار، الصلاحيات، والمجموعات الخاصة لضمان الوصول الآمن للمعلومات.',
        'tips' => [
            'قم بمراجعة صلاحيات كل دور بشكل دوري للحفاظ على أمان البيانات.',
            'يمكن إنشاء أدوار مخصصة بحسب متطلبات الأقسام المختلفة.',
            'استفد من سجل النشاط لمعرفة آخر التغييرات التي تمت على الأذونات.',
        ],
        'actions' => [
            [
                'label' => 'إدارة المستخدمين',
                'href' => route('users.index'),
                'icon' => 'fas fa-users-cog',
            ],
        ],
    ])
@endsection

@extends('admin.layouts.master')

@section('title', 'الملف الشخصي - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'ملفي الشخصي')
    @section('page-subtitle', 'استعراض معلومات الحساب وتاريخ النشاط')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الملف الشخصي</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-id-card',
        'title' => 'معلومات الحساب الشخصي',
        'description' => 'سيتم عرض بيانات المستخدم، الأدوار، وسجل آخر الأنشطة في هذه الصفحة.',
        'tips' => [
            'تأكد من تحديث بيانات التواصل الخاصة بك بشكل دوري.',
            'يمكنك مراجعة الصلاحيات الممنوحة لك ومعرفة مصدر كل صلاحية.',
            'استعرض سجل الدخول لمعرفة آخر الأجهزة التي استخدمت حسابك.',
        ],
        'actions' => [
            [
                'label' => 'تعديل الملف الشخصي',
                'href' => route('profile.edit'),
                'icon' => 'fas fa-user-edit',
            ],
        ],
    ])
@endsection

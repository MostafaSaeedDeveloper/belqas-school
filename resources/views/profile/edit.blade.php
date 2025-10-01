@extends('admin.layouts.master')

@section('title', 'تعديل الملف الشخصي - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تعديل الملف الشخصي')
    @section('page-subtitle', 'تحديث بيانات الحساب وكلمة المرور')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">الملف الشخصي</a></li>
        <li class="breadcrumb-item active">تعديل</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-user-cog',
        'title' => 'تحديث بيانات الحساب',
        'description' => 'سيتم توفير نموذج لتعديل بيانات الاتصال، الصورة الشخصية، وكلمة المرور الخاصة بك.',
        'tips' => [
            'استخدم كلمة مرور قوية تحتوي على حروف وأرقام ورموز.',
            'قم بتحديث الصورة الشخصية لتعكس هويتك داخل النظام.',
            'راجع بيانات التواصل لضمان تلقيك الإشعارات المهمة.',
        ],
        'actions' => [
            [
                'label' => 'عرض الملف الشخصي',
                'href' => route('profile.show'),
                'style' => 'outline-secondary',
                'icon' => 'fas fa-id-card',
            ],
        ],
    ])
@endsection

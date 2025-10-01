@extends('admin.layouts.master')

@section('title', 'إضافة امتحان - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إضافة امتحان جديد')
    @section('page-subtitle', 'تهيئة بيانات الامتحان وتحديد مواعيده')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">الامتحانات</a></li>
        <li class="breadcrumb-item active">إضافة امتحان</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-calendar-plus',
        'title' => 'نموذج إنشاء الامتحان',
        'description' => 'سيتم هنا إدخال تفاصيل الامتحان مثل المادة والفصل الزمني والقاعات المشمولة.',
        'tips' => [
            'حدد المادة الدراسية والصف المستهدف لضمان ظهور الامتحان للطلاب الصحيحين.',
            'يمكن إضافة أكثر من جلسة امتحانية وتحديد لجنة المراقبة لكل جلسة.',
            'لا تنسَ تفعيل التنبيهات لإبلاغ الطلاب وأولياء الأمور بالمواعيد.',
        ],
        'actions' => [
            [
                'label' => 'الرجوع لقائمة الامتحانات',
                'href' => route('exams.index'),
                'style' => 'outline-secondary',
                'icon' => 'fas fa-arrow-right',
            ],
        ],
    ])
@endsection

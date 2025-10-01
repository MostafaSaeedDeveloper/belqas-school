@extends('admin.layouts.master')

@section('title', 'إدارة الامتحانات - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الامتحانات')
    @section('page-subtitle', 'تنظيم الجداول ومتابعة لجان الامتحانات')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الامتحانات</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-file-pen',
        'title' => 'إدارة الامتحانات',
        'description' => 'سيتم عرض قوائم الامتحانات القادمة والمنعقدة مع إمكانية إدارة اللجان والمراقبين.',
        'tips' => [
            'قم بإنشاء جدول الامتحانات وتحديد المواد والفصول المعنية.',
            'يمكن ربط كل امتحان بقاعات محددة وإرسال تنبيهات للطلاب.',
            'تابع حالة تسليم أوراق الأسئلة ورفع محاضر الغياب والمخالفات.',
        ],
        'actions' => [
            [
                'label' => 'إضافة امتحان جديد',
                'href' => route('exams.create'),
                'icon' => 'fas fa-plus',
            ],
        ],
    ])
@endsection

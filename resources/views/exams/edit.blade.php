@extends('admin.layouts.master')

@section('title', 'تعديل الامتحان - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تعديل الامتحان')
    @section('page-subtitle', 'تحديث بيانات الامتحان وجدوله الزمني')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">الامتحانات</a></li>
        <li class="breadcrumb-item active">تعديل الامتحان</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-pen-to-square',
        'title' => 'نموذج تعديل الامتحان',
        'description' => 'سيتيح لك هذا النموذج تحديث مواعيد الامتحان، القاعات، والتكليفات المرتبطة بالمعلمين.',
        'tips' => [
            'يمكن تعديل خطة توزيع اللجان والمراقبين بسهولة.',
            'تأكد من تحديث الملاحظات والتعليمات الخاصة بكل لجنة قبل الحفظ.',
            'سيتم إخطار الطلاب تلقائياً في حال تغيير موعد الامتحان.',
        ],
        'actions' => [
            [
                'label' => 'مشاهدة تفاصيل الامتحان',
                'href' => route('exams.show', ['exam' => request()->route('exam')]),
                'style' => 'outline-secondary',
                'icon' => 'fas fa-file-lines',
            ],
        ],
    ])
@endsection

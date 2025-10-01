@extends('admin.layouts.master')

@section('title', 'تفاصيل الامتحان - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تفاصيل الامتحان')
    @section('page-subtitle', 'استعراض بيانات الامتحان والتكليفات المرتبطة')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">الامتحانات</a></li>
        <li class="breadcrumb-item active">تفاصيل الامتحان</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-file-lines',
        'title' => 'تفاصيل الامتحان',
        'description' => 'سيتم إظهار بيانات الامتحان المختار مع توزيع الطلاب واللجان وأعمال المراقبة.',
        'tips' => [
            'يمكن تحميل أوراق الأسئلة والنماذج من خلال هذه الصفحة عند توفرها.',
            'يظهر سجل المخالفات والملاحظات التي تم تسجيلها أثناء الامتحان.',
            'ستجد ملخصاً لدرجات الطلاب فور إدخالها من خلال المعلمين.',
        ],
        'actions' => [
            [
                'label' => 'تعديل بيانات الامتحان',
                'href' => route('exams.edit', ['exam' => request()->route('exam')]),
                'icon' => 'fas fa-edit',
            ],
            [
                'label' => 'الرجوع للقائمة',
                'href' => route('exams.index'),
                'style' => 'outline-secondary',
                'icon' => 'fas fa-list',
            ],
        ],
    ])
@endsection

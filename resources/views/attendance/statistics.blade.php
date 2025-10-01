@extends('admin.layouts.master')

@section('title', 'إحصائيات الحضور - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إحصائيات الحضور')
    @section('page-subtitle', 'مؤشرات ورسوم بيانية لمتابعة الالتزام اليومي')
    @section('breadcrumb')
        <li class="breadcrumb-item">الحضور</li>
        <li class="breadcrumb-item active">إحصائيات الحضور</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-chart-pie',
        'title' => 'لوحة مؤشرات الحضور',
        'description' => 'ستتوفر هنا لوحة تحكم تفاعلية تتضمن نسب الحضور حسب الفصول والصفوف والفترات الزمنية.',
        'tips' => [
            'يمكن عرض أعلى الصفوف التزاماً بالحضور مع مقارنة مباشرة.',
            'يتم تحديث البيانات بشكل دوري لإبقاء المعلومات محدثة.',
            'استعرض بيانات الغياب المتكرر لإعداد خطط علاجية للطلاب.',
        ],
    ])
@endsection

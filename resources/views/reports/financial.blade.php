@extends('admin.layouts.master')

@section('title', 'التقارير المالية التفصيلية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'التقارير المالية التفصيلية')
    @section('page-subtitle', 'تحليل الإيرادات والمصروفات والتزامات الرسوم')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">التقارير</a></li>
        <li class="breadcrumb-item active">التقارير المالية</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-sack-dollar',
        'title' => 'التقارير المالية التفصيلية',
        'description' => 'ستتوفر هنا البيانات المالية الدقيقة مع إمكانية تحليل الأداء حسب الأقسام أو الفترات الزمنية.',
        'tips' => [
            'راجع الإيرادات المتحققة مقابل المصروفات لمراقبة الربحية.',
            'حدد الطلاب المتأخرين عن السداد لمتابعتهم بسرعة.',
            'استفد من الرسوم البيانية لمتابعة التدفق النقدي المتوقع.',
        ],
    ])
@endsection

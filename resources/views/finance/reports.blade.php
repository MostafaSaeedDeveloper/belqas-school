@extends('admin.layouts.master')

@section('title', 'التقارير المالية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'التقارير المالية')
    @section('page-subtitle', 'تحليل الأداء المالي والإيرادات والمصروفات')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
        <li class="breadcrumb-item active">التقارير المالية</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-chart-area',
        'title' => 'لوحة التقارير المالية',
        'description' => 'ستظهر الرسوم البيانية والتقارير التفصيلية لمتابعة الإيرادات والمصروفات والالتزامات المستقبلية.',
        'tips' => [
            'قارن بين الأداء المالي الشهري والسنوي لتحديد الاتجاه العام.',
            'استخدم مؤشرات الربحية والسيولة لقياس الاستدامة المالية.',
            'صدّر التقارير وشاركها مع مجلس الإدارة عند الحاجة.',
        ],
    ])
@endsection

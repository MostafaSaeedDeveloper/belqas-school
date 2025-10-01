@extends('admin.layouts.master')

@section('title', 'التقارير الشاملة - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'مركز التقارير')
    @section('page-subtitle', 'الوصول السريع إلى تقارير الأداء المختلفة')
    @section('breadcrumb')
        <li class="breadcrumb-item active">التقارير</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-chart-simple',
        'title' => 'لوحة مركز التقارير',
        'description' => 'اختر نوع التقرير الذي ترغب في استعراضه للحصول على معلومات دقيقة ومحدثة.',
        'tips' => [
            'استعرض تقارير الأداء الأكاديمي، الحضور، والمالية من مكان واحد.',
            'يمكنك حفظ إعدادات التصفية المفضلة لإعادة استخدامها لاحقاً.',
            'يتيح النظام مشاركة التقارير بشكل آمن مع الإدارة العليا.',
        ],
        'actions' => [
            [
                'label' => 'التقارير الأكاديمية',
                'href' => route('reports.academic'),
                'icon' => 'fas fa-graduation-cap',
            ],
            [
                'label' => 'تقارير الحضور',
                'href' => route('reports.attendance'),
                'style' => 'outline-secondary',
                'icon' => 'fas fa-user-clock',
            ],
        ],
    ])
@endsection

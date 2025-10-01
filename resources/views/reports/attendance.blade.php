@extends('admin.layouts.master')

@section('title', 'تقارير الحضور العامة - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الحضور العامة')
    @section('page-subtitle', 'مؤشرات الالتزام ومقارنات الحضور بين الأقسام المختلفة')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">التقارير</a></li>
        <li class="breadcrumb-item active">تقارير الحضور</li>
    @endsection
@endsection

@section('content')
    @include('admin.shared.placeholder', [
        'icon' => 'fas fa-user-clock',
        'title' => 'تحليلات الحضور الشاملة',
        'description' => 'يعرض هذا القسم البيانات المجمعة لمعدلات الحضور على مستوى المدرسة بالكامل.',
        'tips' => [
            'حدد الفترة الزمنية والمرحلة الدراسية لعرض الإحصاءات المناسبة.',
            'قارن بين الأقسام المختلفة لمعرفة الفوارق في الالتزام.',
            'استخرج التقارير التفصيلية لإرسالها إلى الجهات المعنية.',
        ],
    ])
@endsection

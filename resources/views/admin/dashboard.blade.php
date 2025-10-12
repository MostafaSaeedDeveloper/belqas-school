@extends('admin.layouts.master')

@php
    $dashboardRole = $role ?? 'admin';
    $pageTitle = match ($dashboardRole) {
        'teacher' => 'لوحة المعلم',
        'student' => 'لوحة الطالب',
        default => 'لوحة التحكم',
    };
    $pageSubtitle = match ($dashboardRole) {
        'teacher' => 'متابعة الصفوف والطلاب المكلف بهم',
        'student' => 'متابعة الدروس والحضور والنتائج الخاصة بك',
        default => 'نظرة شاملة على أداء المدرسة',
    };
@endphp

@section('title', $pageTitle . ' - نظام إدارة مدرسة بلقاس')

@section('page-header')
    @section('page-title')
        {{ $pageTitle }}
    @endsection

    @section('page-subtitle')
        {{ $pageSubtitle }}
    @endsection

    @section('breadcrumb')
        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
    @endsection
@endsection

@section('content')
    @switch($dashboardRole)
        @case('teacher')
            @include('admin.dashboard.partials.teacher')
            @break

        @case('student')
            @include('admin.dashboard.partials.student')
            @break

        @default
            @include('admin.dashboard.partials.admin')
    @endswitch
@endsection

@php
    $bootstrapPayload = [
        'role' => $dashboardRole,
        'stats' => $stats ?? [],
        'attendanceSummary' => $attendanceSummary ?? [],
        'studentTrend' => $studentTrend ?? [],
        'attendanceTrend' => isset($attendanceTrend)
            ? ($attendanceTrend instanceof \Illuminate\Support\Collection
                ? $attendanceTrend->values()->toArray()
                : (array) $attendanceTrend)
            : [],
        'studentGrowth' => isset($studentGrowth)
            ? ($studentGrowth instanceof \Illuminate\Support\Collection
                ? $studentGrowth->values()->toArray()
                : (array) $studentGrowth)
            : [],
        'announcements' => $announcementFeed ?? [],
    ];
@endphp

@push('inline-scripts')
    <script>
        window.dashboardBootstrap = {!! json_encode($bootstrapPayload) !!};
    </script>
@endpush

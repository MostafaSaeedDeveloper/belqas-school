@extends('admin.layouts.master')

@section('title', 'إضافة رسالة جديدة')

@section('page-header')
@section('page-title', 'الأخبار والإعلانات')
@section('page-subtitle', 'إنشاء خبر أو إعلان جديد للمجتمع المدرسي')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">الأخبار والإعلانات</a></li>
    <li class="breadcrumb-item active">إضافة جديدة</li>
@endsection
@endsection

@section('content')
@include('admin.announcements.partials.form', [
    'announcement' => $announcement,
    'action' => route('announcements.store'),
    'method' => 'POST',
    'submitLabel' => 'نشر الرسالة',
])
@endsection

@extends('admin.layouts.master')

@section('title', 'تعديل الرسالة')

@section('page-header')
@section('page-title', 'الأخبار والإعلانات')
@section('page-subtitle', 'تحديث محتوى الخبر أو الإعلان المنشور')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">الأخبار والإعلانات</a></li>
    <li class="breadcrumb-item active">تعديل</li>
@endsection
@endsection

@section('content')
@include('admin.announcements.partials.form', [
    'announcement' => $announcement,
    'action' => route('announcements.update', $announcement),
    'method' => 'PUT',
    'submitLabel' => 'حفظ التعديلات',
])
@endsection

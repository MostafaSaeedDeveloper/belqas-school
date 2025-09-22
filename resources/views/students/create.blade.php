@extends('admin.layouts.master')

@section('title', 'إضافة طالب')
@section('page-title', 'إضافة طالب جديد')
@section('page-subtitle', 'قم بتسجيل جميع بيانات الطالب الأساسية والأكاديمية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
    <li class="breadcrumb-item active" aria-current="page">إضافة جديد</li>
@endsection

@section('content')
    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        @include('students.partials.form')
    </form>
@endsection

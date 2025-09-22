@extends('admin.layouts.master')

@section('title', 'تعديل بيانات الفصل')
@section('page-title', 'تعديل الفصل الدراسي')
@section('page-subtitle', 'تحديث بيانات الفصل والشعب المرتبطة به')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الفصول الدراسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('classes.show', $class) }}">{{ $class->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">تعديل</li>
@endsection

@section('content')
    <form action="{{ route('classes.update', $class) }}" method="POST">
        @csrf
        @method('PUT')

        @include('classes.partials.form')

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('classes.show', $class) }}" class="btn btn-link text-decoration-none">
                <i class="fas fa-arrow-right ms-1"></i>
                العودة لصفحة الفصل
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('classes.index') }}" class="btn btn-light">إلغاء</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save ms-1"></i>
                    حفظ التغييرات
                </button>
            </div>
        </div>
    </form>
@endsection

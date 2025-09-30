@extends('admin.layouts.master')

@section('title', 'تسجيل طالب جديد - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تسجيل طالب جديد')
    @section('page-subtitle', 'إضافة بيانات طالب جديد إلى النظام')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">تسجيل طالب</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">بيانات الطالب الأساسية</h5>
            <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @include('students::partials.form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('students.index') }}" class="btn btn-light">إلغاء</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
